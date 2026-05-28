@extends('layouts.admin')
@section('title', 'Data Siswa')

@section('content')
<div class="page-header">
    <h2>Data Siswa</h2>
    <p>Kelola data kelulusan seluruh siswa</p>
</div>

{{-- Flash --}}
@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

{{-- Filter --}}
<div class="card filter-card">
    <form method="GET" action="{{ route('admin.siswa.index') }}" class="filter-form">
        <div class="filter-search">
            <label>Cari NISN / Nama</label>
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Ketik untuk mencari…">
        </div>
        <div class="filter-grid">
            <div class="filter-select">
                <label>Status</label>
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="LULUS"            {{ request('status') === 'LULUS'            ? 'selected' : '' }}>Lulus</option>
                    <option value="LULUS BERSYARAT"  {{ request('status') === 'LULUS BERSYARAT'  ? 'selected' : '' }}>Lulus Bersyarat</option>
                    <option value="TIDAK LULUS"      {{ request('status') === 'TIDAK LULUS'      ? 'selected' : '' }}>Tidak Lulus</option>
                </select>
            </div>
            <div class="filter-select">
                <label>Tahun</label>
                <select name="tahun">
                    <option value="">Semua Tahun</option>
                    @foreach($tahuns as $t)
                        <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}/{{ $t+1 }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-select">
                <label>Akun Portal</label>
                <select name="akun">
                    <option value="">Semua</option>
                    <option value="ada"   {{ request('akun') === 'ada'   ? 'selected' : '' }}>Sudah Ada Akun</option>
                    <option value="belum" {{ request('akun') === 'belum' ? 'selected' : '' }}>Belum Ada Akun</option>
                </select>
            </div>
            <div class="filter-select">
                <label>Foto Profil</label>
                <select name="foto">
                    <option value="">Semua</option>
                    <option value="ada"   {{ request('foto') === 'ada'   ? 'selected' : '' }}>Ada Foto</option>
                    <option value="belum" {{ request('foto') === 'belum' ? 'selected' : '' }}>Belum Ada Foto</option>
                </select>
            </div>
        </div>
        <div class="filter-btns">
            <button class="btn btn-gold" type="submit">Filter</button>
            <a href="{{ route('admin.siswa.index') }}" class="btn btn-ghost">Reset</a>
        </div>
    </form>
</div>

{{-- Action buttons --}}
<div class="action-bar">
    <a href="{{ route('admin.siswa.create') }}" class="btn btn-gold">➕ Tambah Siswa</a>
    <a href="{{ route('admin.siswa.import-form') }}" class="btn btn-ghost">📥 Import Excel / Foto</a>

    {{-- Download SKL Massal --}}
    <div class="skl-wrap">
        <form method="GET" action="{{ route('admin.siswa.skl-massal') }}" target="_blank" style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap">
            <select name="tahun" class="skl-select">
                <option value="">Semua Tahun</option>
                @foreach($tahuns as $t)
                    <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}/{{ $t+1 }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-green">📄 Download SKL Massal</button>
        </form>
    </div>
</div>

{{-- ════ TABEL (md ke atas) ════ --}}
<div class="card tbl-wrap-outer">
    <div class="tbl-scroll">
        <table class="tbl-main">
            <thead>
                <tr>
                    <th class="col-no">#</th>
                    <th class="col-foto">Foto</th>
                    <th>NISN</th>
                    <th>Nama</th>
                    <th class="col-hide-sm">Kelas</th>
                    <th class="col-hide-sm">Tahun</th>
                    <th class="col-hide-md">Nilai</th>
                    <th>Status</th>
                    <th class="col-hide-md">Akun</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($siswas as $i => $s)
                @php $fotoUrl = $s->foto_profil_url; @endphp
                <tr>
                    <td class="col-no td-muted">{{ $siswas->firstItem() + $i }}</td>
                    <td class="col-foto">
                        @if($fotoUrl)
                            <img src="{{ $fotoUrl }}" alt="{{ $s->nama }}" class="tbl-avatar"
                                 onclick="bukaFotoModal('{{ $fotoUrl }}','{{ $s->nama }}','{{ $s->nisn }}')"
                                 title="Klik untuk perbesar">
                        @else
                            <div class="tbl-avatar-empty">👤</div>
                        @endif
                    </td>
                    <td><span class="td-nisn">{{ $s->nisn }}</span></td>
                    <td>
                        <span class="td-nama">{{ $s->nama }}</span>
                        {{-- Kelas + tahun tampil inline saat kolom disembunyikan --}}
                        <div class="td-sub col-show-sm">{{ $s->kelas }} · {{ $s->tahun_lulus }}</div>
                    </td>
                    <td class="col-hide-sm td-sm">{{ $s->kelas }}</td>
                    <td class="col-hide-sm td-sm">{{ $s->tahun_lulus }}</td>
                    <td class="col-hide-md td-sm">{{ $s->nilai_rata ? number_format($s->nilai_rata, 2) : '-' }}</td>
                    <td>
                        @if($s->status === 'LULUS')
                            <span class="pill pill-lulus">Lulus</span>
                        @elseif($s->status === 'LULUS BERSYARAT')
                            <span class="pill pill-bersyarat">Bersyarat</span>
                        @else
                            <span class="pill pill-tidak">Tdk Lulus</span>
                        @endif
                        {{-- Akun ikon tampil di bawah status kalau kolom tersembunyi --}}
                        @if(isset($akunNisns[$s->nisn]))
                            <div class="col-show-md" style="margin-top:.25rem"><span class="pill-akun pill-akun-ada">🔐</span></div>
                        @endif
                    </td>
                    <td class="col-hide-md">
                        @if(isset($akunNisns[$s->nisn]))
                            <span class="pill-akun pill-akun-ada">🔐 Ada</span>
                        @else
                            <span class="pill-akun pill-akun-belum">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="row-actions">
                            <a href="{{ route('admin.siswa.edit', $s) }}" class="btn btn-ghost btn-xs">Edit</a>
                            <a href="{{ route('siswa.surat', $s) }}" class="btn btn-ghost btn-xs" target="_blank" title="Cetak Surat">🖨</a>
                            <form method="POST" action="{{ route('admin.siswa.destroy', $s) }}" onsubmit="return confirm('Hapus data ini?')" style="margin:0">
                                @csrf @method('DELETE')
                                <button class="btn btn-red btn-xs" type="submit">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="td-empty">Tidak ada data ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $siswas->links() }}</div>
