@extends('layouts.admin')
@section('title', $mode === 'create' ? 'Tambah Siswa' : 'Edit Siswa')

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <a href="{{ route('admin.siswa.index') }}" class="back-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
        </a>
        <div>
            <h2>{{ $mode === 'create' ? 'Tambah Siswa' : 'Edit Data Siswa' }}</h2>
            <p>{{ $mode === 'create' ? 'Isi data siswa baru di bawah ini' : 'Perbarui informasi siswa' }}</p>
        </div>
    </div>
</div>

{{-- Layout dua kolom: form kiri, foto+akun kanan (mode edit) --}}
<div class="form-layout">

    {{-- ═══ KOLOM KIRI: FORM DATA ═══ --}}
    <div class="form-col-main">
        <div class="section-card">
            <div class="section-card-header">
                <span class="section-icon">📋</span>
                <div>
                    <div class="section-title">Data Siswa</div>
                    <div class="section-sub">Informasi identitas dan akademik siswa</div>
                </div>
            </div>

            <form
                method="POST"
                action="{{ $mode === 'create' ? route('admin.siswa.store') : route('admin.siswa.update', $siswa) }}"
                id="form-siswa"
            >
                @csrf
                @if($mode === 'edit') @method('PUT') @endif

                <div class="form-grid">
                    {{-- NISN --}}
                    <div class="form-group">
                        <label for="nisn">NISN <span class="required-dot">*</span></label>
                        <input type="text" id="nisn" name="nisn" value="{{ old('nisn', $siswa->nisn) }}" placeholder="0012345678" maxlength="20" class="{{ $errors->has('nisn') ? 'is-error' : '' }}">
                        @error('nisn')<span class="err-msg"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>@enderror
                    </div>

                    {{-- Nama --}}
                    <div class="form-group">
                        <label for="nama">Nama Lengkap <span class="required-dot">*</span></label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama', $siswa->nama) }}" placeholder="Nama siswa" class="{{ $errors->has('nama') ? 'is-error' : '' }}">
                        @error('nama')<span class="err-msg"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>@enderror
                    </div>

                    {{-- Kelas --}}
                    <div class="form-group">
                        <label for="kelas">Kelas <span class="required-dot">*</span></label>
                        <input type="text" id="kelas" name="kelas" value="{{ old('kelas', $siswa->kelas) }}" placeholder="XII IPA 1" class="{{ $errors->has('kelas') ? 'is-error' : '' }}">
                        @error('kelas')<span class="err-msg"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>@enderror
                    </div>

                    {{-- Tahun Lulus --}}
                    <div class="form-group">
                        <label for="tahun_lulus">Tahun Lulus <span class="required-dot">*</span></label>
                        <input type="number" id="tahun_lulus" name="tahun_lulus" value="{{ old('tahun_lulus', $siswa->tahun_lulus ?? date('Y')) }}" min="2000" max="2099" class="{{ $errors->has('tahun_lulus') ? 'is-error' : '' }}">
                        @error('tahun_lulus')<span class="err-msg"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>@enderror
                    </div>

                    {{-- Nilai Rata-rata --}}
                    <div class="form-group">
                        <label for="nilai_rata">Nilai Rata-rata <span class="optional-tag">Opsional</span></label>
                        <input type="number" id="nilai_rata" name="nilai_rata" value="{{ old('nilai_rata', $siswa->nilai_rata) }}" step="0.01" min="0" max="100" placeholder="85.50" class="{{ $errors->has('nilai_rata') ? 'is-error' : '' }}">
                        @error('nilai_rata')<span class="err-msg"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>@enderror
                    </div>

                    {{-- Status --}}
                    <div class="form-group">
                        <label for="status">Status <span class="required-dot">*</span></label>
                        <div class="select-wrap">
                            <select id="status" name="status" class="{{ $errors->has('status') ? 'is-error' : '' }}">
                                <option value="LULUS"           {{ old('status', $siswa->status) === 'LULUS'           ? 'selected' : '' }}>LULUS</option>
                                <option value="LULUS BERSYARAT" {{ old('status', $siswa->status) === 'LULUS BERSYARAT' ? 'selected' : '' }}>LULUS BERSYARAT</option>
                                <option value="TIDAK LULUS"     {{ old('status', $siswa->status) === 'TIDAK LULUS'     ? 'selected' : '' }}>TIDAK LULUS</option>
                            </select>
                            <svg class="select-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                        @error('status')<span class="err-msg"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>@enderror
                    </div>

                    {{-- Catatan --}}
                    <div class="form-group full">
                        <label for="catatan">Catatan <span class="optional-tag">Opsional</span></label>
                        <textarea id="catatan" name="catatan" rows="3" placeholder="Catatan tambahan tentang siswa ini…" class="{{ $errors->has('catatan') ? 'is-error' : '' }}">{{ old('catatan', $siswa->catatan) }}</textarea>
                        @error('catatan')<span class="err-msg"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-actions">
                    <button class="btn btn-gold" type="submit">
                        @if($mode === 'create')
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Simpan Data
                        @else
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            Perbarui Data
                        @endif
                    </button>
                    <a href="{{ route('admin.siswa.index') }}" class="btn btn-ghost">Batal</a>
                </div>
            </form>
        </div>
    </div>{{-- end .form-col-main --}}

    {{-- ═══ KOLOM KANAN: FOTO + AKUN (mode edit saja) ═══ --}}
    @if($mode === 'edit')
    <div class="form-col-side">

        {{-- ── SECTION: FOTO PROFIL ── --}}
        <div class="section-card">
            <div class="section-card-header">
                <span class="section-icon">🖼</span>
                <div style="flex:1">
                    <div class="section-title">Foto Profil</div>
                    <div class="section-sub">Foto resmi siswa</div>
                </div>
                @if($fotoProfilUrl)
                    <span class="status-badge status-aktif">
                        <span class="status-dot"></span>Ada Foto
                    </span>
                @else
                    <span class="status-badge status-belum">Belum Ada Foto</span>
                @endif
            </div>

            @if(session('foto_success'))
                <div class="alert alert-success" style="margin-bottom:1rem">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ session('foto_success') }}
                </div>
            @endif
            @error('foto_profil')
                <div class="alert alert-err" style="margin-bottom:1rem">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $message }}
                </div>
            @enderror

            <div class="foto-area">
                {{-- Preview --}}
                <div class="foto-preview" id="fotoPreviewWrap">
                    @if($fotoProfilUrl)
                        <img id="fotoPreviewImg" src="{{ $fotoProfilUrl }}" alt="{{ $siswa->nama }}">
                    @else
                        <div id="fotoPreviewImg" class="foto-placeholder">
                            <span>👤</span>
                        </div>
                    @endif
                </div>

                {{-- Aksi --}}
                <div class="foto-actions">
                    <form method="POST"
                          action="{{ route('admin.siswa.foto.upload', $siswa) }}"
                          enctype="multipart/form-data"
                          id="form-foto-upload">
                        @csrf

                        <input type="file"
                               name="foto_profil"
                               id="fotoInput"
                               accept="image/jpg,image/jpeg,image/png,image/webp"
                               style="display:none"
                               onchange="onFotoSelected(this)">

                        <div id="fotoSelectedInfo" style="display:none;margin-bottom:.75rem">
                            <div style="display:flex;align-items:center;gap:.5rem;padding:.5rem .75rem;background:rgba(201,168,76,.06);border:1px solid rgba(201,168,76,.2);border-radius:.5rem">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#c9a84c" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                <span id="fotoSelectedName" style="font-size:.75rem;color:var(--gold2,#c9a84c);font-weight:600;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"></span>
                                <button type="button" onclick="batalFoto()" style="background:none;border:none;color:var(--muted);cursor:pointer;font-size:.8rem;padding:0;line-height:1;min-width:24px;min-height:24px">✕</button>
                            </div>
                        </div>

                        <div class="foto-btn-group" id="foto-btn-group">
                            <button type="button" class="btn btn-ghost" id="btnPilihFoto" onclick="document.getElementById('fotoInput').click()">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                                {{ $fotoProfilUrl ? 'Ganti Foto' : 'Pilih Foto' }}
                            </button>

                            <button type="submit" class="btn btn-gold" id="btnUploadFoto" style="display:none">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Upload Foto
                            </button>

                            @if($fotoProfilUrl)
                            <form method="POST"
                                  action="{{ route('admin.siswa.foto.hapus', $siswa) }}"
                                  style="margin:0"
                                  id="form-hapus-foto"
                                  onsubmit="return confirm('Hapus foto profil {{ $siswa->nama }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                                    Hapus
                                </button>
                            </form>
                            @endif
                        </div>

                        <div style="font-size:.72rem;color:var(--muted);margin-top:.625rem;line-height:1.6">
                            JPG, PNG, atau WebP · Maks 2MB
                        </div>
                    </form>

                    <div class="import-hint">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="flex-shrink:0;margin-top:.1rem"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span>Untuk upload massal, gunakan <a href="{{ route('admin.siswa.import-form') }}">Import Foto (ZIP)</a>.</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── SECTION: AKUN PORTAL ── --}}
        @php $akunSiswa = \App\Models\SiswaAccount::where('nisn', $siswa->nisn)->first(); @endphp
        <div class="section-card" style="margin-top:1rem">
            <div class="section-card-header">
                <span class="section-icon">🔐</span>
                <div style="flex:1">
                    <div class="section-title">Akun Portal Siswa</div>
                    <div class="section-sub">Akun login ke portal momen bahagia</div>
                </div>
                @if($akunSiswa)
                    <span class="status-badge status-aktif">
                        <span class="status-dot"></span>Aktif
                    </span>
                @else
                    <span class="status-badge status-belum">Belum Ada Akun</span>
                @endif
            </div>

            @if(session('akun_success'))
                <div class="alert alert-success">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
                    {{ session('akun_success') }}
                </div>
            @endif
            @error('akun_password')
                <div class="alert alert-err">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $message }}
                </div>
            @enderror

            <form method="POST" action="{{ route('admin.siswa.akun', $siswa) }}" id="form-akun">
                @csrf

                @if($akunSiswa)
                <div class="akun-info-bar">
                    <div class="akun-info-item">
                        <span class="akun-info-label">NISN Login</span>
                        <span class="akun-info-val">{{ $siswa->nisn }}</span>
                    </div>
                    <div class="akun-info-divider"></div>
                    <div class="akun-info-item">
                        <span class="akun-info-label">Dibuat</span>
                        <span class="akun-info-val">{{ $akunSiswa->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="akun-info-divider"></div>
                    <div class="akun-info-item">
                        <span class="akun-info-label">Diperbarui</span>
                        <span class="akun-info-val">{{ $akunSiswa->updated_at->format('d M Y') }}</span>
                    </div>
                </div>
                @endif

                <div class="form-grid" style="margin-top:1.25rem">
                    <div class="form-group">
                        <label for="akun_password">
                            {{ $akunSiswa ? 'Password Baru' : 'Password' }}
                            @if(!$akunSiswa) <span class="required-dot">*</span> @else <span class="optional-tag">Kosongkan jika tidak diubah</span> @endif
                        </label>
                        <div class="pw-wrap">
                            <input type="password" id="akun_password" name="akun_password" placeholder="{{ $akunSiswa ? 'Isi untuk mengganti…' : 'Minimal 6 karakter' }}" autocomplete="new-password">
                            <button type="button" class="pw-toggle" onclick="togglePw('akun_password', this)" title="Tampilkan password">
                                <svg class="eye-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="akun_password_confirmation">Konfirmasi Password</label>
                        <div class="pw-wrap">
                            <input type="password" id="akun_password_confirmation" name="akun_password_confirmation" placeholder="Ulangi password" autocomplete="new-password">
                            <button type="button" class="pw-toggle" onclick="togglePw('akun_password_confirmation', this)" title="Tampilkan password">
                                <svg class="eye-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-actions" style="margin-top:1.25rem">
                    <button class="btn btn-gold" type="submit">
                        @if($akunSiswa)
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Perbarui Password
                        @else
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Buat Akun
                        @endif
                    </button>
                    @if($akunSiswa)
                    <button class="btn btn-danger" type="submit" name="hapus_akun" value="1"
                        onclick="return confirm('Hapus akun portal siswa ini? Semua foto momen akan ikut terhapus.')">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                        Hapus Akun
                    </button>
                    @endif
                </div>
            </form>
        </div>

    </div>{{-- end .form-col-side --}}
    @endif

</div>{{-- end .form-layout --}}

<style>
/* ── Page Header ── */
.page-header { margin-bottom: 1.5rem; }
.page-header-left { display: flex; align-items: center; gap: 1rem; }
.back-btn {
    display: flex; align-items: center; justify-content: center;
    width: 2.5rem; height: 2.5rem; border-radius: .625rem;
    border: 1px solid var(--border); color: var(--muted);
    text-decoration: none; background: rgba(255,255,255,.03); transition: all .2s; flex-shrink: 0;
}
.back-btn:hover { background: rgba(255,255,255,.07); color: var(--text); border-color: rgba(255,255,255,.15); }
.page-header h2 { font-size: 1.15rem; font-weight: 600; margin: 0 0 .2rem; }
.page-header p { font-size: .78rem; color: var(--muted); margin: 0; }

/* ── Layout dua kolom ── */
.form-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 1.25rem;
    align-items: start;
}

/* ── Section Card ── */
.section-card {
    background: var(--card, rgba(255,255,255,.04));
    border: 1px solid var(--border); border-radius: 1rem; padding: 1.5rem;
}
.section-card-header {
    display: flex; align-items: flex-start; gap: .875rem;
    padding-bottom: 1.25rem; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border);
}
.section-icon { font-size: 1.1rem; line-height: 1; margin-top: .1rem; }
.section-title { font-size: .875rem; font-weight: 600; margin-bottom: .15rem; }
.section-sub { font-size: .73rem; color: var(--muted); }

