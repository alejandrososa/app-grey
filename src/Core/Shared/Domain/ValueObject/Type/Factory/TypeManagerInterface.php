<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Factory;

use App\Core\Shared\Domain\ValueObject\Type\TypeInterface;

interface TypeManagerInterface
{
    public function createType(string $type): TypeInterface;
}
