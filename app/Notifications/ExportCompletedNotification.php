<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExportCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly string $type,
        public readonly string $format,
        public readonly string $filePath,
        public readonly string $filename,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $typeLabel = match ($this->type) {
            'ibu_hamil' => 'Ibu Hamil',
            'balita' => 'Balita',
            'lansia' => 'Lansia',
            default => 'Laporan',
        };

        $formatLabel = $this->format === 'pdf' ? 'PDF' : 'Excel';

        return (new MailMessage)
            ->subject("Export Laporan {$typeLabel} Selesai")
            ->greeting('Halo,')
            ->line("Export laporan {$typeLabel} ({$formatLabel}) telah selesai diproses.")
            ->action('Download Laporan', url("downloads/{$this->filePath}"))
            ->line('File akan otomatis terdownload.')
            ->salutation('Terima kasih telah menggunakan SIPOS.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $typeLabel = match ($this->type) {
            'ibu_hamil' => 'Ibu Hamil',
            'balita' => 'Balita',
            'lansia' => 'Lansia',
            default => 'Laporan',
        };

        $formatLabel = $this->format === 'pdf' ? 'PDF' : 'Excel';

        return [
            'title' => "Export {$typeLabel} Selesai",
            'message' => "Laporan {$typeLabel} ({$formatLabel}) telah selesai dan siap untuk diunduh.",
            'type' => 'export_completed',
            'file_path' => $this->filePath,
            'filename' => $this->filename,
            'icon' => 'success',
        ];
    }
}
