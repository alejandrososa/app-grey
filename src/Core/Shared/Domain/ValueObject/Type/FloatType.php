<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type;

use App\Core\Shared\Domain\ValueObject\Type\Cast\ToFloatConvertibleInterface;

/**
 * @see \App\Tests\Core\Shared\Domain\ValueObject\Type\FloatTypeTest
 */
final class FloatType implements TypeInterface
{
    private TypeExpectationInterface $typeExpectation;

    public function __construct()
    {
        $this->typeExpectation = new TypeExpectation(
            acceptsClass: null,
            acceptsFloat: true
        );
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->typeExpectation;
    }

    public function prepareValue(mixed $value): float
    {
        if (\is_float($value)) {
            return $value;
        }

        if (\is_object($value) && $value instanceof ToFloatConvertibleInterface) {
            return $value->toFloat();
        }

        throw TypeViolation::exception();
    }
}
