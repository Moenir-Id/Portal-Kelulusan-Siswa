@php
    use App\Models\Setting;
    use Illuminate\Support\Facades\Storage;
    $sekolahNama = Setting::get('sekolah_nama', 'Sekolah');
    $sekolahLogo = Setting::get('sekolah_logo', '');
    $logoUrl = '';
    if ($sekolahLogo && Storage::disk('public')->exists($sekolahLogo)) {
        $logoUrl = asset('storage/' . $sekolahLogo);
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" content="#080808">
<title>Masuk — {{ $sekolahNama }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;0,900;1,400;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
    --gold:#C9A84C;--gold2:#E8C97A;--gold3:#F5E4A8;
    --dark:#060606;--card:#0F0F0F;--card2:#161616;
    --b1:rgba(201,168,76,.18);--b2:rgba(201,168,76,.07);--b3:rgba(201,168,76,.04);
    --txt:#F0ECE3;--muted:#7A7268;--muted2:#3A3632;
}

html,body{height:100%;overflow:hidden}
body{
    font-family:'DM Sans',sans-serif;
    background:var(--dark);color:var(--txt);
    display:flex;align-items:stretch;
}

/* ══ NOISE TEXTURE overlay ══ */
body::after{
    content:'';position:fixed;inset:0;z-index:0;pointer-events:none;
    background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
    opacity:.4;
}

/* ══ LEFT — visual panel ══ */
.left{
    flex:1;position:relative;
    display:flex;flex-direction:column;
    justify-content:space-between;
    padding:3rem;overflow:hidden;
    background:var(--dark);
    z-index:1;
}

/* diagonal gold stripe */
.left::before{
    content:'';position:absolute;
    top:-40%;right:-20%;
    width:70%;height:200%;
    background:linear-gradient(135deg,transparent 40%,rgba(201,168,76,.035) 40%,rgba(201,168,76,.035) 60%,transparent 60%);
    transform:rotate(-15deg);
    pointer-events:none;
}

/* radial glow */
.left-glow{
    position:absolute;top:-10%;left:-10%;
    width:70%;height:70%;
    background:radial-gradient(ellipse,rgba(201,168,76,.1) 0%,transparent 65%);
    pointer-events:none;
    animation:glowPulse 6s ease-in-out infinite;
}
@keyframes glowPulse{
    0%,100%{opacity:.7;transform:scale(1)}
    50%{opacity:1;transform:scale(1.1)}
}

/* grid lines */
.left-grid{
    position:absolute;inset:0;pointer-events:none;
    background-image:
        linear-gradient(rgba(201,168,76,.03) 1px,transparent 1px),
        linear-gradient(90deg,rgba(201,168,76,.03) 1px,transparent 1px);
    background-size:52px 52px;
    animation:gridDrift 25s linear infinite;
}
@keyframes gridDrift{from{background-position:0 0}to{background-position:52px 52px}}

.left-top{position:relative;z-index:2}
.left-bottom{position:relative;z-index:2}

/* Logo desktop (kiri) */
.logo-seal{
    width:72px;height:72px;
    display:flex;align-items:center;justify-content:center;
    font-size:2rem;overflow:hidden;
    margin-bottom:2.5rem;
}
.logo-seal img{width:100%;height:100%;object-fit:contain}

/* Logo mobile (kanan, tersembunyi di desktop) */
.logo-mobile{
    display:none;
}

.left-label{
    font-size:.6rem;letter-spacing:.22em;text-transform:uppercase;
    color:var(--gold);margin-bottom:.875rem;
    display:flex;align-items:center;gap:.625rem;
}
.left-label::before{content:'';display:block;width:20px;height:1px;background:var(--gold);opacity:.6}

.left-title{
    font-family:'Cormorant Garamond',serif;
    font-size:clamp(3rem,5.5vw,4.5rem);
    font-weight:900;line-height:.95;
    margin-bottom:1.5rem;letter-spacing:-.01em;
}
.left-title em{
    font-style:italic;font-weight:400;
    display:block;
    background:linear-gradient(135deg,var(--gold),var(--gold2),var(--gold3));
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}

.left-divider{
    width:56px;height:1px;
    background:linear-gradient(90deg,var(--gold),transparent);
    margin-bottom:1.5rem;
}

.left-desc{
    font-size:.875rem;color:var(--muted);
    line-height:1.8;max-width:320px;
}

/* bottom pills */
.left-pills{display:flex;gap:.5rem;flex-wrap:wrap;margin-top:2.5rem}
.pill-tag{
    font-size:.65rem;letter-spacing:.08em;
    padding:.3rem .875rem;border-radius:999px;
    border:1px solid var(--b1);color:var(--muted);
    background:var(--b3);
}
.pill-tag strong{color:var(--gold2);font-weight:600}

/* bottom copyright */
.left-copy{
    font-size:.65rem;color:var(--muted2);
    margin-top:auto;padding-top:2rem;
}

/* ══ RIGHT — form panel ══ */
.right{
    width:480px;flex-shrink:0;
    background:var(--card);
    border-left:1px solid var(--b1);
    display:flex;flex-direction:column;justify-content:center;
    padding:3.5rem 3rem;
    position:relative;z-index:1;
    animation:panelIn .8s cubic-bezier(.16,1,.3,1) both;
}
@keyframes panelIn{from{opacity:0;transform:translateX(40px)}to{opacity:1;transform:translateX(0)}}

/* corner accents */
.right::before,.right::after{
    content:'';position:absolute;
    width:56px;height:56px;
    border-color:var(--b1);border-style:solid;
}
.right::before{top:1.75rem;right:1.75rem;border-width:1px 1px 0 0}
.right::after{bottom:1.75rem;left:1.75rem;border-width:0 0 1px 1px}

/* right top line accent */
.right-accent{
    position:absolute;top:0;left:0;right:0;height:2px;
    background:linear-gradient(90deg,transparent,var(--gold),transparent);
    opacity:.5;
}

.form-tag{
    font-size:.62rem;letter-spacing:.2em;text-transform:uppercase;
    color:var(--gold);margin-bottom:1.75rem;
    display:flex;align-items:center;gap:.625rem;
}
.form-tag::before{content:'';display:block;width:20px;height:1px;background:var(--gold)}

.form-heading{
    font-family:'Cormorant Garamond',serif;
    font-size:2.6rem;font-weight:700;line-height:1.05;
    margin-bottom:.5rem;
}
.form-heading em{font-style:italic;color:var(--gold2);font-weight:400}

.form-sub{
    font-size:.8rem;color:var(--muted);
    line-height:1.65;margin-bottom:2.25rem;
}

/* ── Field ── */
.field{margin-bottom:1.375rem}
.field-lbl{
    font-size:.65rem;letter-spacing:.13em;text-transform:uppercase;
    color:var(--muted);margin-bottom:.5rem;
    display:flex;align-items:center;gap:.4rem;
}
.field-lbl .ic{font-size:.8rem}

.input-shell{position:relative}
.input-shell input{
    width:100%;
    background:rgba(255,255,255,.025);
    border:1px solid rgba(255,255,255,.07);
    border-radius:.75rem;
    padding:.925rem 1.125rem;
    color:var(--txt);
    font-family:'DM Sans',sans-serif;font-size:.925rem;
    outline:none;
    transition:border-color .3s,background .3s,box-shadow .3s;
    -webkit-appearance:none;
}
.input-shell input:focus{
    border-color:var(--gold);
    background:rgba(201,168,76,.03);
    box-shadow:0 0 0 3px rgba(201,168,76,.08),inset 0 1px 0 rgba(201,168,76,.06);
}
.input-shell input::placeholder{color:rgba(122,114,104,.4);font-size:.875rem}
.input-shell input[type=password],
.input-shell input[type=text]{padding-right:3.25rem}

/* focus underline sweep */
.input-line{
    position:absolute;bottom:0;left:50%;
    width:0;height:2px;
    background:linear-gradient(90deg,var(--gold),var(--gold2));
    border-radius:0 0 .75rem .75rem;
    transition:width .35s ease,left .35s ease;
    pointer-events:none;
}
.input-shell input:focus~.input-line{width:100%;left:0}

.pw-eye{
    position:absolute;right:1rem;top:50%;transform:translateY(-50%);
    background:none;border:none;cursor:pointer;
    font-size:.875rem;opacity:.35;transition:opacity .2s;
    color:var(--txt);padding:.2rem;line-height:1;
}
.pw-eye:hover{opacity:.85}

/* hint badges */
.hint-row{
    display:flex;gap:.4rem;margin-top:.625rem;flex-wrap:wrap;
}
.hint-badge{
    font-size:.65rem;padding:.2rem .6rem;border-radius:999px;
    background:var(--b3);border:1px solid var(--b2);color:var(--muted);
    display:flex;align-items:center;gap:.3rem;
}

/* ── Submit ── */
.btn-login{
    width:100%;position:relative;overflow:hidden;
    background:linear-gradient(135deg,var(--gold) 0%,var(--gold2) 50%,var(--gold) 100%);
    background-size:200% auto;
    border:none;border-radius:.875rem;
    padding:1.05rem;
    color:#050505;
    font-family:'DM Sans',sans-serif;font-weight:700;font-size:.975rem;
    letter-spacing:.05em;cursor:pointer;
    margin-top:.5rem;
    transition:background-position .45s,transform .2s,box-shadow .2s;
}
.btn-login:hover{
    background-position:right center;
    transform:translateY(-2px);
    box-shadow:0 14px 36px rgba(201,168,76,.32);
}
.btn-login:active{transform:translateY(0)}
/* shine sweep */
.btn-login::after{
    content:'';position:absolute;
    top:0;left:-100%;width:60%;height:100%;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.28),transparent);
    transition:left .55s ease;pointer-events:none;
}
.btn-login:hover::after{left:150%}

