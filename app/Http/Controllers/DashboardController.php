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
        return Inertia::render('Dashboard', [
            'stats' => $this->laporanService->getStatistikDashboard(),
            'upcomingJadwal' => $this->laporanService->getUpcomingJadwal(5),
            'recentAktivitas' => $this->laporanService->getRecentAktivitas(5),
            'chartData' => $this->laporanService->getChartBulanan(6),
            'distribusiData' => $this->laporanService->getDistribusiPerPosyandu(),
            'alerts' => $this->laporanService->getAlerts(),
        ]);
    }
}
