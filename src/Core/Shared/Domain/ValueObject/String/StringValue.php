<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\String;

use App\Core\Shared\Domain\ValueObject\ImmutableObjectTrait;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToStringConvertibleInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\FromStringConstructableInterface;

/** @phpstan-consistent-constructor
 * @see \App\Tests\Core\Shared\Domain\ValueObject\String\StringValueTest */
class StringValue implements \JsonSerializable, ToStringConvertibleInterface, FromStringConstructableInterface
{
    use ImmutableObjectTrait;

    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function getLength(): int
    {
        return \mb_strlen($this->value);
    }

    public static function fromString(string $value): self
    {
        return new static($value);
    }
}
