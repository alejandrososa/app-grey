<?php

declare(strict_types=1);

namespace ApP\Core\Shared\Domain\ValueObject;

use Stringable;

abstract class StringValueObject extends ValueObject implements Stringable
{
    public function __construct(protected string $value)
    {
    }

    public static function fromString(mixed $value): static
    {
        return new static($value);
    }

    public function __toString()
    {
        return $this->value();
    }

    public function value(): string
    {
        return $this->value;
    }
}
