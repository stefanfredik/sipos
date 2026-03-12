<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Bidan;
use App\Models\User;

class BidanPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin]);
    }

    public function view(User $user, Bidan $bidan): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, Bidan $bidan): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Bidan $bidan): bool
    {
        return $user->role === UserRole::Admin;
    }
}
