<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Kirim reminder jadwal H-3 setiap hari jam 08:00
Schedule::command('sipos:send-jadwal-reminder')->dailyAt('08:00');
