<?php

declare(strict_types=1);

namespace App\Dto;

use Brick\Math\BigDecimal;
use DateTimeImmutable;
use Exception;

/**
 * Rate DTO for interacting with rates providers
 */
class Rate
{
    public readonly DateTimeImmutable $date;
    public readonly string $rate;

    /**
     * @throws Exception
     */
    public function __construct(
        public readonly string $currency,
        public readonly string $base,
        string $rate,
        string $date
    ) {
        $this->rate = (string)BigDecimal::of($rate);
        $this->date = new DateTimeImmutable($date);
    }
}
