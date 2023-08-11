<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\String;

use App\Core\Shared\Domain\ValueObject\Violation;

class StringViolation extends Violation implements StringViolationInterface
{
    public function __construct(string $message = '')
    {
        parent::__construct($message ?: 'Invalid string value.');
    }
}
