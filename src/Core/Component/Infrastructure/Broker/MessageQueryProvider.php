<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Broker;

use App\Core\Shared\Domain\Bus\Query\Query;
use App\Core\Shared\Domain\Broker\BrokerProvider as BaseBrokerProvider;

class MessageQueryProvider implements BaseBrokerProvider
{
    /** @param array<mixed> $queries */
    public function __construct(private array $queries)
    {
    }

    public function addMessageQuery(string $contextName, string $messageQuery): void
    {
        $this->queries[$contextName] = $messageQuery;
    }

    /** @return array<mixed> */
    public function getAllMessageQueries(): array
    {
        return $this->queries;
    }

    /**
     * @param array<mixed> $args
     *
     * @throws \Exception
     */
    public function getMessageQuery(string $contextName, array $args = []): Query
    {
        $this->guardExistsContextName($contextName);

        $queryName = $this->queries[$contextName];

        $this->guardExistMessageQueryClass($queryName);

        return $this->getQuery($queryName, $args);
    }

    private function guardExistsContextName(string $contextName): void
    {
        if (empty($this->queries[$contextName])) {
            throw new \Exception('Reply Message Query is not registered with the broker provider');
        }
    }

    private function guardExistMessageQueryClass(mixed $queryName): void
    {
        if (!class_exists($queryName)) {
            throw new \Exception('Reply Message Query not found');
        }
    }

    /**
     * @param array<string> $args
     */
    private function getQuery(string $queryName, array $args = []): Query
    {
        $reflectionClass = $this->getReflectionClass($queryName);

        return is_null($reflectionClass->getConstructor())
            ? $reflectionClass->newInstanceWithoutConstructor()
            : $reflectionClass->newInstanceArgs($args);
    }

    /**
     * @throws \ReflectionException
     */
    private function getReflectionClass(mixed $queryName): \ReflectionClass
    {
        return new \ReflectionClass($queryName);
    }
}
