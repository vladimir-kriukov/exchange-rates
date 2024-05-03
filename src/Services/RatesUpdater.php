<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Rate as RateDto;
use App\Entity\Rate;
use App\Entity\Rate as RateEntity;
use Brick\Math\BigDecimal;
use Brick\Math\Exception\DivisionByZeroException;
use Brick\Math\Exception\MathException;
use Brick\Math\Exception\NumberFormatException;
use Brick\Math\RoundingMode;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Exception\OutOfBoundsException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Rates updater
 */
class RatesUpdater
{
    public function __construct(private readonly iterable $ratesProviders, private readonly ValidatorInterface $validator, private readonly ManagerRegistry $doctrine)
    {
    }

    /**
     * Get currency rates from providers and save into rates table.
     *
     * @throws OutOfBoundsException|MathException
     */
    public function __invoke(): void
    {
        $rates = [];

        foreach ($this->ratesProviders as $ratesProvider) {
            $rates[] = ($ratesProvider)();
        }

        $rates = array_merge(...$rates);
        $this->save($rates);
    }

    /**
     * Save rates to the database.
     *
     * @param RateDto[] $rates
     *
     * @throws OutOfBoundsException
     * @throws MathException
     */
    protected function save(array $rates): void
    {
        $entityManager = $this->doctrine->getManager();
        $validator = $this->validator;

        array_walk($rates, function (RateDto $rateDto) use ($entityManager, $validator): void {
            $this->updateDirectRate($rateDto, $validator);
            $this->updateReverseRate($rateDto, $validator);
        });

        $entityManager->flush();
    }

    /**
     * Update existing direct exchange rate.
     */
    private function updateDirectRate(RateDto $rateDto, ValidatorInterface $validator): void
    {
        $entityManager = $this->doctrine->getManager();
        $rate = $this->doctrine->getManager()->getRepository(Rate::class)->findOneBy(
            [
                'base' => $rateDto->base,
                'currency' => $rateDto->currency,
            ]
        );

        if ($rate instanceof Rate) {
            $rate->setRate($rateDto->rate);
            $rate->setDate($rateDto->date);
        } else {
            $rate = $this->makeDirectRate($rateDto, $validator);
            $entityManager->persist($rate);
        }
    }

    /**
     * Update existing direct exchange rate.
     *
     * @throws MathException
     */
    private function updateReverseRate(RateDto $rateDto, ValidatorInterface $validator): void
    {
        $entityManager = $this->doctrine->getManager();
        $rate = $entityManager->getRepository(Rate::class)->findOneBy(
            [
                'base' => $rateDto->currency,
                'currency' => $rateDto->base,
            ]
        );

        if ($rate instanceof Rate) {
            $rateValue = $this->calculateReverseRate($rateDto->rate);
            $rate->setRate($rateValue);
            $rate->setDate($rateDto->date);
        } else {
            $rate = $this->makeReverseRate($rateDto, $validator);
            $entityManager->persist($rate);
        }
    }

    /**
     * Direct exchange rate.
     *
     * @throws OutOfBoundsException
     */
    private function makeDirectRate(RateDto $rateDto, ValidatorInterface $validator): RateEntity
    {
        $rate = new RateEntity();
        $rate->setBase($rateDto->base);
        $rate->setDate($rateDto->date);
        $rate->setRate($rateDto->rate);
        $rate->setCurrency($rateDto->currency);
        $errors = $validator->validate($rate);

        if (count($errors) > 0) {
            throw new OutOfBoundsException((string)$errors);
        }

        return $rate;
    }

    /**
     * Reverse exchange rate.
     *
     * @throws OutOfBoundsException
     * @throws MathException
     */
    private function makeReverseRate(RateDto $rateDto, ValidatorInterface $validator): RateEntity
    {
        $rateValue = $this->calculateReverseRate($rateDto->rate);
        $rate = new RateEntity();
        $rate->setBase($rateDto->currency);
        $rate->setDate($rateDto->date);
        $rate->setRate($rateValue);
        $rate->setCurrency($rateDto->base);
        $errors = $validator->validate($rate);

        if (count($errors) > 0) {
            throw new OutOfBoundsException((string)$errors);
        }

        return $rate;
    }

    /**
     * Calculate reverse rate.
     *
     * @throws DivisionByZeroException
     * @throws MathException
     * @throws NumberFormatException
     */
    private static function calculateReverseRate($rate): string
    {
        return (string)BigDecimal::of(1)->dividedBy(BigDecimal::of($rate), 8, RoundingMode::HALF_DOWN);
    }
}
