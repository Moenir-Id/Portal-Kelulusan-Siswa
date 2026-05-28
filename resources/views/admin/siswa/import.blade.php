@extends('layouts.admin')
@section('title', 'Import Data Siswa')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <a href="{{ route('admin.siswa.index') }}" class="back-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </a>
        <div>
            <h2>Import Data Siswa</h2>
            <p>Upload file Excel/CSV untuk data siswa, atau ZIP untuk foto profil massal</p>
        </div>
    </div>
</div>

{{-- ── Flash Messages ── --}}
@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1.25rem">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
        {{ session('success') }}
    </div>
@endif

@if(session('foto_import_success'))
    @php $detail = session('foto_import_detail', []); @endphp
    <div class="alert alert-success" style="margin-bottom:1.25rem;flex-direction:column;align-items:flex-start;gap:.625rem">
        <div style="display:flex;align-items:center;gap:.5rem">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            <strong>{{ session('foto_import_success') }}</strong>
        </div>
        @if(!empty($detail))
        <div style="display:flex;gap:.75rem;flex-wrap:wrap;margin-top:.2rem">
            <div class="stat-chip stat-green">✅ {{ $detail['berhasil'] }} berhasil</div>
            @if($detail['tidakAda'] > 0)
                <div class="stat-chip stat-yellow">⚠ {{ $detail['tidakAda'] }} NISN tidak ditemukan</div>
            @endif
            @if($detail['dilewati'] > 0)
                <div class="stat-chip stat-muted">— {{ $detail['dilewati'] }} dilewati</div>
            @endif
        </div>
        @endif
    </div>
@endif

