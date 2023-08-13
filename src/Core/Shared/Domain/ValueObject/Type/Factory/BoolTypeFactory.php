<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Factory;

use App\Core\Shared\Domain\ValueObject\Type\BoolType;
use App\Core\Shared\Domain\ValueObject\Type\TypeInterface;

class BoolTypeFactory implements TypeFactoryInterface
{
    public function createType(): TypeInterface
    {
        return new BoolType();
    }
}
