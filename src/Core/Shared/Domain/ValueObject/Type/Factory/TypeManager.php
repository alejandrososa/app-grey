<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Factory;

use App\Core\Shared\Domain\ValueObject\Type;
use App\Core\Shared\Domain\ValueObject\Type\TypeInterface;

final class TypeManager
{
    private TypeFactoryProviderInterface $typeFactoryProvider;

    public function __construct()
    {
        $this->typeFactoryProvider = new TypeFactoryProvider();
        $this->typeFactoryProvider->registerFactory(Type::PHP_STRING, new StringTypeFactory());
        $this->typeFactoryProvider->registerFactory(Type::PHP_INT, new IntTypeFactory());
        $this->typeFactoryProvider->registerFactory(Type::PHP_FLOAT, new FloatTypeFactory());
        $this->typeFactoryProvider->registerFactory(Type::PHP_BOOL, new BoolTypeFactory());
        $this->typeFactoryProvider->registerFactory(Type::PHP_ARRAY, new ArrayTypeFactory());
        $this->typeFactoryProvider->registerFactory(Type::PHP_VOID, new VoidTypeFactory());
    }

    public function createType(string $type): TypeInterface
    {
        return $this->typeFactoryProvider
            ->getTypeFactory($type)
            ->createType();
    }
}
