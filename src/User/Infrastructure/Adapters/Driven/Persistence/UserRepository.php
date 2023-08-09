<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Adapters\Driven\Persistence;

use App\User\Core\Model\User;
use App\User\Core\Ports\Outgoing\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function saveUser(User $user): void
    {
        // TODO: Implement saveUser() method.
    }
}
