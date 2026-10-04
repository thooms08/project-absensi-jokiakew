@extends('admin.layouts.app')

@section('title', 'Data Guru')
@section('page-title', 'Data Guru')

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
       SEARCH BAR
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
    }

    .search-input::placeholder { color: rgba(255,255,255,0.3); }

    .search-input:focus {
        outline: none;
        border-color: rgba(74,222,128,0.45);
        background: rgba(255,255,255,0.08);
    }

    /* ============================================================
       TOMBOL BAGIKAN
    ============================================================ */
    .btn-share-guru {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        background: rgba(74,222,128,0.1);
        border: 1px solid rgba(74,222,128,0.28);
        border-radius: 10px;
        color: #4ade80;
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        cursor: pointer;
        transition: background 0.18s, transform 0.18s;
        white-space: nowrap;
        font-family: 'Inter', sans-serif;
    }

    .btn-share-guru:hover {
        background: rgba(74,222,128,0.18);
        transform: translateY(-1px);
    }

    @media (max-width: 399.98px) {
        .btn-share-label { display: none; }
    }

    /* ============================================================
       TOMBOL TAMBAH
    ============================================================ */
    .btn-add-guru {
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

    .btn-add-guru:hover {
        opacity: 0.88;
        transform: translateY(-1px);
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

    /* ============================================================
       MODAL OVERRIDE  (tema gelap)
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

    .modal-dark .modal-title {
        font-size: 1rem;
        font-weight: 700;
        color: #ffffff;
    }

    .modal-dark .modal-footer {
        border-top: 1px solid rgba(74,222,128,0.12);
        padding: 0.9rem 1.4rem;
    }

    .modal-dark .btn-close {
        filter: invert(1) brightness(0.7);
    }

    .modal-dark .modal-body {
        padding: 1.25rem 1.4rem;
    }

    /* ============================================================
       STEP INDICATOR
    ============================================================ */
    .step-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        margin-bottom: 1.6rem;
    }

    .step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.35rem;
        position: relative;
        flex: 1;
    }

    .step-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 700;
        border: 2px solid rgba(74,222,128,0.2);
        background: rgba(255,255,255,0.05);
        color: rgba(255,255,255,0.35);
        transition: all 0.25s;
        z-index: 1;
        position: relative;
    }

    .step-circle.active {
        border-color: #4ade80;
        background: rgba(74,222,128,0.15);
        color: #4ade80;
    }

    .step-circle.done {
        border-color: #22c55e;
        background: #22c55e;
        color: #064e2e;
    }

    .step-label {
        font-size: 0.68rem;
        font-weight: 600;
        color: rgba(255,255,255,0.35);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        text-align: center;
        white-space: nowrap;
    }

    .step-label.active { color: #4ade80; }
    .step-label.done   { color: #22c55e; }

    .step-line {
        flex: 1;
        height: 2px;
        background: rgba(74,222,128,0.12);
        margin-bottom: 1.35rem;
        transition: background 0.25s;
    }

    .step-line.done { background: #22c55e; }

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

    .form-control-dark {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(74,222,128,0.18);
        border-radius: 10px;
        color: #e2f5ea;
        font-size: 0.875rem;
        padding: 0.55rem 0.85rem;
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

    .form-control-dark.is-invalid {
        border-color: rgba(248,113,113,0.6);
    }

    .form-control-dark.is-valid {
        border-color: rgba(74,222,128,0.6);
    }

    /* ============================================================
       FOTO UPLOAD AREA
    ============================================================ */
    .foto-upload-area {
        border: 2px dashed rgba(74,222,128,0.22);
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.18s, background 0.18s;
        position: relative;
        overflow: hidden;
    }

    .foto-upload-area:hover {
        border-color: rgba(74,222,128,0.45);
        background: rgba(74,222,128,0.04);
    }

    .foto-upload-icon {
        font-size: 2rem;
        color: rgba(74,222,128,0.45);
        margin-bottom: 0.5rem;
        display: block;
    }

    .foto-upload-text {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.45);
        margin: 0;
    }

    .foto-upload-text span {
        color: #4ade80;
        font-weight: 600;
    }

    /* Preview */
    .foto-preview-wrap {
        position: relative;
        display: inline-block;
        margin-top: 0.75rem;
    }

    .foto-preview-img {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(74,222,128,0.35);
    }

    .foto-preview-remove {
        position: absolute;
        top: -4px;
        right: -4px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #f87171;
        border: none;
        color: #fff;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        line-height: 1;
        transition: background 0.15s;
    }

    .foto-preview-remove:hover { background: #ef4444; }

    /* ============================================================
       FEEDBACK TEKS
    ============================================================ */
    .field-feedback {
        font-size: 0.75rem;
        margin-top: 0.3rem;
        min-height: 1rem;
    }

    .field-feedback.valid   { color: #4ade80; }
    .field-feedback.invalid { color: #f87171; }
    .field-feedback.warning { color: #fbbf24; }
    .field-feedback.muted   { color: rgba(255,255,255,0.38); }

    /* ============================================================
       TOMBOL MODAL
    ============================================================ */
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

    /* ============================================================
       PASSWORD STRENGTH
    ============================================================ */
    .pwd-strength-bar {
        height: 4px;
        border-radius: 3px;
        background: rgba(255,255,255,0.08);
        margin-top: 0.45rem;
        overflow: hidden;
    }

    .pwd-strength-fill {
        height: 100%;
        border-radius: 3px;
        width: 0;
        transition: width 0.3s, background 0.3s;
    }

    /* ============================================================
       LOADING SPINNER (list)
    ============================================================ */
    .list-loading {
        text-align: center;
        padding: 2.5rem 1rem;
        color: rgba(255,255,255,0.35);
        font-size: 0.875rem;
    }

    .spinner-green {
        width: 28px;
        height: 28px;
        border: 3px solid rgba(74,222,128,0.15);
        border-top-color: #4ade80;
        border-radius: 50%;
        animation: spin 0.65s linear infinite;
        display: inline-block;
        vertical-align: middle;
        margin-right: 0.5rem;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 575.98px) {
        .page-header { flex-direction: column; align-items: flex-start; }
        .search-wrap { max-width: 100%; }
        .modal-dialog { margin: 0.5rem; }
    }

    /* ============================================================
       MAPEL SECTION (dalam modal tambah/edit)
    ============================================================ */
    .mapel-section-wrap {
        margin-top: 1rem;
        background: rgba(251,191,36,0.04);
        border: 1px solid rgba(251,191,36,0.15);
        border-radius: 12px;
        padding: 0.9rem 1rem;
    }

    .mapel-section-header {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #fbbf24;
        margin-bottom: 0.65rem;
    }

    .mapel-section-hint {
        font-size: 0.72rem;
        font-weight: 400;
        color: rgba(255,255,255,0.3);
        margin-left: 0.1rem;
    }

    /* Multi-select mapel */
    .mapel-select-wrap {
        position: relative;
    }

    .select-mapel {
        width: 100%;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(251,191,36,0.22);
        border-radius: 9px;
        color: #e2f5ea;
        font-size: 0.845rem;
        font-family: 'Inter', sans-serif;
        padding: 0.3rem 0.4rem;
        outline: none;
        cursor: pointer;
        transition: border-color 0.18s;
        /* Tampilkan maks 5 item agar tidak terlalu panjang */
        min-height: 36px;
    }

    .select-mapel:focus {
        border-color: rgba(251,191,36,0.5);
        box-shadow: 0 0 0 3px rgba(251,191,36,0.07);
    }

    .select-mapel option {
        background: #0d2e1a;
        color: #e2f5ea;
        padding: 0.3rem 0.5rem;
        font-size: 0.845rem;
        border-radius: 4px;
    }

    .select-mapel option:checked {
        background: rgba(217,119,6,0.35);
        color: #fbbf24;
    }

    .mapel-hint-text {
        font-size: 0.71rem;
        color: rgba(255,255,255,0.28);
        margin-top: 0.35rem;
    }

    .mapel-selected-info {
        font-size: 0.72rem;
        color: rgba(251,191,36,0.7);
        font-weight: 600;
        margin-top: 0.3rem;
        min-height: 1rem;
    }
</style>
@endpush

@section('content')

{{-- ================================================================
     PAGE HEADER
================================================================ --}}
<div class="page-header">
    <div class="page-header-left">
        <h2>Data Guru</h2>
        <p>Kelola data dan akun guru sekolah</p>
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        <div class="search-wrap">
            <i class="bi bi-search"></i>
            <input
                type="text"
                id="searchInput"
                class="search-input"
                placeholder="Cari nama, username, email..."
                autocomplete="off"
            >
        </div>

        <button class="btn-share-guru" id="btnBagikanLink" title="Bagikan tautan pendaftaran guru">
            <i class="bi bi-share-fill"></i>
            <span class="btn-share-label">Bagikan</span>
        </button>

        <button class="btn-add-guru" id="btnTambahGuru">
            <i class="bi bi-plus-lg"></i>
            Guru
        </button>
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

{{-- ================================================================
     MODAL TAMBAH GURU (2-step)
================================================================ --}}
<div class="modal fade modal-dark" id="modalTambahGuru" tabindex="-1" aria-labelledby="modalTambahGuruLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahGuruLabel">
                    <i class="bi bi-person-plus-fill me-2" style="color:#4ade80;"></i>
                    Tambah Guru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">

                {{-- Step Indicator --}}
                <div class="step-indicator" id="stepIndicator">
                    <div class="step-item">
                        <div class="step-circle active" id="stepCircle1">1</div>
                        <span class="step-label active" id="stepLabel1">Data Guru</span>
                    </div>
                    <div class="step-line" id="stepLine1"></div>
                    <div class="step-item">
                        <div class="step-circle" id="stepCircle2">2</div>
                        <span class="step-label" id="stepLabel2">Akun</span>
                    </div>
                </div>

                <form id="formTambahGuru" enctype="multipart/form-data" novalidate>
                    @csrf

                    {{-- ---- STEP 1: Data Guru ---- --}}
                    <div id="step1Content">

                        {{-- Nama --}}
                        <div class="mb-3">
                            <label class="form-label-dark" for="inputNama">Nama Lengkap <span style="color:#f87171;">*</span></label>
                            <input
                                type="text"
                                id="inputNama"
                                name="name"
                                class="form-control-dark"
                                placeholder="Contoh: Budi Santoso, S.Pd"
                                maxlength="255"
                                required
                            >
                            <div class="field-feedback" id="feedbackNama"></div>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label-dark" for="inputEmail">Alamat Email <span style="color:#f87171;">*</span></label>
                            <input
                                type="email"
                                id="inputEmail"
                                name="email"
                                class="form-control-dark"
                                placeholder="guru@sekolah.sch.id"
                                maxlength="255"
                                required
                            >
                            <div class="field-feedback" id="feedbackEmail"></div>
                        </div>

                        {{-- Foto Profil --}}
                        <div class="mb-1">
                            <label class="form-label-dark">Foto Profil <span style="color:rgba(255,255,255,0.35);">(opsional)</span></label>
                            <div class="foto-upload-area" id="fotoUploadArea" role="button" aria-label="Klik untuk unggah foto">
                                <i class="bi bi-camera foto-upload-icon"></i>
                                <p class="foto-upload-text">
                                    <span>Klik untuk unggah</span> atau seret ke sini<br>
                                    <span style="color:rgba(255,255,255,0.35);font-size:0.72rem;">JPG, PNG, WEBP, maks. 2 MB</span>
                                </p>
                                <input
                                    type="file"
                                    id="inputFoto"
                                    name="image"
                                    accept="image/jpeg,image/png,image/webp"
                                    style="display:none;"
                                >
                            </div>
                            {{-- Preview --}}
                            <div class="text-center" id="fotoPreviewWrap" style="display:none;">
                                <div class="foto-preview-wrap">
                                    <img src="" id="fotoPreviewImg" class="foto-preview-img" alt="Preview foto profil">
                                    <button type="button" class="foto-preview-remove" id="btnHapusFoto" aria-label="Hapus foto">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="field-feedback" id="feedbackFoto"></div>
                        </div>

                    </div>
                    {{-- /step1Content --}}

                    {{-- ---- STEP 2: Pembuatan Akun ---- --}}
                    <div id="step2Content" style="display:none;">

                        {{-- Username --}}
                        <div class="mb-3">
                            <label class="form-label-dark" for="inputUsername">Username <span style="color:#f87171;">*</span></label>
                            <input
                                type="text"
                                id="inputUsername"
                                name="username"
                                class="form-control-dark"
                                placeholder="Contoh: budi_santoso"
                                maxlength="60"
                                autocomplete="off"
                                required
                            >
                            <div class="field-feedback" id="feedbackUsername"></div>
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label class="form-label-dark" for="inputPassword">Password <span style="color:#f87171;">*</span></label>
                            <div style="position:relative;">
                                <input
                                    type="password"
                                    id="inputPassword"
                                    name="password"
                                    class="form-control-dark"
                                    placeholder="Min. 8 karakter (huruf + angka)"
                                    maxlength="128"
                                    autocomplete="new-password"
                                    required
                                    style="padding-right:2.6rem;"
                                >
                                <button type="button" id="togglePwd" aria-label="Tampilkan password"
                                    style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(255,255,255,0.4);cursor:pointer;padding:0;line-height:1;">
                                    <i class="bi bi-eye" id="togglePwdIcon"></i>
                                </button>
                            </div>
                            <div class="pwd-strength-bar">
                                <div class="pwd-strength-fill" id="pwdStrengthFill"></div>
                            </div>
                            <div class="field-feedback" id="feedbackPassword"></div>
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="mb-3">
                            <label class="form-label-dark" for="inputPasswordConfirm">Konfirmasi Password <span style="color:#f87171;">*</span></label>
                            <div style="position:relative;">
                                <input
                                    type="password"
                                    id="inputPasswordConfirm"
                                    name="password_confirmation"
                                    class="form-control-dark"
                                    placeholder="Ulangi password"
                                    maxlength="128"
                                    autocomplete="new-password"
                                    required
                                    style="padding-right:2.6rem;"
                                >
                                <button type="button" id="togglePwdConfirm" aria-label="Tampilkan konfirmasi password"
                                    style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;color:rgba(255,255,255,0.4);cursor:pointer;padding:0;line-height:1;">
                                    <i class="bi bi-eye" id="togglePwdConfirmIcon"></i>
                                </button>
                            </div>
                            <div class="field-feedback" id="feedbackPasswordConfirm"></div>
                        </div>

                    </div>
                    {{-- /step2Content --}}

                    {{-- ---- STEP 2 BAWAH: Pilih Mata Pelajaran ---- --}}
                    <div id="step2MapelSection" style="display:none; margin-top:1rem;">
                        <div class="mapel-section-wrap">
                            <div class="mapel-section-header">
                                <i class="bi bi-book-fill" style="color:#fbbf24;"></i>
                                <span>Mata Pelajaran</span>
                                <span class="mapel-section-hint">(opsional — tahan Ctrl/⌘ untuk pilih banyak)</span>
                            </div>
                            <div class="mapel-select-wrap">
                                <select
                                    id="selectMapelTambah"
                                    class="select-mapel"
                                    multiple
                                    size="5"
                                    aria-label="Pilih mata pelajaran"
                                >
                                    {{-- diisi JS saat masuk step 2 --}}
                                </select>
                            </div>
                            <p class="mapel-hint-text">Tahan <kbd style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);border-radius:4px;padding:0 4px;font-size:0.68rem;">Ctrl</kbd> atau <kbd style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);border-radius:4px;padding:0 4px;font-size:0.68rem;">⌘</kbd> untuk memilih lebih dari satu.</p>
                            <div class="mapel-selected-info" id="tambahMapelInfo"></div>
                        </div>
                    </div>

                </form>

            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn-modal-secondary" id="btnStepBack" style="display:none;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </button>
                <div class="ms-auto d-flex gap-2">
                    <button type="button" class="btn-modal-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-modal-primary" id="btnStepNext">
                        Lanjut <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ================================================================
     MODAL EDIT GURU
================================================================ --}}
<div class="modal fade modal-dark" id="modalEditGuru" tabindex="-1" aria-labelledby="modalEditGuruLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalEditGuruLabel">
                    <i class="bi bi-pencil-fill me-2" style="color:#4ade80;"></i>
                    Edit Data Guru
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <form id="formEditGuru" enctype="multipart/form-data" novalidate>
                    @csrf
                    <input type="hidden" id="editGuruId">

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label-dark" for="editNama">Nama Lengkap <span style="color:#f87171;">*</span></label>
                        <input type="text" id="editNama" name="name" class="form-control-dark" placeholder="Nama lengkap" maxlength="255" required>
                        <div class="field-feedback" id="feedbackEditNama"></div>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label-dark" for="editEmail">Alamat Email <span style="color:#f87171;">*</span></label>
                        <input type="email" id="editEmail" name="email" class="form-control-dark" placeholder="Email" maxlength="255" required>
                        <div class="field-feedback" id="feedbackEditEmail"></div>
                    </div>

                    {{-- Username --}}
                    <div class="mb-3">
                        <label class="form-label-dark" for="editUsername">Username <span style="color:#f87171;">*</span></label>
                        <input type="text" id="editUsername" name="username" class="form-control-dark" placeholder="Username" maxlength="60" autocomplete="off" required>
                        <div class="field-feedback" id="feedbackEditUsername"></div>
                    </div>

                    {{-- Foto --}}
                    <div class="mb-3">
                        <label class="form-label-dark">Foto Profil <span style="color:rgba(255,255,255,0.35);">(opsional, kosongkan jika tidak diganti)</span></label>
                        <div class="foto-upload-area" id="editFotoUploadArea" role="button">
                            <i class="bi bi-camera foto-upload-icon"></i>
                            <p class="foto-upload-text"><span>Klik untuk unggah</span> atau seret ke sini</p>
                            <input type="file" id="editInputFoto" name="image" accept="image/jpeg,image/png,image/webp" style="display:none;">
                        </div>
                        <div class="text-center" id="editFotoPreviewWrap" style="display:none;">
                            <div class="foto-preview-wrap">
                                <img src="" id="editFotoPreviewImg" class="foto-preview-img" alt="Preview foto">
                                <button type="button" class="foto-preview-remove" id="editBtnHapusFoto" aria-label="Hapus foto">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Password (opsional) --}}
                    <div class="mb-1">
                        <label class="form-label-dark" for="editPassword">
                            Password Baru
                            <span style="color:rgba(255,255,255,0.35);">(kosongkan jika tidak diganti)</span>
                        </label>
                        <input type="password" id="editPassword" name="password" class="form-control-dark" placeholder="Min. 8 karakter (huruf + angka)" maxlength="128" autocomplete="new-password">
                        <div class="field-feedback" id="feedbackEditPassword"></div>
                    </div>

                    {{-- Mata Pelajaran (edit) --}}
                    <div class="mb-1">
                        <div class="mapel-section-wrap">
                            <div class="mapel-section-header">
                                <i class="bi bi-book-fill" style="color:#fbbf24;"></i>
                                <span>Mata Pelajaran</span>
                                <span class="mapel-section-hint">(opsional — tahan Ctrl/⌘ untuk pilih banyak)</span>
                            </div>
                            <div class="mapel-select-wrap">
                                <select
                                    id="selectMapelEdit"
                                    class="select-mapel"
                                    multiple
                                    size="5"
                                    aria-label="Pilih mata pelajaran"
                                >
                                    {{-- diisi JS saat modal edit dibuka --}}
                                </select>
                            </div>
                            <p class="mapel-hint-text">Tahan <kbd style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);border-radius:4px;padding:0 4px;font-size:0.68rem;">Ctrl</kbd> atau <kbd style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);border-radius:4px;padding:0 4px;font-size:0.68rem;">⌘</kbd> untuk memilih lebih dari satu.</p>
                            <div class="mapel-selected-info" id="editMapelInfo"></div>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-modal-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-modal-primary" id="btnSimpanEdit">
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

    /* ============================================================
       CSRF TOKEN
    ============================================================ */
    var CSRF = document.querySelector('meta[name="csrf-token"]')
                ? document.querySelector('meta[name="csrf-token"]').content
                : '{{ csrf_token() }}';

    /* ============================================================
       STATE
    ============================================================ */
    var currentStep     = 1;
    var searchTimer     = null;
    var usernameTimer   = null;
    var usernameOk      = false;
    var currentTab      = 'aktif';

    /* ============================================================
       HELPERS
    ============================================================ */
    function setFeedback(el, type, msg) {
        el.className = 'field-feedback ' + type;
        el.textContent = msg;
    }

    function clearFeedback(el) {
        el.className = 'field-feedback muted';
        el.textContent = '';
    }

    function setInputState(input, state) {
        input.classList.remove('is-valid', 'is-invalid');
        if (state) input.classList.add(state);
    }

    /* ============================================================
       LOAD LIST (AJAX)
    ============================================================ */
    function loadList(search, tab) {
        search = search || '';
        tab    = tab    || currentTab;
        currentTab = tab;

        var container = document.getElementById('listContainer');
        container.innerHTML = '<div class="list-loading"><span class="spinner-green"></span> Memuat data...</div>';

        var url = '{{ route("admin.data_guru.list") }}?search=' + encodeURIComponent(search) + '&tab=' + encodeURIComponent(tab);

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (res) { return res.text(); })
        .then(function (html) { container.innerHTML = html; bindListEvents(); })
        .catch(function () {
            container.innerHTML = '<div class="list-loading" style="color:#f87171;">Gagal memuat data. Silakan muat ulang halaman.</div>';
        });
    }

    /* ============================================================
       SEARCH DEBOUNCE
    ============================================================ */
    var searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);
        var val = this.value.trim();
        searchTimer = setTimeout(function () { loadList(val, currentTab); }, 350);
    });

    /* ============================================================
       BIND EVENTS PADA ELEMEN LIST (delegasi)
    ============================================================ */
    function bindListEvents() {
        var container = document.getElementById('listContainer');

        /* Tab switch */
        container.querySelectorAll('[data-tab]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var tab = this.dataset.tab;
                loadList(searchInput.value.trim(), tab);
            });
        });

        /* Tombol Edit */
        container.querySelectorAll('[data-edit-id]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                openEditModal(this.dataset.editId);
            });
        });

        /* Tombol Hapus (softdelete) */
        container.querySelectorAll('[data-delete-id]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                confirmHapus(this.dataset.deleteId, this.dataset.deleteNama);
            });
        });

        /* Tombol Pulihkan */
        container.querySelectorAll('[data-restore-id]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                confirmRestore(this.dataset.restoreId, this.dataset.restoreNama);
            });
        });

        /* Tombol Hapus Permanen */
        container.querySelectorAll('[data-force-id]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                confirmForceDelete(this.dataset.forceId, this.dataset.forceNama);
            });
        });
    }

    /* ============================================================
       MODAL TAMBAH: STEP LOGIC
    ============================================================ */
    var modalTambahEl = document.getElementById('modalTambahGuru');
    var modalTambah   = new bootstrap.Modal(modalTambahEl);

    document.getElementById('btnTambahGuru').addEventListener('click', function () {
        resetTambahForm();
        modalTambah.show();
    });

    /* Reset form tambah */
    function resetTambahForm() {
        currentStep = 1;
        document.getElementById('formTambahGuru').reset();
        removeFotoPreview();
        goToStep(1);
        clearAllFeedbackTambah();
        usernameOk = false;
    }

    function clearAllFeedbackTambah() {
        ['feedbackNama','feedbackEmail','feedbackFoto',
         'feedbackUsername','feedbackPassword','feedbackPasswordConfirm'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) clearFeedback(el);
        });
        ['inputNama','inputEmail','inputUsername','inputPassword','inputPasswordConfirm'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) setInputState(el, null);
        });
        document.getElementById('pwdStrengthFill').style.width = '0';
        document.getElementById('pwdStrengthFill').style.background = '';
    }

    function goToStep(step) {
        currentStep = step;

        var s1 = document.getElementById('step1Content');
        var s2 = document.getElementById('step2Content');
        var c1 = document.getElementById('stepCircle1');
        var c2 = document.getElementById('stepCircle2');
        var l1 = document.getElementById('stepLabel1');
        var l2 = document.getElementById('stepLabel2');
        var ln = document.getElementById('stepLine1');
        var btnBack = document.getElementById('btnStepBack');
        var btnNext = document.getElementById('btnStepNext');

        if (step === 1) {
            s1.style.display = '';
            s2.style.display = 'none';
            c1.className = 'step-circle active'; l1.className = 'step-label active';
            c2.className = 'step-circle';        l2.className = 'step-label';
            ln.className = 'step-line';
            btnBack.style.display = 'none';
            btnNext.innerHTML = 'Lanjut <i class="bi bi-arrow-right ms-1"></i>';
        } else {
            s1.style.display = 'none';
            s2.style.display = '';
            c1.className = 'step-circle done'; c1.innerHTML = '<i class="bi bi-check-lg"></i>';
            l1.className = 'step-label done';
            c2.className = 'step-circle active'; l2.className = 'step-label active';
            ln.className = 'step-line done';
            btnBack.style.display = '';
            btnNext.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';
        }
    }

    document.getElementById('btnStepBack').addEventListener('click', function () {
        if (currentStep > 1) goToStep(currentStep - 1);
    });

    document.getElementById('btnStepNext').addEventListener('click', function () {
        if (currentStep === 1) {
            if (validateStep1()) goToStep(2);
        } else {
            submitTambahGuru();
        }
    });

    /* ============================================================
       VALIDASI STEP 1
    ============================================================ */
    function validateStep1() {
        var ok = true;

        var nama  = document.getElementById('inputNama').value.trim();
        var email = document.getElementById('inputEmail').value.trim();

        if (!nama) {
            setInputState(document.getElementById('inputNama'), 'is-invalid');
            setFeedback(document.getElementById('feedbackNama'), 'invalid', 'Nama lengkap wajib diisi.');
            ok = false;
        } else {
            setInputState(document.getElementById('inputNama'), 'is-valid');
            clearFeedback(document.getElementById('feedbackNama'));
        }

        var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRe.test(email)) {
            setInputState(document.getElementById('inputEmail'), 'is-invalid');
            setFeedback(document.getElementById('feedbackEmail'), 'invalid', 'Masukkan alamat email yang valid.');
            ok = false;
        } else {
            setInputState(document.getElementById('inputEmail'), 'is-valid');
            clearFeedback(document.getElementById('feedbackEmail'));
        }

        return ok;
    }

    /* ============================================================
       FOTO UPLOAD & PREVIEW (Tambah)
    ============================================================ */
    var fotoArea     = document.getElementById('fotoUploadArea');
    var fotoInput    = document.getElementById('inputFoto');
    var fotoPreview  = document.getElementById('fotoPreviewWrap');
    var fotoImg      = document.getElementById('fotoPreviewImg');
    var feedbackFoto = document.getElementById('feedbackFoto');

    fotoArea.addEventListener('click', function () { fotoInput.click(); });

    fotoArea.addEventListener('dragover', function (e) {
        e.preventDefault();
        fotoArea.style.borderColor = 'rgba(74,222,128,0.6)';
    });

    fotoArea.addEventListener('dragleave', function () {
        fotoArea.style.borderColor = '';
    });

    fotoArea.addEventListener('drop', function (e) {
        e.preventDefault();
        fotoArea.style.borderColor = '';
        var file = e.dataTransfer.files[0];
        if (file) handleFotoFile(file, fotoInput, fotoImg, fotoArea, fotoPreview, feedbackFoto);
    });

    fotoInput.addEventListener('change', function () {
        if (this.files[0]) handleFotoFile(this.files[0], this, fotoImg, fotoArea, fotoPreview, feedbackFoto);
    });

    document.getElementById('btnHapusFoto').addEventListener('click', function () {
        removeFotoPreview();
    });

    function handleFotoFile(file, inputEl, imgEl, areaEl, previewWrap, feedback) {
        var allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!allowed.includes(file.type)) {
            setFeedback(feedback, 'invalid', 'Format file tidak didukung. Gunakan JPG, PNG, atau WEBP.');
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            setFeedback(feedback, 'invalid', 'Ukuran file melebihi 2 MB.');
            return;
        }
        clearFeedback(feedback);
        var reader = new FileReader();
        reader.onload = function (e) {
            imgEl.src = e.target.result;
            areaEl.style.display = 'none';
            previewWrap.style.display = '';
        };
        reader.readAsDataURL(file);
    }

    function removeFotoPreview() {
        fotoInput.value = '';
        fotoImg.src = '';
        fotoArea.style.display = '';
        fotoPreview.style.display = 'none';
        clearFeedback(feedbackFoto);
    }

    /* ============================================================
       USERNAME POLLING (AJAX)
    ============================================================ */
    document.getElementById('inputUsername').addEventListener('input', function () {
        var val = this.value.trim();
        clearTimeout(usernameTimer);
        usernameOk = false;

        if (!val) {
            clearFeedback(document.getElementById('feedbackUsername'));
            setInputState(this, null);
            return;
        }

        var inputEl = this;
        setFeedback(document.getElementById('feedbackUsername'), 'muted', 'Memeriksa...');

        usernameTimer = setTimeout(function () {
            fetch('{{ route("admin.data_guru.check_username") }}?username=' + encodeURIComponent(val), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                usernameOk = data.available;
                if (data.available) {
                    setInputState(inputEl, 'is-valid');
                    setFeedback(document.getElementById('feedbackUsername'), 'valid', data.message);
                } else {
                    setInputState(inputEl, 'is-invalid');
                    setFeedback(document.getElementById('feedbackUsername'), 'invalid', data.message);
                }
            })
            .catch(function () {
                setFeedback(document.getElementById('feedbackUsername'), 'warning', 'Tidak dapat memeriksa username.');
            });
        }, 450);
    });

    /* ============================================================
       PASSWORD STRENGTH + TOGGLE
    ============================================================ */
    document.getElementById('togglePwd').addEventListener('click', function () {
        var input = document.getElementById('inputPassword');
        var icon  = document.getElementById('togglePwdIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });

    document.getElementById('togglePwdConfirm').addEventListener('click', function () {
        var input = document.getElementById('inputPasswordConfirm');
        var icon  = document.getElementById('togglePwdConfirmIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });

    function calcStrength(pwd) {
        var score = 0;
        if (pwd.length >= 8)  score++;
        if (pwd.length >= 12) score++;
        if (/[a-z]/.test(pwd) && /[A-Z]/.test(pwd)) score++;
        if (/\d/.test(pwd))   score++;
        if (/[^a-zA-Z0-9]/.test(pwd)) score++;
        return score; // 0-5
    }

    document.getElementById('inputPassword').addEventListener('input', function () {
        var val    = this.value;
        var fill   = document.getElementById('pwdStrengthFill');
        var score  = calcStrength(val);
        var pct    = (score / 5) * 100;
        fill.style.width = pct + '%';

        if (score <= 1) {
            fill.style.background = '#f87171';
        } else if (score <= 3) {
            fill.style.background = '#fbbf24';
        } else {
            fill.style.background = '#4ade80';
        }

        validatePassword();
        validatePasswordConfirm();
    });

    document.getElementById('inputPasswordConfirm').addEventListener('input', function () {
        validatePasswordConfirm();
    });

    function validatePassword() {
        var val  = document.getElementById('inputPassword').value;
        var fb   = document.getElementById('feedbackPassword');
        var inp  = document.getElementById('inputPassword');

        if (!val) { clearFeedback(fb); setInputState(inp, null); return false; }

        var hasLetter  = /[a-zA-Z]/.test(val);
        var hasNumber  = /\d/.test(val);
        var minLen     = val.length >= 8;

        if (!minLen) {
            setInputState(inp, 'is-invalid');
            setFeedback(fb, 'invalid', 'Password minimal 8 karakter.');
            return false;
        }
        if (!hasLetter || !hasNumber) {
            setInputState(inp, 'is-invalid');
            setFeedback(fb, 'invalid', 'Password harus mengandung huruf dan angka.');
            return false;
        }
        setInputState(inp, 'is-valid');
        clearFeedback(fb);
        return true;
    }

    function validatePasswordConfirm() {
        var pwd     = document.getElementById('inputPassword').value;
        var confirm = document.getElementById('inputPasswordConfirm').value;
        var fb      = document.getElementById('feedbackPasswordConfirm');
        var inp     = document.getElementById('inputPasswordConfirm');

        if (!confirm) { clearFeedback(fb); setInputState(inp, null); return false; }

        if (pwd !== confirm) {
            setInputState(inp, 'is-invalid');
            setFeedback(fb, 'invalid', 'Password tidak cocok.');
            return false;
        }
        setInputState(inp, 'is-valid');
        setFeedback(fb, 'valid', 'Password cocok.');
        return true;
    }

    /* ============================================================
       SUBMIT TAMBAH GURU
    ============================================================ */
    function submitTambahGuru() {
        var username = document.getElementById('inputUsername').value.trim();
        var pwdOk    = validatePassword();
        var confOk   = validatePasswordConfirm();

        if (!username) {
            setInputState(document.getElementById('inputUsername'), 'is-invalid');
            setFeedback(document.getElementById('feedbackUsername'), 'invalid', 'Username wajib diisi.');
            return;
        }

        if (!usernameOk) {
            setInputState(document.getElementById('inputUsername'), 'is-invalid');
            setFeedback(document.getElementById('feedbackUsername'), 'invalid', 'Periksa kembali username Anda.');
            return;
        }

        if (!pwdOk || !confOk) return;

        var btn = document.getElementById('btnStepNext');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-green" style="width:16px;height:16px;border-width:2px;margin-right:6px;"></span> Menyimpan...';

        var formData = new FormData(document.getElementById('formTambahGuru'));

        fetch('{{ route("admin.data_guru.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN':    CSRF,
                'X-Requested-With':'XMLHttpRequest'
            },
            body: formData
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';

            if (data.success) {
                modalTambah.hide();
                /* Jika ada mapel dipilih, simpan dulu baru tampilkan alert */
                var guruIdBaru = data.guru_id;
                function selesaiBuatGuru() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || 'Data guru berhasil ditambahkan.',
                        confirmButtonColor: '#22c55e',
                        background: '#0a2e1c',
                        color: '#ffffff',
                        timer: 2500,
                        showConfirmButton: false,
                    }).then(function () {
                        loadList(searchInput.value.trim(), currentTab);
                    });
                }

                if (guruIdBaru) {
                    /* Baca mapel dari select multi — termasuk jika kosong (array kosong = hapus semua) */
                    var selTambah     = document.getElementById('selectMapelTambah');
                    var mapelTerpilih = selTambah ? getSelectedFromSelect(selTambah) : [];

                    fetch('{{ url("admin/data-guru") }}/' + guruIdBaru + '/mapel', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN':     CSRF,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type':     'application/json',
                        },
                        body: JSON.stringify({ mapel_ids: mapelTerpilih })
                    })
                    .then(function () { selesaiBuatGuru(); })
                    .catch(function () { selesaiBuatGuru(); });
                } else {
                    selesaiBuatGuru();
                }
            } else {
                var msgs = [];
                if (data.errors) {
                    Object.values(data.errors).forEach(function (arr) { msgs = msgs.concat(arr); });
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal menyimpan',
                    html: msgs.length ? msgs.join('<br>') : (data.message || 'Terjadi kesalahan.'),
                    confirmButtonColor: '#22c55e',
                    background: '#0a2e1c',
                    color: '#ffffff',
                });
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';
            Swal.fire({
                icon: 'error',
                title: 'Koneksi gagal',
                text: 'Tidak dapat terhubung ke server.',
                confirmButtonColor: '#22c55e',
                background: '#0a2e1c',
                color: '#ffffff',
            });
        });
    }

    /* ============================================================
       HAPUS (SOFTDELETE)
    ============================================================ */
    function confirmHapus(id, nama) {
        Swal.fire({
            icon: 'warning',
            title: 'Nonaktifkan guru?',
            html: 'Guru <strong>' + escHtml(nama) + '</strong> akan dipindahkan ke daftar tidak aktif.',
            showCancelButton: true,
            confirmButtonText: 'Ya, nonaktifkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f87171',
            cancelButtonColor: 'rgba(255,255,255,0.1)',
            background: '#0a2e1c',
            color: '#ffffff',
        }).then(function (result) {
            if (!result.isConfirmed) return;

            fetch('{{ url("admin/data-guru") }}/' + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN':    CSRF,
                    'X-Requested-With':'XMLHttpRequest',
                    'Content-Type':    'application/json',
                }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Dinonaktifkan',
                        text: data.message,
                        timer: 1800,
                        showConfirmButton: false,
                        background: '#0a2e1c',
                        color: '#ffffff',
                    }).then(function () { loadList(searchInput.value.trim(), currentTab); });
                } else {
                    Swal.fire({ icon:'error', title:'Gagal', text: data.message || 'Terjadi kesalahan.', background:'#0a2e1c', color:'#fff' });
                }
            })
            .catch(function () {
                Swal.fire({ icon:'error', title:'Koneksi gagal', text:'Tidak dapat terhubung ke server.', background:'#0a2e1c', color:'#fff' });
            });
        });
    }

    /* ============================================================
       PULIHKAN
    ============================================================ */
    function confirmRestore(id, nama) {
        Swal.fire({
            icon: 'question',
            title: 'Aktifkan kembali?',
            html: 'Guru <strong>' + escHtml(nama) + '</strong> akan diaktifkan kembali.',
            showCancelButton: true,
            confirmButtonText: 'Ya, aktifkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#22c55e',
            cancelButtonColor: 'rgba(255,255,255,0.1)',
            background: '#0a2e1c',
            color: '#ffffff',
        }).then(function (result) {
            if (!result.isConfirmed) return;

            fetch('{{ url("admin/data-guru") }}/' + id + '/restore', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN':    CSRF,
                    'X-Requested-With':'XMLHttpRequest',
                    'Content-Type':    'application/json',
                }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    Swal.fire({
                        icon: 'success', title: 'Diaktifkan',
                        text: data.message, timer: 1800, showConfirmButton: false,
                        background: '#0a2e1c', color: '#ffffff',
                    }).then(function () { loadList(searchInput.value.trim(), currentTab); });
                } else {
                    Swal.fire({ icon:'error', title:'Gagal', text: data.message, background:'#0a2e1c', color:'#fff' });
                }
            });
        });
    }

    /* ============================================================
       HAPUS PERMANEN
    ============================================================ */
    function confirmForceDelete(id, nama) {
        Swal.fire({
            icon: 'error',
            title: 'Hapus permanen?',
            html: 'Data guru <strong>' + escHtml(nama) + '</strong> akan dihapus selamanya dan tidak dapat dipulihkan.',
            showCancelButton: true,
            confirmButtonText: 'Hapus permanen',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f87171',
            cancelButtonColor: 'rgba(255,255,255,0.1)',
            background: '#0a2e1c',
            color: '#ffffff',
        }).then(function (result) {
            if (!result.isConfirmed) return;

            fetch('{{ url("admin/data-guru") }}/' + id + '/force', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN':    CSRF,
                    'X-Requested-With':'XMLHttpRequest',
                    'Content-Type':    'application/json',
                }
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    Swal.fire({
                        icon: 'success', title: 'Dihapus',
                        text: data.message, timer: 1800, showConfirmButton: false,
                        background: '#0a2e1c', color: '#ffffff',
                    }).then(function () { loadList(searchInput.value.trim(), 'nonaktif'); });
                } else {
                    Swal.fire({ icon:'error', title:'Gagal', text: data.message, background:'#0a2e1c', color:'#fff' });
                }
            });
        });
    }

    /* ============================================================
       MODAL EDIT
    ============================================================ */
    var modalEditEl = document.getElementById('modalEditGuru');
    var modalEdit   = new bootstrap.Modal(modalEditEl);

    function openEditModal(id) {
        fetch('{{ url("admin/data-guru") }}/' + id + '/edit', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            document.getElementById('editGuruId').value  = data.id;
            document.getElementById('editNama').value     = data.name;
            document.getElementById('editEmail').value    = data.email;
            document.getElementById('editUsername').value = data.username;
            document.getElementById('editPassword').value = '';

            /* Reset foto edit */
            var editFotoInput   = document.getElementById('editInputFoto');
            var editFotoArea    = document.getElementById('editFotoUploadArea');
            var editFotoPreview = document.getElementById('editFotoPreviewWrap');
            var editFotoImg     = document.getElementById('editFotoPreviewImg');
            editFotoInput.value = '';

            if (data.image) {
                editFotoImg.src              = data.image;
                editFotoArea.style.display   = 'none';
                editFotoPreview.style.display = '';
            } else {
                editFotoArea.style.display   = '';
                editFotoPreview.style.display = 'none';
            }

            /* Clear validasi */
            ['feedbackEditNama','feedbackEditEmail','feedbackEditUsername','feedbackEditPassword'].forEach(function (id) {
                clearFeedback(document.getElementById(id));
            });
            ['editNama','editEmail','editUsername','editPassword'].forEach(function (id) {
                setInputState(document.getElementById(id), null);
            });

            /* Load mapel yang sudah dipilih guru ini, populate select */
            var selEdit = document.getElementById('selectMapelEdit');
            if (selEdit) selEdit.innerHTML = '<option disabled>Memuat...</option>';

            fetch('{{ url("admin/data-guru") }}/' + data.id + '/mapel', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (r) { return r.json(); })
            .then(function (mapelData) {
                var dipilih = (mapelData.mapel_dipilih || []).map(String);
                window._populateEditMapelSelect(mapelData.semua_mapel || [], dipilih);
            })
            .catch(function () {
                if (selEdit) selEdit.innerHTML = '';
            });

            modalEdit.show();
        })
        .catch(function () {
            Swal.fire({ icon:'error', title:'Gagal', text:'Tidak dapat memuat data guru.', background:'#0a2e1c', color:'#fff' });
        });
    }

    /* Foto edit */
    var editFotoArea  = document.getElementById('editFotoUploadArea');
    var editFotoInput = document.getElementById('editInputFoto');

    editFotoArea.addEventListener('click', function () { editFotoInput.click(); });

    editFotoInput.addEventListener('change', function () {
        if (this.files[0]) {
            var feedbackEl = { className: '', textContent: '' };
            handleFotoFile(
                this.files[0], this,
                document.getElementById('editFotoPreviewImg'),
                editFotoArea,
                document.getElementById('editFotoPreviewWrap'),
                feedbackEl
            );
        }
    });

    document.getElementById('editBtnHapusFoto').addEventListener('click', function () {
        editFotoInput.value = '';
        document.getElementById('editFotoPreviewImg').src = '';
        editFotoArea.style.display = '';
        document.getElementById('editFotoPreviewWrap').style.display = 'none';
    });

    /* Submit edit */
    document.getElementById('btnSimpanEdit').addEventListener('click', function () {
        var id     = document.getElementById('editGuruId').value;
        var nama   = document.getElementById('editNama').value.trim();
        var email  = document.getElementById('editEmail').value.trim();
        var uname  = document.getElementById('editUsername').value.trim();
        var ok     = true;

        if (!nama) {
            setInputState(document.getElementById('editNama'), 'is-invalid');
            setFeedback(document.getElementById('feedbackEditNama'), 'invalid', 'Nama wajib diisi.');
            ok = false;
        } else {
            setInputState(document.getElementById('editNama'), 'is-valid');
            clearFeedback(document.getElementById('feedbackEditNama'));
        }

        var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRe.test(email)) {
            setInputState(document.getElementById('editEmail'), 'is-invalid');
            setFeedback(document.getElementById('feedbackEditEmail'), 'invalid', 'Email tidak valid.');
            ok = false;
        } else {
            setInputState(document.getElementById('editEmail'), 'is-valid');
            clearFeedback(document.getElementById('feedbackEditEmail'));
        }

        if (!uname) {
            setInputState(document.getElementById('editUsername'), 'is-invalid');
            setFeedback(document.getElementById('feedbackEditUsername'), 'invalid', 'Username wajib diisi.');
            ok = false;
        } else {
            setInputState(document.getElementById('editUsername'), 'is-valid');
            clearFeedback(document.getElementById('feedbackEditUsername'));
        }

        if (!ok) return;

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-green" style="width:16px;height:16px;border-width:2px;margin-right:6px;"></span> Menyimpan...';

        var formData = new FormData(document.getElementById('formEditGuru'));
        formData.append('_method', 'POST');

        fetch('{{ url("admin/data-guru") }}/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN':    CSRF,
                'X-Requested-With':'XMLHttpRequest',
            },
            body: formData
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';

            if (data.success) {
                /* Setelah data guru tersimpan, simpan juga mapel dari select */
                var selMapel    = document.getElementById('selectMapelEdit');
                var mapelTerpilih = selMapel ? getSelectedFromSelect(selMapel) : [];

                function selesaiUpdateGuru() {
                    modalEdit.hide();
                    Swal.fire({
                        icon: 'success', title: 'Berhasil!',
                        text: data.message, timer: 2000, showConfirmButton: false,
                        background: '#0a2e1c', color: '#ffffff',
                    }).then(function () { loadList(searchInput.value.trim(), currentTab); });
                }

                fetch('{{ url("admin/data-guru") }}/' + id + '/mapel', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN':     CSRF,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type':     'application/json',
                    },
                    body: JSON.stringify({ mapel_ids: mapelTerpilih })
                })
                .then(function () { selesaiUpdateGuru(); })
                .catch(function () { selesaiUpdateGuru(); });
            } else {
                var msgs = [];
                if (data.errors) Object.values(data.errors).forEach(function (arr) { msgs = msgs.concat(arr); });
                Swal.fire({
                    icon: 'error', title: 'Gagal menyimpan',
                    html: msgs.length ? msgs.join('<br>') : (data.message || 'Terjadi kesalahan.'),
                    confirmButtonColor: '#22c55e', background: '#0a2e1c', color: '#fff',
                });
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';
            Swal.fire({ icon:'error', title:'Koneksi gagal', text:'Tidak dapat terhubung ke server.', background:'#0a2e1c', color:'#fff' });
        });
    });

    /* ============================================================
       UTIL: escape HTML
    ============================================================ */
    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    /* ============================================================
       TOMBOL BAGIKAN TAUTAN REGISTRASI
    ============================================================ */
    var REGISTER_URL = '{{ route("register.guru") }}';

    document.getElementById('btnBagikanLink').addEventListener('click', function () {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(REGISTER_URL).then(function () {
                showCopiedAlert();
            }).catch(function () {
                fallbackCopy(REGISTER_URL);
            });
        } else {
            fallbackCopy(REGISTER_URL);
        }
    });

    function fallbackCopy(text) {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.cssText = 'position:fixed;left:-9999px;top:-9999px;opacity:0;';
        document.body.appendChild(ta);
        ta.focus();
        ta.select();
        try {
            document.execCommand('copy');
            showCopiedAlert();
        } catch (e) {
            Swal.fire({
                icon: 'info',
                title: 'Tautan Pendaftaran Guru',
                html: '<div style="word-break:break-all;font-size:0.82rem;background:rgba(255,255,255,0.06);padding:0.65rem 0.85rem;border-radius:8px;color:#4ade80;border:1px solid rgba(74,222,128,0.2);">' + text + '</div><p style="font-size:0.78rem;color:rgba(255,255,255,0.5);margin-top:0.75rem;margin-bottom:0;">Salin tautan di atas dan bagikan kepada guru.</p>',
                confirmButtonColor: '#22c55e',
                background: '#0a2e1c',
                color: '#ffffff',
            });
        }
        document.body.removeChild(ta);
    }

    function showCopiedAlert() {
        Swal.fire({
            icon: 'success',
            title: 'Tautan disalin!',
            html: '<p style="font-size:0.85rem;color:rgba(255,255,255,0.65);margin-bottom:0.5rem;">Bagikan tautan berikut kepada guru agar dapat mendaftar secara mandiri:</p><div style="word-break:break-all;font-size:0.78rem;background:rgba(255,255,255,0.06);padding:0.55rem 0.8rem;border-radius:8px;color:#4ade80;border:1px solid rgba(74,222,128,0.18);">' + REGISTER_URL + '</div>',
            confirmButtonText: 'Oke',
            confirmButtonColor: '#22c55e',
            background: '#0a2e1c',
            color: '#ffffff',
            timer: 4000,
            timerProgressBar: true,
        });
    }

    /* ============================================================
       INISIALISASI
    ============================================================ */
    loadList('', 'aktif');

    /* ============================================================
       FITUR MAPEL GURU  –  multi-select dropdown (inline)
    ============================================================ */
    var selectedMapelIds = [];   /* dipakai saat submit tambah guru */
    var allMapelCache    = [];   /* cache semua mapel dari server */

    /* -- Populate <select> dengan opsi mapel -- */
    function populateMapelSelect(selectEl, dipilihIds) {
        selectEl.innerHTML = '';
        if (!allMapelCache.length) {
            var opt = document.createElement('option');
            opt.disabled = true;
            opt.textContent = 'Belum ada mata pelajaran';
            selectEl.appendChild(opt);
            return;
        }
        allMapelCache.forEach(function (m) {
            var opt    = document.createElement('option');
            opt.value  = String(m.id);
            opt.textContent = m.nama_mapel;
            if (dipilihIds && dipilihIds.indexOf(String(m.id)) > -1) {
                opt.selected = true;
            }
            selectEl.appendChild(opt);
        });
    }

    /* -- Baca nilai yang dipilih dari select element -- */
    function getSelectedFromSelect(selectEl) {
        return Array.from(selectEl.selectedOptions).map(function (o) { return o.value; });
    }

    /* -- Update info teks "X dipilih" -- */
    function updateMapelInfo(selectEl, infoId) {
        var count = selectEl.selectedOptions.length;
        var el    = document.getElementById(infoId);
        if (!el) return;
        el.textContent = count > 0 ? count + ' mata pelajaran dipilih' : '';
    }

    /* -- Load semua mapel dari server (cache sekali) -- */
    function loadAllMapel(cb) {
        if (allMapelCache.length) { if (cb) cb(); return; }
        fetch('{{ route("admin.data_guru.semua_mapel") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            allMapelCache = data.semua_mapel || [];
            if (cb) cb();
        })
        .catch(function () { if (cb) cb(); });
    }

    /* ---- Tampilkan section mapel saat user masuk step 2 ---- */
    var origGoToStep = goToStep;
    goToStep = function (step) {
        origGoToStep(step);
        var section = document.getElementById('step2MapelSection');
        section.style.display = step === 2 ? '' : 'none';

        if (step === 2) {
            loadAllMapel(function () {
                var sel = document.getElementById('selectMapelTambah');
                populateMapelSelect(sel, selectedMapelIds);
                updateMapelInfo(sel, 'tambahMapelInfo');
            });
        }
    };

    /* Listener perubahan pilihan pada select tambah */
    document.getElementById('selectMapelTambah').addEventListener('change', function () {
        selectedMapelIds = getSelectedFromSelect(this);
        updateMapelInfo(this, 'tambahMapelInfo');
    });

    /* ---- Reset mapel saat form tambah direset ---- */
    var origResetTambahForm = resetTambahForm;
    resetTambahForm = function () {
        origResetTambahForm();
        selectedMapelIds = [];
        allMapelCache    = [];
        var sel = document.getElementById('selectMapelTambah');
        if (sel) {
            sel.innerHTML = '';
            var opt = document.createElement('option');
            opt.disabled = true;
            opt.textContent = '— Memuat...';
            sel.appendChild(opt);
        }
        var info = document.getElementById('tambahMapelInfo');
        if (info) info.textContent = '';
    };

    /* ---- Modal Edit: load & populate select mapel ---- */
    /* Dipanggil dari openEditModal() setelah data guru berhasil di-fetch */
    window._populateEditMapelSelect = function (semua, dipilih) {
        allMapelCache = semua;
        var sel = document.getElementById('selectMapelEdit');
        populateMapelSelect(sel, dipilih);
        updateMapelInfo(sel, 'editMapelInfo');
    };

    document.getElementById('selectMapelEdit').addEventListener('change', function () {
        updateMapelInfo(this, 'editMapelInfo');
    });

    /* ---- Simpan mapel edit saat klik Simpan di modal edit ---- */
    /* Disisipkan ke dalam btnSimpanEdit: setelah update guru berhasil,
       langsung POST mapel dari selectMapelEdit */
    var origBtnSimpanEdit = document.getElementById('btnSimpanEdit');
    origBtnSimpanEdit._origClickHandled = true;   /* flag untuk skip double-bind */

}());
</script>
@endpush
