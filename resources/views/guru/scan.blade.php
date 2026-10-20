@extends('guru.layouts.app')

@section('title', 'Scan QR Absen Murid')
@section('page-title', 'Scan QR Absen Murid')

@push('styles')
<style>
/* ================================================================
   HEADER
================================================================ */
.scan-page-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: .75rem;
    margin-bottom: 1.25rem;
    flex-wrap: wrap;
}
.scan-page-header h2 { font-size: 1.1rem; font-weight: 700; color: #fff; margin: 0 0 .2rem; }
.scan-page-header p  { font-size: .78rem; color: rgba(255,255,255,.4); margin: 0; }

.btn-back {
    display: inline-flex; align-items: center; gap: .4rem;
    padding: .45rem 1rem;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.13);
    border-radius: 10px;
    color: rgba(255,255,255,.72); font-size: .82rem; font-weight: 500;
    text-decoration: none; white-space: nowrap; flex-shrink: 0;
    transition: background .18s, color .18s;
}
.btn-back:hover { background: rgba(255,255,255,.13); color: #fff; }

/* ================================================================
   ALERT
================================================================ */
.alert-scan {
    display: none; align-items: center; gap: .8rem;
    padding: .85rem 1rem; border-radius: 12px;
    margin-bottom: 1rem; font-size: .85rem; font-weight: 500; line-height: 1.4;
}
.alert-scan-ok   { background: rgba(74,222,128,.13);  border: 1px solid rgba(74,222,128,.28);  color: #4ade80; }
.alert-scan-warn { background: rgba(251,191,36,.1);   border: 1px solid rgba(251,191,36,.25);  color: #fbbf24; }
.alert-scan-err  { background: rgba(248,113,113,.11); border: 1px solid rgba(248,113,113,.25); color: #f87171; }
.alert-scan-icon { font-size: 1.15rem; flex-shrink: 0; }
.alert-scan-close {
    margin-left: auto; background: none; border: none;
    color: inherit; opacity: .6; cursor: pointer; font-size: .95rem; padding: 0; line-height: 1;
}
.alert-scan-close:hover { opacity: 1; }

/* ================================================================
   CARD WRAPPER (kamera, jadwal, riwayat — sama lebarnya)
================================================================ */
.scan-card {
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(74,222,128,.14);
    border-radius: 16px 0 16px 0;
    overflow: hidden;
    margin-bottom: 1rem;
    width: 100%;
    max-width: 520px;   /* cukup di desktop, penuh di mobile */
}
@media (min-width: 700px) {
    .scan-card { margin-left: auto; margin-right: auto; }
}

.scan-card-header {
    display: flex; align-items: center; gap: .55rem;
    padding: .8rem 1rem;
    border-bottom: 1px solid rgba(74,222,128,.1);
    font-size: .82rem; font-weight: 600; color: rgba(255,255,255,.75);
    background: rgba(255,255,255,.025);
}
.scan-card-header i { color: #4ade80; }

/* ================================================================
   AREA KAMERA
   Strategi: biarkan html5-qrcode mengatur tinggi videonya sendiri.
   Kita hanya sembunyikan UI bawaannya dan berikan lebar 100%.
   JANGAN ubah posisi/ukuran video lewat JS — itu yang bikin hitam.
================================================================ */
.qr-video-wrap {
    width: 100%;
    background: #000;
    position: relative;
    /* overflow:hidden penting agar lebar video tidak meluber */
    overflow: hidden;
}

/* html5-qrcode menaruh div ini langsung di dalam #qr-reader */
#qr-reader {
    width: 100% !important;
}

/* Video: biarkan tingginya otomatis (diatur library), paksa lebar 100% */
#qr-reader video {
    width: 100% !important;
    height: auto !important;
    display: block !important;
    max-height: 75vw;           /* di mobile maks 75% lebar layar */
}
@media (min-width: 700px) {
    #qr-reader video { max-height: 400px; }
}

/* Sembunyikan seluruh dashboard bawaan (tombol, teks, file input) */
#qr-reader__dashboard              { display: none !important; }
#qr-reader__header_message         { display: none !important; }
#qr-reader__status_span            { display: none !important; }
#qr-reader__camera_permission_button { display: none !important; }
#qr-reader__filescan_input         { display: none !important; }
#qr-reader img                     { display: none !important; }

/* ── Kotak bidik overlay — absolut di atas video ── */
.qr-overlay {
    position: absolute;
    inset: 0;
    display: none;              /* ditampilkan JS setelah kamera aktif */
    align-items: center;
    justify-content: center;
    pointer-events: none;
    z-index: 5;
}
.qr-box {
    width: clamp(130px, 55%, 210px);
    aspect-ratio: 1/1;
    border: 2px solid rgba(74,222,128,.9);
    border-radius: 10px;
    box-shadow: 0 0 0 9999px rgba(0,0,0,.42);
    position: relative;
}
/* Sudut dekoratif */
.qr-box::before, .qr-box::after,
.qr-c1, .qr-c2 {
    content: '';
    position: absolute;
    width: 18px; height: 18px;
    border-color: #4ade80; border-style: solid;
}
.qr-box::before { top:-2px;    left:-2px;   border-width:3px 0 0 3px; border-radius:8px 0 0 0; }
.qr-box::after  { bottom:-2px; right:-2px;  border-width:0 3px 3px 0; border-radius:0 0 8px 0; }
.qr-c1          { top:-2px;    right:-2px;  border-width:3px 3px 0 0; border-radius:0 8px 0 0; }
.qr-c2          { bottom:-2px; left:-2px;   border-width:0 0 3px 3px; border-radius:0 0 0 8px; }
/* Garis scan */
.qr-line {
    position: absolute; left:5px; right:5px; height:2px;
    background: linear-gradient(90deg, transparent, #4ade80, transparent);
    animation: sweep 1.8s ease-in-out infinite; border-radius:1px;
}
@keyframes sweep { 0%,100% { top:8%; } 50% { top:86%; } }

/* Status bar */
.camera-status {
    display: flex; align-items: center; gap: .5rem;
    padding: .6rem 1rem; font-size: .72rem; color: rgba(255,255,255,.38);
    border-top: 1px solid rgba(74,222,128,.07);
    background: rgba(255,255,255,.02);
}
.scan-dot {
    width:7px; height:7px; border-radius:50%; background:#4ade80; flex-shrink:0;
    animation: blink 1.4s ease-in-out infinite;
}
.scan-dot.idle  { background: rgba(255,255,255,.22); animation:none; }
.scan-dot.error { background: #f87171; animation:none; }
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

/* ================================================================
   JADWAL & RIWAYAT — dalam scan-card yang sama
================================================================ */
.card-body { padding: 1rem; }

.f-label {
    font-size:.73rem; font-weight:600; color:rgba(255,255,255,.55);
    margin-bottom:.35rem; display:block;
}
.f-select {
    width:100%; background:rgba(255,255,255,.06);
    border:1px solid rgba(74,222,128,.18); border-radius:10px;
    color:#e2f5ea; font-size:.875rem; padding:.5rem 2rem .5rem .8rem;
    outline:none; font-family:'Inter',sans-serif;
    appearance:none; -webkit-appearance:none;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='11' height='11' viewBox='0 0 16 16'%3E%3Cpath fill='rgba(74,222,128,0.5)' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat:no-repeat; background-position:right .8rem center; cursor:pointer;
}
.f-select option { background:#0a2e1c; }
.f-select:focus  { border-color:rgba(74,222,128,.5); box-shadow:0 0 0 3px rgba(74,222,128,.07); }
.f-hint { font-size:.7rem; color:rgba(255,255,255,.28); margin-top:.4rem; }

/* Riwayat */
.riwayat-head {
    display:flex; align-items:center; justify-content:space-between;
    padding:.8rem 1rem; border-top:1px solid rgba(74,222,128,.1);
}
.riwayat-title {
    font-size:.82rem; font-weight:600; color:rgba(255,255,255,.72);
    display:flex; align-items:center; gap:.45rem; margin:0;
}
.riwayat-title i { color:#4ade80; }
.btn-clear {
    background:none; border:none; color:rgba(248,113,113,.55);
    font-size:.7rem; font-weight:600; cursor:pointer;
    font-family:'Inter',sans-serif; padding:.15rem .4rem; border-radius:6px;
    transition:background .15s,color .15s;
}
.btn-clear:hover { background:rgba(248,113,113,.1); color:#f87171; }

.riwayat-list { max-height:250px; overflow-y:auto; }
.riwayat-row {
    display:flex; align-items:center; gap:.65rem;
    padding:.55rem 1rem; border-top:1px solid rgba(255,255,255,.035);
}
.r-avatar {
    width:32px; height:32px; border-radius:50%;
    background:linear-gradient(135deg,#0a7c4a,#4ade80);
    display:flex; align-items:center; justify-content:center;
    font-size:.75rem; font-weight:700; color:#064e2e; flex-shrink:0;
}
.r-nama { font-size:.82rem; font-weight:600; color:#fff; margin:0 0 .1rem; }
.r-meta { font-size:.68rem; color:rgba(255,255,255,.35); margin:0; }
.r-badge {
    margin-left:auto; font-size:.62rem; font-weight:700;
    padding:.15rem .5rem; border-radius:50px; white-space:nowrap; flex-shrink:0;
}
.r-ok  { background:rgba(74,222,128,.15); color:#4ade80; border:1px solid rgba(74,222,128,.25); }
.r-dup { background:rgba(251,191,36,.1);  color:#fbbf24; border:1px solid rgba(251,191,36,.2); }
.r-err { background:rgba(248,113,113,.1); color:#f87171; border:1px solid rgba(248,113,113,.2); }
.r-empty { text-align:center; padding:1.25rem 1rem; font-size:.75rem; color:rgba(255,255,255,.22); }
</style>
@endpush

@section('content')

{{-- Header --}}
<div class="scan-page-header">
    <div>
        <h2><i class="bi bi-qr-code-scan me-2" style="color:#4ade80;"></i>Scan QR Absen Murid</h2>
        <p>Arahkan kamera ke QR Code milik murid untuk mencatat kehadiran</p>
    </div>
    <a href="{{ route('guru.absen_murid') }}" class="btn-back">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

{{-- Alert --}}
<div class="alert-scan alert-scan-ok" id="alertOk" role="alert" aria-live="polite">
    <i class="bi bi-check-circle-fill alert-scan-icon"></i>
    <span id="alertOkMsg"></span>
    <button class="alert-scan-close" onclick="tutupAlert('alertOk')" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
</div>
<div class="alert-scan alert-scan-warn" id="alertWarn" role="alert" aria-live="polite">
    <i class="bi bi-exclamation-triangle-fill alert-scan-icon"></i>
    <span id="alertWarnMsg"></span>
    <button class="alert-scan-close" onclick="tutupAlert('alertWarn')" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
</div>
<div class="alert-scan alert-scan-err" id="alertErr" role="alert" aria-live="polite">
    <i class="bi bi-x-circle-fill alert-scan-icon"></i>
    <span id="alertErrMsg"></span>
    <button class="alert-scan-close" onclick="tutupAlert('alertErr')" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
</div>

{{-- ── KAMERA ── --}}
<div class="scan-card">
    <div class="scan-card-header">
        <i class="bi bi-camera-video-fill"></i>
        Kamera Scanner
    </div>
    <div class="qr-video-wrap" id="qrVideoWrap">
        <div id="qr-reader"></div>
        <div class="qr-overlay" id="qrOverlay">
            <div class="qr-box">
                <span class="qr-c1"></span>
                <span class="qr-c2"></span>
                <div class="qr-line"></div>
            </div>
        </div>
    </div>
    <div class="camera-status">
        <span class="scan-dot idle" id="scanDot"></span>
        <span id="scanStatus">Memulai kamera…</span>
    </div>
</div>

{{-- ── JADWAL ── --}}
<div class="scan-card">
    <div class="scan-card-header">
        <i class="bi bi-calendar2-week-fill"></i>
        Pilih Jadwal
    </div>
    <div class="card-body">
        <label class="f-label" for="selectJadwal">
            Jadwal Hari Ini <span style="color:#f87171;">*</span>
        </label>
        <select id="selectJadwal" class="f-select" aria-label="Pilih jadwal">
            <option value="">— Memuat jadwal... —</option>
        </select>
        <p class="f-hint">
            <i class="bi bi-info-circle me-1"></i>
            Pilih jadwal sebelum scan. Murid yang di-scan otomatis dicatat
            <strong style="color:#4ade80;">hadir</strong>.
        </p>
    </div>
</div>

{{-- ── RIWAYAT ── --}}
<div class="scan-card">
    <div class="riwayat-head">
        <p class="riwayat-title">
            <i class="bi bi-clock-history"></i>
            Riwayat Scan Sesi Ini
        </p>
        <button class="btn-clear" id="btnClear" aria-label="Hapus riwayat">
            <i class="bi bi-trash3 me-1"></i>Hapus
        </button>
    </div>
    <div class="riwayat-list" id="riwayatList">
        <p class="r-empty">Belum ada scan.</p>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
(function () {
    'use strict';

    var CSRF       = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
    var SCAN_URL   = '{{ route("guru.absen_murid.scan_qr") }}';
    var JADWAL_URL = '{{ route("guru.kode_absen.jadwal_hari_ini") }}';

    var riwayat     = [];
    var lastScanned = null;
    var processing  = false;

    /* ============================================================
       STATUS
    ============================================================ */
    function setStatus(txt, mode) {
        document.getElementById('scanDot').className = 'scan-dot ' + (mode || 'idle');
        document.getElementById('scanStatus').textContent = txt;
    }

    /* ============================================================
       ALERT
    ============================================================ */
    window.tutupAlert = function (id) {
        var el = document.getElementById(id);
        if (el) el.style.display = 'none';
    };

    function tampil(type, html) {
        ['alertOk','alertWarn','alertErr'].forEach(function (id) {
            document.getElementById(id).style.display = 'none';
        });
        var ids  = { ok:'alertOk',    warn:'alertWarn',    err:'alertErr'    };
        var msgs = { ok:'alertOkMsg', warn:'alertWarnMsg', err:'alertErrMsg' };
        var el = document.getElementById(ids[type]);
        var mg = document.getElementById(msgs[type]);
        if (!el || !mg) return;
        mg.innerHTML = html;
        el.style.display = 'flex';
        clearTimeout(el._t);
        el._t = setTimeout(function () { el.style.display = 'none'; }, 5000);
    }

    /* ============================================================
       JADWAL
    ============================================================ */
    function loadJadwal() {
        fetch(JADWAL_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                var sel = document.getElementById('selectJadwal');
                var js  = d.jadwals || [];
                if (!js.length) {
                    sel.innerHTML = '<option value="">— Tidak ada jadwal hari ini —</option>';
                    return;
                }
                sel.innerHTML = '<option value="">— Pilih Jadwal —</option>';
                js.forEach(function (j) {
                    var o = document.createElement('option');
                    o.value           = j.id;
                    o.textContent     = j.kelas + ' · ' + j.mapel + ' (' + j.waktu_mulai + '–' + j.waktu_berakhir + ')';
                    o.dataset.kelas   = j.kelas;
                    o.dataset.mapel   = j.mapel;
                    sel.appendChild(o);
                });
            })
            .catch(function () {
                document.getElementById('selectJadwal').innerHTML =
                    '<option value="">— Gagal memuat jadwal —</option>';
            });
    }

    /* ============================================================
       RIWAYAT
    ============================================================ */
    function esc(s) {
        var d = document.createElement('div');
        d.appendChild(document.createTextNode(String(s)));
        return d.innerHTML;
    }

    function pushRiwayat(item) { riwayat.unshift(item); renderRiwayat(); }

    function renderRiwayat() {
        var el = document.getElementById('riwayatList');
        if (!riwayat.length) { el.innerHTML = '<p class="r-empty">Belum ada scan.</p>'; return; }
        el.innerHTML = riwayat.map(function (r) {
            var bc = r.s === 'ok'  ? 'r-ok'  : r.s === 'dup' ? 'r-dup' : 'r-err';
            var bt = r.s === 'ok'  ? '✓ Hadir' : r.s === 'dup' ? '⚠ Sudah Absen' : '✗ Gagal';
            return '<div class="riwayat-row">'
                + '<div class="r-avatar">' + r.ini + '</div>'
                + '<div style="min-width:0;flex:1;">'
                + '<p class="r-nama">' + esc(r.nama) + '</p>'
                + '<p class="r-meta">' + esc(r.kelas) + ' · ' + esc(r.mapel) + ' · ' + r.jam + '</p>'
                + '</div>'
                + '<span class="r-badge ' + bc + '">' + bt + '</span>'
                + '</div>';
        }).join('');
    }

    document.getElementById('btnClear').addEventListener('click', function () {
        riwayat = []; renderRiwayat();
    });

    /* ============================================================
       SCAN CALLBACK
    ============================================================ */
    function onScan(decoded) {
        if (processing) return;
        if (lastScanned === decoded) return;

        var jadwalId = document.getElementById('selectJadwal').value;
        if (!jadwalId) {
            tampil('warn', '<strong>Pilih jadwal terlebih dahulu</strong> sebelum scan QR murid.');
            return;
        }
        if (!/^\d+$/.test(decoded.trim())) {
            tampil('err', 'QR tidak valid — pastikan QR yang di-scan adalah QR absen murid.');
            return;
        }

        processing  = true;
        lastScanned = decoded;
        setStatus('Memproses…', 'idle');

        var sel   = document.getElementById('selectJadwal');
        var opt   = sel.options[sel.selectedIndex];
        var kelas = (opt && opt.dataset.kelas) ? opt.dataset.kelas : '-';
        var mapel = (opt && opt.dataset.mapel) ? opt.dataset.mapel : '-';
        var jam   = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

        fetch(SCAN_URL, {
            method  : 'POST',
            headers : {
                'X-CSRF-TOKEN'    : CSRF,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type'    : 'application/json',
            },
            body: JSON.stringify({
                user_id  : parseInt(decoded.trim(), 10),
                jadwal_id: parseInt(jadwalId, 10),
            }),
        })
        .then(function (r) { return r.json().then(function (d) { return { st: r.status, d: d }; }); })
        .then(function (res) {
            processing = false;
            var d = res.d;
            /* reset cooldown setelah 2 detik */
            setTimeout(function () { lastScanned = null; }, 2000);

            if (d.success) {
                tampil('ok',
                    '<strong>' + esc(d.nama) + '</strong> berhasil dicatat hadir'
                    + (d.kelas ? ' · ' + esc(d.kelas) : '')
                    + (d.mapel ? ' · ' + esc(d.mapel) : '') + '.'
                );
                setStatus('Siap scan berikutnya', 'scanning');
                pushRiwayat({ s:'ok',  ini:(d.nama||'M').charAt(0).toUpperCase(), nama:d.nama||'-', kelas:d.kelas||kelas, mapel:d.mapel||mapel, jam:jam });
            } else if (res.st === 422 && d.sudah_absen) {
                tampil('warn', '<strong>' + esc(d.nama||'Murid') + '</strong> sudah tercatat absen untuk jadwal ini.');
                pushRiwayat({ s:'dup', ini:(d.nama||'M').charAt(0).toUpperCase(), nama:d.nama||'-', kelas:d.kelas||kelas, mapel:d.mapel||mapel, jam:jam });
            } else {
                tampil('err', d.message || 'Gagal mencatat absen.');
                pushRiwayat({ s:'err', ini:'?', nama:d.nama||'Unknown', kelas:kelas, mapel:mapel, jam:jam });
                setStatus('Siap scan', 'scanning');
            }
        })
        .catch(function () {
            processing  = false;
            lastScanned = null;
            tampil('err', 'Koneksi gagal. Periksa jaringan dan coba lagi.');
            setStatus('Siap scan', 'scanning');
        });
    }

    /* ============================================================
       START SCANNER
       — Tidak ada MutationObserver, tidak ada forceVideoFit.
       — Library dibiarkan mengatur videonya sendiri.
       — Kita hanya atur qrbox proporsional & tampilkan overlay CSS.
    ============================================================ */
    function startScanner() {
        var scanner = new Html5Qrcode('qr-reader');

        /* Hitung sisi qrbox: 55% lebar container, min 130px, maks 220px */
        var wrap = document.getElementById('qrVideoWrap');
        var w    = wrap ? wrap.offsetWidth : window.innerWidth;
        var side = Math.round(Math.min(Math.max(w * 0.55, 130), 220));

        var cfg = {
            fps        : 10,
            qrbox      : { width: side, height: side },
            aspectRatio: 1.333,   /* 4:3 — natural untuk kamera mobile */
            disableFlip: false,
        };

        function onStarted() {
            /* Tampilkan overlay bidik setelah kamera hidup */
            document.getElementById('qrOverlay').style.display = 'flex';
            setStatus('Siap scan — arahkan QR ke kotak bidik', 'scanning');
        }

        scanner.start({ facingMode: 'environment' }, cfg, onScan, null)
            .then(onStarted)
            .catch(function () {
                /* Fallback kamera depan */
                scanner.start({ facingMode: 'user' }, cfg, onScan, null)
                    .then(function () {
                        onStarted();
                        setStatus('Siap scan (kamera depan) — arahkan QR ke kotak bidik', 'scanning');
                    })
                    .catch(function (e) {
                        setStatus('Kamera tidak dapat diakses. ' + e, 'error');
                        document.getElementById('scanDot').className = 'scan-dot error';
                    });
            });
    }

    /* ============================================================
       INIT
    ============================================================ */
    loadJadwal();
    startScanner();
}());
</script>
@endpush
