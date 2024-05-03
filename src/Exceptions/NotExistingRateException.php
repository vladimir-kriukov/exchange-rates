<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;
use UnexpectedValueException;

/**
 * Not-existing rate exception
 */
class NotExistingRateException extends UnexpectedValueException
{
    /**
     * @inheritDoc
     */
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        $message = $message ?: 'No rate for the currencies';

        parent::__construct($message, $code, $previous);
    }
}
