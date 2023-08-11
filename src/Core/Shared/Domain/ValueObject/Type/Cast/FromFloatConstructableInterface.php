<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Cast;

interface FromFloatConstructableInterface
{
    public static function fromFloat(float $value): self;
}
