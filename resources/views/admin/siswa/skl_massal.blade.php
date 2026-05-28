@php
    $cfg = isset($setting) ? $setting : \App\Models\Setting::all_map();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SKL Massal</title>
    <style>
        @media print {
            .no-print { display: none !important; }
            .halaman  { page-break-after: always; }
            .halaman:last-child { page-break-after: avoid; }
            @page { margin: 1.8cm 2cm; size: A4; }
        }
        .halaman { page-break-after: always; }
        .halaman:last-child { page-break-after: avoid; }
    </style>
</head>
<body>

{{-- Tombol cetak --}}
<div class="no-print" style="position:fixed;top:16px;right:20px;z-index:999;display:flex;gap:.5rem">
    <button onclick="window.print()"
        style="background:#1a1a1a;color:#fff;border:none;padding:.6rem 1.5rem;border-radius:.5rem;font-size:.875rem;cursor:pointer;font-family:sans-serif;box-shadow:0 2px 8px rgba(0,0,0,.3)">
        🖨 Cetak / Simpan PDF
    </button>
    <button onclick="window.history.back()"
        style="background:#fff;border:1px solid #ccc;color:#555;padding:.6rem 1.25rem;border-radius:.5rem;font-size:.875rem;cursor:pointer;font-family:sans-serif">
        ← Kembali
    </button>
</div>

@foreach($siswas as $siswa)
<div class="halaman">
    @include('admin.siswa.surat', ['siswa' => $siswa, 'setting' => $cfg])
</div>
@endforeach

</body>
</html>