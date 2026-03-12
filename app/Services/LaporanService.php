<?php

namespace App\Services;

use App\Enums\JadwalStatus;
use App\Models\Balita;
use App\Models\IbuHamil;
use App\Models\JadwalPosyandu;
use App\Models\Lansia;
use App\Models\Pemeriksaan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LaporanService
{
    /**
     * Get dashboard statistics.
     */
    public function getStatistikDashboard(): array
    {
        return [
            'total_bumil' => IbuHamil::where('is_active', true)->count(),
            'total_balita' => Balita::where('is_active', true)->count(),
            'total_lansia' => Lansia::where('is_active', true)->count(),
            'pemeriksaan_bulan_ini' => Pemeriksaan::whereMonth('tgl_pemeriksaan', now()->month)
                ->whereYear('tgl_pemeriksaan', now()->year)
                ->count(),
            'jadwal_bulan_ini' => JadwalPosyandu::thisMonth()->count(),
        ];
    }

    /**
     * Get upcoming jadwal for dashboard.
     */
    public function getUpcomingJadwal(int $limit = 5): Collection
    {
        return JadwalPosyandu::with('posyandu')
            ->where('tanggal', '>=', now()->toDateString())
            ->whereIn('status', [JadwalStatus::Draft, JadwalStatus::Validated])
            ->orderBy('tanggal', 'asc')
            ->take($limit)
            ->get()
            ->map(fn ($j) => [
                'id' => $j->id,
                'posyandu_nama' => $j->posyandu->nama_posyandu ?? $j->posyandu->nama ?? '-',
                'tanggal' => $j->tanggal->format('d/m/Y'),
                'waktu' => "{$j->waktu_mulai} - " . ($j->waktu_selesai ?? 'Selesai'),
                'status' => $j->status->value,
                'status_label' => $j->status->label(),
            ]);
    }

    /**
     * Get recent pemeriksaan activity.
     */
    public function getRecentAktivitas(int $limit = 5): Collection
    {
        return Pemeriksaan::with('peserta')
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'nama' => $p->peserta?->nama ?? 'Unknown',
                'type_label' => $this->getPesertaTypeLabel($p->peserta_type),
                'tanggal' => $p->tgl_pemeriksaan->format('d/m/Y'),
                'status' => $p->hadir ? 'Hadir' : 'Absen',
            ]);
    }

    /**
     * Get monthly trend chart data (last N months).
     */
    public function getChartBulanan(int $months = 6): Collection
    {
        return DB::table('pemeriksaan')
            ->select(
                DB::raw("DATE_FORMAT(tgl_pemeriksaan, '%Y-%m') as periode"),
                DB::raw("DATE_FORMAT(tgl_pemeriksaan, '%M') as month"),
                DB::raw('count(*) as total')
            )
            ->where('tgl_pemeriksaan', '>=', now()->subMonths($months))
            ->whereNull('deleted_at')
            ->groupBy('periode', 'month')
            ->orderBy('periode', 'asc')
            ->get();
    }

    /**
     * Get peserta distribution per posyandu.
     */
    public function getDistribusiPerPosyandu(): Collection
    {
        // Count kader per posyandu as a proxy for activity
        return DB::table('posyandu')
            ->leftJoin('kader', 'posyandu.id', '=', 'kader.posyandu_id')
            ->select('posyandu.nama_posyandu as nama', DB::raw('count(kader.id) as total_kader'))
            ->whereNull('posyandu.deleted_at')
            ->groupBy('posyandu.id', 'posyandu.nama_posyandu')
            ->get();
    }

    /**
     * Get laporan data filtered by period.
     */
    public function laporanBulanan(int $bulan, int $tahun, ?string $posyanduId = null): array
    {
        $query = Pemeriksaan::with(['peserta', 'jadwal.posyandu'])
            ->whereMonth('tgl_pemeriksaan', $bulan)
            ->whereYear('tgl_pemeriksaan', $tahun);

        if ($posyanduId) {
            $query->whereHas('jadwal', fn ($q) => $q->where('posyandu_id', $posyanduId));
        }

        $data = $query->orderBy('tgl_pemeriksaan', 'desc')->get();

        $stats = [
            'total_bumil' => $data->where('peserta_type', 'ibu_hamil')->count(),
            'total_balita' => $data->where('peserta_type', 'balita')->count(),
            'total_lansia' => $data->where('peserta_type', 'lansia')->count(),
            'total_hadir' => $data->where('hadir', true)->count(),
        ];

        return [
            'data' => $data,
            'stats' => $stats,
        ];
    }

    /**
     * Laporan ibu hamil with filters.
     */
    public function laporanIbuHamil(array $filters = []): Collection
    {
        $query = IbuHamil::with(['pemeriksaan' => fn ($q) => $q->latest('tgl_pemeriksaan')])
            ->where('is_active', true);

        if (!empty($filters['posyandu_id'])) {
            // Filter by posyandu through pemeriksaan -> jadwal
            $query->whereHas('pemeriksaan.jadwal', fn ($q) => $q->where('posyandu_id', $filters['posyandu_id']));
        }

        return $query->orderBy('nama', 'asc')->get();
    }

    /**
     * Laporan balita with filters.
     */
    public function laporanBalita(array $filters = []): Collection
    {
        $query = Balita::with(['pemeriksaan' => fn ($q) => $q->latest('tgl_pemeriksaan')])
            ->where('is_active', true);

        if (!empty($filters['posyandu_id'])) {
            $query->whereHas('pemeriksaan.jadwal', fn ($q) => $q->where('posyandu_id', $filters['posyandu_id']));
        }

        return $query->orderBy('nama', 'asc')->get();
    }

    /**
     * Laporan lansia with filters.
     */
    public function laporanLansia(array $filters = []): Collection
    {
        $query = Lansia::with(['pemeriksaan' => fn ($q) => $q->latest('tgl_pemeriksaan')])
            ->where('is_active', true);

        if (!empty($filters['posyandu_id'])) {
            $query->whereHas('pemeriksaan.jadwal', fn ($q) => $q->where('posyandu_id', $filters['posyandu_id']));
        }

        return $query->orderBy('nama', 'asc')->get();
    }

    /**
     * Growth chart data for a specific balita.
     */
    public function getGrafikPertumbuhan(string $balitaId): Collection
    {
        return Pemeriksaan::where('peserta_type', 'balita')
            ->where('peserta_id', $balitaId)
            ->orderBy('tgl_pemeriksaan', 'asc')
            ->get()
            ->map(fn ($p) => [
                'tanggal' => $p->tgl_pemeriksaan->format('d/m/Y'),
                'berat_badan' => (float) $p->berat_badan,
                'tinggi_badan' => (float) $p->tinggi_badan,
            ]);
    }

    /**
     * Health chart data for a specific peserta.
     */
    public function getGrafikKesehatan(string $pesertaId, string $type): Collection
    {
        return Pemeriksaan::where('peserta_type', $type)
            ->where('peserta_id', $pesertaId)
            ->orderBy('tgl_pemeriksaan', 'asc')
            ->get()
            ->map(fn ($p) => [
                'tanggal' => $p->tgl_pemeriksaan->format('d/m/Y'),
                'berat_badan' => (float) $p->berat_badan,
                'tinggi_badan' => (float) $p->tinggi_badan,
                'tensi_darah' => $p->tensi_darah,
            ]);
    }

    /**
     * Get alerts for KEK / stunting.
     */
    public function getAlerts(): array
    {
        $kekCount = Pemeriksaan::where('peserta_type', 'ibu_hamil')
            ->whereNotNull('lila')
            ->where('lila', '<', 23.5)
            ->whereMonth('tgl_pemeriksaan', now()->month)
            ->whereYear('tgl_pemeriksaan', now()->year)
            ->count();

        // Stunting detection (BB/TB below normal)
        $stuntingCount = Pemeriksaan::where('peserta_type', 'balita')
            ->whereNotNull('tinggi_badan')
            ->whereNotNull('berat_badan')
            ->whereMonth('tgl_pemeriksaan', now()->month)
            ->whereYear('tgl_pemeriksaan', now()->year)
            ->whereRaw('berat_badan / NULLIF(tinggi_badan, 0) < 0.035') // Simplified stunting indicator
            ->count();

        // High blood pressure for lansia
        $hipertensiCount = Pemeriksaan::where('peserta_type', 'lansia')
            ->whereNotNull('sistole')
            ->where('sistole', '>', 140)
            ->whereMonth('tgl_pemeriksaan', now()->month)
            ->whereYear('tgl_pemeriksaan', now()->year)
            ->count();

        return [
            'kek_count' => $kekCount,
            'stunting_count' => $stuntingCount,
            'hipertensi_count' => $hipertensiCount,
        ];
    }
    
    /**
     * Get jadwal that need validation (for bidan dashboard).
     */
    public function getJadwalValidationQueue(): \Illuminate\Support\Collection
    {
        return JadwalPosyandu::with('posyandu', 'kader')
            ->where('status', JadwalStatus::Draft)
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get()
            ->map(fn ($j) => [
                'id' => $j->id,
                'posyandu_nama' => $j->posyandu?->nama_posyandu ?? '-',
                'kader_nama' => $j->kader?->nama_kader ?? '-',
                'tanggal' => $j->tanggal->format('d/m/Y'),
                'created_at' => $j->created_at->format('d/m/Y H:i'),
            ]);
    }
    
    /**
     * Get jadwal for kader's posyandu (for kader dashboard).
     */
    public function getMyPosyanduJadwal($kader): \Illuminate\Support\Collection
    {
        if (!$kader) {
            return collect([]);
        }
        
        return JadwalPosyandu::with('posyandu')
            ->whereHas('posyandu', fn ($q) => $q->where('kader_id', $kader->id))
            ->where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal', 'asc')
            ->take(5)
            ->get()
            ->map(fn ($j) => [
                'id' => $j->id,
                'posyandu_nama' => $j->posyandu?->nama_posyandu ?? '-',
                'tanggal' => $j->tanggal->format('d/m/Y'),
                'waktu' => "{$j->waktu_mulai} - " . ($j->waktu_selesai ?? 'Selesai'),
                'status' => $j->status->value,
                'status_label' => $j->status->label(),
            ]);
    }

    private function getPesertaTypeLabel(string $type): string
    {
        return match ($type) {
            'ibu_hamil' => 'Ibu Hamil',
            'balita' => 'Balita',
            'lansia' => 'Lansia',
            default => $type,
        };
    }
}
