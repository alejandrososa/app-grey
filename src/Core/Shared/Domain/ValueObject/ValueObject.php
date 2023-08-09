<?php

namespace App\Core\Shared\Domain\ValueObject;

abstract class ValueObject
{
    public function __construct(protected ?string $value = null)
    {
    }

    public function equals(ValueObject $object): bool
    {
        return static::class === $object::class
            && $this === $object
            && $this->value() === $object->value();
    }

    private function value(): int|string
    {
        return $this->value;
    }
}
