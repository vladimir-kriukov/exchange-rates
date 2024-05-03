<?php

declare(strict_types=1);

namespace App\Providers;

use App\Dto\Rate;
use Exception;

/**
 * CoinDesk BTC rates provider in USD
 */
final class CoinDeskRatesProvider extends RatesProvider
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function transform(array $data): array
    {
        $rates = array_values($data['bpi']);
        $base = $this->base;
        $date = $data['time']['updated'];

        return array_map(
            static fn (array $rate): Rate => new Rate(
                $rate['code'],
                $base,
                (string)$rate['rate_float'],
                $date
            ),
            $rates
        );
    }
}
