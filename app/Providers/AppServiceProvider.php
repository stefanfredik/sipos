<?php

namespace App\Providers;

use App\Models\JadwalPosyandu;
use App\Policies\JadwalPosyanduPolicy;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // Register policies not auto-discovered by convention
        Gate::policy(JadwalPosyandu::class, JadwalPosyanduPolicy::class);

        // Laporan gates (no model)
        Gate::define('laporan.viewAny', [\App\Policies\LaporanPolicy::class, 'viewAny']);
        Gate::define('laporan.exportPdf', [\App\Policies\LaporanPolicy::class, 'exportPdf']);
        Gate::define('laporan.exportExcel', [\App\Policies\LaporanPolicy::class, 'exportExcel']);

        Relation::morphMap([
            'ibu_hamil' => \App\Models\IbuHamil::class,
            'balita' => \App\Models\Balita::class,
            'lansia' => \App\Models\Lansia::class,
        ]);
    }
}
