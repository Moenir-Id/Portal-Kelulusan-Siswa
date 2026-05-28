<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\SiswaAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SiswaAuthController extends Controller
{
    // ── Guard ────────────────────────────────────────────────────
    private function guard()
    {
        return Auth::guard('siswa');
    }

    // ── Register ─────────────────────────────────────────────────
    public function registerForm()
    {
        return view('siswa.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nisn'                  => 'required|string|max:20',
            'password'              => 'required|string|min:6|confirmed',
        ], [
            'nisn.required'         => 'NISN wajib diisi.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 6 karakter.',
            'password.confirmed'    => 'Konfirmasi password tidak cocok.',
        ]);

        // Cek NISN ada di tabel siswas
        $siswa = Siswa::where('nisn', $request->nisn)->first();
        if (!$siswa) {
            return back()->withErrors(['nisn' => 'NISN tidak ditemukan dalam data siswa.'])->withInput();
        }

        // Cek sudah punya akun
        if (SiswaAccount::where('nisn', $request->nisn)->exists()) {
            return back()->withErrors(['nisn' => 'Akun dengan NISN ini sudah terdaftar. Silakan login.'])->withInput();
        }

        $account = SiswaAccount::create([
            'siswa_id' => $siswa->id,
            'nisn'     => $request->nisn,
            'password' => $request->password, // dicasting hashed otomatis
        ]);

        $this->guard()->login($account);

        return redirect()->route('siswa.dashboard');
    }

    // ── Login ─────────────────────────────────────────────────────
    public function loginForm()
    {
        if ($this->guard()->check()) {
            return redirect()->route('siswa.dashboard');
        }
        return view('siswa.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nisn'     => 'required|string',
            'password' => 'required|string',
        ]);

        if ($this->guard()->attempt(['nisn' => $request->nisn, 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('siswa.dashboard'));
        }

        return back()->withErrors(['nisn' => 'NISN atau password salah.'])->withInput();
    }

    // ── Logout ────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
