<?php

use App\Helpers\UmurHelper;

describe('UmurHelper', function () {
    it('calculates baby age in months correctly', function () {
        $birthDate = now()->subMonths(6)->subDays(15);
        $umur = UmurHelper::hitungUmurBalita($birthDate);
        
        expect($umur)->toBeGreaterThan(5)->toBeLessThan(7);
    });

    it('calculates elderly age in years correctly', function () {
        $birthDate = now()->subYears(60)->subMonths(3);
        $umur = UmurHelper::hitungUmurLansia($birthDate);
        
        expect($umur)->toBeGreaterThanOrEqual(60);
    });

    it('returns 0 for baby born today', function () {
        $birthDate = now();
        $umur = UmurHelper::hitungUmurBalita($birthDate);
        
        expect($umur)->toBe(0);
    });

    it('formats elderly age with years and months', function () {
        $birthDate = now()->subYears(65)->subMonths(4);
        $format = UmurHelper::formatUmur($birthDate);
        
        expect($format)->toContain('Tahun');
    });

    it('formats baby age in months only', function () {
        $birthDate = now()->subMonths(6);
        $format = UmurHelper::formatUmur($birthDate);
        
        expect($format)->toContain('Bulan');
    });

    it('returns 0 bulan for future date', function () {
        $birthDate = now()->addDays(5);
        $format = UmurHelper::formatUmur($birthDate);
        
        expect($format)->toBe('0 Bulan');
    });
});
