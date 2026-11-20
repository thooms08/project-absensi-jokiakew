@extends('guru.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Beranda')

@push('styles')
<style>
    /* ============================================================
       WELCOME CARD
    ============================================================ */
    .welcome-card {
        background: linear-gradient(135deg, rgba(10,124,74,0.25) 0%, rgba(6,78,46,0.15) 100%);
        border: 1px solid rgba(74,222,128,0.18);
        border-radius: 20px 0 20px 0;
        padding: 2rem 1.75rem;
        display: flex; align-items: center; gap: 1.5rem;
        flex-wrap: wrap; margin-bottom: 1.75rem;
        position: relative; overflow: hidden;
    }

    .welcome-card::before {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(74,222,128,0.06) 0%, transparent 60%);
        pointer-events: none;
    }

    .welcome-avatar {
        width: 64px; height: 64px; border-radius: 50%;
        background: linear-gradient(135deg, #16a34a, #4ade80);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; font-weight: 800; color: #fff; flex-shrink: 0;
        border: 3px solid rgba(74,222,128,0.35);
        overflow: hidden;
    }

    .welcome-avatar img { width: 100%; height: 100%; object-fit: cover; }

    .welcome-title { font-size: 1.2rem; font-weight: 800; color: #fff; margin: 0 0 .25rem; }
    .welcome-sub   { font-size: .82rem; color: rgba(226,245,234,.6); margin: 0 0 .75rem; }

    .info-strip {
        display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;
    }

    .info-chip {
        display: inline-flex; align-items: center; gap: 0.3rem;
        background: rgba(74,222,128,0.08); border: 1px solid rgba(74,222,128,0.15);
        border-radius: 50px; padding: 0.22rem 0.65rem;
        font-size: 0.72rem; color: rgba(255,255,255,0.55);
    }

    .info-chip i { color: #4ade80; font-size: 0.75rem; }

    /* ============================================================
       SECTION LABEL
    ============================================================ */
    .section-label {
        font-size: .75rem; font-weight: 700;
        color: rgba(74,222,128,.65);
        text-transform: uppercase; letter-spacing: .08em;
        margin-bottom: .75rem;
    }

    /* ============================================================
       SHORTCUT GRID  –  icon-only style (sama seperti murid)
    ============================================================ */
    .shortcut-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    .shortcut-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(74,222,128,.14);
        border-radius: 14px;
        padding: 1.25rem 1rem;
        display: flex; flex-direction: column; align-items: center; gap: .5rem;
        text-decoration: none; color: rgba(226,245,234,.7);
        font-size: .82rem; font-weight: 600;
        transition: background .18s, border-color .18s, transform .15s;
        text-align: center;
    }

    .shortcut-card:hover {
        background: rgba(74,222,128,.12);
        border-color: rgba(74,222,128,.35);
        color: #4ade80; transform: translateY(-2px);
    }

    .shortcut-card i { font-size: 1.5rem; }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 767.98px) {
        .welcome-card { padding: 1.5rem 1.25rem; }
        .welcome-title { font-size: 1.05rem; }
        .shortcut-grid { grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); }
    }
</style>
@endpush

@section('content')

{{-- Welcome --}}
<div class="welcome-card">
    @php
        $guruImg = Auth::user()->image ?? null;
        $guruFotoUrl = $guruImg
            ? route('guru.profile.foto', ['filename' => basename($guruImg)])
            : null;
    @endphp
    <div class="welcome-avatar">
        @if($guruFotoUrl)
            <img src="{{ $guruFotoUrl }}" alt="Foto profil">
        @else
            {{ strtoupper(substr(Auth::user()->name ?? 'G', 0, 1)) }}
        @endif
    </div>
    <div>
        <p class="welcome-title">Halo, {{ Auth::user()->name ?? 'Guru' }}!</p>
        <p class="welcome-sub">Selamat datang di panel guru. Kelola kelas dan absensi di sini.</p>
        <div class="info-strip">
            <span class="info-chip">
                <i class="bi bi-calendar3"></i>
                {{ now()->translatedFormat('l, d F Y') }}
            </span>
            <span class="info-chip">
                <i class="bi bi-clock-fill"></i>
                <span id="jamSekarang">{{ now()->format('H:i') }}</span>
            </span>
        </div>
    </div>
</div>

{{-- Shortcut Menu --}}
<p class="section-label">
    <i class="bi bi-lightning-fill me-1"></i>Akses Cepat
</p>

<div class="shortcut-grid">

    <a href="{{ route('guru.absen_guru') }}" class="shortcut-card">
        <i class="bi bi-clipboard2-check-fill" style="color:#4ade80;"></i>
        Absensi Saya
    </a>

    <a href="{{ route('guru.absen_murid') }}" class="shortcut-card">
        <i class="bi bi-clipboard2-pulse-fill" style="color:#38bdf8;"></i>
        Absensi Murid
    </a>

    <a href="{{ route('guru.jadwal_mengajar') }}" class="shortcut-card">
        <i class="bi bi-calendar2-week-fill" style="color:#fbbf24;"></i>
        Jadwal Mengajar
    </a>

    <a href="{{ route('guru.data_kelas') }}" class="shortcut-card">
        <i class="bi bi-mortarboard-fill" style="color:#a78bfa;"></i>
        Data Kelas
    </a>

    <a href="{{ route('guru.data_murid') }}" class="shortcut-card">
        <i class="bi bi-people-fill" style="color:#fb923c;"></i>
        Data Murid
    </a>

    <a href="{{ route('guru.profile') }}" class="shortcut-card">
        <i class="bi bi-person-circle" style="color:#34d399;"></i>
        Profil Saya
    </a>

</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    function updateJam() {
        var el = document.getElementById('jamSekarang');
        if (!el) return;
        var now = new Date();
        el.textContent = String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');
    }

    updateJam();
    setInterval(updateJam, 30000);
}());
</script>
@endpush
