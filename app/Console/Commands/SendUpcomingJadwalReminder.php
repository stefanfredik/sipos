<?php

namespace App\Console\Commands;

use App\Enums\JadwalStatus;
use App\Models\JadwalPosyandu;
use App\Models\User;
use App\Notifications\JadwalUpdateNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendUpcomingJadwalReminder extends Command
{
    protected $signature = 'sipos:send-jadwal-reminder {--days=3 : Hari sebelum jadwal}';

    protected $description = 'Kirim notifikasi reminder jadwal posyandu H-3';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $targetDate = now()->addDays($days)->toDateString();

        $jadwalList = JadwalPosyandu::with(['posyandu', 'kader', 'bidan'])
            ->where('tanggal', $targetDate)
            ->where('status', JadwalStatus::Validated)
            ->get();

        if ($jadwalList->isEmpty()) {
            $this->info("Tidak ada jadwal pada {$targetDate}.");
            return self::SUCCESS;
        }

        foreach ($jadwalList as $jadwal) {
            // Notify kader
            $kaderUser = $jadwal->kader?->user;
            if ($kaderUser) {
                $kaderUser->notify(new JadwalUpdateNotification($jadwal));
            }

            // Notify all peserta
            $pesertaUsers = User::where('role', \App\Enums\UserRole::Peserta)->get();
            Notification::send($pesertaUsers, new JadwalUpdateNotification($jadwal));

            $this->info("Reminder terkirim untuk jadwal: {$jadwal->posyandu->nama_posyandu} ({$jadwal->tanggal->format('d/m/Y')})");
        }

        $this->info("Total {$jadwalList->count()} reminder terkirim.");
        return self::SUCCESS;
    }
}
