@extends('layouts.admin')
@section('title', 'Riwayat Login')
@section('content')

<div class="page-header">
    <h2>🔐 Riwayat Login</h2>
    <p>Monitor aktivitas login admin dan siswa ke dalam sistem</p>
</div>

{{-- ── Sesi Aktif Sekarang ──────────────────────────────────────── --}}
<div class="section-card">
    <div class="section-head">
        <span class="dot dot-green"></span>
        <h3>Sedang Online <span class="badge-count">{{ $aktiveSessions->count() }}</span></h3>
    </div>

    @if($aktiveSessions->isEmpty())
        <p class="empty-note">Tidak ada sesi aktif saat ini.</p>
    @else
        <div class="table-wrap">
            <table class="log-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>IP Address</th>
                        <th>Tipe</th>
                        <th>Aktivitas Terakhir</th>
                        <th class="hide-sm">Browser / Device</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aktiveSessions as $i => $s)
                    <tr>
                        <td class="num">{{ $i + 1 }}</td>
                        <td><code>{{ $s->ip_address ?? '-' }}</code></td>
                        <td>
                            @if($s->guard === 'admin')
                                <span class="pill pill-gold">👤 Admin</span>
                            @elseif($s->guard === 'siswa')
                                <span class="pill pill-blue">🎓 Siswa</span>
                            @else
                                <span class="pill pill-gray">Tamu</span>
                            @endif
                        </td>
                        <td>{{ $s->last_activity->diffForHumans() }}</td>
                        <td class="ua hide-sm">{{ Str::limit($s->user_agent ?? '-', 65) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- ── Riwayat Login Admin ──────────────────────────────────────── --}}
<div class="section-card">
    <div class="section-head">
        <span class="dot dot-gold"></span>
        <h3>Login Admin</h3>
    </div>

    @if($riwayatAdmin->isEmpty())
        <p class="empty-note">Belum ada riwayat login admin.</p>
    @else
        {{-- Desktop table --}}
        <div class="table-wrap hide-mobile">
            <table class="log-table">
                <thead>
                    <tr>
                        <th>Nama / Email</th>
                        <th>Login Terakhir</th>
                        <th>IP Terakhir</th>
                        <th>Total Login</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayatAdmin as $a)
                    <tr>
                        <td>
                            <div class="user-name">{{ $a->name }}</div>
                            <div class="user-sub">{{ $a->email }}</div>
                        </td>
                        <td>
                            <div>{{ \Carbon\Carbon::parse($a->last_login_at)->format('d M Y, H:i') }}</div>
                            <div class="user-sub">{{ \Carbon\Carbon::parse($a->last_login_at)->diffForHumans() }}</div>
                        </td>
                        <td><code>{{ $a->last_login_ip ?? '-' }}</code></td>
                        <td><span class="badge-count">{{ number_format($a->login_count) }}×</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- Mobile cards --}}
        <div class="card-list show-mobile">
            @foreach($riwayatAdmin as $a)
            <div class="log-card">
                <div class="lc-row">
                    <span class="lc-label">Nama</span>
                    <span class="lc-val">{{ $a->name }}<span class="user-sub" style="margin-left:.4rem">{{ $a->email }}</span></span>
                </div>
                <div class="lc-row">
                    <span class="lc-label">Login Terakhir</span>
                    <span class="lc-val">{{ \Carbon\Carbon::parse($a->last_login_at)->format('d M Y, H:i') }}<span class="user-sub" style="margin-left:.4rem">{{ \Carbon\Carbon::parse($a->last_login_at)->diffForHumans() }}</span></span>
                </div>
                <div class="lc-row">
                    <span class="lc-label">IP</span>
                    <code>{{ $a->last_login_ip ?? '-' }}</code>
                </div>
                <div class="lc-row">
                    <span class="lc-label">Total Login</span>
                    <span class="badge-count">{{ number_format($a->login_count) }}×</span>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- ── Riwayat Login Siswa ──────────────────────────────────────── --}}
