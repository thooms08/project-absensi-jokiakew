@extends('admin.layouts.app')

@section('title', 'Detail Kelas - ' . $kelas->nama_kelas)
@section('page-title', 'Detail Kelas')

@push('styles')
<style>
    /* ============================================================
       BACK LINK
    ============================================================ */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.82rem;
        color: rgba(255,255,255,0.45);
        text-decoration: none;
        margin-bottom: 1.25rem;
        transition: color 0.18s;
    }

    .back-link:hover { color: #4ade80; }

    /* ============================================================
       KELAS HEADER CARD
    ============================================================ */
    .kelas-header-card {
        background: linear-gradient(135deg, rgba(10,124,74,0.22) 0%, rgba(6,78,46,0.12) 100%);
        border: 1px solid rgba(74,222,128,0.18);
        border-radius: 20px 0 20px 0;
        padding: 1.5rem 1.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.75rem;
        position: relative;
        overflow: hidden;
    }

    .kelas-header-card::before {
        content: '';
        position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(74,222,128,0.05) 0%, transparent 60%);
        pointer-events: none;
    }

    .kelas-header-info { display: flex; align-items: center; gap: 1.1rem; }

    .kelas-icon-wrap {
        width: 56px; height: 56px;
        border-radius: 16px;
        background: rgba(74,222,128,0.12);
        border: 1px solid rgba(74,222,128,0.22);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; color: #4ade80; flex-shrink: 0;
    }

    .kelas-nama-besar {
        font-size: 1.25rem;
        font-weight: 800;
        color: #ffffff;
        margin: 0 0 0.25rem;
    }

    .kelas-wali-info {
        font-size: 0.82rem;
        color: rgba(255,255,255,0.5);
        display: flex; align-items: center; gap: 0.4rem;
    }

    .kelas-wali-info i { color: #4ade80; }

    .kelas-header-stats {
        display: flex;
        gap: 1.25rem;
        flex-wrap: wrap;
    }

    .stat-chip {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(74,222,128,0.12);
        border-radius: 12px;
        padding: 0.65rem 1.1rem;
        min-width: 70px;
    }

    .stat-chip-val {
        font-size: 1.35rem;
        font-weight: 800;
        color: #4ade80;
        line-height: 1;
    }

    .stat-chip-label {
        font-size: 0.68rem;
        color: rgba(255,255,255,0.45);
        margin-top: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
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
        content: ''; flex: 1; height: 1px;
        background: rgba(74,222,128,0.12);
    }

    /* ============================================================
       ANGGOTA PANEL
    ============================================================ */
    .anggota-panel {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(74,222,128,0.12);
        border-radius: 16px 0 16px 0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .anggota-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.9rem 1.25rem;
        border-bottom: 1px solid rgba(74,222,128,0.1);
        background: rgba(255,255,255,0.02);
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .anggota-panel-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #ffffff;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .anggota-count-badge {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.12rem 0.5rem;
        border-radius: 50px;
        background: rgba(74,222,128,0.12);
        color: #4ade80;
        border: 1px solid rgba(74,222,128,0.2);
    }

    .btn-add-anggota {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: linear-gradient(135deg, #0a7c4a, #22c55e);
        border: none; border-radius: 8px;
        color: #fff; font-size: 0.78rem; font-weight: 600;
        padding: 0.42rem 0.9rem; cursor: pointer;
        transition: opacity 0.18s; white-space: nowrap;
        font-family: 'Inter', sans-serif;
    }

    .btn-add-anggota:hover { opacity: 0.88; }

    /* Tabel anggota */
    .anggota-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.845rem;
    }

    .anggota-table thead th {
        font-size: 0.66rem;
        font-weight: 700;
        color: rgba(74,222,128,0.6);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 0.75rem 1.1rem;
        border-bottom: 1px solid rgba(74,222,128,0.08);
        white-space: nowrap;
        background: rgba(255,255,255,0.02);
    }

    .anggota-table tbody td {
        padding: 0.72rem 1.1rem;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        vertical-align: middle;
    }

    .anggota-table tbody tr:last-child td { border-bottom: none; }
    .anggota-table tbody tr:hover td { background: rgba(74,222,128,0.03); }

    .anggota-avatar {
        width: 34px; height: 34px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(74,222,128,0.22);
        flex-shrink: 0;
    }

    .anggota-avatar-init {
        width: 34px; height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0a7c4a, #4ade80);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.78rem; font-weight: 700; color: #064e2e;
        flex-shrink: 0;
    }

    .anggota-name { font-weight: 600; color: #fff; font-size: 0.855rem; }
    .anggota-username { font-size: 0.72rem; color: rgba(74,222,128,0.65); }

    .label-badge {
        display: inline-flex; align-items: center; gap: 0.25rem;
        font-size: 0.68rem; font-weight: 600; padding: 0.15rem 0.55rem;
        border-radius: 50px; white-space: nowrap;
    }

    .label-wali  { background: rgba(251,191,36,0.12); color: #fbbf24; border: 1px solid rgba(251,191,36,0.25); }
    .label-guru  { background: rgba(74,222,128,0.12); color: #4ade80; border: 1px solid rgba(74,222,128,0.22); }
    .label-murid { background: rgba(34,211,238,0.1); color: #22d3ee; border: 1px solid rgba(34,211,238,0.2); }

    .btn-hapus-anggota {
        width: 30px; height: 30px;
        border-radius: 8px;
        background: rgba(248,113,113,0.1);
        border: 1px solid rgba(248,113,113,0.2);
        color: #f87171; font-size: 0.8rem;
        display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background 0.15s;
    }

    .btn-hapus-anggota:hover { background: rgba(248,113,113,0.22); }

    .anggota-empty {
        text-align: center;
        padding: 2rem 1rem;
        color: rgba(255,255,255,0.28);
        font-size: 0.82rem;
    }

    /* ============================================================
       MODAL PILIH
    ============================================================ */
    .modal-dark .modal-content {
        background: #0a2e1c;
        border: 1px solid rgba(74,222,128,0.18);
        border-radius: 16px; color: #e2f5ea;
    }

    .modal-dark .modal-header {
        border-bottom: 1px solid rgba(74,222,128,0.12);
        padding: 1.1rem 1.4rem 1rem;
    }

    .modal-dark .modal-title { font-size: 1rem; font-weight: 700; color: #ffffff; }
    .modal-dark .modal-footer { border-top: 1px solid rgba(74,222,128,0.12); padding: 0.9rem 1.4rem; }
    .modal-dark .btn-close { filter: invert(1) brightness(0.7); }
    .modal-dark .modal-body { padding: 1.25rem 1.4rem; }

    .pick-search {
        width: 100%;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(74,222,128,0.18);
        border-radius: 8px; color: #e2f5ea;
        font-size: 0.85rem; padding: 0.5rem 0.85rem;
        margin-bottom: 0.75rem; font-family: 'Inter', sans-serif;
        outline: none; transition: border-color 0.18s;
    }

    .pick-search:focus { border-color: rgba(74,222,128,0.45); }
    .pick-search::placeholder { color: rgba(255,255,255,0.3); }

    .pick-list {
        max-height: 300px; overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(74,222,128,0.2) transparent;
    }

    .pick-list::-webkit-scrollbar { width: 4px; }
    .pick-list::-webkit-scrollbar-thumb { background: rgba(74,222,128,0.2); border-radius: 2px; }

    .pick-item {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.6rem 0.15rem;
        border-bottom: 1px solid rgba(255,255,255,0.05);
    }

    .pick-item:last-child { border-bottom: none; }
    .pick-item:hover { background: rgba(74,222,128,0.04); border-radius: 8px; }

    .pick-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: linear-gradient(135deg, #0a7c4a, #4ade80);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.78rem; font-weight: 700; color: #064e2e; flex-shrink: 0;
    }

    .pick-name  { font-size: 0.845rem; font-weight: 600; color: #fff; }
    .pick-uname { font-size: 0.72rem; color: rgba(74,222,128,0.65); }

    .btn-pilih {
        margin-left: auto;
        background: rgba(74,222,128,0.12);
        border: 1px solid rgba(74,222,128,0.22);
        border-radius: 8px; color: #4ade80;
        font-size: 0.75rem; font-weight: 600;
        padding: 0.3rem 0.7rem; cursor: pointer;
        transition: background 0.15s; white-space: nowrap;
        font-family: 'Inter', sans-serif;
    }

    .btn-pilih:hover { background: rgba(74,222,128,0.22); }

    .pick-empty {
        text-align: center;
        padding: 1.25rem;
        color: rgba(255,255,255,0.3);
        font-size: 0.82rem;
    }

    .spinner-green {
        width: 20px; height: 20px;
        border: 3px solid rgba(74,222,128,0.15);
        border-top-color: #4ade80;
        border-radius: 50%;
        animation: spin 0.65s linear infinite;
        display: inline-block;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    @media (max-width: 767.98px) {
        .kelas-header-card { flex-direction: column; align-items: flex-start; }
        .anggota-panel-header { gap: 0.5rem; }
        .modal-dialog { margin: 0.5rem; }
    }

    @media (max-width: 575.98px) {
        .dk-col-hide-xs { display: none; }
    }

    /* ============================================================
       TOMBOL MODAL (digunakan di footer modal tambah guru/murid)
    ============================================================ */
    .btn-modal-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: linear-gradient(135deg, #0a7c4a, #22c55e);
        border: none;
        border-radius: 10px;
        color: #fff;
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.5rem 1.2rem;
        cursor: pointer;
        transition: opacity 0.18s;
        font-family: 'Inter', sans-serif;
        white-space: nowrap;
    }

    .btn-modal-primary:hover { opacity: 0.87; }

    .btn-modal-primary:disabled {
        opacity: 0.38;
        cursor: not-allowed;
    }

    .btn-modal-secondary {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.14);
        border-radius: 10px;
        color: rgba(255,255,255,0.7);
        font-size: 0.875rem;
        font-weight: 500;
        padding: 0.5rem 1.1rem;
        cursor: pointer;
        transition: background 0.18s;
        font-family: 'Inter', sans-serif;
        white-space: nowrap;
    }

    .btn-modal-secondary:hover { background: rgba(255,255,255,0.13); }

    /* Tombol "Pilih Semua" */
    .btn-select-all {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.4rem 0.9rem;
        cursor: pointer;
        transition: background 0.15s;
        font-family: 'Inter', sans-serif;
        white-space: nowrap;
        border: 1px solid transparent;
    }

    .btn-select-all-guru {
        background: rgba(74,222,128,0.08);
        border-color: rgba(74,222,128,0.2);
        color: rgba(74,222,128,0.85);
    }

    .btn-select-all-guru:hover { background: rgba(74,222,128,0.15); }

    .btn-select-all-murid {
        background: rgba(34,211,238,0.08);
        border-color: rgba(34,211,238,0.2);
        color: rgba(34,211,238,0.85);
    }

    .btn-select-all-murid:hover { background: rgba(34,211,238,0.15); }

    /* Custom checkbox visual — sembunyikan default, ganti dengan kotak custom */
    .pick-cb-wrap {
        width: 18px;
        height: 18px;
        border-radius: 5px;
        border: 2px solid rgba(74,222,128,0.35);
        background: rgba(255,255,255,0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background 0.15s, border-color 0.15s;
        pointer-events: none;
    }

    .pick-cb-wrap.checked {
        background: #22c55e;
        border-color: #22c55e;
    }

    .pick-cb-wrap.checked::after {
        content: '';
        width: 5px;
        height: 9px;
        border: 2px solid #064e2e;
        border-top: none;
        border-left: none;
        transform: rotate(45deg) translateY(-1px);
        display: block;
    }

    .pick-cb-wrap-murid {
        border-color: rgba(34,211,238,0.35);
    }

    .pick-cb-wrap-murid.checked {
        background: #22d3ee;
        border-color: #22d3ee;
    }

    .pick-cb-wrap-murid.checked::after {
        border-color: #0a2040;
    }
</style>
@endpush

@section('content')

{{-- Tombol kembali --}}
<a href="{{ route('admin.kelas') }}" class="back-link">
    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kelas
</a>

{{-- ============================================================
     HEADER KELAS
============================================================ --}}
@php
    $jumlahGuru  = $kelas->peserta->whereIn('label', ['wali_kelas','guru_kelas'])->count();
    $jumlahMurid = $kelas->peserta->where('label', 'murid')->count();
    $wali        = $kelas->peserta->firstWhere('label', 'wali_kelas');
@endphp

<div class="kelas-header-card">
    <div class="kelas-header-info">
        <div class="kelas-icon-wrap">
            <i class="bi bi-door-open-fill"></i>
        </div>
        <div>
            <h2 class="kelas-nama-besar">{{ $kelas->nama_kelas }}</h2>
            <div class="kelas-wali-info">
                <i class="bi bi-person-fill"></i>
                Wali Kelas:
                {{ $wali && $wali->user ? $wali->user->name : 'Belum ditentukan' }}
            </div>
        </div>
    </div>

    <div class="kelas-header-stats">
        <div class="stat-chip">
            <span class="stat-chip-val">{{ $jumlahGuru }}</span>
            <span class="stat-chip-label">Guru</span>
        </div>
        <div class="stat-chip">
            <span class="stat-chip-val">{{ $jumlahMurid }}</span>
            <span class="stat-chip-label">Murid</span>
        </div>
        <div class="stat-chip">
            <span class="stat-chip-val">{{ $jumlahGuru + $jumlahMurid }}</span>
            <span class="stat-chip-label">Total</span>
        </div>
    </div>
</div>

{{-- ============================================================
     PANEL GURU
============================================================ --}}
<p class="section-heading">Daftar Guru</p>

<div class="anggota-panel" id="panelGuru">
    <div class="anggota-panel-header">
        <div class="anggota-panel-title">
            <i class="bi bi-person-lines-fill" style="color:#4ade80;"></i>
            Guru Kelas
            <span class="anggota-count-badge" id="countGuru">{{ $jumlahGuru }}</span>
        </div>
        <button type="button" class="btn-add-anggota" id="btnTambahGuru">
            <i class="bi bi-plus-lg"></i> Guru
        </button>
    </div>

    <div id="tableGuruWrap">
        @include('admin.partials.detail_kelas_guru', ['peserta' => $kelas->peserta->whereIn('label', ['wali_kelas','guru_kelas'])->values()])
    </div>
</div>

{{-- ============================================================
     PANEL MURID
============================================================ --}}
<p class="section-heading">Daftar Murid</p>

<div class="anggota-panel" id="panelMurid">
    <div class="anggota-panel-header">
        <div class="anggota-panel-title">
            <i class="bi bi-people-fill" style="color:#22d3ee;"></i>
            Murid Kelas
            <span class="anggota-count-badge" id="countMurid"
                  style="background:rgba(34,211,238,0.1);color:#22d3ee;border-color:rgba(34,211,238,0.2);">
                {{ $jumlahMurid }}
            </span>
        </div>
        <button type="button" class="btn-add-anggota" id="btnTambahMurid">
            <i class="bi bi-plus-lg"></i> Murid
        </button>
    </div>

    <div id="tableMuridWrap">
        @include('admin.partials.detail_kelas_murid', ['peserta' => $kelas->peserta->where('label', 'murid')->values()])
    </div>
</div>

{{-- ============================================================
     MODAL PILIH GURU (multi-select)
============================================================ --}}
<div class="modal fade modal-dark" id="modalPickGuru"
     tabindex="-1" aria-labelledby="modalPickGuruLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPickGuruLabel">
                    <i class="bi bi-person-lines-fill me-2" style="color:#4ade80;"></i>
                    Tambah Guru ke Kelas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="pickGuruSearch" class="pick-search"
                       placeholder="Cari nama guru..." autocomplete="off">
                <!-- Info jumlah dipilih -->
                <div id="guruSelectedInfo"
                     style="font-size:0.75rem;color:rgba(74,222,128,0.7);margin-bottom:0.6rem;min-height:1.1rem;"></div>
                <div class="pick-list" id="pickGuruList">
                    <div class="pick-empty"><span class="spinner-green"></span></div>
                </div>
            </div>
            <div class="modal-footer justify-content-between align-items-center"
                 style="border-top:1px solid rgba(74,222,128,0.12); padding:0.85rem 1.25rem;">
                <button type="button" id="btnSelectAllGuru"
                        class="btn-select-all btn-select-all-guru">
                    <i class="bi bi-check2-all"></i>
                    Pilih Semua
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn-modal-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnKonfirmasiGuru" class="btn-modal-primary" disabled>
                        <i class="bi bi-check-lg"></i>
                        <span id="btnKonfirmasiGuruLabel">Tambahkan (0)</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================
     MODAL PILIH MURID (multi-select)
============================================================ --}}
<div class="modal fade modal-dark" id="modalPickMurid"
     tabindex="-1" aria-labelledby="modalPickMuridLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPickMuridLabel">
                    <i class="bi bi-people-fill me-2" style="color:#22d3ee;"></i>
                    Tambah Murid ke Kelas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="pickMuridSearch" class="pick-search"
                       placeholder="Cari nama murid..." autocomplete="off">
                <div id="muridSelectedInfo"
                     style="font-size:0.75rem;color:rgba(34,211,238,0.7);margin-bottom:0.6rem;min-height:1.1rem;"></div>
                <div class="pick-list" id="pickMuridList">
                    <div class="pick-empty"><span class="spinner-green"></span></div>
                </div>
            </div>
            <div class="modal-footer justify-content-between align-items-center"
                 style="border-top:1px solid rgba(74,222,128,0.12); padding:0.85rem 1.25rem;">
                <button type="button" id="btnSelectAllMurid"
                        class="btn-select-all btn-select-all-murid">
                    <i class="bi bi-check2-all"></i>
                    Pilih Semua
                </button>
                <div class="d-flex gap-2">
                    <button type="button" class="btn-modal-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="btnKonfirmasiMurid" class="btn-modal-primary" disabled
                            style="background:linear-gradient(135deg,#0a5878,#22d3ee);">
                        <i class="bi bi-check-lg"></i>
                        <span id="btnKonfirmasiMuridLabel">Tambahkan (0)</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var CSRF     = (document.querySelector('meta[name="csrf-token"]') || {}).content || '{{ csrf_token() }}';
    var KELAS_ID = {{ $kelas->id }};

    /* ============================================================
       UTIL
    ============================================================ */
    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;')
                          .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function swal(icon, title, text, timer) {
        return Swal.fire({ icon: icon, title: title, text: text,
            confirmButtonColor: '#22c55e', background: '#0a2e1c', color: '#fff',
            timer: timer || undefined, showConfirmButton: !timer });
    }

    /* ============================================================
       REFRESH TABEL GURU
    ============================================================ */
    function refreshGuru() {
        fetch('{{ url("admin/kelas") }}/' + KELAS_ID + '/guru-list', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            renderGuruTable(data.data || []);
        });
    }

    function renderGuruTable(list) {
        document.getElementById('countGuru').textContent = list.length;
        var wrap = document.getElementById('tableGuruWrap');

        /* Kita re-fetch semua peserta guru melalui endpoint yang sudah ada,
           tapi karena endpoint guruList hanya mengembalikan yang BELUM masuk,
           kita reload halaman parsial via AJAX ke endpoint detail */
        reloadPanelGuru();
    }

    function reloadPanelGuru() {
        fetch('{{ url("admin/kelas") }}/' + KELAS_ID + '?panel=guru', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.text(); })
        .then(function () {
            /* Reload halaman agar data segar — pendekatan paling andal */
            window.location.reload();
        });
    }

    /* ============================================================
       HAPUS PESERTA
    ============================================================ */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-hapus-peserta]');
        if (!btn) return;

        var pesertaId = btn.dataset.hapusPeserta;
        var nama      = btn.dataset.nama;

        Swal.fire({
            icon: 'warning',
            title: 'Keluarkan dari kelas?',
            html: '<strong>' + escHtml(nama) + '</strong> akan dikeluarkan dari kelas ini.',
            showCancelButton: true,
            confirmButtonText: 'Ya, keluarkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f87171',
            cancelButtonColor: 'rgba(255,255,255,0.1)',
            background: '#0a2e1c', color: '#fff',
        }).then(function (result) {
            if (!result.isConfirmed) return;

            fetch('{{ url("admin/kelas") }}/' + KELAS_ID + '/hapus-peserta', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN':     CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type':     'application/json',
                },
                body: JSON.stringify({ peserta_id: pesertaId })
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    Swal.fire({ icon:'success', title:'Berhasil', text: data.message,
                        timer:1600, showConfirmButton:false,
                        background:'#0a2e1c', color:'#fff'
                    }).then(function () { window.location.reload(); });
                } else {
                    swal('error', 'Gagal', data.message);
                }
            });
        });
    });

    /* ============================================================
       MODAL PICK GURU  (multi-select)
    ============================================================ */
    var modalPickGuruEl  = document.getElementById('modalPickGuru');
    var modalPickGuru    = new bootstrap.Modal(modalPickGuruEl);
    var pickGuruTimer    = null;
    var selectedGuru     = {};  /* { id: name } */

    document.getElementById('btnTambahGuru').addEventListener('click', function () {
        selectedGuru = {};
        document.getElementById('pickGuruSearch').value = '';
        updateGuruUI();
        loadPickGuru('');
        modalPickGuru.show();
    });

    document.getElementById('pickGuruSearch').addEventListener('input', function () {
        clearTimeout(pickGuruTimer);
        var val = this.value.trim();
        pickGuruTimer = setTimeout(function () { loadPickGuru(val); }, 350);
    });

    /* Pilih semua yang tampil */
    document.getElementById('btnSelectAllGuru').addEventListener('click', function () {
        var boxes     = document.querySelectorAll('#pickGuruList .pick-cb-wrap');
        var allChecked = Array.from(boxes).every(function (cb) { return cb.classList.contains('checked'); });
        boxes.forEach(function (cb) {
            var id   = cb.dataset.id;
            var name = cb.closest('.pick-item').querySelector('.pick-name').textContent.trim();
            if (!allChecked) {
                selectedGuru[id] = name;
            } else {
                delete selectedGuru[id];
            }
        });
        updateGuruUI();
    });

    function updateGuruUI() {
        var count = Object.keys(selectedGuru).length;
        var btn   = document.getElementById('btnKonfirmasiGuru');
        var label = document.getElementById('btnKonfirmasiGuruLabel');
        var info  = document.getElementById('guruSelectedInfo');
        btn.disabled = count === 0;
        label.textContent = 'Tambahkan (' + count + ')';
        info.textContent  = count > 0 ? count + ' guru dipilih' : '';
        /* Sync visual checkbox */
        document.querySelectorAll('#pickGuruList .pick-cb-wrap').forEach(function (cb) {
            var id = cb.dataset.id;
            if (selectedGuru[id]) {
                cb.classList.add('checked');
                cb.closest('.pick-item').style.background = 'rgba(74,222,128,0.07)';
            } else {
                cb.classList.remove('checked');
                cb.closest('.pick-item').style.background = '';
            }
        });
    }

    function loadPickGuru(search) {
        var listEl = document.getElementById('pickGuruList');
        listEl.innerHTML = '<div class="pick-empty"><span class="spinner-green"></span></div>';

        fetch('{{ url("admin/kelas") }}/' + KELAS_ID + '/guru-list?search=' + encodeURIComponent(search), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            var list = data.data || [];
            if (!list.length) {
                listEl.innerHTML = '<div class="pick-empty">Tidak ada guru tersedia.</div>';
                return;
            }
            var html = '';
            list.forEach(function (g) {
                var isChecked = !!selectedGuru[g.id];
                var rowBg     = isChecked ? 'rgba(74,222,128,0.07)' : '';
                html += '<div class="pick-item" style="cursor:pointer;border-radius:8px;background:' + rowBg + ';gap:0.75rem;"'
                    +   ' onclick="toggleGuruCheck(\'' + g.id + '\',\'' + escHtml(g.name) + '\')">'
                    + '<div class="pick-cb-wrap ' + (isChecked ? 'checked' : '') + '" data-id="' + g.id + '"></div>'
                    + '<div class="pick-avatar">' + escHtml(g.name.charAt(0).toUpperCase()) + '</div>'
                    + '<div style="flex:1;min-width:0;">'
                    +   '<div class="pick-name text-truncate">' + escHtml(g.name) + '</div>'
                    +   '<div class="pick-uname">@' + escHtml(g.username) + '</div>'
                    + '</div>'
                    + '</div>';
            });
            listEl.innerHTML = html;
        });
    }

    window.toggleGuruCheck = function (id, name) {
        if (selectedGuru[id]) {
            delete selectedGuru[id];
        } else {
            selectedGuru[id] = name;
        }
        updateGuruUI();
    };

    /* Submit tambah guru */
    document.getElementById('btnKonfirmasiGuru').addEventListener('click', function () {
        var ids = Object.keys(selectedGuru);
        if (!ids.length) return;

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-green" style="width:14px;height:14px;border-width:2px;"></span>';

        fetch('{{ url("admin/kelas") }}/' + KELAS_ID + '/tambah-guru', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_ids: ids })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                modalPickGuru.hide();
                Swal.fire({ icon:'success', title:'Berhasil', text: data.message,
                    timer:1800, showConfirmButton:false, background:'#0a2e1c', color:'#fff'
                }).then(function () { window.location.reload(); });
            } else {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-lg me-1"></i><span id="btnKonfirmasiGuruLabel">Tambahkan (' + ids.length + ')</span>';
                swal('warning', 'Gagal', data.message || 'Terjadi kesalahan.');
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i><span>Tambahkan</span>';
            swal('error', 'Koneksi gagal', 'Tidak dapat terhubung ke server.');
        });
    });

    /* ============================================================
       MODAL PICK MURID  (multi-select)
    ============================================================ */
    var modalPickMuridEl = document.getElementById('modalPickMurid');
    var modalPickMurid   = new bootstrap.Modal(modalPickMuridEl);
    var pickMuridTimer   = null;
    var selectedMurid    = {};

    document.getElementById('btnTambahMurid').addEventListener('click', function () {
        selectedMurid = {};
        document.getElementById('pickMuridSearch').value = '';
        updateMuridUI();
        loadPickMurid('');
        modalPickMurid.show();
    });

    document.getElementById('pickMuridSearch').addEventListener('input', function () {
        clearTimeout(pickMuridTimer);
        var val = this.value.trim();
        pickMuridTimer = setTimeout(function () { loadPickMurid(val); }, 350);
    });

    document.getElementById('btnSelectAllMurid').addEventListener('click', function () {
        var boxes      = document.querySelectorAll('#pickMuridList .pick-cb-wrap');
        var allChecked = Array.from(boxes).every(function (cb) { return cb.classList.contains('checked'); });
        boxes.forEach(function (cb) {
            var id   = cb.dataset.id;
            var name = cb.closest('.pick-item').querySelector('.pick-name').textContent.trim();
            if (!allChecked) {
                selectedMurid[id] = name;
            } else {
                delete selectedMurid[id];
            }
        });
        updateMuridUI();
    });

    function updateMuridUI() {
        var count = Object.keys(selectedMurid).length;
        var btn   = document.getElementById('btnKonfirmasiMurid');
        var label = document.getElementById('btnKonfirmasiMuridLabel');
        var info  = document.getElementById('muridSelectedInfo');
        btn.disabled = count === 0;
        label.textContent = 'Tambahkan (' + count + ')';
        info.textContent  = count > 0 ? count + ' murid dipilih' : '';
        document.querySelectorAll('#pickMuridList .pick-cb-wrap').forEach(function (cb) {
            var id = cb.dataset.id;
            if (selectedMurid[id]) {
                cb.classList.add('checked');
                cb.closest('.pick-item').style.background = 'rgba(34,211,238,0.07)';
            } else {
                cb.classList.remove('checked');
                cb.closest('.pick-item').style.background = '';
            }
        });
    }

    function loadPickMurid(search) {
        var listEl = document.getElementById('pickMuridList');
        listEl.innerHTML = '<div class="pick-empty"><span class="spinner-green"></span></div>';

        fetch('{{ url("admin/kelas") }}/' + KELAS_ID + '/murid-list?search=' + encodeURIComponent(search), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            var list = data.data || [];
            if (!list.length) {
                listEl.innerHTML = '<div class="pick-empty">Tidak ada murid tersedia.</div>';
                return;
            }
            var html = '';
            list.forEach(function (m) {
                var isChecked = !!selectedMurid[m.id];
                var rowBg     = isChecked ? 'rgba(34,211,238,0.07)' : '';
                html += '<div class="pick-item" style="cursor:pointer;border-radius:8px;background:' + rowBg + ';gap:0.75rem;"'
                    +   ' onclick="toggleMuridCheck(\'' + m.id + '\',\'' + escHtml(m.name) + '\')">'
                    + '<div class="pick-cb-wrap pick-cb-wrap-murid ' + (isChecked ? 'checked' : '') + '" data-id="' + m.id + '"></div>'
                    + '<div class="pick-avatar" style="background:linear-gradient(135deg,#0e4a7a,#22d3ee);color:#0a2040;">'
                    +   escHtml(m.name.charAt(0).toUpperCase())
                    + '</div>'
                    + '<div style="flex:1;min-width:0;">'
                    +   '<div class="pick-name text-truncate">' + escHtml(m.name) + '</div>'
                    +   '<div class="pick-uname" style="color:rgba(34,211,238,0.65);">@' + escHtml(m.username) + '</div>'
                    + '</div>'
                    + '</div>';
            });
            listEl.innerHTML = html;
        });
    }

    window.toggleMuridCheck = function (id, name) {
        if (selectedMurid[id]) {
            delete selectedMurid[id];
        } else {
            selectedMurid[id] = name;
        }
        updateMuridUI();
    };

    /* Submit tambah murid */
    document.getElementById('btnKonfirmasiMurid').addEventListener('click', function () {
        var ids = Object.keys(selectedMurid);
        if (!ids.length) return;

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-green" style="width:14px;height:14px;border-width:2px;"></span>';

        fetch('{{ url("admin/kelas") }}/' + KELAS_ID + '/tambah-murid', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' },
            body: JSON.stringify({ user_ids: ids })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (data.success) {
                modalPickMurid.hide();
                Swal.fire({ icon:'success', title:'Berhasil', text: data.message,
                    timer:1800, showConfirmButton:false, background:'#0a2e1c', color:'#fff'
                }).then(function () { window.location.reload(); });
            } else {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-lg me-1"></i><span id="btnKonfirmasiMuridLabel">Tambahkan (' + ids.length + ')</span>';
                swal('warning', 'Gagal', data.message || 'Terjadi kesalahan.');
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i><span>Tambahkan</span>';
            swal('error', 'Koneksi gagal', 'Tidak dapat terhubung ke server.');
        });
    });

}());
</script>
@endpush
