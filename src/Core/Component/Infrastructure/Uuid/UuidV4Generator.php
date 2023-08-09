<?php

namespace App\Core\Component\Infrastructure\Uuid;

use App\Core\Shared\Domain\UuidGenerator;
use Symfony\Component\Uid\Uuid;

class UuidV4Generator implements UuidGenerator
{
    public function generate(): string
    {
        return Uuid::v4()->toRfc4122();
    }
}
