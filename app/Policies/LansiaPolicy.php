<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Lansia;
use App\Models\User;

class LansiaPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function view(User $user, Lansia $lansia): bool
    {
        if (in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader])) {
            return true;
        }

        return $user->role === UserRole::Peserta && $lansia->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function update(User $user, Lansia $lansia): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function delete(User $user, Lansia $lansia): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function restore(User $user, Lansia $lansia): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function forceDelete(User $user, Lansia $lansia): bool
    {
        return $user->role === UserRole::Admin;
    }
}
