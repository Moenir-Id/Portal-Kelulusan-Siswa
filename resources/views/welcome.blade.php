<?php
use App\Models\Setting;
use App\Models\SiswaAccount;
use Illuminate\Support\Facades\Storage;

$cfg = isset($s) && count($s) ? $s : Setting::all_map();

$sekolahNama   = $cfg['sekolah_nama']     ?? 'Sekolah';
$sekolahNpsn   = $cfg['sekolah_npsn']     ?? '';
$sekolahAlamat = $cfg['sekolah_alamat']   ?? '';
$sekolahTelp   = $cfg['sekolah_telp']     ?? '';
$sekolahEmail  = $cfg['sekolah_email']    ?? '';

$pgJudul  = $cfg['pengumuman_judul'] ?? 'Pengumuman Kelulusan';
$pgTahun  = $cfg['tahun_pelajaran']  ?? '';
$pgAktif  = ($cfg['pengumuman_aktif'] ?? '1') === '1';

$cdAktif  = ($cfg['countdown_aktif'] ?? '0') === '1';
$cdWaktu  = trim($cfg['countdown_waktu'] ?? '');
$cdLabel  = $cfg['countdown_label']  ?? 'Pengumuman akan dibuka pada:';
$pesanB   = $cfg['pesan_sebelum']    ?? '';

$cdValid = $cdAktif && $cdWaktu !== '';
$cdTime  = null;
$cdBelum = false;
if ($cdValid) {
    try {
        $cdTime  = \Carbon\Carbon::parse($cdWaktu, 'Asia/Jakarta');
        $cdBelum = now('Asia/Jakarta')->lt($cdTime);
    } catch (\Exception $e) { $cdValid = false; }
}
$locked = !$pgAktif || $cdBelum;

$logoUrl = '';
$sekolahLogo = $cfg['sekolah_logo'] ?? '';
if (!empty($sekolahLogo)) {
    try {
        if (Storage::disk('public')->exists($sekolahLogo)) {
            $logoUrl = asset('storage/' . $sekolahLogo);
        } elseif (file_exists(public_path($sekolahLogo))) {
            $logoUrl = asset($sekolahLogo);
        }
    } catch (\Exception $e) {}
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="theme-color" content="#0a0a0a">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<title><?= e($pgJudul) ?> — <?= e($sekolahNama) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;-webkit-tap-highlight-color:transparent}
:root{
  --g:#B8860B;--g1:#D4A017;--g2:#F0C040;--g3:#FDE8A0;
  --bg:#0a0a0a;--s1:#141414;--s2:#1c1c1c;--s3:#242424;
  --bd:rgba(212,160,23,.18);--bd2:rgba(212,160,23,.08);
  --t0:#F5F0E8;--t1:#B0A898;--t2:#5C5750;
  --r:rgba(220,38,38,.1);--rb:rgba(220,38,38,.2);--rt:#FCA5A5;
  --rad:16px;--rad-sm:12px;--rad-xs:8px;
  --sb:env(safe-area-inset-bottom,0px);
  --st:env(safe-area-inset-top,0px);
}
html{scroll-behavior:smooth;-webkit-text-size-adjust:100%;height:100%}
body{
  font-family:'Plus Jakarta Sans',sans-serif;
  background:var(--bg);color:var(--t0);
  min-height:100%;overflow-x:hidden;
  /* ruang untuk bottom nav di mobile */
  padding-bottom:calc(76px + var(--sb));
}
body::before{
  content:'';position:fixed;inset:0;z-index:0;pointer-events:none;
  background:
    radial-gradient(ellipse 80% 40% at 50% 0%,rgba(212,160,23,.09) 0%,transparent 60%),
    radial-gradient(ellipse 50% 50% at 85% 85%,rgba(212,160,23,.04) 0%,transparent 50%);
}

