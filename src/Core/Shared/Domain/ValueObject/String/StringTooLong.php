<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\String;

use App\Core\Shared\Domain\ValueObject\ViolationException;
use App\Core\Shared\Domain\ValueObject\ViolationExceptionInterface;

final class StringTooLong extends StringLengthViolation
{
    private int $maxLength;

    public function __construct(int $maxLength, int $length, string $message = '')
    {
        $this->maxLength = $maxLength;
        parent::__construct($length, $message ?: "Expected at most $maxLength characters.");
    }

    public function getMaxLength(): int
    {
        return $this->maxLength;
    }

    public static function exception(int $maxLength, int $length): ViolationExceptionInterface
    {
        return ViolationException::for(new self($maxLength, $length));
    }

    public static function check(int $maxLength, int $length): void
    {
        if ($length > $maxLength) {
            throw self::exception($maxLength, $length);
        }
    }
}
