<?php

namespace App\Http\Requests\Kader;

use Illuminate\Foundation\Http\FormRequest;

class StoreKaderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id|unique:kader,user_id',
            'posyandu_id' => 'required|exists:posyandu,id',
            'nama_kader' => 'required|string|max:100',
            'foto_kader' => 'nullable|image|max:2048',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
        ];
    }
}
