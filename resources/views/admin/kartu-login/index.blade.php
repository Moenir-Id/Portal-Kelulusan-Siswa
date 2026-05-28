@extends('layouts.admin')
@section('title', 'Cetak Kartu Login Siswa')

@section('content')
<div class="page-header">
    <h2>Cetak Kartu Login</h2>
    <p>Filter siswa lalu cetak kartu login seukuran KTP untuk dibagikan</p>
</div>

@if(session('success'))
    <div class="alert alert-success">✓ {{ session('success') }}</div>
@endif

<div class="card" style="width:100%">
    <form method="GET" action="{{ route('admin.kartu-login.cetak') }}" target="_blank">

        <div class="kl-grid">
            <div class="form-group">
                <label>Tahun Lulus</label>
                <select name="tahun">
                    <option value="">Semua Tahun</option>
                    @foreach($tahuns as $t)
                        <option value="{{ $t }}">{{ $t }}/{{ $t+1 }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="LULUS">Lulus</option>
                    <option value="TIDAK LULUS">Tidak Lulus</option>
                </select>
            </div>
            <div class="form-group kl-full">
                <label>Kelas <span style="color:var(--muted);font-size:.65rem;text-transform:none;letter-spacing:0">(opsional — tulis persis, misal: XII IPA 1)</span></label>
                <input type="text" name="kelas" placeholder="Kosongkan untuk semua kelas">
            </div>
        </div>

        <div class="kl-note">
            🪪 Kartu dicetak di kertas <strong style="color:var(--txt)">A4</strong>, 8 kartu per halaman (2 × 4), siap potong.<br>
            Hanya siswa dengan <strong style="color:var(--txt)">akun + password tersimpan</strong> yang tampil.
        </div>

        <div class="kl-actions">
            <button class="btn btn-gold kl-btn-submit" type="submit">
                🖨 Buka Halaman Cetak
            </button>
            <a href="{{ route('admin.siswa.index') }}" class="btn btn-ghost">Batal</a>
        </div>

    </form>
</div>

<style>
/* Grid filter — 2 kolom di tablet+, 1 kolom di hp kecil */
.kl-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}
.kl-full { grid-column: 1 / -1; }

.kl-note {
    font-size: .78rem;
    color: var(--muted);
    background: rgba(255,255,255,.03);
    border: 1px solid var(--border);
    border-radius: .6rem;
    padding: .75rem 1rem;
    margin-bottom: 1.25rem;
    line-height: 1.7;
}

.kl-actions {
    display: flex;
    gap: .75rem;
}
.kl-btn-submit {
    flex: 1;
    justify-content: center;
}

/* Desktop lebar — 3 kolom (tahun, status, kelas sejajar) */
@media (min-width: 900px) {
    .kl-grid {
        grid-template-columns: 1fr 1fr 2fr;
    }
    .kl-full { grid-column: auto; }
}

/* HP kecil — 1 kolom */
@media (max-width: 480px) {
    .kl-grid { grid-template-columns: 1fr; }
    .kl-full { grid-column: 1 / -1; }
    .kl-actions { flex-direction: column; }
    .kl-btn-submit { flex: none; width: 100%; }
}
</style>

@endsection