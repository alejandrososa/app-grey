<?php

declare(strict_types=1);

namespace App\Core\Shared\Domain\Slug;

interface Sluger
{
    /**
     * @param array<mixed>|null $options
     */
    public function slugify(string $string, array $options = null): string;
}
