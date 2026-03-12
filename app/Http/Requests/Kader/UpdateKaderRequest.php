<?php

namespace App\Http\Requests\Kader;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKaderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'posyandu_id' => 'sometimes|exists:posyandu,id',
            'nama_kader' => 'sometimes|string|max:100',
            'foto_kader' => 'nullable|image|max:2048',
            'alamat' => 'sometimes|string',
            'no_telp' => 'sometimes|string|max:20',
            'jenis_kelamin' => 'sometimes|in:L,P',
        ];
    }
}
