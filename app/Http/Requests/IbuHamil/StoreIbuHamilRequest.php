<?php

namespace App\Http\Requests\IbuHamil;

use Illuminate\Foundation\Http\FormRequest;

class StoreIbuHamilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nik' => ['required', 'string', 'size:16', 'unique:ibu_hamil,nik'],
            'nama' => ['required', 'string', 'max:100'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'tgl_lahir' => ['required', 'date', 'before:today'],
            'kehamilan_keberapa' => ['required', 'integer', 'min:1'],
            'jarak_anak' => ['nullable', 'integer', 'min:0'],
            'usia_kehamilan' => ['required', 'integer', 'min:0', 'max:42'],
            'no_telp' => ['required', 'string', 'max:20'],
            'alamat' => ['required', 'string'],
            'keterangan' => ['nullable', 'string'],
            'user_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
