# Fitur Momen Bahagia — Panduan Setup

## File yang dihasilkan

### Migrations (jalankan: php artisan migrate)
- `migrations/2026_03_19_000001_create_siswa_accounts_table.php`
- `migrations/2026_03_19_000002_create_momen_table.php`

### Models
- `models/SiswaAccount.php`  → app/Models/SiswaAccount.php
- `models/Momen.php`         → app/Models/Momen.php

### Controllers
- `controllers/SiswaAuthController.php`    → app/Http/Controllers/Siswa/SiswaAuthController.php
- `controllers/SiswaDashboardController.php` → app/Http/Controllers/Siswa/SiswaDashboardController.php
- `controllers/AdminGaleriController.php`  → app/Http/Controllers/AdminGaleriController.php

### Views
- `views/siswa/register.blade.php` → resources/views/siswa/register.blade.php
- `views/siswa/login.blade.php`    → resources/views/siswa/login.blade.php
- `views/siswa/dashboard.blade.php` → resources/views/siswa/dashboard.blade.php
- `views/siswa/galeri.blade.php`   → resources/views/siswa/galeri.blade.php
- `views/admin/galeri.blade.php`   → resources/views/admin/galeri.blade.php
- `views/welcome_patch.blade.php`  → PATCH ke welcome.blade.php (lihat instruksi)

---

## Langkah Setup

### 1. Tambah Guard di config/auth.php

```php
// guards:
'siswa' => [
    'driver'   => 'session',
    'provider' => 'siswa_accounts',
],

// providers:
'siswa_accounts' => [
    'driver' => 'eloquent',
    'model'  => App\Models\SiswaAccount::class,
],
```

### 2. Tambah Routes di routes/web.php

Salin isi `routes/web_tambahan.php` ke bawah routes yang sudah ada di web.php.
CATATAN: Hapus comment `// ... route admin yang sudah ada ...` dan sesuaikan
dengan prefix admin yang sudah kamu punya (jangan dobel prefix).

### 3. Jalankan Migration

```bash
php artisan migrate
```

### 4. Patch welcome.blade.php

Di dalam `@foreach($siswa as $sv)` pada bagian `.card-actions`,
tambahkan blok dari `welcome_patch.blade.php` SEBELUM tag `</div>` penutup `.card-actions`.

Juga tambahkan CSS `.btn-momen` ke dalam `<style>` di welcome.blade.php.

### 5. Tambah link Galeri di sidebar admin

Di `layouts/admin.blade.php`, tambahkan menu item:
```html
<a href="{{ route('admin.galeri') }}" class="nav-item ...">
    🖼 Galeri Momen
</a>
```

### 6. Storage link (jika belum)

```bash
php artisan storage:link
```

---

## Alur lengkap

```
Halaman publik (welcome)
  → Cek NISN → Status LULUS → tombol "📸 Bagikan Momen Bahagia"
  → Jika belum punya akun → /siswa/register (input NISN + password)
  → Jika sudah → /siswa/login
  → /siswa/dashboard
      → Lihat status kelulusan
      → Upload foto (kamera/galeri) + overlay frame emas
      → Foto tersimpan → tampil di galeri
  → /siswa/galeri → lihat semua foto siswa (masonry grid + lightbox)

Admin:
  → /admin/galeri → lihat + hapus semua foto
```

## Catatan teknis

- Canvas overlay di-render **client-side** (JS) — tidak butuh library tambahan
- Foto yang diupload adalah hasil canvas (dengan frame overlay), bukan foto mentah
- Guard `siswa` terpisah dari guard `web` (admin) — tidak bentrok
- Model SiswaAccount menggunakan kolom `nisn` sebagai username untuk Auth::attempt
