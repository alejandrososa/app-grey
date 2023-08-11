<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Float;

use App\Core\Shared\Domain\ValueObject\Violation;

class FloatViolation extends Violation implements FloatViolationInterface
{
    public function __construct(string $message = '')
    {
        parent::__construct($message ?: 'Invalid float value.');
    }
}
