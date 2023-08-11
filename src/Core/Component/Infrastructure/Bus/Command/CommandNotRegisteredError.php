<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Bus\Command;

use App\Core\Shared\Domain\Bus\Command\Command;

final class CommandNotRegisteredError extends \RuntimeException
{
    public function __construct(Command $command)
    {
        $commandClass = $command::class;

        parent::__construct("The command <{$commandClass}> hasn't a command handler associated");
    }
}
