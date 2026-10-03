@extends('admin.layouts.app')

@section('title', 'Data Murid')
@section('page-title', 'Data Murid')

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
    .btn-share-murid {
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

    .btn-share-murid:hover {
        background: rgba(74,222,128,0.18);
        transform: translateY(-1px);
    }

    @media (max-width: 399.98px) {
        .btn-share-murid-label { display: none; }
    }

    /* ============================================================
       TOMBOL TAMBAH
    ============================================================ */
    .btn-add-murid {
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

    .btn-add-murid:hover {
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

    .form-control-dark.is-invalid { border-color: rgba(248,113,113,0.6); }
    .form-control-dark.is-valid   { border-color: rgba(74,222,128,0.6); }

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

    .foto-upload-text span { color: #4ade80; font-weight: 600; }

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
       PASSWORD HINT
    ============================================================ */
    .pwd-hint {
        font-size: 0.72rem;
        color: rgba(255,255,255,0.35);
        margin-top: 0.3rem;
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

    @keyframes spin { to { transform: rotate(360deg); } }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 575.98px) {
        .page-header { flex-direction: column; align-items: flex-start; }
        .search-wrap { max-width: 100%; }
        .modal-dialog { margin: 0.5rem; }
    }
</style>
@endpush

@section('content')

{{-- ================================================================
     PAGE HEADER
================================================================ --}}
<div class="page-header">
    <div class="page-header-left">
        <h2>Data Murid</h2>
        <p>Kelola data dan akun murid sekolah</p>
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

        <button class="btn-share-murid" id="btnBagikanLinkMurid" title="Bagikan tautan pendaftaran murid">
            <i class="bi bi-share-fill"></i>
            <span class="btn-share-murid-label">Bagikan</span>
        </button>

        <button class="btn-add-murid" id="btnTambahMurid">
            <i class="bi bi-plus-lg"></i>
            Murid
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
     MODAL TAMBAH MURID (2-step)
================================================================ --}}
<div class="modal fade modal-dark" id="modalTambahMurid" tabindex="-1"
     aria-labelledby="modalTambahMuridLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahMuridLabel">
                    <i class="bi bi-person-plus-fill me-2" style="color:#4ade80;"></i>
                    Tambah Murid
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">

                {{-- Step Indicator --}}
                <div class="step-indicator">
                    <div class="step-item">
                        <div class="step-circle active" id="mStepCircle1">1</div>
                        <span class="step-label active" id="mStepLabel1">Data Murid</span>
                    </div>
                    <div class="step-line" id="mStepLine1"></div>
                    <div class="step-item">
                        <div class="step-circle" id="mStepCircle2">2</div>
                        <span class="step-label" id="mStepLabel2">Akun</span>
                    </div>
                </div>

                <form id="formTambahMurid" enctype="multipart/form-data" novalidate>
                    @csrf

                    {{-- STEP 1: Data Murid --}}
                    <div id="mStep1Content">

                        <div class="mb-3">
                            <label class="form-label-dark" for="mInputNama">
                                Nama Lengkap <span style="color:#f87171;">*</span>
                            </label>
                            <input type="text" id="mInputNama" name="name"
                                class="form-control-dark"
                                placeholder="Contoh: Ahmad Fauzi"
                                maxlength="255" required>
                            <div class="field-feedback" id="mFeedbackNama"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-dark" for="mInputEmail">
                                Alamat Email <span style="color:#f87171;">*</span>
                            </label>
                            <input type="email" id="mInputEmail" name="email"
                                class="form-control-dark"
                                placeholder="murid@sekolah.sch.id"
                                maxlength="255" required>
                            <div class="field-feedback" id="mFeedbackEmail"></div>
                        </div>

                        <div class="mb-1">
                            <label class="form-label-dark">
                                Foto Profil
                                <span style="color:rgba(255,255,255,0.35);">(opsional)</span>
                            </label>
                            <div class="foto-upload-area" id="mFotoUploadArea"
                                 role="button" aria-label="Klik untuk unggah foto">
                                <i class="bi bi-camera foto-upload-icon"></i>
                                <p class="foto-upload-text">
                                    <span>Klik untuk unggah</span> atau seret ke sini<br>
                                    <span style="color:rgba(255,255,255,0.35);font-size:0.72rem;">
                                        JPG, PNG, WEBP, maks. 2 MB
                                    </span>
                                </p>
                                <input type="file" id="mInputFoto" name="image"
                                    accept="image/jpeg,image/png,image/webp"
                                    style="display:none;">
                            </div>
                            <div class="text-center" id="mFotoPreviewWrap" style="display:none;">
                                <div class="foto-preview-wrap">
                                    <img src="" id="mFotoPreviewImg" class="foto-preview-img"
                                         alt="Preview foto profil">
                                    <button type="button" class="foto-preview-remove"
                                            id="mBtnHapusFoto" aria-label="Hapus foto">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="field-feedback" id="mFeedbackFoto"></div>
                        </div>

                    </div>
                    {{-- /mStep1Content --}}

                    {{-- STEP 2: Akun --}}
                    <div id="mStep2Content" style="display:none;">

                        <div class="mb-3">
                            <label class="form-label-dark" for="mInputUsername">
                                Username <span style="color:#f87171;">*</span>
                            </label>
                            <input type="text" id="mInputUsername" name="username"
                                class="form-control-dark"
                                placeholder="Contoh: ahmad_fauzi"
                                maxlength="60" autocomplete="off" required>
                            <div class="field-feedback" id="mFeedbackUsername"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-dark" for="mInputPassword">
                                Password <span style="color:#f87171;">*</span>
                            </label>
                            <div style="position:relative;">
                                <input type="password" id="mInputPassword" name="password"
                                    class="form-control-dark"
                                    placeholder="Min. 8 karakter (huruf + angka)"
                                    maxlength="128" autocomplete="new-password" required
                                    style="padding-right:2.6rem;">
                                <button type="button" id="mTogglePwd"
                                    aria-label="Tampilkan password"
                                    style="position:absolute;right:0.75rem;top:50%;
                                           transform:translateY(-50%);background:none;
                                           border:none;color:rgba(255,255,255,0.4);
                                           cursor:pointer;padding:0;line-height:1;">
                                    <i class="bi bi-eye" id="mTogglePwdIcon"></i>
                                </button>
                            </div>
                            <p class="pwd-hint">Minimal 8 karakter, kombinasi huruf dan angka.</p>
                            <div class="field-feedback" id="mFeedbackPassword"></div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label-dark" for="mInputPasswordConfirm">
                                Konfirmasi Password <span style="color:#f87171;">*</span>
                            </label>
                            <div style="position:relative;">
                                <input type="password" id="mInputPasswordConfirm"
                                    name="password_confirmation"
                                    class="form-control-dark"
                                    placeholder="Ulangi password"
                                    maxlength="128" autocomplete="new-password" required
                                    style="padding-right:2.6rem;">
                                <button type="button" id="mTogglePwdConfirm"
                                    aria-label="Tampilkan konfirmasi password"
                                    style="position:absolute;right:0.75rem;top:50%;
                                           transform:translateY(-50%);background:none;
                                           border:none;color:rgba(255,255,255,0.4);
                                           cursor:pointer;padding:0;line-height:1;">
                                    <i class="bi bi-eye" id="mTogglePwdConfirmIcon"></i>
                                </button>
                            </div>
                            <div class="field-feedback" id="mFeedbackPasswordConfirm"></div>
                        </div>

                    </div>
                    {{-- /mStep2Content --}}

                </form>

            </div>

            <div class="modal-footer justify-content-between">
                <button type="button" class="btn-modal-secondary"
                        id="mBtnStepBack" style="display:none;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </button>
                <div class="ms-auto d-flex gap-2">
                    <button type="button" class="btn-modal-secondary"
                            data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-modal-primary" id="mBtnStepNext">
                        Lanjut <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ================================================================
     MODAL EDIT MURID
================================================================ --}}
<div class="modal fade modal-dark" id="modalEditMurid" tabindex="-1"
     aria-labelledby="modalEditMuridLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalEditMuridLabel">
                    <i class="bi bi-pencil-fill me-2" style="color:#4ade80;"></i>
                    Edit Data Murid
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <form id="formEditMurid" enctype="multipart/form-data" novalidate>
                    @csrf
                    <input type="hidden" id="mEditMuridId">

                    <div class="mb-3">
                        <label class="form-label-dark" for="mEditNama">
                            Nama Lengkap <span style="color:#f87171;">*</span>
                        </label>
                        <input type="text" id="mEditNama" name="name"
                            class="form-control-dark" placeholder="Nama lengkap"
                            maxlength="255" required>
                        <div class="field-feedback" id="mFeedbackEditNama"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-dark" for="mEditEmail">
                            Alamat Email <span style="color:#f87171;">*</span>
                        </label>
                        <input type="email" id="mEditEmail" name="email"
                            class="form-control-dark" placeholder="Email"
                            maxlength="255" required>
                        <div class="field-feedback" id="mFeedbackEditEmail"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-dark" for="mEditUsername">
                            Username <span style="color:#f87171;">*</span>
                        </label>
                        <input type="text" id="mEditUsername" name="username"
                            class="form-control-dark" placeholder="Username"
                            maxlength="60" autocomplete="off" required>
                        <div class="field-feedback" id="mFeedbackEditUsername"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-dark">
                            Foto Profil
                            <span style="color:rgba(255,255,255,0.35);">
                                (opsional, kosongkan jika tidak diganti)
                            </span>
                        </label>
                        <div class="foto-upload-area" id="mEditFotoUploadArea" role="button">
                            <i class="bi bi-camera foto-upload-icon"></i>
                            <p class="foto-upload-text">
                                <span>Klik untuk unggah</span> atau seret ke sini
                            </p>
                            <input type="file" id="mEditInputFoto" name="image"
                                accept="image/jpeg,image/png,image/webp" style="display:none;">
                        </div>
                        <div class="text-center" id="mEditFotoPreviewWrap" style="display:none;">
                            <div class="foto-preview-wrap">
                                <img src="" id="mEditFotoPreviewImg" class="foto-preview-img"
                                     alt="Preview foto">
                                <button type="button" class="foto-preview-remove"
                                        id="mEditBtnHapusFoto" aria-label="Hapus foto">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-1">
                        <label class="form-label-dark" for="mEditPassword">
                            Password Baru
                            <span style="color:rgba(255,255,255,0.35);">
                                (kosongkan jika tidak diganti)
                            </span>
                        </label>
                        <input type="password" id="mEditPassword" name="password"
                            class="form-control-dark"
                            placeholder="Min. 8 karakter (huruf + angka)"
                            maxlength="128" autocomplete="new-password">
                        <div class="field-feedback" id="mFeedbackEditPassword"></div>
                    </div>

                </form>
            </div>

            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-modal-secondary"
                        data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-modal-primary" id="mBtnSimpanEdit">
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

    /* ============================================================
       STATE
    ============================================================ */
    var currentStep   = 1;
    var searchTimer   = null;
    var usernameTimer = null;
    var usernameOk    = false;
    var currentTab    = 'aktif';

    /* ============================================================
       HELPERS
    ============================================================ */
    function setFeedback(el, type, msg) {
        el.className  = 'field-feedback ' + type;
        el.textContent = msg;
    }

    function clearFeedback(el) {
        el.className  = 'field-feedback muted';
        el.textContent = '';
    }

    function setInputState(input, state) {
        input.classList.remove('is-valid', 'is-invalid');
        if (state) input.classList.add(state);
    }

    function escHtml(str) {
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    /* ============================================================
       LOAD LIST (AJAX)
    ============================================================ */
    function loadList(search, tab) {
        search     = search || '';
        tab        = tab    || currentTab;
        currentTab = tab;

        var container = document.getElementById('listContainer');
        container.innerHTML =
            '<div class="list-loading"><span class="spinner-green"></span> Memuat data...</div>';

        var url = '{{ route("admin.data_murid.list") }}'
                  + '?search=' + encodeURIComponent(search)
                  + '&tab='    + encodeURIComponent(tab);

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
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
    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchTimer);
        var val = this.value.trim();
        searchTimer = setTimeout(function () { loadList(val, currentTab); }, 350);
    });

    /* ============================================================
       BIND EVENTS PADA LIST
    ============================================================ */
    function bindListEvents() {
        var c = document.getElementById('listContainer');

        c.querySelectorAll('[data-tab]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                loadList(document.getElementById('searchInput').value.trim(),
                         this.dataset.tab);
            });
        });

        c.querySelectorAll('[data-edit-id]').forEach(function (btn) {
            btn.addEventListener('click', function () { openEditModal(this.dataset.editId); });
        });

        c.querySelectorAll('[data-delete-id]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                confirmHapus(this.dataset.deleteId, this.dataset.deleteNama);
            });
        });

        c.querySelectorAll('[data-restore-id]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                confirmRestore(this.dataset.restoreId, this.dataset.restoreNama);
            });
        });

        c.querySelectorAll('[data-force-id]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                confirmForceDelete(this.dataset.forceId, this.dataset.forceNama);
            });
        });
    }

    /* ============================================================
       MODAL TAMBAH: 2-STEP
    ============================================================ */
    var modalTambahEl = document.getElementById('modalTambahMurid');
    var modalTambah   = new bootstrap.Modal(modalTambahEl);

    document.getElementById('btnTambahMurid').addEventListener('click', function () {
        resetTambahForm();
        modalTambah.show();
    });

    function resetTambahForm() {
        currentStep = 1;
        document.getElementById('formTambahMurid').reset();
        removeFotoPreview();
        goToStep(1);
        clearAllFeedback();
        usernameOk = false;
    }

    function clearAllFeedback() {
        ['mFeedbackNama','mFeedbackEmail','mFeedbackFoto',
         'mFeedbackUsername','mFeedbackPassword','mFeedbackPasswordConfirm']
        .forEach(function (id) {
            var el = document.getElementById(id);
            if (el) clearFeedback(el);
        });
        ['mInputNama','mInputEmail','mInputUsername',
         'mInputPassword','mInputPasswordConfirm']
        .forEach(function (id) {
            var el = document.getElementById(id);
            if (el) setInputState(el, null);
        });
    }

    function goToStep(step) {
        currentStep = step;

        var s1   = document.getElementById('mStep1Content');
        var s2   = document.getElementById('mStep2Content');
        var c1   = document.getElementById('mStepCircle1');
        var c2   = document.getElementById('mStepCircle2');
        var l1   = document.getElementById('mStepLabel1');
        var l2   = document.getElementById('mStepLabel2');
        var ln   = document.getElementById('mStepLine1');
        var back = document.getElementById('mBtnStepBack');
        var next = document.getElementById('mBtnStepNext');

        if (step === 1) {
            s1.style.display = ''; s2.style.display = 'none';
            c1.className = 'step-circle active'; l1.className = 'step-label active';
            c2.className = 'step-circle';        l2.className = 'step-label';
            ln.className = 'step-line';
            back.style.display = 'none';
            next.innerHTML = 'Lanjut <i class="bi bi-arrow-right ms-1"></i>';
        } else {
            s1.style.display = 'none'; s2.style.display = '';
            c1.className = 'step-circle done';
            c1.innerHTML = '<i class="bi bi-check-lg"></i>';
            l1.className = 'step-label done';
            c2.className = 'step-circle active'; l2.className = 'step-label active';
            ln.className = 'step-line done';
            back.style.display = '';
            next.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';
        }
    }

    document.getElementById('mBtnStepBack').addEventListener('click', function () {
        if (currentStep > 1) goToStep(currentStep - 1);
    });

    document.getElementById('mBtnStepNext').addEventListener('click', function () {
        if (currentStep === 1) {
            if (validateStep1()) goToStep(2);
        } else {
            submitTambahMurid();
        }
    });

    /* ============================================================
       VALIDASI STEP 1
    ============================================================ */
    function validateStep1() {
        var ok    = true;
        var nama  = document.getElementById('mInputNama').value.trim();
        var email = document.getElementById('mInputEmail').value.trim();

        if (!nama) {
            setInputState(document.getElementById('mInputNama'), 'is-invalid');
            setFeedback(document.getElementById('mFeedbackNama'), 'invalid',
                        'Nama lengkap wajib diisi.');
            ok = false;
        } else {
            setInputState(document.getElementById('mInputNama'), 'is-valid');
            clearFeedback(document.getElementById('mFeedbackNama'));
        }

        var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRe.test(email)) {
            setInputState(document.getElementById('mInputEmail'), 'is-invalid');
            setFeedback(document.getElementById('mFeedbackEmail'), 'invalid',
                        'Masukkan alamat email yang valid.');
            ok = false;
        } else {
            setInputState(document.getElementById('mInputEmail'), 'is-valid');
            clearFeedback(document.getElementById('mFeedbackEmail'));
        }

        return ok;
    }

    /* ============================================================
       FOTO UPLOAD & PREVIEW (Tambah)
    ============================================================ */
    var fotoArea    = document.getElementById('mFotoUploadArea');
    var fotoInput   = document.getElementById('mInputFoto');
    var fotoPreview = document.getElementById('mFotoPreviewWrap');
    var fotoImg     = document.getElementById('mFotoPreviewImg');
    var fbFoto      = document.getElementById('mFeedbackFoto');

    fotoArea.addEventListener('click', function () { fotoInput.click(); });

    fotoArea.addEventListener('dragover', function (e) {
        e.preventDefault();
        fotoArea.style.borderColor = 'rgba(74,222,128,0.6)';
    });
    fotoArea.addEventListener('dragleave', function () { fotoArea.style.borderColor = ''; });
    fotoArea.addEventListener('drop', function (e) {
        e.preventDefault(); fotoArea.style.borderColor = '';
        if (e.dataTransfer.files[0])
            handleFotoFile(e.dataTransfer.files[0], fotoInput, fotoImg, fotoArea, fotoPreview, fbFoto);
    });
    fotoInput.addEventListener('change', function () {
        if (this.files[0])
            handleFotoFile(this.files[0], this, fotoImg, fotoArea, fotoPreview, fbFoto);
    });

    document.getElementById('mBtnHapusFoto').addEventListener('click', removeFotoPreview);

    function handleFotoFile(file, inputEl, imgEl, areaEl, previewWrap, feedback) {
        var allowed = ['image/jpeg','image/jpg','image/png','image/webp'];
        if (!allowed.includes(file.type)) {
            setFeedback(feedback, 'invalid', 'Format tidak didukung. Gunakan JPG, PNG, atau WEBP.');
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
        clearFeedback(fbFoto);
    }

    /* ============================================================
       USERNAME POLLING
    ============================================================ */
    document.getElementById('mInputUsername').addEventListener('input', function () {
        var val = this.value.trim();
        clearTimeout(usernameTimer);
        usernameOk = false;

        if (!val) {
            clearFeedback(document.getElementById('mFeedbackUsername'));
            setInputState(this, null);
            return;
        }

        var inputEl = this;
        setFeedback(document.getElementById('mFeedbackUsername'), 'muted', 'Memeriksa...');

        usernameTimer = setTimeout(function () {
            fetch('{{ route("admin.data_murid.check_username") }}'
                  + '?username=' + encodeURIComponent(val),
                  { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                usernameOk = data.available;
                if (data.available) {
                    setInputState(inputEl, 'is-valid');
                    setFeedback(document.getElementById('mFeedbackUsername'),
                                'valid', data.message);
                } else {
                    setInputState(inputEl, 'is-invalid');
                    setFeedback(document.getElementById('mFeedbackUsername'),
                                'invalid', data.message);
                }
            })
            .catch(function () {
                setFeedback(document.getElementById('mFeedbackUsername'),
                            'warning', 'Tidak dapat memeriksa username.');
            });
        }, 450);
    });

    /* ============================================================
       PASSWORD TOGGLE + VALIDASI REALTIME
    ============================================================ */
    document.getElementById('mTogglePwd').addEventListener('click', function () {
        var inp = document.getElementById('mInputPassword');
        var ico = document.getElementById('mTogglePwdIcon');
        if (inp.type === 'password') {
            inp.type = 'text'; ico.className = 'bi bi-eye-slash';
        } else {
            inp.type = 'password'; ico.className = 'bi bi-eye';
        }
    });

    document.getElementById('mTogglePwdConfirm').addEventListener('click', function () {
        var inp = document.getElementById('mInputPasswordConfirm');
        var ico = document.getElementById('mTogglePwdConfirmIcon');
        if (inp.type === 'password') {
            inp.type = 'text'; ico.className = 'bi bi-eye-slash';
        } else {
            inp.type = 'password'; ico.className = 'bi bi-eye';
        }
    });

    function validatePassword() {
        var val = document.getElementById('mInputPassword').value;
        var fb  = document.getElementById('mFeedbackPassword');
        var inp = document.getElementById('mInputPassword');

        if (!val) { clearFeedback(fb); setInputState(inp, null); return false; }

        if (val.length < 8) {
            setInputState(inp, 'is-invalid');
            setFeedback(fb, 'invalid', 'Password minimal 8 karakter.');
            return false;
        }
        if (!/[a-zA-Z]/.test(val) || !/\d/.test(val)) {
            setInputState(inp, 'is-invalid');
            setFeedback(fb, 'invalid', 'Password harus mengandung huruf dan angka.');
            return false;
        }
        setInputState(inp, 'is-valid'); clearFeedback(fb);
        return true;
    }

    function validatePasswordConfirm() {
        var pwd = document.getElementById('mInputPassword').value;
        var con = document.getElementById('mInputPasswordConfirm').value;
        var fb  = document.getElementById('mFeedbackPasswordConfirm');
        var inp = document.getElementById('mInputPasswordConfirm');

        if (!con) { clearFeedback(fb); setInputState(inp, null); return false; }
        if (pwd !== con) {
            setInputState(inp, 'is-invalid');
            setFeedback(fb, 'invalid', 'Password tidak cocok.');
            return false;
        }
        setInputState(inp, 'is-valid');
        setFeedback(fb, 'valid', 'Password cocok.');
        return true;
    }

    document.getElementById('mInputPassword').addEventListener('input', function () {
        validatePassword(); validatePasswordConfirm();
    });
    document.getElementById('mInputPasswordConfirm').addEventListener('input',
        validatePasswordConfirm);

    /* ============================================================
       SUBMIT TAMBAH MURID
    ============================================================ */
    function submitTambahMurid() {
        var uname  = document.getElementById('mInputUsername').value.trim();
        var pwdOk  = validatePassword();
        var confOk = validatePasswordConfirm();

        if (!uname) {
            setInputState(document.getElementById('mInputUsername'), 'is-invalid');
            setFeedback(document.getElementById('mFeedbackUsername'),
                        'invalid', 'Username wajib diisi.');
            return;
        }
        if (!usernameOk) {
            setInputState(document.getElementById('mInputUsername'), 'is-invalid');
            setFeedback(document.getElementById('mFeedbackUsername'),
                        'invalid', 'Periksa kembali username Anda.');
            return;
        }
        if (!pwdOk || !confOk) return;

        var btn = document.getElementById('mBtnStepNext');
        btn.disabled = true;
        btn.innerHTML =
            '<span class="spinner-green" style="width:16px;height:16px;border-width:2px;'
            + 'margin-right:6px;"></span> Menyimpan...';

        fetch('{{ route("admin.data_murid.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN':     CSRF,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new FormData(document.getElementById('formTambahMurid'))
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
                    text: data.message || 'Data murid berhasil ditambahkan.',
                    confirmButtonColor: '#22c55e',
                    background: '#0a2e1c',
                    color: '#ffffff',
                    timer: 2500,
                    showConfirmButton: false,
                }).then(function () {
                    loadList(document.getElementById('searchInput').value.trim(), currentTab);
                });
            } else {
                var msgs = [];
                if (data.errors)
                    Object.values(data.errors).forEach(function (arr) {
                        msgs = msgs.concat(arr);
                    });
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
                icon: 'error', title: 'Koneksi gagal',
                text: 'Tidak dapat terhubung ke server.',
                confirmButtonColor: '#22c55e', background: '#0a2e1c', color: '#ffffff',
            });
        });
    }

    /* ============================================================
       HAPUS (SOFTDELETE)
    ============================================================ */
    function confirmHapus(id, nama) {
        Swal.fire({
            icon: 'warning',
            title: 'Nonaktifkan murid?',
            html: 'Murid <strong>' + escHtml(nama) + '</strong> akan dipindahkan ke daftar tidak aktif.',
            showCancelButton: true,
            confirmButtonText: 'Ya, nonaktifkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f87171',
            cancelButtonColor: 'rgba(255,255,255,0.1)',
            background: '#0a2e1c',
            color: '#ffffff',
        }).then(function (result) {
            if (!result.isConfirmed) return;
            fetch('{{ url("admin/data-murid") }}/' + id, {
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
                        icon: 'success', title: 'Dinonaktifkan', text: data.message,
                        timer: 1800, showConfirmButton: false,
                        background: '#0a2e1c', color: '#ffffff',
                    }).then(function () {
                        loadList(document.getElementById('searchInput').value.trim(), currentTab);
                    });
                } else {
                    Swal.fire({ icon:'error', title:'Gagal', text: data.message,
                                background:'#0a2e1c', color:'#fff' });
                }
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
            html: 'Murid <strong>' + escHtml(nama) + '</strong> akan diaktifkan kembali.',
            showCancelButton: true,
            confirmButtonText: 'Ya, aktifkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#22c55e',
            cancelButtonColor: 'rgba(255,255,255,0.1)',
            background: '#0a2e1c',
            color: '#ffffff',
        }).then(function (result) {
            if (!result.isConfirmed) return;
            fetch('{{ url("admin/data-murid") }}/' + id + '/restore', {
                method: 'POST',
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
                        icon: 'success', title: 'Diaktifkan', text: data.message,
                        timer: 1800, showConfirmButton: false,
                        background: '#0a2e1c', color: '#ffffff',
                    }).then(function () {
                        loadList(document.getElementById('searchInput').value.trim(), currentTab);
                    });
                } else {
                    Swal.fire({ icon:'error', title:'Gagal', text: data.message,
                                background:'#0a2e1c', color:'#fff' });
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
            html: 'Data murid <strong>' + escHtml(nama)
                  + '</strong> akan dihapus selamanya dan tidak dapat dipulihkan.',
            showCancelButton: true,
            confirmButtonText: 'Hapus permanen',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#f87171',
            cancelButtonColor: 'rgba(255,255,255,0.1)',
            background: '#0a2e1c',
            color: '#ffffff',
        }).then(function (result) {
            if (!result.isConfirmed) return;
            fetch('{{ url("admin/data-murid") }}/' + id + '/force', {
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
                    }).then(function () {
                        loadList(document.getElementById('searchInput').value.trim(), 'nonaktif');
                    });
                } else {
                    Swal.fire({ icon:'error', title:'Gagal', text: data.message,
                                background:'#0a2e1c', color:'#fff' });
                }
            });
        });
    }

    /* ============================================================
       MODAL EDIT
    ============================================================ */
    var modalEditEl = document.getElementById('modalEditMurid');
    var modalEdit   = new bootstrap.Modal(modalEditEl);

    function openEditModal(id) {
        fetch('{{ url("admin/data-murid") }}/' + id + '/edit', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            document.getElementById('mEditMuridId').value  = data.id;
            document.getElementById('mEditNama').value     = data.name;
            document.getElementById('mEditEmail').value    = data.email;
            document.getElementById('mEditUsername').value = data.username;
            document.getElementById('mEditPassword').value = '';

            var editFotoInput   = document.getElementById('mEditInputFoto');
            var editFotoArea    = document.getElementById('mEditFotoUploadArea');
            var editFotoPreview = document.getElementById('mEditFotoPreviewWrap');
            var editFotoImg     = document.getElementById('mEditFotoPreviewImg');
            editFotoInput.value = '';

            if (data.image) {
                editFotoImg.src            = data.image;
                editFotoArea.style.display   = 'none';
                editFotoPreview.style.display = '';
            } else {
                editFotoArea.style.display   = '';
                editFotoPreview.style.display = 'none';
            }

            ['mFeedbackEditNama','mFeedbackEditEmail',
             'mFeedbackEditUsername','mFeedbackEditPassword'].forEach(function (fid) {
                clearFeedback(document.getElementById(fid));
            });
            ['mEditNama','mEditEmail','mEditUsername','mEditPassword'].forEach(function (fid) {
                setInputState(document.getElementById(fid), null);
            });

            modalEdit.show();
        })
        .catch(function () {
            Swal.fire({ icon:'error', title:'Gagal',
                        text:'Tidak dapat memuat data murid.',
                        background:'#0a2e1c', color:'#fff' });
        });
    }

    /* Foto edit */
    var mEditFotoArea  = document.getElementById('mEditFotoUploadArea');
    var mEditFotoInput = document.getElementById('mEditInputFoto');

    mEditFotoArea.addEventListener('click', function () { mEditFotoInput.click(); });

    mEditFotoInput.addEventListener('change', function () {
        if (this.files[0]) {
            var fb = document.getElementById('mFeedbackEditPassword');
            handleFotoFile(
                this.files[0], this,
                document.getElementById('mEditFotoPreviewImg'),
                mEditFotoArea,
                document.getElementById('mEditFotoPreviewWrap'),
                fb
            );
        }
    });

    document.getElementById('mEditBtnHapusFoto').addEventListener('click', function () {
        mEditFotoInput.value = '';
        document.getElementById('mEditFotoPreviewImg').src = '';
        mEditFotoArea.style.display = '';
        document.getElementById('mEditFotoPreviewWrap').style.display = 'none';
    });

    /* Submit edit */
    document.getElementById('mBtnSimpanEdit').addEventListener('click', function () {
        var id    = document.getElementById('mEditMuridId').value;
        var nama  = document.getElementById('mEditNama').value.trim();
        var email = document.getElementById('mEditEmail').value.trim();
        var uname = document.getElementById('mEditUsername').value.trim();
        var ok    = true;

        if (!nama) {
            setInputState(document.getElementById('mEditNama'), 'is-invalid');
            setFeedback(document.getElementById('mFeedbackEditNama'), 'invalid', 'Nama wajib diisi.');
            ok = false;
        } else {
            setInputState(document.getElementById('mEditNama'), 'is-valid');
            clearFeedback(document.getElementById('mFeedbackEditNama'));
        }

        var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRe.test(email)) {
            setInputState(document.getElementById('mEditEmail'), 'is-invalid');
            setFeedback(document.getElementById('mFeedbackEditEmail'), 'invalid', 'Email tidak valid.');
            ok = false;
        } else {
            setInputState(document.getElementById('mEditEmail'), 'is-valid');
            clearFeedback(document.getElementById('mFeedbackEditEmail'));
        }

        if (!uname) {
            setInputState(document.getElementById('mEditUsername'), 'is-invalid');
            setFeedback(document.getElementById('mFeedbackEditUsername'), 'invalid', 'Username wajib diisi.');
            ok = false;
        } else {
            setInputState(document.getElementById('mEditUsername'), 'is-valid');
            clearFeedback(document.getElementById('mFeedbackEditUsername'));
        }

        if (!ok) return;

        var btn = this;
        btn.disabled = true;
        btn.innerHTML =
            '<span class="spinner-green" style="width:16px;height:16px;border-width:2px;'
            + 'margin-right:6px;"></span> Menyimpan...';

        var fd = new FormData(document.getElementById('formEditMurid'));
        fd.append('_method', 'POST');

        fetch('{{ url("admin/data-murid") }}/' + id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN':     CSRF,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: fd
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Simpan';

            if (data.success) {
                modalEdit.hide();
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: data.message,
                    timer: 2000, showConfirmButton: false,
                    background: '#0a2e1c', color: '#ffffff',
                }).then(function () {
                    loadList(document.getElementById('searchInput').value.trim(), currentTab);
                });
            } else {
                var msgs = [];
                if (data.errors)
                    Object.values(data.errors).forEach(function (arr) { msgs = msgs.concat(arr); });
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
            Swal.fire({ icon:'error', title:'Koneksi gagal',
                        text:'Tidak dapat terhubung ke server.',
                        background:'#0a2e1c', color:'#fff' });
        });
    });

    /* ============================================================
       TOMBOL BAGIKAN TAUTAN REGISTRASI MURID
    ============================================================ */
    var REGISTER_MURID_URL = '{{ route("register.murid") }}';

    document.getElementById('btnBagikanLinkMurid').addEventListener('click', function () {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(REGISTER_MURID_URL)
                .then(showCopiedAlert)
                .catch(function () { fallbackCopy(REGISTER_MURID_URL); });
        } else {
            fallbackCopy(REGISTER_MURID_URL);
        }
    });

    function fallbackCopy(text) {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.cssText = 'position:fixed;left:-9999px;top:-9999px;opacity:0;';
        document.body.appendChild(ta);
        ta.focus(); ta.select();
        try { document.execCommand('copy'); showCopiedAlert(); } catch (e) {
            Swal.fire({
                icon: 'info',
                title: 'Tautan Pendaftaran Murid',
                html: '<div style="word-break:break-all;font-size:0.82rem;background:rgba(255,255,255,0.06);'
                    + 'padding:0.65rem 0.85rem;border-radius:8px;color:#4ade80;'
                    + 'border:1px solid rgba(74,222,128,0.2);">' + text + '</div>'
                    + '<p style="font-size:0.78rem;color:rgba(255,255,255,0.5);margin-top:0.75rem;margin-bottom:0;">'
                    + 'Salin tautan di atas dan bagikan kepada murid.</p>',
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
            html: '<p style="font-size:0.85rem;color:rgba(255,255,255,0.65);margin-bottom:0.5rem;">'
                + 'Bagikan tautan berikut kepada murid agar dapat mendaftar secara mandiri:</p>'
                + '<div style="word-break:break-all;font-size:0.78rem;background:rgba(255,255,255,0.06);'
                + 'padding:0.55rem 0.8rem;border-radius:8px;color:#4ade80;'
                + 'border:1px solid rgba(74,222,128,0.18);">' + REGISTER_MURID_URL + '</div>',
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

}());
</script>
@endpush