{{-- ── Layout dua kolom ── --}}
<div class="import-layout">

    {{-- ═══ SECTION 1: IMPORT SISWA + AKUN ═══ --}}
    <div class="section-card">
        <div class="section-card-header">
            <div class="section-icon-wrap icon-blue">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2z"/><polyline points="12 11 12 17"/><polyline points="9 14 12 17 15 14"/></svg>
            </div>
            <div>
                <div class="section-title">Import Data Siswa</div>
                <div class="section-sub">Satu file untuk data siswa & akun portal sekaligus</div>
            </div>
        </div>

        <div class="info-box">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="flex-shrink:0;margin-top:.15rem"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <div style="width:100%;min-width:0">
                <div style="margin-bottom:.6rem"><strong>Format kolom Excel / CSV:</strong></div>
                <div class="col-table">
                    <div class="col-row col-row-head">
                        <span>Kolom</span><span>Keterangan</span><span>Wajib?</span>
                    </div>
                    <div class="col-row"><span><code>nisn</code></span><span>Nomor Induk Siswa Nasional</span><span class="badge-wajib">Wajib</span></div>
                    <div class="col-row"><span><code>nama</code></span><span>Nama lengkap siswa</span><span class="badge-wajib">Wajib</span></div>
                    <div class="col-row"><span><code>kelas</code></span><span>Contoh: XII IPA 1</span><span class="badge-wajib">Wajib</span></div>
                    <div class="col-row"><span><code>tahun_lulus</code></span><span>Contoh: 2026</span><span class="badge-wajib">Wajib</span></div>
                    <div class="col-row"><span><code>status</code></span><span><code>LULUS</code>, <code>LULUS BERSYARAT</code>, atau <code>TIDAK LULUS</code></span><span class="badge-wajib">Wajib</span></div>
                    <div class="col-row"><span><code>nilai_rata</code></span><span>Contoh: 87.50</span><span class="badge-ops">Opsional</span></div>
                    <div class="col-row"><span><code>catatan</code></span><span>Catatan tambahan</span><span class="badge-ops">Opsional</span></div>
                    <div class="col-row col-row-highlight"><span><code>password</code></span><span>Kosongkan jika tidak buat akun</span><span class="badge-ops">Opsional</span></div>
                </div>
                <div style="margin-top:.625rem;font-size:.72rem;color:var(--muted)">
                    💡 Jika NISN sudah ada, data & password akan <strong style="color:var(--text)">diperbarui</strong>.
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.siswa.import') }}" enctype="multipart/form-data" id="form-import">
            @csrf
            <div class="upload-area" id="drop-siswa" onclick="document.getElementById('file').click()">
                <div class="upload-icon">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                </div>
                <div class="upload-text">Klik atau seret file ke sini</div>
                <div class="upload-sub">Format: .xlsx, .xls, atau .csv · Maks. 5 MB</div>
                <div class="upload-filename" id="filename-siswa" style="display:none"></div>
                <input type="file" id="file" name="file" accept=".xlsx,.xls,.csv" style="display:none"
                       onchange="showFilename(this, 'filename-siswa', 'drop-siswa')">
            </div>
            @error('file')
                <span class="err-msg" style="margin-top:.5rem;display:flex">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $message }}
                </span>
            @enderror

            <div class="form-actions">
                <button class="btn btn-primary" type="submit">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Import Sekarang
                </button>
                <a href="{{ route('admin.siswa.import-template') }}" class="btn btn-ghost">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Download Template CSV
                </a>
            </div>
        </form>
    </div>

    {{-- ═══ SECTION 2: IMPORT FOTO MASSAL ═══ --}}
    <div class="section-card">
        <div class="section-card-header">
            <div class="section-icon-wrap icon-purple">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </div>
            <div>
                <div class="section-title">Import Foto Profil Massal</div>
                <div class="section-sub">Upload ZIP — nama file = NISN siswa</div>
            </div>
        </div>

        <div class="info-box" style="margin-bottom:1.25rem">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="flex-shrink:0;margin-top:.15rem"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <div style="width:100%;min-width:0">
                <div style="margin-bottom:.75rem"><strong>Cara menyiapkan file ZIP:</strong></div>
                <div class="steps">
                    <div class="step">
                        <div class="step-num">1</div>
                        <div class="step-body">
                            Kumpulkan foto siswa. <strong>Ganti nama file</strong> menjadi NISN masing-masing.
                            <div class="file-example">
                                <div class="file-tree">
                                    <div class="file-item">📁 foto-siswa/</div>
                                    <div class="file-item file-indent">🖼 <span class="fname">0078103635</span>.jpg</div>
                                    <div class="file-item file-indent">🖼 <span class="fname">131235020036</span>.png</div>
                                    <div class="file-item file-indent">🖼 <span class="fname">0091234567</span>.jpg</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-num">2</div>
                        <div class="step-body">Compress folder menjadi file <code>.zip</code>.</div>
                    </div>
                    <div class="step">
                        <div class="step-num">3</div>
                        <div class="step-body">Upload ZIP di bawah. Sistem memetakan foto ke siswa berdasarkan NISN.</div>
                    </div>
                </div>
                <div style="margin-top:.875rem;display:flex;flex-wrap:wrap;gap:.5rem;font-size:.72rem">
                    <span class="tag-info">✅ Format: JPG, PNG, WebP</span>
                    <span class="tag-info">✅ Foto lama terganti otomatis</span>
                    <span class="tag-info">✅ NISN tidak cocok = dilewati</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.siswa.import-foto') }}" enctype="multipart/form-data" id="form-foto">
            @csrf
            <div class="upload-area upload-area-purple" id="drop-foto" onclick="document.getElementById('file-zip').click()">
                <div class="upload-icon" style="color:#a78bfa;opacity:1">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                        <line x1="9" y1="12" x2="15" y2="12" stroke-width="1" opacity=".5"/>
                        <line x1="10" y1="15" x2="14" y2="15" stroke-width="1" opacity=".3"/>
                    </svg>
                </div>
                <div class="upload-text">Klik atau seret file ZIP ke sini</div>
                <div class="upload-sub">Format: .zip · Maks. 50 MB</div>
                <div class="upload-filename" id="filename-foto" style="display:none"></div>
                <input type="file" id="file-zip" name="zip_foto" accept=".zip" style="display:none"
                       onchange="showFilename(this, 'filename-foto', 'drop-foto')">
            </div>

            @error('zip_foto')
                <span class="err-msg" style="margin-top:.5rem;display:flex">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $message }}
                </span>
            @enderror

            <div class="form-actions">
                <button class="btn btn-purple" type="submit" id="btnImportFoto">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    Import Foto Sekarang
                </button>
            </div>
        </form>

        <div id="uploadProgress" style="display:none;margin-top:1rem">
            <div class="progress-bar-wrap">
                <div class="progress-bar-inner" id="progressBarInner"></div>
            </div>
            <div style="font-size:.72rem;color:var(--muted);margin-top:.4rem;text-align:center">
                Sedang mengekstrak dan menyimpan foto, harap tunggu…
            </div>
        </div>
    </div>

