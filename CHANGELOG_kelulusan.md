# CHANGELOG — Sistem Kelulusan (kelulusan.test)

> Dokumentasi lengkap seluruh file yang telah dikerjakan pada project ini.  
> Laravel 10/11 · PHP 8.x · MySQL · Laragon (Windows)

---

## 📁 CONFIG

### `config/app.php`
- `timezone` diset ke **`Asia/Jakarta`** (bukan UTC default)
- `env` default `production`, `debug` default `false`

### `config/auth.php`
- Ditambahkan guard **`siswa`** (driver: session, provider: `siswa_accounts`)
- Ditambahkan provider **`siswa_accounts`** (driver: eloquent, model: `SiswaAccount`)
- Ditambahkan password broker **`siswa`** untuk reset password siswa
- Guard `web` tetap untuk admin (model: `User`)

### `config/cache.php`
- Default cache driver: **`database`** (bukan `file`)

### `config/session.php`
- Default session driver: **`database`** (bukan `file`)

### `config/queue.php`
- Default queue driver: **`database`**

### `config/filesystems.php`
- Disk `public` → `storage/app/public` (standar Laravel)
- Symlink: `public/storage` → `storage/app/public`
- ⚠️ Catatan: beberapa controller menyimpan file langsung ke `public/uploads/` via `file->move()`, bukan via `Storage::disk('public')` — inkonsistensi yang perlu diperhatikan

---

## 📁 MODELS

### `app/Models/Siswa.php`
- Table: `siswas`
- Fillable: `nisn`, `nama`, `kelas`, `tahun_lulus`, `nilai_rata`, `status`, `catatan`, `foto_profil`
- Accessor `foto_profil_url`: cek `file_exists(public_path(...))` → return `asset(...)` atau `null`

### `app/Models/SiswaAccount.php`
- Extends `Authenticatable` (bukan `Model` biasa)
- Fillable: `siswa_id`, `nisn`, `password`, `plain_password`
- Hidden: `password`, `remember_token`
- **Tidak ada `hashed` cast** pada password — password di-hash manual via `Hash::make()` di controller/import untuk menghindari double-hashing yang menyebabkan `attempt()` gagal
- Override `getAuthIdentifierName()` → return `'nisn'` (paksa Laravel login via NISN bukan email/id)
- Relasi: `belongsTo(Siswa::class, 'siswa_id')`, `hasMany(Momen::class, 'siswa_account_id')`

### `app/Models/Momen.php`
- Table: `momen`
- Fillable: `siswa_account_id`, `foto`, `caption`
- Relasi: `belongsTo(SiswaAccount::class, 'siswa_account_id')`
- Accessor `getSiswaAttribute()`: shortcut ke `siswaAccount->siswa`
- Accessor `foto_url`: `asset('storage/' . $this->foto)`

### `app/Models/Setting.php`
- Table: `settings` (key-value store)
- Static method `get(key, default)` — ambil satu nilai
- Static method `set(key, value)` — upsert satu nilai
- Static method `all_map()` — return semua setting sebagai associative array `[key => value]`

### `app/Models/User.php`
- Standard Laravel User model
- Cast `password` → `hashed` (berbeda dengan `SiswaAccount` yang tidak pakai cast)

---

## 📁 CONTROLLERS

### `app/Http/Controllers/LoginController.php` *(Unified)*
- Menggantikan `Auth\LoginController` default Laravel
- Method `showLoginForm()`: redirect ke dashboard yang sesuai jika sudah login
- Method `login()`: deteksi tipe user dari karakter `@`
  - Mengandung `@` → login sebagai **admin** via guard `web` (field: `email`)
  - Tidak mengandung `@` → login sebagai **siswa** via guard `siswa` (field: `nisn`) + `Log::info()` untuk debug
- Method `logout()`: logout kedua guard sekaligus, lalu redirect ke `route('login')`
- Middleware: `guest` dan `guest:siswa` untuk semua method kecuali `logout`

