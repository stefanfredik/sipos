<?php

namespace App\Http\Requests\Bidan;

use Illuminate\Foundation\Http\FormRequest;

class StoreBidanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id|unique:bidan,user_id',
            'nama_bidan' => 'required|string|max:255',
            'foto_bidan' => 'nullable|image|max:2048',
            'alamat' => 'required|string',
            'no_telp' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
        ];
    }
}
