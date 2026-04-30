<?php

namespace App\Notifications;

use App\Models\JadwalPosyandu;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JadwalBaruNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public JadwalPosyandu $jadwal)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'jadwal_id' => $this->jadwal->id,
            'posyandu_nama' => $this->jadwal->posyandu->nama_posyandu,
            'tanggal' => $this->jadwal->tanggal->format('Y-m-d'),
            'kader_nama' => $this->jadwal->kader?->nama_kader ?? 'Sistem',
            'title' => 'Usulan Jadwal Posyandu Baru',
            'message' => "Kader {$this->jadwal->kader?->nama_kader} mengusulkan jadwal baru di {$this->jadwal->posyandu->nama_posyandu} pada tanggal {$this->jadwal->tanggal->format('d/m/Y')}.",
            'url' => route('jadwal-posyandu.edit', $this->jadwal->id),
        ];
    }
}
