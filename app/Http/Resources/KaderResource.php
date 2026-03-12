<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KaderResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'posyandu_id' => $this->posyandu_id,
            'posyandu_nama' => $this->whenLoaded('posyandu', fn() => $this->posyandu->nama_posyandu),
            'nama_kader' => $this->nama_kader,
            'foto' => $this->foto_kader ? asset('storage/' . $this->foto_kader) : null,
            'alamat' => $this->alamat,
            'no_telp' => $this->no_telp,
            'jenis_kelamin' => $this->jenis_kelamin,
            'username' => $this->whenLoaded('user', fn() => $this->user->username),
            'email' => $this->whenLoaded('user', fn() => $this->user->email),
            'created_at' => $this->created_at->format('d-m-Y H:i'),
        ];
    }
}