</div>{{-- end .import-layout --}}

<style>
/* ── Page Header ── */
.page-header { margin-bottom: 1.5rem; }
.page-header-left { display: flex; align-items: center; gap: 1rem; }
.back-btn {
    display: flex; align-items: center; justify-content: center;
    width: 2.5rem; height: 2.5rem; border-radius: .625rem;
    border: 1px solid var(--border); color: var(--muted);
    text-decoration: none; background: rgba(255,255,255,.03); transition: all .2s; flex-shrink: 0;
}
.back-btn:hover { background: rgba(255,255,255,.07); color: var(--text); border-color: rgba(255,255,255,.15); }
.page-header h2 { font-size: 1.15rem; font-weight: 600; margin: 0 0 .2rem; }
.page-header p { font-size: .78rem; color: var(--muted); margin: 0; }

/* ── Import layout (dua kolom) ── */
.import-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
    align-items: start;
}

/* ── Section Card ── */
.section-card {
    background: var(--card, rgba(255,255,255,.04));
    border: 1px solid var(--border); border-radius: 1rem; padding: 1.5rem;
}
.section-card-header {
    display: flex; align-items: center; gap: .875rem;
    padding-bottom: 1.25rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border);
}
.section-icon-wrap {
    width: 2.4rem; height: 2.4rem; border-radius: .75rem;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.icon-blue   { background: rgba(59,130,246,.12);  border: 1px solid rgba(59,130,246,.2);  color: #93c5fd; }
.icon-purple { background: rgba(139,92,246,.12);  border: 1px solid rgba(139,92,246,.2);  color: #c4b5fd; }
.section-title { font-size: .875rem; font-weight: 600; margin-bottom: .15rem; }
.section-sub { font-size: .73rem; color: var(--muted); }

/* ── Info Box ── */
.info-box {
    display: flex; gap: .625rem;
    background: rgba(255,255,255,.03); border: 1px solid var(--border);
    border-radius: .75rem; padding: 1rem;
    font-size: .775rem; color: var(--muted); line-height: 1.55; margin-bottom: 1.25rem;
}

/* ── Column Table ── */
.col-table { display: flex; flex-direction: column; border-radius: .625rem; overflow: hidden; border: 1px solid var(--border); }
.col-row {
    display: grid; grid-template-columns: 7rem 1fr 5rem;
    gap: .5rem; align-items: center;
    padding: .5rem .75rem; font-size: .73rem;
    border-bottom: 1px solid var(--border);
}
.col-row:last-child { border-bottom: none; }
.col-row-head { font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; color: var(--muted); background: rgba(255,255,255,.03); }
.col-row-highlight { background: rgba(201,168,76,.04); }
.col-row code { font-family: monospace; font-size: .72rem; background: rgba(255,255,255,.07); padding: .1rem .4rem; border-radius: .3rem; border: 1px solid var(--border); color: var(--text); }
.badge-wajib { font-size: .65rem; font-weight: 600; padding: .15rem .55rem; border-radius: 999px; background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.2); color: #fca5a5; white-space: nowrap; text-align: center; }
.badge-ops   { font-size: .65rem; font-weight: 600; padding: .15rem .55rem; border-radius: 999px; background: rgba(255,255,255,.05); border: 1px solid var(--border); color: var(--muted); white-space: nowrap; text-align: center; }

/* ── Steps ── */
.steps { display: flex; flex-direction: column; gap: .75rem; }
.step  { display: flex; gap: .75rem; align-items: flex-start; }
.step-num {
    width: 22px; height: 22px; border-radius: 50%; flex-shrink: 0;
    background: rgba(139,92,246,.15); border: 1px solid rgba(139,92,246,.3);
    color: #c4b5fd; font-size: .7rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center; margin-top: .1rem;
}
.step-body { font-size: .775rem; color: var(--muted); line-height: 1.55; }
.step-body strong { color: var(--text); }
.step-body code { font-family: monospace; font-size: .72rem; background: rgba(255,255,255,.07); padding: .1rem .4rem; border-radius: .3rem; border: 1px solid var(--border); color: var(--text); }

/* ── File tree ── */
.file-example { margin: .625rem 0 .25rem; }
.file-tree { background: rgba(0,0,0,.25); border: 1px solid var(--border); border-radius: .625rem; padding: .625rem .875rem; font-family: monospace; font-size: .75rem; color: var(--muted); }
.file-item { padding: .15rem 0; }
.file-indent { padding-left: 1.25rem; }
.fname { color: #c4b5fd; font-weight: 600; }

/* ── Tag info ── */
.tag-info { font-size: .68rem; padding: .2rem .625rem; border-radius: 999px; background: rgba(255,255,255,.05); border: 1px solid var(--border); color: var(--muted); }

/* ── Stat chips ── */
.stat-chip { font-size: .72rem; padding: .2rem .625rem; border-radius: 999px; font-weight: 600; }
.stat-green  { background: rgba(34,197,94,.1);  border: 1px solid rgba(34,197,94,.2);  color: #86efac; }
.stat-yellow { background: rgba(234,179,8,.1);  border: 1px solid rgba(234,179,8,.2);  color: #fde68a; }
.stat-muted  { background: rgba(255,255,255,.05); border: 1px solid var(--border); color: var(--muted); }

/* ── Upload Area ── */
.upload-area {
    border: 2px dashed var(--border); border-radius: .875rem; padding: 2rem 1.5rem;
    display: flex; flex-direction: column; align-items: center;
    gap: .5rem; cursor: pointer; text-align: center;
    transition: border-color .2s, background .2s;
    margin-bottom: 1.25rem; background: rgba(255,255,255,.02);
    min-height: 44px;
}
.upload-area:hover { border-color: #3b82f6; background: rgba(59,130,246,.04); }
.upload-area.has-file { border-color: #22c55e; background: rgba(34,197,94,.04); border-style: solid; }
.upload-area-purple:hover { border-color: #7c3aed; background: rgba(139,92,246,.04); }
.upload-area-purple.has-file { border-color: #22c55e; background: rgba(34,197,94,.04); border-style: solid; }
.upload-icon { color: var(--muted); opacity: .5; margin-bottom: .25rem; }
.upload-text { font-size: .82rem; font-weight: 600; }
.upload-sub  { font-size: .72rem; color: var(--muted); }
.upload-filename { font-size: .75rem; font-weight: 600; color: #86efac; background: rgba(34,197,94,.1); border: 1px solid rgba(34,197,94,.2); padding: .3rem .875rem; border-radius: 999px; margin-top: .25rem; }

/* ── Progress bar ── */
.progress-bar-wrap { background: rgba(255,255,255,.06); border-radius: 999px; height: 6px; overflow: hidden; }
.progress-bar-inner { height: 100%; width: 0; background: linear-gradient(90deg,#8b5cf6,#c4b5fd); border-radius: 999px; animation: progAnim 2s ease-in-out infinite; }
@keyframes progAnim { 0%{width:0;margin-left:0} 50%{width:60%;margin-left:20%} 100%{width:0;margin-left:100%} }

/* ── Buttons ── */
.form-actions { display: flex; gap: .75rem; flex-wrap: wrap; }
.btn {
    display: inline-flex; align-items: center; gap: .45rem;
    padding: .625rem 1.125rem; border-radius: .625rem; min-height: 44px;
    font-size: .8rem; font-weight: 600; font-family: inherit;
    cursor: pointer; border: none; text-decoration: none; transition: all .2s; white-space: nowrap;
}
.btn-primary { background: #3b82f6; color: #fff; }
.btn-primary:hover { filter: brightness(1.1); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(59,130,246,.3); }
.btn-purple { background: rgba(139,92,246,.85); color: #fff; }
.btn-purple:hover { filter: brightness(1.1); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(139,92,246,.35); }
.btn-ghost { background: rgba(255,255,255,.06); color: var(--muted); border: 1px solid var(--border); }
.btn-ghost:hover { background: rgba(255,255,255,.1); color: var(--text); }

/* ── Error ── */
.err-msg { font-size: .72rem; color: #FCA5A5; display: flex; align-items: center; gap: .3rem; }

/* ── Alerts ── */
.alert { display: flex; align-items: flex-start; gap: .6rem; padding: .75rem 1rem; border-radius: .75rem; font-size: .8rem; }
.alert-success { background: rgba(34,197,94,.08); border: 1px solid rgba(34,197,94,.2); border-left: 3px solid #22c55e; color: #86efac; }

/* ── Responsive ── */
@media (max-width: 860px) {
    .import-layout { grid-template-columns: 1fr; }
}

@media (max-width: 640px) {
    .section-card { padding: 1rem; }
    .form-actions { flex-direction: column; }
    .form-actions .btn { width: 100%; justify-content: center; }
    .col-row { grid-template-columns: 5.5rem 1fr 4rem; font-size: .68rem; }
    .steps { gap: .625rem; }
    .info-box { flex-direction: column; }
}
</style>

<script>
function showFilename(input, labelId, areaId) {
    var label = document.getElementById(labelId);
    var area  = document.getElementById(areaId);
    if (input.files && input.files[0]) {
        label.textContent = '📄 ' + input.files[0].name;
        label.style.display = 'block';
        area.classList.add('has-file');
    } else {
        label.style.display = 'none';
        area.classList.remove('has-file');
    }
}

// Drag & drop — Excel
var areaExcel = document.getElementById('drop-siswa');
areaExcel.addEventListener('dragover',  function(e) { e.preventDefault(); areaExcel.style.borderColor = '#3b82f6'; });
areaExcel.addEventListener('dragleave', function()  { if (!document.getElementById('file').files.length) areaExcel.style.borderColor = ''; });
areaExcel.addEventListener('drop',      function(e) {
    e.preventDefault();
    var input = document.getElementById('file');
    input.files = e.dataTransfer.files;
    showFilename(input, 'filename-siswa', 'drop-siswa');
});

// Drag & drop — ZIP
var areaZip = document.getElementById('drop-foto');
areaZip.addEventListener('dragover',  function(e) { e.preventDefault(); areaZip.style.borderColor = '#7c3aed'; });
areaZip.addEventListener('dragleave', function()  { if (!document.getElementById('file-zip').files.length) areaZip.style.borderColor = ''; });
areaZip.addEventListener('drop',      function(e) {
    e.preventDefault();
    var input = document.getElementById('file-zip');
    input.files = e.dataTransfer.files;
    showFilename(input, 'filename-foto', 'drop-foto');
});

// Progress saat submit form ZIP
document.getElementById('form-foto').addEventListener('submit', function() {
    var btn = document.getElementById('btnImportFoto');
    btn.disabled = true;
    btn.textContent = '⏳ Memproses…';
    document.getElementById('uploadProgress').style.display = 'block';
});
</script>

@endsection
