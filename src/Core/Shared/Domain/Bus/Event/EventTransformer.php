<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Bus\Event;

interface EventTransformer
{
    /** @param array<string, string> $data */
    public function write(array $data): self;

    public function read(): EventReceived|Event;
}