### `app/Http/Controllers/AdminController.php`
- Middleware: `auth`
- `dashboard()`: statistik total/lulus/tidak lulus, tahun aktif, tahun pelajaran dari Setting
- `index()`: filter cari (nisn/nama), status, tahun_lulus, akun (ada/belum), foto (ada/belum) — paginate 20
- `create()` / `store()`: form tambah siswa baru
- `edit()` / `update()`: form edit, update NISN di `siswa_accounts` jika NISN berubah
- `uploadFotoProfil()`: upload foto ke `public/uploads/foto-profil/`, hapus lama otomatis
- `hapusFotoProfil()`: hapus file + set `foto_profil = null`
- `destroy()`: hapus file foto + delete record
- `kelolaAkun()`: create/update password akun siswa, atau hapus akun beserta semua momennya
- `importForm()`: halaman form import CSV/Excel
- `downloadTemplate()`: download template CSV siswa (dengan BOM UTF-8)
- `import()`: import CSV/Excel via `SiswaImport`, tampilkan ringkasan akun yang dibuat/diperbarui
- `downloadAkunTemplate()`: download template CSV khusus akun (nisn + password)
- `importAkun()`: import akun siswa via `SiswaAkunImport`
- `cetakSurat()`: render view surat kelulusan untuk admin
- `validateSiswa()` (private): validasi data siswa dengan unique check opsional untuk update

### `app/Http/Controllers/SettingController.php`
- Middleware: `auth`
- `index()`: load semua setting via `Setting::all_map()`
- `update()`: normalisasi `countdown_waktu` dari format browser → `Y-m-d H:i:s` (Asia/Jakarta), validasi semua field, upload logo ke `public/uploads/logo/`, simpan semua key via `Setting::set()`, `Cache::forget('settings_all')`
- `hapusLogo()`: hapus file logo + kosongkan key `sekolah_logo`
- Keys yang dikelola: `sekolah_nama`, `sekolah_instansi`, `sekolah_npsn`, `sekolah_nsm`, `sekolah_akreditasi`, `sekolah_alamat`, `sekolah_telp`, `sekolah_email`, `sekolah_website`, `kepala_nama`, `kepala_nip`, `format_nomor_surat`, `sekolah_logo`, `pengumuman_judul`, `pengumuman_aktif`, `tahun_pelajaran`, `countdown_aktif`, `countdown_waktu`, `countdown_label`, `pesan_sebelum`, `pesan_sesudah`

### `app/Http/Controllers/KelulusanController.php`
- Publik (tanpa auth)
- `index()`: load setting, bust cache jika ada query `?bust=`
- `cek()`: validasi countdown aktif & belum waktunya → tolak pencarian; cek pengumuman aktif; cari siswa by NISN atau nama (LIKE)
- `cetakSurat()`: render view surat untuk publik (siswa yang sudah tahu NISN-nya)

### `app/Http/Controllers/AdminGaleriController.php`
- Middleware: `auth`
- `index()`: paginate 16 momen dengan eager load `siswaAccount.siswa`
- `destroy()`: hapus file via `Storage::disk('public')->delete()` + delete record

### `app/Http/Controllers/KartuLoginController.php`
- Middleware: `auth`
- `index()`: halaman filter (daftar tahun_lulus unik)
- `cetak()`: query `SiswaAccount` where `plain_password` not null/empty, filter opsional tahun/kelas/status via `whereHas('siswa', ...)`, sort by nama — untuk cetak kartu login massal

### `app/Http/Controllers/FotoProfilImportController.php`
- Middleware: `auth`
- `import()`: upload ZIP (maks 50MB), ekstrak file gambar, nama file = NISN siswa (e.g. `0078103635.jpg`)
- Lewati direktori, file `__MACOSX`, file tersembunyi
- Validasi ekstensi: `jpg`, `jpeg`, `png`, `webp`
- Cari siswa by NISN dari pluck map (efisien, 1 query)
- Hapus foto lama jika ada, simpan ke `public/uploads/foto-profil/`
- Return ringkasan: berhasil / NISN tidak ada / dilewati

### `app/Http/Controllers/SiswaFotoController.php`
- Tanpa middleware (diproteksi route-level)
- `upload()`: upload foto profil siswa (dipanggil dari route admin)
- `hapus()`: hapus foto profil siswa

