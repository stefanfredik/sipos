<?php

namespace App\Enums;

enum PesertaType: string
{
    case IbuHamil = 'ibu_hamil';
    case Balita = 'balita';
    case Lansia = 'lansia';

    public function label(): string
    {
        return match($this) {
            self::IbuHamil => 'Ibu Hamil',
            self::Balita => 'Balita',
            self::Lansia => 'Lansia',
        };
    }
}
