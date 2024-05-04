<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Exchange;
use App\Entity\Rate;
use App\Exceptions\NotExistingRateException;
use App\Repository\RateRepository;
use Brick\Math\BigDecimal;
use Brick\Math\Exception\MathException;

/**
 * Rates converter
 */
class RatesConverter
{
    public function __construct(
        private readonly RateRepository $repository,
    ) {
    }

    /**
     * Convert $amount $from $to.
     *
     * @throws MathException
     */
    public function __invoke(Exchange $exchange): string
    {
        $rate = $this->repository->findOneBy(
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
