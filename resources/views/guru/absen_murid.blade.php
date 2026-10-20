@extends('guru.layouts.app')

@section('title', 'Absensi Murid')
@section('page-title', 'Absensi Murid')

@push('styles')
<style>
    .page-header { display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.85rem;margin-bottom:1.5rem; }
    .page-header h2 { font-size:1.15rem;font-weight:700;color:#fff;margin:0 0 .2rem; }
    .page-header p  { font-size:.8rem;color:rgba(255,255,255,.45);margin:0; }

    /* Card aksi absensi */
    .absen-aksi-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .85rem;
        margin-bottom: 1.25rem;
    }
    @media (max-width: 480px) {
        .absen-aksi-grid { grid-template-columns: 1fr; }
    }

    .absen-aksi-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.1rem;
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(74,222,128,.13);
        border-radius: 14px 0 14px 0;
        cursor: pointer;
        text-decoration: none;
        font-family: 'Inter', sans-serif;
        transition: background .18s, border-color .18s, transform .15s;
        text-align: left;
    }
    .absen-aksi-card:hover {
        background: rgba(255,255,255,.07);
        border-color: rgba(74,222,128,.28);
        transform: translateY(-1px);
    }

    .absen-aksi-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem; flex-shrink: 0;
    }

    .absen-aksi-title {
        font-size: .875rem; font-weight: 700;
        color: #fff; margin: 0 0 .15rem;
    }
    .absen-aksi-sub {
        font-size: .72rem; color: rgba(255,255,255,.38); margin: 0;
    }

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

    /* ── Tabel absen murid hari ini ── */
    .absen-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

    .absen-table {
        width: 100%; border-collapse: collapse;
        font-size: .84rem; min-width: 540px;
    }
    .absen-table thead th {
        font-size: .67rem; font-weight: 700;
        color: rgba(74,222,128,.6); text-transform: uppercase;
        letter-spacing: .08em; padding: .8rem 1rem;
        border-bottom: 1px solid rgba(74,222,128,.1);
        white-space: nowrap; background: rgba(255,255,255,.02);
    }
    .absen-table thead th:first-child { padding-left: 1.25rem; }
    .absen-table thead th:last-child  { padding-right: 1.25rem; }

    .absen-table tbody td {
        padding: .7rem 1rem; border-bottom: 1px solid rgba(255,255,255,.04);
        color: rgba(255,255,255,.78); vertical-align: middle;
    }
    .absen-table tbody td:first-child { padding-left: 1.25rem; }
    .absen-table tbody td:last-child  { padding-right: 1.25rem; }
    .absen-table tbody tr:last-child td { border-bottom: none; }
    .absen-table tbody tr:hover td { background: rgba(74,222,128,.03); }

    .absen-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        background: linear-gradient(135deg,#0a7c4a,#4ade80);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .78rem; font-weight: 700; color: #064e2e; flex-shrink: 0;
    }
    .absen-nama { font-size:.875rem; font-weight:600; color:#fff; margin:0 0 .1rem; }
    .absen-user { font-size:.72rem; color:rgba(74,222,128,.65); margin:0; }

    .badge-status {
        display: inline-flex; align-items: center; gap: .3rem;
        border-radius: 50px; padding: .2rem .65rem;
        font-size: .7rem; font-weight: 700; white-space: nowrap;
    }
    .badge-hadir { background:rgba(74,222,128,.12); color:#4ade80; border:1px solid rgba(74,222,128,.22); }
    .badge-izin  { background:rgba(251,191,36,.1);  color:#fbbf24; border:1px solid rgba(251,191,36,.22); }
    .badge-tidak { background:rgba(248,113,113,.1); color:#f87171; border:1px solid rgba(248,113,113,.22); }

    .badge-via {
        display: inline-flex; align-items: center; gap: .25rem;
        font-size: .62rem; font-weight: 600; padding: .15rem .45rem;
        border-radius: 50px; white-space: nowrap;
    }
    .badge-via-qr   { background:rgba(56,189,248,.1); color:#38bdf8; border:1px solid rgba(56,189,248,.2); }
    .badge-via-kode { background:rgba(74,222,128,.08); color:rgba(74,222,128,.7); border:1px solid rgba(74,222,128,.15); }

    .absen-list-header {
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: .5rem;
        padding: .9rem 1.25rem;
        border-bottom: 1px solid rgba(74,222,128,.1);
    }
    .absen-list-title {
        font-size: .82rem; font-weight: 700; color: rgba(255,255,255,.75);
        display: flex; align-items: center; gap: .5rem; margin: 0;
    }
    .absen-list-title i { color: #4ade80; }
    .absen-list-meta { font-size: .72rem; color: rgba(255,255,255,.3); }

    .absen-refresh-btn {
        display: inline-flex; align-items: center; gap: .35rem;
        background: rgba(74,222,128,.08); border: 1px solid rgba(74,222,128,.18);
        border-radius: 8px; color: rgba(74,222,128,.8);
        font-size: .75rem; font-weight: 600; padding: .3rem .75rem;
        cursor: pointer; font-family: 'Inter',sans-serif;
        transition: background .18s;
    }
    .absen-refresh-btn:hover { background: rgba(74,222,128,.16); }

    .absen-empty {
        text-align: center; padding: 3rem 1.5rem;
    }
    .absen-empty i { font-size: 2rem; color: rgba(74,222,128,.18); display: block; margin-bottom: .75rem; }
    .absen-empty p { font-size: .84rem; color: rgba(255,255,255,.28); margin: 0; }

    /* Summary strip */
    .absen-summary {
        display: flex; gap: 1rem; flex-wrap: wrap;
        padding: .75rem 1.25rem;
        border-bottom: 1px solid rgba(74,222,128,.08);
    }
    .sum-chip {
        display: inline-flex; align-items: center; gap: .35rem;
        font-size: .72rem; font-weight: 600;
        padding: .22rem .65rem; border-radius: 50px;
    }
    .sum-hadir { background:rgba(74,222,128,.1);  color:#4ade80; }
    .sum-izin  { background:rgba(251,191,36,.09); color:#fbbf24; }
    .sum-tidak { background:rgba(248,113,113,.09);color:#f87171; }

    /* Hide cols on small screens */
    @media (max-width: 600px) { .col-hide-sm { display: none; } }

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
</div>

{{-- Card aksi absensi --}}
<div class="absen-aksi-grid">
    <button type="button" class="absen-aksi-card" id="btnBuatKode">
        <span class="absen-aksi-icon" style="background:rgba(74,222,128,.12);color:#4ade80;">
            <i class="bi bi-key-fill"></i>
        </span>
        <div>
            <p class="absen-aksi-title">Buat Kode Absen</p>
            <p class="absen-aksi-sub">Generate kode 6 digit untuk murid</p>
        </div>
    </button>
    <a href="{{ route('guru.absen_murid.scan') }}" class="absen-aksi-card">
        <span class="absen-aksi-icon" style="background:rgba(56,189,248,.1);color:#38bdf8;">
            <i class="bi bi-qr-code-scan"></i>
        </span>
        <div>
            <p class="absen-aksi-title">Scan QR Absen</p>
            <p class="absen-aksi-sub">Absen murid lewat scan QR code</p>
        </div>
    </a>
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
       LOAD LIST  —  absen murid hari ini (AJAX real)
    ============================================================ */
    var LIST_URL = '{{ route("guru.absen_murid.hari_ini") }}';

    function loadList() {
        document.getElementById('listContainer').innerHTML =
            '<div class="list-loading"><span class="spinner-green"></span> Memuat data absensi...</div>';

        fetch(LIST_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (d) { renderList(d.rows || [], d.tanggal); })
            .catch(function () {
                document.getElementById('listContainer').innerHTML =
                    '<div class="list-loading" style="color:#f87171;">'
                    + '<i class="bi bi-exclamation-circle me-2"></i>Gagal memuat data. Coba refresh.</div>';
            });
    }

    function renderList(rows, tanggal) {
        var tglFmt = tanggal
            ? new Date(tanggal).toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric' })
            : '-';

        var hadir = rows.filter(function (r) { return r.status === 'hadir'; }).length;
        var izin  = rows.filter(function (r) { return r.status === 'izin';  }).length;
        var tidak = rows.filter(function (r) { return r.status === 'tidak'; }).length;

        var html = '<div class="absen-list-header">'
            + '<p class="absen-list-title"><i class="bi bi-clipboard2-pulse-fill"></i>Absen Murid Hari Ini'
            + '<span class="absen-list-meta">' + esc(tglFmt) + '</span></p>'
            + '<button class="absen-refresh-btn" id="btnRefreshList">'
            + '<i class="bi bi-arrow-clockwise"></i> Refresh</button>'
            + '</div>';

        if (rows.length) {
            html += '<div class="absen-summary">'
                + '<span class="sum-chip sum-hadir"><i class="bi bi-check-circle-fill"></i>' + hadir + ' Hadir</span>'
                + '<span class="sum-chip sum-izin"><i class="bi bi-envelope-fill"></i>'        + izin  + ' Izin</span>'
                + '<span class="sum-chip sum-tidak"><i class="bi bi-x-circle-fill"></i>'       + tidak + ' Tidak Hadir</span>'
                + '</div>';

            html += '<div class="absen-table-wrap"><table class="absen-table" aria-label="Absen murid hari ini">'
                + '<thead><tr>'
                + '<th style="width:48px;">#</th>'
                + '<th>Nama Murid</th>'
                + '<th class="col-hide-sm">Jadwal</th>'
                + '<th>Status</th>'
                + '<th class="col-hide-sm">Via</th>'
                + '<th style="text-align:right;">Jam</th>'
                + '</tr></thead><tbody>';

            rows.forEach(function (r, i) {
                var statusBadge = r.status === 'hadir'
                    ? '<span class="badge-status badge-hadir"><i class="bi bi-check-circle-fill" style="font-size:.45rem;"></i>Hadir</span>'
                    : r.status === 'izin'
                    ? '<span class="badge-status badge-izin"><i class="bi bi-envelope-fill" style="font-size:.45rem;"></i>Izin</span>'
                    : '<span class="badge-status badge-tidak"><i class="bi bi-x-circle-fill" style="font-size:.45rem;"></i>Tidak Hadir</span>';

                var viaBadge = r.via_qr
                    ? '<span class="badge-via badge-via-qr"><i class="bi bi-qr-code"></i>QR</span>'
                    : '<span class="badge-via badge-via-kode"><i class="bi bi-key-fill"></i>Kode</span>';

                html += '<tr>'
                    + '<td style="color:rgba(255,255,255,.3);font-size:.78rem;">' + (i + 1) + '</td>'
                    + '<td><div class="d-flex align-items-center gap-2">'
                    + '<span class="absen-avatar">' + esc(r.avatar) + '</span>'
                    + '<div><p class="absen-nama">' + esc(r.nama) + '</p>'
                    + '<p class="absen-user">@' + esc(r.username) + '</p></div>'
                    + '</div></td>'
                    + '<td class="col-hide-sm" style="font-size:.78rem;color:rgba(255,255,255,.5);">'
                    + esc(r.kelas) + ' · ' + esc(r.mapel)
                    + '<br><span style="color:rgba(255,255,255,.3);">' + esc(r.jam_mulai) + '–' + esc(r.jam_berakhir) + '</span></td>'
                    + '<td>' + statusBadge
                    + (r.alasan ? '<p style="font-size:.68rem;color:rgba(255,255,255,.35);margin:.2rem 0 0;">' + esc(r.alasan) + '</p>' : '')
                    + '</td>'
                    + '<td class="col-hide-sm">' + (r.status === 'hadir' ? viaBadge : '—') + '</td>'
                    + '<td style="text-align:right;font-size:.78rem;color:rgba(255,255,255,.4);">' + esc(r.waktu) + '</td>'
                    + '</tr>';
            });

            html += '</tbody></table></div>';
        } else {
            html += '<div class="absen-empty">'
                + '<i class="bi bi-clipboard2-x"></i>'
                + '<p>Belum ada data absen murid hari ini.</p>'
                + '</div>';
        }

        document.getElementById('listContainer').innerHTML = html;

        /* Tombol refresh */
        var btnRef = document.getElementById('btnRefreshList');
        if (btnRef) {
            btnRef.addEventListener('click', function () { loadList(); });
        }
    }

    function esc(s) {
        var d = document.createElement('div');
        d.appendChild(document.createTextNode(String(s == null ? '' : s)));
        return d.innerHTML;
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
