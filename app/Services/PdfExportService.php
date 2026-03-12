<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PdfExportService
{
    public function exportIbuHamil($data): Response
    {
        $pdf = Pdf::loadView('laporan.ibu-hamil', ['data' => $data])
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-ibu-hamil-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportBalita($data): Response
    {
        $pdf = Pdf::loadView('laporan.balita', ['data' => $data])
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-balita-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportLansia($data): Response
    {
        $pdf = Pdf::loadView('laporan.lansia', ['data' => $data])
            ->setPaper('a4', 'landscape');

        return $pdf->download('laporan-lansia-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportBulanan($data, array $stats, string $periode): Response
    {
        $pdf = Pdf::loadView('laporan.bulanan', [
            'data' => $data,
            'stats' => $stats,
            'periode' => $periode,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan-bulanan-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportKmsIbuHamil($ibuHamil, $pemeriksaan): Response
    {
        $pdf = Pdf::loadView('laporan.kms-ibu-hamil', [
            'ibuHamil' => $ibuHamil,
            'pemeriksaan' => $pemeriksaan,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('kms-ibu-hamil-' . $ibuHamil->nama . '.pdf');
    }

    public function exportKmsBalita($balita, $pemeriksaan): Response
    {
        $pdf = Pdf::loadView('laporan.kms-balita', [
            'balita' => $balita,
            'pemeriksaan' => $pemeriksaan,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('kms-balita-' . $balita->nama . '.pdf');
    }
}
