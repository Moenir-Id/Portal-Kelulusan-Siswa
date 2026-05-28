@php
    $logo        = $setting['sekolah_logo'] ?? '';
    $logoUrl     = '';
    if ($logo) {
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($logo)) {
            $logoUrl = asset('storage/' . $logo);
        } elseif (file_exists(public_path($logo))) {
            $logoUrl = asset($logo);
        }
    }
    $sekolahNama = $setting['sekolah_nama'] ?? 'Sekolah';

    // Countdown
    $cdAktif = ($setting['countdown_aktif'] ?? '0') === '1';
    $cdWaktu = trim($setting['countdown_waktu'] ?? '');
    $cdLabel = $setting['countdown_label'] ?? 'Pengumuman akan dibuka pada:';
    $pesanB  = $setting['pesan_sebelum']   ?? '';
    $cdValid = $cdAktif && $cdWaktu !== '';
    $cdTime  = null;
    $cdBelum = false;
    if ($cdValid) {
        try {
            $cdTime  = \Carbon\Carbon::parse($cdWaktu, 'Asia/Jakarta');
            $cdBelum = now('Asia/Jakarta')->lt($cdTime);
        } catch (\Exception $e) { $cdValid = false; }
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Galeri Momen — {{ $sekolahNama }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Amiri:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--gold:#3d7a52;--gold2:#6db88a;--dark:#04100a;--card:#0a1a0f;--b1:rgba(61,122,82,.28);--b2:rgba(61,122,82,.10);--txt:#e4f2eb;--muted:#3a6b4e}
body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--txt);min-height:100vh}
body::before{content:'';position:fixed;inset:0;pointer-events:none;z-index:0;background:radial-gradient(ellipse 100% 50% at 50% -10%,rgba(61,122,82,.12) 0%,transparent 65%)}

