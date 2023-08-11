<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type;

use App\Core\Shared\Domain\ValueObject\Type\Cast\ToStringConvertibleInterface;

/**
 * @see \App\Tests\Core\Shared\Domain\ValueObject\Type\StringTypeTest
 */
final class StringType implements TypeInterface
{
    private TypeExpectationInterface $typeExpectation;

    public function __construct()
    {
        $this->typeExpectation = new TypeExpectation(
            acceptsClass: null,
            acceptsString: true
        );
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->typeExpectation;
    }

    public function prepareValue(mixed $value): string
    {
        if (\is_string($value)) {
            return $value;
        }

        if (\is_object($value) && $value instanceof ToStringConvertibleInterface) {
            return (string)$value;
        }

        throw TypeViolation::exception();
    }
}
