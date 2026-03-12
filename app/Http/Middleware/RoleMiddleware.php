<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! empty($roles) && ! in_array($user->role->value, $roles)) {
            abort(403, 'Akses tidak diizinkan untuk role Anda.');
        }

        return $next($request);
    }
}
