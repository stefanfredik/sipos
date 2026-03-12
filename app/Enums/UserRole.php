<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Bidan = 'bidan';
    case Kader = 'kader';
    case Peserta = 'peserta';

    public function label(): string
    {
        return match($this) {
            self::Admin => 'Admin',
            self::Bidan => 'Bidan',
            self::Kader => 'Kader',
            self::Peserta => 'Peserta',
        };
    }
}
