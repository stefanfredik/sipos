<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Interfaces\IbuHamilRepositoryInterface::class,
            \App\Repositories\Eloquent\IbuHamilRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\BalitaRepositoryInterface::class,
            \App\Repositories\Eloquent\BalitaRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\LansiaRepositoryInterface::class,
            \App\Repositories\Eloquent\LansiaRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\PosyanduRepositoryInterface::class,
            \App\Repositories\Eloquent\PosyanduRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\KaderRepositoryInterface::class,
            \App\Repositories\Eloquent\KaderRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\BidanRepositoryInterface::class,
            \App\Repositories\Eloquent\BidanRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\PemeriksaanRepositoryInterface::class,
            \App\Repositories\Eloquent\PemeriksaanRepository::class
        );

        $this->app->bind(
            \App\Repositories\Interfaces\JadwalPosyanduRepositoryInterface::class,
            \App\Repositories\Eloquent\JadwalPosyanduRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
