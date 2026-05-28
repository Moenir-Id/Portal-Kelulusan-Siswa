@php
    $cfg = isset($setting) ? $setting : \App\Models\Setting::all_map();

    $sekolahInstansi  = $cfg['sekolah_instansi']  ?? '';
    $sekolahNama      = $cfg['sekolah_nama']       ?? '';
    $sekolahNpsn      = $cfg['sekolah_npsn']       ?? '';
    $sekolahNsm       = $cfg['sekolah_nsm']        ?? '';
    $sekolahAkreditasi= $cfg['sekolah_akreditasi'] ?? '';
    $sekolahAlamat    = $cfg['sekolah_alamat']     ?? '';
    $sekolahTelp      = $cfg['sekolah_telp']       ?? '';
    $sekolahEmail     = $cfg['sekolah_email']      ?? '';
    $sekolahWebsite   = $cfg['sekolah_website']    ?? '';
    $sekolahLogo      = $cfg['sekolah_logo']       ?? '';
    $kepalaNama       = $cfg['kepala_nama']         ?? '';
    $kepalaNip        = $cfg['kepala_nip']          ?? '';
    $formatNomorSurat = $cfg['format_nomor_surat']  ?? '{nomor}/SKL/{tahun}';
    $pgTahun          = $cfg['tahun_pelajaran']      ?? '';

    $nomorUrut  = str_pad($siswa->id, 3, '0', STR_PAD_LEFT);
    $bulanCetak = \Carbon\Carbon::now('Asia/Jakarta')->format('m');
    $tahunLulus = $siswa->tahun_lulus;
    $nomorSurat = str_replace(
        ['{nomor}', '{bulan}', '{tahun}'],
        [$nomorUrut, $bulanCetak, $tahunLulus],
        $formatNomorSurat
    );

    $logoUrl = '';
    if (!empty($sekolahLogo)) {
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($sekolahLogo)) {
            $logoUrl = asset('storage/' . $sekolahLogo);
        } elseif (file_exists(public_path($sekolahLogo))) {
            $logoUrl = asset($sekolahLogo);
        }
    }

    $detailBaris = array_filter([
        $sekolahNsm  ? 'NSM : '  . $sekolahNsm  : null,
        $sekolahNpsn ? 'NPSN : ' . $sekolahNpsn : null,
    ]);

    $kota = '...........';
    if (!empty($sekolahAlamat)) {
        if (str_contains($sekolahAlamat, ',')) {
            $parts = array_map('trim', explode(',', $sekolahAlamat));
            $kota  = trim(end($parts)) ?: $kota;
        } else {
            $words = preg_split('/\s+/', trim($sekolahAlamat));
            foreach ($words as $word) {
                $up = strtoupper(rtrim($word, '.'));
                if (!in_array($up, ['JL','JLN','JALAN','NO','KP','DS','RT','RW','GG'])) {
                    $kota = $word;
                    break;
                }
            }
        }
    }

    $tahunPelajaran = $pgTahun ?: ($siswa->tahun_lulus . '/' . (intval($siswa->tahun_lulus) + 1));
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Kelulusan - {{ $siswa->nama }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Times New Roman', Times, serif;
            background: #fff;
            color: #000;
            padding: 30px 50px;
            max-width: 820px;
            margin: 0 auto;
            font-size: 12pt;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .kop { display: flex; align-items: center; gap: 18px; padding-bottom: 10px; }
        .kop-logo { width: 90px; height: 90px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .kop-logo img { width: 90px; height: 90px; object-fit: contain; }
        .kop-logo .logo-fallback { font-size: 3rem; }
        .kop-text { flex: 1; text-align: center; line-height: 1.45; }
        .kop-text .instansi-atas { font-size: 10pt; font-weight: normal; letter-spacing: .02em; }
        .kop-text .nama-sekolah  { font-size: 16pt; font-weight: 900; letter-spacing: .04em; margin: 2px 0; }
        .kop-text .detail-baris  { font-size: 10pt; margin-top: 3px; }
        .kop-text .alamat-baris  { font-size: 9.5pt; font-style: italic; margin-top: 2px; color: #222; }

        .kop-garis       { border: none; border-top: 4px solid #000; margin: 0; }
        .kop-garis-tipis { border: none; border-top: 1.5px solid #000; margin-top: 2px; margin-bottom: 14px; }

        .surat-judul {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-bottom: 4px;
            text-decoration: underline;
        }
        .nomor-surat { text-align: center; font-size: 11pt; margin-bottom: 18px; margin-top: 4px; }

        .isi p { font-size: 12pt; line-height: 1.9; text-align: justify; margin-bottom: 6px; }
        .data-table { width: 100%; margin: 10px 0 10px 20px; border-collapse: collapse; font-size: 12pt; }
        .data-table td { padding: 2px 6px; vertical-align: top; }
        .data-table td:first-child { width: 170px; }
        .data-table td:nth-child(2) { width: 12px; }

        /* Status kelulusan — satu deklarasi bersih */
        .status-kelulusan {
            text-align: center !important;
            font-size: 23px;
            font-weight: 900;
            margin: 15px auto;
            letter-spacing: .15em;
            text-transform: uppercase;
            border: 1.5px solid #000;
            width: fit-content;
            padding: 5px 30px;
        }

        .ttd-area  { display: flex; justify-content: flex-end; margin-top: 24px; }
        .ttd-box   { min-width: 230px; text-align: center; font-size: 12pt; line-height: 1.7; }
        .ttd-space { height: 72px; }

        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
            @page {
                margin: 1.8cm 2cm;
                /* Hapus header & footer bawaan browser */
                size: A4;
            }
        }
        /* Sembunyikan header/footer URL & tanggal di semua browser */
        @page { margin-top: 1.8cm; margin-bottom: 1.8cm; }
    </style>
</head>
<body>

{{-- KOP SURAT --}}
<div class="kop">
    <div class="kop-logo">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="{{ $sekolahNama }}">
        @else
            <span class="logo-fallback">🏫</span>
        @endif
    </div>
    <div class="kop-text">
        @if($sekolahInstansi)
            <div class="instansi-atas">{{ $sekolahInstansi }}</div>
        @endif
        <div class="nama-sekolah">{{ strtoupper($sekolahNama ?: 'NAMA SEKOLAH') }}</div>
        @if(count($detailBaris))
            <div class="detail-baris">{{ implode('   ', $detailBaris) }}</div>
        @endif
        @if($sekolahAkreditasi)
            <div class="detail-baris">Status : {{ $sekolahAkreditasi }}</div>
        @endif
        @if($sekolahAlamat)
            <div class="alamat-baris">Alamat : {{ $sekolahAlamat }}</div>
        @endif
        @if($sekolahTelp || $sekolahEmail || $sekolahWebsite)
            <div class="alamat-baris">
                @if($sekolahTelp)Telp. {{ $sekolahTelp }}@endif
                @if($sekolahTelp && ($sekolahEmail || $sekolahWebsite)) &nbsp;·&nbsp; @endif
                @if($sekolahEmail)Email : {{ $sekolahEmail }}@endif
                @if($sekolahEmail && $sekolahWebsite) &nbsp;·&nbsp; @endif
                @if($sekolahWebsite)Website : {{ $sekolahWebsite }}@endif
            </div>
        @endif
    </div>
</div>

<hr class="kop-garis">
<hr class="kop-garis-tipis">

{{-- JUDUL --}}
<div class="surat-judul">Surat Keterangan Kelulusan</div>
<div class="nomor-surat">Nomor : {{ $nomorSurat }}</div>

{{-- ISI --}}
<div class="isi">
    <p>Yang bertanda tangan di bawah ini, Kepala {{ $sekolahNama ?: 'Sekolah' }}, dengan ini menerangkan bahwa:</p>

    <table class="data-table">
        <tr><td>Nama Lengkap</td>    <td>:</td><td><strong>{{ $siswa->nama }}</strong></td></tr>
        <tr><td>NISN</td>            <td>:</td><td>{{ $siswa->nisn }}</td></tr>
        <tr><td>Kelas</td>           <td>:</td><td>{{ $siswa->kelas }}</td></tr>
        <tr><td>Tahun Pelajaran</td> <td>:</td><td>{{ $tahunPelajaran }}</td></tr>
        @if($siswa->nilai_rata)
        <tr><td>Nilai Rata-rata</td> <td>:</td><td>{{ number_format($siswa->nilai_rata, 2) }}</td></tr>
        @endif
    </table>

    <p>
        Berdasarkan hasil rapat Dewan Guru tentang kelulusan siswa Tahun Pelajaran {{ $tahunPelajaran }},
        dengan memperhatikan kriteria kelulusan yang ditetapkan serta pencapaian nilai Ujian Madrasah,
        maka siswa tersebut di atas dinyatakan:
    </p>

    <p class="status-kelulusan">{{ strtoupper($siswa->status) }}</p>

    <p>
        dari satuan pendidikan {{ $sekolahNama ?: 'sekolah ini' }} setelah menyelesaikan
        seluruh program pembelajaran dan memenuhi kriteria kelulusan sesuai dengan peraturan perundang-undangan
        yang berlaku.
    </p>

    <p>
        Demikian surat keterangan ini dibuat dengan sebenar-benarnya untuk dapat dipergunakan
        sebagaimana mestinya.
    </p>

    <div style="margin-top: 10px;">
        @if($siswa->catatan)
            <p style="font-size:11px; font-style:italic; color:#333; margin-bottom: 5px;">
                Catatan : {{ $siswa->catatan }}
            </p>
        @endif
        <div style="margin-top: 15px; font-size: 11px; font-style: italic; color: #555; border-top: 1px dotted #ccc; padding-top: 5px;">
            * Surat ini adalah pernyataan kelulusan sementara. Rincian nilai resmi silakan merujuk pada
            dokumen yang diterbitkan oleh PDUM / Ijazah asli.
        </div>
    </div>
</div>

{{-- TTD --}}
<div class="ttd-area">
    <div class="ttd-box">
        <p>{{ $kota }}, {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') }}</p>
        <p>Kepala {{ $sekolahNama ?: 'Sekolah' }},</p>
        <div class="ttd-space"></div>
        <p><strong>{{ $kepalaNama ?: '.........................................' }}</strong></p>
        @php $nipBersih = trim($kepalaNip, ' -'); @endphp
        <p style="font-size:11px;color:#333;">NIP. {{ $nipBersih ?: '-' }}</p>
    </div>
</div>

{{-- TOMBOL CETAK --}}
<div class="no-print" style="text-align:center;margin-top:2rem;padding-top:1rem;border-top:1px solid #eee">
    <button onclick="window.print()"
        style="background:#1a1a1a;color:#fff;border:none;padding:.65rem 2rem;border-radius:.5rem;font-size:.875rem;cursor:pointer;font-family:sans-serif">
        Cetak / Simpan PDF
    </button>
    <button onclick="window.history.back()"
        style="background:transparent;border:1px solid #ccc;color:#555;padding:.65rem 1.5rem;border-radius:.5rem;font-size:.875rem;cursor:pointer;margin-left:.5rem;font-family:sans-serif">
        Kembali
    </button>
</div>

</body>
</html>