<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Factory;

use App\Core\Shared\Domain\ValueObject\Type\IntType;
use App\Core\Shared\Domain\ValueObject\Type\TypeInterface;

class IntTypeFactory implements TypeFactoryInterface
{
    public function createType(): TypeInterface
    {
        return new IntType();
    }
}
