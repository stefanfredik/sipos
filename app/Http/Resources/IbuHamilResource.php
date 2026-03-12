<?php

namespace App\Http\Resources;

use App\Helpers\UmurHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class IbuHamilResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nik' => $this->nik,
            'nama' => $this->nama,
            'foto' => $this->foto ? Storage::disk('public')->url($this->foto) : null,
            'tgl_lahir' => $this->tgl_lahir->format('Y-m-d'),
            'umur_label' => UmurHelper::formatUmur($this->tgl_lahir),
            'kehamilan_keberapa' => $this->kehamilan_keberapa,
            'jarak_anak' => $this->jarak_anak,
            'usia_kehamilan' => $this->usia_kehamilan,
            'no_telp' => $this->no_telp,
            'alamat' => $this->alamat,
            'keterangan' => $this->keterangan,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
