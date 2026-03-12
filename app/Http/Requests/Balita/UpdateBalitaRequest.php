<?php

namespace App\Http\Requests\Balita;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBalitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('balita');

        return [
            'nik' => [
                'required',
                'string',
                'size:16',
                Rule::unique('balita', 'nik')->ignore($id),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'nama_orang_tua' => ['required', 'string', 'max:100'],
            'tgl_lahir' => ['required', 'date', 'before:today'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'no_telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'keterangan' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ];
    }
}
