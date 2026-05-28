{{-- DASHBOARD EKSKLUSIF — NISN 0078103635 --}}
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Santri — {{ $sekolahNama }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Scheherazade+New:wght@400;700&family=Tajawal:wght@300;400;500;700&family=Cinzel:wght@400;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}

:root{
    --bg:    #040e07;
    --bg2:   #071209;
    --s1:    #1e5c35;
    --s2:    #2d8050;
    --s3:    #4aad72;
    --s4:    #7dcfa0;
    --s5:    #b2eecb;
    --txt:   #dff2e8;
    --txt2:  #8ecba6;
    --muted: #3d6b51;
    --b1:    rgba(46,128,80,.30);
    --b2:    rgba(46,128,80,.10);
}

html,body{min-height:100vh}
body{
    font-family:'Tajawal',sans-serif;
    background:var(--bg);
    color:var(--txt);
    overflow-x:hidden;
}

body::before{
    content:'';position:fixed;inset:0;pointer-events:none;z-index:0;
    background:
        radial-gradient(ellipse 75% 40% at 50% -5%, rgba(30,92,53,.28) 0%,transparent 65%),
        radial-gradient(ellipse 45% 25% at 10% 60%, rgba(46,128,80,.08) 0%,transparent 55%),
        radial-gradient(ellipse 45% 25% at 90% 75%, rgba(46,128,80,.06) 0%,transparent 55%);
}

body::after{
    content:'';position:fixed;inset:0;pointer-events:none;z-index:0;opacity:.028;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Cpath d='M40 0L80 40L40 80L0 40Z' fill='none' stroke='%234aad72' stroke-width='.8'/%3E%3Cpath d='M40 12L68 40L40 68L12 40Z' fill='none' stroke='%234aad72' stroke-width='.5'/%3E%3Ccircle cx='40' cy='40' r='9' fill='none' stroke='%234aad72' stroke-width='.5'/%3E%3C/svg%3E");
    background-size:80px 80px;
}

