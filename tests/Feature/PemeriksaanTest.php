<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Balita;
use App\Models\JadwalPosyandu;
use App\Models\Pemeriksaan;
use App\Models\Kader;
use App\Models\Bidan;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view pemeriksaan index', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    Pemeriksaan::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('pemeriksaan.index'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Pemeriksaan/Index')
        ->has('pemeriksaan.data', 3)
    );
});

test('admin can create pemeriksaan for balita', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $jadwal = JadwalPosyandu::factory()->create();
    $balita = Balita::factory()->create();
    $kader = Kader::factory()->create();
    $bidan = Bidan::factory()->create();

    $data = [
        'jadwal_posyandu_id' => $jadwal->id,
        'peserta_type' => 'balita',
        'peserta_id' => $balita->id,
        'kader_id' => $kader->id,
        'bidan_id' => $bidan->id,
        'tgl_pemeriksaan' => now()->toDateString(),
        'berat_badan' => 10.5,
        'tinggi_badan' => 80.0,
        'hadir' => true,
    ];

    $response = $this->actingAs($admin)->post(route('pemeriksaan.store'), $data);

    $response->assertStatus(302); // Redirect back
    $this->assertDatabaseHas('pemeriksaan', [
        'peserta_id' => $balita->id,
        'berat_badan' => 10.5
    ]);
});
