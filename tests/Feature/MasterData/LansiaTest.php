<?php

use App\Models\User;
use App\Models\Lansia;
use App\Enums\UserRole;

describe('Lansia Management', function () {
    describe('List Lansia', function () {
        it('allows admin to view lansia list', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);
            Lansia::factory()->count(3)->create();

            $response = $this->actingAs($admin)->get(route('lansia.index'));

            $response->assertStatus(200);
        });

        it('allows bidan to view lansia list', function () {
            $bidan = User::factory()->create(['role' => UserRole::Bidan]);
            Lansia::factory()->count(2)->create();

            $response = $this->actingAs($bidan)->get(route('lansia.index'));

            $response->assertStatus(200);
        });

        it('allows kader to view lansia list', function () {
            $kader = User::factory()->create(['role' => UserRole::Kader]);
            Lansia::factory()->count(2)->create();

            $response = $this->actingAs($kader)->get(route('lansia.index'));

            $response->assertStatus(200);
        });

        it('denies peserta from viewing lansia list', function () {
            $peserta = User::factory()->create(['role' => UserRole::Peserta]);

            $response = $this->actingAs($peserta)->get(route('lansia.index'));

            $response->assertStatus(403);
        });
    });

    describe('Create Lansia', function () {
        it('allows admin to create lansia', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);

            $data = [
                'nik' => '1234567890123456',
                'nama' => 'Test Lansia',
                'tgl_lahir' => now()->subYears(60)->format('Y-m-d'),
                'jenis_kelamin' => 'L',
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Test No. 123',
                'is_active' => true,
            ];

            $response = $this->actingAs($admin)->post(route('lansia.store'), $data);

            $response->assertRedirect(route('lansia.index'));
            $this->assertDatabaseHas('lansia', ['nik' => '1234567890123456']);
        });

        it('validates NIK is 16 digits', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);

            $data = [
                'nik' => '12345',
                'nama' => 'Test Lansia',
                'tgl_lahir' => now()->subYears(60)->format('Y-m-d'),
                'jenis_kelamin' => 'L',
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Test No. 123',
            ];

            $response = $this->actingAs($admin)->post(route('lansia.store'), $data);

            $response->assertSessionHasErrors('nik');
        });
    });

    describe('Edit Lansia', function () {
        it('allows admin to edit lansia', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);
            $lansia = Lansia::factory()->create();

            $response = $this->actingAs($admin)->get(route('lansia.edit', $lansia->id));

            $response->assertStatus(200);
            $response->assertInertiaHas('lansia');
        });

        it('returns 404 for non-existent lansia', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);

            $response = $this->actingAs($admin)->get(route('lansia.edit', 'non-existent-id'));

            $response->assertStatus(404);
        });
    });

    describe('Delete Lansia', function () {
        it('allows admin to delete lansia', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);
            $lansia = Lansia::factory()->create();

            $response = $this->actingAs($admin)->delete(route('lansia.destroy', $lansia->id));

            $response->assertRedirect(route('lansia.index'));
            $this->assertSoftDeleted('lansia', ['id' => $lansia->id]);
        });
    });
});
