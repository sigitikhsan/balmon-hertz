<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('internal.auth.login');
    }

    public function login(Request $request)
    {
        $request->merge([
            'email' => filter_var($request->email, FILTER_SANITIZE_EMAIL),
        ]);

        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (! $user->isAdmin()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akses ditolak. Bukan admin.']);
            }

            if (! $user->isActive()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda nonaktif. Hubungi super admin.']);
            }

            $request->session()->regenerate();

            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'auth.login',
                'model_type' => User::class,
                'model_id' => $user->id,
                'new_values' => ['email' => $user->email],
                'ip_address' => $request->ip(),
            ]);

            return redirect()->intended('/akses-internal/dashboard');
        }

        return back()->withErrors([
            'email' => 'Kredensial salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'auth.logout',
                'model_type' => User::class,
                'model_id' => $user->id,
                'new_values' => ['email' => $user->email],
                'ip_address' => $request->ip(),
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/akses-internal');
    }
}
