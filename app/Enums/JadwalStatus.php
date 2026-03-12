<?php

namespace App\Enums;

enum JadwalStatus: string
{
    case Draft = 'draft';
    case Validated = 'validated';
    case Rejected = 'rejected';
    case Completed = 'completed';

    public function label(): string
    {
        return match($this) {
            self::Draft => 'Draft',
            self::Validated => 'Divalidasi',
            self::Rejected => 'Ditolak',
            self::Completed => 'Selesai',
        };
    }
}
