<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            // Identitas Sekolah
            'sekolah_nama'       => 'SMA Negeri 1 Contoh',
            'sekolah_npsn'       => '12345678',
            'sekolah_alamat'     => 'Jl. Pendidikan No. 1, Kota',
            'sekolah_telp'       => '(021) 000-0000',
            'sekolah_email'      => 'info@sekolah.sch.id',
            'sekolah_website'    => 'www.sekolah.sch.id',
            'sekolah_logo'       => '',   // path relatif dari storage/app/public

            // Pengumuman
            'pengumuman_judul'   => 'Pengumuman Kelulusan Tahun Pelajaran 2024/2025',
            'pengumuman_aktif'   => '1',  // 1 = halaman cek aktif, 0 = tersembunyi
            'countdown_aktif'    => '1',  // 1 = tampilkan countdown
            'countdown_waktu'    => '',   // datetime: 2025-06-02 08:00:00
            'countdown_label'    => 'Pengumuman kelulusan akan dibuka pada:',
            'pesan_sebelum'      => 'Pengumuman kelulusan belum dibuka. Pantau terus halaman ini.',
            'pesan_sesudah'      => 'Selamat! Pengumuman kelulusan resmi telah dibuka. Silakan cek status kelulusan Anda.',
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
