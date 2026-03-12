<?php

namespace App\Http\Requests\Posyandu;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePosyanduRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_posyandu' => ['required', 'string', 'max:100'],
            'lokasi' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
