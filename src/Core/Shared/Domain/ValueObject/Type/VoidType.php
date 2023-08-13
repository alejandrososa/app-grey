<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type;

use App\Core\Shared\Domain\ValueObject\ViolationExceptionInterface;

/**
 * @see \App\Tests\Core\Shared\Domain\ValueObject\Type\VoidTypeTest
 */
final class VoidType implements TypeInterface
{
    private TypeExpectationInterface $typeExpectation;

    public function __construct()
    {
        $this->typeExpectation = TypeExpectation::createVoidType();
    }

    public function getExpectation(): TypeExpectationInterface
    {
        return $this->typeExpectation;
    }

    /**
     * @throws ViolationExceptionInterface
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function prepareValue(mixed $value): never
    {
        throw TypeViolation::exception();
    }
}
