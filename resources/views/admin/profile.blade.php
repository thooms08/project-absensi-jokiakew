@extends('admin.layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@push('styles')
<style>
/* ================================================================
   LAYOUT
================================================================ */
.profile-grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 1.5rem;
    align-items: start;
}
@media (max-width: 860px) {
    .profile-grid { grid-template-columns: 1fr; }
}

/* ================================================================
   CARD BASE
================================================================ */
.p-card {
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(74,222,128,.13);
    border-radius: 18px 0 18px 0;
    overflow: hidden;
}

.p-card-header {
    display: flex; align-items: center; gap: .6rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid rgba(74,222,128,.1);
    font-size: .85rem; font-weight: 700; color: #fff;
    background: rgba(255,255,255,.025);
}
.p-card-header i { color: #4ade80; font-size: 1rem; }
.p-card-body { padding: 1.25rem; }

/* ================================================================
   KOLOM KIRI — avatar + info + tombol sekolah
================================================================ */
.avatar-wrap {
    display: flex; flex-direction: column;
    align-items: center; gap: 1rem;
    padding: 1.5rem 1.25rem 1.25rem;
    text-align: center;
}

.avatar-ring {
    position: relative;
    width: 108px; height: 108px;
}

.avatar-img,
.avatar-initials {
    width: 108px; height: 108px; border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(74,222,128,.35);
    display: flex; align-items: center; justify-content: center;
}
.avatar-initials {
    background: linear-gradient(135deg,#0a7c4a,#4ade80);
    font-size: 2.2rem; font-weight: 800; color: #064e2e;
}

.avatar-edit-btn {
    position: absolute; bottom: 2px; right: 2px;
    width: 30px; height: 30px; border-radius: 50%;
    background: #0a7c4a; border: 2px solid #062015;
    color: #fff; font-size: .8rem;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: background .18s;
}
.avatar-edit-btn:hover { background: #22c55e; }

.admin-name {
    font-size: 1rem; font-weight: 700; color: #fff;
    margin: 0 0 .2rem;
}
.admin-role {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .68rem; font-weight: 700; letter-spacing: .06em;
    text-transform: uppercase;
    background: rgba(74,222,128,.12); color: #4ade80;
    border: 1px solid rgba(74,222,128,.25);
    border-radius: 50px; padding: .18rem .65rem;
}
.admin-email {
    font-size: .78rem; color: rgba(255,255,255,.4);
    margin: .25rem 0 0; word-break: break-all;
}

.btn-sekolah {
    display: inline-flex; align-items: center; gap: .5rem;
    width: 100%;
    background: rgba(251,191,36,.08);
    border: 1px solid rgba(251,191,36,.25);
    border-radius: 12px;
    color: #fbbf24; font-size: .82rem; font-weight: 600;
    padding: .65rem 1rem; cursor: pointer;
    font-family: 'Inter', sans-serif;
    transition: background .18s, border-color .18s;
    justify-content: center;
    margin-top: .25rem;
}
.btn-sekolah:hover { background: rgba(251,191,36,.16); border-color: rgba(251,191,36,.45); }

/* ================================================================
   FORM PROFILE
================================================================ */
.f-group { margin-bottom: 1.1rem; }

.f-label {
    display: block; font-size: .75rem; font-weight: 600;
    color: rgba(255,255,255,.55); margin-bottom: .38rem;
}
.f-label .req { color: #f87171; }

.f-input {
    width: 100%;
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(74,222,128,.18);
    border-radius: 10px;
    color: #e2f5ea; font-size: .875rem;
    padding: .55rem .85rem;
    outline: none; font-family: 'Inter', sans-serif;
    transition: border-color .18s, background .18s;
}
.f-input::placeholder { color: rgba(255,255,255,.22); }
.f-input:focus {
    border-color: rgba(74,222,128,.5);
    background: rgba(255,255,255,.09);
    box-shadow: 0 0 0 3px rgba(74,222,128,.07);
}
.f-input.is-valid   { border-color: rgba(74,222,128,.6); }
.f-input.is-invalid { border-color: rgba(248,113,113,.6); }

.f-input-wrap { position: relative; }
.f-eye-btn {
    position: absolute; right: .75rem; top: 50%; transform: translateY(-50%);
    background: none; border: none; color: rgba(255,255,255,.35);
    cursor: pointer; padding: 0; line-height: 1;
    transition: color .18s;
}
.f-eye-btn:hover { color: rgba(255,255,255,.7); }

.f-feedback {
    font-size: .72rem; margin-top: .3rem; min-height: 1rem;
    display: flex; align-items: center; gap: .3rem;
}
.f-feedback.ok   { color: #4ade80; }
.f-feedback.err  { color: #f87171; }
.f-feedback.muted{ color: rgba(255,255,255,.3); }

/* Password strength bar */
.pwd-bar-wrap {
    height: 4px; border-radius: 3px;
    background: rgba(255,255,255,.08);
    margin-top: .45rem; overflow: hidden;
}
.pwd-bar-fill {
    height: 100%; border-radius: 3px; width: 0;
    transition: width .3s, background .3s;
}

.divider {
    border: none;
    border-top: 1px solid rgba(74,222,128,.1);
    margin: 1.25rem 0;
}

/* Submit btn */
.btn-save {
    display: inline-flex; align-items: center; gap: .5rem;
    background: linear-gradient(135deg,#0a7c4a,#22c55e);
    border: none; border-radius: 10px; color: #fff;
    font-size: .875rem; font-weight: 600;
    padding: .6rem 1.5rem; cursor: pointer;
    font-family: 'Inter', sans-serif;
    transition: opacity .18s, transform .18s;
}
.btn-save:hover { opacity: .88; transform: translateY(-1px); }
.btn-save:disabled { opacity: .55; cursor: not-allowed; transform: none; }

.spinner-sm {
    width: 15px; height: 15px;
    border: 2px solid rgba(255,255,255,.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .6s linear infinite;
    display: inline-block;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ================================================================
   MODAL PROFIL SEKOLAH
================================================================ */
.modal-sekolah .modal-content {
    background: #0a2e1c;
    border: 1px solid rgba(251,191,36,.22);
    border-radius: 18px; color: #e2f5ea;
}
.modal-sekolah .modal-header {
    border-bottom: 1px solid rgba(251,191,36,.12);
    padding: 1.1rem 1.4rem 1rem;
}
.modal-sekolah .modal-title { font-size: 1rem; font-weight: 700; color: #fff; }
.modal-sekolah .modal-footer { border-top: 1px solid rgba(251,191,36,.12); padding: .9rem 1.4rem; }
.modal-sekolah .btn-close { filter: invert(1) brightness(.7); }
.modal-sekolah .modal-body { padding: 1.25rem 1.4rem; }

/* Input sekolah pakai aksen kuning */
.f-input-blue {
    width: 100%;
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(251,191,36,.22);
    border-radius: 10px;
    color: #e2f5ea; font-size: .875rem;
    padding: .55rem .85rem;
    outline: none; font-family: 'Inter', sans-serif;
    transition: border-color .18s;
}
.f-input-blue::placeholder { color: rgba(255,255,255,.22); }
.f-input-blue:focus { border-color: rgba(251,191,36,.6); box-shadow: 0 0 0 3px rgba(251,191,36,.07); }

/* Logo upload/preview */
.logo-upload-area {
    border: 2px dashed rgba(251,191,36,.3);
    border-radius: 12px;
    padding: 1.5rem 1rem;
    text-align: center; cursor: pointer;
    transition: border-color .18s, background .18s;
    background: rgba(251,191,36,.03);
}
.logo-upload-area:hover { border-color: rgba(251,191,36,.65); background: rgba(251,191,36,.06); }
.logo-upload-icon { font-size: 2rem; color: rgba(251,191,36,.45); display: block; margin-bottom: .5rem; }
.logo-upload-text { font-size: .8rem; color: rgba(255,255,255,.4); margin: 0; }
.logo-upload-text span { color: #fbbf24; font-weight: 600; }

.logo-preview-wrap {
    position: relative; display: inline-block; margin-top: .75rem;
}
.logo-preview-img {
    max-height: 120px; max-width: 100%; border-radius: 10px;
    border: 1px solid rgba(251,191,36,.25);
    background: #fff; padding: 6px; display: block; margin: 0 auto;
}
.logo-remove-btn {
    position: absolute; top: -8px; right: -8px;
    width: 22px; height: 22px; border-radius: 50%;
    background: #f87171; border: 2px solid #0a2e1c;
    color: #fff; font-size: .65rem;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; line-height: 1;
}

.btn-modal-primary {
    background: linear-gradient(135deg,#b45309,#fbbf24);
    border: none; border-radius: 10px; color: #422006;
    font-size: .875rem; font-weight: 700;
    padding: .55rem 1.35rem; cursor: pointer;
    font-family: 'Inter', sans-serif;
    transition: opacity .18s;
    display: inline-flex; align-items: center; gap: .4rem;
}
.btn-modal-primary:hover { opacity: .88; }
.btn-modal-primary:disabled { opacity: .55; cursor: not-allowed; }
.btn-modal-secondary {
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 10px; color: rgba(255,255,255,.72);
    font-size: .875rem; font-weight: 500;
    padding: .55rem 1.2rem; cursor: pointer;
    font-family: 'Inter', sans-serif;
}
.btn-modal-secondary:hover { background: rgba(255,255,255,.12); }
</style>
@endpush

@section('content')

{{-- ================================================================
     PAGE HEADER
================================================================ --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;margin-bottom:1.5rem;">
    <div>
        <h2 style="font-size:1.15rem;font-weight:700;color:#fff;margin:0 0 .2rem;">Profil Saya</h2>
        <p style="font-size:.8rem;color:rgba(255,255,255,.45);margin:0;">Kelola informasi akun dan profil sekolah</p>
    </div>
</div>

{{-- ================================================================
     GRID UTAMA
================================================================ --}}
<div class="profile-grid">

    {{-- ── KOLOM KIRI ── --}}
    <div>
        <div class="p-card">
            <div class="avatar-wrap">

                {{-- Avatar --}}
                <div class="avatar-ring">
                    @if(Auth::user()->image)
                        <img
                            id="avatarImg"
                            src="{{ route('admin.profile.foto', ['filename' => basename(Auth::user()->image)]) }}"
                            alt="Foto {{ Auth::user()->name }}"
                            class="avatar-img"
                        >
                    @else
                        <div class="avatar-initials" id="avatarInitials">
                            {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <img id="avatarImg" src="" alt="" class="avatar-img" style="display:none;">
                    @endif
                    <button class="avatar-edit-btn" id="btnEditAvatar" title="Ganti foto" aria-label="Ganti foto profil">
                        <i class="bi bi-camera-fill"></i>
                    </button>
                    <input type="file" id="inputAvatar" accept="image/jpeg,image/png,image/webp" style="display:none;">
                </div>

                <div>
                    <p class="admin-name" id="displayName">{{ Auth::user()->name }}</p>
                    <span class="admin-role"><i class="bi bi-shield-fill-check"></i> Administrator</span>
                    <p class="admin-email">{{ Auth::user()->email }}</p>
                </div>

                {{-- Tombol Profil Sekolah --}}
                <button type="button" class="btn-sekolah" id="btnProfilSekolah">
                    <i class="bi bi-building-fill"></i>
                    Profil Sekolah
                </button>

            </div>
        </div>
    </div>

    {{-- ── KOLOM KANAN: FORM PROFILE ── --}}
    <div>
        <div class="p-card">
            <div class="p-card-header">
                <i class="bi bi-person-fill-gear"></i>
                Informasi Akun
            </div>
            <div class="p-card-body">
                <form id="formProfile" novalidate>
                    @csrf

                    {{-- Nama --}}
                    <div class="f-group">
                        <label class="f-label" for="inputName">Nama Lengkap <span class="req">*</span></label>
                        <input type="text" id="inputName" name="name" class="f-input"
                            value="{{ Auth::user()->name }}"
                            placeholder="Nama lengkap" maxlength="255" required>
                        <div class="f-feedback muted" id="fbName"></div>
                    </div>

                    {{-- Username --}}
                    <div class="f-group">
                        <label class="f-label" for="inputUsername">Username <span class="req">*</span></label>
                        <input type="text" id="inputUsername" name="username" class="f-input"
                            value="{{ Auth::user()->username }}"
                            placeholder="username" maxlength="60" autocomplete="off" required>
                        <div class="f-feedback muted" id="fbUsername"></div>
                    </div>

                    {{-- Email --}}
                    <div class="f-group">
                        <label class="f-label" for="inputEmail">Alamat Email <span class="req">*</span></label>
                        <input type="email" id="inputEmail" name="email" class="f-input"
                            value="{{ Auth::user()->email }}"
                            placeholder="admin@sekolah.sch.id" maxlength="255" required>
                        <div class="f-feedback muted" id="fbEmail"></div>
                    </div>

                    <hr class="divider">

                    <p style="font-size:.75rem;font-weight:700;color:rgba(74,222,128,.65);text-transform:uppercase;letter-spacing:.07em;margin-bottom:.85rem;">
                        <i class="bi bi-lock-fill me-1"></i>Ganti Password
                    </p>

                    {{-- Password baru --}}
                    <div class="f-group">
                        <label class="f-label" for="inputPassword">
                            Password Baru
                            <span style="color:rgba(255,255,255,.3);font-weight:400;">(kosongkan jika tidak ingin ganti)</span>
                        </label>
                        <div class="f-input-wrap">
                            <input type="password" id="inputPassword" name="password" class="f-input"
                                placeholder="Min. 8 karakter (huruf + angka)"
                                maxlength="128" autocomplete="new-password"
                                style="padding-right:2.6rem;">
                            <button type="button" class="f-eye-btn" id="togglePwd" aria-label="Tampilkan password">
                                <i class="bi bi-eye" id="iconPwd"></i>
                            </button>
                        </div>
                        <div class="pwd-bar-wrap"><div class="pwd-bar-fill" id="pwdBar"></div></div>
                        <div class="f-feedback muted" id="fbPassword"></div>
                    </div>

                    {{-- Konfirmasi password --}}
                    <div class="f-group">
                        <label class="f-label" for="inputPasswordConfirm">Konfirmasi Password Baru</label>
                        <div class="f-input-wrap">
                            <input type="password" id="inputPasswordConfirm" name="password_confirmation" class="f-input"
                                placeholder="Ulangi password baru"
                                maxlength="128" autocomplete="new-password"
                                style="padding-right:2.6rem;">
                            <button type="button" class="f-eye-btn" id="togglePwdConfirm" aria-label="Tampilkan konfirmasi password">
                                <i class="bi bi-eye" id="iconPwdConfirm"></i>
                            </button>
                        </div>
                        <div class="f-feedback muted" id="fbPasswordConfirm"></div>
                    </div>

                    <div style="display:flex;justify-content:flex-end;margin-top:1.25rem;">
                        <button type="button" class="btn-save" id="btnSimpanProfile">
                            <i class="bi bi-check-lg"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- ================================================================
     MODAL PROFIL SEKOLAH
================================================================ --}}
<div class="modal fade modal-sekolah" id="modalSekolah" tabindex="-1" aria-hidden="true" aria-labelledby="modalSekolahTitle">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalSekolahTitle">
                    <i class="bi bi-building-fill me-2" style="color:#fbbf24;"></i>
                    Profil Sekolah
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form id="formSekolah" novalidate>
                    @csrf

                    {{-- Logo --}}
                    <div class="f-group">
                        <label class="f-label">Logo Sekolah <span style="color:rgba(255,255,255,.3);font-weight:400;">(opsional)</span></label>

                        {{-- Area upload (ditampilkan jika belum ada logo) --}}
                        <div class="logo-upload-area" id="logoUploadArea">
                            <i class="bi bi-image logo-upload-icon"></i>
                            <p class="logo-upload-text">
                                <span>Klik untuk upload</span> atau seret ke sini<br>
                                <span style="color:rgba(255,255,255,.3);font-size:.72rem;">JPG, PNG, WEBP · maks. 2 MB</span>
                            </p>
                            <input type="file" id="inputLogo" accept="image/jpeg,image/png,image/webp" style="display:none;">
                        </div>

                        {{-- Preview --}}
                        <div id="logoPreviewWrap" style="display:none;text-align:center;margin-top:.75rem;">
                            <div class="logo-preview-wrap">
                                <img src="" id="logoPreviewImg" class="logo-preview-img" alt="Preview logo">
                                <button type="button" class="logo-remove-btn" id="btnHapusLogo" aria-label="Hapus logo">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>

                        <input type="hidden" id="logoBase64" name="logo_base64">
                        <div class="f-feedback muted" id="fbLogo"></div>
                    </div>

                    {{-- Nama Sekolah --}}
                    <div class="f-group">
                        <label class="f-label" for="inputNamaSekolah">
                            Nama Sekolah <span class="req">*</span>
                        </label>
                        <input type="text" id="inputNamaSekolah" name="nama_sekolah" class="f-input-blue"
                            value="{{ $sekolah->nama_sekolah ?? '' }}"
                            placeholder="Contoh: SMKS Ibnu Sina" maxlength="255" required>
                        <div class="f-feedback muted" id="fbNamaSekolah"></div>
                    </div>

                </form>
            </div>
            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-modal-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-modal-primary" id="btnSimpanSekolah">
                    <i class="bi bi-check-lg"></i> Simpan
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

    var CSRF         = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
    var UPDATE_URL   = '{{ route("admin.profile.update") }}';
    var SEKOLAH_URL  = '{{ route("admin.profile.sekolah") }}';

    /* ================================================================
       HELPERS
    ================================================================ */
    function setFb(id, type, msg) {
        var el = document.getElementById(id);
        if (!el) return;
        el.className = 'f-feedback ' + type;
        el.innerHTML = msg
            ? (type === 'ok'  ? '<i class="bi bi-check-circle-fill"></i>' :
               type === 'err' ? '<i class="bi bi-exclamation-circle-fill"></i>' : '')
              + ' ' + msg
            : '';
    }
    function clearFb(id) { setFb(id, 'muted', ''); }

    function setValid(inputId, valid) {
        var el = document.getElementById(inputId);
        if (!el) return;
        el.classList.remove('is-valid', 'is-invalid');
        if (valid === true)  el.classList.add('is-valid');
        if (valid === false) el.classList.add('is-invalid');
    }

    /* ================================================================
       TOGGLE PASSWORD VISIBILITY
    ================================================================ */
    function bindEye(btnId, iconId, inputId) {
        document.getElementById(btnId).addEventListener('click', function () {
            var inp  = document.getElementById(inputId);
            var icon = document.getElementById(iconId);
            var show = inp.type === 'password';
            inp.type = show ? 'text' : 'password';
            icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    }
    bindEye('togglePwd',        'iconPwd',        'inputPassword');
    bindEye('togglePwdConfirm', 'iconPwdConfirm', 'inputPasswordConfirm');

    /* ================================================================
       PASSWORD STRENGTH BAR
    ================================================================ */
    document.getElementById('inputPassword').addEventListener('input', function () {
        var v = this.value;
        var score = 0;
        if (v.length >= 8)          score++;
        if (/[A-Z]/.test(v))        score++;
        if (/[0-9]/.test(v))        score++;
        if (/[^a-zA-Z0-9]/.test(v)) score++;

        var bar = document.getElementById('pwdBar');
        var pct = ['0%','30%','55%','80%','100%'][score];
        var clr = ['','#f87171','#fbbf24','#4ade80','#22c55e'][score];
        bar.style.width      = pct;
        bar.style.background = clr;

        /* Bersihkan feedback konfirmasi jika password kosong */
        if (!v) {
            setValid('inputPasswordConfirm', null);
            clearFb('fbPasswordConfirm');
            clearFb('fbPassword');
        }

        /* Real-time check konfirmasi */
        checkPasswordMatch();
    });

    /* ================================================================
       REAL-TIME KONFIRMASI PASSWORD
    ================================================================ */
    function checkPasswordMatch() {
        var pwd     = document.getElementById('inputPassword').value;
        var confirm = document.getElementById('inputPasswordConfirm').value;

        if (!pwd && !confirm) {
            setValid('inputPassword', null);
            setValid('inputPasswordConfirm', null);
            clearFb('fbPassword');
            clearFb('fbPasswordConfirm');
            return true;
        }

        if (pwd && pwd.length < 8) {
            setValid('inputPassword', false);
            setFb('fbPassword', 'err', 'Password minimal 8 karakter dan harus mengandung angka.');
        } else if (pwd) {
            setValid('inputPassword', true);
            clearFb('fbPassword');
        }

        if (!confirm) {
            setValid('inputPasswordConfirm', null);
            clearFb('fbPasswordConfirm');
            return false;
        }

        if (pwd === confirm) {
            setValid('inputPasswordConfirm', true);
            setFb('fbPasswordConfirm', 'ok', 'Password cocok.');
            return true;
        } else {
            setValid('inputPasswordConfirm', false);
            setFb('fbPasswordConfirm', 'err', 'Password tidak cocok.');
            return false;
        }
    }

    document.getElementById('inputPasswordConfirm').addEventListener('input', checkPasswordMatch);

    /* ================================================================
       UPLOAD FOTO AVATAR
    ================================================================ */
    var fotoBase64 = null;

    document.getElementById('btnEditAvatar').addEventListener('click', function () {
        document.getElementById('inputAvatar').click();
    });

    document.getElementById('inputAvatar').addEventListener('change', function () {
        var file = this.files[0];
        if (!file) return;

        if (file.size > 2 * 1024 * 1024) {
            Swal.fire({ icon:'warning', title:'Ukuran terlalu besar', text:'Maksimum 2 MB.',
                background:'#0a2e1c', color:'#fff' });
            return;
        }

        var reader = new FileReader();
        reader.onload = function (e) {
            fotoBase64 = e.target.result;

            /* Tampilkan preview avatar */
            var imgEl       = document.getElementById('avatarImg');
            var initialsEl  = document.getElementById('avatarInitials');
            imgEl.src       = fotoBase64;
            imgEl.style.display = '';
            if (initialsEl) initialsEl.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

    /* ================================================================
       SUBMIT FORM PROFILE
    ================================================================ */
    document.getElementById('btnSimpanProfile').addEventListener('click', function () {
        var btn   = this;
        var name  = document.getElementById('inputName').value.trim();
        var email = document.getElementById('inputEmail').value.trim();
        var uname = document.getElementById('inputUsername').value.trim();
        var pwd   = document.getElementById('inputPassword').value;
        var cpwd  = document.getElementById('inputPasswordConfirm').value;
        var ok    = true;

        /* Validasi nama */
        if (!name) {
            setValid('inputName', false);
            setFb('fbName', 'err', 'Nama wajib diisi.');
            ok = false;
        } else {
            setValid('inputName', true); clearFb('fbName');
        }

        /* Validasi email */
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            setValid('inputEmail', false);
            setFb('fbEmail', 'err', 'Alamat email tidak valid.');
            ok = false;
        } else {
            setValid('inputEmail', true); clearFb('fbEmail');
        }

        /* Validasi username */
        if (!uname) {
            setValid('inputUsername', false);
            setFb('fbUsername', 'err', 'Username wajib diisi.');
            ok = false;
        } else if (!/^[a-zA-Z0-9_.\-]+$/.test(uname)) {
            setValid('inputUsername', false);
            setFb('fbUsername', 'err', 'Username hanya boleh huruf, angka, titik, underscore, dash.');
            ok = false;
        } else {
            setValid('inputUsername', true); clearFb('fbUsername');
        }

        /* Validasi password (jika diisi) */
        if (pwd || cpwd) {
            if (pwd.length < 8 || !/[0-9]/.test(pwd)) {
                setValid('inputPassword', false);
                setFb('fbPassword', 'err', 'Password minimal 8 karakter dan harus mengandung angka.');
                ok = false;
            }
            if (pwd !== cpwd) {
                setValid('inputPasswordConfirm', false);
                setFb('fbPasswordConfirm', 'err', 'Password tidak cocok.');
                ok = false;
            }
        }

        if (!ok) return;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-sm"></span> Menyimpan...';

        var payload = {
            name      : name,
            email     : email,
            username  : uname,
            foto_base64: fotoBase64 || '',
        };
        if (pwd) {
            payload.password              = pwd;
            payload.password_confirmation = cpwd;
        }

        fetch(UPDATE_URL, {
            method : 'POST',
            headers: {
                'X-CSRF-TOKEN'    : CSRF,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type'    : 'application/json',
            },
            body: JSON.stringify(payload),
        })
        .then(function (r) { return r.json().then(function (d) { return { st: r.status, d: d }; }); })
        .then(function (res) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg"></i> Simpan Perubahan';

            if (res.d.success) {
                /* Update nama di sidebar/topbar bila ada */
                document.getElementById('displayName').textContent = name;

                /* Reset password fields */
                document.getElementById('inputPassword').value        = '';
                document.getElementById('inputPasswordConfirm').value = '';
                document.getElementById('pwdBar').style.width = '0';
                ['inputPassword','inputPasswordConfirm'].forEach(function(id){ setValid(id, null); });
                clearFb('fbPassword'); clearFb('fbPasswordConfirm');
                fotoBase64 = null;

                /* Update src avatar jika ada foto baru */
                if (res.d.foto_url) {
                    document.getElementById('avatarImg').src = res.d.foto_url + '?t=' + Date.now();
                }

                Swal.fire({
                    icon: 'success', title: 'Tersimpan!',
                    text: res.d.message,
                    timer: 2000, showConfirmButton: false,
                    background: '#0a2e1c', color: '#fff',
                });
            } else {
                var msgs = [];
                if (res.d.errors) Object.values(res.d.errors).forEach(function (arr) { msgs = msgs.concat(arr); });
                Swal.fire({
                    icon: 'error', title: 'Gagal menyimpan',
                    html: msgs.length ? msgs.join('<br>') : (res.d.message || 'Terjadi kesalahan.'),
                    confirmButtonColor: '#22c55e', background: '#0a2e1c', color: '#fff',
                });
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg"></i> Simpan Perubahan';
            Swal.fire({ icon:'error', title:'Koneksi gagal', text:'Tidak dapat terhubung ke server.',
                background:'#0a2e1c', color:'#fff' });
        });
    });

    /* ================================================================
       MODAL PROFIL SEKOLAH
    ================================================================ */
    var modalSekolahEl = document.getElementById('modalSekolah');
    var modalSekolah   = new bootstrap.Modal(modalSekolahEl);
    var logoBase64Sekolah = null;

    /* Buka modal */
    document.getElementById('btnProfilSekolah').addEventListener('click', function () {
        /* Pre-load logo yang sudah ada */
        @if($sekolah->logo)
            var existingLogoUrl = '{{ route("admin.profile.logo", ["filename" => basename($sekolah->logo)]) }}';
            var previewImg = document.getElementById('logoPreviewImg');
            previewImg.src = existingLogoUrl;
            document.getElementById('logoUploadArea').style.display   = 'none';
            document.getElementById('logoPreviewWrap').style.display  = '';
        @endif
        modalSekolah.show();
    });

    /* Reset state logo saat modal ditutup */
    modalSekolahEl.addEventListener('hidden.bs.modal', function () {
        logoBase64Sekolah = null;
        document.getElementById('inputLogo').value = '';
        /* Jika tidak ada logo tersimpan, kembalikan ke upload area */
        @if(!$sekolah->logo)
            document.getElementById('logoUploadArea').style.display  = '';
            document.getElementById('logoPreviewWrap').style.display = 'none';
        @endif
    });

    /* Klik area upload → buka file picker */
    document.getElementById('logoUploadArea').addEventListener('click', function () {
        document.getElementById('inputLogo').click();
    });

    /* Pilih file logo */
    document.getElementById('inputLogo').addEventListener('change', function () {
        var file = this.files[0];
        if (!file) return;

        if (file.size > 2 * 1024 * 1024) {
            setFb('fbLogo', 'err', 'Ukuran file maksimum 2 MB.');
            return;
        }
        clearFb('fbLogo');

        var reader = new FileReader();
        reader.onload = function (e) {
            logoBase64Sekolah = e.target.result;
            document.getElementById('logoPreviewImg').src              = logoBase64Sekolah;
            document.getElementById('logoUploadArea').style.display   = 'none';
            document.getElementById('logoPreviewWrap').style.display  = '';
        };
        reader.readAsDataURL(file);
    });

    /* Hapus logo */
    document.getElementById('btnHapusLogo').addEventListener('click', function () {
        logoBase64Sekolah = null;
        document.getElementById('inputLogo').value = '';
        document.getElementById('logoPreviewImg').src             = '';
        document.getElementById('logoUploadArea').style.display  = '';
        document.getElementById('logoPreviewWrap').style.display = 'none';
    });

    /* Submit profil sekolah */
    document.getElementById('btnSimpanSekolah').addEventListener('click', function () {
        var btn          = this;
        var namaSekolah  = document.getElementById('inputNamaSekolah').value.trim();

        if (!namaSekolah) {
            setFb('fbNamaSekolah', 'err', 'Nama sekolah wajib diisi.');
            setValid('inputNamaSekolah', false);
            return;
        }
        setValid('inputNamaSekolah', true);
        clearFb('fbNamaSekolah');

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-sm"></span> Menyimpan...';

        fetch(SEKOLAH_URL, {
            method : 'POST',
            headers: {
                'X-CSRF-TOKEN'    : CSRF,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type'    : 'application/json',
            },
            body: JSON.stringify({
                nama_sekolah: namaSekolah,
                logo_base64 : logoBase64Sekolah || '',
            }),
        })
        .then(function (r) { return r.json().then(function (d) { return { st: r.status, d: d }; }); })
        .then(function (res) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg"></i> Simpan';

            if (res.d.success) {
                /* Update preview logo jika ada */
                if (res.d.logo_url) {
                    var newSrc = res.d.logo_url + '?t=' + Date.now();
                    document.getElementById('logoPreviewImg').src             = newSrc;
                    document.getElementById('logoUploadArea').style.display  = 'none';
                    document.getElementById('logoPreviewWrap').style.display = '';
                    logoBase64Sekolah = null;
                }

                modalSekolah.hide();
                Swal.fire({
                    icon: 'success', title: 'Tersimpan!',
                    text: res.d.message,
                    timer: 2000, showConfirmButton: false,
                    background: '#0a2e1c', color: '#fff',
                });
            } else {
                var msgs = [];
                if (res.d.errors) Object.values(res.d.errors).forEach(function (arr) { msgs = msgs.concat(arr); });
                Swal.fire({
                    icon: 'error', title: 'Gagal',
                    html: msgs.length ? msgs.join('<br>') : (res.d.message || 'Terjadi kesalahan.'),
                    confirmButtonColor: '#22c55e', background: '#0a2e1c', color: '#fff',
                });
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg"></i> Simpan';
            Swal.fire({ icon:'error', title:'Koneksi gagal', text:'Tidak dapat terhubung ke server.',
                background:'#0a2e1c', color:'#fff' });
        });
    });

    /* Drag-and-drop logo */
    (function () {
        var area = document.getElementById('logoUploadArea');
        area.addEventListener('dragover',  function (e) { e.preventDefault(); area.style.borderColor = 'rgba(251,191,36,.8)'; });
        area.addEventListener('dragleave', function ()  { area.style.borderColor = ''; });
        area.addEventListener('drop', function (e) {
            e.preventDefault();
            area.style.borderColor = '';
            var file = e.dataTransfer.files[0];
            if (!file) return;
            document.getElementById('inputLogo').files = e.dataTransfer.files;
            document.getElementById('inputLogo').dispatchEvent(new Event('change'));
        });
    }());

}());
</script>
@endpush
