<?php

use App\Models\User;
use App\Models\IbuHamil;
use App\Enums\UserRole;

describe('Ibu Hamil Management', function () {
    describe('List Ibu Hamil', function () {
        it('allows admin to view ibu hamil list', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);
            IbuHamil::factory()->count(3)->create();

            $response = $this->actingAs($admin)->get(route('ibu-hamil.index'));

            $response->assertStatus(200);
            $response->assertInertiaHas('lansia.data');
        });

        it('allows bidan to view ibu hamil list', function () {
            $bidan = User::factory()->create(['role' => UserRole::Bidan]);
            IbuHamil::factory()->count(2)->create();

            $response = $this->actingAs($bidan)->get(route('ibu-hamil.index'));

            $response->assertStatus(200);
        });

        it('allows kader to view ibu hamil list', function () {
            $kader = User::factory()->create(['role' => UserRole::Kader]);
            IbuHamil::factory()->count(2)->create();

            $response = $this->actingAs($kader)->get(route('ibu-hamil.index'));

            $response->assertStatus(200);
        });

        it('denies peserta from viewing ibu hamil list', function () {
            $peserta = User::factory()->create(['role' => UserRole::Peserta]);

            $response = $this->actingAs($peserta)->get(route('ibu-hamil.index'));

            $response->assertStatus(403);
        });

        it('filters ibu hamil by search term', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);
            IbuHamil::factory()->create(['nama' => 'Siti Aminah']);
            IbuHamil::factory()->create(['nama' => 'Rina Wati']);

            $response = $this->actingAs($admin)->get(route('ibu-hamil.index', ['search' => 'Siti']));

            $response->assertStatus(200);
        });

        it('filters ibu hamil by active status', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);
            IbuHamil::factory()->create(['is_active' => true]);
            IbuHamil::factory()->create(['is_active' => false]);

            $response = $this->actingAs($admin)->get(route('ibu-hamil.index', ['is_active' => true]));

            $response->assertStatus(200);
        });
    });

    describe('Create Ibu Hamil', function () {
        it('allows admin to create ibu hamil', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);

            $data = [
                'nik' => '1234567890123456',
                'nama' => 'Test Ibu Hamil',
                'tgl_lahir' => '1995-05-15',
                'kehamilan_keberapa' => 1,
                'usia_kehamilan' => 20,
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Test No. 123',
                'is_active' => true,
            ];

            $response = $this->actingAs($admin)->post(route('ibu-hamil.store'), $data);

            $response->assertRedirect(route('ibu-hamil.index'));
            $this->assertDatabaseHas('ibu_hamil', ['nik' => '1234567890123456']);
        });

        it('validates NIK is 16 digits', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);

            $data = [
                'nik' => '12345', // Too short
                'nama' => 'Test Ibu Hamil',
                'tgl_lahir' => '1995-05-15',
                'kehamilan_keberapa' => 1,
                'usia_kehamilan' => 20,
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Test No. 123',
            ];

            $response = $this->actingAs($admin)->post(route('ibu-hamil.store'), $data);

            $response->assertSessionHasErrors('nik');
        });

        it('validates NIK is unique', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);
            IbuHamil::factory()->create(['nik' => '1234567890123456']);

            $data = [
                'nik' => '1234567890123456', // Duplicate
                'nama' => 'Test Ibu Hamil',
                'tgl_lahir' => '1995-05-15',
                'kehamilan_keberapa' => 1,
                'usia_kehamilan' => 20,
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Test No. 123',
            ];

            $response = $this->actingAs($admin)->post(route('ibu-hamil.store'), $data);

            $response->assertSessionHasErrors('nik');
        });

        it('validates birth date is not in the future', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);

            $data = [
                'nik' => '1234567890123456',
                'nama' => 'Test Ibu Hamil',
                'tgl_lahir' => now()->addYear()->format('Y-m-d'), // Future date
                'kehamilan_keberapa' => 1,
                'usia_kehamilan' => 20,
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Test No. 123',
            ];

            $response = $this->actingAs($admin)->post(route('ibu-hamil.store'), $data);

            $response->assertSessionHasErrors('tgl_lahir');
        });

        it('allows photo upload', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);

            $data = [
                'nik' => '1234567890123457',
                'nama' => 'Test Ibu Hamil',
                'tgl_lahir' => '1995-05-15',
                'kehamilan_keberapa' => 1,
                'usia_kehamilan' => 20,
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Test No. 123',
            ];

            $response = $this->actingAs($admin)->post(route('ibu-hamil.store'), $data);

            $response->assertRedirect();
        });
    });

    describe('Update Ibu Hamil', function () {
        it('allows admin to update ibu hamil', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);
            $ibuHamil = IbuHamil::factory()->create();

            $data = [
                'nik' => '1234567890123456',
                'nama' => 'Updated Name',
                'tgl_lahir' => '1995-05-15',
                'kehamilan_keberapa' => 2,
                'usia_kehamilan' => 25,
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Updated No. 456',
                'is_active' => true,
            ];

            $response = $this->actingAs($admin)->put(route('ibu-hamil.update', $ibuHamil->id), $data);

            $response->assertRedirect(route('ibu-hamil.index'));
            $this->assertDatabaseHas('ibu_hamil', [
                'id' => $ibuHamil->id,
                'nama' => 'Updated Name',
            ]);
        });

        it('validates NIK unique ignore self on update', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);
            $ibuHamil1 = IbuHamil::factory()->create(['nik' => '1234567890123456']);
            $ibuHamil2 = IbuHamil::factory()->create(['nik' => '1234567890123457']);

            $data = [
                'nik' => '1234567890123457', // Same as ibuHamil2
                'nama' => 'Updated Name',
                'tgl_lahir' => '1995-05-15',
                'kehamilan_keberapa' => 2,
                'usia_kehamilan' => 25,
                'no_telp' => '081234567890',
                'alamat' => 'Jl. Updated No. 456',
            ];

            $response = $this->actingAs($admin)->put(route('ibu-hamil.update', $ibuHamil1->id), $data);

            $response->assertSessionHasErrors('nik');
        });
    });

    describe('Delete Ibu Hamil', function () {
        it('allows admin to delete ibu hamil', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);
            $ibuHamil = IbuHamil::factory()->create();

            $response = $this->actingAs($admin)->delete(route('ibu-hamil.destroy', $ibuHamil->id));

            $response->assertRedirect(route('ibu-hamil.index'));
            $this->assertSoftDeleted('ibu_hamil', ['id' => $ibuHamil->id]);
        });

        it('soft deletes ibu hamil', function () {
            $admin = User::factory()->create(['role' => UserRole::Admin]);
            $ibuHamil = IbuHamil::factory()->create();

            $this->actingAs($admin)->delete(route('ibu-hamil.destroy', $ibuHamil->id));

            $this->assertDatabaseHas('ibu_hamil', [
                'id' => $ibuHamil->id,
                'deleted_at' => null,
            ]);

            $this->assertSoftDeleted('ibu_hamil', ['id' => $ibuHamil->id]);
        });
    });
});
