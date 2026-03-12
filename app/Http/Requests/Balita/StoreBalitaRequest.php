<?php

namespace App\Http\Requests\Balita;

use Illuminate\Foundation\Http\FormRequest;

class StoreBalitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nik' => ['required', 'string', 'size:16', 'unique:balita,nik'],
            'nama' => ['required', 'string', 'max:100'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'nama_orang_tua' => ['required', 'string', 'max:100'],
            'tgl_lahir' => ['required', 'date', 'before:today'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'no_telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'keterangan' => ['nullable', 'string'],
            'user_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
