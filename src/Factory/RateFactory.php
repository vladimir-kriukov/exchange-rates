<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\Rate as RateDto;
use App\Entity\Rate as RateEntity;

class RateFactory
{
    public function createRateEntityFromRateDto(RateDto $rateDto): RateEntity
    {
        $rate = new RateEntity();
        $rate->setBase($rateDto->base);
        $rate->setCurrency($rateDto->currency);
        $rate->setRate($rateDto->rate);
        $rate->setDate($rateDto->date);

        return $rate;
    }
}
