@php
    $isLulus        = $siswa->status === 'LULUS';
    $isBersyarat    = $siswa->status === 'LULUS BERSYARAT';
    $logo           = $setting['sekolah_logo'] ?? '';
    $logoUrl        = '';
    if ($logo) {
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($logo)) {
            $logoUrl = asset('storage/' . $logo);
        } elseif (file_exists(public_path($logo))) {
            $logoUrl = asset($logo);
        }
    }
    $sekolahNama   = $setting['sekolah_nama'] ?? 'Sekolah';
    $pgTahun       = $setting['tahun_pelajaran'] ?? '';
    $fotoProfilUrl = $siswa->foto_profil_url;

    $quotes = [
        'Selamat atas kelulusanmu. Pencapaian ini adalah bukti nyata dari kerja keras dan dedikasi yang telah kamu tunjukkan selama ini.',
        'Kami mengucapkan selamat atas keberhasilanmu menyelesaikan studi. Semoga langkah ini menjadi awal dari perjalanan yang lebih gemilang.',
        'Selamat! Kelulusanmu adalah kebanggaan kami. Terima kasih telah menjadi bagian dari keluarga besar sekolah ini.',
        'Dengan bangga kami mengucapkan selamat atas kelulusanmu. Kamu telah membuktikan bahwa ketekunan adalah kunci keberhasilan.',
        'Selamat menyelesaikan satu fase penting dalam hidupmu. Kami bangga menyaksikan pertumbuhanmu hingga hari ini.',
        'Kelulusanmu adalah hasil dari perjuangan panjang yang tidak pernah kamu sia-siakan. Selamat dan teruslah berprestasi.',
        'Kami turut bersyukur atas keberhasilanmu. Selamat lulus — kamu telah mengharumkan nama sekolah dengan pencapaianmu.',
        'Selamat atas kelulusanmu yang membanggakan. Jadikan momen ini sebagai titik tolak menuju cita-cita yang lebih tinggi.',
        'Kelulusan ini bukanlah akhir dari perjalanan, melainkan awal dari babak baru yang penuh dengan peluang dan kemungkinan.',
        'Di hadapanmu terbentang masa depan yang cerah. Melangkahlah dengan penuh keyakinan dan keberanian.',
        'Dunia membutuhkan generasi muda yang cerdas, berakhlak, dan berdedikasi. Jadilah bagian dari perubahan yang positif.',
        'Setiap langkah yang kamu ambil hari ini adalah fondasi bagi kesuksesanmu di masa depan. Teruslah melangkah dengan mantap.',
        'Jadilah pribadi yang tidak hanya sukses untuk diri sendiri, tetapi juga bermanfaat bagi masyarakat dan bangsa.',
        'Masa depan yang cerah menanti mereka yang berani bermimpi dan tidak pernah berhenti berusaha. Kamu adalah salah satunya.',
        'Tantangan di depan mungkin tidak mudah, namun kami percaya kamu memiliki semua yang dibutuhkan untuk menghadapinya.',
        'Bawalah nilai-nilai yang telah kamu pelajari di sini sebagai bekal menuju kehidupan yang lebih luas dan penuh makna.',
        'Jangan pernah membatasi dirimu sendiri. Potensimu jauh lebih besar dari yang kamu bayangkan saat ini.',
        'Setiap mimpi besar layak untuk diperjuangkan. Kami percaya kamu mampu mewujudkan semua impianmu.',
        'Alhamdulillah, atas rahmat dan karunia Allah SWT, kamu berhasil menyelesaikan studi dengan baik. Semoga ilmumu menjadi amal jariyah.',
        'Segala puji bagi Allah SWT yang telah meridhoi perjalanan belajarmu hingga hari ini. Selamat atas kelulusanmu.',
        'Barakallahu fiik. Semoga ilmu yang kamu peroleh menjadi cahaya yang menerangi jalanmu dan orang-orang di sekitarmu.',
        'Alhamdulillah, perjuanganmu berbuah hasil yang membanggakan. Semoga Allah SWT senantiasa membimbingmu di setiap langkah.',
        'Semoga kelulusanmu menjadi berkah yang membawa kebaikan, tidak hanya bagimu, tetapi juga bagi keluarga dan masyarakat.',
        'Dengan mengucap syukur kepada Allah SWT, kami menyampaikan selamat atas kelulusanmu. Tetaplah rendah hati dan teruslah bermanfaat.',
        'Semoga Allah SWT meridhoi setiap langkah perjalananmu ke depan dan menjadikan ilmumu sebagai amal yang mengalir pahalanya.',
        'Ilmu yang bermanfaat adalah investasi terbaik. Semoga apa yang kamu pelajari di sini menjadi bekal hidupmu dunia dan akhirat.',
        'Alhamdulillah, kamu telah menyelesaikan amanah belajar dengan baik. Semoga Allah SWT memudahkan setiap urusanmu ke depan.',
        'Kelulusan ini adalah pencapaian yang patut dirayakan, namun ingatlah bahwa belajar adalah perjalanan yang tidak pernah berakhir.',
        'Semangat belajar yang kamu tunjukkan selama ini adalah modal terbesar yang kamu miliki. Jangan pernah padamkan semangat itu.',
        'Ilmu pengetahuan terus berkembang. Jadilah pribadi yang selalu haus akan ilmu dan tidak pernah puas dengan pencapaian yang ada.',
        'Di luar sana masih banyak hal yang menunggumu untuk dipelajari. Teruslah tumbuh dan berkembang setiap harinya.',
        'Alhamdulillah, kamu telah menyelesaikan amanah belajar dengan baik. Semoga ilmu yang kamu peroleh menjadi cahaya yang menerangi jalanmu, keluargamu, dan orang-orang di sekitarmu. Kami bangga dengan perjalananmu yang tidak mudah ini — teruslah istiqomah.',
        'Selamat lulus. Kini saatnya kamu membawa semangat belajar ini ke jenjang dan bidang yang lebih luas.',
        'Orang yang terus belajar adalah orang yang terus tumbuh. Jadilah pelajar sepanjang hayat demi masa depan yang lebih baik.',
        'Pengetahuan adalah senjata paling ampuh yang bisa kamu gunakan untuk mengubah hidupmu dan dunia di sekitarmu.',
        'Jadikan setiap pengalaman hidupmu sebagai pelajaran berharga. Karena kehidupan sendiri adalah sekolah yang tidak pernah usai.',
        'Sekolah ini akan selalu menjadi rumah bagimu. Kemanapun kamu melangkah, kami selalu mendoakan yang terbaik untukmu.',
        'Kami melepasmu dengan penuh kebanggaan dan harapan. Jadilah alumni yang membawa nama baik almamatermu.',
        'Terima kasih telah menjadi bagian dari perjalanan sekolah ini. Kehadiranmu telah meninggalkan jejak yang berarti bagi kami.',
        'Kami mendoakan agar setiap langkahmu ke depan selalu diiringi kemudahan, kesehatan, dan keberkahan dari Allah SWT.',
        'Seluruh keluarga besar sekolah ini mengucapkan selamat dan menyampaikan rasa bangga yang sebesar-besarnya atas kelulusanmu.',
        'Kamu telah menyelesaikan tugasmu sebagai siswa dengan baik. Kini dunia menantimu untuk mengerjakan tugas-tugas yang lebih besar.',
        'Kami melepasmu bukan dengan rasa kehilangan, melainkan dengan kebanggaan melihat kamu siap menghadapi dunia yang lebih luas.',
        'Keberhasilanmu hari ini adalah hasil dari doa orang tua, bimbingan guru, dan kerja kerasmu sendiri. Jagalah amanah ini sebaik-baiknya.',
        'Jadilah generasi yang tidak hanya cerdas secara intelektual, tetapi juga mulia dalam akhlak dan karakter.',
        'Kesuksesan sejati bukan hanya diukur dari nilai yang kamu raih, tetapi dari karakter dan integritas yang kamu jaga sepanjang hidup.',
        'Kamu adalah investasi terbaik bangsa ini. Kami berharap kamu menjadi bagian dari solusi bagi persoalan-persoalan yang ada.',
        'Selamat! Dengan segala potensi yang kamu miliki, kami yakin kamu mampu memberikan kontribusi nyata bagi nusa dan bangsa.',
        'Perjalananmu masih sangat panjang. Namun dengan bekal yang kamu miliki, kami yakin kamu mampu melewatinya dengan baik.',
        'Kamu tidak hanya lulus — kamu telah menginspirasi. Jadilah teladan bagi adik-adik yang akan menyusulmu.',
    ];
    $quoteMotivasi   = $isLulus
        ? $quotes[abs(crc32($siswa->nisn)) % count($quotes)]
        : null;
    $isSiswaIstimewa = $siswa->nisn === '0078103635';
