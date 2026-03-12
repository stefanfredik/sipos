<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Posyandu;
use App\Models\User;

class PosyanduPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function view(User $user, Posyandu $posyandu): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function update(User $user, Posyandu $posyandu): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function delete(User $user, Posyandu $posyandu): bool
    {
        return $user->role === UserRole::Admin;
    }
}
