<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Messenger\Command;

use ReflectionClass;
use ReflectionException;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\Command\DebugCommand;

class DebugMessagesCommand extends DebugCommand
{
    protected static $defaultName = 'debug:messenger';

    private array $mapping;

    public function __construct(array $mapping = [])
    {
        $this->mapping = $mapping;

        parent::__construct($mapping);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->title('Messenger');

        $mapping = $this->mapping;
        if ($bus = $input->getArgument('bus')) {
            if (!isset($mapping[$bus])) {
                $keys = implode('", "', array_keys($this->mapping));

                throw new RuntimeException(sprintf('Bus "%s" does not exist. Known buses are "%s".', $bus, $keys));
            }
            $mapping = [$bus => $mapping[$bus]];
        }

        foreach ($mapping as $bus => $handlersByMessage) {
            $symfonyStyle->section($bus);

            $tableRows = [];
            foreach ($handlersByMessage as $message => $handlers) {
                if (($description = self::getClassDescription($message)) !== '' && ($description = self::getClassDescription($message)) !== '0') {
                    $tableRows[] = [sprintf('<comment>%s</>', $description)];
                }

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
                            $tableRows[] = [
                                sprintf('    handled by <info>%s</>', $domainEventSubscriber[0]) . $this->formatConditions($domainEventSubscriber[1]),
                            ];
                            if (($handlerDescription = self::getClassDescription($domainEventSubscriber[0])) !== '' && ($handlerDescription = self::getClassDescription($domainEventSubscriber[0])) !== '0') {
                                $tableRows[] = [sprintf('               <comment>%s</>', $handlerDescription)];
                            }
                        }
                    }
                }

                if ($bus !== 'event.bus') {
                    $tableRows[] = [sprintf('<fg=cyan>%s</fg=cyan>', $message)];
                    foreach ($handlers as $handler) {
                        $tableRows[] = [
                            sprintf('    handled by <info>%s</>', $handler[0]) . $this->formatConditions($handler[1]),
                        ];
                        if (($handlerDescription = self::getClassDescription($handler[0])) !== '' && ($handlerDescription = self::getClassDescription($handler[0])) !== '0') {
                            $tableRows[] = [sprintf('               <comment>%s</>', $handlerDescription)];
                        }
                    }
                }

                $tableRows[] = [''];
            }

            if ($tableRows !== []) {
                $symfonyStyle->text('The following messages can be dispatched:');
                $symfonyStyle->newLine();
                $symfonyStyle->table([], $tableRows);
            } else {
                $symfonyStyle->warning(sprintf('No handled message found in bus "%s".', $bus));
            }
        }

        return 0;
    }

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
        try {
            $reflectionClass = new ReflectionClass($class);

            if ($docComment = $reflectionClass->getDocComment()) {
                $docComment = preg_split('#\n\s*\*\s*[\n@]#', substr($docComment, 3, -2), 2)[0];

                return trim(preg_replace('#\s*\n\s*\*\s*#', ' ', $docComment));
            }
        } catch (ReflectionException $e) {
        }

        return '';
    }
}