.nav{position:sticky;top:0;z-index:100;display:flex;align-items:center;gap:.75rem;padding:.75rem 1.25rem;background:rgba(4,16,10,.92);backdrop-filter:blur(20px);border-bottom:1px solid var(--b2)}
.nav-logo{width:40px;height:40px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.nav-logo img{width:100%;height:100%;object-fit:contain}
.nav-title{font-size:.875rem;font-weight:600;flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.nav-links{display:flex;gap:.5rem;align-items:center}
.nav-link{font-size:.75rem;color:var(--muted);text-decoration:none;padding:.4rem .75rem;border-radius:.5rem;border:1px solid transparent;transition:color .2s,border-color .2s;white-space:nowrap}
.nav-link:hover,.nav-link.active{color:var(--gold2);border-color:var(--b1)}
.btn-logout{font-size:.72rem;color:#FCA5A5;border:1px solid rgba(220,38,38,.2);background:rgba(220,38,38,.06);padding:.35rem .75rem;border-radius:.5rem;cursor:pointer;font-family:'DM Sans',sans-serif}

.main{position:relative;z-index:1;max-width:900px;margin:0 auto;padding:2rem 1rem 4rem}

.page-header{text-align:center;margin-bottom:2.5rem}
.page-header h1{font-family:'Amiri',serif;font-size:clamp(2rem,7vw,3.2rem);font-weight:700;line-height:1.1;margin-bottom:.5rem}
.page-header h1 em{font-style:normal;background:linear-gradient(135deg,var(--gold),var(--gold2));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.page-header p{font-size:.82rem;color:var(--muted)}

/* Grid masonry-ish */
.galeri-grid{columns:3;column-gap:.875rem}
.galeri-grid-item{break-inside:avoid;margin-bottom:.875rem;border-radius:1rem;overflow:hidden;background:var(--card);position:relative;cursor:pointer}
.galeri-grid-item img{width:100%;display:block;transition:transform .35s}
.galeri-grid-item:hover img{transform:scale(1.04)}

.galeri-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.75) 0%,transparent 55%);opacity:0;transition:opacity .25s;padding:1rem;display:flex;flex-direction:column;justify-content:flex-end}
.galeri-grid-item:hover .galeri-overlay{opacity:1}
.go-nama{font-size:.8rem;font-weight:600;color:#fff;line-height:1.3}
.go-nisn{font-size:.68rem;color:rgba(109,184,138,.85);margin-top:.15rem}
.go-caption{font-size:.7rem;color:rgba(240,236,227,.7);margin-top:.3rem;line-height:1.4;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical}
.go-time{font-size:.62rem;color:rgba(122,114,104,.7);margin-top:.3rem}

/* Tombol download di thumbnail */
.go-dl-btn{
    position:absolute;top:.6rem;right:.6rem;
    background:rgba(0,0,0,.55);backdrop-filter:blur(8px);
    border:1px solid rgba(61,122,82,.35);
    color:var(--gold2);width:30px;height:30px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    font-size:.8rem;cursor:pointer;text-decoration:none;
    opacity:0;transition:opacity .2s,background .2s,transform .2s;
    z-index:5;
}
.galeri-grid-item:hover .go-dl-btn{opacity:1}
.go-dl-btn:hover{background:rgba(201,168,76,.25);transform:scale(1.1)}

/* Lightbox */
.lightbox{
    display:none;position:fixed;inset:0;z-index:300;
    align-items:center;justify-content:center;padding:1rem;
    background:rgba(0,0,0,.88);backdrop-filter:blur(18px);
}
.lightbox.open{display:flex}
.lb-inner{
    position:relative;max-width:580px;width:100%;
    animation:lbIn .35s cubic-bezier(.16,1,.3,1) both;
}
@keyframes lbIn{
    from{opacity:0;transform:scale(.92) translateY(20px)}
    to{opacity:1;transform:scale(1) translateY(0)}
}

.lb-img-wrap{
    position:relative;border-radius:1.25rem;overflow:hidden;
    background:#0a0a0a;
    box-shadow:0 32px 80px rgba(0,0,0,.6),0 0 0 1px rgba(61,122,82,.18);
}
.lb-img{width:100%;display:block;max-height:72vh;object-fit:contain;transition:opacity .22s}
.lb-img.switching{opacity:0}

.lb-close{
    position:absolute;top:.75rem;right:.75rem;z-index:10;
    background:rgba(0,0,0,.55);border:1px solid rgba(255,255,255,.1);
    color:#fff;width:34px;height:34px;border-radius:50%;
    cursor:pointer;font-size:.9rem;
    display:flex;align-items:center;justify-content:center;
    transition:background .2s;backdrop-filter:blur(8px);
}
.lb-close:hover{background:rgba(220,38,38,.5)}

.lb-prev,.lb-next{
    position:absolute;top:50%;transform:translateY(-50%);z-index:10;
    background:rgba(0,0,0,.5);border:1px solid rgba(255,255,255,.1);
    color:#fff;width:40px;height:40px;border-radius:50%;
    cursor:pointer;font-size:1rem;
    display:flex;align-items:center;justify-content:center;
    transition:background .2s,opacity .2s;
    backdrop-filter:blur(8px);
}
.lb-prev{left:.75rem}
.lb-next{right:.75rem}
.lb-prev:hover,.lb-next:hover{background:rgba(61,122,82,.40);border-color:var(--b1)}
.lb-prev:disabled,.lb-next:disabled{opacity:.2;cursor:default;pointer-events:none}

.lb-counter{
    position:absolute;bottom:.75rem;left:50%;transform:translateX(-50%);
    font-size:.65rem;color:rgba(255,255,255,.5);
    background:rgba(0,0,0,.45);backdrop-filter:blur(6px);
    padding:.2rem .65rem;border-radius:999px;letter-spacing:.1em;pointer-events:none;
}

.lb-info{
    margin-top:.875rem;padding:.875rem 1rem;
    background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);
    border-radius:1rem;
    display:flex;align-items:flex-start;gap:.75rem;
}
.lb-avatar{
    width:36px;height:36px;flex-shrink:0;
    display:flex;align-items:center;justify-content:center;
}
.lb-meta{flex:1;min-width:0}
.lb-nama{font-size:.9rem;font-weight:600;line-height:1.2}
.lb-nisn{font-size:.68rem;color:var(--gold2);margin-top:.15rem}
.lb-caption{font-size:.78rem;color:var(--muted);margin-top:.4rem;line-height:1.6}
.lb-time{font-size:.65rem;color:rgba(122,114,104,.6);margin-top:.3rem}

.lb-dl-btn{
    display:inline-flex;align-items:center;gap:.4rem;
    margin-top:.75rem;padding:.45rem 1rem;
    background:rgba(61,122,82,.12);border:1px solid var(--b1);
    border-radius:.625rem;color:var(--gold2);
    font-size:.75rem;font-weight:600;cursor:pointer;text-decoration:none;
    transition:background .2s,transform .15s;white-space:nowrap;
}
.lb-dl-btn:hover{background:rgba(61,122,82,.22);transform:translateY(-1px)}
.lb-dl-btn svg{width:13px;height:13px;flex-shrink:0}

/* Toast */
.dl-toast{
    position:fixed;bottom:5rem;left:50%;transform:translateX(-50%) translateY(12px);
    background:rgba(22,163,74,.18);border:1px solid rgba(22,163,74,.3);
    color:#86efac;font-size:.78rem;padding:.45rem 1.1rem;border-radius:999px;
    opacity:0;pointer-events:none;z-index:400;
    transition:opacity .25s,transform .25s;
}
.dl-toast.show{opacity:1;transform:translateX(-50%) translateY(0)}

/* Empty */
.empty{text-align:center;padding:4rem 1rem;color:var(--muted)}
.empty .ico{font-size:3rem;display:block;margin-bottom:.875rem}

/* Pagination */
.pg-wrap{margin-top:2rem}
.pg-wrap .pagination{display:flex;justify-content:center;gap:.5rem;flex-wrap:wrap}
.pg-wrap .page-link{background:var(--card);border:1px solid var(--b1);color:var(--txt);padding:.4rem .875rem;border-radius:.625rem;font-size:.78rem;text-decoration:none;transition:background .2s}
.pg-wrap .page-link:hover,.pg-wrap .page-item.active .page-link{background:rgba(61,122,82,.18);border-color:var(--gold)}

/* Bottom Nav (mobile only) */
.bottom-nav{
    display:none;position:fixed;bottom:0;left:0;right:0;z-index:200;
    background:rgba(4,16,10,.96);backdrop-filter:blur(24px);
    border-top:1px solid var(--b2);
    padding:.5rem .5rem calc(.5rem + env(safe-area-inset-bottom));
    grid-template-columns:repeat(3,1fr);
}
.bn-item{
    display:flex;flex-direction:column;align-items:center;gap:.25rem;
    text-decoration:none;color:var(--muted);
    padding:.5rem .25rem;border-radius:.75rem;
    transition:color .2s,background .2s;
    font-size:.62rem;font-weight:600;letter-spacing:.04em;
    border:none;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;width:100%;
}
.bn-item.active{color:var(--gold2)}
.bn-item:hover{color:var(--gold2);background:rgba(61,122,82,.07)}
.bn-icon{font-size:1.2rem;line-height:1;display:block}
.bn-item.active .bn-icon-wrap{background:rgba(61,122,82,.14);border-radius:.625rem;padding:.35rem .75rem}
.bn-logout{color:#FCA5A5}
.bn-logout:hover{background:rgba(220,38,38,.08)}

/* ── Countdown ── */
.cd-banner{border-radius:1.25rem;padding:1.75rem 1.5rem;margin-bottom:2rem;background:linear-gradient(135deg,rgba(61,122,82,.09),rgba(61,122,82,.03));border:1px solid var(--b1);text-align:center;position:relative;overflow:hidden}
.cd-banner::after{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--gold),var(--gold2),transparent)}
.cd-banner-lbl{font-size:.68rem;letter-spacing:.14em;text-transform:uppercase;color:var(--muted);margin-bottom:1rem;line-height:1.6}
.cd-banner-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:.5rem;max-width:360px;margin:0 auto 1rem}
.cd-b{background:rgba(0,0,0,.38);border:1px solid var(--b1);border-radius:.875rem;padding:.75rem .25rem}
.cd-b-val{font-family:'Amiri',serif;font-size:clamp(1.6rem,6vw,2.4rem);font-weight:700;color:var(--gold2);line-height:1;display:block}
.cd-b-lbl{font-size:.55rem;color:var(--muted);letter-spacing:.1em;text-transform:uppercase;margin-top:.25rem;display:block}
.cd-banner-note{font-size:.78rem;color:var(--muted);line-height:1.65}

