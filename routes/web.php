<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('ibu-hamil', \App\Http\Controllers\IbuHamilController::class);
    Route::resource('balita', \App\Http\Controllers\BalitaController::class)->parameters(['balita' => 'balita']);
    Route::resource('lansia', \App\Http\Controllers\LansiaController::class)->parameters(['lansia' => 'lansia']);
    Route::resource('posyandu', \App\Http\Controllers\PosyanduController::class)->parameters(['posyandu' => 'posyandu']);
    Route::resource('kader', \App\Http\Controllers\KaderController::class);
    Route::resource('bidan', \App\Http\Controllers\BidanController::class);
    Route::resource('pemeriksaan', \App\Http\Controllers\PemeriksaanController::class);
    Route::resource('jadwal-posyandu', \App\Http\Controllers\JadwalPosyanduController::class);
    Route::post('jadwal-posyandu/{jadwal_posyandu}/validate', [\App\Http\Controllers\JadwalPosyanduController::class, 'validateJadwal'])->name('jadwal-posyandu.validate');
    Route::post('jadwal-posyandu/{jadwal_posyandu}/reject', [\App\Http\Controllers\JadwalPosyanduController::class, 'rejectJadwal'])->name('jadwal-posyandu.reject');
    Route::post('jadwal-posyandu/{jadwal_posyandu}/complete', [\App\Http\Controllers\JadwalPosyanduController::class, 'completeJadwal'])->name('jadwal-posyandu.complete');
    Route::get('/laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/ibu-hamil', [\App\Http\Controllers\LaporanController::class, 'ibuHamil'])->name('laporan.ibu-hamil');
    Route::get('/laporan/balita', [\App\Http\Controllers\LaporanController::class, 'balita'])->name('laporan.balita');
    Route::get('/laporan/lansia', [\App\Http\Controllers\LaporanController::class, 'lansia'])->name('laporan.lansia');
    Route::post('/laporan/export/pdf', [\App\Http\Controllers\LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
    Route::post('/laporan/export/excel', [\App\Http\Controllers\LaporanController::class, 'exportExcel'])->name('laporan.export.excel');

    // Notifikasi
    Route::get('/notifications', [\App\Http\Controllers\NotifikasiController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotifikasiController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotifikasiController::class, 'markAllAsRead'])->name('notifications.read-all');

    // Portal Peserta (KMS Digital)
    Route::get('/portal', [\App\Http\Controllers\PortalController::class, 'index'])->name('portal.index');
    Route::get('/portal/kms', [\App\Http\Controllers\PortalController::class, 'kms'])->name('portal.kms');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