/* ── HEADER ── */
.hdr{
  position:sticky;top:0;z-index:200;
  display:flex;align-items:center;gap:10px;
  padding:10px 16px;
  padding-top:calc(10px + var(--st));
  background:rgba(10,10,10,.88);
  backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);
  border-bottom:1px solid var(--bd2);
}
.hdr-logo{
  width:38px;height:38px;flex-shrink:0;border-radius:9px;
  overflow:hidden;background:var(--s2);border:1px solid var(--bd);
  display:flex;align-items:center;justify-content:center;font-size:18px;
}
.hdr-logo img{width:100%;height:100%;object-fit:contain}
.hdr-info{flex:1;min-width:0}
.hdr-nama{font-size:13px;font-weight:600;color:var(--t0);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;line-height:1.3}
.hdr-sub{font-size:11px;color:var(--t1);margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.hdr-btn{
  flex-shrink:0;display:flex;align-items:center;gap:5px;
  padding:7px 13px;border-radius:999px;
  background:var(--bd2);border:1px solid var(--bd);
  color:var(--g2);font-size:12px;font-weight:600;
  font-family:'Plus Jakarta Sans',sans-serif;cursor:pointer;
  transition:background .15s;white-space:nowrap;
}
.hdr-btn:active{background:rgba(212,160,23,.22)}

/* ── MAIN ── */
main{
  position:relative;z-index:1;
  display:flex;flex-direction:column;align-items:center;
  padding:24px 16px 16px;
  max-width:520px;margin:0 auto;width:100%;
}

/* ── COUNTDOWN ── */
.cd-wrap{width:100%;margin-bottom:28px;text-align:center}
.cd-lbl{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:var(--t1);margin-bottom:14px;line-height:1.7}
.cd-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:7px;margin-bottom:12px}
.cd-box{background:var(--s1);border:1px solid var(--bd);border-radius:var(--rad-sm);padding:12px 4px 10px;position:relative;overflow:hidden}
.cd-box::after{content:'';position:absolute;top:0;left:15%;right:15%;height:1px;background:linear-gradient(90deg,transparent,var(--g1),transparent)}
.cd-num{font-family:'Playfair Display',serif;font-size:clamp(26px,8vw,42px);font-weight:700;line-height:1;color:var(--g2);display:block}
.cd-unit{font-size:9px;color:var(--t2);letter-spacing:.1em;text-transform:uppercase;margin-top:4px;display:block}
.cd-note{font-size:13px;color:var(--t1);line-height:1.7;background:rgba(212,160,23,.04);border:1px solid var(--bd2);border-radius:var(--rad-sm);padding:11px 14px}

/* ── HERO ── */
.hero{text-align:center;margin-bottom:24px;padding:0 4px;width:100%}
.hero-badge{
  display:inline-flex;align-items:center;gap:6px;
  background:var(--bd2);border:1px solid var(--bd);border-radius:999px;
  padding:5px 14px;font-size:10px;letter-spacing:.12em;
  text-transform:uppercase;color:var(--g2);font-weight:600;
  margin-bottom:12px;
}
.hero-title{
  font-family:'Playfair Display',serif;
  font-size:clamp(30px,9vw,52px);font-weight:900;
  line-height:1.08;color:var(--t0);margin-bottom:10px;
}
.hero-em{
  background:linear-gradient(135deg,var(--g),var(--g2),var(--g3));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.hero-sub{font-size:14px;color:var(--t1);line-height:1.7;max-width:340px;margin:0 auto}

/* ── SEARCH CARD ── */
.search-card{
  width:100%;background:var(--s1);border:1px solid var(--bd);
  border-radius:var(--rad);padding:20px;
  box-shadow:0 24px 56px rgba(0,0,0,.45);
  margin-bottom:10px;
}
.card-lbl{font-size:10px;letter-spacing:.13em;text-transform:uppercase;color:var(--g1);font-weight:600;display:block;margin-bottom:10px}
.input-wrap{position:relative;margin-bottom:10px}
.s-ico{position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--t2);pointer-events:none}
.search-inp{
  width:100%;background:rgba(255,255,255,.05);
  border:1.5px solid rgba(255,255,255,.09);border-radius:var(--rad-sm);
  padding:14px 14px 14px 40px;
  color:var(--t0);font-family:'Plus Jakarta Sans',sans-serif;
  font-size:16px;/* 16px cegah zoom iOS */
  outline:none;-webkit-appearance:none;
  transition:border-color .2s,box-shadow .2s;
}
.search-inp:focus{border-color:var(--g1);box-shadow:0 0 0 3px rgba(212,160,23,.12)}
.search-inp::placeholder{color:var(--t2)}
.search-inp:disabled{opacity:.3;cursor:not-allowed}
.btn-cek{
  width:100%;border:none;border-radius:var(--rad-sm);padding:14px;
  background:linear-gradient(135deg,var(--g),var(--g2));
  color:#1a0e00;font-family:'Plus Jakarta Sans',sans-serif;
  font-size:15px;font-weight:700;cursor:pointer;
  display:flex;align-items:center;justify-content:center;gap:8px;
  transition:opacity .2s,transform .1s;
}
.btn-cek:active{transform:scale(.98)}
.btn-cek:disabled{opacity:.3;cursor:not-allowed;transform:none}
.hint-txt{font-size:12px;color:var(--t2);text-align:center;margin-top:8px;line-height:1.6}
.err-box{background:var(--r);border:1px solid var(--rb);border-radius:var(--rad-xs);padding:10px 14px;color:var(--rt);font-size:13px;margin-top:10px}

