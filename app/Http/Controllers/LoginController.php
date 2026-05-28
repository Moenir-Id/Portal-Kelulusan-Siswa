<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:siswa')->except('logout');
    }

    public function showLoginForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }
        if (Auth::guard('siswa')->check()) {
            return redirect()->route('siswa.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'password'   => 'required|string',
        ], [
            'identifier.required' => 'Email atau NISN wajib diisi.',
            'password.required'   => 'Password wajib diisi.',
        ]);

        $identifier = trim($request->identifier);
        $password   = $request->password;
        $remember   = $request->boolean('remember');
        $ip         = $request->ip();

        if (str_contains($identifier, '@')) {
            // ── Login Admin ───────────────────────────────────────────
            if (Auth::guard('web')->attempt(['email' => $identifier, 'password' => $password], $remember)) {
                $request->session()->regenerate();

                // Catat waktu & IP login admin
                Auth::guard('web')->user()->update([
                    'last_login_at' => now(),
                    'last_login_ip' => $ip,
                    'login_count'   => \DB::raw('COALESCE(login_count, 0) + 1'),
                ]);

                $intended = session()->pull('url.intended');
                if ($intended && $intended !== url('/') && $intended !== route('home')) {
                    return redirect($intended);
                }
                return redirect()->route('admin.dashboard');
            }
        } else {
            // ── Login Siswa (NISN) ────────────────────────────────────
            $result = Auth::guard('siswa')->attempt(['nisn' => $identifier, 'password' => $password], $remember);
            Log::info('Siswa login attempt', [
                'nisn'   => $identifier,
                'result' => $result,
                'user'   => \App\Models\SiswaAccount::where('nisn', $identifier)->first()?->toArray(),
            ]);

            if ($result) {
                $request->session()->regenerate();

                // Catat waktu & IP login siswa
                Auth::guard('siswa')->user()->update([
                    'last_login_at' => now(),
                    'last_login_ip' => $ip,
                    'login_count'   => \DB::raw('COALESCE(login_count, 0) + 1'),
                ]);

                return redirect()->route('siswa.dashboard');
            }
        }

        return back()
            ->withErrors(['identifier' => 'Email/NISN atau password salah. Coba lagi.'])
            ->withInput($request->only('identifier'));
    }

    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        if (Auth::guard('siswa')->check()) {
            Auth::guard('siswa')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}