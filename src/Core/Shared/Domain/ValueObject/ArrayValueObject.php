<?php

declare(strict_types=1);

namespace ApP\Core\Shared\Domain\ValueObject;

use Stringable;

abstract class ArrayValueObject extends ValueObject implements Stringable
{
    /** @param array<string, string> $value */
    public function __construct(protected array $value)
    {
    }

    /** @param array<string, string> $value */
    public static function fromArray(array $value): static
    {
        return new static($value);
    }

    /** @param array<string, string>|null $value */
    public static function fromJson(?string $value): static
    {
        if ($value === null || $value === '') {
            return new static(null);
        }

        return new static(json_decode($value, true));
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    public function value(): array
    {
        return $this->value;
    }

    public function toJson(): string
    {
        if ($this->value === []) {
            return json_encode([]);
        }

        return json_encode($this->value);
    }

    public function toArray(): array
    {
        return $this->value;
    }
}
