<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Rate as RateDto;
use App\Entity\Rate as RateEntity;
use App\Factory\RateFactory;
use App\Providers\RatesProviderInterface;
use App\Repository\RateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Exception\OutOfBoundsException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Rates updater
 */
class RatesUpdater
{
    /**
     * @param iterable<RatesProviderInterface> $ratesProviders
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     * @param RateRepository $repository
     * @param RateFactory $rateFactory
     */
    public function __construct(
        private readonly iterable $ratesProviders,
        private readonly ValidatorInterface $validator,
        private readonly EntityManagerInterface $em,
        private readonly RateRepository $repository,
        private readonly RateFactory $rateFactory,
    ) {
    }

    /**
     * Get currency rates from providers and save into rates table.
     *
     * @throws OutOfBoundsException
     */
    public function __invoke(): void
    {
        foreach ($this->ratesProviders as $ratesProvider) {
            /** @var RateDto[] $rateDto */
            $rateDtos = ($ratesProvider)();

            foreach ($rateDtos as $rateDto) {
                $this->updateRate($rateDto);
            }
        }

        $this->em->flush();
    }

    /**
     * Update or Create a rate entity.
     *
     * @param RateDto $rateDto
     *
     * @return void
     */
    private function updateRate(RateDto $rateDto): void
    {
        $rate = $this->repository->findOneBy(
            [
                'base' => $rateDto->base,
                'currency' => $rateDto->currency,
            ]
        );

        if ($rate instanceof RateEntity) {
            $rate->setRate($rateDto->rate);
            $rate->setDate($rateDto->date);
        } else {
            $rate = $this->rateFactory->createRateEntityFromRateDto($rateDto);
        }

        $errors = $this->validator->validate($rate);

        if (count($errors) > 0) {
            throw new OutOfBoundsException((string)$errors);
        }

        $this->em->persist($rate);
    }

}
