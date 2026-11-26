@extends('guru.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Beranda')

@push('styles')
<style>
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

    .info-strip { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }

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
    .dash-section-label {
        font-size: .72rem; font-weight: 700;
        color: rgba(74,222,128,.65);
        text-transform: uppercase; letter-spacing: .08em;
        margin: 0 0 .75rem;
        display: flex; align-items: center; gap: .4rem;
    }

    /* ============================================================
       SUMMARY GRID
    ============================================================ */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: .85rem;
        margin-bottom: 1.75rem;
    }

    .sum-card {
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(74,222,128,.13);
        border-radius: 14px 0 14px 0;
        padding: 1.1rem 1rem;
        display: flex; flex-direction: column; align-items: center; gap: .35rem;
        transition: border-color .2s;
    }

    .sum-card:hover { border-color: rgba(74,222,128,.3); }

    .sum-icon {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.05rem;
    }

    .sum-icon-hadir { background: rgba(34,197,94,.12);  color: #4ade80; }
    .sum-icon-izin  { background: rgba(251,191,36,.1);  color: #fbbf24; }
    .sum-icon-tidak { background: rgba(248,113,113,.1); color: #f87171; }
    .sum-icon-total { background: rgba(45,212,191,.12); color: #2dd4bf; }

    .sum-val   { font-size: 1.7rem; font-weight: 800; color: #fff; line-height: 1; }
    .sum-label { font-size: .68rem; font-weight: 600; color: rgba(226,245,234,.4); text-transform: uppercase; letter-spacing: .06em; }

    /* ============================================================
       JADWAL HARI INI
    ============================================================ */
    .jadwal-list {
        display: flex; flex-direction: column; gap: .7rem;
        margin-bottom: 1.75rem;
    }

    .jadwal-row {
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(74,222,128,.12);
        border-radius: 12px 0 12px 0;
        padding: .85rem 1.1rem;
        display: flex; align-items: center; gap: 1rem;
        flex-wrap: wrap;
        position: relative; overflow: hidden;
        transition: border-color .2s;
    }

    .jadwal-row::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0;
        width: 3px; border-radius: 3px 0 0 3px;
    }

    .jadwal-row-aktif   { border-color: rgba(74,222,128,.3); }
    .jadwal-row-aktif::before   { background: #4ade80; }
    .jadwal-row-hadir::before   { background: #4ade80; }
    .jadwal-row-izin::before    { background: #fbbf24; }
    .jadwal-row-tidak::before   { background: #f87171; }
    .jadwal-row-menunggu::before{ background: rgba(255,255,255,.12); }
    .jadwal-row-lewat::before   { background: rgba(255,255,255,.12); }

    .jadwal-row:hover { border-color: rgba(74,222,128,.28); }

    .jadwal-jam {
        font-size: .72rem; font-weight: 700;
        color: rgba(74,222,128,.8);
        background: rgba(74,222,128,.08);
        border: 1px solid rgba(74,222,128,.15);
        border-radius: 6px; padding: .2rem .55rem;
        white-space: nowrap; flex-shrink: 0;
    }

    .jadwal-info { flex: 1; min-width: 0; }

    .jadwal-kelas {
        font-size: .9rem; font-weight: 700; color: #fff;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .jadwal-mapel {
        font-size: .75rem; color: rgba(74,222,128,.7);
        display: flex; align-items: center; gap: .25rem; margin-top: .15rem;
    }

    .jadwal-status {
        display: inline-flex; align-items: center; gap: .3rem;
        border-radius: 50px; padding: .2rem .65rem;
        font-size: .7rem; font-weight: 700; white-space: nowrap; flex-shrink: 0;
    }

    .js-hadir   { background: rgba(34,197,94,.12);  color: #4ade80; border: 1px solid rgba(34,197,94,.25); }
    .js-izin    { background: rgba(251,191,36,.1);  color: #fbbf24; border: 1px solid rgba(251,191,36,.22); }
    .js-tidak   { background: rgba(248,113,113,.1); color: #f87171; border: 1px solid rgba(248,113,113,.22); }
    .js-aktif   { background: rgba(74,222,128,.1);  color: #4ade80; border: 1px solid rgba(74,222,128,.22); animation: pulseGreen 2s ease-in-out infinite; }
    .js-tunggu  { background: rgba(255,255,255,.05); color: rgba(226,245,234,.35); border: 1px solid rgba(255,255,255,.08); }

    @keyframes pulseGreen {
        0%,100% { box-shadow: 0 0 0 0 rgba(74,222,128,0); }
        50%      { box-shadow: 0 0 0 4px rgba(74,222,128,.12); }
    }

    .jadwal-empty {
        text-align: center; padding: 2.5rem 1rem;
        background: rgba(255,255,255,.02);
        border: 1px dashed rgba(74,222,128,.12);
        border-radius: 12px;
        color: rgba(226,245,234,.3);
        font-size: .85rem;
    }

    .jadwal-empty i { font-size: 2rem; display: block; margin-bottom: .6rem; opacity: .4; }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 767.98px) {
        .welcome-card  { padding: 1.5rem 1.25rem; }
        .welcome-title { font-size: 1.05rem; }
        .summary-grid  { grid-template-columns: repeat(2, 1fr); gap: .65rem; }
        .sum-val       { font-size: 1.45rem; }
    }

    @media (max-width: 399.98px) {
        .summary-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endpush

@section('content')

{{-- Welcome --}}
<div class="welcome-card">
    @php
        $guruImg    = Auth::user()->image ?? null;
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
        <p class="welcome-sub">Selamat datang di panel guru.</p>
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

{{-- Summary Absensi Bulan Ini --}}
<p class="dash-section-label">
    <i class="bi bi-bar-chart-fill"></i>
    Absensi Saya — {{ now()->translatedFormat('F Y') }}
</p>

<div class="summary-grid">
    <div class="sum-card">
        <div class="sum-icon sum-icon-hadir"><i class="bi bi-check-circle-fill"></i></div>
        <div class="sum-val">{{ $summary['hadir'] }}</div>
        <div class="sum-label">Hadir</div>
    </div>
    <div class="sum-card">
        <div class="sum-icon sum-icon-izin"><i class="bi bi-envelope-fill"></i></div>
        <div class="sum-val">{{ $summary['izin'] }}</div>
        <div class="sum-label">Izin</div>
    </div>
    <div class="sum-card">
        <div class="sum-icon sum-icon-tidak"><i class="bi bi-x-circle-fill"></i></div>
        <div class="sum-val">{{ $summary['tidak'] }}</div>
        <div class="sum-label">Tidak Hadir</div>
    </div>
    <div class="sum-card">
        <div class="sum-icon sum-icon-total"><i class="bi bi-layers-fill"></i></div>
        <div class="sum-val">{{ $summary['total'] }}</div>
        <div class="sum-label">Total</div>
    </div>
</div>

{{-- Jadwal Mengajar Hari Ini --}}
<p class="dash-section-label">
    <i class="bi bi-calendar2-week-fill"></i>
    Jadwal Hari Ini
    @if($hariIni)
        <span style="color:rgba(226,245,234,.35);font-weight:500;text-transform:none;letter-spacing:0;">- {{ $hariIni }}</span>
    @endif
</p>

@if($jadwalHariIni->isEmpty())
    <div class="jadwal-empty">
        <i class="bi bi-calendar2-x"></i>
        Tidak ada jadwal mengajar hari ini.
    </div>
@else
    <div class="jadwal-list">
        @foreach($jadwalHariIni as $item)
        @php
            $absen  = $item['absen'];
            $aktif  = $item['aktif'];
            $lewat  = $item['sudah_lewat'];

            $rowCls = 'jadwal-row ';
            if ($absen) {
                $rowCls .= 'jadwal-row-' . $absen->status;
            } elseif ($aktif) {
                $rowCls .= 'jadwal-row-aktif';
            } elseif ($lewat) {
                $rowCls .= 'jadwal-row-lewat';
            } else {
                $rowCls .= 'jadwal-row-menunggu';
            }
        @endphp
        <div class="{{ $rowCls }}">
            <span class="jadwal-jam">{{ $item['jam_mulai'] }} – {{ $item['jam_akhir'] }}</span>

            <div class="jadwal-info">
                <div class="jadwal-kelas">{{ $item['jadwal']->kelas?->nama_kelas ?? '-' }}</div>
                <div class="jadwal-mapel">
                    <i class="bi bi-book-fill" style="font-size:.6rem;"></i>
                    {{ $item['jadwal']->mapel?->nama_mapel ?? '-' }}
                </div>
            </div>

            @if($absen)
                @if($absen->status === 'hadir')
                    <span class="jadwal-status js-hadir">
                        <i class="bi bi-check-circle-fill" style="font-size:.55rem;"></i>Hadir
                    </span>
                @elseif($absen->status === 'izin')
                    <span class="jadwal-status js-izin">
                        <i class="bi bi-envelope-fill" style="font-size:.55rem;"></i>Izin
                    </span>
                @else
                    <span class="jadwal-status js-tidak">
                        <i class="bi bi-x-circle-fill" style="font-size:.55rem;"></i>Tidak Hadir
                    </span>
                @endif
            @elseif($aktif)
                <span class="jadwal-status js-aktif">
                    <i class="bi bi-circle-fill" style="font-size:.45rem;"></i>Sedang Berlangsung
                </span>
            @elseif($lewat)
                <span class="jadwal-status js-tidak">
                    <i class="bi bi-x-circle-fill" style="font-size:.55rem;"></i>Tidak Hadir
                </span>
            @else
                <span class="jadwal-status js-tunggu">
                    <i class="bi bi-hourglass-split" style="font-size:.55rem;"></i>{{ $item['jam_mulai'] }}
                </span>
            @endif
        </div>
        @endforeach
    </div>
@endif

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
