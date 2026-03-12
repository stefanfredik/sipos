<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Balita;
use App\Models\IbuHamil;
use App\Models\Lansia;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view balita index', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    Balita::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('balita.index'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Balita/Index')
        ->has('balita.data', 3)
    );
});

test('admin can create balita', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $data = [
        'nik' => '1234567890123456',
        'nama' => 'Balita A',
        'nama_orang_tua' => 'Ortu A',
        'tgl_lahir' => '2023-01-01',
        'jenis_kelamin' => 'L',
        'no_telp' => '08123456789',
        'alamat' => 'Alamat A',
        'is_active' => true,
    ];

    $response = $this->actingAs($admin)->post(route('balita.store'), $data);

    $response->assertRedirect(route('balita.index'));
    $this->assertDatabaseHas('balita', ['nik' => '1234567890123456']);
});

test('admin can view ibu hamil index', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    IbuHamil::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('ibu-hamil.index'));

    $response->assertStatus(200);
});

test('admin can view lansia index', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    Lansia::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('lansia.index'));

    $response->assertStatus(200);
});
