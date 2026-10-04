@extends('guru.layouts.app')

@section('title', 'Data Kelas')
@section('page-title', 'Data Kelas')

@push('styles')
<style>
    /* ============================================================
       PAGE HEADER
    ============================================================ */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.85rem;
        margin-bottom: 1.5rem;
    }

    .page-header-left h2 {
        font-size: 1.15rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0 0 0.2rem;
    }

    .page-header-left p {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.45);
        margin: 0;
    }

    /* ============================================================
       SEARCH
    ============================================================ */
    .search-wrap {
        position: relative;
        flex: 1;
        min-width: 200px;
        max-width: 340px;
    }

    .search-wrap .bi-search {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(74,222,128,0.55);
        font-size: 0.88rem;
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(74,222,128,0.18);
        border-radius: 10px;
        color: #e2f5ea;
        font-size: 0.875rem;
        padding: 0.5rem 0.85rem 0.5rem 2.35rem;
        transition: border-color 0.18s, background 0.18s;
        font-family: 'Inter', sans-serif;
        outline: none;
    }

    .search-input::placeholder { color: rgba(255,255,255,0.3); }
    .search-input:focus {
        border-color: rgba(74,222,128,0.45);
        background: rgba(255,255,255,0.08);
    }

    /* ============================================================
       CONTAINER LIST
    ============================================================ */
    .list-container {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(74,222,128,0.12);
        border-radius: 16px 0 16px 0;
        overflow: hidden;
    }

    .list-loading {
        text-align: center;
        padding: 2.5rem 1rem;
        color: rgba(255,255,255,0.35);
        font-size: 0.875rem;
    }

    .spinner-green {
        width: 28px; height: 28px;
        border: 3px solid rgba(74,222,128,0.15);
        border-top-color: #4ade80;
        border-radius: 50%;
        animation: spin 0.65s linear infinite;
        display: inline-block;
        vertical-align: middle;
        margin-right: 0.5rem;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    @media (max-width: 575.98px) {
        .page-header { flex-direction: column; align-items: flex-start; }
        .search-wrap { max-width: 100%; }
    }
</style>
@endpush

@section('content')

{{-- ================================================================
     PAGE HEADER
================================================================ --}}
<div class="page-header">
    <div class="page-header-left">
        <h2>Kelas Saya</h2>
        <p>Daftar kelas yang Anda ikuti sebagai guru</p>
    </div>

    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input
            type="text"
            id="searchInput"
            class="search-input"
            placeholder="Cari nama kelas..."
            autocomplete="off"
        >
    </div>
</div>

{{-- ================================================================
     LIST CONTAINER  (diisi AJAX)
================================================================ --}}
<div class="list-container" id="listContainer">
    <div class="list-loading">
        <span class="spinner-green"></span> Memuat data...
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var searchTimer = null;
    var searchInput = document.getElementById('searchInput');

    function loadList(search) {
        search = search || '';
        var container = document.getElementById('listContainer');
        container.innerHTML = '<div class="list-loading"><span class="spinner-green"></span> Memuat data...</div>';

        fetch('{{ route("guru.data_kelas.list") }}?search=' + encodeURIComponent(search), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.text(); })
        .then(function (html) { container.innerHTML = html; })
        .catch(function () {
            container.innerHTML = '<div class="list-loading" style="color:#f87171;">Gagal memuat data.</div>';
        });
    }

    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);
        var val = this.value.trim();
        searchTimer = setTimeout(function () { loadList(val); }, 350);
    });

    loadList('');

}());
</script>
@endpush
