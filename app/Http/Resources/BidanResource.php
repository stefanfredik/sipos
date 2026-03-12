<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BidanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'nama_bidan' => $this->nama_bidan,
            'foto' => $this->foto_bidan ? asset('storage/' . $this->foto_bidan) : null,
            'alamat' => $this->alamat,
            'no_telp' => $this->no_telp,
            'jenis_kelamin' => $this->jenis_kelamin,
            'username' => $this->user->username ?? null,
            'email' => $this->user->email ?? null,
            'created_at' => $this->created_at->format('d-m-Y H:i'),
        ];
    }
}
