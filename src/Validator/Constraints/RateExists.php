<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

/**
 * Constraint that provided rate exist in rates table in the provided field
 */
#[Attribute]
class RateExists extends Constraint
{
    public string $message = 'Rate "{{ value }}" does not exist';

    public string $field = '';
}
