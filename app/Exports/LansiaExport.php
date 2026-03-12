<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LansiaExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
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
            'jk' => $item->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            'status' => $item->is_active ? 'Aktif' : 'Nonaktif',
        ]);
    }

    public function headings(): array
    {
        return ['No', 'NIK', 'Nama', 'Jenis Kelamin', 'Status'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true, 'size' => 11]]];
    }

    public function columnWidths(): array
    {
        return ['A' => 5, 'B' => 20, 'C' => 25, 'D' => 15, 'E' => 12];
    }
}
