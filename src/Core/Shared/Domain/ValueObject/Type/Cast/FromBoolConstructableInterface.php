<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Cast;

interface FromBoolConstructableInterface
{
    public static function fromBool(bool $value): self;
}
