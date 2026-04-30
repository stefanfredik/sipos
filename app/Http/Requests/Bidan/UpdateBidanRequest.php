<?php

namespace App\Http\Requests\Bidan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Bidan;

class UpdateBidanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bidan = Bidan::findOrFail($this->route('bidan'));
        
        return [
            'username' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('users', 'username')
                    ->where('role', 'bidan')
                    ->ignore($bidan->user_id, 'id'),
            ],
            'nama_bidan' => 'sometimes|string|max:255',
            'foto_bidan' => 'nullable|image|max:2048',
            'alamat' => 'sometimes|string',
            'no_telp' => 'sometimes|string|max:20',
            'jenis_kelamin' => 'sometimes|in:L,P',
        ];
    }

    public function messages(): array
    {
        return [
            'username.unique' => 'Username sudah digunakan oleh user lain.',
        ];
    }
}
