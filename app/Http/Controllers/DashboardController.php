<?php

namespace App\Http\Controllers;

use App\Services\LaporanService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly LaporanService $laporanService
    ) {}

    public function index(): Response
    {
        $user = auth()->user();
        
        $data = [
            'stats' => $this->laporanService->getStatistikDashboard(),
            'upcomingJadwal' => $this->laporanService->getUpcomingJadwal(5),
            'recentAktivitas' => $this->laporanService->getRecentAktivitas(5),
            'chartData' => $this->laporanService->getChartBulanan(6),
            'distribusiData' => $this->laporanService->getDistribusiPerPosyandu(),
            'alerts' => $this->laporanService->getAlerts(),
        ];
        
        // Add role-specific data
        if ($user->isBidan()) {
            $data['jadwalValidationQueue'] = $this->laporanService->getJadwalValidationQueue();
        }
        
        if ($user->isKader()) {
            $data['myPosyanduJadwal'] = $this->laporanService->getMyPosyanduJadwal($user->kader);
        }
        
        return Inertia::render('Dashboard', $data);
    }
}