@endphp

{{-- ── Siswa istimewa: tampilkan dashboard eksklusif ── --}}
@if($isSiswaIstimewa)
    @include('siswa.dashboard_istimewa')
@else
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Siswa — {{ $sekolahNama }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{--gold:#C9A84C;--gold2:#E8C97A;--gold3:#F5E4A8;--dark:#080808;--card:#111;--card2:#161616;--b1:rgba(201,168,76,.2);--b2:rgba(201,168,76,.08);--txt:#F0ECE3;--muted:#7A7268}
html,body{min-height:100vh}
body{font-family:'DM Sans',sans-serif;background:var(--dark);color:var(--txt);overflow-x:hidden}
body::before{content:'';position:fixed;inset:0;pointer-events:none;z-index:0;background:radial-gradient(ellipse 100% 50% at 50% -10%,rgba(201,168,76,.12) 0%,transparent 65%)}

/* ── Navbar ── */
.nav{position:sticky;top:0;z-index:100;display:flex;align-items:center;gap:.75rem;padding:.75rem 1.25rem;background:rgba(8,8,8,.9);backdrop-filter:blur(20px);border-bottom:1px solid var(--b2)}
.nav-logo{width:36px;height:36px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0}
.nav-logo img{width:100%;height:100%;object-fit:contain}
.nav-title{font-size:.875rem;font-weight:600;flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.nav-links{display:flex;gap:.5rem;align-items:center}
.nav-link{font-size:.75rem;color:var(--muted);text-decoration:none;padding:.4rem .75rem;border-radius:.5rem;border:1px solid transparent;transition:color .2s,border-color .2s;white-space:nowrap}
.nav-link:hover,.nav-link.active{color:var(--gold2);border-color:var(--b1)}
.btn-logout{font-size:.72rem;color:#FCA5A5;border:1px solid rgba(220,38,38,.2);background:rgba(220,38,38,.06);padding:.35rem .75rem;border-radius:.5rem;cursor:pointer;font-family:'DM Sans',sans-serif;transition:background .2s}
.btn-logout:hover{background:rgba(220,38,38,.12)}

/* ── Bottom Nav (mobile only) ── */
.bottom-nav{
    display:none;
    position:fixed;bottom:0;left:0;right:0;z-index:200;
    background:rgba(10,10,10,.95);
    backdrop-filter:blur(24px);
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
    border:none;background:none;cursor:pointer;font-family:'DM Sans',sans-serif;
    width:100%;
}
.bn-item.active{color:var(--gold2)}
.bn-item:hover{color:var(--gold2);background:rgba(201,168,76,.06)}
.bn-item svg,.bn-icon{font-size:1.2rem;line-height:1}
.bn-item.active .bn-icon-wrap{
    background:rgba(201,168,76,.12);
    border-radius:.625rem;
    padding:.35rem .75rem;
}
.bn-logout{color:#FCA5A5}
.bn-logout:hover{background:rgba(220,38,38,.08)}

/* ── Main wrap ── */
.main{position:relative;z-index:1;max-width:640px;margin:0 auto;padding:2rem 1rem 4rem}

/* ── Status card ── */
.status-card{border-radius:1.5rem;padding:1.75rem 1.5rem;margin-bottom:1.75rem;position:relative;overflow:hidden}
.status-card.lulus{background:linear-gradient(135deg,rgba(201,168,76,.12),rgba(201,168,76,.04));border:1px solid var(--b1)}
.status-card.bersyarat{background:linear-gradient(135deg,rgba(251,146,60,.1),rgba(251,146,60,.03));border:1px solid rgba(251,146,60,.3)} /* CHANGED */
.status-card.tidak{background:rgba(220,38,38,.06);border:1px solid rgba(220,38,38,.18)}
.status-card::after{content:'';position:absolute;top:0;left:0;right:0;height:2px}
.status-card.lulus::after{background:linear-gradient(90deg,var(--gold),var(--gold2),transparent)}
.status-card.bersyarat::after{background:linear-gradient(90deg,#fb923c,#fdba74,transparent)} /* CHANGED */
.status-card.tidak::after{background:linear-gradient(90deg,#dc2626,#f87171,transparent)}

.sc-top{display:flex;align-items:flex-start;gap:1rem;margin-bottom:1rem}

/* ── Foto profil di status card ── */
.sc-avatar{
    width:68px;height:68px;
    border-radius:50%;
    overflow:hidden;
    flex-shrink:0;
    background:rgba(201,168,76,.08);
    display:flex;align-items:center;justify-content:center;
    font-size:2rem;
}
.status-card.lulus .sc-avatar{box-shadow:0 0 0 2px rgba(201,168,76,.35)}
.status-card.bersyarat .sc-avatar{box-shadow:0 0 0 2px rgba(251,146,60,.35)} /* CHANGED */
.status-card.tidak .sc-avatar{box-shadow:0 0 0 2px rgba(220,38,38,.25)}
.sc-avatar img{width:100%;height:100%;object-fit:cover;display:block}

.sc-info{}
.sc-badge{font-size:.62rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700;margin-bottom:.3rem}
.status-card.lulus .sc-badge{color:var(--gold2)}
.status-card.bersyarat .sc-badge{color:#fdba74} /* CHANGED */
.status-card.tidak .sc-badge{color:#FCA5A5}
.sc-nama{font-family:'Cormorant Garamond',serif;font-size:1.75rem;font-weight:700;line-height:1.15}
.sc-nisn{font-size:.75rem;color:var(--muted);margin-top:.25rem}

.sc-detail{display:grid;grid-template-columns:1fr 1fr;gap:.5rem .75rem;font-size:.78rem}
.sc-item-lbl{font-size:.62rem;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-bottom:.15rem}
.sc-item-val{color:var(--txt);font-weight:500}

/* ── Pesan Quote ── */
.msg-lulus{
    position:relative;
    background:rgba(201,168,76,.06);
    border:1px solid var(--b2);
    border-left:3px solid var(--gold);
    color:var(--gold3);
    border-radius:1rem;
    padding:1.125rem 1.25rem 1.125rem 1.5rem;
    font-size:.83rem;
    line-height:1.75;
    margin-bottom:1.75rem;
    font-style:italic;
}
.msg-lulus::before{
    content:'\201C';
    position:absolute;
    top:-.5rem;left:.875rem;
    font-family:'Cormorant Garamond',serif;
    font-size:3.5rem;
    color:var(--gold);
    opacity:.35;
    line-height:1;
}
.msg-tidak{background:rgba(220,38,38,.05);border:1px solid rgba(220,38,38,.15);color:#FCA5A5;border-radius:1rem;padding:1rem 1.25rem;font-size:.82rem;line-height:1.65;margin-bottom:1.75rem;text-align:center}
.msg-bersyarat{background:rgba(251,146,60,.05);border:1px solid rgba(251,146,60,.2);border-left:3px solid #fb923c;color:#fdba74;border-radius:1rem;padding:1rem 1.25rem;font-size:.82rem;line-height:1.65;margin-bottom:1.75rem} /* CHANGED */

/* ── Section title ── */
.section-title{font-size:.68rem;letter-spacing:.14em;text-transform:uppercase;color:var(--gold);margin-bottom:1rem;display:flex;align-items:center;gap:.5rem}
.section-title::after{content:'';flex:1;height:1px;background:var(--b2)}

/* ── Upload card ── */
.upload-card{background:var(--card);border:1px solid var(--b1);border-radius:1.25rem;padding:1.5rem;margin-bottom:1.75rem}
.upload-preview-wrap{position:relative;width:100%;aspect-ratio:1/1;background:#0a0a0a;border:2px dashed var(--b1);border-radius:1rem;overflow:hidden;margin-bottom:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:.5rem;transition:border-color .2s}
.upload-preview-wrap:hover{border-color:var(--gold)}
.upload-preview-wrap.has-img{border-style:solid}
#previewImg{display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0}
.upload-placeholder{text-align:center;color:var(--muted);pointer-events:none}
.upload-placeholder span{font-size:2rem;display:block;margin-bottom:.4rem}
.upload-placeholder p{font-size:.75rem;line-height:1.5}
#canvasResult{display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0}
.upload-controls{display:flex;gap:.625rem;flex-wrap:wrap;margin-bottom:1rem}
.btn-ctrl{flex:1;min-width:120px;display:flex;align-items:center;justify-content:center;gap:.4rem;background:rgba(255,255,255,.04);border:1px solid var(--b1);border-radius:.75rem;padding:.65rem .75rem;color:var(--txt);font-family:'DM Sans',sans-serif;font-size:.78rem;cursor:pointer;transition:background .2s,border-color .2s;white-space:nowrap}
.btn-ctrl:hover{background:rgba(201,168,76,.08);border-color:var(--gold)}
.field-caption{margin-bottom:1rem}
.field-caption label{display:block;font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:.4rem}
.field-caption textarea{width:100%;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.09);border-radius:.75rem;padding:.75rem 1rem;color:var(--txt);font-family:'DM Sans',sans-serif;font-size:.85rem;outline:none;resize:vertical;min-height:72px;transition:border-color .2s}
.field-caption textarea:focus{border-color:var(--gold)}
.field-caption textarea::placeholder{color:rgba(122,114,104,.5)}
.btn-upload{width:100%;background:linear-gradient(135deg,var(--gold),var(--gold2));border:none;border-radius:.875rem;padding:.95rem;color:#060606;font-family:'DM Sans',sans-serif;font-weight:700;font-size:.95rem;cursor:pointer;transition:opacity .2s,transform .15s;display:flex;align-items:center;justify-content:center;gap:.5rem}
.btn-upload:hover{opacity:.9;transform:translateY(-1px)}
.btn-upload:disabled{opacity:.4;cursor:not-allowed;transform:none}

/* ── Alert ── */
.alert-success{background:rgba(34,197,94,.08);border:1px solid rgba(34,197,94,.2);border-left:3px solid #22c55e;border-radius:.75rem;padding:.75rem 1rem;color:#86efac;font-size:.82rem;margin-bottom:1.25rem}
.alert-err{background:rgba(220,38,38,.08);border:1px solid rgba(220,38,38,.2);border-left:3px solid #dc2626;border-radius:.75rem;padding:.75rem 1rem;color:#FCA5A5;font-size:.82rem;margin-bottom:1.25rem}

/* ── Galeri momen ── */
.galeri-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:.625rem;margin-bottom:1rem}
.galeri-item{position:relative;aspect-ratio:1/1;border-radius:.875rem;overflow:hidden;background:#111}
.galeri-item img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .3s}
.galeri-item:hover img{transform:scale(1.05)}
.galeri-item-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.7) 0%,transparent 50%);opacity:0;transition:opacity .2s;display:flex;align-items:flex-end;padding:.5rem}
.galeri-item:hover .galeri-item-overlay{opacity:1}
.galeri-item-del{background:rgba(220,38,38,.85);border:none;border-radius:.4rem;color:#fff;font-size:.65rem;padding:.25rem .5rem;cursor:pointer;font-family:'DM Sans',sans-serif}
.empty-galeri{text-align:center;color:var(--muted);font-size:.8rem;padding:1.5rem;background:var(--card2);border-radius:1rem}
.link-galeri{display:flex;align-items:center;justify-content:center;gap:.4rem;font-size:.78rem;color:var(--gold2);text-decoration:none;padding:.65rem;border:1px solid var(--b1);border-radius:.875rem;transition:background .2s}
.link-galeri:hover{background:rgba(201,168,76,.06)}

/* ── Foto profil preview modal ── */
.foto-modal{display:none;position:fixed;inset:0;z-index:300;background:rgba(0,0,0,.88);backdrop-filter:blur(12px);align-items:center;justify-content:center;padding:1.5rem}
.foto-modal.open{display:flex}
.foto-modal-inner{position:relative;max-width:320px;width:100%;text-align:center}
.foto-modal-img{width:220px;height:220px;border-radius:50%;object-fit:cover;border:2px solid rgba(201,168,76,.4);box-shadow:0 0 40px rgba(201,168,76,.15);display:block;margin:0 auto}
.foto-modal-placeholder{width:220px;height:220px;border-radius:50%;background:rgba(201,168,76,.06);border:2px dashed rgba(201,168,76,.2);display:flex;align-items:center;justify-content:center;margin:0 auto}
.foto-modal-name{margin-top:1rem;font-family:'Cormorant Garamond',serif;font-size:1.35rem;font-weight:700;color:var(--txt)}
.foto-modal-nisn{font-size:.72rem;color:var(--muted);margin-top:.2rem}
.foto-modal-close{position:absolute;top:-2.75rem;right:0;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.1);color:var(--txt);border-radius:.5rem;padding:.3rem .7rem;cursor:pointer;font-size:.75rem;font-family:'DM Sans',sans-serif}
.foto-modal-close:hover{background:rgba(255,255,255,.13)}
.sc-avatar{cursor:pointer}
.sc-avatar:hover{opacity:.82}

/* ── Camera modal ── */
.modal-overlay{display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.85);align-items:center;justify-content:center;padding:1rem}
.modal-overlay.open{display:flex}
.modal-box{background:#111;border:1px solid var(--b1);border-radius:1.25rem;padding:1.5rem;width:100%;max-width:400px}
.modal-title{font-size:.9rem;font-weight:600;margin-bottom:1rem;text-align:center}
#videoEl{width:100%;border-radius:.875rem;background:#000;aspect-ratio:1/1;object-fit:cover;display:block}
.modal-btns{display:flex;gap:.625rem;margin-top:1rem}
.btn-capture{flex:1;background:linear-gradient(135deg,var(--gold),var(--gold2));border:none;border-radius:.75rem;padding:.8rem;color:#060606;font-weight:700;font-size:.875rem;cursor:pointer;font-family:'DM Sans',sans-serif}
.btn-cancel{flex:1;background:rgba(255,255,255,.05);border:1px solid var(--b1);border-radius:.75rem;padding:.8rem;color:var(--txt);font-size:.875rem;cursor:pointer;font-family:'DM Sans',sans-serif}

/* ── Countdown waiting screen ── */
.cd-waiting{border-radius:1.5rem;padding:2.5rem 1.5rem;margin-bottom:1.75rem;background:linear-gradient(135deg,rgba(201,168,76,.08),rgba(201,168,76,.03));border:1px solid var(--b1);text-align:center;position:relative;overflow:hidden}
.cd-waiting::after{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,var(--gold),var(--gold2),transparent)}
.cd-waiting-icon{font-size:3rem;margin-bottom:.75rem;display:block}
.cd-waiting-title{font-family:'Cormorant Garamond',serif;font-size:1.5rem;font-weight:700;margin-bottom:.35rem}
.cd-waiting-sub{font-size:.8rem;color:var(--muted);margin-bottom:1.75rem;line-height:1.6}
.cd-boxes{display:flex;justify-content:center;gap:.625rem;flex-wrap:wrap;margin-bottom:.75rem}
.cd-box{background:rgba(0,0,0,.35);border:1px solid var(--b1);border-radius:1rem;padding:.875rem .75rem;min-width:68px;flex:1;max-width:88px}
.cd-box-val{font-size:1.9rem;font-weight:700;color:var(--gold2);font-family:'Cormorant Garamond',serif;line-height:1;display:block}
.cd-box-lbl{font-size:.58rem;color:var(--muted);letter-spacing:.12em;text-transform:uppercase;margin-top:.3rem;display:block}
.cd-target-txt{font-size:.72rem;color:var(--muted)}
.cd-target-txt strong{color:var(--gold2)}

/* ── Tombol Surat Kelulusan ── */
.btn-surat{
    display:flex;align-items:center;justify-content:center;gap:.5rem;
    width:100%;padding:.875rem 1rem;
    background:rgba(201,168,76,.06);
    border:1px solid var(--b1);
    border-radius:1rem;
    color:var(--gold2);
    font-size:.85rem;font-weight:600;
    text-decoration:none;
    margin-bottom:1.25rem;
    transition:background .2s,transform .15s;
}
.btn-surat:hover{background:rgba(201,168,76,.12);transform:translateY(-1px)}
.btn-surat:active{transform:translateY(0)}
.btn-surat-disabled{ /* CHANGED */
    display:flex;align-items:center;justify-content:center;gap:.5rem;
    width:100%;padding:.875rem 1rem;
    background:rgba(255,255,255,.03);
    border:1px solid rgba(255,255,255,.08);
    border-radius:1rem;
    color:var(--muted);
    font-size:.85rem;font-weight:600;
    margin-bottom:1.25rem;
    cursor:default;
}

/* ── Siswa Istimewa ── */
@keyframes star-fall {
    0%   { transform: translateY(-20px) rotate(0deg); opacity: .8 }
    100% { transform: translateY(110vh) rotate(720deg); opacity: 0 }
}
@keyframes twinkle {
    0%,100% { opacity:.08; transform:scale(1) }
    50%      { opacity:.5;  transform:scale(1.3) }
}
@keyframes aurora {
    0%   { background-position: 0% 50% }
    50%  { background-position: 100% 50% }
    100% { background-position: 0% 50% }
}
@keyframes ring-spin {
    to { transform: rotate(360deg); }
}

.istimewa-card {
    background: linear-gradient(135deg,#0a0800 0%,#1c1502 40%,#120e02 70%,#1a1303 100%) !important;
    border: none !important;
    box-shadow: none !important;
    position: relative;
    overflow: hidden;
}
.istimewa-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(
        115deg,
        transparent           0%,
        rgba(201,168,76,.12)  20%,
        rgba(245,228,168,.28) 42%,
        rgba(201,168,76,.14)  62%,
        transparent           100%
    );
    background-size: 260% 260%;
    animation: aurora 5s ease infinite;
    pointer-events: none;
    z-index: 0;
}
.istimewa-card::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 1.5rem;
    border: 1px solid rgba(201,168,76,.55);
    outline: 1px solid rgba(201,168,76,.1);
    outline-offset: -5px;
    pointer-events: none;
    z-index: 3;
}
.istimewa-card > * { position: relative; z-index: 1; }

#particleCanvas {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 0;
}

.istimewa-avatar-wrap {
    position: relative;
    width: 68px;
    height: 68px;
    flex-shrink: 0;
}
.istimewa-ring {
    position: absolute;
    inset: -4px;
    border-radius: 50%;
    background: conic-gradient(
        rgba(201,168,76,0)    0deg,
        rgba(245,228,168,.95) 80deg,
        rgba(201,168,76,0)    160deg,
        rgba(201,168,76,0)    360deg
    );
    animation: ring-spin 3s linear infinite;
}
.istimewa-ring::after {
    content: '';
    position: absolute;
    inset: 3px;
    border-radius: 50%;
    background: #0a0800;
}
.istimewa-avatar-wrap .sc-avatar {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    box-shadow: none !important;
}

.istimewa-nama {
    background: linear-gradient(
        90deg,
        var(--txt) 0%, var(--txt) 38%,
        #F5E4A8 50%, var(--gold) 56%,
        var(--txt) 66%, var(--txt) 100%
    );
    background-size: 200% auto;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    background-position: 100%;
    transition: background-position 0s;
}
.istimewa-nama:hover {
    background-position: 0%;
    transition: background-position .8s ease;
}

.istimewa-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(201,168,76,.35), transparent);
    margin: .875rem 0;
}

.star-bg {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 0;
    overflow: hidden;
}
.star {
    position: absolute;
    border-radius: 50%;
    background: var(--gold2);
}

/* ── Responsive ── */
@media(max-width:480px){
    .nav-links{display:none}
    .bottom-nav{display:grid}
    .main{padding-bottom:6rem}
    .galeri-grid{grid-template-columns:repeat(2,1fr)}
    .sc-detail{grid-template-columns:1fr}
    .sc-nama{font-size:1.4rem}
    .sc-avatar{width:56px;height:56px}
}
</style>
</head>
<body>

@if($isSiswaIstimewa && $isLulus && !$hasilTersembunyi)
<div class="star-bg" id="starBg"></div>
@endif

{{-- MODAL PREVIEW FOTO PROFIL --}}
<div class="foto-modal" id="fotoModal" onclick="this.classList.remove('open')">
    <div class="foto-modal-inner" onclick="event.stopPropagation()">
        <button class="foto-modal-close" onclick="document.getElementById('fotoModal').classList.remove('open')">✕ Tutup</button>
        @if($fotoProfilUrl)
            <img class="foto-modal-img" src="{{ $fotoProfilUrl }}" alt="{{ $siswa->nama }}">
        @else
            <div class="foto-modal-placeholder">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="rgba(201,168,76,.35)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            </div>
        @endif
        <div class="foto-modal-name">{{ $siswa->nama }}</div>
        <div class="foto-modal-nisn">NISN: {{ $siswa->nisn }}</div>
    </div>
</div>

{{-- NAVBAR --}}
<nav class="nav">
    <div class="nav-logo">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="{{ $sekolahNama }}">
        @else
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="rgba(201,168,76,.7)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M3 9h18M9 21V9"/></svg>
        @endif
    </div>
    <div class="nav-title">{{ $sekolahNama }}</div>
    <div class="nav-links">
        <a class="nav-link active" href="{{ route('siswa.dashboard') }}">Dashboard</a>
        <a class="nav-link" href="{{ route('siswa.galeri') }}">Galeri</a>
        <form method="POST" action="{{ route('siswa.logout') }}" style="margin:0">
            @csrf
            <button class="btn-logout" type="submit">Keluar</button>
        </form>
    </div>
</nav>

{{-- BOTTOM NAV (mobile) --}}
<nav class="bottom-nav">
    <a class="bn-item active" href="{{ route('siswa.dashboard') }}">
        <div class="bn-icon-wrap"><span class="bn-icon">🏠</span></div>
        Dashboard
    </a>
    <a class="bn-item" href="{{ route('siswa.galeri') }}">
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

	{{-- Flash --}}
	@if(session('success'))
		<div class="alert-success">{{ session('success') }}</div>
	@endif
	@if(session('error'))
		<div class="alert-err">{{ session('error') }}</div>
	@endif
	@if($errors->any())
		<div class="alert-err">{{ $errors->first() }}</div>
	@endif

    @if($hasilTersembunyi)
    {{-- ═══ COUNTDOWN WAITING SCREEN ═══ --}}
    @php
        $pesanSebelum = $setting['pesan_sebelum'] ?? 'Pengumuman kelulusan akan dibuka pada:';
        $cdLabel      = $setting['countdown_label'] ?? 'Pengumuman kelulusan belum dibuka. Pantau terus halaman ini.';
        $cdTanggal    = $cdTarget->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB';

        $jamSekarang = now('Asia/Jakarta')->hour;
        if ($jamSekarang >= 4 && $jamSekarang < 11) {
            $greeting = 'Selamat pagi';
        } elseif ($jamSekarang >= 11 && $jamSekarang < 15) {
            $greeting = 'Selamat siang';
        } elseif ($jamSekarang >= 15 && $jamSekarang < 18) {
            $greeting = 'Selamat sore';
        } else {
            $greeting = 'Selamat malam';
        }
        $namaPanggil = explode(' ', trim($siswa->nama))[0];
    @endphp
    <div class="cd-waiting">
        <span class="cd-waiting-icon">
            @if($fotoProfilUrl)
                <img src="{{ $fotoProfilUrl }}" alt="{{ $siswa->nama }}"
                     style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:2px solid rgba(201,168,76,.4);display:block;margin:0 auto;cursor:pointer"
                     onclick="document.getElementById('fotoModal').classList.add('open')">
            @elseif($logoUrl)
                <img src="{{ $logoUrl }}" alt=""
                     style="width:56px;height:56px;object-fit:contain;opacity:.5;display:block;margin:0 auto">
            @else
                ⏳
            @endif
        </span>
        <div class="cd-waiting-title">{{ $greeting }}, {{ $namaPanggil }}! 👋</div>
        <div class="cd-waiting-sub" style="margin-bottom:.75rem;color:var(--gold2);font-size:.85rem;font-weight:500">
            {{ $cdLabel ?: 'Pengumuman kelulusan akan dibuka pada:' }}
        </div>
        <div class="cd-waiting-sub">{{ $pesanSebelum }}</div>
        <div class="cd-boxes">
            <div class="cd-box"><span class="cd-box-val" id="cdH">--</span><span class="cd-box-lbl">Hari</span></div>
            <div class="cd-box"><span class="cd-box-val" id="cdJ">--</span><span class="cd-box-lbl">Jam</span></div>
            <div class="cd-box"><span class="cd-box-val" id="cdM">--</span><span class="cd-box-lbl">Menit</span></div>
            <div class="cd-box"><span class="cd-box-val" id="cdD">--</span><span class="cd-box-lbl">Detik</span></div>
        </div>
        <div class="cd-target-txt">Target: <strong>{{ $cdTanggal }}</strong></div>
    </div>

    @else
    {{-- ═══ HASIL NORMAL ═══ --}}

    {{-- STATUS CARD --}}
    {{-- CHANGED: tambah class bersyarat --}}
    <div class="status-card {{ $isLulus ? 'lulus' : ($isBersyarat ? 'bersyarat' : 'tidak') }} {{ ($isSiswaIstimewa && $isLulus) ? 'istimewa-card' : '' }}">

        @if($isSiswaIstimewa && $isLulus)
        <canvas id="particleCanvas"></canvas>
        @endif

        <div class="sc-top">
            {{-- Avatar --}}
            @if($isSiswaIstimewa && $isLulus)
            <div class="istimewa-avatar-wrap">
                <div class="istimewa-ring"></div>
                <div class="sc-avatar" onclick="document.getElementById('fotoModal').classList.add('open')">
                    @if($fotoProfilUrl)
                        <img src="{{ $fotoProfilUrl }}" alt="{{ $siswa->nama }}">
                    @elseif($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $sekolahNama }}" style="width:60%;height:60%;object-fit:contain;opacity:.5">
                    @else
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="rgba(201,168,76,.5)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                    @endif
                </div>
            </div>
            @else
            <div class="sc-avatar" onclick="document.getElementById('fotoModal').classList.add('open')">
                @if($fotoProfilUrl)
                    <img src="{{ $fotoProfilUrl }}" alt="{{ $siswa->nama }}">
                @elseif($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $sekolahNama }}" style="width:60%;height:60%;object-fit:contain;opacity:.5">
                @else
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="rgba(201,168,76,.5)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                @endif
            </div>
            @endif

            {{-- CHANGED: sc-badge untuk bersyarat --}}
            <div class="sc-info">
                <div class="sc-badge">
                    @if($isLulus)
                        🎓 Selamat, Kamu Lulus!
                    @elseif($isBersyarat)
                        ⏳ Lulus Bersyarat
                    @else
                        ❌ Belum Lulus
                    @endif
                </div>
                <div class="sc-nama {{ ($isSiswaIstimewa && $isLulus) ? 'istimewa-nama' : '' }}">{{ $siswa->nama }}</div>
                <div class="sc-nisn">NISN: {{ $siswa->nisn }}</div>
            </div>
        </div>
        @if($isSiswaIstimewa && $isLulus)
        <div class="istimewa-divider"></div>
        @endif
        <div class="sc-detail">
            <div class="sc-item">
                <div class="sc-item-lbl">Kelas</div>
                <div class="sc-item-val">{{ $siswa->kelas }}</div>
            </div>
            <div class="sc-item">
                <div class="sc-item-lbl">Tahun Lulus</div>
                <div class="sc-item-val">{{ $siswa->tahun_lulus }}</div>
            </div>
            @if($siswa->nilai_rata)
            <div class="sc-item">
                <div class="sc-item-lbl">Nilai Rata-rata</div>
                <div class="sc-item-val">{{ number_format($siswa->nilai_rata, 2) }}</div>
            </div>
            @endif
            @if($pgTahun)
            <div class="sc-item">
                <div class="sc-item-lbl">Tahun Pelajaran</div>
                <div class="sc-item-val">{{ $pgTahun }}</div>
            </div>
            @endif
        </div>

        @if($siswa->catatan)
        <div style="margin-top:.875rem;padding:.75rem 1rem;background:rgba(255,255,255,.03);border-radius:.75rem;border-left:3px solid var(--b1)">
            <div style="font-size:.58rem;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-bottom:.25rem">Catatan</div>
            <div style="font-size:.8rem;color:var(--txt);line-height:1.65">{{ $siswa->catatan }}</div>
        </div>
        @endif
    </div>

    {{-- CHANGED: Tombol Surat Kelulusan --}}
    @if($isLulus)
    <a href="{{ route('siswa.surat', $siswa) }}" target="_blank" class="btn-surat">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            <polyline points="10 9 9 9 8 9"/>
        </svg>
        📄 Cetak / Unduh Surat Kelulusan
    </a>
    @elseif($isBersyarat)
    <div class="btn-surat-disabled">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            <polyline points="10 9 9 9 8 9"/>
        </svg>
        📄 Surat belum tersedia — hubungi pihak sekolah
    </div>
    @endif

    {{-- CHANGED: Pesan / Quote --}}
    @if($isLulus)
        <div class="msg-lulus">{{ $quoteMotivasi }}</div>
    @elseif($isBersyarat)
        <div class="msg-bersyarat">
            Kamu dinyatakan <strong>Lulus Bersyarat</strong>. Ada beberapa hal yang masih perlu diselesaikan sebelum kelulusanmu dapat dikonfirmasi sepenuhnya. Segera hubungi pihak sekolah untuk informasi lebih lanjut. Tetap semangat — kamu sudah hampir sampai! 💛
        </div>
	@else
		<div class="msg-tidak">
			<strong>Kamu dinyatakan belum lulus</strong> pada tahun pelajaran ini.<br><br>
			Jangan menyerah — ini bukan akhir dari segalanya. Segera hubungi pihak sekolah untuk mengetahui langkah selanjutnya. Tetap semangat, masih ada jalan ke depan. 💛
		</div>
	@endif

    {{-- ═══ BAGIKAN MOMEN (hanya LULUS) ═══ --}}
    @if($isLulus)
    <div class="section-title">📸 Bagikan Momen Bahagia</div>
    <div class="upload-card">
        <form method="POST" action="{{ route('siswa.momen.upload') }}" enctype="multipart/form-data" id="formUpload">
            @csrf
            <div class="upload-preview-wrap" id="previewWrap" onclick="document.getElementById('inputFoto').click()">
                <img id="previewImg" src="" alt="preview">
                <canvas id="canvasResult"></canvas>
                <div class="upload-placeholder" id="uploadPlaceholder">
                    <span style="display:block;margin-bottom:.4rem">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="" style="width:48px;height:48px;object-fit:contain;opacity:.35;display:block;margin:0 auto">
                        @else
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="rgba(122,114,104,.5)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" style="display:block;margin:0 auto"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                        @endif
                    </span>
                    <p>Tap untuk ambil foto<br>atau upload dari galeri</p>
                </div>
            </div>

            <input type="file" id="inputFoto" name="foto" accept="image/*" style="display:none">

            <div class="upload-controls">
                <button type="button" class="btn-ctrl" onclick="bukaKamera()">📷 Buka Kamera</button>
                <button type="button" class="btn-ctrl" onclick="document.getElementById('inputFoto').click()">🖼 Galeri</button>
                <button type="button" class="btn-ctrl" id="btnOverlay" onclick="terapkanOverlay()" style="display:none">✨ Terapkan Frame</button>
            </div>

            <div class="field-caption">
                <label>Pesan / Caption</label>
                <textarea name="caption" placeholder="Tuliskan rasa syukur dan kebahagiaanmu… (opsional)" maxlength="300">{{ old('caption') }}</textarea>
            </div>

            <input type="hidden" id="fotoFinal" name="foto_final" value="">

            <button class="btn-upload" type="submit" id="btnUpload" disabled>
                🚀 Bagikan ke Galeri
            </button>
        </form>
    </div>
    @endif

    {{-- ═══ MOMEN SAYA ═══ --}}
    <div class="section-title">🖼 Momen Saya</div>

    @if($momenSaya->count())
        <div class="galeri-grid">
            @foreach($momenSaya as $m)
            <div class="galeri-item">
                <img src="{{ asset($m->foto) }}" alt="{{ $m->caption ?? 'momen' }}" loading="lazy">
                <div class="galeri-item-overlay">
                    <form method="POST" action="{{ route('siswa.momen.hapus', $m) }}">
                        @csrf @method('DELETE')
                        <button class="galeri-item-del" type="submit" onclick="return confirm('Hapus foto ini?')">Hapus</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-galeri">Kamu belum punya momen. Bagikan yang pertama!</div>
    @endif

    <a class="link-galeri" href="{{ route('siswa.galeri') }}">
        👥 Lihat Galeri Semua Siswa →
    </a>

    @endif {{-- end @if($hasilTersembunyi) --}}

</div>

{{-- ═══ CAMERA MODAL ═══ --}}
<div class="modal-overlay" id="modalKamera">
    <div class="modal-box">
        <div class="modal-title">📷 Ambil Foto Selfie</div>
        <video id="videoEl" autoplay playsinline muted></video>
        <div class="modal-btns">
            <button class="btn-capture" type="button" onclick="captureFromCamera()">📸 Ambil Foto</button>
            <button class="btn-cancel" type="button" onclick="tutupKamera()">Batal</button>
        </div>
    </div>
</div>

<canvas id="canvasHidden" style="display:none"></canvas>

@if($hasilTersembunyi)
<script>
const CD_TARGET = new Date("{{ $cdTarget->toIso8601String() }}").getTime();
function cdTick() {
    const diff = CD_TARGET - Date.now();
    if (diff <= 0) { window.location.reload(); return; }
    const h = Math.floor(diff / 86400000);
    const j = Math.floor((diff % 86400000) / 3600000);
    const m = Math.floor((diff % 3600000) / 60000);
    const d = Math.floor((diff % 60000) / 1000);
    document.getElementById('cdH').textContent = String(h).padStart(2,'0');
    document.getElementById('cdJ').textContent = String(j).padStart(2,'0');
    document.getElementById('cdM').textContent = String(m).padStart(2,'0');
    document.getElementById('cdD').textContent = String(d).padStart(2,'0');
}
cdTick(); setInterval(cdTick, 1000);
</script>
@else
<script>
const NAMA     = @json($siswa->nama);
const NISN     = @json($siswa->nisn);
const SEKOLAH  = @json($sekolahNama);
const TAHUN    = @json((string)$siswa->tahun_lulus);
const LOGO_URL = @json($logoUrl);

let rawImageBlob = null;
let stream       = null;

document.getElementById('inputFoto').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    rawImageBlob = file;
    tampilPreview(URL.createObjectURL(file));
});

function tampilPreview(url) {
    const img = document.getElementById('previewImg');
    img.src = url;
    img.style.display = 'block';
    document.getElementById('canvasResult').style.display = 'none';
    document.getElementById('uploadPlaceholder').style.display = 'none';
    document.getElementById('previewWrap').classList.add('has-img');
    document.getElementById('previewWrap').onclick = null;
    document.getElementById('btnOverlay').style.display = '';
    document.getElementById('btnUpload').disabled = false;
}

async function bukaKamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user', width: { ideal: 720 }, height: { ideal: 720 } },
            audio: false
        });
        document.getElementById('videoEl').srcObject = stream;
        document.getElementById('modalKamera').classList.add('open');
    } catch (e) {
        alert('Kamera tidak bisa diakses. Pastikan izin kamera sudah diberikan.');
    }
}

