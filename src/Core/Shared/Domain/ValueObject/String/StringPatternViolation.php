<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\String;

use App\Core\Shared\Domain\ValueObject\ViolationException;
use App\Core\Shared\Domain\ValueObject\ViolationExceptionInterface;

final class StringPatternViolation extends StringViolation
{
    private string $pattern;

    public function __construct(string $pattern, string $message = '')
    {
        $this->pattern = $pattern;
        parent::__construct($message ?: "Expected value to satisfy the pattern: $pattern.");
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public static function exception(string $pattern): ViolationExceptionInterface
    {
        return ViolationException::for(new self($pattern));
    }

    public static function check(string $pattern, string $value): void
    {
        if (\preg_match($pattern, $value) !== 1) {
            throw self::exception($pattern);
        }
    }
}
