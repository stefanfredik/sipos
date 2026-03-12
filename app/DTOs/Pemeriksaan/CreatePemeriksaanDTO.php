<?php

namespace App\DTOs\Pemeriksaan;

class CreatePemeriksaanDTO
{
    public function __construct(
        public readonly string $jadwal_posyandu_id,
        public readonly ?string $kader_id,
        public readonly ?string $bidan_id,
        public readonly string $peserta_type,
        public readonly string $peserta_id,
        public readonly string $tgl_pemeriksaan,
        public readonly ?float $berat_badan = null,
        public readonly ?float $tinggi_badan = null,
        public readonly ?int $sistole = null,
        public readonly ?int $diastole = null,
        public readonly ?int $usia_kehamilan = null,
        public readonly ?float $lila = null,
        public readonly ?bool $kek_status = null,
        public readonly ?bool $mt_bumil = null,
        public readonly ?bool $ttd_status = null,
        public readonly ?bool $kelas_bumil = null,
        public readonly ?float $lingkar_kepala = null,
        public readonly ?float $lingkar_lengan = null,
        public readonly ?bool $obat_cacing = null,
        public readonly ?string $vitamin = null,
        public readonly ?float $lingkar_perut = null,
        public readonly ?string $jenis_keluhan = null,
        public readonly ?string $obat_vitamin = null,
        public readonly ?string $edukasi = null,
        public readonly ?string $keterangan = null,
        public readonly bool $hadir = true,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            jadwal_posyandu_id: $data['jadwal_posyandu_id'],
            kader_id: $data['kader_id'] ?? null,
            bidan_id: $data['bidan_id'] ?? null,
            peserta_type: $data['peserta_type'],
            peserta_id: $data['peserta_id'],
            tgl_pemeriksaan: $data['tgl_pemeriksaan'],
            berat_badan: isset($data['berat_badan']) ? (float) $data['berat_badan'] : null,
            tinggi_badan: isset($data['tinggi_badan']) ? (float) $data['tinggi_badan'] : null,
            sistole: isset($data['sistole']) ? (int) $data['sistole'] : null,
            diastole: isset($data['diastole']) ? (int) $data['diastole'] : null,
            usia_kehamilan: isset($data['usia_kehamilan']) ? (int) $data['usia_kehamilan'] : null,
            lila: isset($data['lila']) ? (float) $data['lila'] : null,
            kek_status: $data['kek_status'] ?? null,
            mt_bumil: $data['mt_bumil'] ?? null,
            ttd_status: $data['ttd_status'] ?? null,
            kelas_bumil: $data['kelas_bumil'] ?? null,
            lingkar_kepala: isset($data['lingkar_kepala']) ? (float) $data['lingkar_kepala'] : null,
            lingkar_lengan: isset($data['lingkar_lengan']) ? (float) $data['lingkar_lengan'] : null,
            obat_cacing: $data['obat_cacing'] ?? null,
            vitamin: $data['vitamin'] ?? null,
            lingkar_perut: isset($data['lingkar_perut']) ? (float) $data['lingkar_perut'] : null,
            jenis_keluhan: $data['jenis_keluhan'] ?? null,
            obat_vitamin: $data['obat_vitamin'] ?? null,
            edukasi: $data['edukasi'] ?? null,
            keterangan: $data['keterangan'] ?? null,
            hadir: $data['hadir'] ?? true,
        );
    }

    public function toArray(): array
    {
        return [
            'jadwal_posyandu_id' => $this->jadwal_posyandu_id,
            'kader_id' => $this->kader_id,
            'bidan_id' => $this->bidan_id,
            'peserta_type' => $this->peserta_type,
            'peserta_id' => $this->peserta_id,
            'tgl_pemeriksaan' => $this->tgl_pemeriksaan,
            'berat_badan' => $this->berat_badan,
            'tinggi_badan' => $this->tinggi_badan,
            'sistole' => $this->sistole,
            'diastole' => $this->diastole,
            'usia_kehamilan' => $this->usia_kehamilan,
            'lila' => $this->lila,
            'kek_status' => $this->kek_status,
            'mt_bumil' => $this->mt_bumil,
            'ttd_status' => $this->ttd_status,
            'kelas_bumil' => $this->kelas_bumil,
            'lingkar_kepala' => $this->lingkar_kepala,
            'lingkar_lengan' => $this->lingkar_lengan,
            'obat_cacing' => $this->obat_cacing,
            'vitamin' => $this->vitamin,
            'lingkar_perut' => $this->lingkar_perut,
            'jenis_keluhan' => $this->jenis_keluhan,
            'obat_vitamin' => $this->obat_vitamin,
            'edukasi' => $this->edukasi,
            'keterangan' => $this->keterangan,
            'hadir' => $this->hadir,
        ];
    }
}
