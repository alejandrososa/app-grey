<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Cast;

interface ToStringConvertibleInterface
{
    public function __toString(): string;
}
