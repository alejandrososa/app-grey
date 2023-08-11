<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus;

use Symfony\Component\DependencyInjection\ContainerInterface;

final class BusChain
{
    /**
     * @var array<mixed>
     */
    private array $handlers = [];

    /**
     * @var array<mixed>
     */
    private array $domainEvents = [];

    public function __construct(private ContainerInterface $container)
    {
    }

    public function addHandlers(string $handler, string $type): void
    {
        $this->handlers[][$type] = $handler;
    }

    /** @return array<mixed>|null */
    public function getHandlers(string $type): ?array
    {
        $handlers = [];
        foreach ($this->handlers as $handlerMap) {
            if (\array_key_exists($type, $handlerMap)) {
                foreach ($handlerMap as $handler) {
                    $handlers[] = $this->container->get($handler);
                }
            }
        }

        return $handlers;
    }

    public function addDomainEvent(string $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

    /** @return array<mixed>|null */
    public function getDomainEvents(): ?array
    {
        return $this->domainEvents;
    }
}