<div class="section-card">
    <div class="section-head">
        <span class="dot dot-blue"></span>
        <h3>Riwayat Login Siswa</h3>
    </div>

    <form method="GET" action="{{ route('admin.login-log') }}" class="filter-bar">
        <input type="text" name="cari" value="{{ request('cari') }}"
               placeholder="Cari nama atau NISN siswa…" class="input-search">
        <button type="submit" class="btn btn-ghost btn-sm">🔍 Cari</button>
        @if(request('cari'))
            <a href="{{ route('admin.login-log') }}" class="btn btn-ghost btn-sm">✕ Reset</a>
        @endif
    </form>

    @if($riwayatSiswa->isEmpty())
        <p class="empty-note">Belum ada siswa yang login.</p>
    @else
        {{-- Desktop table --}}
        <div class="table-wrap hide-mobile">
            <table class="log-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Login Terakhir</th>
                        <th>IP Terakhir</th>
                        <th>Total Login</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayatSiswa as $akun)
                    <tr>
                        <td class="num">{{ $riwayatSiswa->firstItem() + $loop->index }}</td>
                        <td><code>{{ $akun->nisn }}</code></td>
                        <td>
                            @if($akun->siswa)
                                <a href="{{ route('admin.siswa.edit', $akun->siswa) }}" class="link-nama">{{ $akun->siswa->nama }}</a>
                                @php $st = $akun->siswa->status ?? '' @endphp
                                @if($st === 'LULUS') <span class="pill pill-green pill-xs">L</span>
                                @elseif($st === 'LULUS BERSYARAT') <span class="pill pill-amber pill-xs">LB</span>
                                @else <span class="pill pill-red pill-xs">TL</span>
                                @endif
                            @else
                                <span class="user-sub">—</span>
                            @endif
                        </td>
                        <td>{{ $akun->siswa->kelas ?? '-' }}</td>
                        <td>
                            <div>{{ \Carbon\Carbon::parse($akun->last_login_at)->format('d M Y, H:i') }}</div>
                            <div class="user-sub">{{ \Carbon\Carbon::parse($akun->last_login_at)->diffForHumans() }}</div>
                        </td>
                        <td><code>{{ $akun->last_login_ip ?? '-' }}</code></td>
                        <td><span class="badge-count">{{ number_format($akun->login_count) }}×</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile cards --}}
        <div class="card-list show-mobile">
            @foreach($riwayatSiswa as $akun)
            <div class="log-card">
                <div class="lc-top">
                    <div>
                        @if($akun->siswa)
                            <a href="{{ route('admin.siswa.edit', $akun->siswa) }}" class="link-nama">{{ $akun->siswa->nama }}</a>
                            @php $st = $akun->siswa->status ?? '' @endphp
                            @if($st === 'LULUS') <span class="pill pill-green pill-xs">L</span>
                            @elseif($st === 'LULUS BERSYARAT') <span class="pill pill-amber pill-xs">LB</span>
                            @else <span class="pill pill-red pill-xs">TL</span>
                            @endif
                        @else
                            <span class="user-sub">—</span>
                        @endif
                    </div>
                    <span class="badge-count">{{ number_format($akun->login_count) }}×</span>
                </div>
                <div class="lc-row">
                    <span class="lc-label">NISN</span>
                    <code>{{ $akun->nisn }}</code>
                </div>
                <div class="lc-row">
                    <span class="lc-label">Kelas</span>
                    <span class="lc-val">{{ $akun->siswa->kelas ?? '-' }}</span>
                </div>
                <div class="lc-row">
                    <span class="lc-label">Login Terakhir</span>
                    <span class="lc-val">
                        {{ \Carbon\Carbon::parse($akun->last_login_at)->format('d M Y, H:i') }}
                        <span class="user-sub"> · {{ \Carbon\Carbon::parse($akun->last_login_at)->diffForHumans() }}</span>
                    </span>
                </div>
                <div class="lc-row">
                    <span class="lc-label">IP</span>
                    <code>{{ $akun->last_login_ip ?? '-' }}</code>
                </div>
            </div>
            @endforeach
        </div>

        <div class="pagi-wrap">{{ $riwayatSiswa->links() }}</div>
    @endif
</div>

<style>
/* ── Cards ─────────────────────────────────────────────────── */
.section-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}
.section-head {
    display: flex; align-items: center; gap: .625rem; margin-bottom: 1rem;
}
.section-head h3 {
    margin: 0; font-size: 1rem; font-weight: 600;
    display: flex; align-items: center; gap: .5rem;
}
.empty-note { color: var(--muted); font-size: .875rem; margin: 0; }

