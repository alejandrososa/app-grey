<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type;

use App\Core\Shared\Domain\ValueObject\ViolationExceptionInterface;

interface TypeInterface
{
    public function getExpectation(): TypeExpectationInterface;

    /**
     * @throws ViolationExceptionInterface
     */
    public function prepareValue(mixed $value): mixed;
}
