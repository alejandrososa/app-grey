<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Cast;

interface FromIntConstructableInterface
{
    public static function fromInt(int $value): self;
}