/* ── Foto Profil ── */
.foto-area { display: flex; gap: 1rem; align-items: flex-start; }
.foto-preview {
    width: 88px; height: 88px; flex-shrink: 0;
    border-radius: .875rem; overflow: hidden;
    background: rgba(255,255,255,.04); border: 1px solid var(--border);
}
.foto-preview img { width: 100%; height: 100%; object-fit: cover; display: block; }
.foto-placeholder { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 2.25rem; }
.foto-actions { flex: 1; min-width: 0; }
.foto-btn-group { display: flex; gap: .5rem; flex-wrap: wrap; }
.import-hint {
    display: flex; align-items: flex-start; gap: .4rem;
    margin-top: .875rem; padding: .625rem .875rem;
    background: rgba(255,255,255,.03); border: 1px solid var(--border);
    border-radius: .625rem; font-size: .72rem; color: var(--muted); line-height: 1.5;
}
.import-hint a { color: var(--gold2, #c9a84c); text-decoration: none; }
.import-hint a:hover { text-decoration: underline; }

/* ── Form ── */
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem 1.25rem; }
.form-group { display: flex; flex-direction: column; gap: .45rem; }
.form-group.full { grid-column: 1 / -1; }
label { font-size: .75rem; font-weight: 600; color: var(--text); display: flex; align-items: center; gap: .4rem; flex-wrap: wrap; }
.required-dot { color: var(--gold2, #c9a84c); font-size: .8rem; line-height: 1; }
.optional-tag { font-weight: 400; font-size: .68rem; color: var(--muted); background: rgba(255,255,255,.06); border: 1px solid var(--border); padding: .1rem .45rem; border-radius: 999px; }

input[type="text"],input[type="number"],input[type="password"],textarea,select {
    width: 100%; background: rgba(255,255,255,.04); border: 1px solid var(--border); border-radius: .625rem;
    padding: .65rem .875rem; font-size: .825rem; color: var(--text);
    transition: border-color .2s, box-shadow .2s, background .2s; outline: none; font-family: inherit; box-sizing: border-box;
    min-height: 44px;
}
input:focus,textarea:focus,select:focus { border-color: var(--gold2, #c9a84c); box-shadow: 0 0 0 3px rgba(201,168,76,.12); background: rgba(255,255,255,.06); }
input.is-error,textarea.is-error,select.is-error { border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220,38,38,.1); }
textarea { resize: vertical; min-height: 5rem; }

.select-wrap { position: relative; }
.select-wrap select { appearance: none; padding-right: 2.25rem; cursor: pointer; }
.select-arrow { position: absolute; right: .75rem; top: 50%; transform: translateY(-50%); pointer-events: none; color: var(--muted); }
.err-msg { font-size: .72rem; color: #FCA5A5; display: flex; align-items: center; gap: .3rem; }

/* ── Buttons ── */
.form-actions { display: flex; gap: .75rem; flex-wrap: wrap; }
.btn {
    display: inline-flex; align-items: center; gap: .45rem;
    padding: .625rem 1.125rem; border-radius: .625rem; min-height: 44px;
    font-size: .8rem; font-weight: 600; font-family: inherit;
    cursor: pointer; border: none; text-decoration: none; transition: all .2s; white-space: nowrap;
}
.btn-gold { background: var(--gold2, #c9a84c); color: #1a1508; }
.btn-gold:hover { filter: brightness(1.1); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(201,168,76,.25); }
.btn-ghost { background: rgba(255,255,255,.06); color: var(--muted); border: 1px solid var(--border); }
.btn-ghost:hover { background: rgba(255,255,255,.1); color: var(--text); }
.btn-danger { background: rgba(220,38,38,.1); color: #FCA5A5; border: 1px solid rgba(220,38,38,.25); }
.btn-danger:hover { background: rgba(220,38,38,.18); border-color: rgba(220,38,38,.4); }

/* ── Status Badge ── */
.status-badge { display: inline-flex; align-items: center; gap: .35rem; font-size: .68rem; font-weight: 600; padding: .3rem .8rem; border-radius: 999px; flex-shrink: 0; white-space: nowrap; }
.status-aktif { background: rgba(34,197,94,.1); border: 1px solid rgba(34,197,94,.25); color: #86efac; }
.status-dot { width: 6px; height: 6px; border-radius: 50%; background: #22c55e; animation: pulse-dot 2s ease-in-out infinite; flex-shrink: 0; }
@keyframes pulse-dot { 0%,100%{opacity:1} 50%{opacity:.4} }
.status-belum { background: rgba(255,255,255,.04); border: 1px solid var(--border); color: var(--muted); }

/* ── Akun Info Bar ── */
.akun-info-bar { display: flex; align-items: center; background: rgba(255,255,255,.03); border: 1px solid var(--border); border-radius: .75rem; overflow: hidden; }
.akun-info-item { flex: 1; display: flex; flex-direction: column; gap: .2rem; padding: .75rem 1rem; min-width: 0; }
.akun-info-divider { width: 1px; height: 2.5rem; background: var(--border); flex-shrink: 0; }
.akun-info-label { font-size: .65rem; color: var(--muted); text-transform: uppercase; letter-spacing: .05em; }
.akun-info-val { font-size: .78rem; font-weight: 600; color: var(--gold2, #c9a84c); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

/* ── Password Toggle ── */
.pw-wrap { position: relative; }
.pw-wrap input { padding-right: 2.75rem; }
.pw-toggle { position: absolute; right: .75rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: .3rem; color: var(--muted); display: flex; align-items: center; transition: color .2s; min-width: 32px; min-height: 32px; justify-content: center; }
.pw-toggle:hover { color: var(--text); }

/* ── Alerts ── */
.alert { display: flex; align-items: center; gap: .6rem; padding: .7rem 1rem; border-radius: .75rem; font-size: .8rem; margin-bottom: 1rem; }
.alert-success { background: rgba(34,197,94,.08); border: 1px solid rgba(34,197,94,.2); border-left: 3px solid #22c55e; color: #86efac; }
.alert-err { background: rgba(220,38,38,.08); border: 1px solid rgba(220,38,38,.2); border-left: 3px solid #dc2626; color: #FCA5A5; }

/* ── Responsive ── */
@media (max-width: 1100px) {
    .form-layout { grid-template-columns: 1fr 340px; }
}

@media (max-width: 860px) {
    .form-layout { grid-template-columns: 1fr; }
    .form-col-side { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-col-side .section-card { margin-top: 0 !important; }
}

@media (max-width: 640px) {
    .form-grid { grid-template-columns: 1fr; }
    .form-col-side { grid-template-columns: 1fr; }
    .form-actions { flex-direction: column; }
    .form-actions .btn { width: 100%; justify-content: center; }
    .section-card { padding: 1rem; }
    .section-card-header { flex-wrap: wrap; }
    .akun-info-bar { flex-direction: column; }
    .akun-info-divider { width: 100%; height: 1px; }
    .foto-area { flex-direction: column; align-items: stretch; }
    .foto-preview { width: 100%; height: 200px; }
    .foto-btn-group { flex-direction: column; }
    .foto-btn-group .btn { width: 100%; justify-content: center; }
}
</style>

<script>
function togglePw(inputId, btn) {
    var inp = document.getElementById(inputId);
    var isText = inp.type === 'text';
    inp.type = isText ? 'password' : 'text';
    btn.style.color = isText ? '' : 'var(--gold2)';
}

function onFotoSelected(input) {
    if (!input.files || !input.files[0]) return;
    var file = input.files[0];
    var reader = new FileReader();
    reader.onload = function(e) {
        var wrap = document.getElementById('fotoPreviewWrap');
        wrap.innerHTML = '<img src="' + e.target.result + '" alt="Preview" style="width:100%;height:100%;object-fit:cover;display:block;border-radius:.875rem">';
    };
    reader.readAsDataURL(file);
    document.getElementById('fotoSelectedName').textContent = file.name;
    document.getElementById('fotoSelectedInfo').style.display = 'block';
    document.getElementById('btnUploadFoto').style.display = 'inline-flex';
    document.getElementById('btnPilihFoto').innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg> Ganti';
}

function batalFoto() {
    document.getElementById('fotoInput').value = '';
    document.getElementById('fotoSelectedInfo').style.display = 'none';
    document.getElementById('btnUploadFoto').style.display = 'none';
    document.getElementById('btnPilihFoto').innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg> {{ $fotoProfilUrl ? "Ganti Foto" : "Pilih Foto" }}';
    var wrap = document.getElementById('fotoPreviewWrap');
    @if($fotoProfilUrl)
        wrap.innerHTML = '<img src="{{ $fotoProfilUrl }}" alt="{{ $siswa->nama }}" style="width:100%;height:100%;object-fit:cover;display:block">';
    @else
        wrap.innerHTML = '<div class="foto-placeholder"><span>👤</span></div>';
    @endif
}
</script>

@endsection