/* ── Error alert ── */
.err-box{
    background:rgba(220,38,38,.07);
    border:1px solid rgba(220,38,38,.18);
    border-left:3px solid #dc2626;
    border-radius:.75rem;
    padding:.825rem 1rem;
    color:#FCA5A5;font-size:.8rem;
    margin-bottom:1.5rem;
    display:flex;align-items:center;gap:.625rem;
    animation:shake .4s ease;
}
@keyframes shake{
    0%,100%{transform:translateX(0)}
    20%,60%{transform:translateX(-5px)}
    40%,80%{transform:translateX(5px)}
}

/* ── Back link ── */
.back-wrap{
    display:flex;align-items:center;justify-content:center;gap:.5rem;
    margin-top:2rem;font-size:.775rem;color:var(--muted);
    text-decoration:none;transition:color .2s;
}
.back-wrap:hover{color:var(--gold2)}
.back-wrap svg{transition:transform .2s}
.back-wrap:hover svg{transform:translateX(-3px)}

/* ── Responsive ── */
@media(max-width:820px){
    html,body{overflow:auto;height:auto}
    .left{display:none}
    .right{
        width:100%;border-left:none;
        padding:2.5rem 1.75rem;
        min-height:100vh;
        justify-content:center;
    }
    .right::before,.right::after{display:none}

    /* Logo muncul di mobile */
    .logo-mobile{
        display:flex;
        justify-content:center;
        align-items:center;
        margin-bottom:1.75rem;
    }
    .logo-mobile img{
        width:72px;
        height:72px;
        object-fit:contain;
    }
    .logo-mobile .logo-emoji{
        font-size:3rem;
        line-height:1;
    }
}
@media(max-width:400px){
    .right{padding:2rem 1.25rem}
    .form-heading{font-size:2.2rem}
}
</style>
</head>
<body>

