<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
            \App\Http\Middleware\EnsureUserIsActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Foundation\Exceptions\Handler $handler, \Throwable $e) {
            if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                return \Inertia\Inertia::render('Errors/403', [
                    'status' => 403,
                    'title' => 'Akses Ditolak',
                ])->toResponse(request())->setStatusCode(403);
            }

            if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                return \Inertia\Inertia::render('Errors/404', [
                    'status' => 404,
                    'title' => 'Halaman Tidak Ditemukan',
                ])->toResponse(request())->setStatusCode(404);
            }

            if ($e instanceof \Illuminate\Session\TokenMismatchException) {
                return redirect()->route('login');
            }

            // For other 5xx errors
            if (request()->is('api/*') || request()->expectsJson()) {
                return;
            }

            return \Inertia\Inertia::render('Errors/500', [
                'status' => 500,
                'title' => 'Terjadi Kesalahan',
            ])->toResponse(request())->setStatusCode(500);
        });
    })->create();
