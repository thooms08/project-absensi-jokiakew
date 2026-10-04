@extends('admin.layouts.app')

@section('title', 'Jadwal Mengajar')
@section('page-title', 'Jadwal Mengajar')

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
        margin-bottom: 1.25rem;
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
       FILTER BAR  (hari saja, tanpa date picker)
    ============================================================ */
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
        font-size: 0.72rem;
        font-weight: 700;
        color: rgba(74,222,128,0.6);
        text-transform: uppercase;
        letter-spacing: 0.07em;
        white-space: nowrap;
        margin-right: 0.25rem;
    }

    .btn-hari {
        display: inline-flex;
        align-items: center;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: rgba(255,255,255,0.55);
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.4rem 0.85rem;
        cursor: pointer;
        transition: all 0.15s;
        white-space: nowrap;
        font-family: 'Inter', sans-serif;
    }

    .btn-hari:hover {
        background: rgba(74,222,128,0.1);
        border-color: rgba(74,222,128,0.25);
        color: #4ade80;
    }

    .btn-hari.active {
        background: rgba(74,222,128,0.15);
        border-color: rgba(74,222,128,0.4);
        color: #4ade80;
    }

    .btn-hari.today-active {
        background: rgba(34,197,94,0.18);
        border-color: #22c55e;
        color: #4ade80;
        box-shadow: 0 0 0 1px rgba(34,197,94,0.25);
    }

    .btn-refresh {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px; height: 32px;
        border-radius: 8px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.45);
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.15s;
        flex-shrink: 0;
        margin-left: auto;
    }

    .btn-refresh:hover {
        background: rgba(74,222,128,0.1);
        color: #4ade80;
        border-color: rgba(74,222,128,0.25);
    }

    /* ============================================================
       ACTION BAR
    ============================================================ */
    .action-bar {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.25rem;
    }

    .search-wrap {
        position: relative;
        flex: 1;
        min-width: 200px;
        max-width: 320px;
    }

    .search-wrap .bi-search {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(74,222,128,0.5);
        font-size: 0.85rem;
        pointer-events: none;
    }

    .search-input {
        width: 100%;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(74,222,128,0.18);
        border-radius: 10px;
        color: #e2f5ea;
        font-size: 0.875rem;
        padding: 0.5rem 0.85rem 0.5rem 2.3rem;
        transition: border-color 0.18s, background 0.18s;
        font-family: 'Inter', sans-serif;
        outline: none;
    }

    .search-input::placeholder { color: rgba(255,255,255,0.28); }
    .search-input:focus {
        border-color: rgba(74,222,128,0.45);
        background: rgba(255,255,255,0.07);
    }

    .btn-add-jadwal {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #0a7c4a, #22c55e);
        border: none;
        border-radius: 10px;
        color: #fff;
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.55rem 1.15rem;
        cursor: pointer;
        transition: opacity 0.18s, transform 0.18s;
        white-space: nowrap;
        font-family: 'Inter', sans-serif;
    }

    .btn-add-jadwal:hover { opacity: 0.88; transform: translateY(-1px); }

    /* ============================================================
       LIST CONTAINER
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
        width: 26px; height: 26px;
        border: 3px solid rgba(74,222,128,0.15);
        border-top-color: #4ade80;
        border-radius: 50%;
        animation: spin 0.65s linear infinite;
        display: inline-block;
        vertical-align: middle;
        margin-right: 0.5rem;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    /* ============================================================
       MODAL GELAP
    ============================================================ */
    .modal-dark .modal-content {
        background: #0a2e1c;
        border: 1px solid rgba(74,222,128,0.18);
        border-radius: 16px;
        color: #e2f5ea;
    }

    .modal-dark .modal-header {
        border-bottom: 1px solid rgba(74,222,128,0.12);
        padding: 1.1rem 1.4rem 1rem;
    }

    .modal-dark .modal-title { font-size: 1rem; font-weight: 700; color: #fff; }
    .modal-dark .modal-footer { border-top: 1px solid rgba(74,222,128,0.12); padding: 0.9rem 1.4rem; }
    .modal-dark .btn-close { filter: invert(1) brightness(0.7); }
    .modal-dark .modal-body { padding: 1.25rem 1.4rem; }

    /* ============================================================
       FORM ELEMENTS
    ============================================================ */
    .form-label-dark {
        font-size: 0.8rem;
        font-weight: 600;
        color: rgba(255,255,255,0.65);
        margin-bottom: 0.4rem;
        display: block;
    }

    .form-control-dark,
    .form-select-dark {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(74,222,128,0.18);
        border-radius: 10px;
        color: #e2f5ea;
        font-size: 0.875rem;
        padding: 0.55rem 0.85rem;
        width: 100%;
        transition: border-color 0.18s, background 0.18s;
        font-family: 'Inter', sans-serif;
        outline: none;
    }

    .form-select-dark {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='rgba(74,222,128,0.5)' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.85rem center;
        padding-right: 2.2rem;
        cursor: pointer;
    }

    .form-select-dark option { background: #0a2e1c; color: #e2f5ea; }
    .form-control-dark::placeholder { color: rgba(255,255,255,0.28); }

    .form-control-dark:focus,
    .form-select-dark:focus {
        border-color: rgba(74,222,128,0.5);
        background: rgba(255,255,255,0.09);
        box-shadow: 0 0 0 3px rgba(74,222,128,0.07);
    }

    .form-control-dark.is-invalid,
    .form-select-dark.is-invalid { border-color: rgba(248,113,113,0.6); }

    .field-feedback {
        font-size: 0.75rem;
        margin-top: 0.28rem;
        min-height: 1rem;
        color: #f87171;
    }

    .time-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    /* ============================================================
       TOMBOL MODAL
    ============================================================ */
    .btn-modal-primary {
        background: linear-gradient(135deg, #0a7c4a, #22c55e);
        border: none; border-radius: 10px;
        color: #fff; font-size: 0.875rem; font-weight: 600;
        padding: 0.55rem 1.35rem; cursor: pointer;
        transition: opacity 0.18s; font-family: 'Inter', sans-serif;
    }

    .btn-modal-primary:hover { opacity: 0.87; }
    .btn-modal-primary:disabled { opacity: 0.5; cursor: not-allowed; }

    .btn-modal-secondary {
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 10px; color: rgba(255,255,255,0.72);
        font-size: 0.875rem; font-weight: 500;
        padding: 0.55rem 1.2rem; cursor: pointer;
        transition: background 0.18s; font-family: 'Inter', sans-serif;
    }

    .btn-modal-secondary:hover { background: rgba(255,255,255,0.12); }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 575.98px) {
        .page-header { flex-direction: column; align-items: flex-start; }
        .filter-bar  { gap: 0.4rem; }
        .btn-hari    { font-size: 0.75rem; padding: 0.35rem 0.6rem; }
        .modal-dialog { margin: 0.5rem; }
        .time-row { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

{{-- ================================================================
     PAGE HEADER
================================================================ --}}
<div class="page-header">
    <div class="page-header-left">
        <h2>Jadwal Mengajar</h2>
        <p>Atur jadwal mengajar guru per hari</p>
    </div>
</div>

{{-- ================================================================
     FILTER BAR  (hari Sen–Sab, tanpa date picker)
================================================================ --}}
<div class="filter-bar">
    <span class="filter-label"><i class="bi bi-funnel-fill me-1"></i>Hari</span>

    @php
        $daftarHari = [
            ['val' => 'Senin',   'singkat' => 'Senin'],
            ['val' => 'Selasa',  'singkat' => 'Selasa'],
            ['val' => 'Rabu',    'singkat' => 'Rabu'],
            ['val' => 'Kamis',   'singkat' => 'Kamis'],
            ['val' => 'Jumat',   'singkat' => 'Jumat'],
            ['val' => 'Sabtu',   'singkat' => 'Sabtu'],
        ];
    @endphp

    @foreach($daftarHari as $h)
        <button
            type="button"
            class="btn-hari"
            data-hari="{{ $h['val'] }}"
            title="{{ $h['val'] }}"
        >{{ $h['singkat'] }}</button>
    @endforeach

    <button type="button" class="btn-refresh" id="btnRefresh" title="Muat ulang">
        <i class="bi bi-arrow-clockwise"></i>
    </button>
</div>

{{-- ================================================================
     ACTION BAR
================================================================ --}}
<div class="action-bar">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input
            type="text"
            id="searchInput"
            class="search-input"
            placeholder="Cari nama guru, kelas, mapel..."
            autocomplete="off"
        >
    </div>

    <button class="btn-add-jadwal" id="btnTambahJadwal">
        <i class="bi bi-plus-lg"></i> Jadwal
    </button>
</div>

{{-- ================================================================
     LIST CONTAINER  (diisi AJAX)
================================================================ --}}
<div class="list-container" id="listContainer">
    <div class="list-loading">
        <span class="spinner-green"></span> Memuat data...
    </div>
</div>

{{-- ================================================================
     MODAL TAMBAH JADWAL
================================================================ --}}
<div class="modal fade modal-dark" id="modalTambahJadwal"
     tabindex="-1" aria-labelledby="modalTambahJadwalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahJadwalLabel">
                    <i class="bi bi-calendar2-plus-fill me-2" style="color:#4ade80;"></i>
                    Tambah Jadwal Mengajar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
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

                    {{-- Mata Pelajaran --}}
                    <div class="mb-3">
                        <label class="form-label-dark" for="selectMapel">
                            Mata Pelajaran <span style="color:#f87171;">*</span>
                        </label>
                        <select id="selectMapel" name="mapel_id" class="form-select-dark">
                            <option value="">— Pilih Mapel —</option>
                        </select>
                        <div class="field-feedback" id="fbMapel"></div>
                    </div>

                    {{-- Kelas --}}
                    <div class="mb-3">
                        <label class="form-label-dark" for="selectKelas">
                            Kelas <span style="color:#f87171;">*</span>
                        </label>
                        <select id="selectKelas" name="kelas_id" class="form-select-dark">
                            <option value="">— Pilih Kelas —</option>
                        </select>
                        <div class="field-feedback" id="fbKelas"></div>
                    </div>

                    {{-- Hari (ganti field tanggal) --}}
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

                    {{-- Waktu --}}
                    <div class="time-row">
                        <div>
                            <label class="form-label-dark" for="inputWaktuMulai">
                                Jam Mulai <span style="color:#f87171;">*</span>
                            </label>
                            <input
                                type="time"
                                id="inputWaktuMulai"
                                name="waktu_mulai"
                                class="form-control-dark"
                            >
                            <div class="field-feedback" id="fbWaktuMulai"></div>
                        </div>
                        <div>
                            <label class="form-label-dark" for="inputWaktuBerakhir">
                                Jam Berakhir <span style="color:#f87171;">*</span>
                            </label>
                            <input
                                type="time"
                                id="inputWaktuBerakhir"
                                name="waktu_berakhir"
                                class="form-control-dark"
                            >
                            <div class="field-feedback" id="fbWaktuBerakhir"></div>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-modal-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-modal-primary" id="btnSimpanJadwal">
                    <i class="bi bi-check-lg me-1"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================
     MODAL EDIT JADWAL
================================================================ --}}
<div class="modal fade modal-dark" id="modalEditJadwal"
     tabindex="-1" aria-labelledby="modalEditJadwalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditJadwalLabel">
                    <i class="bi bi-pencil-fill me-2" style="color:#4ade80;"></i>
                    Edit Jadwal Mengajar
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <form id="formEditJadwal" novalidate>
                    @csrf
                    <input type="hidden" id="editJadwalId">

                    {{-- Guru --}}
                    <div class="mb-3">
                        <label class="form-label-dark" for="editSelectGuru">
                            Guru <span style="color:#f87171;">*</span>
                        </label>
                        <select id="editSelectGuru" name="user_id" class="form-select-dark">
                            <option value="">— Pilih Guru —</option>
                        </select>
                        <div class="field-feedback" id="fbEditGuru"></div>
                    </div>

                    {{-- Mata Pelajaran --}}
                    <div class="mb-3">
                        <label class="form-label-dark" for="editSelectMapel">
                            Mata Pelajaran <span style="color:#f87171;">*</span>
                        </label>
                        <select id="editSelectMapel" name="mapel_id" class="form-select-dark">
                            <option value="">— Pilih Mapel —</option>
                        </select>
                        <div class="field-feedback" id="fbEditMapel"></div>
                    </div>

                    {{-- Kelas --}}
                    <div class="mb-3">
                        <label class="form-label-dark" for="editSelectKelas">
                            Kelas <span style="color:#f87171;">*</span>
                        </label>
                        <select id="editSelectKelas" name="kelas_id" class="form-select-dark">
                            <option value="">— Pilih Kelas —</option>
                        </select>
                        <div class="field-feedback" id="fbEditKelas"></div>
                    </div>

                    {{-- Hari --}}
                    <div class="mb-3">
                        <label class="form-label-dark" for="editSelectHari">
                            Hari <span style="color:#f87171;">*</span>
                        </label>
                        <select id="editSelectHari" name="hari" class="form-select-dark">
                            <option value="">— Pilih Hari —</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                        </select>
                        <div class="field-feedback" id="fbEditHari"></div>
                    </div>

                    {{-- Waktu --}}
                    <div class="time-row">
                        <div>
                            <label class="form-label-dark" for="editInputWaktuMulai">
                                Jam Mulai <span style="color:#f87171;">*</span>
                            </label>
                            <input
                                type="time"
                                id="editInputWaktuMulai"
                                name="waktu_mulai"
                                class="form-control-dark"
                            >
                            <div class="field-feedback" id="fbEditWaktuMulai"></div>
                        </div>
                        <div>
                            <label class="form-label-dark" for="editInputWaktuBerakhir">
                                Jam Berakhir <span style="color:#f87171;">*</span>
                            </label>
                            <input
                                type="time"
                                id="editInputWaktuBerakhir"
                                name="waktu_berakhir"
                                class="form-control-dark"
                            >
                            <div class="field-feedback" id="fbEditWaktuBerakhir"></div>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-modal-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-modal-primary" id="btnSimpanEditJadwal">
                    <i class="bi bi-check-lg me-1"></i> Simpan
                </button>
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
    var activeHari      = '';   /* nama hari aktif (Senin…Sabtu) */
    var searchTimer     = null;
    var dropdownLoaded  = false;
    var dropdownCache   = { guru: [], mapel: [], kelas: [] };

    /* ============================================================
       HELPERS
    ============================================================ */
    var DOW_TO_HARI = {
        0: 'Senin',   /* fallback Minggu → Senin */
        1: 'Senin',
        2: 'Selasa',
        3: 'Rabu',
        4: 'Kamis',
        5: 'Jumat',
        6: 'Sabtu',
    };

    function hariHariIni() {
        return DOW_TO_HARI[new Date().getDay()] || 'Senin';
    }

    function escHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;')
                        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function clearFb(id) {
        var el = document.getElementById(id);
        if (el) el.textContent = '';
    }

    function setFb(id, msg) {
        var el = document.getElementById(id);
        if (el) el.textContent = msg;
    }

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

        var url = '{{ route("admin.jadwal_mengajar.list") }}'
                + '?hari='   + encodeURIComponent(hari)
                + '&search=' + encodeURIComponent(search);

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function (r) { return r.text(); })
        .then(function (html) {
            container.innerHTML = html;
            bindListEvents();
        })
        .catch(function () {
            container.innerHTML = '<div class="list-loading" style="color:#f87171;">Gagal memuat data.</div>';
        });
    }

    /* ============================================================
       SYNC HIGHLIGHT TOMBOL HARI
    ============================================================ */
    function syncHariButtons(hari) {
        document.querySelectorAll('.btn-hari').forEach(function (btn) {
            btn.classList.remove('active', 'today-active');
            if (btn.dataset.hari === hari) {
                var isToday = hari === hariHariIni();
                btn.classList.add(isToday ? 'today-active' : 'active');
            }
        });
    }

    /* ============================================================
       FILTER EVENTS
    ============================================================ */
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

    /* ============================================================
       SEARCH DEBOUNCE
    ============================================================ */
    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchTimer);
        var val = this.value.trim();
        searchTimer = setTimeout(function () { loadList(activeHari, val); }, 350);
    });

    /* ============================================================
       BIND LIST EVENTS (delegasi)
    ============================================================ */
    function bindListEvents() {
        var c = document.getElementById('listContainer');
        c.querySelectorAll('[data-edit-id]').forEach(function (btn) {
            btn.addEventListener('click', function () { openEditModal(this.dataset.editId); });
        });
        c.querySelectorAll('[data-delete-id]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                confirmHapus(this.dataset.deleteId, this.dataset.deleteLabel);
            });
        });
    }

    /* ============================================================
       DROPDOWN  (guru, mapel, kelas)
    ============================================================ */
    function loadDropdown(cb) {
        if (dropdownLoaded) { if (cb) cb(); return; }

        fetch('{{ route("admin.jadwal_mengajar.dropdown") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            dropdownCache  = data;
            dropdownLoaded = true;
            populateSelect('selectGuru',      data.guru,  'id', 'name',       '— Pilih Guru —');
            populateSelect('selectMapel',     data.mapel, 'id', 'nama_mapel', '— Pilih Mapel —');
            populateSelect('selectKelas',     data.kelas, 'id', 'nama_kelas', '— Pilih Kelas —');
            populateSelect('editSelectGuru',  data.guru,  'id', 'name',       '— Pilih Guru —');
            populateSelect('editSelectMapel', data.mapel, 'id', 'nama_mapel', '— Pilih Mapel —');
            populateSelect('editSelectKelas', data.kelas, 'id', 'nama_kelas', '— Pilih Kelas —');
            if (cb) cb();
        });
    }

    function populateSelect(id, items, valKey, labelKey, placeholder) {
        var el = document.getElementById(id);
        if (!el) return;
        el.innerHTML = '<option value="">' + placeholder + '</option>';
        (items || []).forEach(function (item) {
            var opt = document.createElement('option');
            opt.value       = item[valKey];
            opt.textContent = item[labelKey];
            el.appendChild(opt);
        });
    }

    /* ============================================================
       MODAL TAMBAH
    ============================================================ */
    var modalTambahEl = document.getElementById('modalTambahJadwal');
    var modalTambah   = new bootstrap.Modal(modalTambahEl);

    document.getElementById('btnTambahJadwal').addEventListener('click', function () {
        resetFormTambah();
        loadDropdown(function () {
            /* Pre-select hari aktif */
            document.getElementById('selectHari').value = activeHari;
        });
        modalTambah.show();
    });

    function resetFormTambah() {
        ['selectGuru','selectMapel','selectKelas','selectHari',
         'inputWaktuMulai','inputWaktuBerakhir'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) { el.value = ''; el.classList.remove('is-invalid'); }
        });
        ['fbGuru','fbMapel','fbKelas','fbHari','fbWaktuMulai','fbWaktuBerakhir'].forEach(clearFb);
    }

    function validateTambah() {
        var ok = true;
        [
            { id: 'selectGuru',         fb: 'fbGuru',         msg: 'Pilih guru.' },
            { id: 'selectMapel',        fb: 'fbMapel',        msg: 'Pilih mata pelajaran.' },
            { id: 'selectKelas',        fb: 'fbKelas',        msg: 'Pilih kelas.' },
            { id: 'selectHari',         fb: 'fbHari',         msg: 'Pilih hari.' },
            { id: 'inputWaktuMulai',    fb: 'fbWaktuMulai',   msg: 'Isi jam mulai.' },
            { id: 'inputWaktuBerakhir', fb: 'fbWaktuBerakhir',msg: 'Isi jam berakhir.' },
        ].forEach(function (f) {
            var el = document.getElementById(f.id);
            if (!el.value) { el.classList.add('is-invalid'); setFb(f.fb, f.msg); ok = false; }
            else           { el.classList.remove('is-invalid'); clearFb(f.fb); }
        });
        var mulai = document.getElementById('inputWaktuMulai').value;
        var akhir = document.getElementById('inputWaktuBerakhir').value;
        if (mulai && akhir && akhir <= mulai) {
            document.getElementById('inputWaktuBerakhir').classList.add('is-invalid');
            setFb('fbWaktuBerakhir', 'Jam berakhir harus setelah jam mulai.');
            ok = false;
        }
        return ok;
    }

    document.getElementById('btnSimpanJadwal').addEventListener('click', function () {
        if (!validateTambah()) return;

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-green" style="width:14px;height:14px;border-width:2px;margin-right:5px;"></span> Menyimpan...';

        fetch('{{ route("admin.jadwal_mengajar.store") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' },
            body: JSON.stringify({
                user_id:        document.getElementById('selectGuru').value,
                mapel_id:       document.getElementById('selectMapel').value,
                kelas_id:       document.getElementById('selectKelas').value,
                hari:           document.getElementById('selectHari').value,
                waktu_mulai:    document.getElementById('inputWaktuMulai').value,
                waktu_berakhir: document.getElementById('inputWaktuBerakhir').value,
            })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';

            if (data.success) {
                modalTambah.hide();
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: data.message,
                    timer: 2000, showConfirmButton: false, background: '#0a2e1c', color: '#fff',
                }).then(function () {
                    var hariDipilih = document.getElementById('selectHari').value;
                    loadList(hariDipilih || activeHari, document.getElementById('searchInput').value.trim());
                });
            } else {
                showServerErrors(data, {
                    user_id:        'fbGuru',
                    mapel_id:       'fbMapel',
                    kelas_id:       'fbKelas',
                    hari:           'fbHari',
                    waktu_mulai:    'fbWaktuMulai',
                    waktu_berakhir: 'fbWaktuBerakhir',
                });
                if (!data.errors) {
                    Swal.fire({ icon:'error', title:'Gagal', text: data.message || 'Terjadi kesalahan.', background:'#0a2e1c', color:'#fff' });
                }
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';
            Swal.fire({ icon:'error', title:'Koneksi gagal', text:'Tidak dapat terhubung ke server.', background:'#0a2e1c', color:'#fff' });
        });
    });

    /* ============================================================
       MODAL EDIT
    ============================================================ */
    var modalEditEl = document.getElementById('modalEditJadwal');
    var modalEdit   = new bootstrap.Modal(modalEditEl);

    function openEditModal(id) {
        loadDropdown(function () {
            fetch('{{ url("admin/jadwal-mengajar") }}/' + id + '/edit', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                document.getElementById('editJadwalId').value           = d.id;
                document.getElementById('editSelectGuru').value         = d.user_id;
                document.getElementById('editSelectMapel').value        = d.mapel_id;
                document.getElementById('editSelectKelas').value        = d.kelas_id;
                document.getElementById('editSelectHari').value         = d.hari;
                document.getElementById('editInputWaktuMulai').value    = d.waktu_mulai;
                document.getElementById('editInputWaktuBerakhir').value = d.waktu_berakhir;

                ['editSelectGuru','editSelectMapel','editSelectKelas','editSelectHari',
                 'editInputWaktuMulai','editInputWaktuBerakhir'].forEach(function (elId) {
                    var el = document.getElementById(elId);
                    if (el) el.classList.remove('is-invalid');
                });
                ['fbEditGuru','fbEditMapel','fbEditKelas','fbEditHari',
                 'fbEditWaktuMulai','fbEditWaktuBerakhir'].forEach(clearFb);

                modalEdit.show();
            })
            .catch(function () {
                Swal.fire({ icon:'error', title:'Gagal', text:'Tidak dapat memuat data jadwal.', background:'#0a2e1c', color:'#fff' });
            });
        });
    }

    function validateEdit() {
        var ok = true;
        [
            { id: 'editSelectGuru',          fb: 'fbEditGuru',         msg: 'Pilih guru.' },
            { id: 'editSelectMapel',         fb: 'fbEditMapel',        msg: 'Pilih mata pelajaran.' },
            { id: 'editSelectKelas',         fb: 'fbEditKelas',        msg: 'Pilih kelas.' },
            { id: 'editSelectHari',          fb: 'fbEditHari',         msg: 'Pilih hari.' },
            { id: 'editInputWaktuMulai',     fb: 'fbEditWaktuMulai',   msg: 'Isi jam mulai.' },
            { id: 'editInputWaktuBerakhir',  fb: 'fbEditWaktuBerakhir',msg: 'Isi jam berakhir.' },
        ].forEach(function (f) {
            var el = document.getElementById(f.id);
            if (!el.value) { el.classList.add('is-invalid'); setFb(f.fb, f.msg); ok = false; }
            else           { el.classList.remove('is-invalid'); clearFb(f.fb); }
        });
        var mulai = document.getElementById('editInputWaktuMulai').value;
        var akhir = document.getElementById('editInputWaktuBerakhir').value;
        if (mulai && akhir && akhir <= mulai) {
            document.getElementById('editInputWaktuBerakhir').classList.add('is-invalid');
            setFb('fbEditWaktuBerakhir', 'Jam berakhir harus setelah jam mulai.');
            ok = false;
        }
        return ok;
    }

    document.getElementById('btnSimpanEditJadwal').addEventListener('click', function () {
        if (!validateEdit()) return;

        var id  = document.getElementById('editJadwalId').value;
        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-green" style="width:14px;height:14px;border-width:2px;margin-right:5px;"></span> Menyimpan...';

        fetch('{{ url("admin/jadwal-mengajar") }}/' + id, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' },
            body: JSON.stringify({
                user_id:        document.getElementById('editSelectGuru').value,
                mapel_id:       document.getElementById('editSelectMapel').value,
                kelas_id:       document.getElementById('editSelectKelas').value,
                hari:           document.getElementById('editSelectHari').value,
                waktu_mulai:    document.getElementById('editInputWaktuMulai').value,
                waktu_berakhir: document.getElementById('editInputWaktuBerakhir').value,
            })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';

            if (data.success) {
                modalEdit.hide();
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: data.message,
                    timer: 2000, showConfirmButton: false, background: '#0a2e1c', color: '#fff',
                }).then(function () { loadList(activeHari, document.getElementById('searchInput').value.trim()); });
            } else {
                showServerErrors(data, {
                    user_id:        'fbEditGuru',
                    mapel_id:       'fbEditMapel',
                    kelas_id:       'fbEditKelas',
                    hari:           'fbEditHari',
                    waktu_mulai:    'fbEditWaktuMulai',
                    waktu_berakhir: 'fbEditWaktuBerakhir',
                });
                if (!data.errors) {
                    Swal.fire({ icon:'error', title:'Gagal', text: data.message || 'Terjadi kesalahan.', background:'#0a2e1c', color:'#fff' });
                }
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';
            Swal.fire({ icon:'error', title:'Koneksi gagal', text:'Tidak dapat terhubung ke server.', background:'#0a2e1c', color:'#fff' });
        });
    });

    /* ============================================================
       HAPUS
    ============================================================ */
    function confirmHapus(id, label) {
        Swal.fire({
            icon: 'warning',
            title: 'Hapus jadwal?',
            html: 'Jadwal <strong>' + escHtml(label || '') + '</strong> akan dihapus permanen.',
            showCancelButton: true, confirmButtonText: 'Ya, hapus', cancelButtonText: 'Batal',
            confirmButtonColor: '#f87171', cancelButtonColor: 'rgba(255,255,255,0.1)',
            background: '#0a2e1c', color: '#fff',
        }).then(function (result) {
            if (!result.isConfirmed) return;

            fetch('{{ url("admin/jadwal-mengajar") }}/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    Swal.fire({ icon:'success', title:'Dihapus', text: data.message, timer:1800, showConfirmButton:false, background:'#0a2e1c', color:'#fff' })
                    .then(function () { loadList(activeHari, document.getElementById('searchInput').value.trim()); });
                } else {
                    Swal.fire({ icon:'error', title:'Gagal', text: data.message, background:'#0a2e1c', color:'#fff' });
                }
            });
        });
    }

    /* ============================================================
       UTIL
    ============================================================ */
    function showServerErrors(data, map) {
        if (!data.errors) return;
        Object.keys(map).forEach(function (field) {
            if (data.errors[field]) setFb(map[field], data.errors[field][0]);
        });
    }

    /* ============================================================
       INISIALISASI  –  tampilkan hari ini
    ============================================================ */
    loadList(hariHariIni(), '');

}());
</script>
@endpush
