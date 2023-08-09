<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Logger;

interface Logger
{
    /** @param array<string, string> $context */
    public function info(string $message, array $context = []): void;

    /** @param array<string, string> $context */
    public function debug(string $message, array $context = []): void;

    /** @param array<string, string> $context */
    public function warning(string $message, array $context = []): void;

    /** @param array<string, string> $context */
    public function critical(string $message, array $context = []): void;
}
