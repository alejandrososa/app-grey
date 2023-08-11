<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Cast;

interface FromAnyConstructableInterface
{
    /**
     * @return static
     */
    public static function fromAny(mixed $value): self;
}