{{-- ══ LEFT PANEL ══ --}}
<div class="left">
    <div class="left-glow"></div>
    <div class="left-grid"></div>

    <div class="left-top">
        <div class="logo-seal">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $sekolahNama }}">
            @else
                🎓
            @endif
        </div>

        <div class="left-label">Sistem Informasi</div>

        <div class="left-title">
            Pengumuman<br>
            <em>Kelulusan</em>
        </div>

        <div class="left-divider"></div>

        <p class="left-desc">
            {{ $sekolahNama }} — platform resmi pengumuman kelulusan siswa
            yang terpusat, aman, dan mudah diakses.
        </p>

        <div class="left-pills">
            <div class="pill-tag"><strong>Admin</strong> — gunakan Email</div>
            <div class="pill-tag"><strong>Siswa</strong> — gunakan NISN</div>
        </div>
    </div>

    <div class="left-bottom">
        <div class="left-copy">
            &copy; {{ date('Y') }} {{ $sekolahNama }}. Hak cipta dilindungi.
        </div>
    </div>
</div>

{{-- ══ RIGHT PANEL ══ --}}
<div class="right">
    <div class="right-accent"></div>

    {{-- Logo mobile — hanya tampil di layar kecil / PWA --}}
    <div class="logo-mobile">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="{{ $sekolahNama }}">
        @else
            <span class="logo-emoji">🎓</span>
        @endif
    </div>

    <div class="form-tag">Masuk ke Sistem</div>

    <div class="form-heading">
        Halo,<br><em>Selamat Datang</em>
    </div>
    <p class="form-sub">
        Admin masuk dengan <strong style="color:var(--gold2)">Email</strong>,
        siswa masuk dengan <strong style="color:var(--gold2)">NISN</strong>.
        Sistem akan mendeteksi otomatis.
    </p>

    @if($errors->any())
        <div class="err-box">
            <span>⚠</span>
            <span>{{ $errors->first('identifier') ?? $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email / NISN --}}
        <div class="field">
            <div class="field-lbl">
                <span class="ic">👤</span> Email atau NISN
            </div>
            <div class="input-shell">
                <input
                    type="text"
                    name="identifier"
                    id="inputIdentifier"
                    value="{{ old('identifier') }}"
                    placeholder="Masukkan email atau NISN"
                    autocomplete="username"
                    required
                    autofocus
                >
                <div class="input-line"></div>
            </div>
            <div class="hint-row">
                <span class="hint-badge">📧 Email → Admin</span>
                <span class="hint-badge">🔢 NISN → Siswa</span>
            </div>
        </div>

        {{-- Password --}}
        <div class="field">
            <div class="field-lbl">
                <span class="ic">🔒</span> Password
            </div>
            <div class="input-shell">
                <input
                    type="password"
                    name="password"
                    id="inputPw"
                    placeholder="Masukkan Password"
                    autocomplete="current-password"
                    required
                >
                <button type="button" class="pw-eye" onclick="togglePw()" tabindex="-1" title="Tampilkan password">
                    <span id="eyeIcon">👁</span>
                </button>
                <div class="input-line"></div>
            </div>
        </div>

        <button class="btn-login" type="submit" id="btnLogin">
            <span id="btnText">Masuk →</span>
            <span id="btnLoad" style="display:none">⏳ Memproses…</span>
        </button>
    </form>

    <a class="back-wrap" href="{{ route('home') }}">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
            <path d="M9 2L4 7L9 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        Kembali ke halaman pengumuman
    </a>
