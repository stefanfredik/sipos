<?php

namespace App\Http\Controllers;

use App\Http\Resources\IbuHamilResource;
use App\Http\Resources\BalitaResource;
use App\Http\Resources\LansiaResource;
use App\Models\IbuHamil;
use App\Models\Balita;
use App\Models\Lansia;
use App\Enums\JadwalStatus;
use App\Models\JadwalPosyandu;
use App\Models\Pemeriksaan;
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
        $grafikData = ['labels' => [], 'beratBadan' => []];
        
        if ($ibuHamil) {
            $peserta = new IbuHamilResource($ibuHamil);
            $pesertaType = 'ibu_hamil';
            $pemeriksaanData = $this->getPemeriksaanData($ibuHamil, 'ibu_hamil');
            $riwayatPemeriksaan = $pemeriksaanData['riwayat'];
            $grafikData = $pemeriksaanData['grafik'];
        } elseif ($balita) {
            $peserta = new BalitaResource($balita);
            $pesertaType = 'balita';
            $pemeriksaanData = $this->getPemeriksaanData($balita, 'balita');
            $riwayatPemeriksaan = $pemeriksaanData['riwayat'];
            $grafikData = $pemeriksaanData['grafik'];
        } elseif ($lansia) {
            $peserta = new LansiaResource($lansia);
            $pesertaType = 'lansia';
            $pemeriksaanData = $this->getPemeriksaanData($lansia, 'lansia');
            $riwayatPemeriksaan = $pemeriksaanData['riwayat'];
            $grafikData = $pemeriksaanData['grafik'];
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
        
        $ibuHamil = $user->ibuHamil;
        $balita = $user->balita;
        $lansia = $user->lansia;
        
        $peserta = null;
        $pesertaType = null;
        $riwayatPemeriksaan = [];
        
        if ($ibuHamil) {
            $peserta = new IbuHamilResource($ibuHamil);
            $pesertaType = 'ibu_hamil';
            $riwayatPemeriksaan = $this->getPemeriksaanData($ibuHamil, 'ibu_hamil')['riwayat'];
        } elseif ($balita) {
            $peserta = new BalitaResource($balita);
            $pesertaType = 'balita';
            $riwayatPemeriksaan = $this->getPemeriksaanData($balita, 'balita')['riwayat'];
        } elseif ($lansia) {
            $peserta = new LansiaResource($lansia);
            $pesertaType = 'lansia';
            $riwayatPemeriksaan = $this->getPemeriksaanData($lansia, 'lansia')['riwayat'];
        }
        
        return Inertia::render('Portal/Kms', [
            'peserta' => $peserta,
            'pesertaType' => $pesertaType,
            'riwayatPemeriksaan' => $riwayatPemeriksaan,
        ]);
    }
    
    /**
     * Get pemeriksaan data for peserta.
     */
    private function getPemeriksaanData(object $peserta, string $type): array
    {
        $pemeriksaan = $peserta->pemeriksaan()
            ->with('jadwal.posyandu')
            ->orderBy('tgl_pemeriksaan', 'desc')
            ->take(10)
            ->get();
        
        $riwayat = $pemeriksaan->map(fn ($p) => [
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
        ])->toArray();
        
        $grafik = [
            'labels' => $pemeriksaan->reverse()->pluck('tanggal')->map(fn ($t) => $t->format('d/m/Y'))->toArray(),
            'beratBadan' => $pemeriksaan->reverse()->pluck('berat_badan')->toArray(),
        ];
        
        if ($type === 'balita') {
            $grafik['tinggiBadan'] = $pemeriksaan->reverse()->pluck('tinggi_badan')->toArray();
        } elseif ($type === 'ibu_hamil') {
            $grafik['lila'] = $pemeriksaan->reverse()->pluck('lila')->toArray();
        }
        
        return [
            'riwayat' => $riwayat,
            'grafik' => $grafik,
        ];
    }
}
