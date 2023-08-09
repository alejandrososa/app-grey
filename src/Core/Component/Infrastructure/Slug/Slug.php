<?php
declare(strict_types=1);

namespace App\Core\Component\Infrastructure\Slug;

use Cocur\Slugify\Slugify;
use App\Core\Shared\Domain\Slug\Sluger;

class Slug implements Sluger
{
    /**
     * @var Slugify
     */
    private $slugify;

    public function __construct()
    {
        $this->slugify = new Slugify(
            [
                'separator' => '-',
                'lowercase_after_regexp' => false,
            ]
        );
    }

    public function slugify($string, $options = null): string
    {
        return $this->slugify->slugify($string, $options);
    }
}