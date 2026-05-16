<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Usage: middleware('role:ADMIN') or middleware('role:ADMIN,PELAKSANA')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            // Check if user role was changed from WAKIF to something else (e.g. APPROVED as PELAKSANA)
            // but they are still trying to access WAKIF routes.
            if ($request->user() && $request->user()->role === 'PELAKSANA' && in_array('WAKIF', $roles)) {
                auth()->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect('/login')->with('success', 'Pengajuan Anda telah disetujui! Peran akun Anda telah berubah menjadi Pelaksana Nazhir. Silakan login kembali untuk mengakses dashboard baru Anda.');
            }

            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
