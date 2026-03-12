<?php

namespace App\Policies;

use App\Enums\JadwalStatus;
use App\Enums\UserRole;
use App\Models\JadwalPosyandu;
use App\Models\User;

class JadwalPosyanduPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader, UserRole::Peserta]);
    }

    public function view(User $user, JadwalPosyandu $jadwal): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader, UserRole::Peserta]);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function update(User $user, JadwalPosyandu $jadwal): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function delete(User $user, JadwalPosyandu $jadwal): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    /**
     * Only bidan and admin can validate jadwal.
     */
    public function validate(User $user, JadwalPosyandu $jadwal): bool
    {
        if (! in_array($user->role, [UserRole::Admin, UserRole::Bidan])) {
            return false;
        }

        return $jadwal->status === JadwalStatus::Draft;
    }

    /**
     * Only bidan and admin can reject jadwal.
     */
    public function reject(User $user, JadwalPosyandu $jadwal): bool
    {
        if (! in_array($user->role, [UserRole::Admin, UserRole::Bidan])) {
            return false;
        }

        return $jadwal->status === JadwalStatus::Draft;
    }

    /**
     * Mark jadwal as completed.
     */
    public function complete(User $user, JadwalPosyandu $jadwal): bool
    {
        if (! in_array($user->role, [UserRole::Admin, UserRole::Bidan])) {
            return false;
        }

        return $jadwal->status === JadwalStatus::Validated;
    }

    public function restore(User $user, JadwalPosyandu $jadwal): bool
    {
        return $user->role === UserRole::Admin;
    }

    public function forceDelete(User $user, JadwalPosyandu $jadwal): bool
    {
        return $user->role === UserRole::Admin;
    }
}
