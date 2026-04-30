<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\LaporanService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ExportLaporanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the queued job may be run.
     */
    public int $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly string $type,
        public readonly array $filters,
        public readonly string $format, // 'pdf' or 'excel'
        public readonly ?int $userId = null,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(LaporanService $laporanService): void
    {
        Log::info("Starting export laporan: {$this->type} ({$this->format})");

        try {
            $data = match ($this->type) {
                'ibu_hamil' => $laporanService->laporanIbuHamil($this->filters),
                'balita' => $laporanService->laporanBalita($this->filters),
                'lansia' => $laporanService->laporanLansia($this->filters),
                'bulanan' => $laporanService->laporanBulanan(
                    $this->filters['bulan'] ?? now()->month,
                    $this->filters['tahun'] ?? now()->year,
                    $this->filters['posyandu_id'] ?? null
                )['data'] ?? [],
                default => throw new \InvalidArgumentException("Invalid report type: {$this->type}")
            };

            $filename = $this->generateFilename();
            $filePath = "exports/{$filename}";

            if ($this->format === 'pdf') {
                $this->exportToPdf($data, $filePath);
            } else {
                $this->exportToExcel($data, $filePath);
            }

            // Store export result for user to download
            if ($this->userId) {
                $this->storeExportResult($filePath, $filename);
            }

            Log::info("Export completed: {$filename}");
        } catch (\Exception $e) {
            Log::error("Export failed: {$e->getMessage()}", [
                'type' => $this->type,
                'format' => $this->format,
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("ExportLaporanJob failed", [
            'type' => $this->type,
            'format' => $this->format,
            'exception' => $exception->getMessage(),
        ]);

        // Optionally notify user about failure
        if ($this->userId) {
            $user = User::find($this->userId);
            if ($user) {
                // Send notification about failed export
                $user->notify(new \App\Notifications\ExportFailedNotification($this->type));
            }
        }
    }

    /**
     * Generate unique filename.
     */
    private function generateFilename(): string
    {
        $timestamp = now()->format('Y-m-d_His');
        $extension = $this->format === 'pdf' ? 'pdf' : 'xlsx';
        return "laporan_{$this->type}_{$timestamp}.{$extension}";
    }

    /**
     * Export data to PDF.
     */
    private function exportToPdf(array|object $data, string $filePath): void
    {
        $viewName = match ($this->type) {
            'ibu_hamil' => 'laporan.ibu-hamil',
            'balita' => 'laporan.balita',
            'lansia' => 'laporan.lansia',
            default => 'laporan.bulanan',
        };

        // For bulanan, we might need stats and periode. Since this is a job we handle it specially.
        $viewData = ['data' => $data];
        if ($this->type === 'bulanan') {
            $bulanLabels = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
                7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
            $bulan = $this->filters['bulan'] ?? now()->month;
            $tahun = $this->filters['tahun'] ?? now()->year;
            $viewData['periode'] = ($bulanLabels[$bulan] ?? $bulan) . ' ' . $tahun;
            
            $collection = is_array($data) ? collect($data) : $data;
            $viewData['stats'] = [
                'total_bumil' => collect($collection)->where('peserta_type', 'ibu_hamil')->count(),
                'total_balita' => collect($collection)->where('peserta_type', 'balita')->count(),
                'total_lansia' => collect($collection)->where('peserta_type', 'lansia')->count(),
                'total_hadir' => collect($collection)->where('hadir', true)->count(),
            ];
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($viewName, $viewData);
        $pdf->setPaper('A4', 'landscape');
        \Illuminate\Support\Facades\Storage::put($filePath, $pdf->output());
    }

    /**
     * Export data to Excel.
     */
    private function exportToExcel(array|object $data, string $filePath): void
    {
        $export = match ($this->type) {
            'ibu_hamil' => new \App\Exports\IbuHamilExport($data),
            'balita' => new \App\Exports\BalitaExport($data),
            'lansia' => new \App\Exports\LansiaExport($data),
            'bulanan' => new \App\Exports\LaporanBulananExport($data),
        };

        \Maatwebsite\Excel\Facades\Excel::store($export, $filePath);
    }

    /**
     * Store export result for user download.
     */
    private function storeExportResult(string $filePath, string $filename): void
    {
        // Send notification to user
        $user = User::find($this->userId);
        if ($user) {
            $user->notify(new \App\Notifications\ExportCompletedNotification(
                $this->type,
                $this->format,
                $filePath,
                $filename
            ));
        }

        Log::info("Export notification sent to user {$this->userId}");
    }
}