/* ── LOGIN PANEL ── */
.lp-wrap{width:100%;background:var(--s1);border:1px solid var(--bd2);border-radius:var(--rad);overflow:hidden;margin-bottom:10px}
.lp-toggle{
  width:100%;display:flex;align-items:center;gap:10px;
  padding:14px 18px;background:transparent;border:none;
  color:var(--t1);font-family:'Plus Jakarta Sans',sans-serif;
  cursor:pointer;text-align:left;transition:background .15s;
}
.lp-toggle:active{background:rgba(255,255,255,.04)}
.lp-icon{
  width:34px;height:34px;border-radius:9px;flex-shrink:0;
  background:var(--bd2);border:1px solid var(--bd);
  display:flex;align-items:center;justify-content:center;font-size:15px;
}
.lp-txt{flex:1}
.lp-ttl{font-size:13px;font-weight:600;color:var(--t0);line-height:1.3}
.lp-sub{font-size:11px;color:var(--t2);margin-top:1px}
.lp-chev{font-size:10px;color:var(--t2);flex-shrink:0;transition:transform .3s cubic-bezier(.4,0,.2,1)}
.lp-chev.open{transform:rotate(180deg)}

.lp-body{max-height:0;overflow:hidden;transition:max-height .42s cubic-bezier(.4,0,.2,1)}
.lp-body.open{max-height:640px}
.lp-inner{padding:0 18px 20px;border-top:1px solid var(--bd2)}

