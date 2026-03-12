<?php

namespace App\Events;

use App\Models\JadwalPosyandu;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class JadwalValidated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public JadwalPosyandu $jadwal
    ) {}
}
