<?php

namespace App\Http\Requests\Pemeriksaan;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePemeriksaanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tgl_pemeriksaan' => ['sometimes', 'required', 'date'],
            'hadir' => ['sometimes', 'required', 'boolean'],
            
            'berat_badan' => ['nullable', 'numeric', 'min:0'],
            'tinggi_badan' => ['nullable', 'numeric', 'min:0'],
            'sistole' => ['nullable', 'integer', 'min:0'],
            'diastole' => ['nullable', 'integer', 'min:0'],

            // Ibu Hamil
            'usia_kehamilan' => ['nullable', 'integer', 'min:0'],
            'lila' => ['nullable', 'numeric', 'min:0'],
            'kek_status' => ['nullable', 'boolean'],
            'mt_bumil' => ['nullable', 'boolean'],
            'ttd_status' => ['nullable', 'boolean'],
            'kelas_bumil' => ['nullable', 'boolean'],

            // Balita
            'lingkar_kepala' => ['nullable', 'numeric', 'min:0'],
            'lingkar_lengan' => ['nullable', 'numeric', 'min:0'],
            'obat_cacing' => ['nullable', 'boolean'],
            'vitamin' => ['nullable', 'string', 'max:100'],

            // Lansia
            'lingkar_perut' => ['nullable', 'numeric', 'min:0'],
            'jenis_keluhan' => ['nullable', 'string', 'max:255'],
            'obat_vitamin' => ['nullable', 'string', 'max:255'],

            'edukasi' => ['nullable', 'string'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
