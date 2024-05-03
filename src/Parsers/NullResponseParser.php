<?php

declare(strict_types=1);

namespace App\Parsers;

use App\Exceptions\InvalidResponseException;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Nothing-to-do response parser
 */
class NullResponseParser implements ResponseParser
{
    /**
     * @throws InvalidResponseException
     */
    public function parse(ResponseInterface $response): array
    {
        throw new InvalidResponseException();
    }
}
