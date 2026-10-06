@extends('murid.layouts.app')

@section('title', 'Beranda')
@section('page-title', 'Beranda')

@push('styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, rgba(79,70,229,.28) 0%, rgba(99,102,241,.12) 100%);
        border: 1px solid rgba(129,140,248,.22);
        border-radius: 20px 0 20px 0;
        padding: 2rem 1.75rem;
        display: flex; align-items: center; gap: 1.5rem;
        flex-wrap: wrap; margin-bottom: 1.75rem;
        position: relative; overflow: hidden;
    }

    .welcome-card::before {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(129,140,248,.06) 0%, transparent 60%);
        pointer-events: none;
    }

    .welcome-avatar {
        width: 64px; height: 64px; border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #818cf8);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; font-weight: 800; color: #fff; flex-shrink: 0;
        border: 3px solid rgba(129,140,248,.35);
    }

    .welcome-title { font-size: 1.2rem; font-weight: 800; color: #fff; margin: 0 0 .25rem; }
    .welcome-sub   { font-size: .82rem; color: rgba(226,232,255,.5); margin: 0; }

    /* Summary cards */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    .sum-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(129,140,248,.14);
        border-radius: 16px 0 16px 0;
        padding: 1.25rem 1rem;
        display: flex; flex-direction: column; align-items: center; gap: .4rem;
        transition: border-color .2s;
    }

    .sum-card:hover { border-color: rgba(129,140,248,.35); }

    .sum-icon {
        width: 42px; height: 42px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem; margin-bottom: .1rem;
    }

    .sum-icon-hadir  { background: rgba(34,197,94,.12);  color: #4ade80; }
    .sum-icon-izin   { background: rgba(251,191,36,.1);  color: #fbbf24; }
    .sum-icon-tidak  { background: rgba(248,113,113,.1); color: #f87171; }
    .sum-icon-total  { background: rgba(129,140,248,.12); color: #818cf8; }

    .sum-val  { font-size: 1.8rem; font-weight: 800; color: #fff; line-height: 1; }
    .sum-label { font-size: .72rem; font-weight: 600; color: rgba(226,232,255,.45); text-transform: uppercase; letter-spacing: .06em; }

    .sum-sub { font-size: .7rem; color: rgba(226,232,255,.28); margin-top: .1rem; }

    /* Loading */
    .spinner-purple {
        width: 22px; height: 22px;
        border: 3px solid rgba(129,140,248,.15);
        border-top-color: #818cf8;
        border-radius: 50%;
        animation: spin .65s linear infinite;
        display: inline-block; vertical-align: middle;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    /* Shortcut */
    .shortcut-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px,1fr));
        gap: 1rem;
    }

    .shortcut-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(129,140,248,.14);
        border-radius: 14px;
        padding: 1.25rem 1rem;
        display: flex; flex-direction: column; align-items: center; gap: .5rem;
        text-decoration: none; color: rgba(226,232,255,.7);
        font-size: .82rem; font-weight: 600;
        transition: background .18s, border-color .18s, transform .15s;
        text-align: center;
    }

    .shortcut-card:hover {
        background: rgba(129,140,248,.12);
        border-color: rgba(129,140,248,.35);
        color: #818cf8; transform: translateY(-2px);
    }

    .shortcut-card i { font-size: 1.5rem; }
</style>
@endpush

@section('content')

{{-- Welcome --}}
<div class="welcome-card">
    <div class="welcome-avatar">
        {{ strtoupper(substr(Auth::user()->name ?? 'M', 0, 1)) }}
    </div>
    <div>
        <p class="welcome-title">Halo, {{ Auth::user()->name ?? 'Murid' }}! 👋</p>
        <p class="welcome-sub">Selamat datang di portal absensi. Pantau kehadiranmu di sini.</p>
    </div>
</div>

{{-- Summary Bulan Ini --}}
<p style="font-size:.75rem;font-weight:700;color:rgba(129,140,248,.65);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.75rem;">
    <i class="bi bi-calendar3 me-1"></i>Ringkasan Bulan Ini
</p>

<div class="summary-grid" id="summaryGrid">
    <div style="grid-column:1/-1;text-align:center;padding:1.5rem;color:rgba(226,232,255,.3);">
        <span class="spinner-purple"></span> Memuat...
    </div>
</div>

{{-- Shortcut --}}
<p style="font-size:.75rem;font-weight:700;color:rgba(129,140,248,.65);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.75rem;">
    <i class="bi bi-lightning-fill me-1"></i>Menu Cepat
</p>

<div class="shortcut-grid">
    <a href="{{ route('murid.absen') }}" class="shortcut-card">
        <i class="bi bi-clipboard2-check-fill" style="color:#818cf8;"></i>
        Absensi
    </a>
    <a href="{{ route('murid.riwayat_absen') }}" class="shortcut-card">
        <i class="bi bi-clock-history" style="color:#fbbf24;"></i>
        Riwayat
    </a>
</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    fetch('{{ route("murid.absen.summary") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        var bulan = new Date().toLocaleDateString('id-ID',{month:'long',year:'numeric'});
        document.getElementById('summaryGrid').innerHTML =
            card('bi-check-circle-fill','sum-icon-hadir', d.hadir, 'Hadir')
          + card('bi-envelope-fill',     'sum-icon-izin',  d.izin,  'Izin')
          + card('bi-x-circle-fill',     'sum-icon-tidak', d.tidak, 'Tidak Hadir')
          + card('bi-bar-chart-fill',    'sum-icon-total', d.total, 'Total');
    })
    .catch(function () {
        document.getElementById('summaryGrid').innerHTML =
            '<div style="grid-column:1/-1;text-align:center;color:#f87171;padding:1rem;">Gagal memuat data.</div>';
    });

    function card(icon, cls, val, label) {
        return '<div class="sum-card">'
             + '<div class="sum-icon '+cls+'"><i class="bi '+icon+'"></i></div>'
             + '<div class="sum-val">' + (val||0) + '</div>'
             + '<div class="sum-label">' + label + '</div>'
             + '</div>';
    }
}());
</script>
@endpush
