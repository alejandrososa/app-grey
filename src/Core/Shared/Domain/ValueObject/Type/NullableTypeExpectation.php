<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type;

final class NullableTypeExpectation implements TypeExpectationInterface
{
    private TypeExpectationInterface $typeExpectation;

    public function __construct(TypeExpectationInterface $typeExpectation)
    {
        $this->typeExpectation = $typeExpectation;
    }

    public function acceptsNull(): bool
    {
        return true;
    }

    public function acceptsClass(string $class): bool
    {
        return $this->typeExpectation->acceptsClass($class);
    }

    public function acceptsString(): bool
    {
        return $this->typeExpectation->acceptsString();
    }

    public function acceptsInt(): bool
    {
        return $this->typeExpectation->acceptsInt();
    }

    public function acceptsFloat(): bool
    {
        return $this->typeExpectation->acceptsFloat();
    }

    public function acceptsBool(): bool
    {
        return $this->typeExpectation->acceptsBool();
    }

    public function acceptsArray(): bool
    {
        return $this->typeExpectation->acceptsArray();
    }
}
