<?php

namespace App\Providers;

use App\Models\IbuHamil;
use App\Models\Balita;
use App\Models\Lansia;
use App\Models\JadwalPosyandu;
use App\Models\Pemeriksaan;
use App\Models\User;
use App\Observers\AuditObserver;
use App\Policies\JadwalPosyanduPolicy;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Vite::prefetch(concurrency: 3);

        // Register policies not auto-discovered by convention
        Gate::policy(JadwalPosyandu::class, JadwalPosyanduPolicy::class);

        // Laporan gates (no model)
        Gate::define('laporan.viewAny', [\App\Policies\LaporanPolicy::class, 'viewAny']);
        Gate::define('laporan.exportPdf', [\App\Policies\LaporanPolicy::class, 'exportPdf']);
        Gate::define('laporan.exportExcel', [\App\Policies\LaporanPolicy::class, 'exportExcel']);

        Relation::morphMap([
            'ibu_hamil' => IbuHamil::class,
            'balita' => Balita::class,
            'lansia' => Lansia::class,
        ]);

        // Register audit observer for sensitive models
        IbuHamil::observe(AuditObserver::class);
        Balita::observe(AuditObserver::class);
        Lansia::observe(AuditObserver::class);
        Pemeriksaan::observe(AuditObserver::class);
        User::observe(AuditObserver::class);
    }
}
