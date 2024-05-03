<?php

declare(strict_types=1);

namespace App\Dto;

use App\Validator\Constraints\AmountRequirements;
use App\Validator\Constraints\ConvertCurrencyRequirements;
use Brick\Math\BigDecimal;
use TypeError;

/**
 * Exchange DTO for interacting with rates converter
 */
class Exchange
{
    #[AmountRequirements]
    public readonly string $amount;

    /**
     * @throws TypeError
     */
    public function __construct(
        #[ConvertCurrencyRequirements(options: ['field' => 'base'])] public readonly string     $from,
        #[ConvertCurrencyRequirements(options: ['field' => 'currency'])] public readonly string $to,
        string                                                                                  $amount,
    ) {
        $this->amount = (string)BigDecimal::of($amount);
    }
}
