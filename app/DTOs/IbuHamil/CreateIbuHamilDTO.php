<?php

namespace App\DTOs\IbuHamil;

use Illuminate\Http\UploadedFile;

class CreateIbuHamilDTO
{
    public function __construct(
        public readonly string $nik,
        public readonly string $nama,
        public readonly string $tgl_lahir,
        public readonly int $kehamilan_keberapa,
        public readonly ?int $jarak_anak,
        public readonly int $usia_kehamilan,
        public readonly string $no_telp,
        public readonly string $alamat,
        public readonly ?string $keterangan = null,
        public readonly ?UploadedFile $foto = null,
        public readonly ?string $user_id = null,
    ) {}

    public static function fromRequest(array $data, ?UploadedFile $foto = null): self
    {
        return new self(
            nik: $data['nik'],
            nama: $data['nama'],
            tgl_lahir: $data['tgl_lahir'],
            kehamilan_keberapa: (int)$data['kehamilan_keberapa'],
            jarak_anak: isset($data['jarak_anak']) ? (int)$data['jarak_anak'] : null,
            usia_kehamilan: (int)$data['usia_kehamilan'],
            no_telp: $data['no_telp'],
            alamat: $data['alamat'],
            keterangan: $data['keterangan'] ?? null,
            foto: $foto,
            user_id: $data['user_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'nik' => $this->nik,
            'nama' => $this->nama,
            'tgl_lahir' => $this->tgl_lahir,
            'kehamilan_keberapa' => $this->kehamilan_keberapa,
            'jarak_anak' => $this->jarak_anak,
            'usia_kehamilan' => $this->usia_kehamilan,
            'no_telp' => $this->no_telp,
            'alamat' => $this->alamat,
            'keterangan' => $this->keterangan,
            'user_id' => $this->user_id,
        ];
    }
}
