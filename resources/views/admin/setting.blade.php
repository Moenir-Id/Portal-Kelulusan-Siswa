@extends('layouts.admin')
@section('title', 'Pengaturan')

@section('content')
<div class="page-header">
    <h2>Pengaturan</h2>
    <p>Identitas sekolah, logo, dan konfigurasi countdown pengumuman</p>
</div>

@if($errors->any())
    <div class="alert alert-error" style="margin-bottom:1.5rem">
        <strong>Pengaturan gagal disimpan:</strong>
        <ul style="margin:.5rem 0 0 1rem;list-style:disc">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<style>
    /* ── Section header ── */
    .sec-hdr {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border);
    }
    .sec-hdr-ico   { font-size: 1.3rem; flex-shrink: 0; }
    .sec-hdr-title { font-weight: 600; font-size: .95rem; }
    .sec-hdr-sub   { font-size: .75rem; color: var(--muted); margin-top: .15rem; }

    /* ── Logo area ── */
    .logo-area {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }
    .logo-preview {
        width: 80px; height: 80px;
        border-radius: 50%;
        border: 1px solid var(--border);
        background: rgba(201,168,76,.06);
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }
    .logo-preview img { width: 100%; height: 100%; object-fit: cover; }
    .logo-actions { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; }

    /* ── Date-time row ── */
    .dt-row { display: flex; gap: .5rem; }
    .dt-row input[type=date] { flex: 1; min-width: 0; }
    .dt-row input[type=time] { width: 110px; flex-shrink: 0; }

    /* ── Countdown preview grid ── */
    .cd-preview-grid { display: flex; gap: .75rem; flex-wrap: wrap; }
    .cd-preview-box {
        background: rgba(201,168,76,.08);
        border: 1px solid var(--border);
        border-radius: .75rem;
        padding: .75rem 1.1rem;
        text-align: center;
        min-width: 68px;
        flex: 1;
    }

    /* ── Submit bar ── */
    .submit-bar { display: flex; gap: .75rem; flex-wrap: wrap; }
    .submit-bar .btn { flex: 1 1 auto; justify-content: center; min-width: 140px; }

    /* ── Hints & mono ── */
    .field-hint {
        font-size: .72rem;
        color: var(--muted);
        margin-top: .3rem;
        display: block;
        line-height: 1.5;
    }
    .preview-mono {
        font-family: 'Courier New', monospace;
        font-size: .875rem;
        padding: .6rem .9rem;
        background: rgba(201,168,76,.06);
        border: 1px solid var(--border);
        border-radius: .5rem;
        color: var(--gold2);
        word-break: break-all;
    }

    /* ── Card spacing ── */
    .setting-card { margin-bottom: 1.25rem; }

    /* ══════════════════════════════════════
       MOBILE RESPONSIVE
    ═══════════════════════════════════════ */
    @media (max-width: 640px) {
        /* Card padding lebih rapat */
        .card { padding: 1rem; }

        /* Section header lebih compact */
        .sec-hdr { margin-bottom: 1rem; padding-bottom: .75rem; }
        .sec-hdr-title { font-size: .875rem; }

        /* Logo lebih kecil */
        .logo-preview { width: 60px; height: 60px; }
        .logo-actions { gap: .4rem; }
        .logo-actions .btn { font-size: .75rem; padding: .4rem .75rem; }

        /* Form grid jadi 1 kolom */
        .form-grid { grid-template-columns: 1fr !important; }

        /* Datetime row — time input lebih lebar sedikit agar mudah tap */
        .dt-row { flex-direction: row; gap: .4rem; }
        .dt-row input[type=time] { width: 100px; }

        /* Countdown boxes ukuran seimbang */
        .cd-preview-box { min-width: 52px; padding: .6rem .4rem; }
        .cd-preview-box > div:first-child { font-size: 1.35rem !important; }

        /* Submit bar: stack vertikal */
        .submit-bar { flex-direction: column; }
        .submit-bar .btn { min-width: 0; width: 100%; }

        /* Field hint sedikit lebih besar untuk readability */
        .field-hint { font-size: .74rem; }

        /* Preview nomor surat */
        .preview-mono { font-size: .8rem; }
    }
</style>

<form method="POST" action="{{ route('admin.setting.update') }}" enctype="multipart/form-data">
@csrf

