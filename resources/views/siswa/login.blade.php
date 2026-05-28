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
<title>Login Siswa — {{ $sekolahNama }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
    --gold:#C9A84C;--gold2:#E8C97A;--gold3:#F5E4A8;
    --dark:#080808;--card:#111111;--card2:#181818;
    --b1:rgba(201,168,76,.2);--b2:rgba(201,168,76,.08);
    --txt:#F0ECE3;--muted:#7A7268;--muted2:#4A4640;
}
html,body{height:100%}
body{
    font-family:'DM Sans',sans-serif;
    background:var(--dark);
    color:var(--txt);
    min-height:100vh;
    display:flex;
    align-items:stretch;
    overflow:hidden;
}

/* ── Background grid ── */
body::before{
    content:'';position:fixed;inset:0;z-index:0;pointer-events:none;
    background-image:linear-gradient(rgba(201,168,76,.025) 1px,transparent 1px),
                     linear-gradient(90deg,rgba(201,168,76,.025) 1px,transparent 1px);
    background-size:60px 60px;
}

/* ── Left panel — dekoratif ── */
.left{
    flex:1;position:relative;
    display:flex;flex-direction:column;justify-content:flex-end;
    padding:3rem;overflow:hidden;
}
.left::after{
    content:'';position:absolute;inset:0;
    background:radial-gradient(ellipse at 40% 35%,rgba(201,168,76,.13) 0%,transparent 60%);
    pointer-events:none;
}
.orb{
    position:absolute;border-radius:50%;
    filter:blur(80px);animation:orbFloat 8s ease-in-out infinite;
    pointer-events:none;
}
.orb1{width:400px;height:400px;top:-100px;left:-100px;background:rgba(201,168,76,.07);animation-delay:0s}
.orb2{width:300px;height:300px;bottom:60px;right:-60px;background:rgba(232,201,122,.05);animation-delay:-4s}
@keyframes orbFloat{
    0%,100%{transform:translate(0,0) scale(1)}
    50%{transform:translate(20px,-30px) scale(1.05)}
}
.left-content{position:relative;z-index:2}
.left-seal{
    width:80px;height:80px;border-radius:50%;
    border:2px solid var(--b1);
    display:flex;align-items:center;justify-content:center;
    font-size:2.2rem;margin-bottom:2rem;
    background:rgba(201,168,76,.06);
    box-shadow:0 0 40px rgba(201,168,76,.15),inset 0 0 20px rgba(201,168,76,.05);
    animation:sealPulse 3s ease-in-out infinite;
    overflow:hidden;
}
.left-seal img{width:100%;height:100%;object-fit:cover}
@keyframes sealPulse{
    0%,100%{box-shadow:0 0 40px rgba(201,168,76,.15),inset 0 0 20px rgba(201,168,76,.05)}
    50%{box-shadow:0 0 60px rgba(201,168,76,.25),inset 0 0 30px rgba(201,168,76,.08)}
}
.left-title{
    font-family:'Cormorant Garamond',serif;
    font-size:clamp(2.2rem,5vw,3.5rem);font-weight:900;line-height:1.1;
    margin-bottom:.875rem;
    background:linear-gradient(135deg,var(--txt) 0%,var(--gold2) 50%,var(--txt) 100%);
    background-size:200% auto;
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
    animation:shimmer 4s linear infinite;
}
@keyframes shimmer{from{background-position:0% center}to{background-position:200% center}}
.left-divider{width:48px;height:2px;background:linear-gradient(90deg,var(--gold),transparent);margin:1rem 0 1.25rem}
.left-sub{font-size:.875rem;color:var(--muted);line-height:1.75;max-width:340px}
.left-info{
    display:flex;flex-direction:column;gap:.5rem;
    margin-top:2rem;padding:1rem 1.25rem;
    background:rgba(201,168,76,.05);border:1px solid var(--b2);
    border-radius:.875rem;max-width:340px;
}
.left-info-item{display:flex;align-items:center;gap:.625rem;font-size:.78rem;color:var(--muted)}
.left-info-item span:first-child{font-size:.95rem}

