<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Uuid;

use Symfony\Component\Uid\Uuid;
use App\Core\Shared\Domain\UuidGenerator;

class UuidV4Generator implements UuidGenerator
{
    public function generate(): string
    {
        return Uuid::v4()->toRfc4122();
    }
}
