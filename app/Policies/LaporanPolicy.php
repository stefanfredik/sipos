<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class LaporanPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function exportPdf(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }

    public function exportExcel(User $user): bool
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Bidan, UserRole::Kader]);
    }
}
