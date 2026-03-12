<?php

namespace App\Listeners;

use App\Enums\UserRole;
use App\Events\JadwalValidated;
use App\Models\User;
use App\Notifications\JadwalUpdateNotification;
use Illuminate\Support\Facades\Notification;

class SendJadwalNotificationToPeserta
{
    public function handle(JadwalValidated $event): void
    {
        $jadwal = $event->jadwal;

        // Notify all peserta users about validated jadwal
        $pesertaUsers = User::where('role', UserRole::Peserta)->get();

        if ($pesertaUsers->isNotEmpty()) {
            Notification::send($pesertaUsers, new JadwalUpdateNotification($jadwal));
        }
    }
}
