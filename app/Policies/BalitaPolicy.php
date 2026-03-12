<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Balita;
use App\Models\User;

class BalitaPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function view(User $user, Balita $balita): bool
    {
        if (in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader])) {
            return true;
        }

        return $user->role === UserRole::Peserta && $balita->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function update(User $user, Balita $balita): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function delete(User $user, Balita $balita): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function restore(User $user, Balita $balita): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function forceDelete(User $user, Balita $balita): bool
    {
        return $user->role === UserRole::Admin;
    }
}
