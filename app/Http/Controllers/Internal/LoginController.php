<?php

namespace App\Http\Controllers\Internal;

use App\Http\Controllers\Controller;
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
            if (!in_array($user->role, ['admin', 'superadmin'])) {
                Auth::logout();
                return back()->withErrors(['email' => 'Akses ditolak. Bukan admin.']);
            }

            $request->session()->regenerate();
            return redirect()->intended('/akses-internal/dashboard');
        }

        return back()->withErrors([
            'email' => 'Kredensial salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/akses-internal');
    }
}
