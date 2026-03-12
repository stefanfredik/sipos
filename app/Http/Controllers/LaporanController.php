<?php

namespace App\Http\Controllers;

use App\Exports\BalitaExport;
use App\Exports\IbuHamilExport;
use App\Exports\LansiaExport;
use App\Exports\LaporanBulananExport;
use App\Models\Posyandu;
use App\Services\LaporanService;
use App\Services\PdfExportService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function __construct(
        private readonly LaporanService $laporanService,
        private readonly PdfExportService $pdfExportService,
    ) {}

    public function index(Request $request): Response
    {
        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);
        $posyanduId = $request->query('posyandu_id');

        $result = $this->laporanService->laporanBulanan($bulan, $tahun, $posyanduId);

        return Inertia::render('Laporan/Index', [
            'laporan' => $result['data'],
            'stats' => $result['stats'],
            'posyandu' => Posyandu::all(),
            'filters' => [
                'bulan' => (string) $bulan,
                'tahun' => (string) $tahun,
                'posyandu_id' => $posyanduId,
            ],
        ]);
    }

    public function ibuHamil(Request $request): Response
    {
        $filters = $request->only(['posyandu_id']);
        $data = $this->laporanService->laporanIbuHamil($filters);

        return Inertia::render('Laporan/IbuHamil', [
            'data' => $data,
            'posyandu' => Posyandu::all(),
            'filters' => $filters,
        ]);
    }

    public function balita(Request $request): Response
    {
        $filters = $request->only(['posyandu_id']);
        $data = $this->laporanService->laporanBalita($filters);

        return Inertia::render('Laporan/Balita', [
            'data' => $data,
            'posyandu' => Posyandu::all(),
            'filters' => $filters,
        ]);
    }

    public function lansia(Request $request): Response
    {
        $filters = $request->only(['posyandu_id']);
        $data = $this->laporanService->laporanLansia($filters);

        return Inertia::render('Laporan/Lansia', [
            'data' => $data,
            'posyandu' => Posyandu::all(),
            'filters' => $filters,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $type = $request->input('type', 'bulanan');
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);
        $posyanduId = $request->input('posyandu_id');

        return match ($type) {
            'ibu_hamil' => $this->pdfExportService->exportIbuHamil(
                $this->laporanService->laporanIbuHamil($request->only(['posyandu_id']))
            ),
            'balita' => $this->pdfExportService->exportBalita(
                $this->laporanService->laporanBalita($request->only(['posyandu_id']))
            ),
            'lansia' => $this->pdfExportService->exportLansia(
                $this->laporanService->laporanLansia($request->only(['posyandu_id']))
            ),
            default => $this->exportBulananPdf($bulan, $tahun, $posyanduId),
        };
    }

    public function exportExcel(Request $request)
    {
        $type = $request->input('type', 'bulanan');
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);
        $posyanduId = $request->input('posyandu_id');

        return match ($type) {
            'ibu_hamil' => Excel::download(
                new IbuHamilExport($this->laporanService->laporanIbuHamil($request->only(['posyandu_id']))),
                'laporan-ibu-hamil-' . now()->format('Y-m-d') . '.xlsx'
            ),
            'balita' => Excel::download(
                new BalitaExport($this->laporanService->laporanBalita($request->only(['posyandu_id']))),
                'laporan-balita-' . now()->format('Y-m-d') . '.xlsx'
            ),
            'lansia' => Excel::download(
                new LansiaExport($this->laporanService->laporanLansia($request->only(['posyandu_id']))),
                'laporan-lansia-' . now()->format('Y-m-d') . '.xlsx'
            ),
            default => $this->exportBulananExcel($bulan, $tahun, $posyanduId),
        };
    }

    private function exportBulananPdf(int $bulan, int $tahun, ?string $posyanduId)
    {
        $result = $this->laporanService->laporanBulanan($bulan, $tahun, $posyanduId);

        $bulanLabels = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
        $periode = ($bulanLabels[$bulan] ?? $bulan) . ' ' . $tahun;

        return $this->pdfExportService->exportBulanan($result['data'], $result['stats'], $periode);
    }

    private function exportBulananExcel(int $bulan, int $tahun, ?string $posyanduId)
    {
        $result = $this->laporanService->laporanBulanan($bulan, $tahun, $posyanduId);

        return Excel::download(
            new LaporanBulananExport($result['data']),
            'laporan-bulanan-' . $tahun . '-' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '.xlsx'
        );
    }
}
