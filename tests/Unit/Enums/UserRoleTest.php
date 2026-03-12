<?php

use App\Enums\UserRole;
use App\Enums\JadwalStatus;
use App\Enums\PesertaType;

describe('Enums', function () {
    describe('UserRole', function () {
        it('has correct role values', function () {
            expect(UserRole::Admin->value)->toBe('admin');
            expect(UserRole::Bidan->value)->toBe('bidan');
            expect(UserRole::Kader->value)->toBe('kader');
            expect(UserRole::Peserta->value)->toBe('peserta');
        });

        it('returns correct label for admin', function () {
            expect(UserRole::Admin->label())->toBe('Admin');
        });

        it('returns correct label for bidan', function () {
            expect(UserRole::Bidan->label())->toBe('Bidan');
        });

        it('returns correct label for kader', function () {
            expect(UserRole::Kader->label())->toBe('Kader');
        });

        it('returns correct label for peserta', function () {
            expect(UserRole::Peserta->label())->toBe('Peserta');
        });
    });

    describe('JadwalStatus', function () {
        it('has correct status values', function () {
            expect(JadwalStatus::Draft->value)->toBe('draft');
            expect(JadwalStatus::Validated->value)->toBe('validated');
            expect(JadwalStatus::Rejected->value)->toBe('rejected');
            expect(JadwalStatus::Completed->value)->toBe('completed');
        });

        it('returns correct label for draft', function () {
            expect(JadwalStatus::Draft->label())->toBe('Draft');
        });

        it('returns correct label for validated', function () {
            expect(JadwalStatus::Validated->label())->toBe('Divalidasi');
        });

        it('returns correct label for rejected', function () {
            expect(JadwalStatus::Rejected->label())->toBe('Ditolak');
        });

        it('returns correct label for completed', function () {
            expect(JadwalStatus::Completed->label())->toBe('Selesai');
        });
    });

    describe('PesertaType', function () {
        it('has correct type values', function () {
            expect(PesertaType::IbuHamil->value)->toBe('ibu_hamil');
            expect(PesertaType::Balita->value)->toBe('balita');
            expect(PesertaType::Lansia->value)->toBe('lansia');
        });

        it('returns correct label for ibu hamil', function () {
            expect(PesertaType::IbuHamil->label())->toBe('Ibu Hamil');
        });

        it('returns correct label for balita', function () {
            expect(PesertaType::Balita->label())->toBe('Balita');
        });

        it('returns correct label for lansia', function () {
            expect(PesertaType::Lansia->label())->toBe('Lansia');
        });
    });
});