{{-- ══════════════════════════════════════════
     SECTION 1 — IDENTITAS SEKOLAH
═══════════════════════════════════════════ --}}
<div class="card setting-card">
    <div class="sec-hdr">
        <span class="sec-hdr-ico">🏫</span>
        <div>
            <div class="sec-hdr-title">Identitas Sekolah</div>
            <div class="sec-hdr-sub">Tampil di header halaman publik dan kop surat kelulusan</div>
        </div>
    </div>

    {{-- Logo --}}
    <div>
        <label style="font-size:.72rem;letter-spacing:.08em;text-transform:uppercase;color:var(--muted);display:block;margin-bottom:.75rem">Logo Sekolah</label>
        <div class="logo-area">
            @php
                $logoPath = $s['sekolah_logo'] ?? '';
                $logoExists = !empty($logoPath) && Storage::disk('public')->exists($logoPath);
                $logoPreviewUrl = $logoExists ? asset('storage/' . $logoPath) : '';
            @endphp
            <div class="logo-preview" id="logoPreview">
                @if($logoPreviewUrl)
                    <img src="{{ $logoPreviewUrl }}" alt="Logo">
                @else
                    <span style="font-size:2rem">🏫</span>
                @endif
            </div>
            <div>
                <input type="file" name="logo" id="logoInput" accept="image/png,image/jpg,image/jpeg,image/svg+xml" style="display:none" onchange="previewLogo(this)">
                <div class="logo-actions">
                    <button type="button" class="btn btn-ghost" onclick="document.getElementById('logoInput').click()">📁 Pilih Logo</button>
                    @if($logoExists)
                        <button type="button" class="btn btn-red" style="padding:.4rem .9rem;font-size:.78rem"
                            onclick="if(confirm('Hapus logo?')) document.getElementById('formHapusLogo').submit()">Hapus</button>
                    @elseif(!empty($logoPath) && !$logoExists)
                        <span class="field-hint" style="color:#fca5a5">⚠ Logo tersimpan tapi tidak tampil. Jalankan: <code>php artisan storage:link</code></span>
                    @endif
                </div>
                <span class="field-hint">PNG, JPG, atau SVG. Maks 2MB.</span>
            </div>
        </div>
    </div>

    <div class="form-grid" style="grid-template-columns:1fr 1fr">
        <div class="form-group full">
            <label>Instansi / Lembaga Atas</label>
            <input type="text" name="sekolah_instansi" value="{{ old('sekolah_instansi', $s['sekolah_instansi'] ?? '') }}" placeholder="Lembaga Pendidikan Ma'arif NU">
            <span class="field-hint">Baris pertama kop surat, di atas nama sekolah. Kosongkan jika tidak ada.</span>
        </div>
        <div class="form-group full">
            <label>Nama Sekolah *</label>
            <input type="text" name="sekolah_nama" value="{{ old('sekolah_nama', $s['sekolah_nama'] ?? '') }}" placeholder="MA AL-AZHAR" required>
            @error('sekolah_nama')<span class="err-msg">{{ $message }}</span>@enderror
        </div>
        <div class="form-group">
            <label>NPSN</label>
            <input type="text" name="sekolah_npsn" value="{{ old('sekolah_npsn', $s['sekolah_npsn'] ?? '') }}" placeholder="12345678" maxlength="20">
        </div>
        <div class="form-group">
            <label>NSM</label>
            <input type="text" name="sekolah_nsm" value="{{ old('sekolah_nsm', $s['sekolah_nsm'] ?? '') }}" placeholder="131235020036" maxlength="30">
            <span class="field-hint">Nomor Statistik Madrasah (khusus madrasah/pesantren)</span>
        </div>
        <div class="form-group">
            <label>Status Akreditasi</label>
            <input type="text" name="sekolah_akreditasi" value="{{ old('sekolah_akreditasi', $s['sekolah_akreditasi'] ?? '') }}" placeholder="Terakreditasi B" maxlength="50">
        </div>
        <div class="form-group full">
            <label>Alamat</label>
            <input type="text" name="sekolah_alamat" value="{{ old('sekolah_alamat', $s['sekolah_alamat'] ?? '') }}" placeholder="Jl. Mangga No. 11 Ringinputih, Sampung, Ponorogo">
        </div>
        <div class="form-group">
            <label>Telepon</label>
            <input type="text" name="sekolah_telp" value="{{ old('sekolah_telp', $s['sekolah_telp'] ?? '') }}" placeholder="081335491082">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="sekolah_email" value="{{ old('sekolah_email', $s['sekolah_email'] ?? '') }}" placeholder="info@sekolah.sch.id">
        </div>
        <div class="form-group">
            <label>Website</label>
            <input type="text" name="sekolah_website" value="{{ old('sekolah_website', $s['sekolah_website'] ?? '') }}" placeholder="www.sekolah.sch.id">
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     SECTION 2 — KEPALA SEKOLAH
═══════════════════════════════════════════ --}}
<div class="card setting-card">
    <div class="sec-hdr">
        <span class="sec-hdr-ico">👤</span>
        <div>
            <div class="sec-hdr-title">Kepala Sekolah</div>
            <div class="sec-hdr-sub">Tampil di bagian tanda tangan surat kelulusan</div>
        </div>
    </div>
    <div class="form-grid" style="grid-template-columns:1fr 1fr">
        <div class="form-group">
            <label>Nama Kepala Sekolah</label>
            <input type="text" name="kepala_nama" value="{{ old('kepala_nama', $s['kepala_nama'] ?? '') }}" placeholder="Supriyanto, M.Pd">
        </div>
        <div class="form-group">
            <label>NIP</label>
            <input type="text" name="kepala_nip" value="{{ old('kepala_nip', $s['kepala_nip'] ?? '') }}" placeholder="197001012000031001 (kosongkan jika tidak ada)">
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     SECTION 3 — FORMAT NOMOR SURAT
═══════════════════════════════════════════ --}}
<div class="card setting-card">
    <div class="sec-hdr">
        <span class="sec-hdr-ico">🔢</span>
        <div>
            <div class="sec-hdr-title">Format Nomor Surat</div>
            <div class="sec-hdr-sub">Tentukan sendiri format penomoran surat kelulusan</div>
        </div>
    </div>
    <div class="form-grid" style="grid-template-columns:1fr 1fr">
        <div class="form-group full">
            <label>Format Nomor Surat</label>
            <input type="text" name="format_nomor_surat"
                value="{{ old('format_nomor_surat', $s['format_nomor_surat'] ?? '{nomor}/SKL/{tahun}') }}"
                placeholder="{nomor}/SKL/{tahun}" id="formatNomorInput" oninput="previewNomor(this.value)">
            <span class="field-hint">
                Placeholder: <code>{nomor}</code> = nomor urut 4 digit &nbsp;·&nbsp;
                <code>{bulan}</code> = bulan cetak &nbsp;·&nbsp;
                <code>{tahun}</code> = tahun lulus siswa
            </span>
        </div>
        <div class="form-group full">
            <label style="color:var(--muted)">Preview</label>
            <div class="preview-mono" id="previewNomorSurat">
                {{ str_replace(['{nomor}','{bulan}','{tahun}'], ['001', now()->format('m'), date('Y')], $s['format_nomor_surat'] ?? '{nomor}/SKL/{tahun}') }}
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     SECTION 4 — PENGUMUMAN
═══════════════════════════════════════════ --}}
<div class="card setting-card">
    <div class="sec-hdr">
        <span class="sec-hdr-ico">📢</span>
        <div>
            <div class="sec-hdr-title">Pengumuman</div>
            <div class="sec-hdr-sub">Judul, tahun pelajaran, dan status aktif halaman cek kelulusan</div>
        </div>
    </div>
    <div class="form-grid" style="grid-template-columns:1fr 1fr">
        <div class="form-group full">
            <label>Judul Pengumuman</label>
            <input type="text" name="pengumuman_judul"
                value="{{ old('pengumuman_judul', $s['pengumuman_judul'] ?? '') }}"
                placeholder="Pengumuman Kelulusan Tahun Pelajaran 2025/2026">
        </div>
        <div class="form-group">
            <label>Tahun Pelajaran</label>
            <input type="text" name="tahun_pelajaran"
                value="{{ old('tahun_pelajaran', $s['tahun_pelajaran'] ?? '') }}"
                placeholder="2025/2026" maxlength="10">
            <span class="field-hint">Tampil di judul halaman publik dan surat kelulusan. Contoh: <code>2025/2026</code></span>
        </div>
        <div class="form-group">
            <label>Status Halaman Cek *</label>
            <select name="pengumuman_aktif">
                <option value="1" {{ ($s['pengumuman_aktif'] ?? '1') === '1' ? 'selected' : '' }}>✅ Aktif — Siswa bisa mencari</option>
                <option value="0" {{ ($s['pengumuman_aktif'] ?? '1') === '0' ? 'selected' : '' }}>🔒 Nonaktif — Form dikunci</option>
            </select>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════
     SECTION 5 — COUNTDOWN
