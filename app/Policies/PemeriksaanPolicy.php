<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Pemeriksaan;
use App\Models\User;

class PemeriksaanPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function view(User $user, Pemeriksaan $pemeriksaan): bool
    {
        if (in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader])) {
            return true;
        }

        // Peserta can view own pemeriksaan
        if ($user->role === UserRole::Peserta) {
            $peserta = $pemeriksaan->peserta;
            return $peserta && $peserta->user_id === $user->id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function update(User $user, Pemeriksaan $pemeriksaan): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function delete(User $user, Pemeriksaan $pemeriksaan): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function restore(User $user, Pemeriksaan $pemeriksaan): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function forceDelete(User $user, Pemeriksaan $pemeriksaan): bool
    {
        return $user->role === UserRole::Admin;
    }
}