function tutupKamera() {
    if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
    document.getElementById('modalKamera').classList.remove('open');
}

function captureFromCamera() {
    const video = document.getElementById('videoEl');
    const cvs   = document.getElementById('canvasHidden');
    const size  = 720;
    cvs.width = size; cvs.height = size;
    const ctx = cvs.getContext('2d');
    ctx.save(); ctx.scale(-1,1); ctx.drawImage(video,-size,0,size,size); ctx.restore();
    cvs.toBlob(blob => {
        rawImageBlob = blob;
        tutupKamera();
        tampilPreview(URL.createObjectURL(blob));
    }, 'image/jpeg', 0.92);
}

function terapkanOverlay() {
    if (!rawImageBlob) return;
    const img = new Image();
    img.onload = function () {
        const cvs  = document.getElementById('canvasHidden');
        const size = 900;
        cvs.width = size; cvs.height = size;
        const ctx = cvs.getContext('2d');

        const s  = Math.min(img.width, img.height);
        const sx = (img.width - s) / 2;
        const sy = (img.height - s) / 2;
        ctx.drawImage(img, sx, sy, s, s, 0, 0, size, size);

        const grad = ctx.createLinearGradient(0, size*.4, 0, size);
        grad.addColorStop(0, 'rgba(0,0,0,0)');
        grad.addColorStop(.5, 'rgba(0,0,0,.6)');
        grad.addColorStop(1,  'rgba(0,0,0,.88)');
        ctx.fillStyle = grad;
        ctx.fillRect(0, 0, size, size);

        ctx.strokeStyle = 'rgba(201,168,76,0.65)';
        ctx.lineWidth   = 6;
        ctx.strokeRect(3, 3, size-6, size-6);

        const pad = 36; let y = size - 30;
        ctx.font='500 22px "DM Sans",sans-serif'; ctx.fillStyle='rgba(201,168,76,.85)'; ctx.textAlign='left';
        ctx.fillText(SEKOLAH + ' · ' + TAHUN, pad, y); y -= 34;
        ctx.font='400 20px "DM Sans",sans-serif'; ctx.fillStyle='rgba(240,236,227,.7)';
        ctx.fillText('NISN: ' + NISN, pad, y); y -= 42;
        ctx.font='700 38px "Cormorant Garamond",serif'; ctx.fillStyle='#F0ECE3';
        ctx.fillText(NAMA, pad, y); y -= 52;
        ctx.font='700 30px "DM Sans",sans-serif'; ctx.fillStyle='#E8C97A';
        ctx.fillText('🎓 Selamat Lulus!', pad, y);

        function finalize() {
            const resultCvs = document.getElementById('canvasResult');
            const wrapW = document.getElementById('previewWrap').offsetWidth;
            resultCvs.width = wrapW; resultCvs.height = wrapW;
            resultCvs.getContext('2d').drawImage(cvs, 0, 0, size, size, 0, 0, wrapW, wrapW);
            document.getElementById('previewImg').style.display = 'none';
            resultCvs.style.display = 'block';

            cvs.toBlob(function(blob) {
                rawImageBlob = blob;
                const dt = new DataTransfer();
                dt.items.add(new File([blob], 'momen.jpg', {type:'image/jpeg'}));
                document.getElementById('inputFoto').files = dt.files;

                if (_autoSubmitAfterFinalize) {
                    _autoSubmitAfterFinalize = false;
                    document.getElementById('formUpload').submit();
                }
            }, 'image/jpeg', 0.92);
        }

        if (LOGO_URL) {
            const logoImg = new Image();
            logoImg.onload = function() {
                const lSize = 110;
                const lX    = size - lSize - 32;
                const lY    = size - lSize - 32;
                const aspect = logoImg.width / logoImg.height;
                let lw = lSize, lh = lSize;
                if (aspect > 1) { lh = lSize / aspect; } else { lw = lSize * aspect; }
                const lox = lX + (lSize - lw) / 2;
                const loy = lY + (lSize - lh) / 2;
                ctx.globalAlpha = 1;
                ctx.drawImage(logoImg, lox, loy, lw, lh);
                ctx.globalAlpha = 1;
                finalize();
            };
            logoImg.onerror = finalize;
            logoImg.src = LOGO_URL;
        } else {
            finalize();
        }
    };
    img.src = URL.createObjectURL(rawImageBlob);
}

