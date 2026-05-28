<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Setting;
use App\Models\SiswaAccount;
use App\Models\Momen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Imports\SiswaImport;
use App\Imports\SiswaAkunImport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // ── Dashboard ───────────────────────────────────────────────
    public function dashboard()
    {
        $total          = Siswa::count();
        $lulus          = Siswa::where('status', 'LULUS')->count();
        $tidakLulus     = Siswa::where('status', 'TIDAK LULUS')->count();
        $lulusBersyarat = Siswa::where('status', 'LULUS BERSYARAT')->count();
        $tahunAktif     = Siswa::max('tahun_lulus') ?? date('Y');
        $tahunPelajaran = Setting::get('tahun_pelajaran') ?: ($tahunAktif . '/' . ($tahunAktif + 1));

        return view('admin.dashboard', compact('total', 'lulus', 'tidakLulus', 'lulusBersyarat', 'tahunAktif', 'tahunPelajaran'));
    }

    // ── Login Log ────────────────────────────────────────────────
    public function loginLog(Request $request)
    {
        // Sesi aktif: ambil semua session yang belum expired
        $sessionTimeout = config('session.lifetime', 120) * 60; // menit → detik
        $cutoff         = now()->subSeconds($sessionTimeout)->timestamp;

        $aktiveSessions = DB::table('sessions')
            ->where('last_activity', '>=', $cutoff)
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($s) {
                $guard = 'tamu';
                try {
                    $payload = unserialize(base64_decode($s->payload));
                    if (is_array($payload)) {
                        foreach (array_keys($payload) as $key) {
                            if (str_contains($key, 'siswa'))     { $guard = 'siswa'; break; }
                            if (str_contains($key, 'login_web')) { $guard = 'admin'; break; }
                        }
                    }
                } catch (\Throwable $e) { /* biarkan 'tamu' */ }

                $s->guard         = $guard;
                $s->last_activity = \Carbon\Carbon::createFromTimestamp($s->last_activity);
                return $s;
            });

        // Riwayat login siswa (paginasi + filter cari)
        $query = SiswaAccount::with('siswa')
            ->whereNotNull('last_login_at')
            ->orderByDesc('last_login_at');

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('nisn', 'like', "%{$cari}%")
                  ->orWhereHas('siswa', fn($sq) => $sq->where('nama', 'like', "%{$cari}%"));
            });
        }

        $riwayatSiswa = $query->paginate(20)->withQueryString();

        // Riwayat login admin
        $riwayatAdmin = User::whereNotNull('last_login_at')
            ->orderByDesc('last_login_at')
            ->get();

        return view('admin.login_log', compact('aktiveSessions', 'riwayatSiswa', 'riwayatAdmin'));
    }

    // ── List Siswa ───────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Siswa::query();

        if ($request->filled('cari')) {
            $query->where(function ($q) use ($request) {
                $q->where('nisn', 'like', '%' . $request->cari . '%')
                  ->orWhere('nama', 'like', '%' . $request->cari . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun_lulus', $request->tahun);
        }

        if ($request->filled('akun')) {
            $nisnPunyaAkun = SiswaAccount::pluck('nisn')->toArray();
            if ($request->akun === 'ada') {
                $query->whereIn('nisn', $nisnPunyaAkun);
            } elseif ($request->akun === 'belum') {
                $query->whereNotIn('nisn', $nisnPunyaAkun);
            }
        }

        if ($request->filled('foto')) {
            if ($request->foto === 'ada') {
                $query->whereNotNull('foto_profil');
            } elseif ($request->foto === 'belum') {
                $query->whereNull('foto_profil');
            }
        }

        $siswas    = $query->orderBy('nama')->paginate(20)->withQueryString();
        $tahuns    = Siswa::distinct()->pluck('tahun_lulus')->sortDesc();
        $nismIds   = $siswas->pluck('nisn')->toArray();
        $akunNisns = SiswaAccount::whereIn('nisn', $nismIds)->pluck('nisn', 'nisn')->toArray();

        return view('admin.siswa.index', compact('siswas', 'tahuns', 'akunNisns'));
    }

    // ── Create ───────────────────────────────────────────────────
    public function create()
    {
        return view('admin.siswa.form', [
            'siswa'         => new Siswa(),
            'mode'          => 'create',
            'fotoProfilUrl' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateSiswa($request);
        Siswa::create($data);

        return redirect()->route('admin.siswa.index')
                         ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    // ── Edit ─────────────────────────────────────────────────────
    public function edit(Siswa $siswa)
    {
        $fotoProfilUrl = $siswa->foto_profil
            ? asset($siswa->foto_profil)
            : null;

        return view('admin.siswa.form', [
            'siswa'         => $siswa,
            'mode'          => 'edit',
            'fotoProfilUrl' => $fotoProfilUrl,
        ]);
    }

    public function update(Request $request, Siswa $siswa)
    {
        $data = $this->validateSiswa($request, $siswa->id);

        if ($data['nisn'] !== $siswa->nisn) {
            SiswaAccount::where('nisn', $siswa->nisn)
                        ->update(['nisn' => $data['nisn']]);
        }

        $siswa->update($data);

        return redirect()->route('admin.siswa.index')
                         ->with('success', 'Data siswa berhasil diperbarui.');
    }

    // ── Upload Foto Profil ────────────────────────────────────────
    public function uploadFotoProfil(Request $request, Siswa $siswa)
    {
        $request->validate([
            'foto_profil' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'foto_profil.required' => 'Pilih foto terlebih dahulu.',
            'foto_profil.image'    => 'File harus berupa gambar.',
            'foto_profil.max'      => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($siswa->foto_profil && file_exists(public_path($siswa->foto_profil))) {
            unlink(public_path($siswa->foto_profil));
        }

        if (!is_dir(public_path('uploads/foto-profil'))) {
            mkdir(public_path('uploads/foto-profil'), 0755, true);
        }

        $file     = $request->file('foto_profil');
        $filename = 'siswa_' . $siswa->nisn . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/foto-profil'), $filename);

        $siswa->update(['foto_profil' => 'uploads/foto-profil/' . $filename]);

        return back()->with('foto_success', 'Foto profil berhasil diperbarui.');
    }

    // ── Hapus Foto Profil ─────────────────────────────────────────
    public function hapusFotoProfil(Siswa $siswa)
    {
        if ($siswa->foto_profil && file_exists(public_path($siswa->foto_profil))) {
            unlink(public_path($siswa->foto_profil));
        }

        $siswa->update(['foto_profil' => null]);

        return back()->with('foto_success', 'Foto profil berhasil dihapus.');
    }

    // ── Delete Siswa ─────────────────────────────────────────────
    public function destroy(Siswa $siswa)
    {
        if ($siswa->foto_profil && file_exists(public_path($siswa->foto_profil))) {
            unlink(public_path($siswa->foto_profil));
        }
        $siswa->delete();
        return back()->with('success', 'Data siswa berhasil dihapus.');
    }

    // ── Akun Portal Siswa ────────────────────────────────────────
    public function kelolaAkun(Request $request, Siswa $siswa)
    {
        if ($request->filled('hapus_akun')) {
            $akun = SiswaAccount::where('nisn', $siswa->nisn)->first();
            if ($akun) {
                foreach ($akun->momens as $momen) {
                    if ($momen->foto && file_exists(public_path($momen->foto))) {
                        unlink(public_path($momen->foto));
                    }
                    $momen->delete();
                }
                $akun->delete();
            }
            return back()->with('akun_success', 'Akun portal siswa berhasil dihapus.');
        }

        $request->validate([
            'akun_password' => [
                SiswaAccount::where('nisn', $siswa->nisn)->exists() ? 'nullable' : 'required',
                'string', 'min:6', 'confirmed',
            ],
        ], [
            'akun_password.required'  => 'Password wajib diisi untuk membuat akun baru.',
            'akun_password.min'       => 'Password minimal 6 karakter.',
            'akun_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $akun = SiswaAccount::where('nisn', $siswa->nisn)->first();

        if ($akun) {
            if ($request->filled('akun_password')) {
                $akun->update(['password' => Hash::make($request->akun_password)]);
                return back()->with('akun_success', 'Password akun berhasil diperbarui.');
            }
            return back()->with('akun_success', 'Tidak ada perubahan password.');
        } else {
            SiswaAccount::create([
                'siswa_id' => $siswa->id,
                'nisn'     => $siswa->nisn,
                'password' => Hash::make($request->akun_password),
            ]);
            return back()->with('akun_success', 'Akun portal berhasil dibuat. Login dengan NISN: ' . $siswa->nisn);
        }
    }

    // ── Import Siswa ─────────────────────────────────────────────
    public function importForm()
    {
        return view('admin.siswa.import');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_siswa.csv"',
        ];

        $rows = [
            ['nisn', 'nama', 'kelas', 'tahun_lulus', 'nilai_rata', 'status', 'catatan', 'password'],
            ['0012345678', 'Budi Santoso', 'XII IPA 1', '2026', '87.50', 'LULUS',            '',                        'maazjaya'],
            ['0098765432', 'Siti Rahayu',  'XII IPA 2', '2026', '90.00', 'LULUS',            'Lulus dengan prestasi',   'maazjaya'],
            ['0055667788', 'Dewi Lestari', 'XII IPA 1', '2026', '70.00', 'LULUS BERSYARAT',  'Remidi mapel Matematika', ''],
            ['0011223344', 'Ahmad Fauzi',  'XII IPS 1', '2026', '',      'TIDAK LULUS',      '',                        ''],
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'Pilih file Excel/CSV.',
            'file.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $import = new SiswaImport;
        Excel::import($import, $request->file('file'));

        $hasil     = $import->hasil();
        $pesanAkun = '';
        if ($hasil['buat'] > 0 || $hasil['update'] > 0) {
            $pesanAkun = " ({$hasil['buat']} akun dibuat, {$hasil['update']} akun diperbarui)";
        }

        return redirect()->route('admin.siswa.index')
                         ->with('success', 'Import data siswa berhasil.' . $pesanAkun);
    }

    // ── Download Template Akun ────────────────────────────────────
    public function downloadAkunTemplate()
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_import_akun_siswa.csv"',
        ];

        $rows = [
            ['nisn', 'password'],
            ['0012345678', 'password123'],
            ['0098765432', 'password456'],
        ];

        $callback = function () use ($rows) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ── Import Akun Siswa ─────────────────────────────────────────
    public function importAkun(Request $request)
    {
        $request->validate([
            'file_akun' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file_akun.required' => 'Pilih file Excel/CSV.',
            'file_akun.mimes'    => 'Format file harus .xlsx, .xls, atau .csv.',
            'file_akun.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $import = new SiswaAkunImport;
        Excel::import($import, $request->file('file_akun'));

        $hasil = $import->hasil();

        return redirect()->route('admin.siswa.index')
                         ->with('success', "Import akun selesai: {$hasil['buat']} dibuat, {$hasil['update']} diperbarui, {$hasil['skip']} dilewati.");
    }

    // ── Cetak Surat ───────────────────────────────────────────────
    public function cetakSurat(Siswa $siswa)
    {
        $setting = Setting::all_map();
        return view('admin.siswa.surat', compact('siswa', 'setting'));
    }

    // ── Download SKL Massal ───────────────────────────────────────
    public function sklMassal(Request $request)
    {
        $query = Siswa::whereIn('status', ['LULUS', 'LULUS BERSYARAT']);

        if ($request->filled('tahun')) {
            $query->where('tahun_lulus', $request->tahun);
        }

        $siswas  = $query->orderBy('kelas')->orderBy('nama')->get();
        $setting = Setting::all_map();

        return view('admin.siswa.skl_massal', compact('siswas', 'setting'));
    }

    // ── Helper ───────────────────────────────────────────────────
    private function validateSiswa(Request $request, $ignoreId = null): array
    {
        return $request->validate([
            'nisn'        => 'required|string|max:20|unique:siswas,nisn' . ($ignoreId ? ',' . $ignoreId : ''),
            'nama'        => 'required|string|max:100',
            'kelas'       => 'required|string|max:20',
            'tahun_lulus' => 'required|digits:4|integer|min:2000|max:2099',
            'nilai_rata'  => 'nullable|numeric|min:0|max:100',
            'status'      => 'required|in:LULUS,TIDAK LULUS,LULUS BERSYARAT',
            'catatan'     => 'nullable|string|max:500',
        ]);
    }
}