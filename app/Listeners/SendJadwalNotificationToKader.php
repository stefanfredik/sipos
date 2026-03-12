<?php

namespace App\Listeners;

use App\Events\JadwalValidated;
use App\Notifications\JadwalUpdateNotification;

class SendJadwalNotificationToKader
{
    public function handle(JadwalValidated $event): void
    {
        $jadwal = $event->jadwal;
        $kaderUser = $jadwal->kader?->user;

        if ($kaderUser) {
            $kaderUser->notify(new JadwalUpdateNotification($jadwal));
        }
    }
}
