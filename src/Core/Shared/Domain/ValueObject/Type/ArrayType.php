<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type;

use App\Core\Shared\Domain\ValueObject\ViolationExceptionInterface;
use App\Core\Shared\Domain\ValueObject\Type\Cast\ToArrayConvertibleInterface;

/**
 * @see \App\Tests\Core\Shared\Domain\ValueObject\Type\ArrayTypeTest
 */
final class ArrayType implements TypeInterface
{
    private TypeExpectationInterface $typeExpectation;

    public function __construct()
    {
        $this->typeExpectation = TypeExpectation::createArrayType();
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->typeExpectation;
    }

    /**
     * @throws ViolationExceptionInterface
     *
     * @return array<mixed>
     */
    public function prepareValue(mixed $value): array
    {
        if (\is_array($value)) {
            return $value;
        }

        if (\is_object($value) && $value instanceof ToArrayConvertibleInterface) {
            return $value->toArray();
        }

        throw TypeViolation::exception();
    }
}
