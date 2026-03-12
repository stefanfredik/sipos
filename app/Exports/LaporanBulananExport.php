<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanBulananExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    public function __construct(
        private readonly Collection $data
    ) {}

    public function collection(): Collection
    {
        return $this->data->map(fn ($item, $i) => [
            'no' => $i + 1,
            'tgl' => $item->tgl_pemeriksaan?->format('d/m/Y'),
            'nama' => $item->peserta?->nama ?? '-',
            'nik' => $item->peserta?->nik ?? '-',
            'kategori' => match ($item->peserta_type) {
                'ibu_hamil' => 'Ibu Hamil',
                'balita' => 'Balita',
                'lansia' => 'Lansia',
                default => $item->peserta_type,
            },
            'lokasi' => $item->jadwal?->posyandu?->nama_posyandu ?? $item->jadwal?->posyandu?->nama ?? '-',
            'bb' => $item->berat_badan ?? '-',
            'tb' => $item->tinggi_badan ?? '-',
            'status' => $item->hadir ? 'Hadir' : 'Absen',
        ]);
    }

    public function headings(): array
    {
        return ['No', 'Tgl. Periksa', 'Nama', 'NIK', 'Kategori', 'Lokasi', 'BB (kg)', 'TB (cm)', 'Status'];
    }

    public function styles(Worksheet $sheet): array
    {
        return [1 => ['font' => ['bold' => true, 'size' => 11]]];
    }

    public function columnWidths(): array
    {
        return ['A' => 5, 'B' => 14, 'C' => 25, 'D' => 20, 'E' => 12, 'F' => 20, 'G' => 10, 'H' => 10, 'I' => 10];
    }
}
