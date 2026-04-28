<?php

namespace Database\Seeders;

use App\Models\Bidan;
use App\Models\Kader;
use App\Models\Posyandu;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::create([
            'nama_user' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@sipos.id',
            'password' => Hash::make('password'),
            'role' => UserRole::Admin->value,
            'is_active' => true,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // 2 Bidan users
        $bidan1 = User::create([
            'nama_user' => 'Ni Wayan Yudiasih',
            'username' => 'bidan1',
            'email' => 'bidan1@sipos.id',
            'password' => Hash::make('password'),
            'role' => UserRole::Bidan->value,
            'is_active' => true,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        $bidan2 = User::create([
            'nama_user' => 'Ni Made Suartini',
            'username' => 'bidan2',
            'email' => 'bidan2@sipos.id',
            'password' => Hash::make('password'),
            'role' => UserRole::Bidan->value,
            'is_active' => true,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // 8 Kader users
        $kaders = [];
        for ($i = 1; $i <= 8; $i++) {
            $kaders[] = User::create([
                'nama_user' => "Kader {$i}",
                'username' => "kader{$i}",
                'email' => "kader{$i}@sipos.id",
                'password' => Hash::make('password'),
                'role' => UserRole::Kader->value,
                'is_active' => true,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]);
        }

        // 1 Peserta demo
        User::create([
            'nama_user' => 'Peserta Demo',
            'username' => 'peserta1',
            'email' => 'peserta1@sipos.id',
            'password' => Hash::make('password'),
            'role' => UserRole::Peserta->value,
            'is_active' => true,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Create Posyandu manually
        $posyanduData = [
            ['nama_posyandu' => 'Posyandu Belumbang Kaja', 'lokasi' => 'Br. Dinas Belumbang Kaja'],
            ['nama_posyandu' => 'Posyandu Belumbang Kelod', 'lokasi' => 'Br. Dinas Belumbang Kelod'],
            ['nama_posyandu' => 'Posyandu Belumbang Kawan', 'lokasi' => 'Br. Dinas Belumbang Kawan'],
            ['nama_posyandu' => 'Posyandu Belumbang Kangin', 'lokasi' => 'Br. Dinas Belumbang Kangin'],
            ['nama_posyandu' => 'Posyandu Pangkung Karung', 'lokasi' => 'Br. Dinas Pangkung Karung'],
            ['nama_posyandu' => 'Posyandu Tegalsaat', 'lokasi' => 'Br. Dinas Tegalsaat'],
            ['nama_posyandu' => 'Posyandu Munduk Tumpeng', 'lokasi' => 'Br. Dinas Munduk Tumpeng'],
            ['nama_posyandu' => 'Posyandu Yeh Buah', 'lokasi' => 'Br. Dinas Yeh Buah'],
        ];

        $posyanduModels = [];
        foreach ($posyanduData as $data) {
            $posyanduModels[] = Posyandu::create([
                'nama_posyandu' => $data['nama_posyandu'],
                'lokasi' => $data['lokasi'],
                'deskripsi' => 'Posyandu Desa Belumbang, Kec. Kerambitan, Kab. Tabanan, Bali',
                'is_active' => true,
            ]);
        }

        // Create Bidan records
        Bidan::create([
            'user_id' => $bidan1->id,
            'nama_bidan' => $bidan1->nama_user,
            'alamat' => 'Desa Belumbang, Kec. Kerambitan, Kab. Tabanan, Bali',
            'no_telp' => '081234567890',
            'jenis_kelamin' => 'P',
        ]);
        Bidan::create([
            'user_id' => $bidan2->id,
            'nama_bidan' => $bidan2->nama_user,
            'alamat' => 'Desa Belumbang, Kec. Kerambitan, Kab. Tabanan, Bali',
            'no_telp' => '081234567891',
            'jenis_kelamin' => 'P',
        ]);

        // Create Kader records (1 per posyandu)
        $dusunNames = [
            'Br. Dinas Belumbang Kaja',
            'Br. Dinas Belumbang Kelod',
            'Br. Dinas Belumbang Kawan',
            'Br. Dinas Belumbang Kangin',
            'Br. Dinas Pangkung Karung',
            'Br. Dinas Tegalsaat',
            'Br. Dinas Munduk Tumpeng',
            'Br. Dinas Yeh Buah',
        ];

        foreach ($kaders as $index => $kader) {
            $posyandu = $posyanduModels[$index] ?? null;
            Kader::create([
                'user_id' => $kader->id,
                'posyandu_id' => $posyandu?->id,
                'nama_kader' => $kader->nama_user,
                'alamat' => $dusunNames[$index] . ', Desa Belumbang, Kec. Kerambitan, Kab. Tabanan',
                'no_telp' => '08' . rand(1000000000, 9999999999),
                'jenis_kelamin' => 'P',
            ]);
        }
        
        // Skip DemoDataSeeder as it uses Faker extensively
    }
}
