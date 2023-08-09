<?php

declare(strict_types=1);

namespace App\User\Core\Ports\Incoming;

use App\User\Core\Model\User;

interface RegisterUserUseCaseInterface
{
    public function registerUser(User $user): void;
}
