@extends('layouts.admin')
@section('title', 'Galeri Momen')
@section('content')
<div class="page-header">
    <h2>Galeri Momen Siswa</h2>
    <p>{{ $momens->total() }} foto telah dibagikan oleh siswa</p>
</div>

@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

{{-- ── Toolbar Download ── --}}
@if($momens->count())
<div class="galeri-toolbar">
    <div class="galeri-info">
        Menampilkan <strong>{{ $momens->count() }}</strong> dari <strong>{{ $momens->total() }}</strong> foto
    </div>
    <div class="galeri-actions">
        <button class="btn-dl" id="btnDlPage" onclick="downloadPage()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download Halaman Ini
        </button>
        @if($momens->total() > $momens->perPage())
        <button class="btn-dl btn-dl-all" id="btnDlAll" onclick="downloadAll()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download Semua ({{ $momens->total() }} foto)
        </button>
        @endif
    </div>
</div>

{{-- Progress bar --}}
<div class="dl-progress-wrap" id="dlProgressWrap" style="display:none">
    <div class="dl-progress-bar"><div class="dl-progress-fill" id="dlProgressFill"></div></div>
    <div class="dl-progress-txt" id="dlProgressTxt">Memuat foto…</div>
</div>
@endif

@if($momens->count())
<div class="galeri-admin-grid" id="galeriGrid">
    @foreach($momens as $m)
    @php
        $sa   = $m->siswaAccount;
        $sv   = $sa?->siswa;
        $nama = $sv?->nama ?? 'Siswa';
        $nisn = $sv?->nisn ?? '-';
    @endphp
    <div class="ga-item card"
         data-idx="{{ $loop->index }}"
         data-foto="{{ asset($m->foto) }}"
         data-nama="{{ $nama }}"
         data-nisn="{{ $nisn }}"
         data-id="{{ $m->id }}"
         data-caption="{{ $m->caption ?? '' }}"
         data-time="{{ $m->created_at->format('d M Y, H:i') }}"
         data-hapus="{{ route('admin.galeri.destroy', $m) }}"
         onclick="bukaLightbox(this)">
        <div class="ga-img-wrap">
            <img src="{{ asset($m->foto) }}" alt="{{ $nama }}" loading="lazy">
            <a class="ga-dl-btn" href="#" title="Unduh foto"
               onclick="event.stopPropagation();downloadSatu('{{ asset($m->foto) }}','{{ $nama }}','{{ $m->id }}');return false;">⬇</a>
        </div>
        <div class="ga-body">
            <div class="ga-nama">{{ $nama }}</div>
            <div class="ga-nisn">NISN: {{ $nisn }}</div>
            @if($m->caption)
                <div class="ga-caption">{{ Str::limit($m->caption, 80) }}</div>
            @endif
            <div class="ga-time">{{ $m->created_at->format('d M Y, H:i') }}</div>
        </div>
    </div>
    @endforeach
</div>
<div class="pagination" style="margin-top:1.5rem">
    {{ $momens->links() }}
</div>
@else
<div class="card" style="text-align:center;padding:3rem;color:var(--muted)">
    <div style="font-size:2.5rem;margin-bottom:.75rem">📷</div>
    <strong>Belum ada foto yang dibagikan.</strong>
</div>
@endif

{{-- ═══ LIGHTBOX ═══ --}}
<div class="lb-overlay" id="lbOverlay" onclick="if(event.target===this)tutupLightbox()">
    <div class="lb-inner">
        <div class="lb-img-wrap">
            <img class="lb-img" id="lbImg" src="" alt="">
            <button class="lb-close" onclick="tutupLightbox()">✕</button>
            <button class="lb-prev" id="lbPrev" onclick="navigasi(-1)">‹</button>
            <button class="lb-next" id="lbNext" onclick="navigasi(1)">›</button>
            <div class="lb-counter" id="lbCounter">1 / 1</div>
        </div>
        <div class="lb-info">
            <div class="lb-meta">
                <div class="lb-nama" id="lbNama"></div>
                <div class="lb-nisn" id="lbNisn"></div>
                <div class="lb-caption" id="lbCaption"></div>
                <div class="lb-time" id="lbTime"></div>
            </div>
            <div class="lb-actions">
                <button class="lb-btn lb-btn-dl" onclick="downloadSatuLb()">
                    <svg width="13" height="13" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v8M5 7l3 3 3-3"/><path d="M2 12h12"/></svg>
                    Unduh
                </button>
                <button class="lb-btn lb-btn-del" id="lbBtnHapus" onclick="hapusDariLightbox()">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Form hapus tersembunyi --}}
