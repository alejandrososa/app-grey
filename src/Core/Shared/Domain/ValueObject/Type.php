<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject;

use App\Core\Shared\Domain\ValueObject\Type\ClassType;
use App\Core\Shared\Domain\ValueObject\Type\NullableType;
use App\Core\Shared\Domain\ValueObject\Type\TypeInterface;
use App\Core\Shared\Domain\ValueObject\Type\Factory\TypeManager;

/**
 * @see \App\Tests\Core\Shared\Domain\ValueObject\TypeTest
 */
final class Type
{
    public const PHP_STRING = 'string';
    public const PHP_INT = 'int';
    public const PHP_FLOAT = 'float';
    public const PHP_BOOL = 'bool';
    public const PHP_ARRAY = 'array';
    public const PHP_VOID = 'void';

    private function __construct()
    {
    }

    private static function forTypeReflection(\ReflectionNamedType $reflectionNamedType): TypeInterface
    {
        /** @psalm-suppress ArgumentTypeCoercion */
        $type = self::forClass($reflectionNamedType->getName());

        if ($reflectionNamedType->isBuiltin()) {
            $type = self::forBuiltinType($reflectionNamedType->getName());
        }

        if ($reflectionNamedType->allowsNull()) {
            return new NullableType($type);
        }

        return $type;
    }

    /**
     * @psalm-param class-string $class
     */
    public static function forClass(string $class): TypeInterface
    {
        $reflectionClass = new \ReflectionClass($class);

        return self::forClassReflection($reflectionClass);
    }

    public static function forBuiltinType(string $name): TypeInterface
    {
        return (new TypeManager())->createType($name);
    }

    private static function forClassReflection(\ReflectionClass $reflectionClass): TypeInterface
    {
        return new ClassType($reflectionClass);
    }

    public static function forPropertyReflection(\ReflectionProperty $reflectionProperty): TypeInterface
    {
        $name = $reflectionProperty->getName();

        if (!$reflectionProperty->hasType()) {
            throw new \RuntimeException("Property $name is missing type hint.");
        }

        $reflectionType = $reflectionProperty->getType();
        if (!$reflectionType instanceof \ReflectionNamedType) {
            throw new \RuntimeException('ReflectionNamedType is not supported.');
        }

        return self::forTypeReflection($reflectionType);
    }

    public static function forMethodReturnType(\ReflectionMethod $reflectionMethod): TypeInterface
    {
        $reflectionType = $reflectionMethod->getReturnType();
        if (!$reflectionType instanceof \ReflectionType) {
            $message = 'Method ' . $reflectionMethod->getName() . ' does not have a return type.';
            throw new \UnexpectedValueException($message);
        }

        if (!$reflectionType instanceof \ReflectionNamedType) {
            throw new \RuntimeException('ReflectionNamedType not supported.');
        }

        return self::forTypeReflection($reflectionType);
    }

    public static function forMethodParameterType(\ReflectionParameter $reflectionParameter): TypeInterface
    {
        $reflectionType = $reflectionParameter->getType();
        if (!$reflectionType instanceof \ReflectionType) {
            throw new \UnexpectedValueException('Method current does not have a return type.');
        }

        if (!$reflectionType instanceof \ReflectionNamedType) {
            throw new \RuntimeException('ReflectionNamedType not supported.');
        }

        return self::forTypeReflection($reflectionType);
    }
}
