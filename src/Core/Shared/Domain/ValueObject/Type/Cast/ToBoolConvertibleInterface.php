<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Cast;

interface ToBoolConvertibleInterface
{
    public function toBool(): bool;
}
