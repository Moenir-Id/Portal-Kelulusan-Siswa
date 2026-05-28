<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin – @yield('title','Panel')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --gold: #C9A84C; --gold2: #E8C97A;
            --dark: #0D0D0D; --card: #141414; --surface: #1C1C1C;
            --border: rgba(201,168,76,.15); --txt: #F0ECE3; --muted: #9A9485;
            --red: #dc2626; --green: #16a34a;
            --sidebar: 240px;
            --bottomnav: 62px;
        }
        body { font-family:'DM Sans',sans-serif; background:var(--dark); color:var(--txt); display:flex; min-height:100vh; }

        /* ── SIDEBAR (desktop) ── */
        .sidebar {
            width:var(--sidebar); background:var(--card);
            border-right:1px solid var(--border);
            display:flex; flex-direction:column;
            position:fixed; top:0; left:0; height:100vh; z-index:100;
        }

        .sidebar-logo {
            padding:1.25rem 1.25rem 1rem;
            border-bottom:1px solid var(--border);
            display:flex; align-items:center; gap:.75rem;
            text-decoration:none;
        }
        .sidebar-logo-img {
            width:38px; height:38px; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
            border-radius:.5rem; overflow:hidden;
            background:rgba(201,168,76,.06);
            border:1px solid rgba(201,168,76,.15);
        }
        .sidebar-logo-img img { width:100%; height:100%; object-fit:contain; display:block; }
        .sidebar-logo-img .logo-fallback { font-size:1.3rem; line-height:1; }
        .sidebar-logo-name {
            font-family:'Playfair Display',serif;
            font-size:.95rem; color:var(--gold2); line-height:1.25;
            display:-webkit-box; -webkit-line-clamp:2;
            -webkit-box-orient:vertical; overflow:hidden;
        }
        .sidebar-logo-sub { font-family:'DM Sans',sans-serif; font-size:.65rem; color:var(--muted); font-weight:400; margin-top:.2rem; }

        nav.sidebar-nav { flex:1; padding:1rem 0; overflow-y:auto; }

        .nav-section {
            font-size:.6rem; letter-spacing:.14em; text-transform:uppercase;
            color:var(--muted); padding:.75rem 1.25rem .3rem; opacity:.6;
        }
        .nav-item {
            display:flex; align-items:center; gap:.75rem;
            padding:.7rem 1.25rem; text-decoration:none;
            color:var(--muted); font-size:.875rem; font-weight:500;
            transition:color .2s, background .2s;
            border-left:3px solid transparent;
        }
        .nav-item:hover, .nav-item.active {
            color:var(--gold2); background:rgba(201,168,76,.06);
            border-left-color:var(--gold);
        }
        .nav-item .ico { font-size:1.1rem; width:20px; text-align:center; }

        .sidebar-footer { padding:1rem 1.25rem; border-top:1px solid var(--border); }
        .btn-logout {
            display:block; width:100%; text-align:center;
            background:rgba(220,38,38,.1); border:1px solid rgba(220,38,38,.2);
            color:#FCA5A5; border-radius:.6rem; padding:.6rem;
            font-size:.8rem; text-decoration:none; transition:background .2s;
            cursor:pointer; font-family:'DM Sans',sans-serif;
        }
        .btn-logout:hover { background:rgba(220,38,38,.2); }

        /* ── MAIN ── */
        .main { margin-left:var(--sidebar); flex:1; padding:2rem; min-height:100vh; }
        .page-header { margin-bottom:2rem; }
        .page-header h2 { font-family:'Playfair Display',serif; font-size:1.8rem; }
        .page-header p  { color:var(--muted); font-size:.875rem; margin-top:.3rem; }

        .card { background:var(--card); border:1px solid var(--border); border-radius:1rem; padding:1.5rem; }
        .stat-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:1rem; margin-bottom:2rem; }
        .stat { background:var(--card); border:1px solid var(--border); border-radius:.875rem; padding:1.25rem; }
        .stat-val { font-family:'Playfair Display',serif; font-size:2.2rem; color:var(--gold2); }
        .stat-lbl { font-size:.78rem; color:var(--muted); margin-top:.25rem; }

        .alert { border-radius:.75rem; padding:.8rem 1rem; font-size:.875rem; margin-bottom:1.25rem; }
        .alert-success { background:rgba(22,163,74,.12); border:1px solid rgba(22,163,74,.25); color:#86efac; }
        .alert-error   { background:rgba(220,38,38,.12); border:1px solid rgba(220,38,38,.25); color:#fca5a5; }

        .tbl-wrap { overflow-x:auto; }
        table { width:100%; border-collapse:collapse; font-size:.875rem; }
        th { text-align:left; padding:.6rem .9rem; border-bottom:1px solid var(--border); font-size:.7rem; letter-spacing:.1em; text-transform:uppercase; color:var(--muted); }
        td { padding:.7rem .9rem; border-bottom:1px solid rgba(255,255,255,.05); vertical-align:middle; }
        tr:hover td { background:rgba(255,255,255,.02); }

        .pill { display:inline-block; padding:.2rem .7rem; border-radius:999px; font-size:.72rem; font-weight:600; }
        .pill-lulus { background:rgba(201,168,76,.15); color:var(--gold2); border:1px solid rgba(201,168,76,.25); }
        .pill-tidak { background:rgba(220,38,38,.12); color:#fca5a5; border:1px solid rgba(220,38,38,.2); }

        .btn { display:inline-flex; align-items:center; gap:.4rem; padding:.5rem 1rem; border-radius:.6rem; font-size:.825rem; font-weight:600; cursor:pointer; text-decoration:none; border:none; transition:all .2s; font-family:'DM Sans',sans-serif; }
        .btn-gold  { background:linear-gradient(135deg,var(--gold),var(--gold2)); color:var(--dark); }
        .btn-gold:hover { box-shadow:0 4px 16px rgba(201,168,76,.3); transform:translateY(-1px); }
        .btn-ghost { background:transparent; border:1px solid var(--border); color:var(--muted); }
        .btn-ghost:hover { border-color:var(--gold); color:var(--gold2); }
        .btn-red   { background:rgba(220,38,38,.1); border:1px solid rgba(220,38,38,.2); color:#fca5a5; }
        .btn-red:hover { background:rgba(220,38,38,.2); }

        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
        .form-group { display:flex; flex-direction:column; gap:.4rem; }
        .form-group.full { grid-column:1/-1; }
        label { font-size:.75rem; letter-spacing:.08em; text-transform:uppercase; color:var(--muted); }
        input, select, textarea {
            background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.1);
            border-radius:.6rem; padding:.7rem .9rem;
            color:var(--txt); font-family:'DM Sans',sans-serif; font-size:.9rem;
            outline:none; transition:border-color .2s;
        }
        input:focus, select:focus, textarea:focus { border-color:var(--gold); }
        input::placeholder, textarea::placeholder { color:var(--muted); }
        select option { background:var(--card); }
        .err-msg { font-size:.78rem; color:#fca5a5; }

        .pagination { display:flex; gap:.5rem; margin-top:1.25rem; flex-wrap:wrap; }
        .pagination a, .pagination span {
            padding:.4rem .75rem; border-radius:.5rem; font-size:.8rem;
            background:var(--surface); border:1px solid var(--border);
            color:var(--muted); text-decoration:none;
        }
        .pagination .active span { background:var(--gold); color:var(--dark); border-color:var(--gold); font-weight:700; }

        .topbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:2rem; }
        .topbar .user { font-size:.85rem; color:var(--muted); }
        .topbar .user strong { color:var(--txt); }

        .mobile-topbar { display:none; }
        .bottom-nav { display:none; }

        /* ── RESPONSIVE ── */
        @media(max-width:768px) {
            .sidebar { display:none; }
            .main {
                margin-left:0;
                padding:0 1rem 1rem;
                padding-bottom:calc(var(--bottomnav) + 1rem);
            }
            .form-grid { grid-template-columns:1fr; }
            .topbar { display:none; }

            .mobile-topbar {
                display:flex; align-items:center; justify-content:space-between;
                padding:.65rem 1rem;
                background:rgba(20,20,20,.92);
                backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px);
                border-bottom:1px solid var(--border);
                margin:0 -1rem 1.25rem;
                position:sticky; top:0; z-index:50; gap:.75rem;
            }
            .mobile-topbar-logo {
                width:30px; height:30px; flex-shrink:0;
                display:flex; align-items:center; justify-content:center;
                border-radius:.4rem; overflow:hidden;
                background:rgba(201,168,76,.06); border:1px solid rgba(201,168,76,.12);
            }
            .mobile-topbar-logo img { width:100%; height:100%; object-fit:contain; display:block; }
            .mobile-topbar-logo .logo-fallback { font-size:1rem; }
            .mobile-topbar-brand {
                font-family:'Playfair Display',serif; font-size:.875rem; color:var(--gold2);
                flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
            }
            .mobile-topbar-user { font-size:.72rem; color:var(--muted); flex-shrink:0; }

            .bottom-nav {
                display:flex;
                position:fixed; bottom:0; left:0; right:0;
                height:var(--bottomnav);
                background:rgba(20,20,20,.96);
                border-top:1px solid var(--border);
                backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px);
                z-index:100;
                padding-bottom:env(safe-area-inset-bottom, 0px);
                overflow-x:auto;
            }
            .bn-item {
                flex:1; min-width:52px;
                display:flex; flex-direction:column;
                align-items:center; justify-content:center;
                gap:.18rem; text-decoration:none;
                color:var(--muted); font-size:.5rem; font-weight:500;
                padding:.35rem .1rem; transition:color .2s;
                border:none; background:transparent; cursor:pointer;
                font-family:'DM Sans',sans-serif;
                -webkit-tap-highlight-color:transparent;
                position:relative;
            }
            .bn-ico { font-size:1.1rem; line-height:1; transition:transform .18s; display:block; }
            .bn-item.active { color:var(--gold2); }
            .bn-item.active .bn-ico { transform:translateY(-2px) scale(1.15); }
            .bn-item.active::before {
                content:''; position:absolute;
                top:0; left:20%; right:20%;
                height:2px; border-radius:0 0 2px 2px;
                background:linear-gradient(90deg,var(--gold),var(--gold2));
            }
            .bn-logout { color:rgba(252,165,165,.5); }
            .bn-logout:hover, .bn-logout:active { color:#fca5a5; }
        }
    </style>
</head>
<body>

@php
    $settingMap    = \App\Models\Setting::all_map();
    $schoolLogo    = $settingMap['sekolah_logo'] ?? '';
    $schoolNama    = $settingMap['sekolah_nama'] ?? 'Panel Admin';
    $schoolLogoUrl = '';
    if ($schoolLogo) {
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($schoolLogo)) {
            $schoolLogoUrl = asset('storage/' . $schoolLogo);
        } elseif (file_exists(public_path($schoolLogo))) {
            $schoolLogoUrl = asset($schoolLogo);
        }
    }
@endphp

{{-- ── SIDEBAR desktop ── --}}
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-img">
            @if($schoolLogoUrl)
                <img src="{{ $schoolLogoUrl }}" alt="{{ $schoolNama }}">
            @else
                <span class="logo-fallback">🎓</span>
            @endif
        </div>
        <div class="sidebar-logo-text">
            <div class="sidebar-logo-name">{{ $schoolNama }}</div>
            <div class="sidebar-logo-sub">Panel Admin</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Menu Utama</div>
        <a class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
           href="{{ route('admin.dashboard') }}">
            <span class="ico">📊</span> Dashboard
        </a>
        <a class="nav-item {{ request()->routeIs('admin.siswa.*') && !request()->routeIs('admin.siswa.import-form') && !request()->routeIs('admin.kartu-login.*') ? 'active' : '' }}"
           href="{{ route('admin.siswa.index') }}">
            <span class="ico">👥</span> Data Siswa
        </a>
        <a class="nav-item {{ request()->routeIs('admin.siswa.import-form') ? 'active' : '' }}"
           href="{{ route('admin.siswa.import-form') }}">
            <span class="ico">📥</span> Import Excel
        </a>
        <a class="nav-item {{ request()->routeIs('admin.kartu-login.*') ? 'active' : '' }}"
           href="{{ route('admin.kartu-login.index') }}">
            <span class="ico">🪪</span> Kartu Login
        </a>
        <a class="nav-item {{ request()->routeIs('admin.login-log') ? 'active' : '' }}"
           href="{{ route('admin.login-log') }}">
            <span class="ico">🔐</span> Riwayat Login
        </a>

        <div class="nav-section">Portal Siswa</div>
        <a class="nav-item {{ request()->routeIs('admin.galeri') ? 'active' : '' }}"
           href="{{ route('admin.galeri') }}">
            <span class="ico">🖼</span> Galeri Momen
        </a>

        <div class="nav-section">Sistem</div>
        <a class="nav-item {{ request()->routeIs('admin.setting') ? 'active' : '' }}"
           href="{{ route('admin.setting') }}">
            <span class="ico">⚙️</span> Pengaturan
        </a>
        <a class="nav-item" href="{{ route('home') }}" target="_blank">
            <span class="ico">🌐</span> Halaman Publik
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Keluar</button>
        </form>
    </div>
</aside>

{{-- ── BOTTOM NAVBAR mobile — semua menu lengkap ── --}}
<nav class="bottom-nav">
    <a class="bn-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
       href="{{ route('admin.dashboard') }}">
        <span class="bn-ico">📊</span>
        <span>Dashboard</span>
    </a>
    <a class="bn-item {{ request()->routeIs('admin.siswa.*') && !request()->routeIs('admin.siswa.import-form') && !request()->routeIs('admin.kartu-login.*') ? 'active' : '' }}"
       href="{{ route('admin.siswa.index') }}">
        <span class="bn-ico">👥</span>
        <span>Siswa</span>
    </a>
    <a class="bn-item {{ request()->routeIs('admin.kartu-login.*') ? 'active' : '' }}"
       href="{{ route('admin.kartu-login.index') }}">
        <span class="bn-ico">🪪</span>
        <span>Kartu</span>
    </a>
    <a class="bn-item {{ request()->routeIs('admin.login-log') ? 'active' : '' }}"
       href="{{ route('admin.login-log') }}">
        <span class="bn-ico">🔐</span>
        <span>Login</span>
    </a>
    <a class="bn-item {{ request()->routeIs('admin.galeri') ? 'active' : '' }}"
       href="{{ route('admin.galeri') }}">
        <span class="bn-ico">🖼</span>
        <span>Galeri</span>
    </a>
    <a class="bn-item {{ request()->routeIs('admin.setting') ? 'active' : '' }}"
       href="{{ route('admin.setting') }}">
        <span class="bn-ico">⚙️</span>
        <span>Setting</span>
    </a>
    <form method="POST" action="{{ route('logout') }}" style="flex:1;min-width:52px;display:contents">
        @csrf
        <button type="submit" class="bn-item bn-logout">
            <span class="bn-ico">🚪</span>
            <span>Keluar</span>
        </button>
    </form>
</nav>

{{-- ── MAIN ── --}}
<main class="main">
    <div class="mobile-topbar">
        <div class="mobile-topbar-logo">
            @if($schoolLogoUrl)
                <img src="{{ $schoolLogoUrl }}" alt="{{ $schoolNama }}">
            @else
                <span class="logo-fallback">🎓</span>
            @endif
        </div>
        <span class="mobile-topbar-brand">{{ $schoolNama }}</span>
        <span class="mobile-topbar-user">{{ auth()->user()->name ?? 'Admin' }}</span>
    </div>

    <div class="topbar">
        <div></div>
        <div class="user">Login sebagai <strong>{{ auth()->user()->name ?? 'Admin' }}</strong></div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">✗ {{ session('error') }}</div>
    @endif

    @yield('content')
</main>

</body>
</html>