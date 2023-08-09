<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain;

interface UuidGenerator
{
    public function generate(): string;
}
