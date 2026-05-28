<?php
namespace App\Http\Controllers\Siswa;
use App\Http\Controllers\Controller;
use App\Models\Momen;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaDashboardController extends Controller
{
    private function guard()
    {
        return Auth::guard('siswa');
    }

    // ── Dashboard ────────────────────────────────────────────────
    public function index()
    {
        $account = $this->guard()->user();
        $siswa   = $account->siswa;
        $setting = Setting::all_map();

        $momenSaya = $account->momens()->latest()->get();

        $cdAktif  = ($setting['countdown_aktif'] ?? '0') === '1';
        $cdWaktu  = $setting['countdown_waktu'] ?? '';
        $hasilTersembunyi = false;
        $cdTarget = null;

        if ($cdAktif && !empty($cdWaktu)) {
            try {
                $cdTarget = Carbon::parse($cdWaktu, 'Asia/Jakarta');
                if (now('Asia/Jakarta')->lt($cdTarget)) {
                    $hasilTersembunyi = true;
                }
            } catch (\Exception $e) {
                // parsing gagal → anggap selesai
            }
        }

        return view('siswa.dashboard', compact(
            'account', 'siswa', 'setting', 'momenSaya',
            'hasilTersembunyi', 'cdTarget'
        ));
    }

    // ── Upload Momen ─────────────────────────────────────────────
    public function uploadMomen(Request $request)
    {
        $account = $this->guard()->user();
        $siswa   = $account->siswa;

        if ($siswa->status !== 'LULUS') {
            return back()->withErrors(['foto' => 'Hanya siswa yang lulus yang dapat berbagi momen.']);
        }

        $request->validate([
            'foto'    => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'caption' => 'nullable|string|max:300',
        ], [
            'foto.required' => 'Pilih foto terlebih dahulu.',
            'foto.image'    => 'File harus berupa gambar.',
            'foto.max'      => 'Ukuran foto maksimal 5MB.',
        ]);

        // Buat folder jika belum ada
        if (!is_dir(public_path('uploads/momen'))) {
            mkdir(public_path('uploads/momen'), 0755, true);
        }

        $file     = $request->file('foto');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/momen'), $filename);
        $path = 'uploads/momen/' . $filename;

        Momen::create([
            'siswa_account_id' => $account->id,
            'foto'             => $path,
            'caption'          => $request->caption,
        ]);

        return back()->with('success', 'Momen berhasil dibagikan! 🎉');
    }

    // ── Hapus Momen milik sendiri ─────────────────────────────────
    public function hapusMomen(Momen $momen)
    {
        $account = $this->guard()->user();

        if ($momen->siswa_account_id !== $account->id) {
            abort(403);
        }

        $fullPath = public_path($momen->foto);
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }

        $momen->delete();

        return back()->with('success', 'Momen dihapus.');
    }

    // ── Galeri semua siswa (hanya untuk siswa LULUS) ─────────────
    public function galeri()
    {
        $account = $this->guard()->user();
        $siswa   = $account->siswa;

        // Hanya siswa LULUS yang boleh akses galeri
        if ($siswa->status !== 'LULUS') {
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Mohon maaf, halaman galeri hanya dapat diakses oleh siswa yang telah dinyatakan lulus.');
        }

        $setting = Setting::all_map();
        $momens  = Momen::with('siswaAccount.siswa')->latest()->paginate(12);
        return view('siswa.galeri', compact('momens', 'setting'));
    }
}