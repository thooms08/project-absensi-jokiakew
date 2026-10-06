@extends('admin.layouts.app')

@section('title', 'Jadwal Mengajar')
@section('page-title', 'Jadwal Mengajar')

@push('styles')
<style>
    .page-header { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.85rem; margin-bottom:1.25rem; }
    .page-header-left h2 { font-size:1.15rem; font-weight:700; color:#fff; margin:0 0 0.2rem; }
    .page-header-left p  { font-size:0.8rem; color:rgba(255,255,255,0.45); margin:0; }

    /* FILTER BAR */
    .filter-bar { display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; background:rgba(255,255,255,0.03); border:1px solid rgba(74,222,128,0.12); border-radius:14px; padding:0.75rem 1rem; margin-bottom:1.25rem; }
    .filter-label { font-size:0.72rem; font-weight:700; color:rgba(74,222,128,0.6); text-transform:uppercase; letter-spacing:0.07em; white-space:nowrap; margin-right:0.25rem; }
    .btn-hari { display:inline-flex; align-items:center; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:8px; color:rgba(255,255,255,0.55); font-size:0.82rem; font-weight:600; padding:0.4rem 0.85rem; cursor:pointer; transition:all 0.15s; white-space:nowrap; font-family:'Inter',sans-serif; }
    .btn-hari:hover { background:rgba(74,222,128,0.1); border-color:rgba(74,222,128,0.25); color:#4ade80; }
    .btn-hari.active { background:rgba(74,222,128,0.15); border-color:rgba(74,222,128,0.4); color:#4ade80; }
    .btn-hari.today-active { background:rgba(34,197,94,0.18); border-color:#22c55e; color:#4ade80; box-shadow:0 0 0 1px rgba(34,197,94,0.25); }
    .btn-refresh { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); color:rgba(255,255,255,0.45); font-size:0.9rem; cursor:pointer; transition:all 0.15s; flex-shrink:0; margin-left:auto; }
    .btn-refresh:hover { background:rgba(74,222,128,0.1); color:#4ade80; border-color:rgba(74,222,128,0.25); }

    /* ACTION BAR */
    .action-bar { display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; margin-bottom:1.25rem; }
    .search-wrap { position:relative; flex:1; min-width:200px; max-width:320px; }
    .search-wrap .bi-search { position:absolute; left:0.85rem; top:50%; transform:translateY(-50%); color:rgba(74,222,128,0.5); font-size:0.85rem; pointer-events:none; }
    .search-input { width:100%; background:rgba(255,255,255,0.05); border:1px solid rgba(74,222,128,0.18); border-radius:10px; color:#e2f5ea; font-size:0.875rem; padding:0.5rem 0.85rem 0.5rem 2.3rem; transition:border-color 0.18s; font-family:'Inter',sans-serif; outline:none; }
    .search-input::placeholder { color:rgba(255,255,255,0.28); }
    .search-input:focus { border-color:rgba(74,222,128,0.45); background:rgba(255,255,255,0.07); }
    .btn-add-jadwal { display:inline-flex; align-items:center; gap:0.5rem; background:linear-gradient(135deg,#0a7c4a,#22c55e); border:none; border-radius:10px; color:#fff; font-size:0.875rem; font-weight:600; padding:0.55rem 1.15rem; cursor:pointer; transition:opacity 0.18s,transform 0.18s; white-space:nowrap; font-family:'Inter',sans-serif; }
    .btn-add-jadwal:hover { opacity:0.88; transform:translateY(-1px); }

    /* LIST CONTAINER */
    .list-container { background:rgba(255,255,255,0.04); border:1px solid rgba(74,222,128,0.12); border-radius:16px 0 16px 0; overflow:hidden; }
    .list-loading { text-align:center; padding:2.5rem 1rem; color:rgba(255,255,255,0.35); font-size:0.875rem; }
    .spinner-green { width:26px; height:26px; border:3px solid rgba(74,222,128,0.15); border-top-color:#4ade80; border-radius:50%; animation:spin 0.65s linear infinite; display:inline-block; vertical-align:middle; margin-right:0.5rem; }
    @keyframes spin { to { transform:rotate(360deg); } }

    /* MODAL */
    .modal-dark .modal-content { background:#0a2e1c; border:1px solid rgba(74,222,128,0.18); border-radius:16px; color:#e2f5ea; }
    .modal-dark .modal-header  { border-bottom:1px solid rgba(74,222,128,0.12); padding:1.1rem 1.4rem 1rem; }
    .modal-dark .modal-title   { font-size:1rem; font-weight:700; color:#fff; }
    .modal-dark .modal-footer  { border-top:1px solid rgba(74,222,128,0.12); padding:0.9rem 1.4rem; }
    .modal-dark .btn-close     { filter:invert(1) brightness(0.7); }
    .modal-dark .modal-body    { padding:1.25rem 1.4rem; }

    /* FORM */
    .form-label-dark { font-size:0.8rem; font-weight:600; color:rgba(255,255,255,0.65); margin-bottom:0.4rem; display:block; }
    .form-control-dark, .form-select-dark { background:rgba(255,255,255,0.06); border:1px solid rgba(74,222,128,0.18); border-radius:10px; color:#e2f5ea; font-size:0.875rem; padding:0.55rem 0.85rem; width:100%; transition:border-color 0.18s; font-family:'Inter',sans-serif; outline:none; }
    .form-select-dark { appearance:none; -webkit-appearance:none; background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='rgba(74,222,128,0.5)' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E"); background-repeat:no-repeat; background-position:right 0.85rem center; padding-right:2.2rem; cursor:pointer; }
    .form-select-dark option { background:#0a2e1c; color:#e2f5ea; }
    .form-control-dark::placeholder { color:rgba(255,255,255,0.28); }
    .form-control-dark:focus, .form-select-dark:focus { border-color:rgba(74,222,128,0.5); background:rgba(255,255,255,0.09); box-shadow:0 0 0 3px rgba(74,222,128,0.07); }
    .form-control-dark.is-invalid, .form-select-dark.is-invalid { border-color:rgba(248,113,113,0.6); }
    .field-feedback { font-size:0.75rem; margin-top:0.28rem; min-height:1rem; color:#f87171; }
    .time-row { display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; }

    /* TOMBOL MODAL */
    .btn-modal-primary { background:linear-gradient(135deg,#0a7c4a,#22c55e); border:none; border-radius:10px; color:#fff; font-size:0.875rem; font-weight:600; padding:0.55rem 1.35rem; cursor:pointer; transition:opacity 0.18s; font-family:'Inter',sans-serif; }
    .btn-modal-primary:hover { opacity:0.87; }
    .btn-modal-primary:disabled { opacity:0.5; cursor:not-allowed; }
    .btn-modal-secondary { background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.12); border-radius:10px; color:rgba(255,255,255,0.72); font-size:0.875rem; font-weight:500; padding:0.55rem 1.2rem; cursor:pointer; transition:background 0.18s; font-family:'Inter',sans-serif; }
    .btn-modal-secondary:hover { background:rgba(255,255,255,0.12); }

    /* SESI ROWS di modal tambah */
    .sesi-list { display:flex; flex-direction:column; gap:0.75rem; margin-top:0.5rem; }
    .sesi-row { background:rgba(255,255,255,0.03); border:1px solid rgba(74,222,128,0.1); border-radius:10px; padding:0.85rem 0.9rem 0.75rem; position:relative; }
    .sesi-row-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:0.65rem; font-size:0.78rem; font-weight:700; color:rgba(74,222,128,0.7); }
    .btn-rm-sesi { background:rgba(248,113,113,0.1); border:1px solid rgba(248,113,113,0.2); border-radius:6px; color:#f87171; font-size:0.78rem; padding:0.18rem 0.55rem; cursor:pointer; font-family:'Inter',sans-serif; transition:background 0.15s; }
    .btn-rm-sesi:hover { background:rgba(248,113,113,0.2); }
    .btn-add-sesi { display:inline-flex; align-items:center; gap:0.4rem; background:rgba(74,222,128,0.08); border:1px solid rgba(74,222,128,0.2); border-radius:8px; color:#4ade80; font-size:0.8rem; font-weight:600; padding:0.4rem 0.85rem; cursor:pointer; font-family:'Inter',sans-serif; transition:background 0.15s; margin-top:0.35rem; }
    .btn-add-sesi:hover { background:rgba(74,222,128,0.15); }

    /* SESI ROWS di modal kelola (edit per sesi) */
    .kelola-sesi-row { background:rgba(255,255,255,0.03); border:1px solid rgba(74,222,128,0.1); border-radius:10px; padding:0.85rem 0.9rem 0.75rem; margin-bottom:0.75rem; }
    .kelola-sesi-row:last-child { margin-bottom:0; }
    .sesi-saved-indicator { font-size:0.72rem; color:rgba(74,222,128,0.65); display:none; }
    .btn-sesi-save { display:inline-flex; align-items:center; gap:0.35rem; background:rgba(74,222,128,0.1); border:1px solid rgba(74,222,128,0.22); border-radius:7px; color:#4ade80; font-size:0.78rem; font-weight:600; padding:0.35rem 0.75rem; cursor:pointer; font-family:'Inter',sans-serif; transition:background 0.15s; }
    .btn-sesi-save:hover { background:rgba(74,222,128,0.2); }
    .btn-sesi-save:disabled { opacity:0.4; cursor:not-allowed; }
    .btn-sesi-del { display:inline-flex; align-items:center; gap:0.35rem; background:rgba(248,113,113,0.08); border:1px solid rgba(248,113,113,0.2); border-radius:7px; color:#f87171; font-size:0.78rem; font-weight:600; padding:0.35rem 0.75rem; cursor:pointer; font-family:'Inter',sans-serif; transition:background 0.15s; }
    .btn-sesi-del:hover { background:rgba(248,113,113,0.18); }

    @media (max-width:575.98px) {
        .page-header { flex-direction:column; align-items:flex-start; }
        .filter-bar  { gap:0.4rem; }
        .btn-hari    { font-size:0.75rem; padding:0.35rem 0.6rem; }
        .modal-dialog { margin:0.5rem; }
        .time-row { grid-template-columns:1fr; }
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h2>Jadwal Mengajar</h2>
        <p>Atur jadwal mengajar guru per hari</p>
    </div>
</div>

{{-- FILTER BAR --}}
<div class="filter-bar">
    <span class="filter-label"><i class="bi bi-funnel-fill me-1"></i>Hari</span>
    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)
        <button type="button" class="btn-hari" data-hari="{{ $h }}" title="{{ $h }}">{{ $h }}</button>
    @endforeach
    <button type="button" class="btn-refresh" id="btnRefresh" title="Muat ulang">
        <i class="bi bi-arrow-clockwise"></i>
    </button>
</div>

{{-- ACTION BAR --}}
<div class="action-bar">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" id="searchInput" class="search-input"
               placeholder="Cari nama guru, kelas, mapel..." autocomplete="off">
    </div>
    <button class="btn-add-jadwal" id="btnTambahJadwal">
        <i class="bi bi-plus-lg"></i> Jadwal
    </button>
</div>

{{-- LIST CONTAINER --}}
<div class="list-container" id="listContainer">
    <div class="list-loading"><span class="spinner-green"></span> Memuat data...</div>
</div>

{{-- ================================================================
     MODAL TAMBAH JADWAL (multi-sesi)
================================================================ --}}
<div class="modal fade modal-dark" id="modalTambahJadwal"
     tabindex="-1" aria-labelledby="modalTambahJadwalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahJadwalLabel">
                    <i class="bi bi-calendar2-plus-fill me-2" style="color:#4ade80;"></i>
                    Tambah Jadwal Mengajar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahJadwal" novalidate>
                    @csrf

                    {{-- Guru --}}
                    <div class="mb-3">
                        <label class="form-label-dark" for="selectGuru">
                            Guru <span style="color:#f87171;">*</span>
                        </label>
                        <select id="selectGuru" name="user_id" class="form-select-dark">
                            <option value="">— Pilih Guru —</option>
                        </select>
                        <div class="field-feedback" id="fbGuru"></div>
                    </div>

                    {{-- Hari --}}
                    <div class="mb-3">
                        <label class="form-label-dark" for="selectHari">
                            Hari <span style="color:#f87171;">*</span>
                        </label>
                        <select id="selectHari" name="hari" class="form-select-dark">
                            <option value="">— Pilih Hari —</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                        </select>
                        <div class="field-feedback" id="fbHari"></div>
                    </div>

                    {{-- Sesi-sesi --}}
                    <div style="font-size:0.8rem;font-weight:700;color:rgba(74,222,128,0.7);margin-bottom:0.5rem;">
                        <i class="bi bi-list-task me-1"></i>Sesi Mengajar
                        <span style="font-size:0.72rem;font-weight:400;color:rgba(255,255,255,0.3);">(bisa tambah banyak)</span>
                    </div>
                    <div class="sesi-list" id="sesiList"></div>
                    <button type="button" class="btn-add-sesi" id="btnAddSesi">
                        <i class="bi bi-plus-lg"></i> Tambah Sesi
                    </button>
                </form>
            </div>
            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-modal-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-modal-primary" id="btnSimpanJadwal">
                    <i class="bi bi-check-lg me-1"></i> Simpan Semua
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================
     MODAL KELOLA SESI GURU (edit semua sesi 1 guru di 1 hari)
================================================================ --}}
<div class="modal fade modal-dark" id="modalKelolaGuru"
     tabindex="-1" aria-labelledby="modalKelolaGuruLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalKelolaGuruLabel">
                    <i class="bi bi-pencil-fill me-2" style="color:#4ade80;"></i>
                    Kelola Jadwal — <span id="kelolaGuruNama" style="color:#4ade80;"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="kelolaGuruBody">
                <div class="list-loading"><span class="spinner-green"></span> Memuat sesi...</div>
            </div>
            <div class="modal-footer justify-content-between align-items-center gap-2">
                <button type="button" class="btn-add-sesi" id="btnAddSesiKelola" style="margin-top:0;">
                    <i class="bi bi-plus-lg"></i> Tambah Sesi Baru
                </button>
                <button type="button" class="btn-modal-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var CSRF = (document.querySelector('meta[name="csrf-token"]') || {}).content || '{{ csrf_token() }}';

    /* ============================================================
       STATE
    ============================================================ */
    var activeHari     = '';
    var searchTimer    = null;
    var dropdownLoaded = false;
    var dropdownCache  = { guru: [], mapel: [], kelas: [] };
    var sesiCount      = 0;   /* counter untuk ID unik baris sesi */

    /* ============================================================
       HELPERS
    ============================================================ */
    var DOW_TO_HARI = {0:'Senin',1:'Senin',2:'Selasa',3:'Rabu',4:'Kamis',5:'Jumat',6:'Sabtu'};
    function hariHariIni() { return DOW_TO_HARI[new Date().getDay()] || 'Senin'; }

    function escHtml(s) {
        return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function clearFb(id) { var el=document.getElementById(id); if(el) el.textContent=''; }
    function setFb(id, msg) { var el=document.getElementById(id); if(el) el.textContent=msg; }

    /* ============================================================
       LOAD LIST
    ============================================================ */
    function loadList(hari, search) {
        hari   = hari   || activeHari;
        search = search || '';
        activeHari = hari;
        syncHariButtons(hari);

        var container = document.getElementById('listContainer');
        container.innerHTML = '<div class="list-loading"><span class="spinner-green"></span> Memuat data...</div>';

        fetch('{{ route("admin.jadwal_mengajar.list") }}?hari='+encodeURIComponent(hari)+'&search='+encodeURIComponent(search), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r){ return r.text(); })
        .then(function(html){ container.innerHTML=html; bindListEvents(); })
        .catch(function(){ container.innerHTML='<div class="list-loading" style="color:#f87171;">Gagal memuat data.</div>'; });
    }

    function syncHariButtons(hari) {
        var hi = hariHariIni();
        document.querySelectorAll('.btn-hari').forEach(function(btn){
            btn.classList.remove('active','today-active');
            if(btn.dataset.hari===hari) btn.classList.add(hari===hi?'today-active':'active');
        });
    }

    document.querySelectorAll('.btn-hari').forEach(function(btn){
        btn.addEventListener('click', function(){ loadList(this.dataset.hari, document.getElementById('searchInput').value.trim()); });
    });

    document.getElementById('btnRefresh').addEventListener('click', function(){
        var icon=this.querySelector('i');
        icon.style.transition='transform 0.5s'; icon.style.transform='rotate(360deg)';
        setTimeout(function(){ icon.style.transform=''; }, 500);
        loadList(activeHari, document.getElementById('searchInput').value.trim());
    });

    document.getElementById('searchInput').addEventListener('input', function(){
        clearTimeout(searchTimer);
        var val=this.value.trim();
        searchTimer=setTimeout(function(){ loadList(activeHari, val); }, 350);
    });

    /* ============================================================
       BIND LIST EVENTS
    ============================================================ */
    function bindListEvents() {
        var c = document.getElementById('listContainer');

        /* Tombol Edit Guru (1 per baris) */
        c.querySelectorAll('[data-edit-guru-id]').forEach(function(btn){
            btn.addEventListener('click', function(){
                bukaModalKelola(this.dataset.editGuruId, this.dataset.editHari, this.dataset.guruNama);
            });
        });

        /* Tombol Hapus Semua Sesi Guru (1 per baris) */
        c.querySelectorAll('[data-delete-guru-id]').forEach(function(btn){
            btn.addEventListener('click', function(){
                var ids   = this.dataset.deleteIds.split(',').filter(Boolean);
                var label = this.dataset.deleteLabel;
                var hari  = this.dataset.deleteHari;
                confirmHapusBulk(ids, label, hari);
            });
        });
    }

    /* ============================================================
       LOAD DROPDOWN
    ============================================================ */
    function loadDropdown(cb) {
        if(dropdownLoaded){ if(cb) cb(); return; }
        fetch('{{ route("admin.jadwal_mengajar.dropdown") }}', { headers:{'X-Requested-With':'XMLHttpRequest'} })
        .then(function(r){ return r.json(); })
        .then(function(data){
            dropdownCache  = data;
            dropdownLoaded = true;
            populateSelect('selectGuru', data.guru,  'id', 'name',       '— Pilih Guru —');
            if(cb) cb();
        });
    }

    function populateSelect(id, items, valKey, labelKey, placeholder) {
        var el = document.getElementById(id);
        if(!el) return;
        el.innerHTML = '<option value="">'+placeholder+'</option>';
        (items||[]).forEach(function(item){
            var opt = document.createElement('option');
            opt.value = item[valKey]; opt.textContent = item[labelKey];
            el.appendChild(opt);
        });
    }

    function buildSelectHtml(items, valKey, labelKey, placeholder, selectedVal) {
        var html = '<option value="">'+escHtml(placeholder)+'</option>';
        (items||[]).forEach(function(item){
            var sel = String(item[valKey]) === String(selectedVal) ? ' selected' : '';
            html += '<option value="'+escHtml(item[valKey])+'"'+sel+'>'+escHtml(item[labelKey])+'</option>';
        });
        return html;
    }

    /* ============================================================
       MODAL TAMBAH — multi-sesi
    ============================================================ */
    var modalTambahEl = document.getElementById('modalTambahJadwal');
    var modalTambah   = new bootstrap.Modal(modalTambahEl);

    document.getElementById('btnTambahJadwal').addEventListener('click', function(){
        resetFormTambah();
        loadDropdown(function(){
            document.getElementById('selectHari').value = activeHari;
            /* Buat 1 sesi awal */
            addSesiRow();
        });
        modalTambah.show();
    });

    function resetFormTambah() {
        sesiCount = 0;
        document.getElementById('sesiList').innerHTML = '';
        ['selectGuru','selectHari'].forEach(function(id){
            var el=document.getElementById(id); if(el){ el.value=''; el.classList.remove('is-invalid'); }
        });
        ['fbGuru','fbHari'].forEach(clearFb);
    }

    /* Buat baris sesi baru */
    function addSesiRow(data) {
        sesiCount++;
        var idx = sesiCount;
        var container = document.getElementById('sesiList');
        var div = document.createElement('div');
        div.className = 'sesi-row';
        div.id = 'sesi-row-'+idx;

        var mapelOpts = buildSelectHtml(dropdownCache.mapel, 'id', 'nama_mapel', '— Pilih Mapel —', data?data.mapel_id:'');
        var kelasOpts = buildSelectHtml(dropdownCache.kelas, 'id', 'nama_kelas', '— Pilih Kelas —', data?data.kelas_id:'');

        div.innerHTML = '<div class="sesi-row-header">'
            + 'Sesi '+idx
            + (sesiCount>1 ? ' <button type="button" class="btn-rm-sesi" onclick="removeSesiRow('+idx+')">Hapus</button>' : '')
            + '</div>'
            + '<div class="mb-2">'
            +   '<label class="form-label-dark">Mata Pelajaran *</label>'
            +   '<select class="form-select-dark sesi-mapel" data-idx="'+idx+'">'+mapelOpts+'</select>'
            +   '<div class="field-feedback" id="fb-mapel-'+idx+'"></div>'
            + '</div>'
            + '<div class="mb-2">'
            +   '<label class="form-label-dark">Kelas *</label>'
            +   '<select class="form-select-dark sesi-kelas" data-idx="'+idx+'">'+kelasOpts+'</select>'
            +   '<div class="field-feedback" id="fb-kelas-'+idx+'"></div>'
            + '</div>'
            + '<div class="time-row">'
            +   '<div>'
            +     '<label class="form-label-dark">Jam Mulai *</label>'
            +     '<input type="time" class="form-control-dark sesi-mulai" data-idx="'+idx+'" value="'+(data?data.waktu_mulai:'')+'">'
            +     '<div class="field-feedback" id="fb-mulai-'+idx+'"></div>'
            +   '</div>'
            +   '<div>'
            +     '<label class="form-label-dark">Jam Berakhir *</label>'
            +     '<input type="time" class="form-control-dark sesi-akhir" data-idx="'+idx+'" value="'+(data?data.waktu_berakhir:'')+'">'
            +     '<div class="field-feedback" id="fb-akhir-'+idx+'"></div>'
            +   '</div>'
            + '</div>';

        container.appendChild(div);
    }

    window.removeSesiRow = function(idx) {
        var el = document.getElementById('sesi-row-'+idx);
        if(el) el.remove();
    };

    document.getElementById('btnAddSesi').addEventListener('click', function(){
        addSesiRow();
    });

    /* Validate & Submit tambah */
    document.getElementById('btnSimpanJadwal').addEventListener('click', function(){
        var guruVal = document.getElementById('selectGuru').value;
        var hariVal = document.getElementById('selectHari').value;
        var ok = true;

        if(!guruVal){ document.getElementById('selectGuru').classList.add('is-invalid'); setFb('fbGuru','Pilih guru.'); ok=false; }
        else { document.getElementById('selectGuru').classList.remove('is-invalid'); clearFb('fbGuru'); }

        if(!hariVal){ document.getElementById('selectHari').classList.add('is-invalid'); setFb('fbHari','Pilih hari.'); ok=false; }
        else { document.getElementById('selectHari').classList.remove('is-invalid'); clearFb('fbHari'); }

        /* Kumpulkan sesi */
        var sesiRows = document.querySelectorAll('#sesiList .sesi-row');
        if(!sesiRows.length){ setFb('fbHari','Tambahkan minimal 1 sesi.'); ok=false; }

        var sesiData = [];
        sesiRows.forEach(function(row){
            var idx    = row.id.replace('sesi-row-','');
            var mapel  = row.querySelector('.sesi-mapel').value;
            var kelas  = row.querySelector('.sesi-kelas').value;
            var mulai  = row.querySelector('.sesi-mulai').value;
            var akhir  = row.querySelector('.sesi-akhir').value;

            if(!mapel){ row.querySelector('.sesi-mapel').classList.add('is-invalid'); document.getElementById('fb-mapel-'+idx).textContent='Pilih mapel.'; ok=false; }
            else { row.querySelector('.sesi-mapel').classList.remove('is-invalid'); document.getElementById('fb-mapel-'+idx).textContent=''; }

            if(!kelas){ row.querySelector('.sesi-kelas').classList.add('is-invalid'); document.getElementById('fb-kelas-'+idx).textContent='Pilih kelas.'; ok=false; }
            else { row.querySelector('.sesi-kelas').classList.remove('is-invalid'); document.getElementById('fb-kelas-'+idx).textContent=''; }

            if(!mulai){ row.querySelector('.sesi-mulai').classList.add('is-invalid'); document.getElementById('fb-mulai-'+idx).textContent='Isi jam mulai.'; ok=false; }
            else { row.querySelector('.sesi-mulai').classList.remove('is-invalid'); document.getElementById('fb-mulai-'+idx).textContent=''; }

            if(!akhir){ row.querySelector('.sesi-akhir').classList.add('is-invalid'); document.getElementById('fb-akhir-'+idx).textContent='Isi jam berakhir.'; ok=false; }
            else if(akhir<=mulai){ row.querySelector('.sesi-akhir').classList.add('is-invalid'); document.getElementById('fb-akhir-'+idx).textContent='Harus setelah jam mulai.'; ok=false; }
            else { row.querySelector('.sesi-akhir').classList.remove('is-invalid'); document.getElementById('fb-akhir-'+idx).textContent=''; }

            if(mapel && kelas && mulai && akhir && akhir>mulai) {
                sesiData.push({ mapel_id:mapel, kelas_id:kelas, waktu_mulai:mulai, waktu_berakhir:akhir });
            }
        });

        if(!ok) return;

        var btn = document.getElementById('btnSimpanJadwal');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-green" style="width:14px;height:14px;border-width:2px;margin-right:5px;"></span> Menyimpan...';

        fetch('{{ route("admin.jadwal_mengajar.store_multi") }}', {
            method:'POST',
            headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest','Content-Type':'application/json'},
            body: JSON.stringify({ user_id:guruVal, hari:hariVal, sesi:sesiData })
        })
        .then(function(r){ return r.json(); })
        .then(function(data){
            btn.disabled=false; btn.innerHTML='<i class="bi bi-check-lg me-1"></i> Simpan Semua';
            if(data.success){
                modalTambah.hide();
                Swal.fire({ icon:'success', title:'Berhasil!', text:data.message, timer:2200, showConfirmButton:false, background:'#0a2e1c', color:'#fff' })
                .then(function(){ loadList(document.getElementById('selectHari').value||activeHari, document.getElementById('searchInput').value.trim()); });
            } else {
                Swal.fire({ icon:'error', title:'Gagal', text:data.message||'Terjadi kesalahan.', background:'#0a2e1c', color:'#fff' });
            }
        })
        .catch(function(){
            btn.disabled=false; btn.innerHTML='<i class="bi bi-check-lg me-1"></i> Simpan Semua';
            Swal.fire({ icon:'error', title:'Koneksi gagal', text:'Tidak dapat terhubung ke server.', background:'#0a2e1c', color:'#fff' });
        });
    });

    /* ============================================================
       MODAL KELOLA SESI GURU
    ============================================================ */
    var modalKelolaEl = document.getElementById('modalKelolaGuru');
    var modalKelola   = new bootstrap.Modal(modalKelolaEl);
    var kelolaGuruId  = null;
    var kelolaHari    = null;

    function bukaModalKelola(guruId, hari, nama) {
        kelolaGuruId = guruId;
        kelolaHari   = hari;
        document.getElementById('kelolaGuruNama').textContent = nama || '';
        document.getElementById('kelolaGuruBody').innerHTML =
            '<div class="list-loading"><span class="spinner-green"></span> Memuat sesi...</div>';
        modalKelola.show();

        loadDropdown(function(){
            fetch('{{ route("admin.jadwal_mengajar.edit_guru") }}?user_id='+guruId+'&hari='+encodeURIComponent(hari), {
                headers:{'X-Requested-With':'XMLHttpRequest'}
            })
            .then(function(r){ return r.json(); })
            .then(function(data){ renderKelolaBody(data); })
            .catch(function(){ document.getElementById('kelolaGuruBody').innerHTML='<p style="color:#f87171;text-align:center;">Gagal memuat data.</p>'; });
        });
    }

    function renderKelolaBody(data) {
        var jadwals = data.jadwals || [];
        if(!jadwals.length){
            document.getElementById('kelolaGuruBody').innerHTML=
                '<p style="text-align:center;color:rgba(255,255,255,0.35);padding:2rem 0;">Tidak ada sesi untuk guru ini pada hari '+escHtml(data.hari)+'.</p>';
            return;
        }

        var html = '';
        jadwals.forEach(function(j){
            var mapelOpts = buildSelectHtml(dropdownCache.mapel,'id','nama_mapel','— Pilih Mapel —', j.mapel_id);
            var kelasOpts = buildSelectHtml(dropdownCache.kelas,'id','nama_kelas','— Pilih Kelas —', j.kelas_id);

            html += '<div class="kelola-sesi-row" id="kelola-row-'+j.id+'">'
                + '<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.6rem;">'
                +   '<span style="font-size:0.78rem;font-weight:700;color:rgba(74,222,128,0.7);">'
                +     '<i class="bi bi-clock me-1"></i>'+escHtml(j.waktu_mulai)+'–'+escHtml(j.waktu_berakhir)
                +   '</span>'
                +   '<span class="sesi-saved-indicator" id="saved-'+j.id+'"><i class="bi bi-check-circle-fill me-1"></i>Tersimpan</span>'
                + '</div>'
                + '<div class="mb-2">'
                +   '<label class="form-label-dark">Mata Pelajaran</label>'
                +   '<select class="form-select-dark" id="k-mapel-'+j.id+'">'+mapelOpts+'</select>'
                + '</div>'
                + '<div class="mb-2">'
                +   '<label class="form-label-dark">Kelas</label>'
                +   '<select class="form-select-dark" id="k-kelas-'+j.id+'">'+kelasOpts+'</select>'
                + '</div>'
                + '<div class="time-row mb-2">'
                +   '<div>'
                +     '<label class="form-label-dark">Jam Mulai</label>'
                +     '<input type="time" class="form-control-dark" id="k-mulai-'+j.id+'" value="'+escHtml(j.waktu_mulai)+'">'
                +   '</div>'
                +   '<div>'
                +     '<label class="form-label-dark">Jam Berakhir</label>'
                +     '<input type="time" class="form-control-dark" id="k-akhir-'+j.id+'" value="'+escHtml(j.waktu_berakhir)+'">'
                +   '</div>'
                + '</div>'
                + '<div style="display:flex;gap:0.5rem;margin-top:0.5rem;">'
                +   '<button type="button" class="btn-sesi-save" id="k-save-'+j.id+'" onclick="simpanSesi('+j.id+')">'
                +     '<i class="bi bi-check-lg"></i> Simpan'
                +   '</button>'
                +   '<button type="button" class="btn-sesi-del" onclick="hapusSesi('+j.id+', \''+escHtml(j.waktu_mulai)+'\')">'
                +     '<i class="bi bi-trash3"></i> Hapus'
                +   '</button>'
                + '</div>'
                + '<div class="field-feedback" id="k-fb-'+j.id+'"></div>'
                + '</div>';
        });
        document.getElementById('kelolaGuruBody').innerHTML = html;
    }

    /* Tombol tambah sesi baru di modal kelola */
    document.getElementById('btnAddSesiKelola').addEventListener('click', function () {
        addSesiKelola();
    });

    /* Render form sesi baru (belum punya ID DB) di modal kelola */
    var kelolaNewCount = 0;

    function addSesiKelola() {
        if (!dropdownCache.mapel.length || !dropdownCache.kelas.length) {
            Swal.fire({ icon:'warning', title:'Data belum dimuat', text:'Tunggu sebentar, data mapel/kelas sedang dimuat.', background:'#0a2e1c', color:'#fff' });
            return;
        }

        kelolaNewCount++;
        var nid      = 'new-' + kelolaNewCount;
        var body     = document.getElementById('kelolaGuruBody');
        var mapelOpts = buildSelectHtml(dropdownCache.mapel,'id','nama_mapel','— Pilih Mapel —', '');
        var kelasOpts = buildSelectHtml(dropdownCache.kelas,'id','nama_kelas','— Pilih Kelas —', '');

        var div = document.createElement('div');
        div.className = 'kelola-sesi-row';
        div.id = 'kelola-row-' + nid;
        div.innerHTML = '<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.6rem;">'
            + '<span style="font-size:0.78rem;font-weight:700;color:rgba(74,222,128,0.55);">Sesi Baru</span>'
            + '</div>'
            + '<div class="mb-2"><label class="form-label-dark">Mata Pelajaran</label>'
            +   '<select class="form-select-dark" id="k-mapel-'+nid+'">'+mapelOpts+'</select>'
            + '</div>'
            + '<div class="mb-2"><label class="form-label-dark">Kelas</label>'
            +   '<select class="form-select-dark" id="k-kelas-'+nid+'">'+kelasOpts+'</select>'
            + '</div>'
            + '<div class="time-row mb-2">'
            +   '<div><label class="form-label-dark">Jam Mulai</label>'
            +     '<input type="time" class="form-control-dark" id="k-mulai-'+nid+'">'
            +   '</div>'
            +   '<div><label class="form-label-dark">Jam Berakhir</label>'
            +     '<input type="time" class="form-control-dark" id="k-akhir-'+nid+'">'
            +   '</div>'
            + '</div>'
            + '<div style="display:flex;gap:0.5rem;margin-top:0.5rem;">'
            +   '<button type="button" class="btn-sesi-save" id="k-save-'+nid+'" onclick="simpanSesiBaru(\''+nid+'\')">'
            +     '<i class="bi bi-plus-lg"></i> Tambahkan'
            +   '</button>'
            +   '<button type="button" class="btn-sesi-del" onclick="document.getElementById(\'kelola-row-'+nid+'\').remove()">'
            +     '<i class="bi bi-x-lg"></i> Batal'
            +   '</button>'
            + '</div>'
            + '<div class="field-feedback" id="k-fb-'+nid+'"></div>';

        body.appendChild(div);
        /* Scroll ke bawah modal agar row baru terlihat */
        body.scrollTop = body.scrollHeight;
    }

    /* Simpan sesi baru (POST storeMulti dengan 1 sesi) */
    window.simpanSesiBaru = function (nid) {
        var mapel = document.getElementById('k-mapel-'+nid).value;
        var kelas = document.getElementById('k-kelas-'+nid).value;
        var mulai = document.getElementById('k-mulai-'+nid).value;
        var akhir = document.getElementById('k-akhir-'+nid).value;
        var fbEl  = document.getElementById('k-fb-'+nid);
        var btn   = document.getElementById('k-save-'+nid);

        if (!mapel || !kelas || !mulai || !akhir) { fbEl.textContent = 'Semua field wajib diisi.'; return; }
        if (akhir <= mulai) { fbEl.textContent = 'Jam berakhir harus setelah jam mulai.'; return; }
        fbEl.textContent = '';

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-green" style="width:12px;height:12px;border-width:2px;"></span>';

        fetch('{{ route("admin.jadwal_mengajar.store_multi") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' },
            body: JSON.stringify({
                user_id: kelolaGuruId,
                hari:    kelolaHari,
                sesi:    [{ mapel_id: mapel, kelas_id: kelas, waktu_mulai: mulai, waktu_berakhir: akhir }]
            })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-plus-lg"></i> Tambahkan';
            if (data.success) {
                /* Hapus row sementara lalu reload semua sesi */
                var row = document.getElementById('kelola-row-'+nid);
                if (row) row.remove();
                loadList(activeHari, document.getElementById('searchInput').value.trim());
                /* Reload daftar sesi di modal */
                fetch('{{ route("admin.jadwal_mengajar.edit_guru") }}?user_id='+kelolaGuruId+'&hari='+encodeURIComponent(kelolaHari), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function (r) { return r.json(); })
                .then(function (d) { renderKelolaBody(d); });
            } else {
                fbEl.textContent = data.message || 'Gagal menyimpan.';
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-plus-lg"></i> Tambahkan';
            fbEl.textContent = 'Koneksi gagal.';
        });
    };

    window.simpanSesi = function(id) {
        var mapel  = document.getElementById('k-mapel-'+id).value;
        var kelas  = document.getElementById('k-kelas-'+id).value;
        var mulai  = document.getElementById('k-mulai-'+id).value;
        var akhir  = document.getElementById('k-akhir-'+id).value;
        var fbEl   = document.getElementById('k-fb-'+id);
        var saveBtn= document.getElementById('k-save-'+id);

        if(!mapel||!kelas||!mulai||!akhir){ fbEl.textContent='Semua field wajib diisi.'; return; }
        if(akhir<=mulai){ fbEl.textContent='Jam berakhir harus setelah jam mulai.'; return; }
        fbEl.textContent='';

        saveBtn.disabled=true; saveBtn.innerHTML='<span class="spinner-green" style="width:12px;height:12px;border-width:2px;"></span>';

        fetch('{{ url("admin/jadwal-mengajar") }}/'+id+'/sesi', {
            method:'PATCH',
            headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest','Content-Type':'application/json'},
            body: JSON.stringify({ mapel_id:mapel, kelas_id:kelas, waktu_mulai:mulai, waktu_berakhir:akhir })
        })
        .then(function(r){ return r.json(); })
        .then(function(data){
            saveBtn.disabled=false; saveBtn.innerHTML='<i class="bi bi-check-lg"></i> Simpan';
            if(data.success){
                var ind = document.getElementById('saved-'+id);
                if(ind){ ind.style.display='inline-flex'; setTimeout(function(){ ind.style.display='none'; },2500); }
                /* Update label jam di header sesi */
                var row = document.getElementById('kelola-row-'+id);
                if(row){
                    var span = row.querySelector('span[style*="font-size:0.78rem"]');
                    if(span) span.innerHTML = '<i class="bi bi-clock me-1"></i>'+escHtml(mulai)+'–'+escHtml(akhir);
                }
                loadList(activeHari, document.getElementById('searchInput').value.trim());
            } else {
                fbEl.textContent = data.message||'Gagal menyimpan.';
            }
        })
        .catch(function(){ saveBtn.disabled=false; saveBtn.innerHTML='<i class="bi bi-check-lg"></i> Simpan'; fbEl.textContent='Koneksi gagal.'; });
    };

    window.hapusSesi = function(id, label) {
        Swal.fire({
            icon:'warning', title:'Hapus sesi ini?',
            html:'Sesi jam <strong>'+escHtml(label)+'</strong> akan dihapus.',
            showCancelButton:true, confirmButtonText:'Ya, hapus', cancelButtonText:'Batal',
            confirmButtonColor:'#f87171', cancelButtonColor:'rgba(255,255,255,0.1)',
            background:'#0a2e1c', color:'#fff',
        }).then(function(result){
            if(!result.isConfirmed) return;
            fetch('{{ url("admin/jadwal-mengajar") }}/'+id, {
                method:'DELETE',
                headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest','Content-Type':'application/json'}
            })
            .then(function(r){ return r.json(); })
            .then(function(data){
                if(data.success){
                    var row = document.getElementById('kelola-row-'+id);
                    if(row) row.remove();
                    loadList(activeHari, document.getElementById('searchInput').value.trim());
                    /* Jika tidak ada sesi tersisa, tutup modal */
                    if(!document.querySelector('#kelolaGuruBody .kelola-sesi-row')){
                        document.getElementById('kelolaGuruBody').innerHTML=
                            '<p style="text-align:center;color:rgba(255,255,255,0.35);padding:2rem 0;">Semua sesi telah dihapus.</p>';
                    }
                } else {
                    Swal.fire({ icon:'error', title:'Gagal', text:data.message, background:'#0a2e1c', color:'#fff' });
                }
            });
        });
    };

    /* ============================================================
       HAPUS BULK (semua sesi 1 guru di 1 hari)
    ============================================================ */
    function confirmHapusBulk(ids, label, hari) {
        Swal.fire({
            icon:'warning', title:'Hapus semua jadwal?',
            html:'Semua jadwal <strong>'+escHtml(label)+'</strong> hari <strong>'+escHtml(hari)+'</strong> ('+ids.length+' sesi) akan dihapus.',
            showCancelButton:true, confirmButtonText:'Ya, hapus semua', cancelButtonText:'Batal',
            confirmButtonColor:'#f87171', cancelButtonColor:'rgba(255,255,255,0.1)',
            background:'#0a2e1c', color:'#fff',
        }).then(function(result){
            if(!result.isConfirmed) return;
            fetch('{{ route("admin.jadwal_mengajar.destroy_bulk") }}', {
                method:'DELETE',
                headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest','Content-Type':'application/json'},
                body: JSON.stringify({ ids:ids })
            })
            .then(function(r){ return r.json(); })
            .then(function(data){
                if(data.success){
                    Swal.fire({ icon:'success', title:'Dihapus', text:data.message, timer:1800, showConfirmButton:false, background:'#0a2e1c', color:'#fff' })
                    .then(function(){ loadList(activeHari, document.getElementById('searchInput').value.trim()); });
                } else {
                    Swal.fire({ icon:'error', title:'Gagal', text:data.message, background:'#0a2e1c', color:'#fff' });
                }
            });
        });
    }

    /* ============================================================
       INIT
    ============================================================ */
    loadList(hariHariIni(), '');

}());
</script>
@endpush
