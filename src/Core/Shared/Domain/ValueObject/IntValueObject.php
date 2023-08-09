<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject;

abstract class IntValueObject extends ValueObject
{
    public function __construct(protected int $value)
    {
    }

    public static function fromString(mixed $value): static
    {
        return new static((int) $value);
    }

    public static function fromInt(int $value): static
    {
        return new static($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public abstract function humanValue(): string;

    public function isBiggerThan(IntValueObject $other): bool
    {
        return $this->value() > $other->value();
    }
}
