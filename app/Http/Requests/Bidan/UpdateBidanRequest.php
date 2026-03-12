<?php

namespace App\Http\Requests\Bidan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBidanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_bidan' => 'sometimes|string|max:255',
            'foto_bidan' => 'nullable|image|max:2048',
            'alamat' => 'sometimes|string',
            'no_telp' => 'sometimes|string|max:20',
            'jenis_kelamin' => 'sometimes|in:L,P',
        ];
    }
}
