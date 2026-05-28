<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaFotoController extends Controller
{
    public function upload(Request $request, Siswa $siswa)
    {
        $request->validate([
            'foto_profil' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'foto_profil.required' => 'Pilih foto terlebih dahulu.',
            'foto_profil.image'    => 'File harus berupa gambar.',
            'foto_profil.max'      => 'Ukuran foto maksimal 2MB.',
        ]);

        // Hapus foto lama
        if ($siswa->foto_profil && file_exists(public_path($siswa->foto_profil))) {
            unlink(public_path($siswa->foto_profil));
        }

        // Buat folder jika belum ada
        if (!is_dir(public_path('uploads/foto-profil'))) {
            mkdir(public_path('uploads/foto-profil'), 0755, true);
        }

        $file     = $request->file('foto_profil');
        $filename = 'siswa_' . $siswa->nisn . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/foto-profil'), $filename);

        $siswa->update(['foto_profil' => 'uploads/foto-profil/' . $filename]);

        return back()->with('foto_success', 'Foto profil berhasil diperbarui.');
    }

    public function hapus(Siswa $siswa)
    {
        if ($siswa->foto_profil && file_exists(public_path($siswa->foto_profil))) {
            unlink(public_path($siswa->foto_profil));
        }

        $siswa->update(['foto_profil' => null]);

        return back()->with('foto_success', 'Foto profil berhasil dihapus.');
    }
}