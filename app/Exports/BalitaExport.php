<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BalitaExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function __construct(
        private readonly Collection $data
    ) {}

    public function collection(): Collection
    {
        return $this->data->map(fn ($item, $i) => [
            'no' => $i + 1,
            'nik' => $item->nik,
            'nama' => $item->nama,
            'orang_tua' => $item->nama_orang_tua,
            'jk' => $item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            'status' => $item->is_active ? 'Aktif' : 'Nonaktif',
        ]);
    }

    public function headings(): array
    {
        return ['No', 'NIK', 'Nama', 'Orang Tua', 'Jenis Kelamin', 'Status'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true, 'size' => 11]]];
    }

    public function columnWidths(): array
    {
        return ['A' => 5, 'B' => 20, 'C' => 25, 'D' => 25, 'E' => 15, 'F' => 12];
    }
}
