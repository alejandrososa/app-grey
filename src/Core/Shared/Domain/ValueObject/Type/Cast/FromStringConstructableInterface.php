<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Cast;

interface FromStringConstructableInterface
{
    public static function fromString(string $value): self;
}
