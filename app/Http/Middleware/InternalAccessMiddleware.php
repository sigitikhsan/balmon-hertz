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
        if (!in_array($user->role, ['admin', 'superadmin'])) {
            Auth::logout();
            return redirect('/akses-internal')->withErrors(['email' => 'Akses ditolak. Bukan admin.']);
        }

        return $next($request);
    }
}
