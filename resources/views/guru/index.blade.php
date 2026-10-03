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
        padding: 2.5rem 2rem;
        display: flex;
        align-items: center;
        gap: 1.75rem;
        margin-bottom: 1.75rem;
        position: relative;
        overflow: hidden;
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(74,222,128,0.06) 0%, transparent 60%);
        pointer-events: none;
    }

    .welcome-icon-wrap {
        width: 72px;
        height: 72px;
        border-radius: 18px;
        background: rgba(74,222,128,0.12);
        border: 1px solid rgba(74,222,128,0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #4ade80;
        flex-shrink: 0;
    }

    .welcome-body h2 {
        font-size: 1.45rem;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 0.35rem;
        line-height: 1.25;
    }

    .welcome-body p {
        font-size: 0.88rem;
        color: rgba(255,255,255,0.55);
        margin: 0;
        line-height: 1.6;
    }

    .welcome-body .name-highlight {
        color: #4ade80;
        font-weight: 700;
    }

    /* ============================================================
       INFO STRIP
    ============================================================ */
    .info-strip {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }

    .info-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(74,222,128,0.08);
        border: 1px solid rgba(74,222,128,0.15);
        border-radius: 50px;
        padding: 0.28rem 0.75rem;
        font-size: 0.75rem;
        color: rgba(255,255,255,0.55);
    }

    .info-chip i { color: #4ade80; font-size: 0.8rem; }

    /* ============================================================
       MENU SHORTCUT CARDS
    ============================================================ */
    .shortcut-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    .shortcut-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(74,222,128,0.12);
        border-radius: 16px 0 16px 0;
        padding: 1.35rem 1.25rem;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: background 0.18s, transform 0.18s, border-color 0.18s;
        position: relative;
        overflow: hidden;
    }

    .shortcut-card:hover {
        background: rgba(74,222,128,0.07);
        border-color: rgba(74,222,128,0.25);
        transform: translateY(-2px);
    }

    .shortcut-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(74,222,128,0.04) 0%, transparent 60%);
        pointer-events: none;
    }

    .shortcut-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
        background: rgba(74,222,128,0.12);
        color: #4ade80;
    }

    .shortcut-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: #ffffff;
        line-height: 1.3;
    }

    .shortcut-desc {
        font-size: 0.73rem;
        color: rgba(255,255,255,0.4);
        margin-top: 0.15rem;
    }

    /* ============================================================
       SECTION HEADING
    ============================================================ */
    .section-heading {
        font-size: 0.75rem;
        font-weight: 700;
        color: rgba(74,222,128,0.7);
        text-transform: uppercase;
        letter-spacing: 0.09em;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.55rem;
    }

    .section-heading::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(74,222,128,0.12);
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 767.98px) {
        .welcome-card {
            flex-direction: column;
            align-items: flex-start;
            padding: 1.75rem 1.5rem;
        }

        .welcome-body h2 { font-size: 1.2rem; }
        .shortcut-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 399.98px) {
        .shortcut-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

{{-- ================================================================
     WELCOME CARD
================================================================ --}}
<div class="welcome-card">
    <div class="welcome-icon-wrap">
        <i class="bi bi-person-check-fill"></i>
    </div>
    <div class="welcome-body">
        <h2>
            Selamat Datang,
            <span class="name-highlight">{{ Auth::user()->name ?? 'Guru' }}</span>
        </h2>
        <p>
            Anda masuk sebagai guru di sistem informasi kehadiran SMKS Ibnu Sina.<br>
            Gunakan menu di sebelah kiri untuk mengakses fitur yang tersedia.
        </p>

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

{{-- ================================================================
     MENU SHORTCUT
================================================================ --}}
<p class="section-heading">Akses Cepat</p>

<div class="shortcut-grid">

    <a href="{{ route('guru.absen_guru') }}" class="shortcut-card">
        <div class="shortcut-icon">
            <i class="bi bi-clipboard2-check-fill"></i>
        </div>
        <div>
            <div class="shortcut-label">Absensi Saya</div>
            <div class="shortcut-desc">Catat kehadiran Anda hari ini</div>
        </div>
    </a>

    <a href="{{ route('guru.absen_murid') }}" class="shortcut-card">
        <div class="shortcut-icon">
            <i class="bi bi-clipboard2-pulse-fill"></i>
        </div>
        <div>
            <div class="shortcut-label">Absensi Murid</div>
            <div class="shortcut-desc">Kelola kehadiran murid</div>
        </div>
    </a>

    <a href="{{ route('guru.jadwal_mengajar') }}" class="shortcut-card">
        <div class="shortcut-icon">
            <i class="bi bi-calendar2-week-fill"></i>
        </div>
        <div>
            <div class="shortcut-label">Jadwal Mengajar</div>
            <div class="shortcut-desc">Lihat jadwal mengajar Anda</div>
        </div>
    </a>

    <a href="{{ route('guru.data_kelas') }}" class="shortcut-card">
        <div class="shortcut-icon">
            <i class="bi bi-mortarboard-fill"></i>
        </div>
        <div>
            <div class="shortcut-label">Data Kelas</div>
            <div class="shortcut-desc">Informasi kelas yang diajar</div>
        </div>
    </a>

</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    /* Jam berjalan */
    function updateJam() {
        var el = document.getElementById('jamSekarang');
        if (!el) return;
        var now  = new Date();
        var hh   = String(now.getHours()).padStart(2, '0');
        var mm   = String(now.getMinutes()).padStart(2, '0');
        el.textContent = hh + ':' + mm;
    }

    updateJam();
    setInterval(updateJam, 30000);
}());
</script>
@endpush
