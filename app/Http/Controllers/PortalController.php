<?php

namespace App\Http\Controllers;

use App\Models\IbuHamil;
use App\Models\Balita;
use App\Models\Lansia;
use App\Models\JadwalPosyandu;
use App\Models\JadwalStatus;
use Inertia\Inertia;
use Inertia\Response;

class PortalController extends Controller
{
    /**
     * Display portal dashboard for peserta (Ibu Hamil, Balita, Lansia).
     */
    public function index(): Response
    {
        $user = auth()->user();
        
        // Get peserta data based on user relationship
        $ibuHamil = $user->ibuHamil;
        $balita = $user->balita;
        $lansia = $user->lansia;
        
        $peserta = null;
        $pesertaType = null;
        $riwayatPemeriksaan = [];
        $grafikData = [];
        
        if ($ibuHamil) {
            $peserta = $ibuHamil;
            $pesertaType = 'ibu_hamil';
            $riwayatPemeriksaan = $ibuHamil->pemeriksaan()
                ->with('jadwal.posyandu')
                ->latest('tgl_pemeriksaan')
                ->take(10)
                ->get()
                ->map(fn ($p) => [
                    'id' => $p->id,
                    'tanggal' => $p->tgl_pemeriksaan->format('d/m/Y'),
                    'usia_kehamilan' => $p->usia_kehamilan,
                    'berat_badan' => $p->berat_badan,
                    'lila' => $p->lila,
                    'tensi_darah' => $p->tensi_darah,
                    'posyandu_nama' => $p->jadwal?->posyandu?->nama_posyandu ?? '-',
                    'keterangan' => $p->keterangan,
                ]);
            
            $grafikData = [
                'labels' => $riwayatPemeriksaan->pluck('tanggal')->reverse(),
                'beratBadan' => $riwayatPemeriksaan->pluck('berat_badan')->reverse()->toArray(),
                'lila' => $riwayatPemeriksaan->pluck('lila')->reverse()->toArray(),
            ];
        } elseif ($balita) {
            $peserta = $balita;
            $pesertaType = 'balita';
            $riwayatPemeriksaan = $balita->pemeriksaan()
                ->with('jadwal.posyandu')
                ->latest('tgl_pemeriksaan')
                ->take(10)
                ->get()
                ->map(fn ($p) => [
                    'id' => $p->id,
                    'tanggal' => $p->tgl_pemeriksaan->format('d/m/Y'),
                    'berat_badan' => $p->berat_badan,
                    'tinggi_badan' => $p->tinggi_badan,
                    'lingkar_kepala' => $p->lingkar_kepala,
                    'lila' => $p->lila,
                    'posyandu_nama' => $p->jadwal?->posyandu?->nama_posyandu ?? '-',
                    'keterangan' => $p->keterangan,
                ]);
            
            $grafikData = [
                'labels' => $riwayatPemeriksaan->pluck('tanggal')->reverse(),
                'beratBadan' => $riwayatPemeriksaan->pluck('berat_badan')->reverse()->toArray(),
                'tinggiBadan' => $riwayatPemeriksaan->pluck('tinggi_badan')->reverse()->toArray(),
            ];
        } elseif ($lansia) {
            $peserta = $lansia;
            $pesertaType = 'lansia';
            $riwayatPemeriksaan = $lansia->pemeriksaan()
                ->with('jadwal.posyandu')
                ->latest('tgl_pemeriksaan')
                ->take(10)
                ->get()
                ->map(fn ($p) => [
                    'id' => $p->id,
                    'tanggal' => $p->tgl_pemeriksaan->format('d/m/Y'),
                    'berat_badan' => $p->berat_badan,
                    'tinggi_badan' => $p->tinggi_badan,
                    'tensi_darah' => $p->tensi_darah,
                    'posyandu_nama' => $p->jadwal?->posyandu?->nama_posyandu ?? '-',
                    'keterangan' => $p->keterangan,
                ]);
            
            $grafikData = [
                'labels' => $riwayatPemeriksaan->pluck('tanggal')->reverse(),
                'beratBadan' => $riwayatPemeriksaan->pluck('berat_badan')->reverse()->toArray(),
                'tensiSistole' => $riwayatPemeriksaan->pluck('tensi_darah')->reverse()->toArray(),
            ];
        }
        
        // Get upcoming jadwal
        $upcomingJadwal = JadwalPosyandu::with('posyandu')
            ->where('tanggal', '>=', now()->toDateString())
            ->whereIn('status', [JadwalStatus::Draft, JadwalStatus::Validated])
            ->orderBy('tanggal', 'asc')
            ->take(3)
            ->get()
            ->map(fn ($j) => [
                'id' => $j->id,
                'posyandu_nama' => $j->posyandu?->nama_posyandu ?? $j->posyandu?->nama ?? '-',
                'tanggal' => $j->tanggal->format('d/m/Y'),
                'waktu' => "{$j->waktu_mulai} - " . ($j->waktu_selesai ?? 'Selesai'),
                'lokasi' => $j->posyandu?->lokasi_posyandu ?? '-',
            ]);
        
        return Inertia::render('Portal/Index', [
            'peserta' => $peserta,
            'pesertaType' => $pesertaType,
            'riwayatPemeriksaan' => $riwayatPemeriksaan,
            'grafikData' => $grafikData,
            'upcomingJadwal' => $upcomingJadwal,
        ]);
    }
    
    /**
     * Show KMS (Kartu Menuju Sehat) for peserta.
     */
    public function kms(): Response
    {
        $user = auth()->user();
        
        $peserta = $user->ibuHamil ?? $user->balita ?? $user->lansia;
        $pesertaType = $user->ibuHamil ? 'ibu_hamil' : ($user->balita ? 'balita' : 'lansia');
        
        if (!$peserta) {
            return Inertia::render('Portal/Kms', [
                'peserta' => null,
                'pesertaType' => null,
                'riwayatPemeriksaan' => [],
            ]);
        }
        
        $riwayatPemeriksaan = $peserta->pemeriksaan()
            ->with('jadwal.posyandu')
            ->orderBy('tgl_pemeriksaan', 'asc')
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'tanggal' => $p->tgl_pemeriksaan->format('d/m/Y'),
                'berat_badan' => $p->berat_badan,
                'tinggi_badan' => $p->tinggi_badan,
                'lila' => $p->lila,
                'tensi_darah' => $p->tensi_darah,
                'usia_kehamilan' => $p->usia_kehamilan,
                'lingkar_kepala' => $p->lingkar_kepala,
                'posyandu_nama' => $p->jadwal?->posyandu?->nama_posyandu ?? '-',
                'keterangan' => $p->keterangan,
            ]);
        
        return Inertia::render('Portal/Kms', [
            'peserta' => $peserta,
            'pesertaType' => $pesertaType,
            'riwayatPemeriksaan' => $riwayatPemeriksaan,
        ]);
    }
}