@media(max-width:640px){
    .nav-links{display:none}
    .bottom-nav{display:grid}
    .main{padding-bottom:6rem}
    .galeri-grid{columns:2}
    .lb-prev{left:.25rem}
    .lb-next{right:.25rem}
    .lb-prev,.lb-next{width:34px;height:34px;font-size:.85rem}
    .go-dl-btn{opacity:1} /* selalu visible di mobile */
}
@media(max-width:400px){
    .galeri-grid{columns:1}
}
</style>
</head>
<body>

<nav class="nav">
    <div class="nav-logo">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="{{ $sekolahNama }}">
        @else
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(61,122,82,.7)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M3 9h18M9 21V9"/></svg>
        @endif
    </div>
    <div class="nav-title">{{ $sekolahNama }}</div>
    <div class="nav-links">
        <a class="nav-link" href="{{ route('siswa.dashboard') }}">Dashboard</a>
        <a class="nav-link active" href="{{ route('siswa.galeri') }}">Galeri</a>
        <form method="POST" action="{{ route('siswa.logout') }}" style="margin:0">
            @csrf
            <button class="btn-logout" type="submit">Keluar</button>
        </form>
    </div>
</nav>

{{-- BOTTOM NAV (mobile) --}}
<nav class="bottom-nav">
    <a class="bn-item" href="{{ route('siswa.dashboard') }}">
        <div class="bn-icon-wrap"><span class="bn-icon">🏠</span></div>
        Dashboard
    </a>
    <a class="bn-item active" href="{{ route('siswa.galeri') }}">
        <div class="bn-icon-wrap"><span class="bn-icon">📸</span></div>
        Galeri
    </a>
    <form method="POST" action="{{ route('siswa.logout') }}" style="margin:0;display:contents">
        @csrf
        <button class="bn-item bn-logout" type="submit">
            <div class="bn-icon-wrap"><span class="bn-icon">🚪</span></div>
            Keluar
        </button>
    </form>
