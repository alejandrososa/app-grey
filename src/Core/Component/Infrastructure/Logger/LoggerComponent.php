<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Logger;

use App\Core\Shared\Domain\Logger\Logger;
use Psr\Log\LoggerInterface;

class LoggerComponent implements Logger
{
    public function __construct(private LoggerInterface $logger)
    {
    }
    public function info(string $message, array $context = []): void
    {
        $this->logger->info($message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->logger->debug($message, $context);
    }

    public function warning(string $message, array $context = []): void
    {
        $this->logger->warning($message, $context);
    }

    public function critical(string $message, array $context = []): void
    {
        $this->logger->critical($message, $context);
    }
}
