<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Bus\Event;

interface DomainEventSubscriber
{
    // see https://tldp.org/LDP/abs/html/exitcodes.html
    public const SUCCESS = 0;
    public const FAILURE = 1;
    public const INVALID = 2;

    /**
     * @return array<string, string>
     */
    public static function subscribedTo(): array;
}
