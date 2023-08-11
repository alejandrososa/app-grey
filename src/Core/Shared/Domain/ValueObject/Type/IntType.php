<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type;

use App\Core\Shared\Domain\ValueObject\Type\Cast\ToIntConvertibleInterface;

/**
 * @see \App\Tests\Core\Shared\Domain\ValueObject\Type\IntTypeTest
 */
final class IntType implements TypeInterface
{
    private TypeExpectationInterface $typeExpectation;

    public function __construct()
    {
        $this->typeExpectation = new TypeExpectation(
            acceptsClass: null,
            acceptsInt: true
        );
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->typeExpectation;
    }

    public function prepareValue(mixed $value): int
    {
        if (\is_int($value)) {
            return $value;
        }

        if (\is_object($value) && $value instanceof ToIntConvertibleInterface) {
            return $value->toInt();
        }

        throw TypeViolation::exception();
    }
}
