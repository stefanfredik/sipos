<?php

namespace App\DTOs\JadwalPosyandu;

use App\Http\Requests\JadwalPosyandu\UpdateJadwalRequest;

readonly class UpdateJadwalDTO
{
    public function __construct(
        public string $posyandu_id,
        public string $kader_id,
        public ?string $bidan_id,
        public string $tanggal,
        public string $waktu_mulai,
        public ?string $waktu_selesai = null,
        public string $status = 'draft',
        public ?string $catatan_bidan = null,
    ) {}

    public static function fromRequest(UpdateJadwalRequest $request): self
    {
        return new self(
            posyandu_id: $request->validated('posyandu_id'),
            kader_id: $request->validated('kader_id'),
            bidan_id: $request->validated('bidan_id'),
            tanggal: $request->validated('tanggal'),
            waktu_mulai: $request->validated('waktu_mulai'),
            waktu_selesai: $request->validated('waktu_selesai'),
            status: $request->validated('status', 'draft'),
            catatan_bidan: $request->validated('catatan_bidan'),
        );
    }
}
