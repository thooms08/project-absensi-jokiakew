@extends('murid.layouts.app')

@section('title', 'Absensi')
@section('page-title', 'Absensi')

@push('styles')
<style>
    .info-bar {
        display: flex; align-items: center; gap: .75rem; flex-wrap: wrap;
        background: rgba(255,255,255,.03); border: 1px solid rgba(74, 222, 128, .12);
        border-radius: 12px; padding: .7rem 1rem; margin-bottom: 1.5rem; font-size: .82rem;
    }

    .info-hari { font-weight: 700; color: #4ade80; }
    .info-tgl  { color: rgba(226, 245, 234, .5); }
    .info-dot  { color: rgba(226, 245, 234, .18); }

    .btn-refresh-kartu {
        margin-left: auto;
        display: inline-flex; align-items: center; gap: .35rem;
        background: rgba(74, 222, 128, .08); border: 1px solid rgba(74, 222, 128, .18);
        border-radius: 8px; color: rgba(74, 222, 128, .75); font-size: .78rem; font-weight: 600;
        padding: .35rem .75rem; cursor: pointer; font-family: 'Inter',sans-serif;
        transition: background .15s;
    }

    .btn-refresh-kartu:hover { background: rgba(74, 222, 128, .15); color: #4ade80; }

    #kartuContainer {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px,1fr));
        gap: 1.1rem;
    }

    .kartu-loading {
        grid-column: 1/-1; text-align: center;
        padding: 3rem 1rem; color: rgba(226, 245, 234, .35); font-size: .875rem;
    }

    .spinner-green {
        width: 24px; height: 24px;
        border: 3px solid rgba(74, 222, 128, .15);
        border-top-color: #4ade80;
        border-radius: 50%; animation: spin .65s linear infinite;
        display: inline-block; vertical-align: middle; margin-right: .5rem;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    .absen-info-note {
        display: flex; align-items: center; gap: .6rem;
        background: rgba(56, 189, 248, .07); border: 1px solid rgba(56, 189, 248, .18);
        border-radius: 10px; padding: .65rem 1rem; margin-bottom: 1.25rem;
        font-size: .8rem; color: rgba(56, 189, 248, .85);
    }

    .absen-info-note i { font-size: 1rem; flex-shrink: 0; }

    @media (max-width: 575.98px) { #kartuContainer { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')

@php
    $hariMap = [1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu',0=>'Minggu'];
    $hariIni = $hariMap[date('w')] ?? '-';
    $tglIni  = \Carbon\Carbon::today()->translatedFormat('d F Y');
@endphp

<div class="info-bar">
    <i class="bi bi-calendar2-week-fill" style="color:#4ade80;font-size:1rem;"></i>
    <span class="info-hari">{{ $hariIni }}</span>
    <span class="info-dot">•</span>
    <span class="info-tgl">{{ $tglIni }}</span>
    <button type="button" class="btn-refresh-kartu" id="btnRefreshKartu">
        <i class="bi bi-arrow-clockwise"></i> Perbarui
    </button>
</div>

<div class="absen-info-note">
    <i class="bi bi-info-circle-fill"></i>
    Absensi dicatat oleh guru melalui scan QR. Halaman ini menampilkan status kehadiran Anda hari ini.
</div>

<div id="kartuContainer">
    <div class="kartu-loading"><span class="spinner-green"></span> Memuat jadwal hari ini...</div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var refreshTimer = null;

    function loadKartu() {
        document.getElementById('kartuContainer').innerHTML =
            '<div class="kartu-loading"><span class="spinner-green"></span> Memuat jadwal hari ini...</div>';

        fetch('{{ route("murid.absen.kartu") }}', { headers: {'X-Requested-With':'XMLHttpRequest'} })
        .then(function(r){ return r.text(); })
        .then(function(html){ document.getElementById('kartuContainer').innerHTML = html; })
        .catch(function(){ document.getElementById('kartuContainer').innerHTML = '<div class="kartu-loading" style="color:#f87171;">Gagal memuat jadwal.</div>'; });
    }

    document.getElementById('btnRefreshKartu').addEventListener('click', function(){
        var i = this.querySelector('i');
        i.style.transition = 'transform .5s'; i.style.transform = 'rotate(360deg)';
        setTimeout(function(){ i.style.transform = ''; }, 500);
        loadKartu();
    });

    refreshTimer = setInterval(loadKartu, 60000);

    loadKartu();
}());
</script>
@endpush