═══════════════════════════════════════════ --}}
<div class="card" style="margin-bottom:2rem">
    <div class="sec-hdr">
        <span class="sec-hdr-ico">⏳</span>
        <div>
            <div class="sec-hdr-title">Countdown Pengumuman</div>
            <div class="sec-hdr-sub">Timer mundur di halaman publik sebelum pengumuman dibuka</div>
        </div>
    </div>
    <div class="form-grid" style="grid-template-columns:1fr 1fr">
        <div class="form-group">
            <label>Tampilkan Countdown *</label>
            <select name="countdown_aktif" id="cdAktifSelect" onchange="toggleCd(this.value)">
                <option value="1" {{ ($s['countdown_aktif'] ?? '0') === '1' ? 'selected' : '' }}>✅ Ya — Tampilkan countdown</option>
                <option value="0" {{ ($s['countdown_aktif'] ?? '0') === '0' ? 'selected' : '' }}>❌ Tidak</option>
            </select>
        </div>
        <div class="form-group" id="cdWaktuWrap" style="{{ ($s['countdown_aktif'] ?? '0') === '0' ? 'opacity:.4;pointer-events:none' : '' }}">
            <label>Tanggal & Waktu Pengumuman</label>
            @php
                $cdVal = $s['countdown_waktu'] ?? '';
                try { $cdCarbon = !empty($cdVal) ? \Carbon\Carbon::parse($cdVal, 'Asia/Jakarta') : null; }
                catch(\Exception $e) { $cdCarbon = null; }
            @endphp
            <div class="dt-row">
                <input type="date" id="cdTanggal" value="{{ $cdCarbon ? $cdCarbon->format('Y-m-d') : '' }}" onchange="gabungDatetime()">
                <input type="time" id="cdJam" value="{{ $cdCarbon ? $cdCarbon->format('H:i') : '08:00' }}" onchange="gabungDatetime()">
            </div>
            <input type="hidden" name="countdown_waktu" id="cdWaktuHidden" value="{{ old('countdown_waktu', $cdCarbon ? $cdCarbon->format('Y-m-d H:i:s') : '') }}">
            @error('countdown_waktu')<span class="err-msg">{{ $message }}</span>@enderror
            <span class="field-hint">Waktu Indonesia Barat (WIB)</span>
        </div>
        <div class="form-group full" id="cdLabelWrap" style="{{ ($s['countdown_aktif'] ?? '0') === '0' ? 'opacity:.4;pointer-events:none' : '' }}">
            <label>Label di atas countdown</label>
            <input type="text" name="countdown_label" value="{{ old('countdown_label', $s['countdown_label'] ?? '') }}" placeholder="Pengumuman kelulusan akan dibuka pada:">
        </div>
        <div class="form-group full" id="cdMsgWrap" style="{{ ($s['countdown_aktif'] ?? '0') === '0' ? 'opacity:.4;pointer-events:none' : '' }}">
            <label>Pesan sebelum pengumuman</label>
            <textarea name="pesan_sebelum" rows="2" placeholder="Pengumuman kelulusan belum dibuka. Pantau terus halaman ini.">{{ old('pesan_sebelum', $s['pesan_sebelum'] ?? '') }}</textarea>
        </div>
        <div class="form-group full" id="cdDoneWrap" style="{{ ($s['countdown_aktif'] ?? '0') === '0' ? 'opacity:.4;pointer-events:none' : '' }}">
            <label>Pesan setelah countdown selesai</label>
            <textarea name="pesan_sesudah" rows="2" placeholder="Pengumuman kelulusan resmi telah dibuka!">{{ old('pesan_sesudah', $s['pesan_sesudah'] ?? '') }}</textarea>
        </div>
    </div>

    @php
        $cdAktif = ($s['countdown_aktif'] ?? '0') === '1';
        $cdWaktu = $s['countdown_waktu'] ?? '';
        $cdBelum = $cdAktif && !empty($cdWaktu) && now('Asia/Jakarta') < \Carbon\Carbon::parse($cdWaktu, 'Asia/Jakarta');
    @endphp
    @if($cdAktif && !empty($cdWaktu))
        <div style="margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid var(--border)">
            <p style="font-size:.68rem;color:var(--muted);margin-bottom:.75rem;letter-spacing:.1em;text-transform:uppercase">Preview saat ini</p>
            @if($cdBelum)
                <div class="cd-preview-grid">
                    @foreach(['pvH'=>'Hari','pvJ'=>'Jam','pvM'=>'Menit','pvD'=>'Detik'] as $id => $lbl)
                    <div class="cd-preview-box">
                        <div style="font-size:1.6rem;font-weight:700;color:var(--gold2)" id="{{ $id }}">--</div>
                        <div style="font-size:.6rem;color:var(--muted);letter-spacing:.1em;text-transform:uppercase;margin-top:.2rem">{{ $lbl }}</div>
                    </div>
                    @endforeach
                </div>
                <p style="font-size:.78rem;color:var(--muted);margin-top:.75rem">
                    Target: <strong style="color:var(--gold2)">{{ \Carbon\Carbon::parse($cdWaktu, 'Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</strong>
                </p>
            @else
                <div style="background:rgba(22,163,74,.1);border:1px solid rgba(22,163,74,.2);border-radius:.75rem;padding:.75rem 1rem;color:#86efac;font-size:.85rem">
                    ✅ Countdown sudah selesai — pengumuman terbuka.
                </div>
            @endif
        </div>
    @endif
</div>

{{-- Submit --}}
<div class="submit-bar" style="margin-bottom:1rem">
    <button class="btn btn-gold" type="submit">💾 Simpan Semua Pengaturan</button>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-ghost">Batal</a>
</div>

</form>

@if(!empty($s['sekolah_logo']))
<form id="formHapusLogo" method="POST" action="{{ route('admin.setting.hapus-logo') }}" style="display:none">
    @csrf
</form>
@endif

<script>
function previewNomor(val) {
    const now = new Date();
    const bulan = String(now.getMonth() + 1).padStart(2, '0');
    const tahun = now.getFullYear();
    const result = val.replace(/{nomor}/g,'001').replace(/{bulan}/g,bulan).replace(/{tahun}/g,tahun);
    document.getElementById('previewNomorSurat').textContent = result || '—';
}
function gabungDatetime() {
    const tgl = document.getElementById('cdTanggal').value;
    const jam = document.getElementById('cdJam').value;
    if (tgl && jam) document.getElementById('cdWaktuHidden').value = tgl + ' ' + jam + ':00';
}
function toggleCd(val) {
    ['cdWaktuWrap','cdLabelWrap','cdMsgWrap','cdDoneWrap'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.style.opacity = val === '1' ? '1' : '.4';
            el.style.pointerEvents = val === '1' ? '' : 'none';
        }
    });
}
function previewLogo(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('logoPreview').innerHTML =
            `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover">`;
    };
    reader.readAsDataURL(input.files[0]);
}
@if($cdBelum)
const pvTarget = new Date("{{ \Carbon\Carbon::parse($cdWaktu, 'Asia/Jakarta')->toIso8601String() }}").getTime();
function pvTick() {
    const diff = pvTarget - Date.now();
    if (diff <= 0) return;
    document.getElementById('pvH').textContent = String(Math.floor(diff / 86400000)).padStart(2,'0');
    document.getElementById('pvJ').textContent = String(Math.floor((diff % 86400000) / 3600000)).padStart(2,'0');
    document.getElementById('pvM').textContent = String(Math.floor((diff % 3600000) / 60000)).padStart(2,'0');
    document.getElementById('pvD').textContent = String(Math.floor((diff % 60000) / 1000)).padStart(2,'0');
}
pvTick(); setInterval(pvTick, 1000);
@endif
</script>
@endsection