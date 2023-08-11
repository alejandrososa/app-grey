<?php

declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Slug;

use Cocur\Slugify\Slugify;
use App\Core\Shared\Domain\Slug\Sluger;

class Slug implements Sluger
{
    private Slugify $slugify;

    public function __construct()
    {
        $this->slugify = new Slugify(
            [
                'separator' => '-',
                'lowercase_after_regexp' => false,
            ]
        );
    }

    /**
     * @param array<mixed>|null $options
     */
    public function slugify(string $string, array $options = null): string
    {
        return $this->slugify->slugify($string, $options);
    }
}
