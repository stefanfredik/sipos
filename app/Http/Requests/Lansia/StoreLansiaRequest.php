<?php

namespace App\Http\Requests\Lansia;

use Illuminate\Foundation\Http\FormRequest;

class StoreLansiaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nik' => ['required', 'string', 'size:16', 'unique:lansia,nik'],
            'nama' => ['required', 'string', 'max:100'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'tgl_lahir' => ['required', 'date', 'before:today'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'no_telp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'keterangan' => ['nullable', 'string'],
            'user_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