/* ── Right panel — form ── */
.right{
    width:460px;flex-shrink:0;
    background:var(--card);border-left:1px solid var(--b2);
    display:flex;flex-direction:column;justify-content:center;
    padding:3rem 2.5rem;position:relative;
    animation:slideIn .7s cubic-bezier(.16,1,.3,1) both;
    z-index:1;
}
@keyframes slideIn{from{opacity:0;transform:translateX(30px)}to{opacity:1;transform:translateX(0)}}
.right::before,.right::after{
    content:'';position:absolute;width:60px;height:60px;
    border-color:var(--b1);border-style:solid;
}
.right::before{top:1.5rem;right:1.5rem;border-width:1px 1px 0 0}
.right::after{bottom:1.5rem;left:1.5rem;border-width:0 0 1px 1px}

.form-eyebrow{
    font-size:.68rem;letter-spacing:.18em;text-transform:uppercase;
    color:var(--gold);margin-bottom:.75rem;
    display:flex;align-items:center;gap:.6rem;
}
.form-eyebrow::before{content:'';display:block;width:24px;height:1px;background:var(--gold)}
.form-title{
    font-family:'Cormorant Garamond',serif;
    font-size:2.4rem;font-weight:700;line-height:1.1;margin-bottom:.4rem;
}
.form-title em{font-style:normal;color:var(--gold2)}
.form-desc{font-size:.82rem;color:var(--muted);margin-bottom:2rem;line-height:1.6}

/* Fields */
.field{margin-bottom:1.25rem}
.field-label{
    display:flex;align-items:center;gap:.5rem;
    font-size:.68rem;letter-spacing:.12em;text-transform:uppercase;
    color:var(--muted);margin-bottom:.5rem;
}
.field-label .ico{font-size:.85rem}
.input-wrap{position:relative}
.input-wrap input{
    width:100%;
    background:rgba(255,255,255,.03);
    border:1px solid rgba(255,255,255,.08);
    border-radius:.75rem;
    padding:.875rem 1rem;
    color:var(--txt);
    font-family:'DM Sans',sans-serif;font-size:.9rem;
    outline:none;
    transition:border-color .25s,background .25s,box-shadow .25s;
    -webkit-appearance:none;
}
.input-wrap input:focus{
    border-color:var(--gold);
    background:rgba(201,168,76,.04);
    box-shadow:0 0 0 3px rgba(201,168,76,.1);
}
.input-wrap input::placeholder{color:rgba(122,114,104,.5)}
/* password toggle */
.input-wrap input[type=password],
.input-wrap input[type=text]{padding-right:3rem}
.pw-toggle{
    position:absolute;right:.875rem;top:50%;transform:translateY(-50%);
    background:none;border:none;cursor:pointer;
    font-size:.9rem;opacity:.4;transition:opacity .2s;padding:0;line-height:1;
    color:var(--txt);
}
.pw-toggle:hover{opacity:.9}

/* Submit */
.btn-submit{
    width:100%;position:relative;
    background:linear-gradient(135deg,var(--gold) 0%,var(--gold2) 50%,var(--gold) 100%);
    background-size:200% auto;
    border:none;border-radius:.875rem;
    padding:1rem;
    color:#0A0A0A;
    font-family:'DM Sans',sans-serif;font-weight:700;font-size:.95rem;
    letter-spacing:.04em;cursor:pointer;
    margin-top:.25rem;overflow:hidden;
    transition:background-position .4s,transform .2s,box-shadow .2s;
}
.btn-submit:hover{
    background-position:right center;
    transform:translateY(-2px);
    box-shadow:0 12px 32px rgba(201,168,76,.35);
}
.btn-submit:active{transform:translateY(0)}
.btn-submit::after{
    content:'';position:absolute;top:0;left:-100%;
    width:60%;height:100%;
    background:linear-gradient(90deg,transparent,rgba(255,255,255,.25),transparent);
    transition:left .5s ease;pointer-events:none;
}
.btn-submit:hover::after{left:150%}

/* Error alert */
.alert-err{
    background:rgba(220,38,38,.08);
    border:1px solid rgba(220,38,38,.2);
    border-left:3px solid #dc2626;
    border-radius:.75rem;
    padding:.8rem 1rem;
    color:#FCA5A5;font-size:.825rem;
    margin-bottom:1.5rem;
    display:flex;align-items:center;gap:.6rem;
    animation:shake .4s ease;
}
@keyframes shake{
    0%,100%{transform:translateX(0)}
    20%,60%{transform:translateX(-6px)}
    40%,80%{transform:translateX(6px)}
}

