<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Exchange;
use App\Entity\Rate;
use App\Exceptions\NotExistingRateException;
use Brick\Math\BigDecimal;
use Brick\Math\Exception\MathException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Rates converter
 */
class RatesConverter
{
    public function __construct(private readonly ManagerRegistry $doctrine)
    {
    }

    /**
     * Convert $amount $from $to.
     *
     * @throws MathException
     */
    public function __invoke(Exchange $exchange): string
    {
        $rate = $this->doctrine->getRepository(Rate::class)->findOneBy(
            [
                'base' => $exchange->from,
                'currency' => $exchange->to,
            ]
        );

        if (!$rate instanceof Rate) {
            throw new NotExistingRateException();
        }
        $amount = BigDecimal::of($exchange->amount);

        return (string)BigDecimal::of($rate->getRate())->multipliedBy($amount);
    }
}