</div>

{{-- ════ MOBILE CARDS (< md) ════ --}}
<div class="siswa-cards">
    @forelse($siswas as $i => $s)
    @php $fotoUrl = $s->foto_profil_url; @endphp
    <div class="sc-item card">
        <div class="sc-top">
            <div class="sc-foto" onclick="bukaFotoModal('{{ $fotoUrl ?? '' }}','{{ $s->nama }}','{{ $s->nisn }}')">
                @if($fotoUrl)
                    <img src="{{ $fotoUrl }}" alt="{{ $s->nama }}">
                @else
                    <div class="sc-foto-empty">👤</div>
                @endif
            </div>
            <div class="sc-identity">
                <div class="sc-nama">{{ $s->nama }}</div>
                <div class="sc-nisn">{{ $s->nisn }}</div>
                <div class="sc-meta">
                    {{ $s->kelas }}
                    <span class="sc-sep">·</span>
                    {{ $s->tahun_lulus }}
                    @if($s->nilai_rata)
                        <span class="sc-sep">·</span>
                        {{ number_format($s->nilai_rata, 2) }}
                    @endif
                </div>
            </div>
            <div class="sc-badges">
                @if($s->status === 'LULUS')
                    <span class="pill pill-lulus">Lulus</span>
                @elseif($s->status === 'LULUS BERSYARAT')
                    <span class="pill pill-bersyarat">Bersyarat</span>
                @else
                    <span class="pill pill-tidak">Tdk Lulus</span>
                @endif
                @if(isset($akunNisns[$s->nisn]))
                    <span class="sc-akun-icon" title="Sudah ada akun portal">🔐</span>
                @endif
            </div>
        </div>
        <div class="sc-actions">
            <a href="{{ route('admin.siswa.edit', $s) }}" class="btn btn-ghost btn-xs sc-btn">✏ Edit</a>
            <a href="{{ route('siswa.surat', $s) }}" class="btn btn-ghost btn-xs sc-btn" target="_blank">🖨 Surat</a>
            <form method="POST" action="{{ route('admin.siswa.destroy', $s) }}"
                  onsubmit="return confirm('Hapus data {{ $s->nama }}?')" style="margin:0;flex:1">
                @csrf @method('DELETE')
                <button class="btn btn-red btn-xs sc-btn" type="submit" style="width:100%">🗑 Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div class="card td-empty">Tidak ada data ditemukan.</div>
    @endforelse
    <div class="pagination" style="margin-top:1rem">{{ $siswas->links() }}</div>
