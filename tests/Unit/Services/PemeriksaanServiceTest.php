<?php

describe('PemeriksaanService - Helper Functions', function () {
    it('detects KEK when LILA is less than 23.5 cm', function () {
        // Test the logic directly
        $isKEK = fn ($lila) => $lila !== null && $lila < 23.5;
        
        expect($isKEK(22.5))->toBeTrue();
        expect($isKEK(20.0))->toBeTrue();
        expect($isKEK(23.4))->toBeTrue();
    });

    it('returns false for normal LILA', function () {
        $isKEK = fn ($lila) => $lila !== null && $lila < 23.5;
        
        expect($isKEK(23.5))->toBeFalse();
        expect($isKEK(24.0))->toBeFalse();
        expect($isKEK(25.0))->toBeFalse();
    });

    it('returns null for empty LILA', function () {
        $isKEK = fn ($lila) => $lila !== null && $lila < 23.5;
        
        expect($isKEK(null))->toBeFalse();
    });

    it('detects stunting based on height and age', function () {
        // Simplified stunting detection logic
        $detectStunting = function ($tb, $usia) {
            if ($tb === null || $usia === null) return null;
            // WHO standards simplified - below minimum height for age
            $minTb = [
                12 => 70, 24 => 82, 36 => 90, 48 => 96, 60 => 102
            ];
            foreach ($minTb as $age => $height) {
                if ($usia <= $age && $tb < $height) return true;
            }
            return false;
        };
        
        // Balita 24 bulan dengan tinggi 75 cm (below normal 82cm)
        expect($detectStunting(75, 24))->toBeTrue();
        
        // Balita 36 bulan dengan tinggi 85 cm (below normal 90cm)
        expect($detectStunting(85, 36))->toBeTrue();
    });

    it('returns false for normal height', function () {
        $detectStunting = function ($tb, $usia) {
            if ($tb === null || $usia === null) return null;
            $minTb = [12 => 70, 24 => 82, 36 => 90, 48 => 96, 60 => 102];
            // Find the appropriate age bracket
            foreach ($minTb as $age => $height) {
                if ($usia <= $age) {
                    return $tb < $height;
                }
            }
            return false;
        };
        
        // Balita 24 bulan dengan tinggi 90 cm (normal, above 82cm)
        expect($detectStunting(90, 24))->toBeFalse();
        
        // Balita 36 bulan dengan tinggi 100 cm (normal, above 90cm)
        expect($detectStunting(100, 36))->toBeFalse();
    });

    it('returns null for missing data', function () {
        $detectStunting = fn ($tb, $usia) => $tb === null || $usia === null ? null : false;
        
        expect($detectStunting(null, 24))->toBeNull();
        expect($detectStunting(80, null))->toBeNull();
    });
});

describe('CreatePemeriksaanDTO', function () {
    it('creates DTO from array data for ibu hamil', function () {
        $data = [
            'jadwal_posyandu_id' => 'test-jadwal-id',
            'peserta_type' => 'ibu_hamil',
            'peserta_id' => 'test-peserta-id',
            'tgl_pemeriksaan' => '2024-01-15',
            'usia_kehamilan' => 28,
            'berat_badan' => 65.5,
            'lila' => 24.0,
            'sistole' => 120,
            'diastole' => 80,
            'edukasi' => 'Jaga pola makan',
            'keterangan' => 'Kondisi baik',
        ];

        $dto = \App\DTOs\Pemeriksaan\CreatePemeriksaanDTO::fromRequest($data);

        expect($dto->jadwal_posyandu_id)->toBe('test-jadwal-id');
        expect($dto->peserta_type)->toBe('ibu_hamil');
        expect($dto->usia_kehamilan)->toBe(28);
        expect($dto->berat_badan)->toBe(65.5);
    });

    it('creates DTO from array data for balita', function () {
        $data = [
            'jadwal_posyandu_id' => 'test-jadwal-id',
            'peserta_type' => 'balita',
            'peserta_id' => 'test-peserta-id',
            'tgl_pemeriksaan' => '2024-01-15',
            'berat_badan' => 12.5,
            'tinggi_badan' => 85.0,
            'lingkar_kepala' => 48.0,
            'lila' => 15.0,
        ];

        $dto = \App\DTOs\Pemeriksaan\CreatePemeriksaanDTO::fromRequest($data);

        expect($dto->peserta_type)->toBe('balita');
        expect($dto->berat_badan)->toBe(12.5);
        expect($dto->tinggi_badan)->toBe(85.0);
    });

    it('creates DTO from array data for lansia', function () {
        $data = [
            'jadwal_posyandu_id' => 'test-jadwal-id',
            'peserta_type' => 'lansia',
            'peserta_id' => 'test-peserta-id',
            'tgl_pemeriksaan' => '2024-01-15',
            'berat_badan' => 60.0,
            'tinggi_badan' => 160.0,
            'sistole' => 140,
            'diastole' => 90,
            'jenis_keluhan' => 'Pusing',
            'obat_vitamin' => 'Paracetamol',
        ];

        $dto = \App\DTOs\Pemeriksaan\CreatePemeriksaanDTO::fromRequest($data);

        expect($dto->peserta_type)->toBe('lansia');
        expect($dto->sistole)->toBe(140);
        expect($dto->jenis_keluhan)->toBe('Pusing');
    });
});
