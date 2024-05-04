<?php

declare(strict_types=1);

namespace App\Providers;

use App\Dto\Rate;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Interface for all rates providers
 */
interface RatesProviderInterface
{
    /**
     * Update all rates and write to database.
     *
     * @return Rate[]
     */
    public function __invoke(): array;
}
