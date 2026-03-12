<?php

namespace App\Http\Requests\JadwalPosyandu;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJadwalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('jadwal_posyandu'));
    }

    public function rules(): array
    {
        return [
            'posyandu_id' => ['required', 'exists:posyandu,id'],
            'kader_id' => ['required', 'exists:kader,id'],
            'bidan_id' => ['nullable', 'exists:bidan,id'],
            'tanggal' => ['required', 'date'],
            'waktu_mulai' => ['required', 'string'],
            'waktu_selesai' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,validated,rejected,completed'],
            'catatan_bidan' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'posyandu_id.required' => 'Posyandu wajib dipilih.',
            'kader_id.required' => 'Kader wajib dipilih.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi.',
        ];
    }
}
