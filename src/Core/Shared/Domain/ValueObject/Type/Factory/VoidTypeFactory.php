<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Factory;

use App\Core\Shared\Domain\ValueObject\Type\VoidType;
use App\Core\Shared\Domain\ValueObject\Type\TypeInterface;

class VoidTypeFactory implements TypeFactoryInterface
{
    public function createType(): TypeInterface
    {
        return new VoidType();
    }
}