/* NAVBAR */
.nav{
    position:sticky;top:0;z-index:100;
    display:flex;align-items:center;gap:.75rem;
    padding:.7rem 1.25rem;
    background:rgba(4,14,7,.95);
    backdrop-filter:blur(24px);
    border-bottom:1px solid var(--b2);
    position:relative;
}
.nav::after{
    content:'';position:absolute;bottom:0;left:0;right:0;height:1px;
    background:linear-gradient(90deg,transparent,var(--s2) 30%,var(--s3) 50%,var(--s2) 70%,transparent);
    opacity:.5;
}
.nav-logo{width:36px;height:36px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
.nav-logo img{width:100%;height:100%;object-fit:contain}
.nav-crescent{font-size:1.5rem;color:var(--s3);line-height:1}
.nav-title{font-size:.78rem;font-weight:500;flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:var(--txt2);letter-spacing:.06em;font-family:'Cinzel',serif;}
.nav-links{display:flex;gap:.5rem;align-items:center}
.nav-link{font-size:.72rem;color:var(--muted);text-decoration:none;padding:.38rem .75rem;border-radius:.4rem;border:1px solid transparent;transition:color .2s,border-color .2s;white-space:nowrap;}
.nav-link:hover,.nav-link.active{color:var(--s3);border-color:var(--b1)}
.btn-logout{font-size:.7rem;color:#FCA5A5;border:1px solid rgba(220,38,38,.2);background:rgba(220,38,38,.06);padding:.35rem .75rem;border-radius:.4rem;cursor:pointer;font-family:'Tajawal',sans-serif;transition:background .2s;}
.btn-logout:hover{background:rgba(220,38,38,.13)}

/* BOTTOM NAV */
.bottom-nav{display:none;position:fixed;bottom:0;left:0;right:0;z-index:200;background:rgba(4,14,7,.97);backdrop-filter:blur(24px);border-top:1px solid var(--b2);padding:.5rem .5rem calc(.5rem + env(safe-area-inset-bottom));grid-template-columns:repeat(3,1fr);}
.bn-item{display:flex;flex-direction:column;align-items:center;gap:.2rem;text-decoration:none;color:var(--muted);padding:.5rem .25rem;border-radius:.75rem;transition:color .2s,background .2s;font-size:.58rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;border:none;background:none;cursor:pointer;font-family:'Tajawal',sans-serif;width:100%;}
.bn-item.active{color:var(--s3)}
.bn-item:hover{color:var(--s3);background:rgba(46,128,80,.07)}
.bn-icon{font-size:1.15rem;line-height:1}
.bn-item.active .bn-icon-wrap{background:rgba(46,128,80,.12);border-radius:.5rem;padding:.3rem .65rem}
.bn-logout{color:#FCA5A5}
.bn-logout:hover{background:rgba(220,38,38,.08)}

/* MAIN */
.main{position:relative;z-index:1;max-width:640px;margin:0 auto;padding:2rem 1rem 4rem}

/* ALERTS */
.alert-success{background:rgba(34,197,94,.07);border:1px solid rgba(34,197,94,.2);border-left:3px solid #22c55e;border-radius:.75rem;padding:.75rem 1rem;color:#86efac;font-size:.82rem;margin-bottom:1.25rem;animation:fadeSlide .35s ease;}
.alert-err{background:rgba(220,38,38,.07);border:1px solid rgba(220,38,38,.2);border-left:3px solid #dc2626;border-radius:.75rem;padding:.75rem 1rem;color:#FCA5A5;font-size:.82rem;margin-bottom:1.25rem;animation:fadeSlide .35s ease;}
@keyframes fadeSlide{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:translateY(0)}}

/* ANIMATIONS */
@keyframes aurora{0%{background-position:0% 50%}50%{background-position:100% 50%}100%{background-position:0% 50%}}
@keyframes ring-spin{to{transform:rotate(360deg)}}
@keyframes shimmer{0%{background-position:200% center}100%{background-position:-200% center}}
@keyframes fade-up{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:translateY(0)}}

/* STATUS CARD — BASE */
.status-card{
    position:relative;border-radius:1.5rem;overflow:hidden;
    margin-bottom:1.75rem;animation:fade-up .6s ease both;
}

/* LULUS — hijau */
.status-card.lulus{
    background:linear-gradient(155deg,#0b1f10 0%,#071409 40%,#0a1c0e 70%,#06100a 100%);
    border:1px solid rgba(46,128,80,.5);
    outline:1px solid rgba(46,128,80,.08);outline-offset:-5px;
}
.status-card.lulus::before{
    content:'';position:absolute;inset:0;
    background:linear-gradient(122deg,transparent 0%,rgba(46,128,80,.09) 20%,rgba(90,200,130,.16) 42%,rgba(46,128,80,.08) 64%,transparent 100%);
    background-size:280% 280%;animation:aurora 8s ease infinite;pointer-events:none;z-index:0;
}
.status-card.lulus::after{
    content:'';position:absolute;top:0;left:0;right:0;height:2px;
    background:linear-gradient(90deg,transparent,var(--s1) 25%,var(--s3) 50%,var(--s1) 75%,transparent);z-index:2;
}

/* BERSYARAT — orange */
.status-card.bersyarat{
    background:linear-gradient(155deg,#1a1004 0%,#120c03 40%,#181005 70%,#120d03 100%);
    border:1px solid rgba(251,146,60,.35);
    outline:1px solid rgba(251,146,60,.06);outline-offset:-5px;
}
.status-card.bersyarat::before{
    content:'';position:absolute;inset:0;
    background:linear-gradient(122deg,transparent 0%,rgba(251,146,60,.07) 20%,rgba(253,186,116,.13) 42%,rgba(251,146,60,.07) 64%,transparent 100%);
    background-size:280% 280%;animation:aurora 8s ease infinite;pointer-events:none;z-index:0;
}
.status-card.bersyarat::after{
    content:'';position:absolute;top:0;left:0;right:0;height:2px;
    background:linear-gradient(90deg,transparent,#b45309 25%,#fb923c 50%,#b45309 75%,transparent);z-index:2;
}

/* TIDAK LULUS — merah */
.status-card.tidak{
    background:linear-gradient(155deg,#1a0404 0%,#120303 40%,#180404 70%,#120303 100%);
    border:1px solid rgba(220,38,38,.35);
    outline:1px solid rgba(220,38,38,.06);outline-offset:-5px;
}
.status-card.tidak::before{
    content:'';position:absolute;inset:0;
    background:linear-gradient(122deg,transparent 0%,rgba(220,38,38,.07) 20%,rgba(248,113,113,.13) 42%,rgba(220,38,38,.07) 64%,transparent 100%);
    background-size:280% 280%;animation:aurora 8s ease infinite;pointer-events:none;z-index:0;
}
.status-card.tidak::after{
    content:'';position:absolute;top:0;left:0;right:0;height:2px;
    background:linear-gradient(90deg,transparent,#7f1d1d 25%,#dc2626 50%,#7f1d1d 75%,transparent);z-index:2;
}

.status-card>*{position:relative;z-index:1}
#particleCanvas{position:absolute;inset:0;width:100%;height:100%;pointer-events:none;z-index:0}

/* Card body */
.card-ornament-top{display:flex;align-items:center;justify-content:center;padding:1.25rem 1.5rem .5rem;gap:.625rem;}
.card-ornament-bot{display:flex;align-items:center;justify-content:center;padding:.5rem 1.5rem 1.25rem;gap:.625rem;}
.card-body{padding:.75rem 1.5rem 1rem}

/* Avatar */
.sc-top{display:flex;align-items:flex-start;gap:1.1rem;margin-bottom:1.25rem}
.sc-avatar-wrap{position:relative;width:76px;height:76px;flex-shrink:0}
.sc-avatar-ring{
    position:absolute;inset:-6px;border-radius:50%;
    background:conic-gradient(rgba(46,128,80,0) 0deg,rgba(90,200,130,.95) 65deg,rgba(46,128,80,.35) 120deg,rgba(46,128,80,0) 180deg,rgba(46,128,80,0) 360deg);
    animation:ring-spin 4.5s linear infinite;
}
.sc-avatar-ring.bersyarat{
    background:conic-gradient(rgba(251,146,60,0) 0deg,rgba(253,186,116,.9) 65deg,rgba(251,146,60,.3) 120deg,rgba(251,146,60,0) 180deg,rgba(251,146,60,0) 360deg);
}
.sc-avatar-ring.tidak{
    background:conic-gradient(rgba(220,38,38,0) 0deg,rgba(248,113,113,.9) 65deg,rgba(220,38,38,.3) 120deg,rgba(220,38,38,0) 180deg,rgba(220,38,38,0) 360deg);
}
.sc-avatar-ring::after{content:'';position:absolute;inset:5px;border-radius:50%;background:#060f09;}
.sc-avatar-ring.bersyarat::after{background:#0e0900;}
.sc-avatar-ring.tidak::after{background:#0e0303;}
.sc-avatar{position:absolute;inset:0;width:100%;height:100%;border-radius:50%;overflow:hidden;background:rgba(46,128,80,.09);display:flex;align-items:center;justify-content:center;}
.sc-avatar img{width:100%;height:100%;object-fit:cover;display:block}

.sc-info{flex:1;min-width:0}
.sc-badge{display:inline-flex;align-items:center;gap:.35rem;font-size:.57rem;letter-spacing:.16em;text-transform:uppercase;font-weight:700;margin-bottom:.4rem;color:var(--s3);opacity:.9;}
.sc-badge.bersyarat{color:#fdba74;}
.sc-badge.tidak{color:#FCA5A5;}
.sc-nama{
    font-family:'Scheherazade New',serif;font-size:2rem;font-weight:700;line-height:1.1;
    background:linear-gradient(90deg,var(--txt) 0%,var(--txt) 25%,var(--s5) 40%,var(--s4) 50%,var(--s5) 60%,var(--txt) 75%,var(--txt) 100%);
    background-size:200% auto;
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
    animation:shimmer 5s linear infinite;word-break:break-word;
}
.sc-nama.bersyarat{
    background:linear-gradient(90deg,var(--txt) 0%,var(--txt) 25%,#fde68a 40%,#fdba74 50%,#fde68a 60%,var(--txt) 75%,var(--txt) 100%);
    background-size:200% auto;
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
    animation:shimmer 5s linear infinite;
}
.sc-nama.tidak{
    background:linear-gradient(90deg,var(--txt) 0%,var(--txt) 25%,#fecaca 40%,#f87171 50%,#fecaca 60%,var(--txt) 75%,var(--txt) 100%);
    background-size:200% auto;
    -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
    animation:shimmer 5s linear infinite;
}
.sc-nisn{font-size:.7rem;color:var(--muted);margin-top:.3rem;letter-spacing:.08em}

/* Divider ornamen */
.sc-divider{display:flex;align-items:center;justify-content:center;margin:.5rem 0 1rem}

/* Grid info */
.sc-detail{display:grid;grid-template-columns:1fr 1fr;gap:.5rem .875rem;font-size:.8rem}
.sc-item-lbl{font-size:.54rem;color:var(--muted);letter-spacing:.12em;text-transform:uppercase;margin-bottom:.15rem;font-family:'Cinzel',serif;}
.sc-item-val{color:var(--txt);font-weight:500}

.sc-catatan{margin-top:.875rem;padding:.75rem 1rem;background:rgba(46,128,80,.06);border-radius:.75rem;border-left:2px solid rgba(46,128,80,.3);}
.sc-catatan-lbl{font-size:.52rem;color:var(--muted);letter-spacing:.12em;text-transform:uppercase;margin-bottom:.25rem;font-family:'Cinzel',serif;}
.sc-catatan-val{font-size:.8rem;color:var(--txt2);line-height:1.75}

/* TOMBOL SURAT */
.btn-surat{display:flex;align-items:center;justify-content:center;gap:.6rem;width:100%;padding:.875rem 1rem;background:linear-gradient(135deg,rgba(46,128,80,.12),rgba(46,128,80,.04));border:1px solid var(--b1);border-radius:1rem;color:var(--s4);font-size:.83rem;font-weight:600;text-decoration:none;margin-bottom:1.25rem;transition:background .2s,transform .15s;}
.btn-surat:hover{background:rgba(46,128,80,.2);transform:translateY(-1px)}
.btn-surat:active{transform:translateY(0)}
.btn-surat-disabled{display:flex;align-items:center;justify-content:center;gap:.6rem;width:100%;padding:.875rem 1rem;background:rgba(255,255,255,.02);border:1px solid rgba(255,255,255,.07);border-radius:1rem;color:var(--muted);font-size:.83rem;font-weight:600;margin-bottom:1.25rem;cursor:default;}

/* QUOTE / PESAN */
.msg-lulus{position:relative;background:linear-gradient(135deg,rgba(46,128,80,.07),rgba(46,128,80,.02));border:1px solid var(--b2);border-left:2px solid var(--s2);color:var(--s5);border-radius:1rem;padding:1.25rem 1.25rem 1.25rem 1.75rem;font-family:'Scheherazade New',serif;font-size:1.05rem;line-height:1.9;margin-bottom:1.75rem;font-style:italic;}
.msg-lulus::before{content:'\201C';position:absolute;top:-.4rem;left:.875rem;font-size:3.5rem;color:var(--s2);opacity:.25;line-height:1;font-family:'Scheherazade New',serif;}
.msg-bersyarat{background:rgba(251,146,60,.05);border:1px solid rgba(251,146,60,.2);border-left:2px solid #fb923c;color:#fdba74;border-radius:1rem;padding:1.25rem 1.25rem 1.25rem 1.75rem;font-family:'Scheherazade New',serif;font-size:1.05rem;line-height:1.9;margin-bottom:1.75rem;}
.msg-tidak{background:rgba(220,38,38,.05);border:1px solid rgba(220,38,38,.15);border-left:2px solid #dc2626;color:#FCA5A5;border-radius:1rem;padding:1.25rem 1.25rem 1.25rem 1.75rem;font-size:.85rem;line-height:1.75;margin-bottom:1.75rem;}

/* SECTION TITLE */
.section-title{font-size:.57rem;letter-spacing:.18em;text-transform:uppercase;color:var(--s3);margin-bottom:1rem;display:flex;align-items:center;gap:.625rem;font-family:'Cinzel',serif;}
.section-title::after{content:'';flex:1;height:1px;background:linear-gradient(90deg,var(--b1),transparent)}

/* UPLOAD */
.upload-card{background:rgba(46,128,80,.03);border:1px solid var(--b1);border-radius:1.25rem;padding:1.5rem;margin-bottom:1.75rem;}
.upload-preview-wrap{position:relative;width:100%;aspect-ratio:1/1;background:rgba(0,0,0,.35);border:1.5px dashed var(--b1);border-radius:1rem;overflow:hidden;margin-bottom:1rem;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:.5rem;transition:border-color .2s;}
.upload-preview-wrap:hover{border-color:var(--s2)}
.upload-preview-wrap.has-img{border-style:solid}
#previewImg{display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0}
.upload-placeholder{text-align:center;color:var(--muted);pointer-events:none}
.upload-placeholder p{font-size:.75rem;line-height:1.6}
#canvasResult{display:none;width:100%;height:100%;object-fit:cover;position:absolute;inset:0}
.upload-controls{display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1rem}
.btn-ctrl{flex:1;min-width:110px;display:flex;align-items:center;justify-content:center;gap:.4rem;background:rgba(255,255,255,.025);border:1px solid var(--b1);border-radius:.75rem;padding:.6rem .75rem;color:var(--txt);font-family:'Tajawal',sans-serif;font-size:.78rem;cursor:pointer;transition:background .2s,border-color .2s;white-space:nowrap;}
.btn-ctrl:hover{background:rgba(46,128,80,.1);border-color:var(--s2)}
.field-caption{margin-bottom:1rem}
.field-caption label{display:block;font-size:.54rem;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);margin-bottom:.4rem;font-family:'Cinzel',serif;}
.field-caption textarea{width:100%;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);border-radius:.75rem;padding:.75rem 1rem;color:var(--txt);font-family:'Tajawal',sans-serif;font-size:.85rem;outline:none;resize:vertical;min-height:72px;transition:border-color .2s;}
.field-caption textarea:focus{border-color:var(--s1)}
.field-caption textarea::placeholder{color:rgba(61,107,81,.5)}
.btn-upload{width:100%;background:linear-gradient(135deg,var(--s1),var(--s2));border:none;border-radius:.875rem;padding:.95rem;color:#e0f5e8;font-family:'Tajawal',sans-serif;font-weight:700;font-size:.92rem;cursor:pointer;transition:opacity .2s,transform .15s;display:flex;align-items:center;justify-content:center;gap:.5rem;}
.btn-upload:hover{opacity:.9;transform:translateY(-1px)}
.btn-upload:disabled{opacity:.35;cursor:not-allowed;transform:none}

/* GALERI */
.galeri-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:.5rem;margin-bottom:1rem}
.galeri-item{position:relative;aspect-ratio:1/1;border-radius:.875rem;overflow:hidden;background:#111;border:1px solid rgba(46,128,80,.1);}
.galeri-item img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .3s}
.galeri-item:hover img{transform:scale(1.06)}
.galeri-item-overlay{position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.72) 0%,transparent 55%);opacity:0;transition:opacity .2s;display:flex;align-items:flex-end;padding:.5rem;}
.galeri-item:hover .galeri-item-overlay{opacity:1}
.galeri-item-del{background:rgba(220,38,38,.85);border:none;border-radius:.35rem;color:#fff;font-size:.62rem;padding:.25rem .5rem;cursor:pointer;font-family:'Tajawal',sans-serif;}
.empty-galeri{text-align:center;color:var(--muted);font-size:.8rem;padding:1.5rem;background:rgba(255,255,255,.015);border-radius:1rem;border:1px dashed var(--b2);}
.link-galeri{display:flex;align-items:center;justify-content:center;gap:.4rem;font-size:.78rem;color:var(--s4);text-decoration:none;padding:.65rem;border:1px solid var(--b1);border-radius:.875rem;transition:background .2s;}
.link-galeri:hover{background:rgba(46,128,80,.07)}

/* COUNTDOWN */
.cd-waiting{border-radius:1.5rem;padding:2.5rem 1.5rem;margin-bottom:1.75rem;background:linear-gradient(135deg,rgba(46,128,80,.09),rgba(46,128,80,.03));border:1px solid var(--b1);text-align:center;position:relative;overflow:hidden;}
.cd-waiting::before{content:'';position:absolute;top:0;left:0;right:0;height:2px;background:linear-gradient(90deg,transparent,var(--s1) 25%,var(--s3) 50%,var(--s1) 75%,transparent);}
.cd-waiting-title{font-family:'Scheherazade New',serif;font-size:1.55rem;font-weight:700;margin-bottom:.35rem;}
.cd-waiting-sub{font-size:.8rem;color:var(--muted);margin-bottom:1.75rem;line-height:1.65}
.cd-boxes{display:flex;justify-content:center;gap:.5rem;flex-wrap:wrap;margin-bottom:.875rem}
.cd-box{background:rgba(0,0,0,.4);border:1px solid var(--b1);border-radius:1rem;padding:.875rem .75rem;min-width:68px;flex:1;max-width:88px;}
.cd-box-val{font-size:2rem;font-weight:700;color:var(--s4);font-family:'Scheherazade New',serif;line-height:1;display:block;}
.cd-box-lbl{font-size:.52rem;color:var(--muted);letter-spacing:.14em;text-transform:uppercase;margin-top:.3rem;display:block;font-family:'Cinzel',serif;}
.cd-target-txt{font-size:.72rem;color:var(--muted)}
.cd-target-txt strong{color:var(--s4)}

/* CAMERA MODAL */
.modal-overlay{display:none;position:fixed;inset:0;z-index:200;background:rgba(0,0,0,.9);align-items:center;justify-content:center;padding:1rem;}
.modal-overlay.open{display:flex}
.modal-box{background:#071209;border:1px solid var(--b1);border-radius:1.25rem;padding:1.5rem;width:100%;max-width:400px;}
.modal-title{font-size:.88rem;font-weight:600;margin-bottom:1rem;text-align:center;color:var(--s4);font-family:'Cinzel',serif;letter-spacing:.07em;}
#videoEl{width:100%;border-radius:.75rem;background:#000;aspect-ratio:1/1;object-fit:cover;display:block}
.modal-btns{display:flex;gap:.5rem;margin-top:1rem}
.btn-capture{flex:1;background:linear-gradient(135deg,var(--s1),var(--s2));border:none;border-radius:.75rem;padding:.8rem;color:var(--txt);font-weight:700;font-size:.875rem;cursor:pointer;font-family:'Tajawal',sans-serif;}
.btn-cancel{flex:1;background:rgba(255,255,255,.04);border:1px solid var(--b1);border-radius:.75rem;padding:.8rem;color:var(--txt);font-size:.875rem;cursor:pointer;font-family:'Tajawal',sans-serif;}

/* ── Foto profil preview modal ── */
.foto-modal{display:none;position:fixed;inset:0;z-index:300;background:rgba(0,0,0,.9);backdrop-filter:blur(14px);align-items:center;justify-content:center;padding:1.5rem}
.foto-modal.open{display:flex}
.foto-modal-inner{position:relative;max-width:320px;width:100%;text-align:center}
.foto-modal-img{width:220px;height:220px;border-radius:50%;object-fit:cover;border:2px solid rgba(46,128,80,.45);box-shadow:0 0 40px rgba(46,128,80,.2);display:block;margin:0 auto}
.foto-modal-placeholder{width:220px;height:220px;border-radius:50%;background:rgba(46,128,80,.06);border:2px dashed rgba(46,128,80,.2);display:flex;align-items:center;justify-content:center;margin:0 auto}
.foto-modal-name{margin-top:1rem;font-family:'Cinzel',serif;font-size:1.2rem;font-weight:600;color:var(--txt)}
.foto-modal-nisn{font-size:.72rem;color:var(--muted);margin-top:.2rem}
.foto-modal-close{position:absolute;top:-2.75rem;right:0;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);color:var(--txt);border-radius:.5rem;padding:.3rem .7rem;cursor:pointer;font-size:.75rem;font-family:'Tajawal',sans-serif}
.foto-modal-close:hover{background:rgba(255,255,255,.1)}
.sc-avatar{cursor:pointer}
.sc-avatar:hover{opacity:.82}

@media(max-width:480px){
    .nav-links{display:none}
    .bottom-nav{display:grid}
    .main{padding-bottom:6rem}
    .galeri-grid{grid-template-columns:repeat(2,1fr)}
    .sc-detail{grid-template-columns:1fr}
    .sc-nama{font-size:1.6rem}
    .sc-avatar-wrap{width:64px;height:64px}
    .card-body{padding:.75rem 1.25rem 1rem}
}
</style>
</head>
<body>

<canvas id="skyCanvas" style="position:fixed;inset:0;width:100%;height:100%;pointer-events:none;z-index:0"></canvas>

{{-- MODAL PREVIEW FOTO PROFIL --}}
<div class="foto-modal" id="fotoModal" onclick="this.classList.remove('open')">
    <div class="foto-modal-inner" onclick="event.stopPropagation()">
        <button class="foto-modal-close" onclick="document.getElementById('fotoModal').classList.remove('open')">✕ Tutup</button>
        @if($fotoProfilUrl)
            <img class="foto-modal-img" src="{{ $fotoProfilUrl }}" alt="{{ $siswa->nama }}">
        @else
            <div class="foto-modal-placeholder">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="rgba(74,173,114,.35)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
            </div>
        @endif
        <div class="foto-modal-name">{{ $siswa->nama }}</div>
        <div class="foto-modal-nisn">NISN: {{ $siswa->nisn }}</div>
    </div>
</div>

<nav class="nav">
    <div class="nav-logo">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" alt="{{ $sekolahNama }}">
        @else
            <span class="nav-crescent">☽</span>
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
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-err">🚫 {{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert-err">⚠️ {{ $errors->first() }}</div>
    @endif

    @if($hasilTersembunyi)
    {{-- ═══ COUNTDOWN ═══ --}}
    @php
        $pesanSebelum = $setting['pesan_sebelum'] ?? 'Pengumuman kelulusan akan dibuka pada:';
        $cdLabel      = $setting['countdown_label'] ?? 'Pengumuman kelulusan belum dibuka. Pantau terus halaman ini.';
        $cdTanggal    = $cdTarget->setTimezone('Asia/Jakarta')->translatedFormat('d F Y, H:i') . ' WIB';
        $jamSekarang  = now('Asia/Jakarta')->hour;
        if ($jamSekarang >= 4 && $jamSekarang < 11)      $greeting = 'Selamat pagi';
        elseif ($jamSekarang >= 11 && $jamSekarang < 15) $greeting = 'Selamat siang';
        elseif ($jamSekarang >= 15 && $jamSekarang < 18) $greeting = 'Selamat sore';
        else                                              $greeting = 'Selamat malam';
        $namaPanggil = explode(' ', trim($siswa->nama))[0];
    @endphp
    <div class="cd-waiting">
        <div style="display:flex;align-items:center;justify-content:center;margin-bottom:1rem">
            <svg width="200" height="20" viewBox="0 0 200 20" fill="none" xmlns="http://www.w3.org/2000/svg" opacity=".45">
                <line x1="0" y1="10" x2="55" y2="10" stroke="#4aad72" stroke-width=".8"/>
                <polygon points="58,10 63,5 68,10 63,15" fill="none" stroke="#4aad72" stroke-width=".8"/>
                <circle cx="78" cy="10" r="4.5" fill="none" stroke="#4aad72" stroke-width=".9"/>
                <circle cx="78" cy="10" r="1.5" fill="#4aad72"/>
                <line x1="85" y1="10" x2="95" y2="10" stroke="#4aad72" stroke-width=".8"/>
                <circle cx="100" cy="10" r="3" fill="none" stroke="#2d8050" stroke-width=".7"/>
                <line x1="105" y1="10" x2="115" y2="10" stroke="#4aad72" stroke-width=".8"/>
                <circle cx="122" cy="10" r="4.5" fill="none" stroke="#4aad72" stroke-width=".9"/>
                <circle cx="122" cy="10" r="1.5" fill="#4aad72"/>
                <polygon points="132,10 137,5 142,10 137,15" fill="none" stroke="#4aad72" stroke-width=".8"/>
                <line x1="145" y1="10" x2="200" y2="10" stroke="#4aad72" stroke-width=".8"/>
            </svg>
        </div>
        <span style="display:block;margin-bottom:.875rem">
            @if($fotoProfilUrl)
                <img src="{{ $fotoProfilUrl }}" alt="{{ $siswa->nama }}" style="width:72px;height:72px;border-radius:50%;object-fit:cover;border:1.5px solid rgba(46,128,80,.45);display:block;margin:0 auto;cursor:pointer" onclick="document.getElementById('fotoModal').classList.add('open')">
            @elseif($logoUrl)
                <img src="{{ $logoUrl }}" alt="" style="width:56px;height:56px;object-fit:contain;opacity:.4;display:block;margin:0 auto">
            @else
                <span style="font-size:2.5rem;display:block;color:var(--s3);opacity:.65">☽</span>
            @endif
        </span>
        <div class="cd-waiting-title">{{ $greeting }}, {{ $namaPanggil }}!</div>
        <div class="cd-waiting-sub" style="margin-bottom:.75rem;color:var(--s4);font-size:.85rem;font-weight:500">
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
    {{-- ═══ HASIL ═══ --}}

    @php $statusClass = $isLulus ? 'lulus' : ($isBersyarat ? 'bersyarat' : 'tidak'); @endphp

    {{-- STATUS CARD --}}
    <div class="status-card {{ $statusClass }}">
        <canvas id="particleCanvas"></canvas>

        {{-- SVG Filigree bingkai sudut absolut --}}
        <svg style="position:absolute;inset:0;width:100%;height:100%;z-index:1;pointer-events:none" viewBox="0 0 400 400" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            @if($isBersyarat)
            <g opacity=".28" stroke="#fb923c" fill="none" stroke-width="1">
            @elseif(!$isLulus && !$isBersyarat)
            <g opacity=".28" stroke="#dc2626" fill="none" stroke-width="1">
            @else
            <g opacity=".32" stroke="#4aad72" fill="none" stroke-width="1">
            @endif
                <path d="M2 50 Q2 2 50 2"/>
                <path d="M2 70 Q2 12 12 2"/>
                <path d="M70 2 Q12 2 2 12"/>
                <path d="M12 38 Q12 12 38 12"/>
                <circle cx="22" cy="22" r="6"/>
                <circle cx="22" cy="22" r="2.2"/>
                <line x1="12" y1="12" x2="17" y2="17"/>
                <line x1="27" y1="12" x2="22" y2="17"/>
                <line x1="12" y1="27" x2="17" y2="22"/>
                <path d="M398 50 Q398 2 350 2"/>
                <path d="M398 70 Q398 12 388 2"/>
                <path d="M330 2 Q388 2 398 12"/>
                <path d="M388 38 Q388 12 362 12"/>
                <circle cx="378" cy="22" r="6"/>
                <circle cx="378" cy="22" r="2.2"/>
                <line x1="388" y1="12" x2="383" y2="17"/>
                <line x1="373" y1="12" x2="378" y2="17"/>
                <line x1="388" y1="27" x2="383" y2="22"/>
                <path d="M2 350 Q2 398 50 398"/>
                <path d="M2 330 Q2 388 12 398"/>
                <path d="M70 398 Q12 398 2 388"/>
                <path d="M12 362 Q12 388 38 388"/>
                <circle cx="22" cy="378" r="6"/>
                <circle cx="22" cy="378" r="2.2"/>
                <path d="M398 350 Q398 398 350 398"/>
                <path d="M398 330 Q398 388 388 398"/>
                <path d="M330 398 Q388 398 398 388"/>
                <path d="M388 362 Q388 388 362 388"/>
                <circle cx="378" cy="378" r="6"/>
                <circle cx="378" cy="378" r="2.2"/>
            </g>
        </svg>

        {{-- Ornamen atas --}}
        <div class="card-ornament-top">
            @if($isBersyarat)
            <svg width="100%" height="18" viewBox="0 0 320 18" preserveAspectRatio="xMidYMid meet" fill="none" xmlns="http://www.w3.org/2000/svg" opacity=".38">
                <line x1="0" y1="9" x2="105" y2="9" stroke="#b45309" stroke-width=".8"/>
                <polygon points="108,9 113,4 118,9 113,14" fill="none" stroke="#fb923c" stroke-width=".9"/>
                <circle cx="128" cy="9" r="4" fill="none" stroke="#fb923c" stroke-width=".9"/>
                <circle cx="128" cy="9" r="1.5" fill="#fb923c"/>
                <circle cx="160" cy="9" r="3" fill="none" stroke="#b45309" stroke-width=".8"/>
                <circle cx="160" cy="9" r="1" fill="#b45309"/>
                <circle cx="192" cy="9" r="4" fill="none" stroke="#fb923c" stroke-width=".9"/>
                <circle cx="192" cy="9" r="1.5" fill="#fb923c"/>
                <polygon points="202,9 207,4 212,9 207,14" fill="none" stroke="#fb923c" stroke-width=".9"/>
                <line x1="215" y1="9" x2="320" y2="9" stroke="#b45309" stroke-width=".8"/>
            </svg>
            @elseif(!$isLulus && !$isBersyarat)
            <svg width="100%" height="18" viewBox="0 0 320 18" preserveAspectRatio="xMidYMid meet" fill="none" xmlns="http://www.w3.org/2000/svg" opacity=".38">
                <line x1="0" y1="9" x2="105" y2="9" stroke="#7f1d1d" stroke-width=".8"/>
                <polygon points="108,9 113,4 118,9 113,14" fill="none" stroke="#dc2626" stroke-width=".9"/>
                <circle cx="128" cy="9" r="4" fill="none" stroke="#dc2626" stroke-width=".9"/>
                <circle cx="128" cy="9" r="1.5" fill="#dc2626"/>
                <circle cx="160" cy="9" r="3" fill="none" stroke="#7f1d1d" stroke-width=".8"/>
                <circle cx="160" cy="9" r="1" fill="#7f1d1d"/>
                <circle cx="192" cy="9" r="4" fill="none" stroke="#dc2626" stroke-width=".9"/>
                <circle cx="192" cy="9" r="1.5" fill="#dc2626"/>
                <polygon points="202,9 207,4 212,9 207,14" fill="none" stroke="#dc2626" stroke-width=".9"/>
                <line x1="215" y1="9" x2="320" y2="9" stroke="#7f1d1d" stroke-width=".8"/>
            </svg>
            @else
            <svg width="100%" height="18" viewBox="0 0 320 18" preserveAspectRatio="xMidYMid meet" fill="none" xmlns="http://www.w3.org/2000/svg" opacity=".4">
                <line x1="0" y1="9" x2="105" y2="9" stroke="#2d8050" stroke-width=".8"/>
                <polygon points="108,9 113,4 118,9 113,14" fill="none" stroke="#4aad72" stroke-width=".9"/>
                <circle cx="128" cy="9" r="4" fill="none" stroke="#4aad72" stroke-width=".9"/>
                <circle cx="128" cy="9" r="1.5" fill="#4aad72"/>
                <circle cx="160" cy="9" r="3" fill="none" stroke="#2d8050" stroke-width=".8"/>
                <circle cx="160" cy="9" r="1" fill="#2d8050"/>
                <circle cx="192" cy="9" r="4" fill="none" stroke="#4aad72" stroke-width=".9"/>
                <circle cx="192" cy="9" r="1.5" fill="#4aad72"/>
                <polygon points="202,9 207,4 212,9 207,14" fill="none" stroke="#4aad72" stroke-width=".9"/>
                <line x1="215" y1="9" x2="320" y2="9" stroke="#2d8050" stroke-width=".8"/>
            </svg>
            @endif
        </div>

        {{-- Body --}}
        <div class="card-body">
            <div class="sc-top">
                <div class="sc-avatar-wrap">
                    <div class="sc-avatar-ring {{ $statusClass === 'lulus' ? '' : $statusClass }}"></div>
                    <div class="sc-avatar" onclick="document.getElementById('fotoModal').classList.add('open')">
                        @if($fotoProfilUrl)
                            <img src="{{ $fotoProfilUrl }}" alt="{{ $siswa->nama }}">
                        @elseif($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $sekolahNama }}" style="width:60%;height:60%;object-fit:contain;opacity:.35">
                        @else
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="rgba(74,173,114,.5)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                            </svg>
                        @endif
                    </div>
                </div>
                <div class="sc-info">
                    <div class="sc-badge {{ $statusClass === 'lulus' ? '' : $statusClass }}">
                        @if($isLulus)
                            🎓 Selamat, Kamu Lulus!
                        @elseif($isBersyarat)
                            ⏳ Lulus Bersyarat
                        @else
                            ❌ Belum Lulus
                        @endif
                    </div>
                    <div class="sc-nama {{ $statusClass === 'lulus' ? '' : $statusClass }}">{{ $siswa->nama }}</div>
                    <div class="sc-nisn">NISN: {{ $siswa->nisn }}</div>
                </div>
            </div>

            {{-- Divider --}}
            <div class="sc-divider">
                @if($isBersyarat)
                <svg width="100%" height="16" viewBox="0 0 320 16" preserveAspectRatio="xMidYMid meet" fill="none" xmlns="http://www.w3.org/2000/svg" opacity=".4">
                    <line x1="0" y1="8" x2="95" y2="8" stroke="#b45309" stroke-width=".8"/>
                    <polygon points="98,8 103,3 108,8 103,13" fill="none" stroke="#fb923c" stroke-width=".8"/>
                    <circle cx="118" cy="8" r="4" fill="none" stroke="#fb923c" stroke-width=".9"/>
                    <circle cx="118" cy="8" r="1.4" fill="#fb923c"/>
                    <line x1="125" y1="8" x2="138" y2="8" stroke="#fb923c" stroke-width=".8"/>
                    <polygon points="141,8 146,3 151,8 146,13" fill="none" stroke="#b45309" stroke-width=".7"/>
                    <circle cx="160" cy="8" r="2.5" fill="none" stroke="#fb923c" stroke-width=".7"/>
                    <polygon points="169,8 174,3 179,8 174,13" fill="none" stroke="#b45309" stroke-width=".7"/>
                    <line x1="182" y1="8" x2="195" y2="8" stroke="#fb923c" stroke-width=".8"/>
                    <circle cx="202" cy="8" r="4" fill="none" stroke="#fb923c" stroke-width=".9"/>
                    <circle cx="202" cy="8" r="1.4" fill="#fb923c"/>
                    <polygon points="212,8 217,3 222,8 217,13" fill="none" stroke="#fb923c" stroke-width=".8"/>
                    <line x1="225" y1="8" x2="320" y2="8" stroke="#b45309" stroke-width=".8"/>
                </svg>
                @elseif(!$isLulus && !$isBersyarat)
                <svg width="100%" height="16" viewBox="0 0 320 16" preserveAspectRatio="xMidYMid meet" fill="none" xmlns="http://www.w3.org/2000/svg" opacity=".4">
                    <line x1="0" y1="8" x2="95" y2="8" stroke="#7f1d1d" stroke-width=".8"/>
                    <polygon points="98,8 103,3 108,8 103,13" fill="none" stroke="#dc2626" stroke-width=".8"/>
                    <circle cx="118" cy="8" r="4" fill="none" stroke="#dc2626" stroke-width=".9"/>
                    <circle cx="118" cy="8" r="1.4" fill="#dc2626"/>
                    <line x1="125" y1="8" x2="138" y2="8" stroke="#dc2626" stroke-width=".8"/>
                    <polygon points="141,8 146,3 151,8 146,13" fill="none" stroke="#7f1d1d" stroke-width=".7"/>
                    <circle cx="160" cy="8" r="2.5" fill="none" stroke="#dc2626" stroke-width=".7"/>
                    <polygon points="169,8 174,3 179,8 174,13" fill="none" stroke="#7f1d1d" stroke-width=".7"/>
                    <line x1="182" y1="8" x2="195" y2="8" stroke="#dc2626" stroke-width=".8"/>
                    <circle cx="202" cy="8" r="4" fill="none" stroke="#dc2626" stroke-width=".9"/>
                    <circle cx="202" cy="8" r="1.4" fill="#dc2626"/>
                    <polygon points="212,8 217,3 222,8 217,13" fill="none" stroke="#dc2626" stroke-width=".8"/>
                    <line x1="225" y1="8" x2="320" y2="8" stroke="#7f1d1d" stroke-width=".8"/>
                </svg>
                @else
                <svg width="100%" height="16" viewBox="0 0 320 16" preserveAspectRatio="xMidYMid meet" fill="none" xmlns="http://www.w3.org/2000/svg" opacity=".45">
                    <line x1="0" y1="8" x2="95" y2="8" stroke="#2d8050" stroke-width=".8"/>
                    <polygon points="98,8 103,3 108,8 103,13" fill="none" stroke="#4aad72" stroke-width=".8"/>
                    <circle cx="118" cy="8" r="4" fill="none" stroke="#4aad72" stroke-width=".9"/>
                    <circle cx="118" cy="8" r="1.4" fill="#4aad72"/>
                    <line x1="125" y1="8" x2="138" y2="8" stroke="#4aad72" stroke-width=".8"/>
                    <polygon points="141,8 146,3 151,8 146,13" fill="none" stroke="#2d8050" stroke-width=".7"/>
                    <circle cx="160" cy="8" r="2.5" fill="none" stroke="#4aad72" stroke-width=".7"/>
                    <polygon points="169,8 174,3 179,8 174,13" fill="none" stroke="#2d8050" stroke-width=".7"/>
                    <line x1="182" y1="8" x2="195" y2="8" stroke="#4aad72" stroke-width=".8"/>
                    <circle cx="202" cy="8" r="4" fill="none" stroke="#4aad72" stroke-width=".9"/>
                    <circle cx="202" cy="8" r="1.4" fill="#4aad72"/>
                    <polygon points="212,8 217,3 222,8 217,13" fill="none" stroke="#4aad72" stroke-width=".8"/>
                    <line x1="225" y1="8" x2="320" y2="8" stroke="#2d8050" stroke-width=".8"/>
                </svg>
                @endif
            </div>

            {{-- Detail informasi siswa --}}
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
            <div class="sc-catatan">
                <div class="sc-catatan-lbl">Catatan</div>
                <div class="sc-catatan-val">{{ $siswa->catatan }}</div>
            </div>
            @endif
        </div>

        {{-- Ornamen bawah card --}}
        <div class="card-ornament-bot">
            @if($isBersyarat)
            <svg width="100%" height="16" viewBox="0 0 320 16" preserveAspectRatio="xMidYMid meet" fill="none" xmlns="http://www.w3.org/2000/svg" opacity=".3">
                <line x1="0" y1="8" x2="108" y2="8" stroke="#92400e" stroke-width=".8"/>
                <polygon points="111,8 116,3 121,8 116,13" fill="none" stroke="#b45309" stroke-width=".8"/>
                <circle cx="131" cy="8" r="4" fill="none" stroke="#fb923c" stroke-width=".9"/>
                <circle cx="131" cy="8" r="1.5" fill="#fb923c"/>
                <circle cx="160" cy="8" r="2.5" fill="none" stroke="#b45309" stroke-width=".7"/>
                <circle cx="189" cy="8" r="4" fill="none" stroke="#fb923c" stroke-width=".9"/>
                <circle cx="189" cy="8" r="1.5" fill="#fb923c"/>
                <polygon points="199,8 204,3 209,8 204,13" fill="none" stroke="#b45309" stroke-width=".8"/>
                <line x1="212" y1="8" x2="320" y2="8" stroke="#92400e" stroke-width=".8"/>
            </svg>
            @elseif(!$isLulus && !$isBersyarat)
            <svg width="100%" height="16" viewBox="0 0 320 16" preserveAspectRatio="xMidYMid meet" fill="none" xmlns="http://www.w3.org/2000/svg" opacity=".3">
                <line x1="0" y1="8" x2="108" y2="8" stroke="#7f1d1d" stroke-width=".8"/>
                <polygon points="111,8 116,3 121,8 116,13" fill="none" stroke="#991b1b" stroke-width=".8"/>
                <circle cx="131" cy="8" r="4" fill="none" stroke="#dc2626" stroke-width=".9"/>
                <circle cx="131" cy="8" r="1.5" fill="#dc2626"/>
                <circle cx="160" cy="8" r="2.5" fill="none" stroke="#991b1b" stroke-width=".7"/>
                <circle cx="189" cy="8" r="4" fill="none" stroke="#dc2626" stroke-width=".9"/>
                <circle cx="189" cy="8" r="1.5" fill="#dc2626"/>
                <polygon points="199,8 204,3 209,8 204,13" fill="none" stroke="#991b1b" stroke-width=".8"/>
                <line x1="212" y1="8" x2="320" y2="8" stroke="#7f1d1d" stroke-width=".8"/>
            </svg>
            @else
            <svg width="100%" height="16" viewBox="0 0 320 16" preserveAspectRatio="xMidYMid meet" fill="none" xmlns="http://www.w3.org/2000/svg" opacity=".35">
                <line x1="0" y1="8" x2="108" y2="8" stroke="#1e5c35" stroke-width=".8"/>
                <polygon points="111,8 116,3 121,8 116,13" fill="none" stroke="#2d8050" stroke-width=".8"/>
                <circle cx="131" cy="8" r="4" fill="none" stroke="#4aad72" stroke-width=".9"/>
                <circle cx="131" cy="8" r="1.5" fill="#4aad72"/>
                <circle cx="160" cy="8" r="2.5" fill="none" stroke="#2d8050" stroke-width=".7"/>
                <circle cx="189" cy="8" r="4" fill="none" stroke="#4aad72" stroke-width=".9"/>
                <circle cx="189" cy="8" r="1.5" fill="#4aad72"/>
                <polygon points="199,8 204,3 209,8 204,13" fill="none" stroke="#2d8050" stroke-width=".8"/>
                <line x1="212" y1="8" x2="320" y2="8" stroke="#1e5c35" stroke-width=".8"/>
            </svg>
            @endif
        </div>
    </div>

    {{-- Tombol Surat --}}
    @if($isLulus)
    <a href="{{ route('siswa.surat', $siswa) }}" target="_blank" class="btn-surat">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
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
        </svg>
        📄 Surat belum tersedia — hubungi pihak sekolah
    </div>
    @endif

    {{-- Pesan / Quote --}}
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

    {{-- Upload momen: hanya LULUS --}}
    @if($isLulus)
    <div class="section-title">📸 Bagikan Momen Bahagia</div>
    <div class="upload-card">
        <form method="POST" action="{{ route('siswa.momen.upload') }}" enctype="multipart/form-data" id="formUpload">
            @csrf
            <div class="upload-preview-wrap" id="previewWrap" onclick="document.getElementById('inputFoto').click()">
                <img id="previewImg" src="" alt="preview">
                <canvas id="canvasResult"></canvas>
                <div class="upload-placeholder" id="uploadPlaceholder">
                    <span style="display:block;margin-bottom:.5rem">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="" style="width:48px;height:48px;object-fit:contain;opacity:.25;display:block;margin:0 auto">
                        @else
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="rgba(46,128,80,.4)" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" style="display:block;margin:0 auto"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
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
            <button class="btn-upload" type="submit" id="btnUpload" disabled>🚀 Bagikan ke Galeri</button>
        </form>
    </div>
    @endif

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
    <a class="link-galeri" href="{{ route('siswa.galeri') }}">👥 Lihat Galeri Semua Santri →</a>

    @endif {{-- end hasilTersembunyi --}}
</div>

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
function cdTick(){
    const diff=CD_TARGET-Date.now();
    if(diff<=0){window.location.reload();return;}
    document.getElementById('cdH').textContent=String(Math.floor(diff/86400000)).padStart(2,'0');
    document.getElementById('cdJ').textContent=String(Math.floor((diff%86400000)/3600000)).padStart(2,'0');
    document.getElementById('cdM').textContent=String(Math.floor((diff%3600000)/60000)).padStart(2,'0');
    document.getElementById('cdD').textContent=String(Math.floor((diff%60000)/1000)).padStart(2,'0');
}
cdTick();setInterval(cdTick,1000);
</script>
@else
<script>
const NAMA     = @json($siswa->nama);
const NISN     = @json($siswa->nisn);
const SEKOLAH  = @json($sekolahNama);
const TAHUN    = @json((string)$siswa->tahun_lulus);
const LOGO_URL = @json($logoUrl);
let rawImageBlob=null, stream=null;

(function(){
    const cvs=document.getElementById('skyCanvas');
    if(!cvs)return;
    const ctx=cvs.getContext('2d');
    function resize(){cvs.width=innerWidth;cvs.height=innerHeight;}
    resize();window.addEventListener('resize',resize);
    const stars=Array.from({length:80},()=>({
        x:Math.random()*innerWidth,y:Math.random()*innerHeight,
        r:.25+Math.random()*1,a:.03+Math.random()*.2,
        da:(Math.random()>.5?1:-1)*.002,vy:.01+Math.random()*.03,
    }));
    function draw(){
        ctx.clearRect(0,0,cvs.width,cvs.height);
        stars.forEach(s=>{
            s.a+=s.da;if(s.a>.25||s.a<.03)s.da*=-1;
            s.y-=s.vy;if(s.y<-2)s.y=cvs.height+2;
            ctx.beginPath();ctx.arc(s.x,s.y,s.r,0,Math.PI*2);
            ctx.fillStyle=`rgba(122,207,160,${s.a})`;ctx.fill();
        });
        requestAnimationFrame(draw);
    }
    draw();
})();

(function(){
    const canvas=document.getElementById('particleCanvas');
    if(!canvas)return;
    const card=canvas.parentElement;
    const ctx=canvas.getContext('2d');
    function resize(){canvas.width=card.offsetWidth;canvas.height=card.offsetHeight;}
    resize();window.addEventListener('resize',resize);
    @if($isBersyarat)
    const COLOR='rgba(251,146,60,';
    @elseif(!$isLulus && !$isBersyarat)
    const COLOR='rgba(220,38,38,';
    @else
    const COLOR='rgba(90,200,130,';
    @endif
    const pts=Array.from({length:16},()=>({
        x:Math.random()*card.offsetWidth,y:Math.random()*card.offsetHeight,
        r:.4+Math.random()*1.2,vx:(Math.random()-.5)*.18,
        vy:-.06-Math.random()*.14,a:.04+Math.random()*.22,
        da:(Math.random()>.5?1:-1)*.0022,
    }));
    function draw(){
        ctx.clearRect(0,0,canvas.width,canvas.height);
        pts.forEach(p=>{
            p.x+=p.vx;p.y+=p.vy;p.a+=p.da;
            if(p.a>.3||p.a<.04)p.da*=-1;
            if(p.y<-10)p.y=canvas.height+10;
            if(p.x<-10)p.x=canvas.width+10;
            if(p.x>canvas.width+10)p.x=-10;
            ctx.beginPath();ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
            ctx.fillStyle=COLOR+p.a+')';ctx.fill();
        });
        requestAnimationFrame(draw);
    }
    draw();
})();

@if($isLulus)
document.getElementById('inputFoto').addEventListener('change',function(){
    const file=this.files[0];if(!file)return;
    rawImageBlob=file;tampilPreview(URL.createObjectURL(file));
});
function tampilPreview(url){
    const img=document.getElementById('previewImg');
    img.src=url;img.style.display='block';
    document.getElementById('canvasResult').style.display='none';
    document.getElementById('uploadPlaceholder').style.display='none';
    document.getElementById('previewWrap').classList.add('has-img');
    document.getElementById('previewWrap').onclick=null;
    document.getElementById('btnOverlay').style.display='';
    document.getElementById('btnUpload').disabled=false;
}
async function bukaKamera(){
    try{
        stream=await navigator.mediaDevices.getUserMedia({video:{facingMode:'user',width:{ideal:720},height:{ideal:720}},audio:false});
        document.getElementById('videoEl').srcObject=stream;
        document.getElementById('modalKamera').classList.add('open');
    }catch(e){alert('Kamera tidak bisa diakses. Pastikan izin kamera sudah diberikan.');}
}
function tutupKamera(){
    if(stream){stream.getTracks().forEach(t=>t.stop());stream=null;}
    document.getElementById('modalKamera').classList.remove('open');
}
function captureFromCamera(){
    const video=document.getElementById('videoEl');
    const cvs=document.getElementById('canvasHidden');const size=720;
    cvs.width=size;cvs.height=size;
    const ctx=cvs.getContext('2d');
    ctx.save();ctx.scale(-1,1);ctx.drawImage(video,-size,0,size,size);ctx.restore();
    cvs.toBlob(blob=>{rawImageBlob=blob;tutupKamera();tampilPreview(URL.createObjectURL(blob));},'image/jpeg',.92);
}
function terapkanOverlay(){
    if(!rawImageBlob)return;
    const img=new Image();
    img.onload=function(){
        const cvs=document.getElementById('canvasHidden');const size=900;
        cvs.width=size;cvs.height=size;
        const ctx=cvs.getContext('2d');
        const s=Math.min(img.width,img.height);
        ctx.drawImage(img,(img.width-s)/2,(img.height-s)/2,s,s,0,0,size,size);
        const grad=ctx.createLinearGradient(0,size*.4,0,size);
        grad.addColorStop(0,'rgba(0,0,0,0)');
        grad.addColorStop(.5,'rgba(4,14,7,.68)');
        grad.addColorStop(1,'rgba(4,14,7,.92)');
        ctx.fillStyle=grad;ctx.fillRect(0,0,size,size);
        ctx.strokeStyle='rgba(46,128,80,.58)';ctx.lineWidth=6;ctx.strokeRect(3,3,size-6,size-6);
        const pad=36;let y=size-30;
        ctx.font='500 22px "Tajawal",sans-serif';ctx.fillStyle='rgba(90,200,130,.8)';ctx.textAlign='left';
        ctx.fillText(SEKOLAH+' · '+TAHUN,pad,y);y-=34;
        ctx.font='400 20px "Tajawal",sans-serif';ctx.fillStyle='rgba(142,203,166,.7)';
        ctx.fillText('NISN: '+NISN,pad,y);y-=44;
        ctx.font='700 40px "Scheherazade New",serif';ctx.fillStyle='#dff2e8';
        ctx.fillText(NAMA,pad,y);y-=52;
        ctx.font='700 28px "Tajawal",sans-serif';ctx.fillStyle='#4aad72';
        ctx.fillText('🎓 Selamat Lulus!',pad,y);
        function finalize(){
            const rc=document.getElementById('canvasResult');
            const ww=document.getElementById('previewWrap').offsetWidth;
            rc.width=ww;rc.height=ww;
            rc.getContext('2d').drawImage(cvs,0,0,size,size,0,0,ww,ww);
            document.getElementById('previewImg').style.display='none';rc.style.display='block';
            cvs.toBlob(function(blob){
                rawImageBlob=blob;
                const dt=new DataTransfer();dt.items.add(new File([blob],'momen.jpg',{type:'image/jpeg'}));
                document.getElementById('inputFoto').files=dt.files;
                if(_autoSubmit){_autoSubmit=false;document.getElementById('formUpload').submit();}
            },'image/jpeg',.92);
        }
        if(LOGO_URL){
            const li=new Image();
            li.onload=function(){
                const ls=110,lx=size-ls-32,ly=size-ls-32;
                const asp=li.width/li.height;let lw=ls,lh=ls;
                if(asp>1){lh=ls/asp;}else{lw=ls*asp;}
                ctx.drawImage(li,lx+(ls-lw)/2,ly+(ls-lh)/2,lw,lh);finalize();
            };
            li.onerror=finalize;li.src=LOGO_URL;
        }else{finalize();}
    };
    img.src=URL.createObjectURL(rawImageBlob);
}
document.getElementById('formUpload').addEventListener('submit',function(e){
    if(!rawImageBlob){e.preventDefault();alert('Pilih foto terlebih dahulu.');return;}
    if(!document.getElementById('inputFoto').files.length){
        e.preventDefault();
        const b=document.getElementById('btnUpload');b.disabled=true;b.innerHTML='⏳ Menyiapkan foto…';
        _autoSubmit=true;terapkanOverlay();
    }
});
var _autoSubmit=false;
@endif
</script>

@if($isLulus)
<script>
(function(){
    var colors=['#1e5c35','#2d8050','#4aad72','#7dcfa0','#b2eecb','#dff2e8'];
    for(var i=0;i<60;i++){
        var el=document.createElement('div');
        var sz=2+Math.random()*5;
        el.style.cssText='position:fixed;top:-10px;width:'+sz+'px;height:'+sz+'px;background:'+colors[Math.floor(Math.random()*colors.length)]+';left:'+Math.random()*100+'vw;border-radius:'+(Math.random()>.45?'50%':'2px')+';pointer-events:none;z-index:9999;animation:cf-sp '+(2.2+Math.random()*2.8)+'s '+(Math.random()*1.4)+'s ease-out forwards';
        document.body.appendChild(el);
        setTimeout(function(e){e.remove();},6000,el);
    }
    var s=document.createElement('style');
    s.textContent='@keyframes cf-sp{0%{transform:translateY(0) rotate(0deg);opacity:.9}100%{transform:translateY(105vh) rotate(540deg);opacity:0}}';
    document.head.appendChild(s);
})();
</script>
@endif

@endif

</body>
</html>