<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use ZipArchive;

class FotoProfilImportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Import foto profil massal via file ZIP.
     * Nama file di dalam ZIP = NISN siswa (misal: 0078103635.jpg)
     *
     * Route: POST /admin/siswa/import-foto
     * Name:  admin.siswa.import-foto
     */
    public function import(Request $request)
    {
        $request->validate([
            'zip_foto' => 'required|file|mimes:zip|max:51200', // maks 50MB
        ]);

        $zip     = new ZipArchive();
        $zipPath = $request->file('zip_foto')->getRealPath();

        if ($zip->open($zipPath) !== true) {
            return back()->withErrors(['zip_foto' => 'File ZIP tidak bisa dibuka. Pastikan file tidak rusak.']);
        }

        // Pastikan folder tujuan ada
        $destDir = public_path('uploads/foto-profil');
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $ekstensiValid = ['jpg', 'jpeg', 'png', 'webp'];
        $berhasil = 0;
        $tidakAda = 0; // NISN tidak ditemukan di DB
        $dilewati = 0; // ekstensi tidak valid atau bukan file gambar

        // Ambil semua NISN yang ada di DB sekali saja (efisien)
        $siswas = Siswa::pluck('id', 'nisn'); // ['0078103635' => 1, ...]

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $namaFile = $zip->getNameIndex($i);

            // Lewati direktori dan file tersembunyi (misal __MACOSX)
            if (
                substr($namaFile, -1) === '/' ||
                str_contains($namaFile, '__MACOSX') ||
                str_starts_with(basename($namaFile), '.')
            ) {
                continue;
            }

            $basename = pathinfo($namaFile, PATHINFO_BASENAME);
            $ext      = strtolower(pathinfo($basename, PATHINFO_EXTENSION));
            $nisn     = pathinfo($basename, PATHINFO_FILENAME);

            // Validasi ekstensi
            if (!in_array($ext, $ekstensiValid)) {
                $dilewati++;
                continue;
            }

            // Cari siswa berdasarkan NISN
            if (!isset($siswas[$nisn])) {
                $tidakAda++;
                continue;
            }

            $siswa = Siswa::find($siswas[$nisn]);

            // Hapus foto lama jika ada
            if ($siswa->foto_profil && file_exists(public_path($siswa->foto_profil))) {
                unlink(public_path($siswa->foto_profil));
            }

            // Baca konten file dari ZIP dan simpan ke public/uploads/foto-profil/
            $konten    = $zip->getFromIndex($i);
            $filename  = $nisn . '_' . time() . '.' . $ext;
            $filePath  = $destDir . '/' . $filename;
            file_put_contents($filePath, $konten);

            $siswa->update(['foto_profil' => 'uploads/foto-profil/' . $filename]);
            $berhasil++;
        }

        $zip->close();

        $pesan = "Import selesai: {$berhasil} foto berhasil diimpor";
        if ($tidakAda > 0) $pesan .= ", {$tidakAda} NISN tidak ditemukan";
        if ($dilewati > 0) $pesan .= ", {$dilewati} file dilewati (bukan gambar)";
        $pesan .= '.';

        return back()
            ->with('foto_import_success', $pesan)
            ->with('foto_import_detail', [
                'berhasil' => $berhasil,
                'tidakAda' => $tidakAda,
                'dilewati' => $dilewati,
            ]);
    }
}