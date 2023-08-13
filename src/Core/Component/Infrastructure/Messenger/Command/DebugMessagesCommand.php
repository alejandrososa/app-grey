<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Messenger\Command;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Command\DebugCommand;
use Symfony\Component\Console\Exception\RuntimeException;

class DebugMessagesCommand extends DebugCommand
{
    protected static $defaultName = 'debug:messenger';

    /** @var array<mixed> */
    private array $mapping;

    /** @param array<mixed> $mapping */
    public function __construct(array $mapping = [])
    {
        $this->mapping = $mapping;

        parent::__construct($mapping);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->title('Messenger');

        $bus = $input->getArgument('bus');
        $mapping = $this->getBusMapping($bus);

        foreach ($mapping as $bus => $handlersByMessage) {
            $symfonyStyle->section($bus);

            $tableRows = [];
            foreach ($handlersByMessage as $message => $handlers) {
                $description = self::getClassDescription($message);
                if ($description !== '' && $description !== '0') {
                    $tableRows[] = [sprintf('<comment>%s</>', $description)];
                }

                $tableRows = $this->getDomainEventList($bus, $handlers, $message, $tableRows);
                $tableRows = $this->getDomainEventHandlerList($bus, $message, $tableRows, $handlers);

                if ($tableRows === []) {
                    $tableRows[] = [''];
                }
            }

            $this->displayMessagesCanBeDispatch($tableRows, $symfonyStyle, $bus);
        }

        return 0;
    }

    /** @param array<mixed> $options */
    private function formatConditions(array $options): string
    {
        if ($options === []) {
            return '';
        }

        $optionsMapping = [];
        foreach ($options as $key => $value) {
            $optionsMapping[] = $key . '=' . $value;
        }

        return ' (when ' . implode(', ', $optionsMapping) . ')';
    }

    private static function getClassDescription(string $class): string
    {
        $reflectionClass = new \ReflectionClass($class);

        $docComment = $reflectionClass->getDocComment();
        if (!empty($docComment)) {
            $docComment = preg_split('#\n\s*\*\s*[\n@]#', mb_substr($docComment, 3, -2), 2)[0];

            return trim(preg_replace('#\s*\n\s*\*\s*#', ' ', $docComment));
        }

        return '';
    }

    /**
     * @param array<mixed> $tableRows
     */
    protected function displayMessagesCanBeDispatch(array $tableRows, SymfonyStyle $symfonyStyle, mixed $bus): void
    {
        if ($tableRows !== []) {
            $symfonyStyle->text('The following messages can be dispatched:');
            $symfonyStyle->newLine();
            $symfonyStyle->table([], $tableRows);
        }

        if ($tableRows === []) {
            $symfonyStyle->warning(sprintf('No handled message found in bus "%s".', $bus));
        }
    }

    /**
     * @param array<mixed> $mapping
     *
     * @return array<mixed>
     */
    protected function guardBusExists(array $mapping, mixed $bus): array
    {
        if (!isset($mapping[$bus])) {
            $keys = implode('", "', array_keys($this->mapping));

            $message = sprintf('Bus "%s" does not exist. Known buses are "%s".', $bus, $keys);
            throw new RuntimeException($message);
        }
        return $mapping;
    }

    /**
     * @return array<mixed>
     */
    protected function getBusMapping(mixed $bus): array
    {
        $mapping = $this->mapping;
        if (!empty($bus)) {
            $mapping = $this->guardBusExists($mapping, $bus);
            $mapping = [$bus => $mapping[$bus]];
        }
        return $mapping;
    }

    /**
     * @param array<mixed> $tableRows
     *
     * @return array<mixed>
     */
    protected function getDomainEventList(mixed $bus, mixed $handlers, int|string $message, array $tableRows): array
    {
        $subscribers = [];
        if ($bus === 'event.bus') {
            foreach ($handlers as $handler) {
                $domainEventSubscriber = $handler[0];
                foreach ($domainEventSubscriber::subscribedTo() as $domainEvent) {
                    $subscribers[$domainEvent][] = [$handler[0], $handler[1]];
                }
            }

            foreach ($subscribers as $domainEvent => $domainEventSubscribers) {
                $label = '<fg=cyan>Domain event: <info>\'%s\'</info> wrapped in %s</fg=cyan>';
                $tableRows[] = [sprintf($label, $domainEvent, $message)];
                foreach ($domainEventSubscribers as $domainEventSubscriber) {
                    $messageWithFormat = sprintf('   handled by <info>%s</>', $domainEventSubscriber[0]);
                    $tableRows[] = [$messageWithFormat . $this->formatConditions($domainEventSubscriber[1])];
                    $handlerDescription = self::getClassDescription($domainEventSubscriber[0]);
                    if ($handlerDescription !== '' && $handlerDescription !== '0') {
                        $tableRows[] = [sprintf('               <comment>%s</>', $handlerDescription)];
                    }
                }
            }
        }
        return $tableRows;
    }

    /**
     * @param array<mixed> $tableRows
     *
     * @return array<mixed>
     */
    protected function getDomainEventHandlerList(
        mixed $bus,
        int|string $message,
        array $tableRows,
        mixed $handlers
    ): array {
        if ($bus !== 'event.bus') {
            $tableRows[] = [sprintf('<fg=cyan>%s</fg=cyan>', $message)];
            foreach ($handlers as $handler) {
                $formatConditions = $this->formatConditions($handler[1]);
                $tableRows[] = [
                    sprintf('    handled by <info>%s</>', $handler[0]) . $formatConditions,
                ];
                $handlerDescription = self::getClassDescription($handler[0]);
                if ($handlerDescription !== '' && $handlerDescription !== '0') {
                    $tableRows[] = [sprintf('               <comment>%s</>', $handlerDescription)];
                }
            }
        }
        return $tableRows;
    }
}
