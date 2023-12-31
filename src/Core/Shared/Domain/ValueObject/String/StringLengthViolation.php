<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\String;

class StringLengthViolation extends StringViolation
{
    private int $length;

    public function __construct(int $length, string $message = '')
    {
        $this->length = $length;
        parent::__construct($message ?: "Unexpected string of length $length.");
    }

    final public function getLength(): int
    {
        return $this->length;
    }
}
