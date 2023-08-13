<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\ValueObject\Type\Factory;

class TypeFactoryProvider implements TypeFactoryProviderInterface
{
    /** @var array<string, TypeFactoryInterface> */
    private array $factories = [];

    public function registerFactory(string $typeName, TypeFactoryInterface $typeFactory): void
    {
        $this->factories[$typeName] = $typeFactory;
    }

    public function getTypeFactory(string $typeName): TypeFactoryInterface
    {
        if (isset($this->factories[$typeName])) {
            return $this->factories[$typeName];
        }
        throw new \InvalidArgumentException("Unknown type: $typeName.");
    }
}
