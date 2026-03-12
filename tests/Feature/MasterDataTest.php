<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Posyandu;
use App\Models\Kader;
use App\Models\Bidan;
use App\Enums\UserRole;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can view posyandu index', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    Posyandu::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('posyandu.index'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Posyandu/Index')
        ->has('posyandu.data', 3)
    );
});

test('admin can create posyandu', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $data = [
        'nama_posyandu' => 'Posyandu Mawar',
        'lokasi' => 'Dusun A',
        'deskripsi' => 'Deskripsi posyandu',
        'is_active' => true,
    ];

    $response = $this->actingAs($admin)->post(route('posyandu.store'), $data);

    $response->assertRedirect(route('posyandu.index'));
    $this->assertDatabaseHas('posyandu', ['nama_posyandu' => 'Posyandu Mawar']);
});

test('admin can update posyandu', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $posyandu = Posyandu::factory()->create();
    $data = [
        'nama_posyandu' => 'Posyandu Melati',
        'lokasi' => 'Dusun B',
        'deskripsi' => 'Updated description',
        'is_active' => false,
    ];

    $response = $this->actingAs($admin)->put(route('posyandu.update', $posyandu->id), $data);

    $response->assertRedirect(route('posyandu.index'));
    $this->assertDatabaseHas('posyandu', ['id' => $posyandu->id, 'nama_posyandu' => 'Posyandu Melati']);
});

test('admin can delete posyandu', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    $posyandu = Posyandu::factory()->create();

    $response = $this->actingAs($admin)->delete(route('posyandu.destroy', $posyandu->id));

    $response->assertRedirect(route('posyandu.index'));
    $this->assertSoftDeleted('posyandu', ['id' => $posyandu->id]);
});

test('admin can view kader index', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    Kader::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('kader.index'));

    $response->assertStatus(200);
});

test('admin can view bidan index', function () {
    $admin = User::factory()->create(['role' => UserRole::Admin]);
    Bidan::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('bidan.index'));

    $response->assertStatus(200);
});
