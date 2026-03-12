<?php

use App\Models\User;
use App\Models\Balita;
use App\Enums\UserRole;

describe('Balita Management', function () {
    describe('List Balita', function () {
        it('allows admin to view balita list', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);
            Balita::factory()->count(3)->create();

            $response = $this->actingAs($admin)->get(route('balita.index'));

            $response->assertStatus(200);
        });

        it('allows bidan to view balita list', function () {
            $bidan = User::factory()->create(['role' => UserRole::Bidan]);
            Balita::factory()->count(2)->create();

            $response = $this->actingAs($bidan)->get(route('balita.index'));

            $response->assertStatus(200);
        });

        it('allows kader to view balita list', function () {
            $kader = User::factory()->create(['role' => UserRole::Kader]);
            Balita::factory()->count(2)->create();

            $response = $this->actingAs($kader)->get(route('balita.index'));

            $response->assertStatus(200);
        });

        it('denies peserta from viewing balita list', function () {
            $peserta = User::factory()->create(['role' => UserRole::Peserta]);

            $response = $this->actingAs($peserta)->get(route('balita.index'));

            $response->assertStatus(403);
        });
    });

    describe('Create Balita', function () {
        it('allows admin to create balita', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);

            $data = [
                'nik' => '1234567890123456',
                'nama' => 'Test Balita',
                'nama_orang_tua' => 'Orang Tua Test',
                'tgl_lahir' => now()->subMonths(12)->format('Y-m-d'),
                'jenis_kelamin' => 'L',
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Test No. 123',
                'is_active' => true,
            ];

            $response = $this->actingAs($admin)->post(route('balita.store'), $data);

            $response->assertRedirect(route('balita.index'));
            $this->assertDatabaseHas('balita', ['nik' => '1234567890123456']);
        });

        it('validates NIK is 16 digits', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);

            $data = [
                'nik' => '12345',
                'nama' => 'Test Balita',
                'nama_orang_tua' => 'Orang Tua Test',
                'tgl_lahir' => now()->subMonths(12)->format('Y-m-d'),
                'jenis_kelamin' => 'L',
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Test No. 123',
            ];

            $response = $this->actingAs($admin)->post(route('balita.store'), $data);

            $response->assertSessionHasErrors('nik');
        });

        it('validates jenis_kelamin is L or P', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);

            $data = [
                'nik' => '1234567890123456',
                'nama' => 'Test Balita',
                'nama_orang_tua' => 'Orang Tua Test',
                'tgl_lahir' => now()->subMonths(12)->format('Y-m-d'),
                'jenis_kelamin' => 'X', // Invalid
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Test No. 123',
            ];

            $response = $this->actingAs($admin)->post(route('balita.store'), $data);

            $response->assertSessionHasErrors('jenis_kelamin');
        });
    });

    describe('Delete Balita', function () {
        it('allows admin to delete balita', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);
            $balita = Balita::factory()->create();

            $response = $this->actingAs($admin)->delete(route('balita.destroy', $balita->id));

            $response->assertRedirect(route('balita.index'));
            $this->assertSoftDeleted('balita', ['id' => $balita->id]);
        });
    });
});