</div>

{{-- Modal preview foto --}}
<div id="fotoModal" onclick="tutupFotoModal()" style="
    display:none;position:fixed;inset:0;z-index:500;
    background:rgba(0,0,0,.85);backdrop-filter:blur(12px);
    align-items:center;justify-content:center;
    padding:env(safe-area-inset-top,1rem) 1rem env(safe-area-inset-bottom,1rem);
    cursor:pointer;
">
    <div onclick="event.stopPropagation()" style="max-width:360px;width:100%;cursor:default">
        <img id="fotoModalImg" src="" alt=""
             style="width:100%;border-radius:1rem;display:block;box-shadow:0 24px 60px rgba(0,0,0,.6)">
        <div style="margin-top:.875rem;text-align:center">
            <div id="fotoModalNama" style="font-weight:600;font-size:.95rem"></div>
            <div id="fotoModalNisn" style="font-size:.72rem;color:#7A7268;margin-top:.2rem"></div>
        </div>
        <div style="text-align:center;margin-top:.75rem">
            <button onclick="tutupFotoModal()"
                style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);
                       color:#F0ECE3;padding:.5rem 1.5rem;border-radius:.5rem;
                       cursor:pointer;font-size:.8rem;min-height:44px">
                Tutup
            </button>
        </div>
    </div>
</div>

<style>
/* ═══════════════════════════════════════════
   FLASH
═══════════════════════════════════════════ */
.alert-success {
    background: rgba(34,197,94,.08);
    border: 1px solid rgba(34,197,94,.2);
    border-left: 3px solid #22c55e;
    border-radius: .75rem;
    padding: .7rem 1rem;
    color: #86efac;
    font-size: .82rem;
    margin-bottom: 1.25rem;
}

/* ═══════════════════════════════════════════
   FILTER CARD
═══════════════════════════════════════════ */
.filter-card { margin-bottom: 1.25rem }

.filter-form {
    display: flex;
    flex-direction: column;
    gap: .75rem;
}

.filter-search {
    display: flex;
    flex-direction: column;
    gap: .35rem;
}

/* Grid filter: 4 kolom → 2 → 1 */
.filter-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: .75rem;
}

.filter-select {
    display: flex;
    flex-direction: column;
    gap: .35rem;
}

.filter-form label { font-size: .72rem; color: var(--muted) }

.filter-btns { display: flex; gap: .5rem }
.filter-btns .btn { flex: 1; justify-content: center }

/* ═══════════════════════════════════════════
   ACTION BAR
═══════════════════════════════════════════ */
.action-bar {
    display: flex;
    gap: .75rem;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
}
.action-bar .btn {
    flex: 1;
    min-width: 150px;
    justify-content: center;
}

/* ═══════════════════════════════════════════
   TABEL DESKTOP
═══════════════════════════════════════════ */
.tbl-wrap-outer { display: block }
.siswa-cards    { display: none  }

/* Scroll horizontal di layar kecil — tabel tidak pecah */
.tbl-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch }

.tbl-main { width: 100%; border-collapse: collapse }

.col-no   { width: 36px }
.col-foto { width: 52px; padding-right: .25rem !important }
.td-sm    { font-size: .8rem }
.td-muted { color: var(--muted) }
.td-empty { text-align: center; color: var(--muted); padding: 2.5rem }
.td-sub   { font-size: .7rem; color: var(--muted); margin-top: .15rem; display: none }

/* Kolom yang disembunyikan secara bertahap */
.col-hide-sm  { }   /* disembunyikan di < 640 */
.col-show-sm  { display: none }  /* muncul di < 640 */
.col-hide-md  { }   /* disembunyikan di < 768 */
.col-show-md  { display: none }  /* muncul di < 768 */

