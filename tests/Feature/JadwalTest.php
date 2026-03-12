<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\JadwalPosyandu;
use App\Models\Posyandu;
use App\Models\Kader;
use App\Models\Bidan;
use App\Enums\UserRole;
use App\Enums\JadwalStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view jadwal index', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    JadwalPosyandu::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('jadwal-posyandu.index'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('JadwalPosyandu/Index')
        ->has('jadwal.data', 3)
    );
});

test('admin can create jadwal', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $posyandu = Posyandu::factory()->create();
    $kader = Kader::factory()->create();

    $data = [
        'posyandu_id' => $posyandu->id,
        'kader_id' => $kader->id,
        'tanggal' => now()->addDays(7)->toDateString(),
        'waktu_mulai' => '09:00',
        'waktu_selesai' => '11:00',
        'status' => 'draft',
    ];

    $response = $this->actingAs($admin)->post(route('jadwal-posyandu.store'), $data);

    $response->assertRedirect(route('jadwal-posyandu.index'));
    $this->assertDatabaseHas('jadwal_posyandu', ['posyandu_id' => $posyandu->id]);
});

test('admin can update jadwal status', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $jadwal = JadwalPosyandu::factory()->create(['status' => JadwalStatus::Draft]);
    $bidan = Bidan::factory()->create();

    $data = [
        'posyandu_id' => $jadwal->posyandu_id,
        'kader_id' => $jadwal->kader_id,
        'bidan_id' => $bidan->id,
        'tanggal' => $jadwal->tanggal->toDateString(),
        'waktu_mulai' => '09:00',
        'status' => 'validated',
    ];

    $response = $this->actingAs($admin)->put(route('jadwal-posyandu.update', $jadwal->id), $data);

    $response->assertRedirect(route('jadwal-posyandu.index'));
    $this->assertDatabaseHas('jadwal_posyandu', [
        'id' => $jadwal->id,
        'status' => 'validated'
    ]);
});