/* ── Dots ──────────────────────────────────────────────────── */
.dot { width:10px; height:10px; border-radius:50%; flex-shrink:0; animation:pulse-dot 2s ease-in-out infinite; }
.dot-green { background:#22c55e; }
.dot-gold  { background:#f59e0b; }
.dot-blue  { background:#60a5fa; }
@keyframes pulse-dot {
    0%,100% { transform:scale(1); opacity:1; }
    50%      { transform:scale(1.35); opacity:.65; }
}

/* ── Table ─────────────────────────────────────────────────── */
.table-wrap { overflow-x: auto; }
.log-table { width:100%; border-collapse:collapse; font-size:.875rem; }
.log-table th {
    text-align:left; padding:.5rem .75rem;
    border-bottom:2px solid var(--border);
    color:var(--muted); font-weight:600; white-space:nowrap;
}
.log-table td { padding:.625rem .75rem; border-bottom:1px solid var(--border); vertical-align:middle; }
.log-table tr:last-child td { border-bottom:none; }
.log-table tr:hover td { background:rgba(255,255,255,.03); }
.log-table code {
    font-size:.78rem; background:rgba(255,255,255,.08);
    padding:.15rem .45rem; border-radius:4px; letter-spacing:.02em;
}
.num { color:var(--muted); width:36px; }
.ua  { color:var(--muted); font-size:.78rem; }

/* ── Pills ─────────────────────────────────────────────────── */
.pill {
    display:inline-flex; align-items:center;
    padding:.2rem .6rem; border-radius:20px;
    font-size:.75rem; font-weight:600; white-space:nowrap;
}
.pill-gold  { background:rgba(245,158,11,.15); color:#fbbf24; }
.pill-blue  { background:rgba(96,165,250,.15);  color:#93c5fd; }
.pill-gray  { background:rgba(156,163,175,.15); color:#9ca3af; }
.pill-green { background:rgba(34,197,94,.15);   color:#86efac; }
.pill-amber { background:rgba(245,158,11,.12);  color:#fcd34d; }
.pill-red   { background:rgba(248,113,113,.15); color:#fca5a5; }
.pill-xs    { padding:.1rem .38rem; font-size:.68rem; margin-left:.3rem; }

/* ── Badge ─────────────────────────────────────────────────── */
.badge-count {
    display:inline-block; background:rgba(255,255,255,.08);
    border-radius:20px; padding:.15rem .55rem;
    font-size:.8rem; font-weight:600;
}

/* ── User ──────────────────────────────────────────────────── */
.user-name { font-weight:500; }
.user-sub  { font-size:.78rem; color:var(--muted); margin-top:.1rem; }
.link-nama { color:var(--gold); text-decoration:none; font-weight:500; }
.link-nama:hover { text-decoration:underline; }

/* ── Filter ────────────────────────────────────────────────── */
.filter-bar { display:flex; gap:.5rem; margin-bottom:1rem; flex-wrap:wrap; align-items:center; }
.input-search {
    background:rgba(255,255,255,.06); border:1px solid var(--border);
    border-radius:8px; padding:.45rem .85rem;
    color:var(--txt); font-size:.875rem;
    min-width:230px; outline:none; transition:border-color .2s;
}
.input-search:focus { border-color:var(--gold); }

/* ── Pagination ────────────────────────────────────────────── */
.pagi-wrap { margin-top:1rem; }

/* ── Mobile card list ──────────────────────────────────────── */
.log-card {
    background:rgba(255,255,255,.03);
    border:1px solid var(--border);
    border-radius:10px;
    padding:.875rem 1rem;
    margin-bottom:.625rem;
}
.lc-top {
    display:flex; justify-content:space-between;
    align-items:flex-start; margin-bottom:.625rem;
}
.lc-row {
    display:flex; align-items:baseline;
    gap:.5rem; padding:.25rem 0;
    border-bottom:1px solid rgba(255,255,255,.04);
    font-size:.82rem;
}
.lc-row:last-child { border-bottom:none; }
.lc-label {
    color:var(--muted); font-size:.72rem;
    text-transform:uppercase; letter-spacing:.06em;
    flex-shrink:0; width:90px;
}
.lc-val { flex:1; }
.lc-row code {
    font-size:.75rem; background:rgba(255,255,255,.08);
    padding:.1rem .4rem; border-radius:4px;
}

/* ── Responsive toggle ─────────────────────────────────────── */
.hide-mobile { display:block; }
.show-mobile { display:none; }
.hide-sm     { display:table-cell; }

@media (max-width: 640px) {
    .section-card { padding:1rem; }
    .hide-mobile  { display:none !important; }
    .show-mobile  { display:block !important; }
    .hide-sm      { display:none !important; }
    .input-search { min-width:0; flex:1; }
}
</style>

@endsection