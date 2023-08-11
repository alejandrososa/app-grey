<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type;

use App\Core\Shared\Domain\ValueObject\ViolationExceptionInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\FromAnyConstructableInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\FromIntConstructableInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\FromBoolConstructableInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\FromArrayConstructableInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\FromFloatConstructableInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\FromObjectConstructableInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\FromStringConstructableInterface;

/**
 * @see \App\Tests\Core\Shared\Domain\ValueObject\Type\ClassTypeTest
 */
final class ClassType implements TypeInterface
{
    private \ReflectionClass $reflectionClass;
    private TypeExpectationInterface $typeExpectation;

    public function __construct(\ReflectionClass $reflectionClass)
    {
        $this->reflectionClass = $reflectionClass;
        $this->typeExpectation = new TypeExpectation(
            $this->reflectionClass->getName(),
            $this->reflectionClass->implementsInterface(FromStringConstructableInterface::class),
            $this->reflectionClass->implementsInterface(FromIntConstructableInterface::class),
            $this->reflectionClass->implementsInterface(FromFloatConstructableInterface::class),
            $this->reflectionClass->implementsInterface(FromBoolConstructableInterface::class),
            $this->reflectionClass->implementsInterface(FromArrayConstructableInterface::class),
        );
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->typeExpectation;
    }

    /**
     * @throws ViolationExceptionInterface
     * @throws \ReflectionException
     */
    public function prepareValue(mixed $value): mixed
    {
        $typeExpectation = $this->getExpectation();

        if (\is_string($value)) {
            if ($typeExpectation->acceptsString()) {
                return $this->reflectionClass->getMethod('fromString')->invoke(null, $value);
            }
        } elseif (\is_int($value)) {
            if ($typeExpectation->acceptsInt()) {
                return $this->reflectionClass->getMethod('fromInt')->invoke(null, $value);
            }
        } elseif (\is_float($value)) {
            if ($typeExpectation->acceptsFloat()) {
                return $this->reflectionClass->getMethod('fromFloat')->invoke(null, $value);
            }
        } elseif (\is_array($value)) {
            if ($typeExpectation->acceptsArray()) {
                return $this->reflectionClass->getMethod('fromArray')->invoke(null, $value);
            }
        } elseif (\is_bool($value)) {
            if ($typeExpectation->acceptsBool()) {
                return $this->reflectionClass->getMethod('fromBool')->invoke(null, $value);
            }
        } elseif (\is_object($value)) {
            if ($this->reflectionClass->implementsInterface(FromObjectConstructableInterface::class)) {
                return $this->reflectionClass->getMethod('fromObject')->invoke(null, $value);
            }

            if (\is_a($value, $this->reflectionClass->getName())) {
                return $value;
            }
        } elseif ($this->reflectionClass->implementsInterface(FromAnyConstructableInterface::class)) {
            return $this->reflectionClass->getMethod('fromAny')->invoke(null, $value);
        }

        throw TypeViolation::exception();
    }
}
