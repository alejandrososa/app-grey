<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Float;

use App\Core\Shared\Domain\ValueObject\ImmutableObjectTrait;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToFloatConvertibleInterface;

/**
 * @see \App\Tests\Core\Shared\Domain\ValueObject\Float\FloatValueTest
 */
class FloatValue implements \JsonSerializable, ToFloatConvertibleInterface
{
    use ImmutableObjectTrait;

    private float $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    final public function toFloat(): float
    {
        return $this->value;
    }

    public function toInt(): int
    {
        return (int)$this->value;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    final public function jsonSerialize(): float
    {
        return $this->value;
    }
}