### `app/Http/Controllers/Siswa/SiswaAuthController.php`
- Guard: `siswa`
- `registerForm()` / `register()`: cek NISN ada di `siswas`, cek belum punya akun, buat `SiswaAccount`, auto-login, redirect ke `siswa.dashboard`
- `loginForm()` / `login()`: login via guard siswa, redirect ke `siswa.dashboard`
- `logout()`: logout guard siswa, redirect ke `route('home')`

### `app/Http/Controllers/Siswa/SiswaDashboardController.php`
- Guard: `siswa`
- `index()`: load akun, siswa, setting, momen milik sendiri; hitung countdown — set `$hasilTersembunyi = true` jika countdown aktif dan belum waktunya
- `uploadMomen()`: hanya siswa dengan status `LULUS`, simpan foto ke `public/uploads/momen/`, buat record `Momen`
- `hapusMomen()`: cek ownership (`siswa_account_id === $account->id`), hapus file + delete record
- `galeri()`: semua momen dari semua siswa, paginate 12

---

## 📁 MIDDLEWARE

### `app/Http/Middleware/RedirectIfAuthenticated.php`
- Override default Laravel
- Jika guard `siswa` sudah login → redirect ke `siswa.dashboard`
- Jika guard lain sudah login → redirect ke `admin.dashboard`

---

## 📁 IMPORTS

### `app/Imports/SiswaImport.php`
- Implements: `ToModel`, `WithHeadingRow`, `SkipsOnError`
- Kolom: `nisn`, `nama`, `kelas`, `tahun_lulus`, `nilai_rata`, `status`, `catatan`, `password`
- Upsert data `Siswa` via `updateOrCreate`
- Jika kolom `password` diisi → upsert `SiswaAccount` (simpan `plain_password` juga)
- Counter `buatAkun`, `updateAkun`, `skipAkun` → method `hasil()`
- Return `null` dari `model()` karena sudah handle manual

### `app/Imports/SiswaAkunImport.php`
- Implements: `ToCollection`, `WithHeadingRow`
- Kolom: `nisn`, `password`
- Skip jika password < 6 karakter atau NISN tidak ada di `siswas`
- Upsert `SiswaAccount`, simpan `plain_password`
- Counter `buat`, `update`, `skip` → method `hasil()`

---

## 📁 ROUTES (`routes/web.php`)

### Publik (tanpa auth)
| Method | URI | Name | Controller |
|--------|-----|------|------------|
| GET | `/` | `home` | `KelulusanController@index` |
| POST | `/cek` | `cek` | `KelulusanController@cek` |
| GET | `/siswa/{siswa}/surat` | `siswa.surat` | `KelulusanController@cetakSurat` |

### Auth (Login/Logout)
| Method | URI | Name | Controller |
|--------|-----|------|------------|
| GET | `/login` | `login` | `LoginController@showLoginForm` |
| POST | `/login` | — | `LoginController@login` |
| POST | `/logout` | `logout` | `LoginController@logout` |

### Admin (`/admin/*`, middleware: `auth`)
| Method | URI | Name | Controller |
|--------|-----|------|------------|
| GET | `/admin/dashboard` | `admin.dashboard` | `AdminController@dashboard` |
| GET | `/admin/siswa` | `admin.siswa.index` | `AdminController@index` |
| GET | `/admin/siswa/tambah` | `admin.siswa.create` | `AdminController@create` |
| POST | `/admin/siswa` | `admin.siswa.store` | `AdminController@store` |
| GET | `/admin/siswa/import` | `admin.siswa.import-form` | `AdminController@importForm` |
| POST | `/admin/siswa/import` | `admin.siswa.import` | `AdminController@import` |
| GET | `/admin/siswa/import-template` | `admin.siswa.import-template` | `AdminController@downloadTemplate` |
| GET | `/admin/siswa/akun-template` | `admin.siswa.akun-template` | `AdminController@downloadAkunTemplate` |
| POST | `/admin/siswa/akun-import` | `admin.siswa.akun-import` | `AdminController@importAkun` |
| POST | `/admin/siswa/import-foto` | `admin.siswa.import-foto` | `FotoProfilImportController@import` |
| GET | `/admin/siswa/{siswa}/edit` | `admin.siswa.edit` | `AdminController@edit` |
| PUT | `/admin/siswa/{siswa}` | `admin.siswa.update` | `AdminController@update` |
| DELETE | `/admin/siswa/{siswa}` | `admin.siswa.destroy` | `AdminController@destroy` |
| GET | `/admin/siswa/{siswa}/surat` | `admin.siswa.surat-admin` | `AdminController@cetakSurat` |
| POST | `/admin/siswa/{siswa}/akun` | `admin.siswa.akun` | `AdminController@kelolaAkun` |
| POST | `/admin/siswa/{siswa}/foto` | `admin.siswa.foto.upload` | `SiswaFotoController@upload` |
| DELETE | `/admin/siswa/{siswa}/foto` | `admin.siswa.foto.hapus` | `SiswaFotoController@hapus` |
| GET | `/admin/setting` | `admin.setting` | `SettingController@index` |
| POST | `/admin/setting` | `admin.setting.update` | `SettingController@update` |
| POST | `/admin/setting/hapus-logo` | `admin.setting.hapus-logo` | `SettingController@hapusLogo` |
| GET | `/admin/galeri` | `admin.galeri` | `AdminGaleriController@index` |
| DELETE | `/admin/galeri/{momen}` | `admin.galeri.destroy` | `AdminGaleriController@destroy` |
| GET | `/admin/kartu-login` | `admin.kartu-login.index` | `KartuLoginController@index` |
| GET | `/admin/kartu-login/cetak` | `admin.kartu-login.cetak` | `KartuLoginController@cetak` |