.tbl-avatar {
    width: 38px; height: 38px;
    border-radius: .5rem;
    object-fit: cover;
    display: block;
    cursor: pointer;
    transition: transform .2s, box-shadow .2s;
    border: 1px solid rgba(255,255,255,.08);
}
.tbl-avatar:hover { transform: scale(1.12); box-shadow: 0 4px 16px rgba(0,0,0,.4) }

.tbl-avatar-empty {
    width: 38px; height: 38px;
    border-radius: .5rem;
    background: rgba(255,255,255,.04);
    border: 1px dashed rgba(255,255,255,.1);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; color: rgba(122,114,104,.5);
}

.td-nisn { font-size: .8rem }
.td-nama { font-size: .82rem }

.btn-xs {
    padding: .4rem .7rem !important;
    font-size: .72rem !important;
    min-height: 36px;
}

.row-actions { display: flex; gap: .35rem; align-items: center }

.pill-akun {
    font-size: .65rem;
    padding: .2rem .55rem;
    border-radius: 999px;
    white-space: nowrap;
    font-weight: 600;
}
.pill-akun-ada {
    background: rgba(201,168,76,.1);
    border: 1px solid rgba(201,168,76,.25);
    color: var(--gold2);
}
.pill-akun-belum { color: var(--muted); font-size: .75rem }

.pill-bersyarat {
    background: rgba(251,146,60,.1);
    border: 1px solid rgba(251,146,60,.25);
    color: #fb923c;
}

/* ═══════════════════════════════════════════
   MOBILE CARDS
═══════════════════════════════════════════ */
.siswa-cards { flex-direction: column; gap: .625rem }
.sc-item { padding: .875rem !important }

.sc-top {
    display: flex;
    gap: .75rem;
    align-items: center;
    margin-bottom: .75rem;
}

.sc-foto {
    width: 52px; height: 52px; flex-shrink: 0;
    border-radius: .75rem; overflow: hidden;
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.08);
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
}
.sc-foto img { width: 100%; height: 100%; object-fit: cover; display: block }
.sc-foto-empty { font-size: 1.6rem; line-height: 1 }

