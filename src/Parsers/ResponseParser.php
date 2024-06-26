<?php

declare(strict_types=1);

namespace App\Parsers;

use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Response parser interface
 */
interface ResponseParser
{
    public function parse(ResponseInterface $response): array;
}
