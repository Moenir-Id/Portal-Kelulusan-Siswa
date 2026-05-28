<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class KelulusanController extends Controller
{
    private function settings(): array
    {
        return Setting::all_map();
    }

    public function index(Request $request)
    {
        // Jika ada ?bust= dari countdown JS, hapus cache dulu
        if ($request->has('bust')) {
            Cache::forget('settings_all');
        }

        $s = $this->settings();
        return view('welcome', compact('s'));
    }

    public function cek(Request $request)
    {
        $s = $this->settings();

        // Jika countdown masih aktif & belum waktunya, tolak pencarian
        if (($s['countdown_aktif'] ?? '0') === '1' && !empty($s['countdown_waktu'])) {
            if (now('Asia/Jakarta') < \Carbon\Carbon::parse($s['countdown_waktu'], 'Asia/Jakarta')) {
                return view('welcome', compact('s'));
            }
        }

        // Jika pengumuman tidak aktif
        if (($s['pengumuman_aktif'] ?? '1') !== '1') {
            return view('welcome', compact('s'));
        }

        $request->validate([
            'cari' => 'required|string|min:3|max:100',
        ], [
            'cari.required' => 'Masukkan NISN atau nama siswa.',
            'cari.min'      => 'Minimal 3 karakter.',
        ]);

        $cari  = trim($request->cari);
        $siswa = Siswa::where('nisn', $cari)
            ->orWhere('nama', 'like', '%' . $cari . '%')
            ->get();

        return view('welcome', compact('siswa', 'cari', 's'));
    }

    public function cetakSurat(Siswa $siswa)
    {
        // Hanya LULUS yang boleh cetak surat dari halaman publik
        if ($siswa->status !== 'LULUS') {
            abort(403, 'Surat kelulusan hanya tersedia untuk siswa yang lulus.');
        }

        $setting = Setting::all_map();
        return view('admin.siswa.surat', compact('siswa', 'setting'));
    }
}