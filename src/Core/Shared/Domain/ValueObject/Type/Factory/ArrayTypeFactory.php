<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Factory;

use App\Core\Shared\Domain\ValueObject\Type\ArrayType;
use App\Core\Shared\Domain\ValueObject\Type\TypeInterface;

class ArrayTypeFactory implements TypeFactoryInterface
{
    public function createType(): TypeInterface
    {
        return new ArrayType();
    }
}
