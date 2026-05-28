@php
    $cfg = $setting;

    $sekolahInstansi  = $cfg['sekolah_instansi']  ?? '';
    $sekolahNama      = $cfg['sekolah_nama']       ?? 'NAMA SEKOLAH';
    $sekolahAlamat    = $cfg['sekolah_alamat']     ?? '';
    $sekolahTelp      = $cfg['sekolah_telp']       ?? '';
    $sekolahNpsn      = $cfg['sekolah_npsn']       ?? '';
    $sekolahNsm       = $cfg['sekolah_nsm']        ?? '';
    $sekolahLogo      = $cfg['sekolah_logo']       ?? '';
    $kepalaNama       = $cfg['kepala_nama']         ?? '';
    $kepalaNip        = $cfg['kepala_nip']          ?? '';
    $tahunPelajaran   = $cfg['tahun_pelajaran']     ?? date('Y') . '/' . (date('Y')+1);

    $logoUrl = '';
    if (!empty($sekolahLogo)) {
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($sekolahLogo)) {
            $logoUrl = asset('storage/' . $sekolahLogo);
        } elseif (file_exists(public_path($sekolahLogo))) {
            $logoUrl = asset($sekolahLogo);
        }
    }

    $kota = '';
    if (!empty($sekolahAlamat) && str_contains($sekolahAlamat, ',')) {
        $parts = array_map('trim', explode(',', $sekolahAlamat));
        $kota  = trim(end($parts));
    }
    $tanggalCetak = \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y');

    $identitasBaris = array_filter([
        $sekolahNsm  ? 'NSM: ' . $sekolahNsm   : null,
        $sekolahNpsn ? 'NPSN: ' . $sekolahNpsn : null,
    ]);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Login — {{ $sekolahNama }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --em:   #1a5c3a;
            --em2:  #22764a;
            --line: #e4e4e4;
            --muted:#999;
            --ink:  #1a1a1a;
            --cred-bg: #f4faf7;
            --cred-border: #b8dbc9;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #d8d8d8;
            color: var(--ink);
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ══ TOOLBAR ══ */
        .toolbar {
            position: sticky; top: 0; z-index: 99;
            background: #fff;
            border-bottom: 1px solid var(--line);
            padding: 9px 20px;
            display: flex; align-items: center; gap: 10px;
        }
        .tb-btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 18px; border-radius: 5px;
            font-size: 12.5px; font-weight: 700;
            cursor: pointer; border: none;
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }
        .tb-primary { background: var(--em); color: #fff; }
        .tb-primary:hover { background: #22764a; }
        .tb-ghost { background: transparent; border: 1px solid #ccc; color: #555; }
        .tb-ghost:hover { border-color: #888; }
        .tb-info { font-size: 12px; color: #aaa; margin-left: 4px; }

        /* ══ HALAMAN A4 ══ */
        .a4-page {
            width: 210mm;
            min-height: 297mm;
            padding: 7mm;
            margin: 16px auto;
            background: #fff;
            box-shadow: 0 4px 24px rgba(0,0,0,.18);
        }

        /* ══ GRID 2 KOLOM ══ */
        .kartu-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
        }

        .kartu-wrap { padding: 2.5mm; }

        /* ══ KARTU ══ */
        .kartu {
            width: 100%;
            height: 58mm;
            border-radius: 2mm;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            background: #fff;
            position: relative;
            border: 0.3mm solid #d0d0d0;
            outline: 0.4mm solid var(--em);
            outline-offset: 0.6mm;
            box-shadow: 0 0.3mm 1.5mm rgba(0,0,0,.08);
        }

        /* ── KOP ── */
		.kartu-kop {
			background: #fff;
			padding: 1.8mm 2.5mm 1.4mm 2.5mm;
			display: flex;
			align-items: center;
			gap: 2.5mm;
			flex-shrink: 0;
			border-bottom: 0.5mm solid var(--em);
		}

		.kartu-logo {
			width: 14mm; height: 14mm;
			flex-shrink: 0;
			display: flex; align-items: center; justify-content: center;
			overflow: hidden;
		}
		.kartu-logo img { width: 100%; height: 100%; object-fit: contain; }
		.kartu-logo-fallback { font-size: 8mm; line-height: 1; }

		.kartu-kop-text { flex: 1; min-width: 0; text-align: center; }
		.kartu-kop-instansi {
			font-size: 4.5pt; color: #777;
			white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
			letter-spacing: .04em; text-transform: uppercase;
		}
		.kartu-kop-nama {
			font-size: 13pt; font-weight: 800;
			text-transform: uppercase; letter-spacing: .04em;
			color: var(--ink);
			white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
			line-height: 1.2;
		}
		.kartu-kop-identitas {
			font-size: 5pt; color: #666;
			white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
			margin-top: .3mm; font-weight: 600;
		}
		.kartu-kop-alamat {
			font-size: 4.2pt; color: #888; font-style: italic;
			white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
		}
		.kartu-kop-subtitle {
			display: inline-block;
			font-size: 4pt; color: #fff;
			font-weight: 700; letter-spacing: .08em;
			text-transform: uppercase;
			margin-top: .8mm;
			background: var(--em);
			padding: .4mm 2.5mm;
			border-radius: 99mm;
		}

        /* ── BODY ── */
        .kartu-body {
            flex: 1; display: flex; gap: 0; min-height: 0;
        }

        /* Strip emerald vertikal kiri */
        .kartu-strip {
            width: 3.5mm;
            background: var(--em);
            flex-shrink: 0;
            position: relative;
        }
        .kartu-strip-text {
			position: absolute;
			bottom: 3mm; left: 50%;
			transform: translateX(-50%) rotate(-90deg);
			font-size: 2.8pt;
			color: rgba(255,255,255,.6);
			white-space: nowrap;
			letter-spacing: .06em;
			font-weight: 700;
			width: 20mm;
			text-align: center;
		}

        .kartu-body-inner {
            flex: 1; display: flex; gap: 2.5mm;
            padding: 1.5mm 2.5mm 1mm 2mm;
            min-height: 0;
        }

        /* Foto */
        .kartu-foto {
		width: 13mm; height: 18mm;
		flex-shrink: 0; align-self: center;
		overflow: hidden;
		background: #f5f5f5;
		display: flex; align-items: center; justify-content: center;
		border: 0.4mm solid #b8dbc9;
		border-radius: 1mm;
	}
        .kartu-foto img { width: 100%; height: 100%; object-fit: cover; object-position: center top; display: block; }
        .kartu-foto-ph { font-size: 6mm; color: #ccc; }

        /* Info */
		.kartu-info {
			flex: 1; min-width: 0;
			display: flex; flex-direction: column; justify-content: flex-start; gap: 1.2mm;
		}
        .kartu-nama {
            font-size: 7.5pt; font-weight: 800;
            color: var(--ink); line-height: 1.2;
            overflow: hidden; display: -webkit-box;
            -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            margin-bottom: .2mm;
        }
        .kartu-kelas {
            font-size: 5pt; color: var(--muted);
            letter-spacing: .02em; font-weight: 500;
            margin-bottom: .4mm;
        }

        /* ══ CREDENTIAL BOX — REDESIGNED ══ */
        .kartu-cred {
            border: 0.5mm solid var(--cred-border);
            border-radius: 1mm;
            overflow: hidden;
            background: var(--cred-bg);
        }
        .kartu-cred-head {
            background: var(--em);
            color: #fff;
            font-size: 4.5pt; font-weight: 700;
            letter-spacing: .12em; text-transform: uppercase;
            padding: .5mm 2mm;
            display: flex; justify-content: space-between; align-items: center;
        }
        .kartu-cred-head-dot { display: flex; gap: .5mm; }
        .kartu-cred-head-dot span {
            width: 1.2mm; height: 1.2mm; border-radius: 50%;
            background: rgba(255,255,255,.3); display: block;
        }
        .kartu-cred-head-dot span:last-child { background: rgba(255,255,255,.85); }

        .kartu-cred-row {
            display: flex;
            align-items: stretch;
            border-top: 0.3mm solid #cde8d9;
            overflow: hidden;
        }
        .kartu-cred-key {
            width: 14mm; flex-shrink: 0;
            padding: .55mm 1.5mm;
            font-size: 5pt;
            font-weight: 600;
            color: var(--em);
            border-right: 0.3mm solid #cde8d9;
            background: #ebf6f0;
            display: flex;
            align-items: center;
            letter-spacing: .02em;
        }
        .kartu-cred-val {
            flex: 1; min-width: 0;
            padding: .6mm 1.5mm;
            font-size: 8pt;
            font-weight: 700;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            color: #111;
            letter-spacing: .01em;
            display: flex;
            align-items: center;
            line-height: 1;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .kartu-cred-val.url {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            font-size: 5.5pt;
            font-weight: 600;
            color: var(--em);
            letter-spacing: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ── FOOTER ── */
        .kartu-footer {
            border-top: 0.3mm solid var(--line);
            padding: .3mm 1.5mm .3mm 4mm;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-shrink: 0;
            background: #fafafa;
        }
        .kf-kiri { font-size: 3.8pt; color: var(--muted); line-height: 1.6; }
        .kf-nisn {
            font-size: 4.4pt; font-weight: 700;
            color: var(--ink); letter-spacing: .06em;
            font-family: 'Courier New', Courier, monospace;
        }
        .kf-rahasia { font-size: 3.2pt; color: #ccc; font-style: italic; }
        .kf-qr { width: 11mm; height: 11mm; flex-shrink: 0; }
        .kf-qr canvas, .kf-qr img { width: 100% !important; height: 100% !important; }
        .kf-qr-label {
            font-size: 3pt; color: #bbb;
            letter-spacing: .06em; text-transform: uppercase;
            text-align: center; margin-top: .3mm;
        }

        /* ══ EMPTY ══ */
        .empty-msg {
            text-align: center; padding: 40px; color: #888; font-size: 14px;
        }

        /* ══ PRINT ══ */
        @media print {
            .toolbar { display: none !important; }
            body { background: #fff; }
            .a4-page { width: 100%; min-height: auto; padding: 8mm; margin: 0; box-shadow: none; }
            .kartu-grid { page-break-inside: auto; }
            .kartu-wrap { page-break-inside: avoid; break-inside: avoid; }
            @page { size: A4 portrait; margin: 0; }
        }
    </style>
</head>
<body>

{{-- ═══ TOOLBAR ═══ --}}
<div class="toolbar">
    <button class="tb-btn tb-primary" onclick="window.print()">🖨 Cetak / Simpan PDF</button>
    <button class="tb-btn tb-ghost" onclick="window.history.back()">← Kembali</button>
    <span class="tb-info">
        {{ $akuns->count() }} kartu
        @if($akuns->count() > 0)
            &nbsp;·&nbsp; ~{{ ceil($akuns->count() / 8) }} hal. A4
        @endif
    </span>
</div>

{{-- ═══ A4 ═══ --}}
<div class="a4-page">

    @if($akuns->isEmpty())
        <div class="empty-msg">
            Tidak ada siswa dengan akun dan password tersimpan.<br>
            <small>Pastikan sudah menjalankan migration dan import ulang data akun.</small>
        </div>
    @else
        <div class="kartu-grid">
            @foreach($akuns as $akun)
            @php
                $siswa   = $akun->siswa;
                $fotoUrl = null;
                if ($siswa->foto_profil && file_exists(public_path($siswa->foto_profil))) {
                    $fotoUrl = asset($siswa->foto_profil);
                }
            @endphp

            <div class="kartu-wrap">
                <div class="kartu">

                    {{-- ── KOP ── --}}
                    <div class="kartu-kop">
                        <div class="kartu-logo">
                            @if($logoUrl)
                                <img src="{{ $logoUrl }}" alt="logo">
                            @else
                                <span class="kartu-logo-fallback">🏫</span>
                            @endif
                        </div>
                        <div class="kartu-kop-text">
                            @if($sekolahInstansi)
                                <div class="kartu-kop-instansi">{{ $sekolahInstansi }}</div>
                            @endif
                            <div class="kartu-kop-nama">{{ $sekolahNama }}</div>
                            @if(count($identitasBaris))
                                <div class="kartu-kop-identitas">{{ implode('  ·  ', $identitasBaris) }}</div>
                            @endif
                            @if($sekolahAlamat)
                                <div class="kartu-kop-alamat">{{ $sekolahAlamat }}</div>
                            @endif
                            <div class="kartu-kop-subtitle">
                                Portal Kelulusan · T.P. {{ $tahunPelajaran }}
                            </div>
                        </div>
                    </div>

                    {{-- ── BODY ── --}}
                    <div class="kartu-body">

                        <div class="kartu-strip">
                            <span class="kartu-strip-text">{{ $tahunPelajaran }}</span>
                        </div>

                        <div class="kartu-body-inner">
                            {{-- Foto --}}
                            <div class="kartu-foto">
                                @if($fotoUrl)
                                    <img src="{{ $fotoUrl }}" alt="{{ $siswa->nama }}">
                                @else
                                    <span class="kartu-foto-ph">👤</span>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="kartu-info">
                                <div>
                                    <div class="kartu-nama">{{ $siswa->nama }}</div>
                                    <div class="kartu-kelas">{{ $siswa->kelas }} &nbsp;&middot;&nbsp; {{ $siswa->tahun_lulus }}</div>
                                </div>
                                <div class="kartu-cred">
                                    <div class="kartu-cred-head">
                                        <span>Data Masuk Portal</span>
                                        <span class="kartu-cred-head-dot">
                                            <span></span><span></span><span></span>
                                        </span>
                                    </div>
                                    <div class="kartu-cred-row">
                                        <span class="kartu-cred-key">Username</span>
                                        <span class="kartu-cred-val">{{ $siswa->nisn }}</span>
                                    </div>
                                    <div class="kartu-cred-row">
                                        <span class="kartu-cred-key">Password</span>
                                        <span class="kartu-cred-val">{{ $akun->plain_password }}</span>
                                    </div>
                                    <div class="kartu-cred-row">
                                        <span class="kartu-cred-key">Alamat</span>
                                        <span class="kartu-cred-val url">{{ url('/login') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ── FOOTER ── --}}
                    <div class="kartu-footer">
                        <div class="kf-kiri">
                            <div style="font-size:3.4pt;color:#aaa;margin-top:.3mm">{{ $kota ? $kota.', ' : '' }}{{ $tanggalCetak }}</div>
                            <div class="kf-rahasia">Rahasia &amp; pribadi</div>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:center;gap:.5mm">
                            <div class="kf-qr" id="qr-{{ $loop->index }}"></div>
                            <div class="kf-qr-label">Scan to Login</div>
                        </div>
                    </div>

                </div>{{-- .kartu --}}
            </div>{{-- .kartu-wrap --}}
            @endforeach
        </div>
    @endif

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
var LOGIN_URL = "{{ url('/login') }}";

var qrTargets = document.querySelectorAll('[id^="qr-"]');
qrTargets.forEach(function(el) {
    new QRCode(el, {
        text: LOGIN_URL,
        width: 64,
        height: 64,
        colorDark: '#1a5c3a',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.M,
    });
});

if (new URLSearchParams(window.location.search).get('autoprint') === '1') {
    window.addEventListener('load', function() {
        setTimeout(function() { window.print(); }, 800);
    });
}
</script>
</body>
</html>