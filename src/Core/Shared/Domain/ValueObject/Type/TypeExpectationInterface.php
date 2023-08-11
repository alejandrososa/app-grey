<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type;

interface TypeExpectationInterface
{
    public function acceptsNull(): bool;

    /**
     * @psalm-param class-string $class
     */
    public function acceptsClass(string $class): bool;

    public function acceptsString(): bool;

    public function acceptsInt(): bool;

    public function acceptsFloat(): bool;

    public function acceptsBool(): bool;

    public function acceptsArray(): bool;
}
