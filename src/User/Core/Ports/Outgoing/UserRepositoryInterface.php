<?php

declare(strict_types=1);

namespace App\User\Core\Ports\Outgoing;

use App\User\Core\Model\User;

interface UserRepositoryInterface
{
    public function saveUser(User $user): void;
}
