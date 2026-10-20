@extends('murid.layouts.app')

@section('title', 'Beranda')
@section('page-title', 'Beranda')

@push('styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, rgba(34, 197, 94, .2) 0%, rgba(74, 222, 128, .08) 100%);
        border: 1px solid rgba(74, 222, 128, .22);
        border-radius: 20px 0 20px 0;
        padding: 2rem 1.75rem;
        display: flex; align-items: center; gap: 1.5rem;
        flex-wrap: wrap; margin-bottom: 1.75rem;
        position: relative; overflow: hidden;
    }

    .welcome-card::before {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(74, 222, 128, .06) 0%, transparent 60%);
        pointer-events: none;
    }

    .welcome-avatar {
        width: 64px; height: 64px; border-radius: 50%;
        background: linear-gradient(135deg, #16a34a, #4ade80);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem; font-weight: 800; color: #fff; flex-shrink: 0;
        border: 3px solid rgba(74, 222, 128, .35);
    }

    .welcome-title { font-size: 1.2rem; font-weight: 800; color: #fff; margin: 0 0 .25rem; }
    .welcome-sub   { font-size: .82rem; color: rgba(226, 245, 234, .6); margin: 0; }

    /* Summary cards */
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    .sum-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(74, 222, 128, .14);
        border-radius: 16px 0 16px 0;
        padding: 1.25rem 1rem;
        display: flex; flex-direction: column; align-items: center; gap: .4rem;
        transition: border-color .2s;
    }

    .sum-card:hover { border-color: rgba(74, 222, 128, .35); }

    .sum-icon {
        width: 42px; height: 42px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem; margin-bottom: .1rem;
    }

    .sum-icon-hadir  { background: rgba(34,197,94,.12);  color: #4ade80; }
    .sum-icon-izin   { background: rgba(251,191,36,.1);  color: #fbbf24; }
    .sum-icon-tidak  { background: rgba(248,113,113,.1); color: #f87171; }
    .sum-icon-total  { background: rgba(45, 212, 191, .12); color: #2dd4bf; } /* Warna teal/cyan untuk Total agar beda tapi tetap senada */

    .sum-val  { font-size: 1.8rem; font-weight: 800; color: #fff; line-height: 1; }
    .sum-label { font-size: .72rem; font-weight: 600; color: rgba(226, 245, 234, .45); text-transform: uppercase; letter-spacing: .06em; }

    .sum-sub { font-size: .7rem; color: rgba(226, 245, 234, .28); margin-top: .1rem; }

    /* Loading */
    .spinner-green {
        width: 22px; height: 22px;
        border: 3px solid rgba(74, 222, 128, .15);
        border-top-color: #4ade80;
        border-radius: 50%;
        animation: spin .65s linear infinite;
        display: inline-block; vertical-align: middle;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    /* Shortcut */
    .shortcut-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px,1fr));
        gap: 1rem;
    }

    .shortcut-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(74, 222, 128, .14);
        border-radius: 14px;
        padding: 1.25rem 1rem;
        display: flex; flex-direction: column; align-items: center; gap: .5rem;
        text-decoration: none; color: rgba(226, 245, 234, .7);
        font-size: .82rem; font-weight: 600;
        transition: background .18s, border-color .18s, transform .15s;
        text-align: center;
    }

    .shortcut-card:hover {
        background: rgba(74, 222, 128, .12);
        border-color: rgba(74, 222, 128, .35);
        color: #4ade80; transform: translateY(-2px);
    }

    .shortcut-card i { font-size: 1.5rem; }

    /* ----------------------------------------------------------------
       MODAL QR SAYA
    ---------------------------------------------------------------- */
    .modal-qr-saya .modal-content {
        background: #0a2e1c;
        border: 1px solid rgba(56,189,248,.22);
        border-radius: 18px;
        color: #e2f5ea;
    }
    .modal-qr-saya .modal-header { border-bottom: 1px solid rgba(56,189,248,.12); padding: 1.1rem 1.4rem 1rem; }
    .modal-qr-saya .modal-title  { font-size: 1rem; font-weight: 700; color: #fff; }
    .modal-qr-saya .modal-footer { border-top: 1px solid rgba(56,189,248,.12); padding: .9rem 1.4rem; }
    .modal-qr-saya .btn-close    { filter: invert(1) brightness(.7); }
    .modal-qr-saya .modal-body   { padding: 1.5rem 1.4rem; text-align: center; }

    .qr-saya-wrap {
        display: inline-block;
        background: #fff;
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 1rem;
        box-shadow: 0 4px 24px rgba(0,0,0,.35);
    }
    .qr-saya-label {
        font-size: .78rem;
        color: rgba(56,189,248,.75);
        margin-bottom: 1rem;
    }
    .btn-dl-qr-saya {
        display: inline-flex; align-items: center; gap: .5rem;
        background: linear-gradient(135deg, #0369a1, #38bdf8);
        border: none; border-radius: 10px; color: #fff;
        font-size: .875rem; font-weight: 600; padding: .55rem 1.35rem;
        cursor: pointer; font-family: 'Inter', sans-serif;
        text-decoration: none; transition: opacity .18s, transform .18s;
    }
    .btn-dl-qr-saya:hover { opacity: .88; transform: translateY(-1px); color: #fff; }
    .btn-sec-qr-saya {
        background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12);
        border-radius: 10px; color: rgba(255,255,255,.72);
        font-size: .875rem; font-weight: 500; padding: .55rem 1.2rem;
        cursor: pointer; font-family: 'Inter', sans-serif;
    }
    .btn-sec-qr-saya:hover { background: rgba(255,255,255,.12); }
</style>
@endpush

@section('content')

{{-- Welcome --}}
<div class="welcome-card">
    <div class="welcome-avatar">
        {{ strtoupper(substr(Auth::user()->name ?? 'M', 0, 1)) }}
    </div>
    <div>
        <p class="welcome-title">Halo, {{ Auth::user()->name ?? 'Murid' }}!</p>
        <p class="welcome-sub">Selamat datang di portal absensi. Pantau kehadiranmu di sini.</p>
    </div>
</div>

{{-- Summary Bulan Ini --}}
<p style="font-size:.75rem;font-weight:700;color:rgba(74, 222, 128, .65);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.75rem;">
    <i class="bi bi-calendar3 me-1"></i>Ringkasan Bulan Ini
</p>

<div class="summary-grid" id="summaryGrid">
    <div style="grid-column:1/-1;text-align:center;padding:1.5rem;color:rgba(226, 245, 234, .3);">
        <span class="spinner-green"></span> Memuat...
    </div>
</div>

{{-- Shortcut --}}
<p style="font-size:.75rem;font-weight:700;color:rgba(74, 222, 128, .65);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.75rem;">
    <i class="bi bi-lightning-fill me-1"></i>Menu Cepat
</p>

<div class="shortcut-grid">
    <a href="{{ route('murid.absen') }}" class="shortcut-card">
        <i class="bi bi-clipboard2-check-fill" style="color:#4ade80;"></i>
        Absensi
    </a>
    <a href="{{ route('murid.riwayat_absen') }}" class="shortcut-card">
        <i class="bi bi-clock-history" style="color:#fbbf24;"></i>
        Riwayat
    </a>
    <button type="button" class="shortcut-card" id="btnQrSaya" style="cursor:pointer;">
        <i class="bi bi-qr-code" style="color:#38bdf8;"></i>
        QR Saya
    </button>
</div>

{{-- Modal QR Saya --}}
<div class="modal fade modal-qr-saya" id="modalQrSaya" tabindex="-1" aria-hidden="true" aria-labelledby="modalQrSayaTitle">
    <div class="modal-dialog modal-dialog-centered" style="max-width:360px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalQrSayaTitle">
                    <i class="bi bi-qr-code me-2" style="color:#38bdf8;"></i>
                    QR Code Absen Saya
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p style="font-size:1rem;font-weight:700;color:#fff;margin-bottom:.25rem;">
                    {{ Auth::user()->name }}
                </p>
                <p class="qr-saya-label">{{ '@' . Auth::user()->username }}</p>
                <div class="qr-saya-wrap" id="qrSayaWrap">
                    {{-- QR di-render JS --}}
                </div>
                <p style="font-size:.72rem;color:rgba(255,255,255,.3);margin:0;">
                    Tunjukkan QR ini kepada guru saat absensi berlangsung.
                </p>
            </div>
            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-sec-qr-saya" data-bs-dismiss="modal">Tutup</button>
                <a id="btnDownloadQrSaya" href="#" download="qr-absen-{{ Auth::user()->username }}.png" class="btn-dl-qr-saya">
                    <i class="bi bi-download"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- QRCode.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
(function () {
    'use strict';

    /* ================================================================
       SUMMARY
    ================================================================ */
    fetch('{{ route("murid.absen.summary") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        var bulan = new Date().toLocaleDateString('id-ID',{month:'long',year:'numeric'});
        document.getElementById('summaryGrid').innerHTML =
            card('bi-check-circle-fill','sum-icon-hadir', d.hadir, 'Hadir')
          + card('bi-envelope-fill',     'sum-icon-izin',  d.izin,  'Izin')
          + card('bi-x-circle-fill',     'sum-icon-tidak', d.tidak, 'Tidak Hadir')
          + card('bi-bar-chart-fill',    'sum-icon-total', d.total, 'Total');
    })
    .catch(function () {
        document.getElementById('summaryGrid').innerHTML =
            '<div style="grid-column:1/-1;text-align:center;color:#f87171;padding:1rem;">Gagal memuat data.</div>';
    });

    function card(icon, cls, val, label) {
        return '<div class="sum-card">'
             + '<div class="sum-icon '+cls+'"><i class="bi '+icon+'"></i></div>'
             + '<div class="sum-val">' + (val||0) + '</div>'
             + '<div class="sum-label">' + label + '</div>'
             + '</div>';
    }

    /* ================================================================
       MODAL QR SAYA
    ================================================================ */
    var MY_ID       = {{ Auth::id() }};
    var MY_USERNAME = '{{ Auth::user()->username }}';

    var modalQrSayaEl = document.getElementById('modalQrSaya');
    var modalQrSaya   = new bootstrap.Modal(modalQrSayaEl);
    var qrSayaWrap    = document.getElementById('qrSayaWrap');
    var btnDlSaya     = document.getElementById('btnDownloadQrSaya');
    var qrSayaBuilt   = false;

    document.getElementById('btnQrSaya').addEventListener('click', function () {
        if (!qrSayaBuilt) {
            qrSayaWrap.innerHTML = '';
            var qr = new QRCode(qrSayaWrap, {
                text        : String(MY_ID),
                width       : 200,
                height      : 200,
                colorDark   : '#000000',
                colorLight  : '#ffffff',
                correctLevel: QRCode.CorrectLevel.M,
            });

            setTimeout(function () {
                var cnv = qrSayaWrap.querySelector('canvas');
                var img = qrSayaWrap.querySelector('img');
                if (cnv) {
                    btnDlSaya.href = cnv.toDataURL('image/png');
                } else if (img) {
                    img.onload = function () {
                        var c = document.createElement('canvas');
                        c.width  = img.naturalWidth  || 200;
                        c.height = img.naturalHeight || 200;
                        c.getContext('2d').drawImage(img, 0, 0);
                        btnDlSaya.href = c.toDataURL('image/png');
                    };
                    if (img.complete && img.naturalWidth) img.onload();
                }
                btnDlSaya.download = 'qr-absen-' + MY_USERNAME + '.png';
            }, 200);

            qrSayaBuilt = true;
        }
        modalQrSaya.show();
    });
}());
</script>
@endpush