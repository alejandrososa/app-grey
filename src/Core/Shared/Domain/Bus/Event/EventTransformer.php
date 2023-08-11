<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Bus\Event;

/** @phpstan-consistent-constructor */
interface EventTransformer
{
    /** @param array<mixed> $data */
    public function write(array $data): self;

    /** @return EventReceived|Event|array<mixed> */
    public function read(): EventReceived|Event|array;
}
