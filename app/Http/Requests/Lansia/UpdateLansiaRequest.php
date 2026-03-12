<?php

namespace App\Http\Requests\Lansia;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLansiaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('lansia');

        return [
            'nik' => [
                'required',
                'string',
                'size:16',
                Rule::unique('lansia', 'nik')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'tgl_lahir' => ['required', 'date', 'before:today'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'no_telp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'keterangan' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
