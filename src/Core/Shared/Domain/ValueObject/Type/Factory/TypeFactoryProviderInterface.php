<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Factory;

interface TypeFactoryProviderInterface
{
    public function registerFactory(string $typeName, TypeFactoryInterface $typeFactory): void;

    public function getTypeFactory(string $typeName): TypeFactoryInterface;
}
