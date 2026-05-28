@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')

<div class="page-header">
    <h2>Dashboard</h2>
    <p>Ringkasan data kelulusan tahun pelajaran {{ $tahunPelajaran }}</p>
</div>

<div class="stat-grid">
    <div class="stat">
        <div class="stat-val">{{ number_format($total) }}</div>
        <div class="stat-lbl">Total Peserta Didik</div>
    </div>
    <div class="stat">
        <div class="stat-val" style="color:#86efac">{{ number_format($lulus) }}</div>
        <div class="stat-lbl">Dinyatakan Lulus</div>
    </div>
    <div class="stat">
        <div class="stat-val" style="color:#fdba74">{{ number_format($lulusBersyarat) }}</div>
        <div class="stat-lbl">Lulus Bersyarat</div>
    </div>
    <div class="stat">
        <div class="stat-val" style="color:#fca5a5">{{ number_format($tidakLulus) }}</div>
        <div class="stat-lbl">Belum Lulus</div>
    </div>
    <div class="stat">
        <div class="stat-val">{{ $total > 0 ? number_format(($lulus / $total) * 100, 1) : 0 }}%</div>
        <div class="stat-lbl">Persentase Lulus Penuh</div>
    </div>
</div>

<div class="dash-actions">
    <a href="{{ route('admin.siswa.create') }}" class="btn btn-gold">➕ Tambah Siswa</a>
    <a href="{{ route('admin.siswa.import-form') }}" class="btn btn-ghost">📥 Import Excel</a>
    <a href="{{ route('admin.siswa.index') }}" class="btn btn-ghost">👥 Lihat Semua Siswa</a>
</div>

<style>
    .stat-grid {
        grid-template-columns: repeat(5, 1fr);
    }

    .dash-actions {
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
    }
    .dash-actions .btn {
        flex: 1 1 auto;
        justify-content: center;
        min-width: 140px;
    }

    @media(max-width: 1100px) {
        .stat-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media(max-width: 900px) {
        .stat-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media(max-width: 480px) {
        .stat-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: .625rem;
        }
        .stat {
            padding: 1rem;
        }
        .stat-val {
            font-size: 1.75rem;
        }
        .dash-actions .btn {
            min-width: 0;
            flex: 1 1 calc(50% - .375rem);
        }
    }
</style>

@endsection