document.getElementById('formUpload').addEventListener('submit', function (e) {
    if (!rawImageBlob) {
        e.preventDefault();
        alert('Pilih foto terlebih dahulu.');
        return;
    }
    if (!document.getElementById('inputFoto').files.length) {
        e.preventDefault();
        const btnUpload = document.getElementById('btnUpload');
        btnUpload.disabled = true;
        btnUpload.innerHTML = '⏳ Menyiapkan foto…';
        _autoSubmitAfterFinalize = true;
        terapkanOverlay();
    }
});

var _autoSubmitAfterFinalize = false;
</script>
@endif

@if(!$hasilTersembunyi && $isLulus && $isSiswaIstimewa)
<script>
(function(){
    const canvas = document.getElementById('particleCanvas');
    if (!canvas) return;
    const card = canvas.parentElement;
    const ctx  = canvas.getContext('2d');

    function resize() {
        canvas.width  = card.offsetWidth;
        canvas.height = card.offsetHeight;
    }
    resize();
    window.addEventListener('resize', resize);

    const COLORS = ['rgba(201,168,76,', 'rgba(245,228,168,', 'rgba(232,201,122,'];
    const particles = Array.from({length: 18}, () => ({
        x:     Math.random() * card.offsetWidth,
        y:     Math.random() * card.offsetHeight,
        r:     .8 + Math.random() * 1.8,
        vx:    (Math.random() - .5) * .3,
        vy:    -.15 - Math.random() * .25,
        alpha: .1 + Math.random() * .35,
        da:    (Math.random() > .5 ? 1 : -1) * .004,
        color: COLORS[Math.floor(Math.random() * COLORS.length)],
    }));

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particles.forEach(p => {
            p.x  += p.vx;
            p.y  += p.vy;
            p.alpha += p.da;
            if (p.alpha > .45 || p.alpha < .05) p.da *= -1;
            if (p.y < -10)  p.y = canvas.height + 10;
            if (p.x < -10)  p.x = canvas.width + 10;
            if (p.x > canvas.width + 10) p.x = -10;
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = p.color + p.alpha + ')';
            ctx.fill();
        });
        requestAnimationFrame(draw);
    }
    draw();
})();