</nav>

<div class="main">
    <div class="page-header">
        <div style="display:flex;justify-content:center;margin-bottom:1.25rem">
            <div style="width:64px;height:64px;display:flex;align-items:center;justify-content:center">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $sekolahNama }}" style="width:100%;height:100%;object-fit:contain">
                @else
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="rgba(61,122,82,.5)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M3 9h18M9 21V9"/></svg>
                @endif
            </div>
        </div>
        <h1>Galeri <em>Momen Bahagia</em></h1>
        <p>Rayakan kelulusan bersama — {{ $momens->total() }} foto telah dibagikan</p>
    </div>

    {{-- ── COUNTDOWN BANNER ── --}}
    @if($cdValid && $cdBelum)
    <div class="cd-banner">
        <div class="cd-banner-lbl">{{ $cdLabel }}</div>
        <div class="cd-banner-grid">
            <div class="cd-b"><span class="cd-b-val" id="cdH">--</span><span class="cd-b-lbl">Hari</span></div>
            <div class="cd-b"><span class="cd-b-val" id="cdJ">--</span><span class="cd-b-lbl">Jam</span></div>
            <div class="cd-b"><span class="cd-b-val" id="cdM">--</span><span class="cd-b-lbl">Menit</span></div>
            <div class="cd-b"><span class="cd-b-val" id="cdD">--</span><span class="cd-b-lbl">Detik</span></div>
        </div>
        @if($pesanB)<div class="cd-banner-note">{{ $pesanB }}</div>@endif
    </div>
    @endif

    @if($momens->count())
        <div class="galeri-grid" id="galeriGrid">
            @foreach($momens as $idx => $m)
            @php
                $sa       = $m->siswaAccount;
                $sv       = $sa?->siswa;
                $nama     = $sv?->nama ?? 'Siswa';
                $nisn     = $sv?->nisn ?? '-';
                $fotoUrl  = asset($m->foto);
                $namaFile = 'galeri-' . ($sv?->nisn ?? 'foto') . '-' . $m->id . '.jpg';
            @endphp
            <div class="galeri-grid-item"
                 data-idx="{{ $idx }}"
                 data-src="{{ $fotoUrl }}"
                 data-nama="{{ $nama }}"
                 data-nisn="{{ $nisn }}"
                 data-caption="{{ $m->caption ?? '' }}"
                 data-time="{{ $m->created_at->diffForHumans() }}"
                 data-filename="{{ $namaFile }}"
                 onclick="bukaLightbox(this)">
                <img src="{{ $fotoUrl }}" alt="{{ $nama }}" loading="lazy">
                {{-- Tombol download di thumbnail --}}
                <a class="go-dl-btn"
                   href="#"
                   title="Unduh foto"
                   onclick="event.stopPropagation();downloadFoto('{{ $fotoUrl }}','{{ $namaFile }}');return false;">
                    ⬇
                </a>
                <div class="galeri-overlay">
                    <div class="go-nama">{{ $nama }}</div>
                    <div class="go-nisn">NISN: {{ $nisn }}</div>
                    @if($m->caption)
                        <div class="go-caption">{{ $m->caption }}</div>
                    @endif
                    <div class="go-time">{{ $m->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pg-wrap">{{ $momens->links() }}</div>
    @else
        <div class="empty">
            <span class="ico">📷</span>
            <strong>Belum ada momen yang dibagikan.</strong><br>
            <span style="font-size:.8rem">Jadilah yang pertama berbagi kebahagiaanmu!</span><br>
            <a href="{{ route('siswa.dashboard') }}" style="display:inline-flex;align-items:center;gap:.5rem;margin-top:1.25rem;padding:.6rem 1.25rem;background:rgba(61,122,82,.09);border:1px solid rgba(61,122,82,.22);border-radius:.75rem;color:var(--gold2);font-size:.8rem;font-weight:600;text-decoration:none;transition:background .2s" onmouseover="this.style.background='rgba(61,122,82,.18)'" onmouseout="this.style.background='rgba(201,168,76,.08)'">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                Kembali ke Dashboard
            </a>
        </div>
    @endif
</div>

{{-- Lightbox --}}
<div class="lightbox" id="lightbox" onclick="if(event.target===this)tutupLightbox()">
    <div class="lb-inner">
        <div class="lb-img-wrap">
            <img class="lb-img" id="lbImg" src="" alt="">
            <button class="lb-close" onclick="tutupLightbox()">✕</button>
            <button class="lb-prev" id="lbPrev" onclick="navigasi(-1)">‹</button>
            <button class="lb-next" id="lbNext" onclick="navigasi(1)">›</button>
            <div class="lb-counter" id="lbCounter">1 / 1</div>
        </div>
        <div class="lb-info">
            <div class="lb-avatar" id="lbAvatar">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="" style="width:100%;height:100%;object-fit:contain">
                @else
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="rgba(61,122,82,.6)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                @endif
            </div>
            <div class="lb-meta">
                <div class="lb-nama" id="lbNama"></div>
                <div class="lb-nisn" id="lbNisn"></div>
                <div class="lb-caption" id="lbCaption"></div>
                <div class="lb-time" id="lbTime"></div>
                <a class="lb-dl-btn" href="#" onclick="event.preventDefault();downloadFoto(currentSrc(),currentFilename())">
                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 2v8M5 7l3 3 3-3"/><path d="M2 12h12"/>
                    </svg>
                    Unduh Foto
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Toast --}}
<div class="dl-toast" id="dlToast">✅ Foto berhasil diunduh</div>

