<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\SiswaAccount;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class SiswaAkunImport implements ToCollection, WithHeadingRow
{
    private int $buat   = 0;
    private int $update = 0;
    private int $skip   = 0;

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $nisn     = trim($row['nisn']     ?? '', " \t\n\r\0\x0B");
            $password = trim($row['password'] ?? '', " \t\n\r\0\x0B");

            // Skip baris kosong atau password kurang dari 6 karakter
            if (!$nisn || strlen($password) < 6) {
                $this->skip++;
                continue;
            }

            // NISN harus ada di tabel siswas
            $siswa = Siswa::where('nisn', $nisn)->first();
            if (!$siswa) {
                $this->skip++;
                continue;
            }

            $akun = SiswaAccount::where('nisn', $nisn)->first();

            if ($akun) {
                $akun->update([
                    'password'       => Hash::make($password),
                    'plain_password' => $password,
                ]);
                $this->update++;
            } else {
                SiswaAccount::create([
                    'siswa_id'       => $siswa->id,
                    'nisn'           => $nisn,
                    'password'       => Hash::make($password),
                    'plain_password' => $password,
                ]);
                $this->buat++;
            }
        }
    }

    public function hasil(): array
    {
        return [
            'buat'   => $this->buat,
            'update' => $this->update,
            'skip'   => $this->skip,
        ];
    }
}