/* Tab switcher */
.tab-row{display:grid;grid-template-columns:1fr 1fr;gap:4px;background:rgba(255,255,255,.04);border-radius:var(--rad-xs);padding:4px;margin:16px 0}
.tab-btn{
  padding:10px;border-radius:6px;border:none;
  background:transparent;color:var(--t1);
  font-size:13px;font-weight:600;font-family:'Plus Jakarta Sans',sans-serif;
  cursor:pointer;transition:all .2s;
  display:flex;align-items:center;justify-content:center;gap:5px;
}
.tab-btn.act-admin{background:linear-gradient(135deg,#4338ca,#7c3aed);color:#fff}
.tab-btn.act-siswa{background:linear-gradient(135deg,var(--g),var(--g2));color:#1a0e00}

/* Field */
.fg{margin-bottom:13px}
.fg-lbl{font-size:10px;letter-spacing:.11em;text-transform:uppercase;color:var(--g1);font-weight:600;display:block;margin-bottom:6px}
.fg-inp{
  width:100%;background:rgba(255,255,255,.05);
  border:1.5px solid rgba(255,255,255,.09);border-radius:var(--rad-xs);
  padding:12px 14px;color:var(--t0);font-family:'Plus Jakarta Sans',sans-serif;
  font-size:16px;outline:none;-webkit-appearance:none;
  transition:border-color .2s,box-shadow .2s;
}
.fg-inp:focus{border-color:var(--g1);box-shadow:0 0 0 3px rgba(212,160,23,.1)}
.fg-inp::placeholder{color:var(--t2)}
.pw-wrap{position:relative}
.pw-eye{
  position:absolute;right:12px;top:50%;transform:translateY(-50%);
  background:transparent;border:none;color:var(--t2);cursor:pointer;
  padding:6px;font-size:15px;
}
.rem-row{display:flex;align-items:center;gap:8px;margin-bottom:13px;cursor:pointer}
.rem-row input{accent-color:var(--g1);width:16px;height:16px;cursor:pointer}
.rem-row span{font-size:13px;color:var(--t1)}
.btn-sub{
  width:100%;border:none;border-radius:var(--rad-sm);padding:13px;
  font-family:'Plus Jakarta Sans',sans-serif;font-size:15px;font-weight:700;
  cursor:pointer;transition:opacity .2s,transform .1s;
  display:flex;align-items:center;justify-content:center;gap:8px;
}
.btn-sub:active{transform:scale(.98)}
.btn-sub.admin{background:linear-gradient(135deg,#4338ca,#7c3aed);color:#fff}
.btn-sub.siswa{background:linear-gradient(135deg,var(--g),var(--g2));color:#1a0e00}
.sub-hint{font-size:11px;color:var(--t2);text-align:center;margin-top:9px;line-height:1.6}
.lf-err{background:var(--r);border:1px solid var(--rb);border-radius:var(--rad-xs);padding:10px 14px;color:var(--rt);font-size:13px;margin-bottom:12px}

/* ── HASIL ── */
.results{width:100%;margin-top:20px}
.res-meta{font-size:12px;color:var(--t1);text-align:center;margin-bottom:14px;line-height:1.6}
.res-meta strong{color:var(--t0);font-weight:600}

.sv-card{background:var(--s1);border-radius:var(--rad);border:1px solid var(--bd);margin-bottom:14px;overflow:hidden;position:relative}
.sv-accent{height:3px;width:100%}
.sv-accent.lulus{background:linear-gradient(90deg,var(--g),var(--g2),var(--g3))}
.sv-accent.tidak{background:linear-gradient(90deg,#991b1b,#dc2626,#f87171)}
.sv-body{padding:18px}
.sv-wm{position:absolute;right:-16px;bottom:-16px;width:110px;height:110px;opacity:.03;pointer-events:none;object-fit:contain}

.sv-status{display:flex;align-items:flex-start;gap:11px;padding:13px;border-radius:var(--rad-sm);margin-bottom:14px}
.sv-status.lulus{background:rgba(212,160,23,.07);border:1px solid rgba(212,160,23,.17)}
.sv-status.tidak{background:var(--r);border:1px solid var(--rb)}
.sv-ico{font-size:20px;flex-shrink:0;margin-top:1px}
.sv-stl{font-size:10px;letter-spacing:.13em;text-transform:uppercase;font-weight:700;margin-bottom:3px}
.sv-status.lulus .sv-stl{color:var(--g2)}
.sv-status.tidak .sv-stl{color:var(--rt)}
.sv-std{font-size:12px;color:var(--t1);line-height:1.6}

.sv-nama{font-family:'Playfair Display',serif;font-size:20px;font-weight:700;line-height:1.2;margin-bottom:14px;color:var(--t0)}

.det-list{margin-bottom:12px}
.det-row{display:flex;align-items:baseline;gap:8px;padding:6px 0;border-bottom:1px solid rgba(255,255,255,.05);font-size:13px}
.det-row:last-child{border-bottom:none}
.dk{color:var(--t1);flex-shrink:0;width:42%}
.dv{color:var(--t0);font-weight:500;flex:1}
.dv.lulus{color:var(--g2);font-weight:700}
.dv.tidak{color:var(--rt);font-weight:700}
.dv.nilai{color:var(--g2);font-weight:700}

.sv-cat{font-size:12px;color:var(--t1);line-height:1.65;padding:9px 12px;background:rgba(255,255,255,.03);border-radius:var(--rad-xs);margin-bottom:11px;border-left:2px solid var(--bd)}
.sv-motiv{font-size:12px;color:var(--t2);line-height:1.75;font-style:italic;margin-bottom:12px}
.sv-note{font-size:11px;color:var(--t2);line-height:1.65;padding:9px 12px;border-radius:var(--rad-xs);background:rgba(212,160,23,.04);border:1px dashed rgba(212,160,23,.14);margin-bottom:12px}

.sv-acts{display:flex;flex-direction:column;gap:8px;padding-top:12px;border-top:1px solid rgba(255,255,255,.06)}
.btn-a{
  display:flex;align-items:center;justify-content:center;gap:7px;
  padding:12px 14px;border-radius:var(--rad-sm);
  font-size:13px;font-weight:600;cursor:pointer;
  text-decoration:none;border:none;
  font-family:'Plus Jakarta Sans',sans-serif;
  transition:opacity .15s,transform .1s;width:100%;
}
.btn-a:active{transform:scale(.98)}
.btn-a.wa{background:rgba(37,211,102,.09);border:1px solid rgba(37,211,102,.2);color:#4ade80}
.btn-a.skl{background:rgba(212,160,23,.09);border:1px solid var(--bd);color:var(--g2)}
.btn-a.momen{background:linear-gradient(135deg,rgba(212,160,23,.11),rgba(240,192,64,.05));border:1px solid var(--bd);color:var(--g2)}
.btn-a.back{background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);color:var(--t1)}
.act-2col{display:grid;grid-template-columns:1fr 1fr;gap:8px}

/* ── EMPTY STATE ── */
.empty{text-align:center;padding:32px 16px;color:var(--t1)}
.empty-ico{font-size:34px;display:block;margin-bottom:10px}
.empty-ttl{font-size:15px;font-weight:600;color:var(--t0);margin-bottom:6px}
.empty-sub{font-size:12px;color:var(--t2);line-height:1.7}

/* ── CONFETTI ── */
.cfp{position:fixed;top:-12px;border-radius:2px;opacity:0;pointer-events:none;z-index:999;animation:cff linear forwards}
@keyframes cff{0%{opacity:1;transform:translateY(0) rotate(0deg)}100%{opacity:0;transform:translateY(100vh) rotate(720deg)}}

/* ── BOTTOM NAV (mobile) ── */
.bnav{
  position:fixed;bottom:0;left:0;right:0;z-index:300;
  background:rgba(10,10,10,.94);
  backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);
  border-top:1px solid var(--bd2);
  padding:10px 16px calc(10px + var(--sb));
  display:flex;gap:8px;
}
.bnav-cek{
  flex:1;border:none;border-radius:var(--rad-sm);padding:13px;
  background:linear-gradient(135deg,var(--g),var(--g2));
  color:#1a0e00;font-family:'Plus Jakarta Sans',sans-serif;
  font-size:14px;font-weight:700;cursor:pointer;transition:opacity .15s;
}
.bnav-cek:disabled{opacity:.3;cursor:not-allowed}
.bnav-masuk{
  flex-shrink:0;border:1px solid var(--bd);border-radius:var(--rad-sm);
  padding:13px 16px;background:var(--bd2);
  color:var(--g2);font-family:'Plus Jakarta Sans',sans-serif;
  font-size:13px;font-weight:600;cursor:pointer;
  display:flex;align-items:center;gap:5px;
  white-space:nowrap;transition:background .15s;
}
.bnav-masuk:active{background:rgba(212,160,23,.2)}

/* ── RESPONSIVE ── */
@media(min-width:600px){
  body{padding-bottom:0}
  .bnav{display:none}
  main{padding:36px 24px 36px}
  .sv-acts{flex-direction:row;flex-wrap:wrap}
  .btn-a{width:auto;flex:1;min-width:140px}
  .act-2col{grid-template-columns:1fr 1fr}
}

/* ── ANIMATIONS ── */
@keyframes fadeUp{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}
@keyframes cardIn{to{opacity:1;transform:translateY(0)}}
.au1{animation:fadeUp .55s ease both}
.au2{animation:fadeUp .55s ease .08s both}
.au3{animation:fadeUp .55s ease .16s both}
.au4{animation:fadeUp .55s ease .24s both}
.au5{animation:fadeUp .55s ease .32s both}
.ac1{opacity:0;transform:translateY(12px);animation:cardIn .4s ease .04s forwards}
.ac2{opacity:0;transform:translateY(12px);animation:cardIn .4s ease .11s forwards}
.ac3{opacity:0;transform:translateY(12px);animation:cardIn .4s ease .18s forwards}
.acn{opacity:0;transform:translateY(12px);animation:cardIn .4s ease .24s forwards}
</style>
</head>
<body>

<!-- ── HEADER ── -->
<header class="hdr">
  <div class="hdr-logo">
    <?php if ($logoUrl): ?><img src="<?= e($logoUrl) ?>" alt=""><?php else: ?>🏫<?php endif; ?>
  </div>
  <div class="hdr-info">
    <div class="hdr-nama"><?= e($sekolahNama) ?></div>
    <?php if ($sekolahNpsn || $sekolahAlamat): ?>
      <div class="hdr-sub"><?= $sekolahNpsn ? 'NPSN ' . e($sekolahNpsn) : e($sekolahAlamat) ?></div>
    <?php endif; ?>
  </div>
  <button class="hdr-btn" onclick="openLogin()" id="hdrBtn">
    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
    Masuk
  </button>
</header>

<main>

  <!-- ── COUNTDOWN ── -->
  <?php if ($cdValid && $cdBelum): ?>
  <div class="cd-wrap au1">
    <p class="cd-lbl"><?= e($cdLabel) ?></p>
    <div class="cd-grid">
      <div class="cd-box"><span class="cd-num" id="cdH">--</span><span class="cd-unit">Hari</span></div>
      <div class="cd-box"><span class="cd-num" id="cdJ">--</span><span class="cd-unit">Jam</span></div>
      <div class="cd-box"><span class="cd-num" id="cdM">--</span><span class="cd-unit">Menit</span></div>
      <div class="cd-box"><span class="cd-num" id="cdD">--</span><span class="cd-unit">Detik</span></div>
    </div>
    <?php if ($pesanB): ?><div class="cd-note"><?= e($pesanB) ?></div><?php endif; ?>
  </div>
  <?php endif; ?>

  <!-- ── HASIL PENCARIAN ── -->
  @isset($siswa)
  <div class="results">
    @if($siswa->count())
      <p class="res-meta"><strong>{{ $siswa->count() }} peserta didik</strong> untuk "<strong>{{ $cari }}</strong>"</p>
      @foreach($siswa as $i => $sv)
        <?php
          $isLulus   = $sv->status === 'LULUS';
          $ac        = ['ac1','ac2','ac3'][$i] ?? 'acn';
          $seed      = array_sum(str_split(preg_replace('/\D/','',$sv->nisn))) + strlen($sv->nama);
          $mPool     = $isLulus ? [
            '"' . $sv->nama . ', dedikasi dan ketekunanmu telah membuahkan hasil luar biasa. Teruslah melangkah!"',
            '"Selamat, ' . $sv->nama . '! Kelulusan ini adalah awal dari babak baru yang penuh kemungkinan."',
            '"Barakallah, ' . $sv->nama . '. Almamater bangga atas pencapaianmu yang luar biasa ini."',
            '"' . $sv->nama . ', satu pintu besar telah terbuka. Tunjukkan kepada dunia apa yang kamu mampu!"',
          ] : [
            '"' . $sv->nama . ', setiap perjalanan punya waktunya. Segera hubungi Wali Kelas untuk langkah selanjutnya."',
            '"Tetaplah optimis, ' . $sv->nama . '. Hambatan hari ini adalah pelajaran yang membentukmu lebih kuat."',
            '"Tetap semangat, ' . $sv->nama . '. Kesuksesan sejati ditentukan seberapa gigih kamu tidak menyerah."',
          ];
          $motiv = $mPool[$seed % count($mPool)];
          $wa    = urlencode(
            '🎓 *Pengumuman Kelulusan*' . "\n" . '*' . $sekolahNama . '*' . "\n\n" .
            '📋 Nama  : ' . $sv->nama . "\n" .
            '🔢 NISN  : ' . $sv->nisn . "\n" .
            '🏫 Kelas : ' . $sv->kelas . "\n" .
            '📅 TP    : ' . ($pgTahun ?: ($sv->tahun_lulus . '/' . ($sv->tahun_lulus + 1))) . "\n\n" .
            '✅ Status : *' . $sv->status . '*' .
            ($sv->catatan ? "\n📝 " . $sv->catatan : '') . "\n\n" .
            '🔗 ' . url('/')
          );
          $punyaAkun = SiswaAccount::where('nisn', $sv->nisn)->exists();
        ?>
        <div class="sv-card <?= $ac ?>" <?= $isLulus ? 'data-cf="1"' : '' ?>>
          <div class="sv-accent <?= $isLulus ? 'lulus' : 'tidak' ?>"></div>
          <?php if ($logoUrl): ?><img class="sv-wm" src="<?= e($logoUrl) ?>" alt=""><?php endif; ?>
          <div class="sv-body">
            <div class="sv-status <?= $isLulus ? 'lulus' : 'tidak' ?>">
              <span class="sv-ico"><?= $isLulus ? '🎉' : '📋' ?></span>
              <div>
                <div class="sv-stl"><?= $isLulus ? 'Selamat! Dinyatakan Lulus' : 'Belum Ada Informasi Lulus' ?></div>
                <div class="sv-std">
                  <?= $isLulus
                    ? 'Barakallah — ' . e($sekolahNama) . ', TP ' . ($pgTahun ?: ($sv->tahun_lulus . '/' . ($sv->tahun_lulus + 1)))
                    : 'Hubungi Administrasi Madrasah untuk informasi lebih lanjut.' ?>
                </div>
              </div>
            </div>
            <div class="sv-nama">{{ $sv->nama }}</div>
            <div class="det-list">
              <div class="det-row"><span class="dk">NISN</span><span class="dv">{{ $sv->nisn }}</span></div>
              <div class="det-row"><span class="dk">Kelas</span><span class="dv">{{ $sv->kelas }}</span></div>
              <div class="det-row"><span class="dk">Tahun Pelajaran</span><span class="dv">{{ $pgTahun ?: ($sv->tahun_lulus.'/'.(intval($sv->tahun_lulus)+1)) }}</span></div>
              @if($sv->nilai_rata)
              <div class="det-row"><span class="dk">Nilai Rata-rata</span><span class="dv nilai">{{ number_format($sv->nilai_rata,2) }}</span></div>
              @endif
              <div class="det-row"><span class="dk">Status</span><span class="dv <?= $isLulus ? 'lulus' : 'tidak' ?>">{{ $sv->status }}</span></div>
            </div>
            @if($sv->catatan)
              <div class="sv-cat">📝 {{ $sv->catatan }}</div>
            @endif
            <div class="sv-motiv"><?= $motiv ?></div>
            @if($isLulus)
              <div class="sv-note">📄 Unduh Surat Keterangan Lulus (SKL) sementara di bawah sebagai bukti resmi.</div>
            @endif
            <div class="sv-acts">
              @if($isLulus)
                <div class="act-2col">
                  <a class="btn-a skl" href="{{ route('siswa.surat', $sv) }}" target="_blank" rel="noopener">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                    Unduh SKL
                  </a>
                  <a class="btn-a momen" href="{{ $punyaAkun ? route('siswa.dashboard') : route('siswa.register') }}">
                    📸 Bagikan Momen
                  </a>
                </div>
              @endif
              <a class="btn-a wa" href="https://wa.me/?text={{ $wa }}" target="_blank" rel="noopener">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                Bagikan ke WhatsApp
              </a>
              <button class="btn-a back" onclick="scrollToCek()">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                Cari Siswa Lain
              </button>
            </div>
          </div>
        </div>
      @endforeach
    @else
      <div class="empty">
        <span class="empty-ico">🔍</span>
        <div class="empty-ttl">Data Tidak Ditemukan</div>
        <div class="empty-sub">Periksa NISN atau nama lengkap.<br>Pastikan sesuai data pokok sekolah.</div>
      </div>
    @endif
  </div>
  @endisset

  <!-- ── HERO ── -->
  <div class="hero au2">
    <div class="hero-badge">🎓 <?= $pgTahun ? 'TP ' . e($pgTahun) : 'Tahun Pelajaran 2025/2026' ?></div>
    <h1 class="hero-title">Pengumuman<br><span class="hero-em">Kelulusan</span></h1>
    <p class="hero-sub">Masukkan NISN atau nama untuk mengetahui status kelulusan Anda.</p>
  </div>

  <!-- ── SEARCH CARD ── -->
  <div class="search-card au3" id="searchCard">
    <label class="card-lbl" for="inputCari">Pencarian Siswa</label>
    <form action="{{ route('cek') }}" method="POST" id="formCek">
      @csrf
      <div class="input-wrap">
        <svg class="s-ico" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input class="search-inp" type="search" name="cari" id="inputCari"
          placeholder="<?= $locked ? 'Pengumuman belum dibuka…' : 'Ketik NISN atau nama…' ?>"
          value="{{ old('cari', $cari ?? '') }}"
          autocomplete="off" enterkeyhint="search"
          <?= $locked ? 'disabled' : '' ?>>
      </div>
      <button class="btn-cek" type="submit" id="btnCek" <?= $locked ? 'disabled' : '' ?>>
        <span id="btnLbl">Lihat Hasil Kelulusan</span>
        <span id="btnLd" style="display:none">⏳ Memproses…</span>
      </button>
      <?php if (!$locked): ?><p class="hint-txt">Contoh: <em>0012345678</em> atau <em>Budi Santoso</em></p><?php endif; ?>
    </form>
    @error('cari')<div class="err-box">{{ $message }}</div>@enderror
  </div>

  <!-- ── LOGIN PANEL ── -->
  <div class="lp-wrap au4" id="lpWrap">
    <button class="lp-toggle" onclick="toggleLogin()" id="lpBtn" aria-expanded="false">
      <div class="lp-icon">🔐</div>
      <div class="lp-txt">
        <div class="lp-ttl">Masuk ke Portal</div>
        <div class="lp-sub">Admin atau Siswa Portal</div>
      </div>
      <span class="lp-chev" id="lpChev">▼</span>
    </button>
    <div class="lp-body" id="lpBody">
      <div class="lp-inner">
        @if($errors->has('identifier'))
          <div class="lf-err">{{ $errors->first('identifier') }}</div>
        @endif
        <div class="tab-row" role="tablist">
          <button class="tab-btn" id="tA" onclick="switchTab('admin')" role="tab">🔐 Admin</button>
          <button class="tab-btn" id="tS" onclick="switchTab('siswa')" role="tab">🎓 Siswa</button>
        </div>
        <form action="{{ route('login') }}" method="POST" id="loginForm">
          @csrf
          <div class="fg" id="fE">
            <label class="fg-lbl" for="inE">Email Admin</label>
            <input class="fg-inp" type="email" name="identifier" id="inE"
              placeholder="contoh@contoh.com"
              value="{{ old('identifier') }}"
              autocomplete="email" inputmode="email">
          </div>
          <div class="fg" id="fN" style="display:none">
            <label class="fg-lbl" for="inN">NISN</label>
            <input class="fg-inp" type="text" name="identifier_n" id="inN"
              placeholder="10 digit NISN"
              autocomplete="username" inputmode="numeric" maxlength="20">
          </div>
          <div class="fg">
            <label class="fg-lbl" for="inP">Password</label>
            <div class="pw-wrap">
              <input class="fg-inp" type="password" name="password" id="inP"
                placeholder="Masukkan password"
                autocomplete="current-password"
                style="padding-right:44px">
              <button type="button" class="pw-eye" onclick="togglePw()" id="pwE" aria-label="Tampilkan password">👁</button>
            </div>
          </div>
          <label class="rem-row">
            <input type="checkbox" name="remember" value="1">
            <span>Ingat saya</span>
          </label>
          <button class="btn-sub" type="submit" id="btnSub">Masuk sebagai Admin</button>
          <p class="sub-hint" id="sH">Gunakan email dan password akun admin.</p>
        </form>
      </div>
    </div>
  </div>

</main>

<!-- ── BOTTOM NAV ── -->
<div class="bnav">
  <button class="bnav-cek" id="bnavCek" onclick="scrollToCek()" <?= $locked ? 'disabled' : '' ?>>
    🔍 Cek Kelulusan
  </button>
  <button class="bnav-masuk" onclick="openLogin()">
    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
    Masuk
  </button>
</div>

<!-- ── SCRIPTS ── -->
<?php if ($cdBelum && $cdTime): ?>
<script>
(function(){
  var t=<?= $cdTime->timestamp * 1000 ?>;
  function pad(n){return String(n).padStart(2,'0')}
  function tick(){
    var d=t-Date.now();
    if(d<=0){var s=location.search?'&':'?';location.replace(location.pathname+location.search+s+'bust='+Date.now());return}
    document.getElementById('cdH').textContent=pad(Math.floor(d/86400000));
    document.getElementById('cdJ').textContent=pad(Math.floor((d%86400000)/3600000));
    document.getElementById('cdM').textContent=pad(Math.floor((d%3600000)/60000));
    document.getElementById('cdD').textContent=pad(Math.floor((d%60000)/1000));
  }
  tick();setInterval(tick,1000);
})();
</script>
<?php endif; ?>

<script>
var _lOpen=false,_tab='admin';

function toggleLogin(){_lOpen?closeLogin():openLogin()}

function openLogin(){
  _lOpen=true;
  document.getElementById('lpBody').classList.add('open');
  document.getElementById('lpBtn').setAttribute('aria-expanded','true');
  document.getElementById('lpChev').classList.add('open');
  document.getElementById('hdrBtn').style.opacity='.4';
  setTimeout(function(){
    document.getElementById('lpWrap').scrollIntoView({behavior:'smooth',block:'nearest'});
  },80);
}

function closeLogin(){
  _lOpen=false;
  document.getElementById('lpBody').classList.remove('open');
  document.getElementById('lpBtn').setAttribute('aria-expanded','false');
  document.getElementById('lpChev').classList.remove('open');
  document.getElementById('hdrBtn').style.opacity='1';
}

function switchTab(t){
  _tab=t;
  var tA=document.getElementById('tA'),tS=document.getElementById('tS');
  var fE=document.getElementById('fE'),fN=document.getElementById('fN');
  var iE=document.getElementById('inE'),iN=document.getElementById('inN');
  var bs=document.getElementById('btnSub'),sh=document.getElementById('sH');
  tA.className='tab-btn';tS.className='tab-btn';
  if(t==='admin'){
    tA.classList.add('act-admin');
    fE.style.display='';fN.style.display='none';
    iE.name='identifier';iN.name='identifier_n';
    bs.className='btn-sub admin';bs.textContent='Masuk sebagai Admin';
    sh.textContent='Gunakan email dan password akun admin.';
  }else{
    tS.classList.add('act-siswa');
    fN.style.display='';fE.style.display='none';
    iN.name='identifier';iE.name='identifier_e';
    bs.className='btn-sub siswa';bs.textContent='Masuk sebagai Siswa';
    sh.textContent='Gunakan NISN 10 digit dan password dari sekolah.';
  }
}
// init tab
switchTab('admin');

function togglePw(){
  var i=document.getElementById('inP'),e=document.getElementById('pwE');
  i.type=i.type==='password'?'text':'password';
  e.textContent=i.type==='password'?'👁':'🙈';
}

function scrollToCek(){
  var el=document.getElementById('searchCard');
  if(!el)return;
  window.scrollTo({top:el.getBoundingClientRect().top+window.scrollY-72,behavior:'smooth'});
  setTimeout(function(){var i=document.getElementById('inputCari');if(i&&!i.disabled)i.focus()},380);
}

// Auto-buka panel + pilih tab jika ada error login
<?php if ($errors->has('identifier')): ?>
openLogin();
(function(){
  var old='{{ old("identifier","") }}';
  if(old&&!old.includes('@'))switchTab('siswa');
})();
<?php endif; ?>
</script>

<?php if (!$locked): ?>
<script>
// Loading state
(function(){
  var f=document.getElementById('formCek'),b=document.getElementById('btnCek');
  if(!f)return;
  f.addEventListener('submit',function(){
    if(!document.getElementById('inputCari').value.trim())return;
    document.getElementById('btnLbl').style.display='none';
    document.getElementById('btnLd').style.display='inline';
    b.disabled=true;b.style.opacity='.75';
  });
})();

// Confetti
(function(){
  if(!document.querySelector('[data-cf="1"]'))return;
  var c=['#D4A017','#F0C040','#FDE8A0','#fff','#fde68a','#fbbf24'];
  function burst(n){
    for(var i=0;i<n;i++){
      var el=document.createElement('div');
      el.className='cfp';
      el.style.cssText='left:'+Math.random()*100+'vw;background:'+c[~~(Math.random()*c.length)]+
        ';width:'+(5+Math.random()*6)+'px;height:'+(9+Math.random()*8)+'px'+
        ';animation-duration:'+(1.4+Math.random()*2)+'s;animation-delay:'+(Math.random()*.9)+'s';
      document.body.appendChild(el);
      setTimeout(function(e){e.remove()},4000,el);
    }
  }
  setTimeout(function(){burst(50)},320);
  setTimeout(function(){burst(30)},1050);
})();
</script>
<?php endif; ?>
</body>
</html>