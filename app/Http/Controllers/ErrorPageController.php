<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class ErrorPageController extends Controller
{
    public function notFound(): Response
    {
        return Inertia::render('Errors/404', [
            'status' => 404,
            'title' => 'Halaman Tidak Ditemukan',
            'description' => 'Maaf, halaman yang Anda cari tidak dapat ditemukan.',
        ]);
    }

    public function forbidden(): Response
    {
        return Inertia::render('Errors/403', [
            'status' => 403,
            'title' => 'Akses Ditolak',
            'description' => 'Anda tidak memiliki izin untuk mengakses halaman ini.',
        ]);
    }

    public function serverError(): Response
    {
        return Inertia::render('Errors/500', [
            'status' => 500,
            'title' => 'Error Server',
            'description' => 'Maaf, terjadi kesalahan pada server.',
        ]);
    }
}
