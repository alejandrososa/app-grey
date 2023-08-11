<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus;

use function Lambdish\Phunctional\reduce;

final class CallableFirstParameterExtractor
{
    /** @return array<callable> */
    // public static function forCallables(iterable $callables): array
    // {
    //    return map(self::unflatten(), reindex(self::classExtractor(new self()), $callables));
    // }

    /**
     * @param iterable<callable> $callables
     *
     * @return array<mixed>
     */
    public static function forPipedCallables(iterable $callables): array
    {
        return reduce(self::pipedCallablesReducer(), $callables, []);
    }

    public function extract(string $class): ?string
    {
        $reflectionClass = new \ReflectionClass($class);
        $method = $reflectionClass->getMethod('__invoke');

        if ($this->hasOnlyOneParameter($method)) {
            return $this->firstParameterClassFrom($method);
        }

        return null;
    }

    // private function classExtractor(CallableFirstParameterExtractor $callableFirstParameterExtractor): callable
    // {
    //    return static fn (callable $handler): ?string => $callableFirstParameterExtractor->extract($handler);
    // }

    private static function pipedCallablesReducer(): callable
    {
        return static function (array $subscribers, $subscriber): array {
            $subscribedEvents = $subscriber::subscribedTo();

            foreach ($subscribedEvents as $subscribedEvent) {
                $subscribers[$subscribedEvent][] = $subscriber;
            }

            return $subscribers;
        };
    }

    //    private static function unflatten(): callable
    //    {
    //        return static fn ($value): array => [$value];
    //    }

    private function firstParameterClassFrom(\ReflectionMethod $reflectionMethod): string
    {
        /** @var \ReflectionNamedType $reflectionType */
        $reflectionType = $reflectionMethod->getParameters()[0]->getType();

        if (!$reflectionType instanceof \ReflectionType) {
            throw new \LogicException('Missing type hint for the first parameter of __invoke');
        }

        return $reflectionType->getName();
    }

    private function hasOnlyOneParameter(\ReflectionMethod $reflectionMethod): bool
    {
        return $reflectionMethod->getNumberOfParameters() === 1;
    }
}
