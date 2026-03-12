<?php

namespace App\Helpers;

use Carbon\Carbon;

class NikHelper
{
    public static function isValid(string $nik): bool
    {
        return preg_match('/^\d{16}$/', $nik) === 1;
    }

    public static function getTanggalLahir(string $nik): ?Carbon
    {
        if (!self::isValid($nik)) {
            return null;
        }

        $tanggal = substr($nik, 6, 2);
        $bulan = substr($nik, 8, 2);
        $tahun = substr($nik, 10, 2);

        // Jika perempuan, tanggal > 40
        $tglInt = (int)$tanggal;
        if ($tglInt > 40) {
            $tglInt -= 40;
        }

        // Tentukan abad (asumsi simplistis)
        $tahunInt = (int)$tahun;
        $tahunLengkap = $tahunInt > (int)date('y') ? 1900 + $tahunInt : 2000 + $tahunInt;

        try {
            return Carbon::createFromDate($tahunLengkap, (int)$bulan, $tglInt);
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function getJenisKelamin(string $nik): ?string
    {
        if (!self::isValid($nik)) {
            return null;
        }

        $tanggal = (int)substr($nik, 6, 2);
        return $tanggal > 40 ? 'P' : 'L';
    }
}
