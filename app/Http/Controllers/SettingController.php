<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $s = Setting::all_map();
        return view('admin.setting', compact('s'));
    }

    public function update(Request $request)
    {
        // Bersihkan & normalisasi countdown_waktu dari berbagai format browser
        if ($request->filled('countdown_waktu')) {
            $raw = trim($request->countdown_waktu);
            $raw = preg_replace('/\s*-+\s*$/', '', $raw);
            $raw = str_replace('T', ' ', $raw);
            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $raw)) {
                $raw .= ':00';
            }
            try {
                $parsed = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $raw, 'Asia/Jakarta');
                $raw    = $parsed->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                $raw = '';
            }
            $request->merge(['countdown_waktu' => $raw]);
        }

        $request->validate([
            'sekolah_nama'        => 'required|string|max:150',
            'sekolah_instansi'    => 'nullable|string|max:200',
            'sekolah_npsn'        => 'nullable|string|max:20',
            'sekolah_nsm'         => 'nullable|string|max:30',
            'sekolah_akreditasi'  => 'nullable|string|max:50',
            'sekolah_alamat'      => 'nullable|string|max:255',
            'sekolah_telp'        => 'nullable|string|max:50',
            'sekolah_email'       => 'nullable|string|max:100',
            'sekolah_website'     => 'nullable|string|max:100',
            'kepala_nama'         => 'nullable|string|max:150',
            'kepala_nip'          => 'nullable|string|max:30',
            'format_nomor_surat'  => 'nullable|string|max:100',
            'logo'                => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'pengumuman_judul'    => 'nullable|string|max:200',
            'tahun_pelajaran'     => 'nullable|string|max:10',
            'pengumuman_aktif'    => 'required|in:0,1',
            'countdown_aktif'     => 'required|in:0,1',
            'countdown_waktu'     => 'nullable|string|max:30',
            'countdown_label'     => 'nullable|string|max:200',
            'pesan_sebelum'       => 'nullable|string|max:500',
            'pesan_sesudah'       => 'nullable|string|max:500',
        ]);

        // Upload logo jika ada file baru
        if ($request->hasFile('logo')) {
            // Hapus logo lama
            $logoLama = Setting::get('sekolah_logo');
            if ($logoLama && file_exists(public_path($logoLama))) {
                unlink(public_path($logoLama));
            }

            // Buat folder jika belum ada
            if (!is_dir(public_path('uploads/logo'))) {
                mkdir(public_path('uploads/logo'), 0755, true);
            }

            $file     = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/logo'), $filename);

            Setting::set('sekolah_logo', 'uploads/logo/' . $filename);
        }

        $keys = [
            'sekolah_nama', 'sekolah_instansi', 'sekolah_npsn', 'sekolah_nsm',
            'sekolah_akreditasi', 'sekolah_alamat', 'sekolah_telp',
            'sekolah_email', 'sekolah_website',
            'kepala_nama', 'kepala_nip', 'format_nomor_surat',
            'pengumuman_judul', 'tahun_pelajaran', 'pengumuman_aktif',
            'countdown_aktif', 'countdown_waktu', 'countdown_label',
            'pesan_sebelum', 'pesan_sesudah',
            // sekolah_logo SENGAJA tidak di sini — dihandle terpisah di atas
        ];

        foreach ($keys as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        \Illuminate\Support\Facades\Cache::forget('settings_all');

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function hapusLogo()
    {
        $logo = Setting::get('sekolah_logo');
        if ($logo && file_exists(public_path($logo))) {
            unlink(public_path($logo));
        }
        Setting::set('sekolah_logo', '');
        return back()->with('success', 'Logo berhasil dihapus.');
    }
}