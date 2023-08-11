<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type;

use App\Core\Shared\Domain\ValueObject\ViolationExceptionInterface;

/**
 * @see \App\Tests\Core\Shared\Domain\ValueObject\Type\NullableTypeTest
 */
final class NullableType implements TypeInterface
{
    private TypeInterface $type;

    public function __construct(TypeInterface $type)
    {
        $this->type = $type;
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return new NullableTypeExpectation($this->type->getExpectation());
    }

    /**
     * @throws ViolationExceptionInterface
     */
    public function prepareValue(mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return $this->type->prepareValue($value);
    }
}