<form id="formHapusLb" method="POST" style="display:none">
    @csrf
    @method('DELETE')
</form>

{{-- Toast --}}
<div class="dl-toast" id="dlToast">✅ Foto berhasil diunduh</div>

<style>
/* ── Flash ── */
.alert-success{background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.2);border-left:3px solid #22c55e;border-radius:.75rem;padding:.75rem 1rem;color:#86efac;font-size:.82rem;margin-bottom:1.25rem}

/* ── Grid — fluid, kolom minimal 180px ── */
.galeri-admin-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 1rem;
}

.ga-item { padding: 0 !important; overflow: hidden; cursor: pointer; border-radius: .875rem; }
.ga-img-wrap { aspect-ratio: 1/1; overflow: hidden; background: #0a0a0a; position: relative; }
.ga-img-wrap img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .3s; }
.ga-item:hover .ga-img-wrap img { transform: scale(1.05); }
.ga-body { padding: .75rem .875rem; }
.ga-nama { font-size: .82rem; font-weight: 600; line-height: 1.3; }
.ga-nisn { font-size: .68rem; color: var(--gold2, #E8C97A); margin-top: .15rem; }
.ga-caption { font-size: .72rem; color: var(--muted, #7A7268); margin-top: .35rem; line-height: 1.5; }
.ga-time { font-size: .65rem; color: var(--muted, #7A7268); margin-top: .3rem; }

/* ── Download btn di thumbnail ── */
.ga-dl-btn {
    position: absolute; top: .5rem; right: .5rem;
    background: rgba(0,0,0,.55); backdrop-filter: blur(8px);
    border: 1px solid rgba(201,168,76,.3);
    color: #E8C97A; width: 34px; height: 34px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .85rem; text-decoration: none;
    opacity: 0; transition: opacity .2s, background .2s, transform .2s; z-index: 5;
}
.ga-item:hover .ga-dl-btn { opacity: 1; }
.ga-dl-btn:hover { background: rgba(201,168,76,.3); transform: scale(1.1); }

/* ── Toolbar ── */
.galeri-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: .75rem; margin-bottom: 1.25rem;
    padding: .875rem 1rem;
    background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.07);
    border-radius: 1rem;
}
.galeri-info { font-size: .78rem; color: var(--muted, #7A7268); }
.galeri-info strong { color: var(--text, #F0ECE3); }
.galeri-actions { display: flex; gap: .5rem; flex-wrap: wrap; }
.btn-dl {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .575rem 1rem; min-height: 44px;
    background: rgba(201,168,76,.08); border: 1px solid rgba(201,168,76,.25);
    border-radius: .75rem; color: #E8C97A; font-size: .78rem; font-weight: 600;
    cursor: pointer; font-family: inherit; transition: background .2s, transform .15s; white-space: nowrap;
}
.btn-dl:hover { background: rgba(201,168,76,.15); transform: translateY(-1px); }
.btn-dl:disabled { opacity: .45; cursor: not-allowed; transform: none; }
.btn-dl-all { background: rgba(201,168,76,.14); border-color: rgba(201,168,76,.4); }

/* ── Progress ── */
.dl-progress-wrap {
    margin-bottom: 1.25rem; padding: .875rem 1rem;
    background: rgba(201,168,76,.05); border: 1px solid rgba(201,168,76,.15);
    border-radius: 1rem;
}
.dl-progress-bar { height: 6px; background: rgba(255,255,255,.07); border-radius: 99px; overflow: hidden; margin-bottom: .5rem; }
.dl-progress-fill { height: 100%; width: 0%; background: linear-gradient(90deg,#C9A84C,#E8C97A); border-radius: 99px; transition: width .2s ease; }
.dl-progress-txt { font-size: .72rem; color: #E8C97A; text-align: center; }

/* ── Lightbox ── */
.lb-overlay {
    display: none; position: fixed; inset: 0; z-index: 300;
    align-items: center; justify-content: center;
    padding: env(safe-area-inset-top, 1rem) 1rem env(safe-area-inset-bottom, 1rem);
    background: rgba(0,0,0,.88); backdrop-filter: blur(18px);
}
.lb-overlay.open { display: flex; }
.lb-inner {
    position: relative; width: 100%;
    max-width: min(560px, 100%);
    max-height: calc(100dvh - 2rem);
    overflow-y: auto;
    animation: lbIn .3s cubic-bezier(.16,1,.3,1) both;
}
@keyframes lbIn { from{opacity:0;transform:scale(.93) translateY(16px)} to{opacity:1;transform:scale(1) translateY(0)} }

.lb-img-wrap {
    position: relative; border-radius: 1.25rem; overflow: hidden; background: #0a0a0a;
    box-shadow: 0 32px 80px rgba(0,0,0,.6), 0 0 0 1px rgba(201,168,76,.15);
}
.lb-img { width: 100%; display: block; max-height: 60dvh; object-fit: contain; transition: opacity .2s; }
.lb-img.switching { opacity: 0; }

.lb-close {
    position: absolute; top: .75rem; right: .75rem; z-index: 10;
    background: rgba(0,0,0,.55); border: 1px solid rgba(255,255,255,.1);
    color: #fff; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; font-size: .9rem;
    display: flex; align-items: center; justify-content: center;
    transition: background .2s; backdrop-filter: blur(8px);
}
.lb-close:hover { background: rgba(220,38,38,.5); }

.lb-prev, .lb-next {
    position: absolute; top: 50%; transform: translateY(-50%); z-index: 10;
    background: rgba(0,0,0,.5); border: 1px solid rgba(255,255,255,.1);
    color: #fff; width: 44px; height: 44px; border-radius: 50%; cursor: pointer; font-size: 1.2rem;
    display: flex; align-items: center; justify-content: center;
    transition: background .2s, opacity .2s; backdrop-filter: blur(8px);
}
.lb-prev { left: .75rem; }
.lb-next { right: .75rem; }
.lb-prev:hover, .lb-next:hover { background: rgba(201,168,76,.35); border-color: rgba(201,168,76,.3); }
.lb-prev:disabled, .lb-next:disabled { opacity: .2; cursor: default; pointer-events: none; }

.lb-counter {
    position: absolute; bottom: .75rem; left: 50%; transform: translateX(-50%);
    font-size: .65rem; color: rgba(255,255,255,.5); background: rgba(0,0,0,.45);
    backdrop-filter: blur(6px); padding: .2rem .65rem; border-radius: 999px;
    pointer-events: none; letter-spacing: .08em;
}
.lb-info {
    margin-top: .75rem; padding: .875rem 1rem;
    background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.07);
    border-radius: 1rem; display: flex; align-items: flex-start;
    justify-content: space-between; gap: .75rem;
}
.lb-meta { flex: 1; min-width: 0; }
.lb-nama { font-size: .9rem; font-weight: 600; line-height: 1.2; }
.lb-nisn { font-size: .68rem; color: #E8C97A; margin-top: .15rem; }
.lb-caption { font-size: .78rem; color: var(--muted, #7A7268); margin-top: .4rem; line-height: 1.6; }
.lb-time { font-size: .65rem; color: rgba(122,114,104,.6); margin-top: .3rem; }
.lb-actions { display: flex; flex-direction: column; gap: .4rem; flex-shrink: 0; }
.lb-btn {
    display: inline-flex; align-items: center; gap: .35rem;
    padding: .5rem .875rem; min-height: 40px;
    border-radius: .625rem; font-size: .75rem; font-weight: 600;
    cursor: pointer; border: 1px solid; white-space: nowrap; font-family: inherit;
    transition: background .2s, transform .15s;
}
.lb-btn:hover { transform: translateY(-1px); }
.lb-btn-dl { background: rgba(201,168,76,.1); border-color: rgba(201,168,76,.3); color: #E8C97A; }
.lb-btn-dl:hover { background: rgba(201,168,76,.22); }
.lb-btn-del { background: rgba(220,38,38,.08); border-color: rgba(220,38,38,.25); color: #FCA5A5; }
.lb-btn-del:hover { background: rgba(220,38,38,.18); }

/* ── Toast ── */
.dl-toast {
    position: fixed; bottom: calc(2rem + env(safe-area-inset-bottom, 0px));
    left: 50%; transform: translateX(-50%) translateY(12px);
    background: rgba(22,163,74,.15); border: 1px solid rgba(22,163,74,.3);
    color: #86efac; font-size: .78rem; padding: .5rem 1.1rem;
    border-radius: 999px; opacity: 0; pointer-events: none;
    z-index: 400; transition: opacity .25s, transform .25s;
}
.dl-toast.show { opacity: 1; transform: translateX(-50%) translateY(0); }

/* ── Responsive ── */
@media (max-width: 900px) {
    .galeri-admin-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); }
}

@media (max-width: 640px) {
    .galeri-admin-grid { grid-template-columns: repeat(2, 1fr); gap: .75rem; }
    .galeri-toolbar { flex-direction: column; align-items: stretch; }
    .galeri-actions { flex-direction: column; }
    .btn-dl { width: 100%; justify-content: center; }
    .ga-dl-btn { opacity: 1; } /* always visible on touch */
    .lb-prev { left: .25rem; }
    .lb-next { right: .25rem; }
    .lb-actions { flex-direction: row; }
    .lb-btn { flex: 1; justify-content: center; }
    .lb-img { max-height: 50dvh; }
}

@media (max-width: 360px) {
    .galeri-admin-grid { grid-template-columns: 1fr 1fr; gap: .5rem; }
    .ga-body { padding: .5rem .625rem; }
    .ga-nama { font-size: .75rem; }
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script>
// ── Data foto dari DOM ────────────────────────────────────────────────────────
var items = [];
document.querySelectorAll('.ga-item[data-foto]').forEach(function(el) {
    items.push({
        src:     el.dataset.foto,
        nama:    el.dataset.nama,
        nisn:    el.dataset.nisn,
        id:      el.dataset.id,
        caption: el.dataset.caption,
        time:    el.dataset.time,
        hapus:   el.dataset.hapus,
    });
});

var currentIdx = 0;

// ── Lightbox ──────────────────────────────────────────────────────────────────

function bukaLightbox(el) {
    currentIdx = parseInt(el.dataset.idx);
    renderLightbox(currentIdx, false);
    document.getElementById('lbOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function renderLightbox(idx, animate) {
    var item = items[idx];
    var img  = document.getElementById('lbImg');
    if (animate) {
        img.classList.add('switching');
        setTimeout(function(){ img.src = item.src; img.classList.remove('switching'); }, 200);
    } else {
        img.src = item.src;
    }
    document.getElementById('lbNama').textContent    = item.nama;
    document.getElementById('lbNisn').textContent    = 'NISN: ' + item.nisn;
    document.getElementById('lbCaption').textContent = item.caption || '';
    document.getElementById('lbTime').textContent    = item.time;
    document.getElementById('lbCounter').textContent = (idx + 1) + ' / ' + items.length;
    document.getElementById('lbPrev').disabled = (idx === 0);
    document.getElementById('lbNext').disabled = (idx === items.length - 1);
    document.getElementById('lbBtnHapus').dataset.hapusUrl = item.hapus;
    document.getElementById('lbBtnHapus').dataset.hapusIdx = idx;
}

function navigasi(arah) {
    var next = currentIdx + arah;
    if (next < 0 || next >= items.length) return;
    currentIdx = next;
    renderLightbox(currentIdx, true);
}

function tutupLightbox() {
    document.getElementById('lbOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

// Keyboard
document.addEventListener('keydown', function(e) {
    if (!document.getElementById('lbOverlay').classList.contains('open')) return;
    if (e.key === 'Escape')     tutupLightbox();
    if (e.key === 'ArrowLeft')  navigasi(-1);
    if (e.key === 'ArrowRight') navigasi(1);
});

// Touch swipe
var tsX = 0, tsY = 0;
document.getElementById('lbOverlay').addEventListener('touchstart', function(e){ tsX = e.touches[0].clientX; tsY = e.touches[0].clientY; }, {passive:true});
document.getElementById('lbOverlay').addEventListener('touchend', function(e){
    var dx = e.changedTouches[0].clientX - tsX;
    var dy = e.changedTouches[0].clientY - tsY;
    if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 50) navigasi(dx < 0 ? 1 : -1);
}, {passive:true});

// ── Hapus dari lightbox ───────────────────────────────────────────────────────

function hapusDariLightbox() {
    var btn = document.getElementById('lbBtnHapus');
    if (!confirm('Hapus foto ini?')) return;
    var form = document.getElementById('formHapusLb');
    form.action = btn.dataset.hapusUrl;
    form.submit();
}

// ── Download ──────────────────────────────────────────────────────────────────

function downloadSatuLb() {
    var item = items[currentIdx];
    if (item) downloadSatu(item.src, item.nama, item.id);
}

function sanitasiNama(str) {
    return str.replace(/[^\w\s\-]/gi, '').replace(/\s+/g, '_').substring(0, 40);
}

function ekstensiDariUrl(url) {
    var bagian = url.split('?')[0].split('.');
    var ext = bagian[bagian.length - 1].toLowerCase();
    return ['jpg','jpeg','png','webp','gif'].includes(ext) ? ext : 'jpg';
}

function downloadSatu(url, nama, id) {
    fetch(url)
        .then(function(r){ return r.blob(); })
        .then(function(blob){
            var a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = sanitasiNama(nama) + '_' + id + '.' + ekstensiDariUrl(url);
            document.body.appendChild(a); a.click();
            setTimeout(function(){ URL.revokeObjectURL(a.href); a.remove(); }, 1000);
            var t = document.getElementById('dlToast');
            t.classList.add('show');
            setTimeout(function(){ t.classList.remove('show'); }, 2500);
        })
        .catch(function(){ window.open(url, '_blank'); });
}

// ── ZIP helpers ───────────────────────────────────────────────────────────────

function setProgress(pct, txt) {
    var wrap  = document.getElementById('dlProgressWrap');
    var fill  = document.getElementById('dlProgressFill');
    var label = document.getElementById('dlProgressTxt');
    wrap.style.display = 'block';
    fill.style.width = pct + '%';
    label.textContent = txt;
    if (pct >= 100) setTimeout(function(){ wrap.style.display = 'none'; }, 2000);
}

async function fetchBlob(url) {
    var res = await fetch(url);
    if (!res.ok) throw new Error('Gagal fetch: ' + url);
    return await res.blob();
}

function dataHalaman() {
    return [...document.querySelectorAll('.ga-item[data-foto]')].map(function(el){
        return { url: el.dataset.foto, nama: el.dataset.nama, nisn: el.dataset.nisn, id: el.dataset.id };
    });
}

async function buatZip(zipItems, labelPrefix) {
    var zip = new JSZip(), total = zipItems.length, berhasil = 0, gagal = 0;
    for (var i = 0; i < total; i++) {
        var item = zipItems[i];
        setProgress(Math.round((i / total) * 90), 'Mengunduh foto ' + (i+1) + ' dari ' + total + '…');
        try {
            var blob = await fetchBlob(item.url);
            var ext  = ekstensiDariUrl(item.url);
            var nama = sanitasiNama(item.nama || 'foto');
            zip.file(String(i+1).padStart(3,'0') + '_' + nama + '_' + item.id + '.' + ext, blob);
            berhasil++;
        } catch(err) { console.warn('Skip:', item.url, err); gagal++; }
    }
    setProgress(95, 'Membuat file ZIP…');
    var blob = await zip.generateAsync({ type:'blob', compression:'DEFLATE', compressionOptions:{level:3} });
    var u    = URL.createObjectURL(blob);
    var a    = document.createElement('a');
    a.href = u; a.download = labelPrefix + '_' + new Date().toISOString().slice(0,10) + '.zip';
    document.body.appendChild(a); a.click(); document.body.removeChild(a); URL.revokeObjectURL(u);
    setProgress(100, '✅ Selesai! ' + berhasil + ' foto berhasil diunduh' + (gagal ? ', ' + gagal + ' gagal' : '') + '.');
}

async function downloadPage() {
    var di = dataHalaman();
    if (!di.length) { alert('Tidak ada foto di halaman ini.'); return; }
    var btn = document.getElementById('btnDlPage');
    btn.disabled = true; btn.innerHTML = '⏳ Memproses…';
    try { await buatZip(di, 'galeri_halaman'); }
    catch(e) { alert('Terjadi kesalahan: ' + e.message); document.getElementById('dlProgressWrap').style.display='none'; }
    finally {
        btn.disabled = false;
        btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> Download Halaman Ini';
    }
}

async function downloadAll() {
    var btn = document.getElementById('btnDlAll');
    if (!btn) return;
    btn.disabled = true; btn.innerHTML = '⏳ Memuat daftar foto…';
    setProgress(5, 'Mengambil daftar semua foto…');
    try {
        var res = await fetch("{{ route('admin.galeri') }}?all=1&format=json", {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        });
        if (!res.ok) throw new Error('Gagal mengambil daftar foto.');
        var data = await res.json();
        var allItems = data.map(function(d){ return { url: d.foto_url, nama: d.nama, nisn: d.nisn, id: d.id }; });
        if (!allItems.length) { alert('Tidak ada foto untuk diunduh.'); return; }
        await buatZip(allItems, 'galeri_semua');
    } catch(e) {
        alert('Gagal mengunduh semua foto: ' + e.message);
        document.getElementById('dlProgressWrap').style.display = 'none';
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> Download Semua ({{ $momens->total() }} foto)';
    }
}
</script>
@endsection