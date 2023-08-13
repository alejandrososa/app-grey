<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus\Event\MySql;

use Doctrine\DBAL\FetchMode;
use Doctrine\DBAL\Connection;
use App\Core\Shared\Domain\Utils;
use Doctrine\ORM\EntityManagerInterface;
use App\Core\Component\Infrastructure\Bus\Event\DomainEventMapping;

use function Lambdish\Phunctional\map;
use function Lambdish\Phunctional\each;

final class MySqlDoctrineDomainEventsConsumer
{
    private Connection $connection;

    public function __construct(EntityManagerInterface $entityManager, private DomainEventMapping $domainEventMapping)
    {
        $this->connection = $entityManager->getConnection();
    }

    public function consume(callable $subscribers, int $eventsToConsume): void
    {
        $events = $this->connection
            ->executeQuery("SELECT * FROM domain_events ORDER BY occurred_on ASC LIMIT {$eventsToConsume}")
            ->fetchAll(FetchMode::ASSOCIATIVE)
        ;

        each($this->executeSubscribers($subscribers), $events);

        $ids = implode(', ', map($this->idExtractor(), $events));

        if ($ids !== '') {
            $this->connection->executeUpdate("DELETE FROM domain_events WHERE id IN ({$ids})");
        }
    }

    private function executeSubscribers(callable $subscribers): callable
    {
        return function (array $rawEvent) use ($subscribers): void {
            $domainEventClass = $this->domainEventMapping->for($rawEvent['name']);
            $domainEvent = $domainEventClass::fromPrimitives(
                $rawEvent['aggregate_id'],
                Utils::jsonDecode($rawEvent['body']),
                $rawEvent['id'],
                $this->formatDate($rawEvent['occurred_on'])
            );
            $subscribers($domainEvent);
        };
    }

    private function formatDate(string $stringDate): string
    {
        return Utils::dateToString(new \DateTimeImmutable($stringDate));
    }

    private function idExtractor(): callable
    {
        return static fn (array $event): string => "'${event['id']}'";
    }
}
