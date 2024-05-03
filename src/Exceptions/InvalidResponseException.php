<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;
use UnexpectedValueException;

/**
 * Invalid response exception
 */
class InvalidResponseException extends UnexpectedValueException
{
    /**
     * @inheritDoc
     */
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        $message = $message ?: "Invalid response";

        parent::__construct($message, $code, $previous);
    }
}
