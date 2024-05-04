<?php

declare(strict_types=1);

namespace App\Dto;

use Brick\Math\BigDecimal;
use Exception;

/**
 * Rate DTO for interacting with rates providers
 */
class Rate
{
    public readonly string $rate;

    /**
     * @throws Exception
     */
    public function __construct(
        public readonly string $currency,
        public readonly string $base,
        string $rate,
        public \DateTimeImmutable $date
    ) {
        $this->rate = (string)BigDecimal::of($rate);
    }
}
