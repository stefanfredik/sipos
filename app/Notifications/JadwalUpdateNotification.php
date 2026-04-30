<?php

namespace App\Notifications;

use App\Models\JadwalPosyandu;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JadwalUpdateNotification extends Notification implements ShouldQueue
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
        $statusLabel = $this->jadwal->status->label();
        
        return [
            'jadwal_id' => $this->jadwal->id,
            'posyandu_nama' => $this->jadwal->posyandu->nama_posyandu,
            'status' => $this->jadwal->status,
            'bidan_nama' => $this->jadwal->bidan?->nama_bidan ?? 'Bidan',
            'title' => "Update Jadwal Posyandu: {$statusLabel}",
            'message' => "Jadwal di {$this->jadwal->posyandu->nama_posyandu} pada {$this->jadwal->tanggal->format('d/m/Y')} telah {$statusLabel} oleh {$this->jadwal->bidan?->nama_bidan}.",
            'url' => route('jadwal-posyandu.edit', $this->jadwal->id),
        ];
    }
}
