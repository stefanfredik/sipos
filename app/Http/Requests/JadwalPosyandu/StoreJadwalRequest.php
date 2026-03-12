<?php

namespace App\Http\Requests\JadwalPosyandu;

use App\Models\JadwalPosyandu;
use Illuminate\Foundation\Http\FormRequest;

class StoreJadwalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', JadwalPosyandu::class);
    }

    public function rules(): array
    {
        return [
            'posyandu_id' => ['required', 'exists:posyandu,id'],
            'kader_id' => ['required', 'exists:kader,id'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
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
            'tanggal.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi.',
        ];
    }
}
