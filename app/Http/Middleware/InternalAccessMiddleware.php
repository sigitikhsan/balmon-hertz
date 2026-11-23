<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class InternalAccessMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/akses-internal');
        }

        $user = Auth::user();

        if (! $user->isAdmin()) {
            Auth::logout();
            return redirect('/akses-internal')->withErrors(['email' => 'Akses ditolak. Bukan admin.']);
        }

        if (! $user->isActive()) {
            Auth::logout();
            return redirect('/akses-internal')->withErrors(['email' => 'Akun Anda nonaktif. Hubungi super admin.']);
        }

        return $next($request);
    }
}
