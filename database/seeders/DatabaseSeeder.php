<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::factory()->admin()->create([
            'nama_user' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@sipos.id',
            'password' => Hash::make('password'),
        ]);

        // 2 Bidan
        User::factory()->bidan()->create([
            'nama_user' => 'Ni Wayan Yudiasih',
            'username' => 'bidan1',
            'email' => 'bidan1@sipos.id',
            'password' => Hash::make('password'),
        ]);
        User::factory()->bidan()->create([
            'nama_user' => 'Ni Made Suartini',
            'username' => 'bidan2',
            'email' => 'bidan2@sipos.id',
            'password' => Hash::make('password'),
        ]);

        // 8 Kader (1 per posyandu)
        for ($i = 1; $i <= 8; $i++) {
            User::factory()->kader()->create([
                'nama_user' => "Kader {$i}",
                'username' => "kader{$i}",
                'email' => "kader{$i}@sipos.id",
                'password' => Hash::make('password'),
            ]);
        }

        // 1 Peserta demo
        User::factory()->peserta()->create([
            'nama_user' => 'Peserta Demo',
            'username' => 'peserta1',
            'email' => 'peserta1@sipos.id',
            'password' => Hash::make('password'),
        ]);

        // Posyandu
        $this->call(PosyanduSeeder::class);

        // Demo data
        $this->call(DemoDataSeeder::class);
    }
}
