@extends('guru.layouts.app')

@section('title', 'Jadwal Mengajar')
@section('page-title', 'Jadwal Mengajar')

@push('styles')
<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.85rem;
        margin-bottom: 1.25rem;
    }

    .page-header-left h2 { font-size: 1.15rem; font-weight: 700; color: #ffffff; margin: 0 0 0.2rem; }
    .page-header-left p  { font-size: 0.8rem; color: rgba(255,255,255,0.45); margin: 0; }

    .filter-bar {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(74,222,128,0.12);
        border-radius: 14px;
        padding: 0.75rem 1rem;
        margin-bottom: 1.25rem;
    }

    .filter-label {
        font-size: 0.72rem; font-weight: 700;
        color: rgba(74,222,128,0.6);
        text-transform: uppercase; letter-spacing: 0.07em;
        white-space: nowrap; margin-right: 0.25rem;
    }

    .btn-hari {
        display: inline-flex; align-items: center;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px; color: rgba(255,255,255,0.55);
        font-size: 0.82rem; font-weight: 600;
        padding: 0.4rem 0.85rem; cursor: pointer;
        transition: all 0.15s; white-space: nowrap;
        font-family: 'Inter', sans-serif;
    }

    .btn-hari:hover { background: rgba(74,222,128,0.1); border-color: rgba(74,222,128,0.25); color: #4ade80; }
    .btn-hari.active { background: rgba(74,222,128,0.15); border-color: rgba(74,222,128,0.4); color: #4ade80; }
    .btn-hari.today-active { background: rgba(34,197,94,0.18); border-color: #22c55e; color: #4ade80; box-shadow: 0 0 0 1px rgba(34,197,94,0.25); }

    .btn-refresh {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 8px;
        background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.45); font-size: 0.9rem; cursor: pointer;
        transition: all 0.15s; flex-shrink: 0; margin-left: auto;
    }

    .btn-refresh:hover { background: rgba(74,222,128,0.1); color: #4ade80; border-color: rgba(74,222,128,0.25); }

    .action-bar { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1.25rem; }

    .search-wrap { position: relative; flex: 1; min-width: 200px; max-width: 340px; }
    .search-wrap .bi-search { position: absolute; left: 0.85rem; top: 50%; transform: translateY(-50%); color: rgba(74,222,128,0.5); font-size: 0.85rem; pointer-events: none; }
    .search-input { width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(74,222,128,0.18); border-radius: 10px; color: #e2f5ea; font-size: 0.875rem; padding: 0.5rem 0.85rem 0.5rem 2.3rem; font-family: 'Inter', sans-serif; outline: none; transition: border-color 0.18s; }
    .search-input::placeholder { color: rgba(255,255,255,0.28); }
    .search-input:focus { border-color: rgba(74,222,128,0.45); background: rgba(255,255,255,0.07); }

    .list-container { background: rgba(255,255,255,0.04); border: 1px solid rgba(74,222,128,0.12); border-radius: 16px 0 16px 0; overflow: hidden; }

    .list-loading { text-align: center; padding: 2.5rem 1rem; color: rgba(255,255,255,0.35); font-size: 0.875rem; }

    .spinner-green { width: 26px; height: 26px; border: 3px solid rgba(74,222,128,0.15); border-top-color: #4ade80; border-radius: 50%; animation: spin 0.65s linear infinite; display: inline-block; vertical-align: middle; margin-right: 0.5rem; }

    @keyframes spin { to { transform: rotate(360deg); } }

    @media (max-width: 575.98px) {
        .page-header { flex-direction: column; align-items: flex-start; }
        .filter-bar  { gap: 0.4rem; }
        .btn-hari    { font-size: 0.75rem; padding: 0.35rem 0.6rem; }
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h2>Jadwal Mengajar Saya</h2>
        <p>Lihat jadwal mengajar Anda per hari</p>
    </div>
</div>

{{-- Filter hari — tanpa date picker --}}
<div class="filter-bar">
    <span class="filter-label"><i class="bi bi-funnel-fill me-1"></i>Hari</span>

    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
        <button type="button" class="btn-hari" data-hari="{{ $h }}" title="{{ $h }}">{{ $h }}</button>
    @endforeach

    <button type="button" class="btn-refresh" id="btnRefresh" title="Muat ulang">
        <i class="bi bi-arrow-clockwise"></i>
    </button>
</div>

<div class="action-bar">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" id="searchInput" class="search-input"
               placeholder="Cari kelas atau mata pelajaran..." autocomplete="off">
    </div>
</div>

<div class="list-container" id="listContainer">
    <div class="list-loading"><span class="spinner-green"></span> Memuat data...</div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var activeHari  = '';
    var searchTimer = null;

    var DOW_TO_HARI = {0:'Senin',1:'Senin',2:'Selasa',3:'Rabu',4:'Kamis',5:'Jumat',6:'Sabtu'};

    function hariHariIni() { return DOW_TO_HARI[new Date().getDay()] || 'Senin'; }

    function loadList(hari, search) {
        hari       = hari   || activeHari;
        search     = search || '';
        activeHari = hari;

        syncHariButtons(hari);

        var container = document.getElementById('listContainer');
        container.innerHTML = '<div class="list-loading"><span class="spinner-green"></span> Memuat data...</div>';

        var url = '{{ route("guru.jadwal_mengajar.list") }}'
                + '?hari='   + encodeURIComponent(hari)
                + '&search=' + encodeURIComponent(search);

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function (r) { return r.text(); })
        .then(function (html) { container.innerHTML = html; })
        .catch(function () {
            container.innerHTML = '<div class="list-loading" style="color:#f87171;">Gagal memuat data.</div>';
        });
    }

    function syncHariButtons(hari) {
        var hariIni = hariHariIni();
        document.querySelectorAll('.btn-hari').forEach(function (btn) {
            btn.classList.remove('active', 'today-active');
            if (btn.dataset.hari === hari) {
                btn.classList.add(hari === hariIni ? 'today-active' : 'active');
            }
        });
    }

    document.querySelectorAll('.btn-hari').forEach(function (btn) {
        btn.addEventListener('click', function () {
            loadList(this.dataset.hari, document.getElementById('searchInput').value.trim());
        });
    });

    document.getElementById('btnRefresh').addEventListener('click', function () {
        var icon = this.querySelector('i');
        icon.style.transition = 'transform 0.5s';
        icon.style.transform  = 'rotate(360deg)';
        setTimeout(function () { icon.style.transform = ''; }, 500);
        loadList(activeHari, document.getElementById('searchInput').value.trim());
    });

    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchTimer);
        var val = this.value.trim();
        searchTimer = setTimeout(function () { loadList(activeHari, val); }, 350);
    });

    loadList(hariHariIni(), '');

}());
</script>
@endpush
