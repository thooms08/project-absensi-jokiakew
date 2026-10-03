@extends('admin.layouts.app')

@section('title', 'Kelas')
@section('page-title', 'Kelas')

@push('styles')
<style>
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
    }

    .search-input::placeholder { color: rgba(255,255,255,0.3); }
    .search-input:focus {
        outline: none;
        border-color: rgba(74,222,128,0.45);
        background: rgba(255,255,255,0.08);
    }

    .btn-add-kelas {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: linear-gradient(135deg, #0a7c4a, #22c55e);
        border: none;
        border-radius: 10px;
        color: #ffffff;
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.55rem 1.15rem;
        cursor: pointer;
        transition: opacity 0.18s, transform 0.18s;
        white-space: nowrap;
        font-family: 'Inter', sans-serif;
    }

    .btn-add-kelas:hover { opacity: 0.88; transform: translateY(-1px); }

    .list-container {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(74,222,128,0.12);
        border-radius: 16px 0 16px 0;
        overflow: hidden;
    }

    /* Modal tema gelap */
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

    .modal-dark .modal-title { font-size: 1rem; font-weight: 700; color: #ffffff; }
    .modal-dark .modal-footer { border-top: 1px solid rgba(74,222,128,0.12); padding: 0.9rem 1.4rem; }
    .modal-dark .btn-close { filter: invert(1) brightness(0.7); }
    .modal-dark .modal-body { padding: 1.25rem 1.4rem; }

    .form-label-dark {
        font-size: 0.8rem;
        font-weight: 600;
        color: rgba(255,255,255,0.65);
        margin-bottom: 0.4rem;
        display: block;
    }

    .form-control-dark {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(74,222,128,0.18);
        border-radius: 10px;
        color: #e2f5ea;
        font-size: 0.875rem;
        padding: 0.6rem 0.9rem;
        width: 100%;
        transition: border-color 0.18s, background 0.18s;
        font-family: 'Inter', sans-serif;
    }

    .form-control-dark::placeholder { color: rgba(255,255,255,0.28); }
    .form-control-dark:focus {
        outline: none;
        border-color: rgba(74,222,128,0.5);
        background: rgba(255,255,255,0.09);
        box-shadow: 0 0 0 3px rgba(74,222,128,0.07);
    }

    .form-control-dark.is-invalid { border-color: rgba(248,113,113,0.6); }

    .field-feedback {
        font-size: 0.75rem;
        margin-top: 0.3rem;
        min-height: 1rem;
        color: #f87171;
    }

    /* Wali kelas picker */
    .wali-display {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(74,222,128,0.18);
        border-radius: 10px;
        padding: 0.55rem 0.85rem;
        min-height: 42px;
        cursor: pointer;
        transition: border-color 0.18s;
    }

    .wali-display:hover { border-color: rgba(74,222,128,0.4); }

    .wali-display .wali-name {
        flex: 1;
        font-size: 0.875rem;
        color: rgba(255,255,255,0.55);
    }

    .wali-display .wali-name.selected { color: #e2f5ea; }

    .wali-display .btn-clear-wali {
        background: none;
        border: none;
        color: rgba(248,113,113,0.7);
        cursor: pointer;
        padding: 0;
        line-height: 1;
        font-size: 0.9rem;
        display: none;
    }

    .wali-display.has-wali .btn-clear-wali { display: inline-flex; }

    /* Daftar guru di modal pilih wali */
    .guru-pick-list {
        max-height: 280px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: rgba(74,222,128,0.2) transparent;
    }

    .guru-pick-list::-webkit-scrollbar { width: 4px; }
    .guru-pick-list::-webkit-scrollbar-thumb { background: rgba(74,222,128,0.2); border-radius: 2px; }

    .guru-pick-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.65rem 0.1rem;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        cursor: pointer;
    }

    .guru-pick-item:last-child { border-bottom: none; }
    .guru-pick-item:hover { background: rgba(74,222,128,0.05); border-radius: 8px; }

    .guru-pick-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0a7c4a, #4ade80);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.78rem;
        font-weight: 700;
        color: #064e2e;
        flex-shrink: 0;
    }

    .guru-pick-name { font-size: 0.85rem; font-weight: 600; color: #fff; }
    .guru-pick-username { font-size: 0.72rem; color: rgba(74,222,128,0.65); }

    .btn-pilih {
        margin-left: auto;
        background: rgba(74,222,128,0.12);
        border: 1px solid rgba(74,222,128,0.22);
        border-radius: 8px;
        color: #4ade80;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.3rem 0.7rem;
        cursor: pointer;
        transition: background 0.15s;
        white-space: nowrap;
        font-family: 'Inter', sans-serif;
    }

    .btn-pilih:hover { background: rgba(74,222,128,0.22); }

    .pick-search {
        width: 100%;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(74,222,128,0.18);
        border-radius: 8px;
        color: #e2f5ea;
        font-size: 0.85rem;
        padding: 0.5rem 0.85rem;
        margin-bottom: 0.75rem;
        font-family: 'Inter', sans-serif;
        outline: none;
        transition: border-color 0.18s;
    }

    .pick-search:focus { border-color: rgba(74,222,128,0.45); }
    .pick-search::placeholder { color: rgba(255,255,255,0.3); }

    .pick-empty {
        text-align: center;
        padding: 1.25rem;
        color: rgba(255,255,255,0.3);
        font-size: 0.82rem;
    }

    .btn-modal-primary {
        background: linear-gradient(135deg, #0a7c4a, #22c55e);
        border: none; border-radius: 10px; color: #fff;
        font-size: 0.875rem; font-weight: 600; padding: 0.55rem 1.35rem;
        cursor: pointer; transition: opacity 0.18s; font-family: 'Inter', sans-serif;
    }

    .btn-modal-primary:hover { opacity: 0.87; }
    .btn-modal-primary:disabled { opacity: 0.5; cursor: not-allowed; }

    .btn-modal-secondary {
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 10px; color: rgba(255,255,255,0.72);
        font-size: 0.875rem; font-weight: 500; padding: 0.55rem 1.2rem;
        cursor: pointer; transition: background 0.18s; font-family: 'Inter', sans-serif;
    }

    .btn-modal-secondary:hover { background: rgba(255,255,255,0.12); }

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
        display: inline-block; vertical-align: middle; margin-right: 0.5rem;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    @media (max-width: 575.98px) {
        .page-header { flex-direction: column; align-items: flex-start; }
        .search-wrap { max-width: 100%; }
        .modal-dialog { margin: 0.5rem; }
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h2>Kelas</h2>
        <p>Kelola data kelas dan anggotanya</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" id="searchInput" class="search-input"
                   placeholder="Cari nama kelas..." autocomplete="off">
        </div>
        <button class="btn-add-kelas" id="btnTambahKelas">
            <i class="bi bi-plus-lg"></i> Kelas
        </button>
    </div>
</div>

<div class="list-container" id="listContainer">
    <div class="list-loading">
        <span class="spinner-green"></span> Memuat data...
    </div>
</div>

{{-- ================================================================
     MODAL TAMBAH KELAS
================================================================ --}}
<div class="modal fade modal-dark" id="modalTambahKelas"
     tabindex="-1" aria-labelledby="modalTambahKelasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahKelasLabel">
                    <i class="bi bi-door-open-fill me-2" style="color:#4ade80;"></i>
                    Tambah Kelas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form id="formTambahKelas" novalidate>
                    @csrf
                    <input type="hidden" id="tWaliId" name="wali_id">

                    <div class="mb-3">
                        <label class="form-label-dark" for="tNamaKelas">
                            Nama Kelas <span style="color:#f87171;">*</span>
                        </label>
                        <input type="text" id="tNamaKelas" name="nama_kelas"
                               class="form-control-dark" placeholder="Contoh: X RPL 1"
                               maxlength="100" required autocomplete="off">
                        <div class="field-feedback" id="tFbNamaKelas"></div>
                    </div>

                    <div class="mb-1">
                        <label class="form-label-dark">Wali Kelas (opsional)</label>
                        <div class="wali-display" id="tWaliDisplay"
                             onclick="bukaPickWali('tambah')" role="button" tabindex="0"
                             aria-label="Pilih wali kelas">
                            <i class="bi bi-person-fill" style="color:rgba(74,222,128,0.5);"></i>
                            <span class="wali-name" id="tWaliNama">Klik untuk memilih wali kelas</span>
                            <button type="button" class="btn-clear-wali"
                                    id="tBtnClearWali" aria-label="Hapus pilihan wali">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-modal-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-modal-primary" id="btnSimpanKelas">
                    <i class="bi bi-check-lg me-1"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================
     MODAL EDIT KELAS
================================================================ --}}
<div class="modal fade modal-dark" id="modalEditKelas"
     tabindex="-1" aria-labelledby="modalEditKelasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditKelasLabel">
                    <i class="bi bi-pencil-fill me-2" style="color:#4ade80;"></i>
                    Edit Kelas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form id="formEditKelas" novalidate>
                    @csrf
                    <input type="hidden" id="eKelasId">
                    <input type="hidden" id="eWaliId" name="wali_id">

                    <div class="mb-3">
                        <label class="form-label-dark" for="eNamaKelas">
                            Nama Kelas <span style="color:#f87171;">*</span>
                        </label>
                        <input type="text" id="eNamaKelas" name="nama_kelas"
                               class="form-control-dark" placeholder="Nama kelas"
                               maxlength="100" required autocomplete="off">
                        <div class="field-feedback" id="eFbNamaKelas"></div>
                    </div>

                    <div class="mb-1">
                        <label class="form-label-dark">Wali Kelas (opsional)</label>
                        <div class="wali-display" id="eWaliDisplay"
                             onclick="bukaPickWali('edit')" role="button" tabindex="0"
                             aria-label="Pilih wali kelas">
                            <i class="bi bi-person-fill" style="color:rgba(74,222,128,0.5);"></i>
                            <span class="wali-name" id="eWaliNama">Klik untuk memilih wali kelas</span>
                            <button type="button" class="btn-clear-wali"
                                    id="eBtnClearWali" aria-label="Hapus pilihan wali">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-modal-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-modal-primary" id="btnSimpanEditKelas">
                    <i class="bi bi-check-lg me-1"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================
     MODAL PILIH WALI KELAS
================================================================ --}}
<div class="modal fade modal-dark" id="modalPickWali"
     tabindex="-1" aria-labelledby="modalPickWaliLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPickWaliLabel">
                    <i class="bi bi-person-check-fill me-2" style="color:#4ade80;"></i>
                    Pilih Wali Kelas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="pickWaliSearch" class="pick-search"
                       placeholder="Cari nama guru..." autocomplete="off">
                <div class="guru-pick-list" id="pickWaliList">
                    <div class="pick-empty">Memuat...</div>
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

    var CSRF        = (document.querySelector('meta[name="csrf-token"]') || {}).content || '{{ csrf_token() }}';
    var searchTimer = null;
    var searchInput = document.getElementById('searchInput');

    /* ============================================================
       LOAD LIST
    ============================================================ */
    function loadList(search) {
        search = search || '';
        var c = document.getElementById('listContainer');
        c.innerHTML = '<div class="list-loading"><span class="spinner-green"></span> Memuat data...</div>';

        fetch('{{ route("admin.kelas.list") }}?search=' + encodeURIComponent(search), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.text(); })
        .then(function (html) { c.innerHTML = html; bindListEvents(); })
        .catch(function () {
            c.innerHTML = '<div class="list-loading" style="color:#f87171;">Gagal memuat data.</div>';
        });
    }

    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);
        var val = this.value.trim();
        searchTimer = setTimeout(function () { loadList(val); }, 350);
    });

    function bindListEvents() {
        var c = document.getElementById('listContainer');

        c.querySelectorAll('[data-edit-id]').forEach(function (btn) {
            btn.addEventListener('click', function () { openEditModal(this.dataset.editId); });
        });

        c.querySelectorAll('[data-delete-id]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                confirmHapus(this.dataset.deleteId, this.dataset.deleteNama);
            });
        });
    }

    /* ============================================================
       MODAL PILIH WALI
    ============================================================ */
    var pickWaliModalEl  = document.getElementById('modalPickWali');
    var pickWaliModal    = new bootstrap.Modal(pickWaliModalEl);
    var pickWaliContext  = 'tambah'; /* 'tambah' | 'edit' */
    var pickWaliTimer    = null;

    window.bukaPickWali = function (ctx) {
        pickWaliContext = ctx;
        document.getElementById('pickWaliSearch').value = '';
        loadPickWaliList('');
        pickWaliModal.show();
    };

    document.getElementById('pickWaliSearch').addEventListener('input', function () {
        clearTimeout(pickWaliTimer);
        var val = this.value.trim();
        pickWaliTimer = setTimeout(function () { loadPickWaliList(val); }, 350);
    });

    function loadPickWaliList(search) {
        var listEl = document.getElementById('pickWaliList');
        listEl.innerHTML = '<div class="pick-empty">Memuat...</div>';

        fetch('{{ route("admin.kelas.semua_guru") }}?search=' + encodeURIComponent(search), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            var list = data.data || [];
            if (!list.length) {
                listEl.innerHTML = '<div class="pick-empty">Tidak ada guru ditemukan.</div>';
                return;
            }
            var html = '';
            list.forEach(function (g) {
                html += '<div class="guru-pick-item">'
                    + '<div class="guru-pick-avatar">' + escHtml(g.name.charAt(0).toUpperCase()) + '</div>'
                    + '<div style="flex:1;min-width:0;">'
                    +   '<div class="guru-pick-name text-truncate">' + escHtml(g.name) + '</div>'
                    +   '<div class="guru-pick-username">@' + escHtml(g.username) + '</div>'
                    + '</div>'
                    + '<button type="button" class="btn-pilih" data-id="' + g.id + '" data-name="' + escHtml(g.name) + '">Pilih</button>'
                    + '</div>';
            });
            listEl.innerHTML = html;

            listEl.querySelectorAll('.btn-pilih').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    setWali(pickWaliContext, this.dataset.id, this.dataset.name);
                    pickWaliModal.hide();
                });
            });
        });
    }

    function setWali(ctx, id, name) {
        if (ctx === 'tambah') {
            document.getElementById('tWaliId').value = id;
            var span  = document.getElementById('tWaliNama');
            var disp  = document.getElementById('tWaliDisplay');
            span.textContent = name;
            span.classList.add('selected');
            disp.classList.add('has-wali');
        } else {
            document.getElementById('eWaliId').value = id;
            var span  = document.getElementById('eWaliNama');
            var disp  = document.getElementById('eWaliDisplay');
            span.textContent = name;
            span.classList.add('selected');
            disp.classList.add('has-wali');
        }
    }

    /* Clear wali */
    document.getElementById('tBtnClearWali').addEventListener('click', function (e) {
        e.stopPropagation();
        clearWali('tambah');
    });

    document.getElementById('eBtnClearWali').addEventListener('click', function (e) {
        e.stopPropagation();
        clearWali('edit');
    });

    function clearWali(ctx) {
        if (ctx === 'tambah') {
            document.getElementById('tWaliId').value = '';
            document.getElementById('tWaliNama').textContent = 'Klik untuk memilih wali kelas';
            document.getElementById('tWaliNama').classList.remove('selected');
            document.getElementById('tWaliDisplay').classList.remove('has-wali');
        } else {
            document.getElementById('eWaliId').value = '';
            document.getElementById('eWaliNama').textContent = 'Klik untuk memilih wali kelas';
            document.getElementById('eWaliNama').classList.remove('selected');
            document.getElementById('eWaliDisplay').classList.remove('has-wali');
        }
    }

    /* Saat modal pick wali ditutup, kembalikan fokus ke modal parent */
    pickWaliModalEl.addEventListener('hidden.bs.modal', function () {
        if (pickWaliContext === 'tambah') {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalTambahKelas')).show();
        } else {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditKelas')).show();
        }
    });

    /* Sembunyikan modal parent sebelum buka pick wali */
    document.getElementById('modalTambahKelas').addEventListener('hide.bs.modal', function (e) {
        if (document.getElementById('modalPickWali').classList.contains('show')) {
            e.preventDefault();
        }
    });

    document.getElementById('modalEditKelas').addEventListener('hide.bs.modal', function (e) {
        if (document.getElementById('modalPickWali').classList.contains('show')) {
            e.preventDefault();
        }
    });

    /* ============================================================
       MODAL TAMBAH
    ============================================================ */
    var modalTambahEl = document.getElementById('modalTambahKelas');
    var modalTambah   = new bootstrap.Modal(modalTambahEl);

    document.getElementById('btnTambahKelas').addEventListener('click', function () {
        document.getElementById('formTambahKelas').reset();
        document.getElementById('tNamaKelas').classList.remove('is-invalid');
        document.getElementById('tFbNamaKelas').textContent = '';
        clearWali('tambah');
        modalTambah.show();
        setTimeout(function () { document.getElementById('tNamaKelas').focus(); }, 350);
    });

    document.getElementById('btnSimpanKelas').addEventListener('click', function () {
        var nama  = document.getElementById('tNamaKelas').value.trim();
        var wali  = document.getElementById('tWaliId').value;
        var inpEl = document.getElementById('tNamaKelas');
        var fbEl  = document.getElementById('tFbNamaKelas');

        if (!nama) {
            inpEl.classList.add('is-invalid'); fbEl.textContent = 'Nama kelas wajib diisi.';
            inpEl.focus(); return;
        }
        inpEl.classList.remove('is-invalid'); fbEl.textContent = '';

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-green" style="width:14px;height:14px;border-width:2px;margin-right:4px;"></span> Menyimpan...';

        fetch('{{ route("admin.kelas.store") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' },
            body: JSON.stringify({ nama_kelas: nama, wali_id: wali || null })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';
            if (data.success) {
                modalTambah.hide();
                Swal.fire({ icon:'success', title:'Berhasil!', text: data.message,
                    confirmButtonColor:'#22c55e', background:'#0a2e1c', color:'#fff',
                    timer:2000, showConfirmButton:false
                }).then(function () { loadList(searchInput.value.trim()); });
            } else {
                var msgs = [];
                if (data.errors) Object.values(data.errors).forEach(function (a) { msgs = msgs.concat(a); });
                inpEl.classList.add('is-invalid');
                fbEl.textContent = msgs.length ? msgs[0] : (data.message || 'Terjadi kesalahan.');
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';
            Swal.fire({ icon:'error', title:'Koneksi gagal', text:'Tidak dapat terhubung ke server.',
                confirmButtonColor:'#22c55e', background:'#0a2e1c', color:'#fff' });
        });
    });

    /* ============================================================
       MODAL EDIT
    ============================================================ */
    var modalEditEl = document.getElementById('modalEditKelas');
    var modalEdit   = new bootstrap.Modal(modalEditEl);

    function openEditModal(id) {
        fetch('{{ url("admin/kelas") }}/' + id + '/edit', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            document.getElementById('eKelasId').value    = data.id;
            document.getElementById('eNamaKelas').value  = data.nama_kelas;
            document.getElementById('eNamaKelas').classList.remove('is-invalid');
            document.getElementById('eFbNamaKelas').textContent = '';

            if (data.wali_id) {
                setWali('edit', data.wali_id, data.wali_nama);
            } else {
                clearWali('edit');
            }
            modalEdit.show();
        })
        .catch(function () {
            Swal.fire({ icon:'error', title:'Gagal', text:'Tidak dapat memuat data.',
                background:'#0a2e1c', color:'#fff' });
        });
    }

    document.getElementById('btnSimpanEditKelas').addEventListener('click', function () {
        var id    = document.getElementById('eKelasId').value;
        var nama  = document.getElementById('eNamaKelas').value.trim();
        var wali  = document.getElementById('eWaliId').value;
        var inpEl = document.getElementById('eNamaKelas');
        var fbEl  = document.getElementById('eFbNamaKelas');

        if (!nama) {
            inpEl.classList.add('is-invalid'); fbEl.textContent = 'Nama kelas wajib diisi.';
            inpEl.focus(); return;
        }
        inpEl.classList.remove('is-invalid'); fbEl.textContent = '';

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-green" style="width:14px;height:14px;border-width:2px;margin-right:4px;"></span> Menyimpan...';

        fetch('{{ url("admin/kelas") }}/' + id, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' },
            body: JSON.stringify({ nama_kelas: nama, wali_id: wali || null })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';
            if (data.success) {
                modalEdit.hide();
                Swal.fire({ icon:'success', title:'Berhasil!', text: data.message,
                    confirmButtonColor:'#22c55e', background:'#0a2e1c', color:'#fff',
                    timer:2000, showConfirmButton:false
                }).then(function () { loadList(searchInput.value.trim()); });
            } else {
                var msgs = [];
                if (data.errors) Object.values(data.errors).forEach(function (a) { msgs = msgs.concat(a); });
                inpEl.classList.add('is-invalid');
                fbEl.textContent = msgs.length ? msgs[0] : (data.message || 'Terjadi kesalahan.');
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';
            Swal.fire({ icon:'error', title:'Koneksi gagal', text:'Tidak dapat terhubung ke server.',
                background:'#0a2e1c', color:'#fff' });
        });
    });

    /* ============================================================
       HAPUS
    ============================================================ */
    function confirmHapus(id, nama) {
        Swal.fire({
            icon: 'warning',
            title: 'Hapus kelas?',
            html: 'Kelas <strong>' + escHtml(nama) + '</strong> dan semua anggotanya akan dihapus.',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f87171',
            cancelButtonColor: 'rgba(255,255,255,0.1)',
            background: '#0a2e1c',
            color: '#ffffff',
        }).then(function (result) {
            if (!result.isConfirmed) return;
            fetch('{{ url("admin/kelas") }}/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    Swal.fire({ icon:'success', title:'Dihapus', text: data.message,
                        timer:1800, showConfirmButton:false, background:'#0a2e1c', color:'#fff'
                    }).then(function () { loadList(searchInput.value.trim()); });
                } else {
                    Swal.fire({ icon:'error', title:'Gagal', text: data.message,
                        background:'#0a2e1c', color:'#fff' });
                }
            });
        });
    }

    /* ============================================================
       UTIL
    ============================================================ */
    function escHtml(str) {
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;')
                          .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    /* ============================================================
       INIT
    ============================================================ */
    loadList('');

}());
</script>
@endpush
