<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject;

use App\Core\Shared\Domain\ValueObject\Type\IntType;
use App\Core\Shared\Domain\ValueObject\Type\BoolType;
use App\Core\Shared\Domain\ValueObject\Type\VoidType;
use App\Core\Shared\Domain\ValueObject\Type\ArrayType;
use App\Core\Shared\Domain\ValueObject\Type\ClassType;
use App\Core\Shared\Domain\ValueObject\Type\FloatType;
use App\Core\Shared\Domain\ValueObject\Type\StringType;
use App\Core\Shared\Domain\ValueObject\Type\NullableType;
use App\Core\Shared\Domain\ValueObject\Type\TypeInterface;

/**
 * @see \App\Tests\Core\Shared\Domain\ValueObject\TypeTest
 */
final class Type
{
    private const PHP_STRING = 'string';
    private const PHP_INT = 'int';
    private const PHP_FLOAT = 'float';
    private const PHP_BOOL = 'bool';
    private const PHP_ARRAY = 'array';
    private const PHP_VOID = 'void';

    private function __construct()
    {
    }

    private static function forTypeReflection(\ReflectionNamedType $reflectionNamedType): TypeInterface
    {
        if ($reflectionNamedType->isBuiltin()) {
            $type = self::forBuiltinType($reflectionNamedType->getName());
        } else {
            /** @psalm-suppress ArgumentTypeCoercion */
            $type = self::forClass($reflectionNamedType->getName());
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
        switch ($name) {
            case self::PHP_STRING:
                return new StringType();
            case self::PHP_INT:
                return new IntType();
            case self::PHP_FLOAT:
                return new FloatType();
            case self::PHP_BOOL:
                return new BoolType();
            case self::PHP_ARRAY:
                return new ArrayType();
            case self::PHP_VOID:
                return new VoidType();
            default:
                throw new \InvalidArgumentException("Not a builtin type: $name.");
        }
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
