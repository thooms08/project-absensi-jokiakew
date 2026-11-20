@extends('guru.layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@push('styles')
<style>
/* ================================================================
   LAYOUT
================================================================ */
.profile-grid {
    display: grid;
    grid-template-columns: 260px 1fr;
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
   KOLOM KIRI — avatar + info
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
}
.avatar-initials {
    background: linear-gradient(135deg, #0a7c4a, #4ade80);
    font-size: 2.2rem; font-weight: 800; color: #064e2e;
    display: flex; align-items: center; justify-content: center;
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

.user-name  { font-size: 1rem; font-weight: 700; color: #fff; margin: 0 0 .2rem; }
.user-role  {
    display: inline-flex; align-items: center; gap: .3rem;
    font-size: .68rem; font-weight: 700; letter-spacing: .06em;
    text-transform: uppercase;
    background: rgba(74,222,128,.12); color: #4ade80;
    border: 1px solid rgba(74,222,128,.25);
    border-radius: 50px; padding: .18rem .65rem;
}
.user-username {
    font-size: .78rem; color: rgba(255,255,255,.4);
    margin: .25rem 0 0;
}

/* ================================================================
   FORM
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
    cursor: pointer; padding: 0; line-height: 1; transition: color .18s;
}
.f-eye-btn:hover { color: rgba(255,255,255,.7); }

.f-feedback {
    font-size: .72rem; margin-top: .3rem; min-height: 1rem;
    display: flex; align-items: center; gap: .3rem;
}
.f-feedback.ok   { color: #4ade80; }
.f-feedback.err  { color: #f87171; }
.f-feedback.muted{ color: rgba(255,255,255,.3); }

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

.btn-save {
    display: inline-flex; align-items: center; gap: .5rem;
    background: linear-gradient(135deg, #0a7c4a, #22c55e);
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

/* username check spinner */
.u-checking {
    width: 13px; height: 13px;
    border: 2px solid rgba(74,222,128,.3);
    border-top-color: #4ade80;
    border-radius: 50%;
    animation: spin .6s linear infinite;
    display: inline-block; vertical-align: middle;
}
</style>
@endpush

@section('content')

<div style="margin-bottom:1.5rem;">
    <h2 style="font-size:1.15rem;font-weight:700;color:#fff;margin:0 0 .2rem;">Profil Saya</h2>
    <p style="font-size:.8rem;color:rgba(255,255,255,.45);margin:0;">Kelola informasi akun dan foto profil</p>
</div>

<div class="profile-grid">

    {{-- ── KOLOM KIRI ── --}}
    <div>
        <div class="p-card">
            <div class="avatar-wrap">

                <div class="avatar-ring">
                    @if(Auth::user()->image)
                        <img id="avatarImg"
                             src="{{ route('guru.profile.foto', ['filename' => basename(Auth::user()->image)]) }}"
                             alt="Foto {{ Auth::user()->name }}"
                             class="avatar-img">
                    @else
                        <div class="avatar-initials" id="avatarInitials">
                            {{ strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <img id="avatarImg" src="" alt="" class="avatar-img" style="display:none;">
                    @endif

                    <button class="avatar-edit-btn" id="btnEditAvatar"
                            title="Ganti foto" aria-label="Ganti foto profil">
                        <i class="bi bi-camera-fill"></i>
                    </button>
                    <input type="file" id="inputAvatar"
                           accept="image/jpeg,image/png,image/webp" style="display:none;">
                </div>

                <div>
                    <p class="user-name" id="displayName">{{ Auth::user()->name }}</p>
                    <span class="user-role">
                        <i class="bi bi-person-check-fill"></i>
                        {{ Auth::user()->role ?? 'Guru' }}
                    </span>
                    <p class="user-username">@{{ Auth::user()->username }}</p>
                </div>

            </div>
        </div>
    </div>

    {{-- ── KOLOM KANAN ── --}}
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
                        <label class="f-label" for="inputName">
                            Nama Lengkap <span class="req">*</span>
                        </label>
                        <input type="text" id="inputName" name="name" class="f-input"
                               value="{{ Auth::user()->name }}"
                               placeholder="Nama lengkap" maxlength="255" required>
                        <div class="f-feedback muted" id="fbName"></div>
                    </div>

                    {{-- Username --}}
                    <div class="f-group">
                        <label class="f-label" for="inputUsername">
                            Username <span class="req">*</span>
                        </label>
                        <input type="text" id="inputUsername" name="username" class="f-input"
                               value="{{ Auth::user()->username }}"
                               placeholder="username" maxlength="60"
                               autocomplete="off" required>
                        <div class="f-feedback muted" id="fbUsername"></div>
                    </div>

                    <hr class="divider">

                    <p style="font-size:.75rem;font-weight:700;color:rgba(74,222,128,.65);text-transform:uppercase;letter-spacing:.07em;margin-bottom:.85rem;">
                        <i class="bi bi-lock-fill me-1"></i>Ganti Password
                    </p>

                    {{-- Password baru --}}
                    <div class="f-group">
                        <label class="f-label" for="inputPassword">
                            Password Baru
                            <span style="color:rgba(255,255,255,.3);font-weight:400;">
                                (kosongkan jika tidak ingin ganti)
                            </span>
                        </label>
                        <div class="f-input-wrap">
                            <input type="password" id="inputPassword" name="password" class="f-input"
                                   placeholder="Min. 8 karakter + angka"
                                   maxlength="128" autocomplete="new-password"
                                   style="padding-right:2.6rem;">
                            <button type="button" class="f-eye-btn" id="togglePwd"
                                    aria-label="Tampilkan password">
                                <i class="bi bi-eye" id="iconPwd"></i>
                            </button>
                        </div>
                        <div class="pwd-bar-wrap">
                            <div class="pwd-bar-fill" id="pwdBar"></div>
                        </div>
                        <div class="f-feedback muted" id="fbPassword"></div>
                    </div>

                    {{-- Konfirmasi password --}}
                    <div class="f-group">
                        <label class="f-label" for="inputPasswordConfirm">
                            Konfirmasi Password Baru
                        </label>
                        <div class="f-input-wrap">
                            <input type="password" id="inputPasswordConfirm"
                                   name="password_confirmation" class="f-input"
                                   placeholder="Ulangi password baru"
                                   maxlength="128" autocomplete="new-password"
                                   style="padding-right:2.6rem;">
                            <button type="button" class="f-eye-btn" id="togglePwdConfirm"
                                    aria-label="Tampilkan konfirmasi password">
                                <i class="bi bi-eye" id="iconPwdConfirm"></i>
                            </button>
                        </div>
                        <div class="f-feedback muted" id="fbPasswordConfirm"></div>
                    </div>

                    <div style="display:flex;justify-content:flex-end;margin-top:1.25rem;">
                        <button type="button" class="btn-save" id="btnSimpan">
                            <i class="bi bi-check-lg"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
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
    var UPDATE_URL   = '{{ route("guru.profile.update") }}';
    var CHECK_URL    = '{{ route("guru.profile.check_username") }}';

    /* ================================================================
       HELPERS
    ================================================================ */
    function setFb(id, type, msg) {
        var el = document.getElementById(id);
        if (!el) return;
        el.className = 'f-feedback ' + type;
        el.innerHTML = msg
            ? (type === 'ok'  ? '<i class="bi bi-check-circle-fill"></i> ' :
               type === 'err' ? '<i class="bi bi-exclamation-circle-fill"></i> ' : '')
              + msg
            : '';
    }

    function setValid(id, v) {
        var el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('is-valid','is-invalid');
        if (v === true)  el.classList.add('is-valid');
        if (v === false) el.classList.add('is-invalid');
    }

    /* ================================================================
       TOGGLE PASSWORD EYE
    ================================================================ */
    function bindEye(btnId, iconId, inputId) {
        var btn = document.getElementById(btnId);
        if (!btn) return;
        btn.addEventListener('click', function () {
            var inp  = document.getElementById(inputId);
            var icon = document.getElementById(iconId);
            var show = inp.type === 'password';
            inp.type     = show ? 'text' : 'password';
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
        bar.style.width      = ['0%','30%','55%','80%','100%'][score];
        bar.style.background = ['','#f87171','#fbbf24','#4ade80','#22c55e'][score];

        if (!v) {
            setValid('inputPassword', null);
            setValid('inputPasswordConfirm', null);
            setFb('fbPassword', 'muted', '');
            setFb('fbPasswordConfirm', 'muted', '');
        }
        checkPasswordMatch();
    });

    function checkPasswordMatch() {
        var pwd  = document.getElementById('inputPassword').value;
        var cpwd = document.getElementById('inputPasswordConfirm').value;

        if (!pwd && !cpwd) {
            setValid('inputPassword', null);
            setValid('inputPasswordConfirm', null);
            setFb('fbPassword', 'muted', '');
            setFb('fbPasswordConfirm', 'muted', '');
            return true;
        }

        if (pwd && pwd.length < 8) {
            setValid('inputPassword', false);
            setFb('fbPassword', 'err', 'Password minimal 8 karakter dan harus mengandung angka.');
        } else if (pwd) {
            setValid('inputPassword', true);
            setFb('fbPassword', 'muted', '');
        }

        if (!cpwd) return false;

        if (pwd === cpwd) {
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
       USERNAME — AJAX POLLING
    ================================================================ */
    var usernameTimer = null;
    var usernameValid = null; // true | false | null
    var currentUsername = '{{ Auth::user()->username }}';

    document.getElementById('inputUsername').addEventListener('input', function () {
        var val = this.value.trim();
        clearTimeout(usernameTimer);
        usernameValid = null;

        if (!val) {
            setValid('inputUsername', false);
            setFb('fbUsername', 'err', 'Username wajib diisi.');
            return;
        }

        if (!/^[a-zA-Z0-9_.\-]+$/.test(val)) {
            setValid('inputUsername', false);
            setFb('fbUsername', 'err', 'Hanya huruf, angka, titik, underscore, dash.');
            return;
        }

        /* Jika sama dengan username saat ini, langsung ok */
        if (val === currentUsername) {
            usernameValid = true;
            setValid('inputUsername', true);
            setFb('fbUsername', 'ok', 'Username Anda saat ini.');
            return;
        }

        /* Tampilkan spinner cek */
        setFb('fbUsername', 'muted', '<span class="u-checking"></span> Memeriksa ketersediaan...');
        setValid('inputUsername', null);

        usernameTimer = setTimeout(function () {
            fetch(CHECK_URL + '?username=' + encodeURIComponent(val), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                usernameValid = d.available;
                setValid('inputUsername', d.available);
                setFb('fbUsername', d.available ? 'ok' : 'err', d.message);
            })
            .catch(function () {
                usernameValid = null;
                setFb('fbUsername', 'muted', 'Gagal memeriksa. Coba lagi.');
            });
        }, 500);
    });

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
            Swal.fire({
                icon: 'warning', title: 'File terlalu besar',
                text: 'Maksimum ukuran foto adalah 2 MB.',
                background: '#0a2e1c', color: '#fff', confirmButtonColor: '#22c55e'
            });
            this.value = '';
            return;
        }

        var reader = new FileReader();
        reader.onload = function (e) {
            fotoBase64 = e.target.result;
            var imgEl      = document.getElementById('avatarImg');
            var initialEl  = document.getElementById('avatarInitials');
            imgEl.src      = fotoBase64;
            imgEl.style.display = '';
            if (initialEl) initialEl.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

    /* ================================================================
       SUBMIT FORM
    ================================================================ */
    document.getElementById('btnSimpan').addEventListener('click', function () {
        var btn  = this;
        var name = document.getElementById('inputName').value.trim();
        var uname= document.getElementById('inputUsername').value.trim();
        var pwd  = document.getElementById('inputPassword').value;
        var cpwd = document.getElementById('inputPasswordConfirm').value;
        var ok   = true;

        /* Nama */
        if (!name) {
            setValid('inputName', false);
            setFb('fbName', 'err', 'Nama wajib diisi.');
            ok = false;
        } else {
            setValid('inputName', true);
            setFb('fbName', 'muted', '');
        }

        /* Username */
        if (!uname) {
            setValid('inputUsername', false);
            setFb('fbUsername', 'err', 'Username wajib diisi.');
            ok = false;
        } else if (!/^[a-zA-Z0-9_.\-]+$/.test(uname)) {
            setValid('inputUsername', false);
            setFb('fbUsername', 'err', 'Format username tidak valid.');
            ok = false;
        } else if (usernameValid === false) {
            setValid('inputUsername', false);
            setFb('fbUsername', 'err', 'Username sudah digunakan.');
            ok = false;
        }

        /* Password */
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

        /* Jika username baru dan belum di-check (typing belum selesai polling) */
        if (uname !== currentUsername && usernameValid === null) {
            setFb('fbUsername', 'muted', 'Tunggu sebentar, sedang memeriksa username...');
            return;
        }

        btn.disabled  = true;
        btn.innerHTML = '<span class="spinner-sm"></span> Menyimpan...';

        var payload = {
            name       : name,
            username   : uname,
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
            btn.disabled  = false;
            btn.innerHTML = '<i class="bi bi-check-lg"></i> Simpan Perubahan';

            if (res.d.success) {
                currentUsername = uname;
                fotoBase64      = null;

                /* Update nama di sidebar/topbar */
                document.getElementById('displayName').textContent = name;

                /* Reset password fields */
                document.getElementById('inputPassword').value        = '';
                document.getElementById('inputPasswordConfirm').value = '';
                document.getElementById('pwdBar').style.width         = '0';
                setValid('inputPassword', null);
                setValid('inputPasswordConfirm', null);
                setFb('fbPassword', 'muted', '');
                setFb('fbPasswordConfirm', 'muted', '');

                /* Update foto di topbar avatar jika ada */
                if (res.d.foto_url) {
                    var tbAvatar = document.getElementById('topbarAvatar');
                    if (tbAvatar) {
                        tbAvatar.innerHTML = '<img src="' + res.d.foto_url
                            + '?t=' + Date.now()
                            + '" alt="Foto profil" style="width:100%;height:100%;object-fit:cover;">';
                    }
                    var bottomBtn = document.getElementById('bottomAvatarBtn');
                    if (bottomBtn) {
                        var imgInBtn = bottomBtn.querySelector('img');
                        if (imgInBtn) imgInBtn.src = res.d.foto_url + '?t=' + Date.now();
                    }
                }

                Swal.fire({
                    icon: 'success', title: 'Berhasil!',
                    text: res.d.message,
                    background: '#0a2e1c', color: '#fff',
                    confirmButtonColor: '#22c55e', timer: 2500, timerProgressBar: true
                });
            } else {
                /* Tampilkan error dari server */
                var msg = res.d.message || 'Terjadi kesalahan.';
                if (res.d.errors) {
                    var errKeys = Object.keys(res.d.errors);
                    if (errKeys.length) msg = res.d.errors[errKeys[0]][0];
                }
                Swal.fire({
                    icon: 'error', title: 'Gagal menyimpan',
                    text: msg,
                    background: '#0a2e1c', color: '#fff', confirmButtonColor: '#22c55e'
                });
            }
        })
        .catch(function () {
            btn.disabled  = false;
            btn.innerHTML = '<i class="bi bi-check-lg"></i> Simpan Perubahan';
            Swal.fire({
                icon: 'error', title: 'Koneksi gagal',
                text: 'Tidak dapat terhubung ke server.',
                background: '#0a2e1c', color: '#fff', confirmButtonColor: '#22c55e'
            });
        });
    });

}());
</script>
@endpush
