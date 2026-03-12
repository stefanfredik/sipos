<?php

namespace App\Helpers;

use Carbon\Carbon;

class UmurHelper
{
    /**
     * Hitung umur balita dalam bulan.
     */
    public static function hitungUmurBalita(string|Carbon $tglLahir): int
    {
        $lahir = Carbon::parse($tglLahir);
        return (int)$lahir->diffInMonths(Carbon::now());
    }

    /**
     * Hitung umur lansia dalam tahun.
     */
    public static function hitungUmurLansia(string|Carbon $tglLahir): int
    {
        $lahir = Carbon::parse($tglLahir);
        return (int)$lahir->diffInYears(Carbon::now());
    }

    /**
     * Format umur untuk display.
     */
    public static function formatUmur(string|Carbon $tglLahir): string
    {
        $lahir = Carbon::parse($tglLahir);
        $diff = $lahir->diff(Carbon::now());

        if ($diff->y > 0) {
            return "{$diff->y} Tahun";
        }

        return "{$diff->m} Bulan";
    }
}
