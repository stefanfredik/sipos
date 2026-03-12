<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JadwalPosyanduResource extends JsonResource
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
            'posyandu' => new PosyanduResource($this->whenLoaded('posyandu')),
            'kader' => new KaderResource($this->whenLoaded('kader')),
            'bidan' => new BidanResource($this->whenLoaded('bidan')),
            'tanggal' => $this->tanggal->format('Y-m-d'),
            'waktu_mulai' => $this->waktu_mulai,
            'waktu_selesai' => $this->waktu_selesai,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'catatan_bidan' => $this->catatan_bidan,
        ];
    }
}
