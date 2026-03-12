<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Kader;
use App\Models\User;

class KaderPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan]);
    }

    public function view(User $user, Kader $kader): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan]);
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, Kader $kader): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Kader $kader): bool
    {
        return $user->role === UserRole::Admin;
    }
}
