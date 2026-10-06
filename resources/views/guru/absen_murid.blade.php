@extends('guru.layouts.app')

@section('title', 'Absensi Murid')
@section('page-title', 'Absensi Murid')

@push('styles')
<style>
    .page-header { display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.85rem;margin-bottom:1.5rem; }
    .page-header h2 { font-size:1.15rem;font-weight:700;color:#fff;margin:0 0 .2rem; }
    .page-header p  { font-size:.8rem;color:rgba(255,255,255,.45);margin:0; }

    .btn-buat-kode {
        display:inline-flex;align-items:center;gap:.5rem;
        background:linear-gradient(135deg,#0a7c4a,#22c55e);
        border:none;border-radius:10px;color:#fff;font-size:.875rem;font-weight:600;
        padding:.55rem 1.15rem;cursor:pointer;font-family:'Inter',sans-serif;
        transition:opacity .18s,transform .18s;
    }

    .btn-buat-kode:hover { opacity:.88;transform:translateY(-1px); }

    .list-container {
        background:rgba(255,255,255,.04);border:1px solid rgba(74,222,128,.12);
        border-radius:16px 0 16px 0;overflow:hidden;
    }

    .list-loading { text-align:center;padding:2.5rem 1rem;color:rgba(255,255,255,.35);font-size:.875rem; }

    .spinner-green {
        width:24px;height:24px;border:3px solid rgba(74,222,128,.15);border-top-color:#4ade80;
        border-radius:50%;animation:spin .65s linear infinite;display:inline-block;vertical-align:middle;margin-right:.5rem;
    }

    @keyframes spin { to { transform:rotate(360deg); } }

    .modal-dark .modal-content { background:#0a2e1c;border:1px solid rgba(74,222,128,.18);border-radius:16px;color:#e2f5ea; }
    .modal-dark .modal-header { border-bottom:1px solid rgba(74,222,128,.12);padding:1.1rem 1.4rem 1rem; }
    .modal-dark .modal-title  { font-size:1rem;font-weight:700;color:#fff; }
    .modal-dark .modal-footer { border-top:1px solid rgba(74,222,128,.12);padding:.9rem 1.4rem; }
    .modal-dark .btn-close    { filter:invert(1) brightness(.7); }
    .modal-dark .modal-body   { padding:1.25rem 1.4rem; }

    /* Kode display */
    .kode-display {
        text-align: center;
        font-size: 2.2rem; /* Diperkecil dari 2.8rem agar aman di layar HP */
        font-weight: 900;
        letter-spacing: .25em; /* Jarak huruf dirapatkan sedikit dari .35em */
        color: #4ade80;
        background: rgba(74,222,128,.06); 
        border: 2px solid rgba(74,222,128,.2);
        border-radius: 14px; 
        padding: 1rem 1.25rem; /* Menyesuaikan ruang kosong di dalam kotak */
        margin: 1rem 0;
        font-family: 'Inter', sans-serif;
        word-break: break-all; /* Memastikan teks tidak akan pernah menembus batas kotak */
    }

    .kode-timer { text-align:center;font-size:.85rem;color:rgba(255,255,255,.5);margin-bottom:.5rem; }
    .kode-timer span { color:#4ade80;font-weight:700; }
    .kode-hint-txt { text-align:center;font-size:.78rem;color:rgba(255,255,255,.35); }

    .form-label-dark { font-size:.8rem;font-weight:600;color:rgba(255,255,255,.65);margin-bottom:.4rem;display:block; }
    .form-select-dark {
        background:rgba(255,255,255,.06);border:1px solid rgba(74,222,128,.18);border-radius:10px;
        color:#e2f5ea;font-size:.875rem;padding:.55rem .85rem;width:100%;outline:none;
        font-family:'Inter',sans-serif;appearance:none;-webkit-appearance:none;
        background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 16 16'%3E%3Cpath fill='rgba(74,222,128,0.5)' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat:no-repeat;background-position:right .85rem center;padding-right:2.2rem;cursor:pointer;
    }

    .form-select-dark option { background:#0a2e1c;color:#e2f5ea; }
    .form-select-dark:focus  { border-color:rgba(74,222,128,.5);background:rgba(255,255,255,.09);box-shadow:0 0 0 3px rgba(74,222,128,.07); }

    .btn-modal-primary   { background:linear-gradient(135deg,#0a7c4a,#22c55e);border:none;border-radius:10px;color:#fff;font-size:.875rem;font-weight:600;padding:.55rem 1.35rem;cursor:pointer;font-family:'Inter',sans-serif; }
    .btn-modal-primary:hover { opacity:.87; }
    .btn-modal-secondary { background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:10px;color:rgba(255,255,255,.72);font-size:.875rem;font-weight:500;padding:.55rem 1.2rem;cursor:pointer;font-family:'Inter',sans-serif; }
    .btn-modal-secondary:hover { background:rgba(255,255,255,.12); }
</style>
@endpush

@section('content')

<div class="page-header">
    <div>
        <h2>Absensi Murid</h2>
        <p>Pantau dan kelola absensi murid di kelas Anda</p>
    </div>
    <button type="button" class="btn-buat-kode" id="btnBuatKode">
        <i class="bi bi-key-fill"></i> Buat Kode Absen
    </button>
</div>

<div class="list-container" id="listContainer">
    <div class="list-loading"><span class="spinner-green"></span> Memuat data absensi...</div>
</div>

<div class="modal fade modal-dark" id="modalKode" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-key-fill me-2" style="color:#4ade80;"></i>
                    Kode Absen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="kodePickStep">
                    <label class="form-label-dark" for="selectJadwalKode">Pilih Jadwal <span style="color:#f87171;">*</span></label>
                    <select id="selectJadwalKode" class="form-select-dark">
                        <option value="">— Memuat jadwal hari ini... —</option>
                    </select>
                    <p style="font-size:.73rem;color:rgba(255,255,255,.3);margin-top:.5rem;">
                        Kode berlaku <strong style="color:#4ade80;">5 menit</strong> sejak dibuat.
                    </p>
                </div>

                <div id="kodeShowStep" style="display:none;">
                    <p class="kode-timer">Berlaku hingga: <span id="kodeTimerText">05:00</span></p>
                    <div class="kode-display" id="kodeDisplay">------</div>
                    <p class="kode-hint-txt">Berikan kode ini kepada murid untuk absen.</p>
                </div>
            </div>
            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-modal-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn-modal-primary" id="btnGenerateKode">
                    <i class="bi bi-lightning-fill me-1"></i> Generate Kode
                </button>
                <button type="button" class="btn-modal-primary" id="btnBuatKodeBaru" style="display:none;">
                    <i class="bi bi-arrow-clockwise me-1"></i> Buat Baru
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

    var CSRF = (document.querySelector('meta[name="csrf-token"]')||{}).content||'{{ csrf_token() }}';
    var timerInterval = null;

    /* ============================================================
       LOAD LIST  (absen murid hari ini)
    ============================================================ */
    function loadList() {
        /* Placeholder — tampilkan info sederhana */
        var today = new Date().toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'});
        document.getElementById('listContainer').innerHTML =
            '<div style="padding:1.5rem 1.25rem;">'
            + '<p style="font-size:.82rem;font-weight:600;color:rgba(255,255,255,.5);margin-bottom:1rem;">'
            + '<i class="bi bi-calendar3 me-1"></i>' + today
            + '</p>'
            + '<p style="font-size:.82rem;color:rgba(255,255,255,.28);">Notifikasi absen murid tersedia di lonceng atas. Klik ikon lonceng untuk melihat daftar murid yang sudah absen hari ini.</p>'
            + '</div>';
    }

    /* ============================================================
       MODAL KODE ABSEN
    ============================================================ */
    var modalKodeEl = document.getElementById('modalKode');
    var modalKode   = new bootstrap.Modal(modalKodeEl);

    document.getElementById('btnBuatKode').addEventListener('click', function () {
        gotoPickStep();
        loadJadwalHariIni();
        modalKode.show();
    });

    function gotoPickStep() {
        document.getElementById('kodePickStep').style.display = '';
        document.getElementById('kodeShowStep').style.display = 'none';
        document.getElementById('btnGenerateKode').style.display = '';
        document.getElementById('btnBuatKodeBaru').style.display = 'none';
        clearInterval(timerInterval);
    }

    function loadJadwalHariIni() {
        var sel = document.getElementById('selectJadwalKode');
        sel.innerHTML = '<option value="">— Memuat... —</option>';

        fetch('{{ route("guru.kode_absen.jadwal_hari_ini") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            var jadwals = d.jadwals || [];
            if (!jadwals.length) {
                sel.innerHTML = '<option value="">— Tidak ada jadwal hari ini —</option>';
                return;
            }
            sel.innerHTML = '<option value="">— Pilih Jadwal —</option>';
            jadwals.forEach(function (j) {
                var opt = document.createElement('option');
                opt.value = j.id;
                opt.textContent = j.kelas + ' · ' + j.mapel + ' (' + j.waktu_mulai + '–' + j.waktu_berakhir + ')';
                sel.appendChild(opt);
            });
        });
    }

    document.getElementById('btnGenerateKode').addEventListener('click', function () {
        var jadwalId = document.getElementById('selectJadwalKode').value;
        if (!jadwalId) { Swal.fire({icon:'warning',title:'Pilih jadwal terlebih dahulu',background:'#0a2e1c',color:'#fff'}); return; }

        var btn = this; btn.disabled = true;
        btn.innerHTML = '<span class="spinner-green" style="width:14px;height:14px;border-width:2px;margin-right:5px;"></span>';

        fetch('{{ route("guru.kode_absen.buat") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' },
            body: JSON.stringify({ jadwal_mengajar_id: jadwalId })
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            btn.disabled = false; btn.innerHTML = '<i class="bi bi-lightning-fill me-1"></i> Generate Kode';
            if (d.success) {
                document.getElementById('kodeDisplay').textContent = d.kode;
                document.getElementById('kodePickStep').style.display = 'none';
                document.getElementById('kodeShowStep').style.display = '';
                document.getElementById('btnGenerateKode').style.display = 'none';
                document.getElementById('btnBuatKodeBaru').style.display = '';
                startTimer(d.sisa_detik || 300);
            } else {
                Swal.fire({ icon:'error', title:'Gagal', text: d.message, background:'#0a2e1c', color:'#fff' });
            }
        });
    });

    document.getElementById('btnBuatKodeBaru').addEventListener('click', function () {
        gotoPickStep();
        loadJadwalHariIni();
    });

    modalKodeEl.addEventListener('hidden.bs.modal', function () { clearInterval(timerInterval); });

    function startTimer(detik) {
        clearInterval(timerInterval);
        var sisa = detik;
        updateTimerDisplay(sisa);
        timerInterval = setInterval(function () {
            sisa--;
            updateTimerDisplay(sisa);
            if (sisa <= 0) {
                clearInterval(timerInterval);
                document.getElementById('kodeDisplay').style.opacity = '.3';
                document.getElementById('kodeTimerText').textContent = 'Kedaluwarsa';
                document.getElementById('kodeTimerText').style.color = '#f87171';
            }
        }, 1000);
    }

    function updateTimerDisplay(s) {
        var m = Math.floor(s / 60);
        var sec = s % 60;
        document.getElementById('kodeTimerText').textContent =
            String(m).padStart(2,'0') + ':' + String(sec).padStart(2,'0');
        document.getElementById('kodeTimerText').style.color = s <= 60 ? '#f87171' : '#4ade80';
    }

    /* ============================================================
       INIT
    ============================================================ */
    loadList();
}());
</script>
@endpush