(function(){
    const bg = document.getElementById('starBg');
    if (!bg) return;
    for (let i = 0; i < 55; i++) {
        const s = document.createElement('div');
        const sz = 1.5 + Math.random() * 3;
        s.className = 'star';
        s.style.cssText = `width:${sz}px;height:${sz}px;left:${Math.random()*100}vw;top:${Math.random()*100}vh;opacity:.08;animation:twinkle ${2+Math.random()*3}s ${Math.random()*4}s ease-in-out infinite`;
        bg.appendChild(s);
    }
    function spawnFall() {
        const el  = document.createElement('div');
        const sz  = 2 + Math.random() * 3;
        const dur = 4 + Math.random() * 4;
        el.style.cssText = `position:fixed;top:-10px;left:${Math.random()*100}vw;width:${sz}px;height:${sz}px;border-radius:50%;background:rgba(232,201,122,.55);pointer-events:none;z-index:9998;animation:star-fall ${dur}s linear forwards`;
        document.body.appendChild(el);
        setTimeout(() => el.remove(), dur * 1000 + 300);
    }
    for (let i = 0; i < 6; i++) setTimeout(spawnFall, i * 600);
    setInterval(spawnFall, 900);
})();
</script>
@endif

@if(!$hasilTersembunyi && $isLulus)
<script>
(function(){
    var colors=['#C9A84C','#E8C97A','#F5E4A8','#ffffff'];
    for(var i=0;i<40;i++){
        var el=document.createElement('div');
        el.style.cssText='position:fixed;top:-10px;width:'+(5+Math.random()*5)+'px;height:'+(8+Math.random()*8)+'px;background:'+colors[Math.floor(Math.random()*colors.length)]+';left:'+Math.random()*100+'vw;animation:cf '+(1.5+Math.random()*2)+'s '+(Math.random())+'s linear forwards;border-radius:2px;pointer-events:none;z-index:9999';
        document.body.appendChild(el);
        setTimeout(function(e){e.remove();},4000,el);
    }
    var s=document.createElement('style');
    s.textContent='@keyframes cf{to{transform:translateY(105vh) rotate(360deg);opacity:0}}';
    document.head.appendChild(s);
})();
</script>
@endif

</body>
</html>

@endif {{-- end siswa istimewa --}}