<?php

namespace Database\Seeders;

use App\Models\Balita;
use App\Models\IbuHamil;
use App\Models\Lansia;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Seed demo data for development/demo purposes.
     */
    public function run(): void
    {
        // 7 ibu hamil aktif
        IbuHamil::factory()->count(7)->create();

        // 20 balita
        Balita::factory()->count(20)->create();

        // 20 lansia
        Lansia::factory()->count(20)->create();
    }
}
