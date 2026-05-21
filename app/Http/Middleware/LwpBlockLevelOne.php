<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LwpBlockLevelOne
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('lwp')->check() && (int) Auth::guard('lwp')->user()->LEVEL === 1) {
            abort(403, 'Akses ditolak. Level 1 tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}
