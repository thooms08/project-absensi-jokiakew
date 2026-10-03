@extends('admin.layouts.app')

@section('title', 'Mata Pelajaran')
@section('page-title', 'Mata Pelajaran')

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

    /* Search */
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

    /* Tombol tambah */
    .btn-add-mapel {
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

    .btn-add-mapel:hover { opacity: 0.88; transform: translateY(-1px); }

    /* Container list */
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

    .modal-dark .modal-title {
        font-size: 1rem;
        font-weight: 700;
        color: #ffffff;
    }

    .modal-dark .modal-footer {
        border-top: 1px solid rgba(74,222,128,0.12);
        padding: 0.9rem 1.4rem;
    }

    .modal-dark .btn-close { filter: invert(1) brightness(0.7); }
    .modal-dark .modal-body { padding: 1.25rem 1.4rem; }

    /* Form */
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

    /* Tombol modal */
    .btn-modal-primary {
        background: linear-gradient(135deg, #0a7c4a, #22c55e);
        border: none;
        border-radius: 10px;
        color: #fff;
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.55rem 1.35rem;
        cursor: pointer;
        transition: opacity 0.18s;
        font-family: 'Inter', sans-serif;
    }

    .btn-modal-primary:hover { opacity: 0.87; }
    .btn-modal-primary:disabled { opacity: 0.5; cursor: not-allowed; }

    .btn-modal-secondary {
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 10px;
        color: rgba(255,255,255,0.72);
        font-size: 0.875rem;
        font-weight: 500;
        padding: 0.55rem 1.2rem;
        cursor: pointer;
        transition: background 0.18s;
        font-family: 'Inter', sans-serif;
    }

    .btn-modal-secondary:hover { background: rgba(255,255,255,0.12); }

    /* Spinner */
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
        .modal-dialog { margin: 0.5rem; }
    }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <div class="page-header-left">
        <h2>Mata Pelajaran</h2>
        <p>Kelola daftar mata pelajaran sekolah</p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input
                type="text"
                id="searchInput"
                class="search-input"
                placeholder="Cari mata pelajaran..."
                autocomplete="off"
            >
        </div>

        <button class="btn-add-mapel" id="btnTambahMapel">
            <i class="bi bi-plus-lg"></i>
            Mapel
        </button>
    </div>
</div>

{{-- LIST CONTAINER --}}
<div class="list-container" id="listContainer">
    <div class="list-loading">
        <span class="spinner-green"></span> Memuat data...
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade modal-dark" id="modalTambahMapel"
     tabindex="-1" aria-labelledby="modalTambahMapelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahMapelLabel">
                    <i class="bi bi-book-fill me-2" style="color:#4ade80;"></i>
                    Tambah Mata Pelajaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <form id="formTambahMapel" novalidate>
                    @csrf
                    <div>
                        <label class="form-label-dark" for="inputNamaMapel">
                            Nama Mata Pelajaran <span style="color:#f87171;">*</span>
                        </label>
                        <input
                            type="text"
                            id="inputNamaMapel"
                            name="nama_mapel"
                            class="form-control-dark"
                            placeholder="Contoh: Matematika"
                            maxlength="255"
                            required
                            autocomplete="off"
                        >
                        <div class="field-feedback" id="feedbackNamaMapel"></div>
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-modal-secondary"
                        data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-modal-primary" id="btnSimpanMapel">
                    <i class="bi bi-check-lg me-1"></i> Simpan
                </button>
            </div>

        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade modal-dark" id="modalEditMapel"
     tabindex="-1" aria-labelledby="modalEditMapelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalEditMapelLabel">
                    <i class="bi bi-pencil-fill me-2" style="color:#4ade80;"></i>
                    Edit Mata Pelajaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <form id="formEditMapel" novalidate>
                    @csrf
                    <input type="hidden" id="editMapelId">
                    <div>
                        <label class="form-label-dark" for="editNamaMapel">
                            Nama Mata Pelajaran <span style="color:#f87171;">*</span>
                        </label>
                        <input
                            type="text"
                            id="editNamaMapel"
                            name="nama_mapel"
                            class="form-control-dark"
                            placeholder="Nama mata pelajaran"
                            maxlength="255"
                            required
                            autocomplete="off"
                        >
                        <div class="field-feedback" id="feedbackEditNamaMapel"></div>
                    </div>
                </form>
            </div>

            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-modal-secondary"
                        data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-modal-primary" id="btnSimpanEditMapel">
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

    var CSRF = (document.querySelector('meta[name="csrf-token"]') || {}).content
               || '{{ csrf_token() }}';

    var searchTimer = null;
    var searchInput = document.getElementById('searchInput');

    /* ============================================================
       LOAD LIST
    ============================================================ */
    function loadList(search) {
        search = search || '';
        var container = document.getElementById('listContainer');
        container.innerHTML =
            '<div class="list-loading"><span class="spinner-green"></span> Memuat data...</div>';

        fetch('{{ route("admin.mapel.list") }}?search=' + encodeURIComponent(search), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.text(); })
        .then(function (html) {
            container.innerHTML = html;
            bindListEvents();
        })
        .catch(function () {
            container.innerHTML =
                '<div class="list-loading" style="color:#f87171;">'
                + 'Gagal memuat data. Silakan muat ulang halaman.</div>';
        });
    }

    /* ============================================================
       SEARCH DEBOUNCE
    ============================================================ */
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);
        var val = this.value.trim();
        searchTimer = setTimeout(function () { loadList(val); }, 350);
    });

    /* ============================================================
       BIND EVENTS LIST
    ============================================================ */
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
       MODAL TAMBAH
    ============================================================ */
    var modalTambahEl = document.getElementById('modalTambahMapel');
    var modalTambah   = new bootstrap.Modal(modalTambahEl);

    document.getElementById('btnTambahMapel').addEventListener('click', function () {
        document.getElementById('formTambahMapel').reset();
        document.getElementById('inputNamaMapel').classList.remove('is-invalid');
        document.getElementById('feedbackNamaMapel').textContent = '';
        modalTambah.show();
        setTimeout(function () {
            document.getElementById('inputNamaMapel').focus();
        }, 350);
    });

    /* Submit tambah */
    document.getElementById('btnSimpanMapel').addEventListener('click', function () {
        var nama = document.getElementById('inputNamaMapel').value.trim();
        var fbEl = document.getElementById('feedbackNamaMapel');
        var inpEl = document.getElementById('inputNamaMapel');

        if (!nama) {
            inpEl.classList.add('is-invalid');
            fbEl.textContent = 'Nama mata pelajaran wajib diisi.';
            inpEl.focus();
            return;
        }

        inpEl.classList.remove('is-invalid');
        fbEl.textContent = '';

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-green" style="width:14px;height:14px;border-width:2px;margin-right:5px;"></span> Menyimpan...';

        fetch('{{ route("admin.mapel.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN':     CSRF,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type':     'application/json',
            },
            body: JSON.stringify({ nama_mapel: nama })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';

            if (data.success) {
                modalTambah.hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    confirmButtonColor: '#22c55e',
                    background: '#0a2e1c',
                    color: '#ffffff',
                    timer: 2000,
                    showConfirmButton: false,
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

    /* Enter di input tambah */
    document.getElementById('inputNamaMapel').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); document.getElementById('btnSimpanMapel').click(); }
    });

    /* ============================================================
       MODAL EDIT
    ============================================================ */
    var modalEditEl = document.getElementById('modalEditMapel');
    var modalEdit   = new bootstrap.Modal(modalEditEl);

    function openEditModal(id) {
        fetch('{{ url("admin/mapel") }}/' + id + '/edit', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            document.getElementById('editMapelId').value     = data.id;
            document.getElementById('editNamaMapel').value   = data.nama_mapel;
            document.getElementById('editNamaMapel').classList.remove('is-invalid');
            document.getElementById('feedbackEditNamaMapel').textContent = '';
            modalEdit.show();
            setTimeout(function () { document.getElementById('editNamaMapel').focus(); }, 350);
        })
        .catch(function () {
            Swal.fire({ icon:'error', title:'Gagal', text:'Tidak dapat memuat data.',
                        background:'#0a2e1c', color:'#fff' });
        });
    }

    /* Submit edit */
    document.getElementById('btnSimpanEditMapel').addEventListener('click', function () {
        var id    = document.getElementById('editMapelId').value;
        var nama  = document.getElementById('editNamaMapel').value.trim();
        var fbEl  = document.getElementById('feedbackEditNamaMapel');
        var inpEl = document.getElementById('editNamaMapel');

        if (!nama) {
            inpEl.classList.add('is-invalid');
            fbEl.textContent = 'Nama mata pelajaran wajib diisi.';
            inpEl.focus();
            return;
        }

        inpEl.classList.remove('is-invalid');
        fbEl.textContent = '';

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-green" style="width:14px;height:14px;border-width:2px;margin-right:5px;"></span> Menyimpan...';

        fetch('{{ url("admin/mapel") }}/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN':     CSRF,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type':     'application/json',
            },
            body: JSON.stringify({ nama_mapel: nama })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';

            if (data.success) {
                modalEdit.hide();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    confirmButtonColor: '#22c55e',
                    background: '#0a2e1c',
                    color: '#ffffff',
                    timer: 2000,
                    showConfirmButton: false,
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

    /* Enter di input edit */
    document.getElementById('editNamaMapel').addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); document.getElementById('btnSimpanEditMapel').click(); }
    });

    /* ============================================================
       HAPUS
    ============================================================ */
    function confirmHapus(id, nama) {
        Swal.fire({
            icon: 'warning',
            title: 'Hapus mata pelajaran?',
            html: 'Mata pelajaran <strong>' + escHtml(nama) + '</strong> akan dihapus permanen.',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f87171',
            cancelButtonColor: 'rgba(255,255,255,0.1)',
            background: '#0a2e1c',
            color: '#ffffff',
        }).then(function (result) {
            if (!result.isConfirmed) return;

            fetch('{{ url("admin/mapel") }}/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN':     CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type':     'application/json',
                }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    Swal.fire({
                        icon: 'success', title: 'Dihapus', text: data.message,
                        timer: 1800, showConfirmButton: false,
                        background: '#0a2e1c', color: '#ffffff',
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
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    /* ============================================================
       INIT
    ============================================================ */
    loadList('');

}());
</script>
@endpush