<script>
var items = [];
document.querySelectorAll('.galeri-grid-item').forEach(function(el) {
    items.push({
        src:      el.dataset.src,
        nama:     el.dataset.nama,
        nisn:     el.dataset.nisn,
        caption:  el.dataset.caption,
        time:     el.dataset.time,
        filename: el.dataset.filename,
    });
});

var currentIdx = 0;
function currentSrc()      { return items[currentIdx] ? items[currentIdx].src : ''; }
function currentFilename() { return items[currentIdx] ? items[currentIdx].filename : 'foto.jpg'; }

function downloadFoto(url, filename) {
    fetch(url)
        .then(function(r){ return r.blob(); })
        .then(function(blob){
            var a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = filename || 'foto.jpg';
            document.body.appendChild(a);
            a.click();
            setTimeout(function(){ URL.revokeObjectURL(a.href); a.remove(); }, 1000);
            var t = document.getElementById('dlToast');
            t.classList.add('show');
            setTimeout(function(){ t.classList.remove('show'); }, 2500);
        })
        .catch(function(){ window.open(url, '_blank'); });
}

function bukaLightbox(el) {
    currentIdx = parseInt(el.dataset.idx);
    renderLightbox(currentIdx, false);
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function renderLightbox(idx, animate) {
    var item = items[idx];
    var img  = document.getElementById('lbImg');
    if (animate) {
        img.classList.add('switching');
        setTimeout(function(){ img.src = item.src; img.classList.remove('switching'); }, 220);
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
}

function navigasi(arah) {
    var next = currentIdx + arah;
    if (next < 0 || next >= items.length) return;
    currentIdx = next;
    renderLightbox(currentIdx, true);
}

function tutupLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (!document.getElementById('lightbox').classList.contains('open')) return;
    if (e.key === 'Escape')     tutupLightbox();
    if (e.key === 'ArrowLeft')  navigasi(-1);
    if (e.key === 'ArrowRight') navigasi(1);
});

var tsX = 0, tsY = 0;
document.getElementById('lightbox').addEventListener('touchstart', function(e){ tsX = e.touches[0].clientX; tsY = e.touches[0].clientY; }, {passive:true});
document.getElementById('lightbox').addEventListener('touchend', function(e){
    var dx = e.changedTouches[0].clientX - tsX;
    var dy = e.changedTouches[0].clientY - tsY;
    if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 50) navigasi(dx < 0 ? 1 : -1);
}, {passive:true});
</script>
@if($cdValid && $cdBelum)
<script>
(function(){
    var t = {{ $cdTime->timestamp * 1000 }};
    function pad(n){ return String(n).padStart(2,'0'); }
    function tick(){
        var d = t - Date.now();
        if(d <= 0){ window.location.reload(); return; }
        document.getElementById('cdH').textContent = pad(Math.floor(d/86400000));
        document.getElementById('cdJ').textContent = pad(Math.floor((d%86400000)/3600000));
        document.getElementById('cdM').textContent = pad(Math.floor((d%3600000)/60000));
        document.getElementById('cdD').textContent = pad(Math.floor((d%60000)/1000));
    }
    tick(); setInterval(tick, 1000);
})();
</script>
@endif

</body>
</html>