<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\SiswaAccount;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class SiswaImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    protected int $buatAkun   = 0;
    protected int $updateAkun = 0;
    protected int $skipAkun   = 0;

    /**
     * Kolom header Excel yang diharapkan:
     * nisn | nama | kelas | tahun_lulus | nilai_rata | status | catatan | password
     *
     * Kolom password bersifat opsional — jika kosong, akun tidak dibuat/diubah.
     */
    public function model(array $row)
    {
        $nisn = trim((string) ($row['nisn'] ?? ''));
        if (empty($nisn)) return null;

        $status = strtoupper(trim((string) ($row['status'] ?? 'LULUS')));
        if (!in_array($status, ['LULUS', 'TIDAK LULUS', 'LULUS BERSYARAT'])) {
            $status = 'LULUS';
        }

        // ── Upsert data siswa ──────────────────────────────────
        $siswa = Siswa::updateOrCreate(
            ['nisn' => $nisn],
            [
                'nama'        => trim((string) ($row['nama'] ?? '')),
                'kelas'       => trim((string) ($row['kelas'] ?? '')),
                'tahun_lulus' => intval($row['tahun_lulus'] ?? date('Y')),
                'nilai_rata'  => is_numeric($row['nilai_rata'] ?? null) ? $row['nilai_rata'] : null,
                'status'      => $status,
                'catatan'     => trim((string) ($row['catatan'] ?? '')),
            ]
        );

        // ── Upsert akun portal (hanya jika kolom password diisi) ─
        //
        // FIX 1: Cast ke string dulu — Excel menyimpan angka murni sebagai integer,
        //         sehingga nilai seperti 123456 terbaca sebagai int, bukan string.
        //
        // FIX 2: Gunakan withoutEvents() agar mutator setPasswordAttribute pada
        //         model SiswaAccount tidak menghash ulang password yang sudah kita
        //         hash secara eksplisit di sini (mencegah double-hash).
        //
        $passwordRaw = trim((string) ($row['password'] ?? ''));

        if ($passwordRaw !== '') {
            $passwordHash = Hash::make($passwordRaw);

            $akun = SiswaAccount::where('nisn', $nisn)->first();

            if ($akun) {
                // Bypass mutator — update langsung ke DB agar tidak double-hash
                SiswaAccount::withoutEvents(function () use ($akun, $passwordHash, $passwordRaw) {
                    $akun->timestamps = false;
                    $akun->forceFill([
                        'password'       => $passwordHash,
                        'plain_password' => $passwordRaw,
                    ])->save();
                    $akun->timestamps = true;
                });
                $this->updateAkun++;
            } else {
                // Bypass mutator saat create juga
                SiswaAccount::withoutEvents(function () use ($siswa, $nisn, $passwordHash, $passwordRaw) {
                    SiswaAccount::forceCreate([
                        'siswa_id'       => $siswa->id,
                        'nisn'           => $nisn,
                        'password'       => $passwordHash,
                        'plain_password' => $passwordRaw,
                    ]);
                });
                $this->buatAkun++;
            }
        } else {
            $this->skipAkun++;
        }

        return null; // sudah di-handle manual via updateOrCreate
    }

    public function hasil(): array
    {
        return [
            'buat'   => $this->buatAkun,
            'update' => $this->updateAkun,
            'skip'   => $this->skipAkun,
        ];
    }
}