@extends('guru.layouts.app')

@section('title', 'Absensi Saya')
@section('page-title', 'Absensi Saya')

@push('styles')
<style>
    /* ============================================================
       PAGE HEADER
    ============================================================ */
    .page-header {
        margin-bottom: 1.5rem;
    }

    .page-header h2 {
        font-size: 1.15rem;
        font-weight: 700;
        color: #ffffff;
        margin: 0 0 0.2rem;
    }

    .page-header p {
        font-size: 0.82rem;
        color: rgba(255,255,255,0.45);
        margin: 0;
    }

    /* ============================================================
       INFO BAR (tanggal + hari)
    ============================================================ */
    .info-bar {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(74,222,128,0.12);
        border-radius: 12px;
        padding: 0.7rem 1rem;
        margin-bottom: 1.5rem;
        font-size: 0.82rem;
    }

    .info-bar-hari {
        font-weight: 700;
        color: #4ade80;
        letter-spacing: 0.03em;
    }

    .info-bar-tgl {
        color: rgba(255,255,255,0.5);
    }

    .info-bar-dot {
        color: rgba(255,255,255,0.18);
    }

    .btn-refresh-kartu {
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(74,222,128,0.08);
        border: 1px solid rgba(74,222,128,0.18);
        border-radius: 8px;
        color: rgba(74,222,128,0.75);
        font-size: 0.78rem;
        font-weight: 600;
        padding: 0.35rem 0.75rem;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: background 0.15s;
    }

    .btn-refresh-kartu:hover {
        background: rgba(74,222,128,0.15);
        color: #4ade80;
    }

    /* ============================================================
       KARTU CONTAINER
    ============================================================ */
    #kartuContainer {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
        gap: 1.1rem;
    }

    /* ============================================================
       LOADING
    ============================================================ */
    .kartu-loading {
        grid-column: 1 / -1;
        text-align: center;
        padding: 3rem 1rem;
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
       MODAL GELAP (kamera & izin)
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
       KAMERA
    ============================================================ */
    .kamera-wrap {
        position: relative;
        width: 100%;
        aspect-ratio: 4/3;
        background: #000;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 0.85rem;
    }

    #videoKamera {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    #canvasFoto {
        display: none;
    }

    #previewFoto {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 12px;
        display: none;
        margin-bottom: 0.85rem;
    }

    .kamera-hint {
        font-size: 0.75rem;
        color: rgba(255,255,255,0.38);
        text-align: center;
        margin-bottom: 0.65rem;
    }

    .kamera-btn-row {
        display: flex;
        gap: 0.65rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-ambil-foto {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        background: rgba(74,222,128,0.12);
        border: 1px solid rgba(74,222,128,0.3);
        border-radius: 10px;
        color: #4ade80;
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.55rem 1.2rem;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: background 0.15s;
    }

    .btn-ambil-foto:hover { background: rgba(74,222,128,0.22); }

    .btn-ulang-foto {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        background: rgba(255,255,255,0.07);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 10px;
        color: rgba(255,255,255,0.65);
        font-size: 0.875rem;
        font-weight: 500;
        padding: 0.55rem 1.1rem;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: background 0.15s;
    }

    .btn-ulang-foto:hover { background: rgba(255,255,255,0.12); }

    /* ============================================================
       FORM IZIN
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
        padding: 0.6rem 0.85rem;
        width: 100%;
        resize: vertical;
        min-height: 100px;
        outline: none;
        font-family: 'Inter', sans-serif;
        transition: border-color 0.18s;
    }

    .form-control-dark::placeholder { color: rgba(255,255,255,0.28); }
    .form-control-dark:focus {
        border-color: rgba(74,222,128,0.5);
        background: rgba(255,255,255,0.09);
        box-shadow: 0 0 0 3px rgba(74,222,128,0.07);
    }

    .field-feedback {
        font-size: 0.75rem;
        color: #f87171;
        margin-top: 0.28rem;
        min-height: 1rem;
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
    .btn-modal-primary:disabled { opacity: 0.45; cursor: not-allowed; }

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
       INFO JADWAL DI MODAL
    ============================================================ */
    .modal-jadwal-info {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(74,222,128,0.12);
        border-radius: 10px;
        padding: 0.7rem 0.9rem;
        margin-bottom: 1rem;
        font-size: 0.82rem;
    }

    .modal-jadwal-info .kelas-nm {
        font-weight: 700;
        color: #ffffff;
        font-size: 0.9rem;
    }

    .modal-jadwal-info .mapel-nm {
        color: #fbbf24;
        font-size: 0.78rem;
    }

    .modal-jadwal-info .jam-nm {
        color: rgba(74,222,128,0.7);
        font-size: 0.75rem;
        margin-top: 0.15rem;
    }

    @media (max-width: 575.98px) {
        #kartuContainer { grid-template-columns: 1fr; }
        .modal-dialog   { margin: 0.5rem; }
    }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="page-header">
    <h2><i class="bi bi-clipboard2-check-fill me-2" style="color:#4ade80;"></i>Absensi Saya</h2>
    <p>Lakukan absensi sesuai jadwal mengajar hari ini</p>
</div>

{{-- INFO BAR --}}
@php
    $hariMap = [1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu',0=>'Minggu'];
    $hariIni = $hariMap[date('w')] ?? '-';
    $tglIni  = \Carbon\Carbon::today()->translatedFormat('d F Y');
@endphp

<div class="info-bar">
    <i class="bi bi-calendar2-week-fill" style="color:#4ade80;font-size:1rem;"></i>
    <span class="info-bar-hari">{{ $hariIni }}</span>
    <span class="info-bar-dot">•</span>
    <span class="info-bar-tgl">{{ $tglIni }}</span>
    <button type="button" class="btn-refresh-kartu" id="btnRefreshKartu">
        <i class="bi bi-arrow-clockwise"></i> Perbarui
    </button>
</div>

{{-- KARTU CONTAINER --}}
<div id="kartuContainer">
    <div class="kartu-loading">
        <span class="spinner-green"></span> Memuat jadwal hari ini...
    </div>
</div>

{{-- ================================================================
     MODAL HADIR (kamera)
================================================================ --}}
<div class="modal fade modal-dark" id="modalHadir"
     tabindex="-1" aria-labelledby="modalHadirLabel" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalHadirLabel">
                    <i class="bi bi-camera-fill me-2" style="color:#4ade80;"></i>
                    Foto Bukti Kehadiran
                </h5>
                <button type="button" class="btn-close" id="btnTutupModalHadir" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                {{-- Info jadwal --}}
                <div class="modal-jadwal-info" id="hadirJadwalInfo">
                    <div class="kelas-nm" id="hadirKelasNama">–</div>
                    <div class="mapel-nm" id="hadirMapelNama">–</div>
                    <div class="jam-nm" id="hadirJamInfo">–</div>
                </div>

                {{-- Video kamera --}}
                <div class="kamera-wrap" id="kameraWrap">
                    <video id="videoKamera" autoplay playsinline muted></video>
                </div>

                {{-- Preview foto yang sudah diambil --}}
                <img id="previewFoto" src="" alt="Preview foto" />

                {{-- Canvas tersembunyi untuk capture --}}
                <canvas id="canvasFoto"></canvas>

                <p class="kamera-hint" id="kameraHint">
                    Arahkan kamera ke kondisi kelas atau ambil selfie sebagai bukti kehadiran.
                </p>

                <div class="kamera-btn-row">
                    <button type="button" class="btn-ambil-foto" id="btnAmbilFoto">
                        <i class="bi bi-camera"></i> Ambil Foto
                    </button>
                    <button type="button" class="btn-ulang-foto" id="btnUlangFoto" style="display:none;">
                        <i class="bi bi-arrow-counterclockwise"></i> Ulangi
                    </button>
                </div>
            </div>

            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-modal-secondary" id="btnBatalHadir">Batal</button>
                <button type="button" class="btn-modal-primary" id="btnSubmitHadir" disabled>
                    <i class="bi bi-check-lg me-1"></i> Kirim Absen
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================
     MODAL IZIN (form keterangan)
================================================================ --}}
<div class="modal fade modal-dark" id="modalIzin"
     tabindex="-1" aria-labelledby="modalIzinLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalIzinLabel">
                    <i class="bi bi-envelope-fill me-2" style="color:#fbbf24;"></i>
                    Pengajuan Izin
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                {{-- Info jadwal --}}
                <div class="modal-jadwal-info" id="izinJadwalInfo">
                    <div class="kelas-nm" id="izinKelasNama">–</div>
                    <div class="mapel-nm" id="izinMapelNama">–</div>
                    <div class="jam-nm" id="izinJamInfo">–</div>
                </div>

                <label class="form-label-dark" for="inputAlasan">
                    Keterangan Izin <span style="color:#f87171;">*</span>
                </label>
                <textarea
                    id="inputAlasan"
                    class="form-control-dark"
                    placeholder="Tuliskan alasan izin Anda..."
                    maxlength="500"
                ></textarea>
                <div class="field-feedback" id="fbAlasan"></div>
            </div>

            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-modal-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-modal-primary" id="btnSubmitIzin"
                        style="background:linear-gradient(135deg,#78500a,#d97706);">
                    <i class="bi bi-send-fill me-1"></i> Kirim Izin
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
    var activeJadwalId = null;   /* jadwal yang sedang diproses */
    var fotoBase64     = null;   /* hasil capture kamera */
    var kameraStream   = null;   /* MediaStream aktif */
    var refreshTimer   = null;   /* auto-refresh setiap 60 detik */

    /* ============================================================
       LOAD KARTU
    ============================================================ */
    function loadKartu() {
        var container = document.getElementById('kartuContainer');
        container.innerHTML = '<div class="kartu-loading"><span class="spinner-green"></span> Memuat jadwal hari ini...</div>';

        fetch('{{ route("guru.absen_guru.kartu") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.text(); })
        .then(function (html) {
            container.innerHTML = html;
            bindKartuEvents();
        })
        .catch(function () {
            container.innerHTML = '<div class="kartu-loading" style="color:#f87171;">Gagal memuat jadwal. Periksa koneksi Anda.</div>';
        });
    }

    document.getElementById('btnRefreshKartu').addEventListener('click', function () {
        var icon = this.querySelector('i');
        icon.style.transition = 'transform 0.5s';
        icon.style.transform  = 'rotate(360deg)';
        setTimeout(function () { icon.style.transform = ''; }, 500);
        loadKartu();
    });

    /* Auto-refresh setiap 60 detik agar status otomatis 'tidak' ter-update */
    function startAutoRefresh() {
        clearInterval(refreshTimer);
        refreshTimer = setInterval(loadKartu, 60000);
    }

    /* ============================================================
       BIND EVENTS KARTU (delegasi)
    ============================================================ */
    function bindKartuEvents() {
        var container = document.getElementById('kartuContainer');

        container.querySelectorAll('[data-btn-hadir]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                bukaModalHadir(
                    this.dataset.btnHadir,
                    this.dataset.kelas,
                    this.dataset.mapel,
                    this.dataset.jam
                );
            });
        });

        container.querySelectorAll('[data-btn-izin]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                bukaModalIzin(
                    this.dataset.btnIzin,
                    this.dataset.kelas,
                    this.dataset.mapel,
                    this.dataset.jam
                );
            });
        });
    }

    /* ============================================================
       MODAL HADIR  –  kamera
    ============================================================ */
    var modalHadirEl  = document.getElementById('modalHadir');
    var modalHadir    = new bootstrap.Modal(modalHadirEl);
    var videoEl       = document.getElementById('videoKamera');
    var canvasEl      = document.getElementById('canvasFoto');
    var previewEl     = document.getElementById('previewFoto');
    var kameraWrap    = document.getElementById('kameraWrap');

    function bukaModalHadir(jadwalId, kelas, mapel, jam) {
        activeJadwalId = jadwalId;
        fotoBase64     = null;

        /* Isi info jadwal */
        document.getElementById('hadirKelasNama').textContent = kelas || '–';
        document.getElementById('hadirMapelNama').textContent = mapel || '–';
        document.getElementById('hadirJamInfo').textContent   = jam   || '–';

        /* Reset tampilan */
        resetKamera();

        modalHadir.show();

        /* Mulai kamera setelah modal terbuka */
        modalHadirEl.addEventListener('shown.bs.modal', startKamera, { once: true });
    }

    function startKamera() {
        var constraints = {
            video: {
                facingMode: { ideal: 'environment' },   /* kamera belakang di HP */
                width:  { ideal: 1280 },
                height: { ideal: 720 },
            },
            audio: false,
        };

        navigator.mediaDevices.getUserMedia(constraints)
        .then(function (stream) {
            kameraStream = stream;
            videoEl.srcObject = stream;
        })
        .catch(function (err) {
            document.getElementById('kameraHint').textContent =
                'Kamera tidak tersedia: ' + err.message +
                '. Gunakan browser yang mendukung akses kamera.';
            document.getElementById('kameraHint').style.color = '#f87171';
        });
    }

    function stopKamera() {
        if (kameraStream) {
            kameraStream.getTracks().forEach(function (t) { t.stop(); });
            kameraStream = null;
        }
        videoEl.srcObject = null;
    }

    function resetKamera() {
        fotoBase64 = null;
        previewEl.style.display = 'none';
        previewEl.src           = '';
        kameraWrap.style.display = '';
        document.getElementById('btnAmbilFoto').style.display = '';
        document.getElementById('btnUlangFoto').style.display = 'none';
        document.getElementById('btnSubmitHadir').disabled = true;
        document.getElementById('kameraHint').textContent =
            'Arahkan kamera ke kondisi kelas atau ambil selfie sebagai bukti kehadiran.';
        document.getElementById('kameraHint').style.color = '';
    }

    /* Ambil foto dari video stream */
    document.getElementById('btnAmbilFoto').addEventListener('click', function () {
        if (!kameraStream) {
            Swal.fire({ icon:'warning', title:'Kamera belum aktif', text:'Tunggu beberapa saat lalu coba lagi.', background:'#0a2e1c', color:'#fff' });
            return;
        }

        var w = videoEl.videoWidth  || 1280;
        var h = videoEl.videoHeight || 720;
        canvasEl.width  = w;
        canvasEl.height = h;
        canvasEl.getContext('2d').drawImage(videoEl, 0, 0, w, h);

        fotoBase64 = canvasEl.toDataURL('image/jpeg', 0.88);

        /* Tampilkan preview, sembunyikan video */
        previewEl.src           = fotoBase64;
        previewEl.style.display = 'block';
        kameraWrap.style.display = 'none';

        document.getElementById('btnAmbilFoto').style.display = 'none';
        document.getElementById('btnUlangFoto').style.display = '';
        document.getElementById('btnSubmitHadir').disabled    = false;
        document.getElementById('kameraHint').textContent     = 'Foto berhasil diambil. Klik "Kirim Absen" untuk menyimpan.';
        document.getElementById('kameraHint').style.color     = 'rgba(74,222,128,0.7)';
    });

    /* Ulangi foto */
    document.getElementById('btnUlangFoto').addEventListener('click', function () {
        resetKamera();
        /* Restart kamera jika stream sudah mati */
        if (!kameraStream) startKamera();
    });

    /* Submit hadir */
    document.getElementById('btnSubmitHadir').addEventListener('click', function () {
        if (!fotoBase64) {
            Swal.fire({ icon:'warning', title:'Belum ada foto', text:'Ambil foto terlebih dahulu.', background:'#0a2e1c', color:'#fff' });
            return;
        }

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-green" style="width:14px;height:14px;border-width:2px;margin-right:5px;"></span> Mengirim...';

        fetch('{{ route("guru.absen_guru.store") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' },
            body: JSON.stringify({ jadwal_id: activeJadwalId, status: 'hadir', foto: fotoBase64 })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Kirim Absen';

            if (data.success) {
                stopKamera();
                modalHadir.hide();
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: data.message,
                    timer: 2200, showConfirmButton: false, background: '#0a2e1c', color: '#fff',
                }).then(loadKartu);
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Terjadi kesalahan.', background: '#0a2e1c', color: '#fff' });
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Kirim Absen';
            Swal.fire({ icon: 'error', title: 'Koneksi gagal', text: 'Tidak dapat terhubung ke server.', background: '#0a2e1c', color: '#fff' });
        });
    });

    /* Tutup modal hadir → stop kamera */
    document.getElementById('btnTutupModalHadir').addEventListener('click', function () {
        stopKamera();
        modalHadir.hide();
    });

    document.getElementById('btnBatalHadir').addEventListener('click', function () {
        stopKamera();
        modalHadir.hide();
    });

    modalHadirEl.addEventListener('hidden.bs.modal', function () {
        stopKamera();
        resetKamera();
    });

    /* ============================================================
       MODAL IZIN  –  form keterangan
    ============================================================ */
    var modalIzinEl = document.getElementById('modalIzin');
    var modalIzin   = new bootstrap.Modal(modalIzinEl);

    function bukaModalIzin(jadwalId, kelas, mapel, jam) {
        activeJadwalId = jadwalId;

        document.getElementById('izinKelasNama').textContent = kelas || '–';
        document.getElementById('izinMapelNama').textContent = mapel || '–';
        document.getElementById('izinJamInfo').textContent   = jam   || '–';
        document.getElementById('inputAlasan').value         = '';
        document.getElementById('fbAlasan').textContent      = '';
        document.getElementById('inputAlasan').classList.remove('is-invalid');

        modalIzin.show();
        setTimeout(function () { document.getElementById('inputAlasan').focus(); }, 350);
    }

    document.getElementById('btnSubmitIzin').addEventListener('click', function () {
        var alasan = document.getElementById('inputAlasan').value.trim();
        var fbEl   = document.getElementById('fbAlasan');

        if (!alasan) {
            document.getElementById('inputAlasan').classList.add('is-invalid');
            fbEl.textContent = 'Keterangan izin wajib diisi.';
            document.getElementById('inputAlasan').focus();
            return;
        }

        document.getElementById('inputAlasan').classList.remove('is-invalid');
        fbEl.textContent = '';

        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-green" style="width:14px;height:14px;border-width:2px;margin-right:5px;"></span> Mengirim...';

        fetch('{{ route("guru.absen_guru.store") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' },
            body: JSON.stringify({ jadwal_id: activeJadwalId, status: 'izin', alasan: alasan })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-send-fill me-1"></i> Kirim Izin';

            if (data.success) {
                modalIzin.hide();
                Swal.fire({
                    icon: 'success', title: 'Izin Terkirim!', text: data.message,
                    timer: 2200, showConfirmButton: false, background: '#0a2e1c', color: '#fff',
                }).then(loadKartu);
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Terjadi kesalahan.', background: '#0a2e1c', color: '#fff' });
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-send-fill me-1"></i> Kirim Izin';
            Swal.fire({ icon: 'error', title: 'Koneksi gagal', text: 'Tidak dapat terhubung ke server.', background: '#0a2e1c', color: '#fff' });
        });
    });

    /* ============================================================
       INIT
    ============================================================ */
    loadKartu();
    startAutoRefresh();

}());
</script>
@endpush
