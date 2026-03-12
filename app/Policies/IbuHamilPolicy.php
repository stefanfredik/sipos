<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\IbuHamil;
use App\Models\User;

class IbuHamilPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function view(User $user, IbuHamil $ibuHamil): bool
    {
        if (in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader])) {
            return true;
        }

        // Peserta can view own data
        return $user->role === UserRole::Peserta && $ibuHamil->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function update(User $user, IbuHamil $ibuHamil): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function delete(User $user, IbuHamil $ibuHamil): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function restore(User $user, IbuHamil $ibuHamil): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function forceDelete(User $user, IbuHamil $ibuHamil): bool
    {
        return $user->role === UserRole::Admin;
    }
}
