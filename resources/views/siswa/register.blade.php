@php
    use App\Models\Setting;
    $sekolahNama = Setting::get('sekolah_nama', 'Sekolah');
    $sekolahLogo = Setting::get('sekolah_logo', '');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Akun Siswa — {{ $sekolahNama }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--gold:#C9A84C;--gold2:#E8C97A;--dark:#080808;--card:#111;--b1:rgba(201,168,76,.2);--b2:rgba(201,168,76,.08);--txt:#F0ECE3;--muted:#7A7268}
body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--txt);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:1.5rem}
body::before{content:'';position:fixed;inset:0;pointer-events:none;background:radial-gradient(ellipse 100% 50% at 50% -10%,rgba(201,168,76,.12) 0%,transparent 65%)}

.box{position:relative;z-index:1;width:100%;max-width:420px;background:var(--card);border:1px solid var(--b1);border-radius:1.5rem;padding:2.25rem 2rem;box-shadow:0 24px 64px rgba(0,0,0,.6)}

/* corner accents */
.box::before,.box::after{content:'';position:absolute;width:50px;height:50px;border-color:var(--b1);border-style:solid}
.box::before{top:1.25rem;right:1.25rem;border-width:1px 1px 0 0}
.box::after{bottom:1.25rem;left:1.25rem;border-width:0 0 1px 1px}

.logo-wrap{display:flex;align-items:center;justify-content:center;margin-bottom:1.5rem}
.logo-seal{width:56px;height:56px;border-radius:50%;border:1.5px solid var(--b1);display:flex;align-items:center;justify-content:center;font-size:1.75rem;overflow:hidden}
.logo-seal img{width:100%;height:100%;object-fit:cover}

.form-eyebrow{font-size:.65rem;letter-spacing:.15em;text-transform:uppercase;color:var(--gold);margin-bottom:.5rem;text-align:center}
.form-title{font-family:'Cormorant Garamond',serif;font-size:1.9rem;font-weight:700;text-align:center;margin-bottom:.3rem}
.form-desc{font-size:.78rem;color:var(--muted);text-align:center;margin-bottom:1.75rem;line-height:1.6}

.field{margin-bottom:1.1rem}
.field label{display:block;font-size:.68rem;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:.4rem}
.field input{width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:.75rem;padding:.825rem 1rem;color:var(--txt);font-family:'DM Sans',sans-serif;font-size:.9rem;outline:none;transition:border-color .2s,box-shadow .2s;-webkit-appearance:none}
.field input:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(201,168,76,.12)}
.field input::placeholder{color:rgba(122,114,104,.5)}
.err-msg{display:block;font-size:.72rem;color:#FCA5A5;margin-top:.35rem}

.btn-submit{width:100%;background:linear-gradient(135deg,var(--gold),var(--gold2));border:none;border-radius:.875rem;padding:.95rem;color:#060606;font-family:'DM Sans',sans-serif;font-weight:700;font-size:.95rem;cursor:pointer;margin-top:.5rem;transition:opacity .2s,transform .15s}
.btn-submit:hover{opacity:.9;transform:translateY(-1px)}

.alt-link{text-align:center;font-size:.78rem;color:var(--muted);margin-top:1.25rem}
.alt-link a{color:var(--gold2);text-decoration:none}
.alt-link a:hover{text-decoration:underline}

.back-link{display:block;text-align:center;font-size:.75rem;color:var(--muted);text-decoration:none;margin-top:.875rem;transition:color .2s}
.back-link:hover{color:var(--gold2)}

.alert-err{background:rgba(220,38,38,.08);border:1px solid rgba(220,38,38,.2);border-left:3px solid #dc2626;border-radius:.75rem;padding:.75rem 1rem;color:#FCA5A5;font-size:.8rem;margin-bottom:1.1rem}
.alert-err ul{margin:0;padding-left:1.1rem}
</style>
</head>
<body>

<div class="box">
    <div class="logo-wrap">
        <div class="logo-seal">
            @if(!empty($sekolahLogo) && \Illuminate\Support\Facades\Storage::disk('public')->exists($sekolahLogo))
                <img src="{{ asset('storage/'.$sekolahLogo) }}" alt="{{ $sekolahNama }}">
            @else
                🎓
            @endif
        </div>
    </div>

    <div class="form-eyebrow">Portal Siswa</div>
    <div class="form-title">Buat Akun</div>
    <p class="form-desc">Gunakan NISN kamu untuk mendaftar dan melihat hasil kelulusan.</p>

    @if($errors->any())
        <div class="alert-err">
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('siswa.register') }}">
        @csrf

        <div class="field">
            <label>NISN</label>
            <input type="text" name="nisn" value="{{ old('nisn') }}" placeholder="0012345678" maxlength="20" required autofocus>
            @error('nisn')<span class="err-msg">{{ $message }}</span>@enderror
        </div>

        <div class="field">
            <label>Password</label>
            <input type="password" name="password" placeholder="Minimal 6 karakter" required>
            @error('password')<span class="err-msg">{{ $message }}</span>@enderror
        </div>

        <div class="field">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" placeholder="Ulangi password" required>
        </div>

        <button class="btn-submit" type="submit">Daftar Sekarang →</button>
    </form>

    <p class="alt-link">Sudah punya akun? <a href="{{ route('siswa.login') }}">Login di sini</a></p>
    <a class="back-link" href="{{ route('home') }}">← Kembali ke halaman utama</a>
</div>

</body>
</html>