/* Info box */
.info-box{
    display:flex;align-items:flex-start;gap:.625rem;
    background:rgba(201,168,76,.05);border:1px solid var(--b2);
    border-radius:.75rem;padding:.75rem 1rem;
    font-size:.75rem;color:var(--muted);line-height:1.6;
    margin-top:1.5rem;
}
.info-box .ico{font-size:.9rem;flex-shrink:0;margin-top:.05rem}

/* Back link */
.back-link{
    display:flex;align-items:center;justify-content:center;gap:.5rem;
    margin-top:1.5rem;font-size:.78rem;color:var(--muted);
    text-decoration:none;transition:color .2s;
}
.back-link:hover{color:var(--gold2)}
.back-link svg{transition:transform .2s}
.back-link:hover svg{transform:translateX(-3px)}

/* ── Responsive ── */
@media(max-width:768px){
    body{overflow:auto}
    .left{display:none}
    .right{width:100%;border-left:none;padding:2rem 1.5rem;justify-content:flex-start;padding-top:3rem}
    .right::before,.right::after{display:none}
}
@media(max-width:380px){
    .right{padding:1.75rem 1.25rem}
}
</style>
</head>
<body>

{{-- LEFT PANEL --}}
<div class="left">
    <div class="orb orb1"></div>
    <div class="orb orb2"></div>
    <div class="left-content">
        <div class="left-seal">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="{{ $sekolahNama }}">
            @else
                🎓
            @endif
        </div>
        <div class="left-title">Portal<br>Siswa</div>
        <div class="left-divider"></div>
        <p class="left-sub">
            Masuk untuk melihat hasil kelulusan dan berbagi momen bahagia bersama teman-temanmu.
        </p>
        <div class="left-info">
            <div class="left-info-item">
                <span>🔑</span>
                <span>Gunakan NISN sebagai username</span>
            </div>
            <div class="left-info-item">
                <span>🔐</span>
                <span>Password diberikan oleh admin sekolah</span>
            </div>
            <div class="left-info-item">
                <span>📸</span>
                <span>Bagikan momen kelulusan ke galeri bersama</span>
            </div>
        </div>
    </div>
</div>

{{-- RIGHT PANEL --}}
<div class="right">
    <div class="form-eyebrow">Portal Siswa</div>
    <div class="form-title">Selamat<br><em>Datang</em></div>
    <p class="form-desc">Masuk dengan NISN dan password yang diberikan oleh admin sekolah.</p>

    @if($errors->any())
        <div class="alert-err">
            <span>⚠</span>
            <span>{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('siswa.login') }}">
        @csrf

        <div class="field">
            <div class="field-label">
                <span class="ico">🔢</span> NISN
            </div>
            <div class="input-wrap">
                <input
                    type="text"
                    name="nisn"
                    value="{{ old('nisn') }}"
                    placeholder="Contoh: 0012345678"
                    maxlength="20"
                    inputmode="numeric"
                    required
                    autofocus
                >
            </div>
        </div>

        <div class="field">
            <div class="field-label">
                <span class="ico">🔒</span> Password
            </div>
            <div class="input-wrap">
                <input
                    type="password"
                    id="inputPw"
                    name="password"
                    placeholder="••••••••"
                    required
                >
                <button type="button" class="pw-toggle" onclick="togglePw()" title="Tampilkan password">
                    👁
                </button>
            </div>
        </div>

        <button class="btn-submit" type="submit">
            Masuk ke Portal →
        </button>
    </form>

    <div class="info-box">
        <span class="ico">💡</span>
        <span>Belum punya akun? Hubungi admin atau wali kelas untuk mendapatkan password portal siswa.</span>
    </div>

    <a class="back-link" href="{{ route('home') }}">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
            <path d="M9 2L4 7L9 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
        Kembali ke halaman pengumuman
    </a>
</div>

<script>
function togglePw() {
    var inp = document.getElementById('inputPw');
    inp.type = inp.type === 'password' ? 'text' : 'password';
}
</script>

</body>
</html>