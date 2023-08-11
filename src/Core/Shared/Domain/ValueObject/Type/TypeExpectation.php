<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type;

final class TypeExpectation implements TypeExpectationInterface
{
    private ?string $acceptsClass;
    private bool $acceptsString;
    private bool $acceptsInt;
    private bool $acceptsFloat;
    private bool $acceptsBool;
    private bool $acceptsArray;

    public function __construct(
        string $acceptsClass = null,
        bool $acceptsString = false,
        bool $acceptsInt = false,
        bool $acceptsFloat = false,
        bool $acceptsBool = false,
        bool $acceptsArray = false
    ) {
        $this->acceptsClass = $acceptsClass;
        $this->acceptsString = $acceptsString;
        $this->acceptsInt = $acceptsInt;
        $this->acceptsFloat = $acceptsFloat;
        $this->acceptsBool = $acceptsBool;
        $this->acceptsArray = $acceptsArray;
    }

    public function acceptsNull(): bool
    {
        return false;
    }

    public function acceptsClass(string $class): bool
    {
        if (!class_exists($class)) {
            return false;
        }

        $classInstance = new $class();

        return $classInstance instanceof $this->acceptsClass;
    }

    public function acceptsString(): bool
    {
        return $this->acceptsString;
    }

    public function acceptsInt(): bool
    {
        return $this->acceptsInt;
    }

    public function acceptsFloat(): bool
    {
        return $this->acceptsFloat;
    }

    public function acceptsBool(): bool
    {
        return $this->acceptsBool;
    }

    public function acceptsArray(): bool
    {
        return $this->acceptsArray;
    }
}