.sc-identity { flex: 1; min-width: 0 }
.sc-nama {
    font-size: .875rem; font-weight: 600; line-height: 1.3;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.sc-nisn { font-size: .7rem; color: var(--gold2, #c9a84c); margin-top: .1rem; font-weight: 600 }
.sc-meta {
    display: flex; flex-wrap: wrap; align-items: center;
    gap: .25rem; font-size: .69rem; color: var(--muted); margin-top: .2rem;
}
.sc-sep { opacity: .35 }

.sc-badges { display: flex; flex-direction: column; align-items: flex-end; gap: .3rem; flex-shrink: 0 }
.sc-akun-icon { font-size: .9rem; opacity: .8 }

.sc-actions {
    display: flex; gap: .5rem; align-items: center;
    padding-top: .625rem;
    border-top: 1px solid var(--border);
}
.sc-btn { flex: 1; justify-content: center; min-height: 40px }

/* ═══════════════════════════════════════════
   PAGINATION
═══════════════════════════════════════════ */
.pagination { margin-top: 1.5rem }
.pagination nav { display: flex !important; flex-direction: column !important; align-items: center !important; gap: .5rem !important }
.pagination nav > div:first-child { display: none !important }
.pagination nav > div:last-child {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    gap: 4px !important;
    flex-wrap: wrap !important;
}
.pagination nav span,
.pagination nav a {
    all: unset !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 36px !important;
    height: 36px !important;
    padding: 0 10px !important;
    border-radius: .5rem !important;
    font-size: .78rem !important;
    font-weight: 400 !important;
    line-height: 1 !important;
    cursor: pointer !important;
    text-decoration: none !important;
    transition: background .15s, border-color .15s !important;
    box-sizing: border-box !important;
    white-space: nowrap !important;
}
.pagination nav a {
    border: 1px solid rgba(255,255,255,.12) !important;
    color: rgba(255,255,255,.5) !important;
    background: transparent !important;
}
.pagination nav a:hover {
    background: rgba(255,255,255,.07) !important;
    border-color: rgba(255,255,255,.2) !important;
    color: rgba(255,255,255,.85) !important;
}
.pagination nav span[aria-current="page"] > span {
    border: 2px solid rgba(201,168,76,.6) !important;
    background: rgba(201,168,76,.12) !important;
    color: #c9a84c !important;
    font-weight: 500 !important;
    cursor: default !important;
}
.pagination nav span[aria-disabled="true"] > span {
    border: 1px solid rgba(255,255,255,.07) !important;
    color: rgba(255,255,255,.15) !important;
    background: transparent !important;
    cursor: not-allowed !important;
}
.pagination nav span.relative > span,
.pagination nav span[role] {
    border: none !important;
    color: rgba(255,255,255,.3) !important;
    min-width: 24px !important;
    padding: 0 4px !important;
    cursor: default !important;
}
.pagination nav a[rel="prev"] svg,
.pagination nav a[rel="next"] svg { display: none !important }
.pagination nav a[rel="prev"]::before { content: "←" !important; font-size: 1rem !important }
.pagination nav a[rel="next"]::after  { content: "→" !important; font-size: 1rem !important }

/* ═══════════════════════════════════════════
   BREAKPOINTS
═══════════════════════════════════════════ */

/* ── Tablet landscape / small desktop (< 1024px) ── */
@media (max-width: 1024px) {
    .filter-grid { grid-template-columns: repeat(2, 1fr) }
}

/* ── Tablet portrait (< 768px) ──
   Sembunyikan kolom Nilai & Akun dari tabel;
   info itu ditampilkan di dalam sel Status/Nama  */
@media (max-width: 768px) {
    .col-hide-md { display: none }
    .col-show-md { display: block }
}

/* ── Mobile landscape / besar (< 640px) ──
   Sembunyikan Kelas & Tahun dari tabel;
   tampilkan sebagai sub-teks di kolom Nama      */
@media (max-width: 640px) {
    .tbl-wrap-outer { display: none !important }
    .siswa-cards    { display: flex }

    .filter-grid { grid-template-columns: repeat(2, 1fr) }
    .card { padding: .875rem }
    .action-bar .btn { min-width: 0 }

    .pagination nav span,
    .pagination nav a { min-width: 32px !important; height: 32px !important; font-size: .73rem !important }
}

/* ── Antara 640px–768px: tabel tetap tampil, kurangi kolom ── */
@media (min-width: 641px) and (max-width: 768px) {
    .col-hide-sm { display: none }
    .col-show-sm { display: block }
    .td-sub      { display: block }
}

/* ── Mobile kecil (< 400px) ── */
@media (max-width: 400px) {
    .filter-grid { grid-template-columns: 1fr }
    .sc-actions  { flex-wrap: wrap }
    .sc-btn      { min-width: calc(50% - .25rem) }
    .filter-btns { flex-direction: column }
    .filter-btns .btn { width: 100% }
}

/* ── Ultrawide / large desktop (≥ 1400px) ── */
@media (min-width: 1400px) {
    .filter-grid { grid-template-columns: repeat(4, 1fr) }
    .tbl-main td, .tbl-main th { padding: .7rem 1rem }
}
{{-- Action buttons --}}
<div class="action-bar">
    <a href="{{ route('admin.siswa.create') }}" class="btn btn-gold">➕ Tambah Siswa</a>
    <a href="{{ route('admin.siswa.import-form') }}" class="btn btn-ghost">📥 Import Excel / Foto</a>

    {{-- SKL Massal --}}
    <form method="GET" action="{{ route('admin.siswa.skl-massal') }}" target="_blank"
          style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;margin:0">
        <select name="tahun" style="
            background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.13);
            border-radius:.5rem;color:#F0ECE3;padding:.45rem .75rem;font-size:.8rem;height:38px;cursor:pointer">
            <option value="">Semua Tahun</option>
            @foreach($tahuns as $t)
                <option value="{{ $t }}">{{ $t }}/{{ $t+1 }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-ghost" style="border-color:rgba(34,197,94,.4);color:#86efac">
            📄 SKL Massal
        </button>
    </form>
</div>
</style>

<script>
function bukaFotoModal(src, nama, nisn) {
    if (!src) return;
    document.getElementById('fotoModalImg').src = src;
    document.getElementById('fotoModalNama').textContent = nama;
    document.getElementById('fotoModalNisn').textContent = 'NISN: ' + nisn;
    document.getElementById('fotoModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function tutupFotoModal() {
    document.getElementById('fotoModal').style.display = 'none';
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') tutupFotoModal();
});
</script>

@endsection