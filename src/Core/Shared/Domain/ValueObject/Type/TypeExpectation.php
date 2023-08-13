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
        ?string $acceptsClass,
        bool $acceptsString,
        bool $acceptsInt,
        bool $acceptsFloat,
        bool $acceptsBool,
        bool $acceptsArray,
    ) {
        $this->acceptsClass = $acceptsClass;
        $this->acceptsString = $acceptsString;
        $this->acceptsInt = $acceptsInt;
        $this->acceptsFloat = $acceptsFloat;
        $this->acceptsBool = $acceptsBool;
        $this->acceptsArray = $acceptsArray;
    }

    public static function createClassType(
        string $acceptsClass,
        bool $acceptsString,
        bool $acceptsInt,
        bool $acceptsFloat,
        bool $acceptsBool,
        bool $acceptsArray
    ): self {
        return new self($acceptsClass, $acceptsString, $acceptsInt, $acceptsFloat, $acceptsBool, $acceptsArray);
    }

    public static function createStringType(): self
    {
        return new self(
            acceptsClass: null,
            acceptsString: true,
            acceptsInt: false,
            acceptsFloat: false,
            acceptsBool: false,
            acceptsArray: false
        );
    }

    public static function createIntType(): self
    {
        return new self(
            acceptsClass: null,
            acceptsString: false,
            acceptsInt: true,
            acceptsFloat: false,
            acceptsBool: false,
            acceptsArray: false
        );
    }

    public static function createFloatType(): self
    {
        return new self(
            acceptsClass: null,
            acceptsString: false,
            acceptsInt: false,
            acceptsFloat: true,
            acceptsBool: false,
            acceptsArray: false
        );
    }

    public static function createBoolType(): self
    {
        return new self(
            acceptsClass: null,
            acceptsString: false,
            acceptsInt: false,
            acceptsFloat: false,
            acceptsBool: true,
            acceptsArray: false
        );
    }

    public static function createArrayType(): self
    {
        return new self(
            acceptsClass: null,
            acceptsString: false,
            acceptsInt: false,
            acceptsFloat: false,
            acceptsBool: false,
            acceptsArray: true
        );
    }

    public static function createVoidType(): self
    {
        return new self(
            acceptsClass: null,
            acceptsString: false,
            acceptsInt: false,
            acceptsFloat: false,
            acceptsBool: false,
            acceptsArray: false
        );
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
