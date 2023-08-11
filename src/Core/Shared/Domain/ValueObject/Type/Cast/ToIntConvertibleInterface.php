<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Cast;

interface ToIntConvertibleInterface
{
    public function toInt(): int;
}