### Siswa Portal (`/siswa/*`, middleware: `auth:siswa`)
| Method | URI | Name | Controller |
|--------|-----|------|------------|
| GET | `/siswa/dashboard` | `siswa.dashboard` | `SiswaDashboardController@index` |
| POST | `/siswa/momen` | `siswa.momen.upload` | `SiswaDashboardController@uploadMomen` |
| DELETE | `/siswa/momen/{momen}` | `siswa.momen.hapus` | `SiswaDashboardController@hapusMomen` |
| GET | `/siswa/galeri` | `siswa.galeri` | `SiswaDashboardController@galeri` |
| POST | `/siswa/logout` | `siswa.logout` | `LoginController@logout` |

---

## 📁 VIEWS

### `resources/views/layouts/app.blade.php`
- Layout minimal: `<!DOCTYPE html>` + `@yield('content')`

### `resources/views/auth/login.blade.php` *(unified admin+siswa)*
- Standalone (tidak extend layout)
- Dark gold theme (Cormorant Garamond + DM Sans)
- Field: `identifier` (email atau NISN) + `password`
- Deteksi tipe user dilakukan di `LoginController`

### `resources/views/siswa/login.blade.php`
- Standalone, dark gold theme sama dengan auth/login
- Split layout: left panel dekoratif (orb animasi, info box) + right panel form
- Field: `nisn` + `password`
- Route: `siswa.login` (via `SiswaAuthController`)
- Logo dari `Setting::get('sekolah_logo')` via `Storage::disk('public')`

### `resources/views/siswa/register.blade.php`
- Standalone, dark gold theme
- Single card layout (tanpa split panel)
- Field: `nisn`, `password`, `password_confirmation`
- Route: `siswa.register` (via `SiswaAuthController`)

---

## ⚠️ CATATAN PENTING

1. **Double-hashing password**: `SiswaAccount` tidak menggunakan cast `hashed` karena password sudah di-`Hash::make()` secara manual. Jika cast ditambahkan, login akan selalu gagal.

2. **plain_password**: Disimpan plaintext di kolom `plain_password` khusus untuk fitur cetak kartu login. Pastikan akses ke tabel ini dibatasi.

3. **Penyimpanan file tidak konsisten**: `AdminGaleriController` pakai `Storage::disk('public')->delete()` tapi upload momen di `SiswaDashboardController` pakai `file->move(public_path('uploads/momen'))`. Perlu disamakan jika akan migrasi storage.

4. **Cache settings**: `Setting::all_map()` di-cache dengan key `settings_all`. Selalu `Cache::forget('settings_all')` setelah update setting.

5. **Countdown logic**: Waktu countdown disimpan dalam format `Y-m-d H:i:s` timezone `Asia/Jakarta` di tabel settings. Normalisasi dilakukan di `SettingController::update()`.

---

*Generated: {{ date('d M Y') }}*
