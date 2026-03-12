<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PemeriksaanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'jadwal_posyandu_id' => $this->jadwal_posyandu_id,
            'kader_id' => $this->kader_id,
            'bidan_id' => $this->bidan_id,
            'peserta_type' => $this->peserta_type,
            'peserta_id' => $this->peserta_id,
            'tgl_pemeriksaan' => $this->tgl_pemeriksaan->format('Y-m-d'),
            
            // Vital Signs
            'berat_badan' => $this->berat_badan,
            'tinggi_badan' => $this->tinggi_badan,
            'sistole' => $this->sistole,
            'diastole' => $this->diastole,
            'tensi_darah' => $this->sistole && $this->diastole ? "{$this->sistole}/{$this->diastole} mmHg" : null,

            // Khusus Ibu Hamil
            'usia_kehamilan' => $this->usia_kehamilan,
            'lila' => $this->lila,
            'kek_status' => $this->kek_status,
            'mt_bumil' => $this->mt_bumil,
            'ttd_status' => $this->ttd_status,
            'kelas_bumil' => $this->kelas_bumil,

            // Khusus Balita
            'lingkar_kepala' => $this->lingkar_kepala,
            'lingkar_lengan' => $this->lingkar_lengan,
            'obat_cacing' => $this->obat_cacing,
            'vitamin' => $this->vitamin,

            // Khusus Lansia
            'lingkar_perut' => $this->lingkar_perut,
            'jenis_keluhan' => $this->jenis_keluhan,
            'obat_vitamin' => $this->obat_vitamin,

            // Edukasi & Catatan
            'edukasi' => $this->edukasi,
            'keterangan' => $this->keterangan,
            'hadir' => $this->hadir,

            // Relationships
            'peserta' => $this->whenLoaded('peserta'),
            'jadwal' => new JadwalPosyanduResource($this->whenLoaded('jadwalPosyandu')),
            'kader' => new KaderResource($this->whenLoaded('kader')),
            'bidan' => new BidanResource($this->whenLoaded('bidan')),
            
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