</div>

<script>
// Toggle show/hide password
function togglePw() {
    var inp  = document.getElementById('inputPw');
    var icon = document.getElementById('eyeIcon');
    var show = inp.type === 'password';
    inp.type  = show ? 'text' : 'password';
    icon.textContent = show ? '🙈' : '👁';
}

// Loading state saat submit
document.querySelector('form').addEventListener('submit', function() {
    var btn  = document.getElementById('btnLogin');
    var txt  = document.getElementById('btnText');
    var load = document.getElementById('btnLoad');
    txt.style.display  = 'none';
    load.style.display = 'inline';
    btn.disabled = true;
    btn.style.opacity = '.8';
});

// Auto-hint: deteksi live apakah input email atau NISN
document.getElementById('inputIdentifier').addEventListener('input', function() {
    var val    = this.value.trim();
    var badges = document.querySelectorAll('.hint-badge');
    if (!val) {
        badges[0].style.opacity = '1';
        badges[1].style.opacity = '1';
        return;
    }
    var isEmail = val.includes('@');
    var isNisn  = /^\d+$/.test(val);
    badges[0].style.opacity = isEmail ? '1' : (isNisn ? '.25' : '.6');
    badges[1].style.opacity = isNisn  ? '1' : (isEmail ? '.25' : '.6');
    // Ganti placeholder password sesuai konteks
    var pwInput = document.getElementById('inputPw');
    if (isEmail) {
        pwInput.placeholder = 'Password admin';
    } else if (isNisn) {
        pwInput.placeholder = 'Password dari sekolah';
    } else {
        pwInput.placeholder = '••••••••';
    }
});
</script>

</body>
</html>