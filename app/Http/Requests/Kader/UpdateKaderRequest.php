<?php

namespace App\Http\Requests\Kader;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Kader;

class UpdateKaderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $kader = Kader::findOrFail($this->route('kader'));
        
        return [
            'username' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('users', 'username')
                    ->where('role', 'kader')
                    ->ignore($kader->user_id, 'id'),
            ],
            'posyandu_id' => 'sometimes|exists:posyandu,id',
            'nama_kader' => 'sometimes|string|max:100',
            'foto_kader' => 'nullable|image|max:2048',
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
