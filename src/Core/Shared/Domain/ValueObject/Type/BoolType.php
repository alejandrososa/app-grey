<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type;

use App\Core\Shared\Domain\ValueObject\Type\Cast\ToBoolConvertibleInterface;

/**
 * @see \App\Tests\Core\Shared\Domain\ValueObject\Type\BoolTypeTest
 */
final class BoolType implements TypeInterface
{
    private TypeExpectationInterface $typeExpectation;

    public function __construct()
    {
        $this->typeExpectation = new TypeExpectation(
            acceptsClass: null,
            acceptsBool: true
        );
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->typeExpectation;
    }

    public function prepareValue(mixed $value): bool
    {
        if (\is_bool($value)) {
            return $value;
        }

        if (\is_object($value) && $value instanceof ToBoolConvertibleInterface) {
            return $value->toBool();
        }

        throw TypeViolation::exception();
    }
}
