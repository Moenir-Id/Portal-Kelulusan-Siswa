<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\SiswaAccount;
use App\Models\Setting;
use Illuminate\Http\Request;

class KartuLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Halaman form pilih filter sebelum cetak.
     * GET /admin/kartu-login
     */
    public function index(Request $request)
    {
        $tahuns = Siswa::distinct()->pluck('tahun_lulus')->sortDesc();
        return view('admin.kartu-login.index', compact('tahuns'));
    }

    /**
     * Halaman cetak kartu login — full page, siap print.
     * GET /admin/kartu-login/cetak
     */
    public function cetak(Request $request)
    {
        $setting = Setting::all_map();

        // Ambil NISN yang punya akun dan plain_password
        $query = SiswaAccount::with('siswa')
            ->whereNotNull('plain_password')
            ->where('plain_password', '!=', '');

        // Filter opsional berdasarkan tahun lulus
        if ($request->filled('tahun')) {
            $query->whereHas('siswa', fn($q) => $q->where('tahun_lulus', $request->tahun));
        }

        // Filter berdasarkan kelas
        if ($request->filled('kelas')) {
            $query->whereHas('siswa', fn($q) => $q->where('kelas', $request->kelas));
        }

        // Filter status
        if ($request->filled('status')) {
            $query->whereHas('siswa', fn($q) => $q->where('status', $request->status));
        }

        $akuns = $query->get()->filter(fn($a) => $a->siswa !== null)->sortBy('siswa.nama');

        return view('admin.kartu-login.cetak', compact('akuns', 'setting'));
    }
}
