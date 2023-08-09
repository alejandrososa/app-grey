<?php
declare(strict_types=1);

namespace App\Core\Shared\Domain\Slug;

interface Sluger
{
    public function slugify($string, $options = null): string;
}