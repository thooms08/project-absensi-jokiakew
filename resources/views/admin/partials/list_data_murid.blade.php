<style>
    /* ============================================================
       TAB NAV
    ============================================================ */
    .murid-tab-nav {
        display: flex;
        align-items: center;
        gap: 0;
        border-bottom: 1px solid rgba(74,222,128,0.12);
        padding: 0 1.25rem;
        flex-wrap: wrap;
    }

    .murid-tab-btn {
        position: relative;
        background: none;
        border: none;
        border-bottom: 2px solid transparent;
        color: rgba(255,255,255,0.45);
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.85rem 1rem;
        cursor: pointer;
        transition: color 0.18s, border-color 0.18s;
        white-space: nowrap;
        font-family: 'Inter', sans-serif;
        display: flex;
        align-items: center;
        gap: 0.45rem;
        margin-bottom: -1px;
    }

    .murid-tab-btn:hover { color: rgba(255,255,255,0.78); }

    .murid-tab-btn.active {
        color: #4ade80;
        border-bottom-color: #4ade80;
    }

    .murid-tab-badge {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.12rem 0.48rem;
        border-radius: 50px;
        background: rgba(74,222,128,0.12);
        color: rgba(255,255,255,0.5);
        border: 1px solid rgba(74,222,128,0.15);
        line-height: 1.4;
    }

    .murid-tab-btn.active .murid-tab-badge {
        background: rgba(74,222,128,0.18);
        color: #4ade80;
        border-color: rgba(74,222,128,0.3);
    }

    .murid-tab-btn-nonaktif .murid-tab-badge {
        background: rgba(248,113,113,0.1);
        color: rgba(248,113,113,0.65);
        border-color: rgba(248,113,113,0.2);
    }

    .murid-tab-btn-nonaktif.active .murid-tab-badge {
        background: rgba(248,113,113,0.18);
        color: #f87171;
        border-color: rgba(248,113,113,0.35);
    }

    .murid-tab-btn-nonaktif.active {
        color: #f87171;
        border-bottom-color: #f87171;
    }

    /* ============================================================
       TABLE AREA
    ============================================================ */
    .murid-table-wrap {
        padding: 0;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .murid-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.84rem;
        min-width: 560px;
    }

    .murid-table thead th {
        font-size: 0.67rem;
        font-weight: 700;
        color: rgba(74,222,128,0.6);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 0.85rem 1rem;
        border-bottom: 1px solid rgba(74,222,128,0.1);
        white-space: nowrap;
        background: rgba(255,255,255,0.02);
    }

    .murid-table thead th:first-child { padding-left: 1.25rem; }
    .murid-table thead th:last-child  { padding-right: 1.25rem; }

    .murid-table tbody td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.78);
        vertical-align: middle;
    }

    .murid-table tbody td:first-child { padding-left: 1.25rem; }
    .murid-table tbody td:last-child  { padding-right: 1.25rem; }

    .murid-table tbody tr:last-child td { border-bottom: none; }

    .murid-table tbody tr:hover td {
        background: rgba(74,222,128,0.035);
    }

    /* ============================================================
       AVATAR
    ============================================================ */
    .murid-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(74,222,128,0.25);
        flex-shrink: 0;
    }

    .murid-avatar-initials {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0a7c4a, #4ade80);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.82rem;
        font-weight: 700;
        color: #064e2e;
        flex-shrink: 0;
        border: 2px solid rgba(74,222,128,0.2);
    }

    .murid-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: #ffffff;
        margin: 0 0 0.1rem;
        line-height: 1.3;
    }

    .murid-username {
        font-size: 0.73rem;
        color: rgba(74,222,128,0.65);
        margin: 0;
    }

    .murid-email {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.5);
    }

    /* ============================================================
       STATUS BADGE
    ============================================================ */
    .badge-murid-aktif {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: rgba(74,222,128,0.12);
        color: #4ade80;
        border: 1px solid rgba(74,222,128,0.22);
        border-radius: 50px;
        padding: 0.2rem 0.65rem;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-murid-nonaktif {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: rgba(248,113,113,0.1);
        color: #f87171;
        border: 1px solid rgba(248,113,113,0.22);
        border-radius: 50px;
        padding: 0.2rem 0.65rem;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
    }

    /* ============================================================
       TOMBOL AKSI
    ============================================================ */
    .btn-aksi-m {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 0.85rem;
        transition: background 0.18s, transform 0.15s;
        padding: 0;
        flex-shrink: 0;
    }

    .btn-aksi-m:hover { transform: translateY(-1px); }

    .btn-m-edit {
        background: rgba(74,222,128,0.12);
        color: #4ade80;
        border: 1px solid rgba(74,222,128,0.2);
    }
    .btn-m-edit:hover { background: rgba(74,222,128,0.22); }

    .btn-m-hapus {
        background: rgba(248,113,113,0.1);
        color: #f87171;
        border: 1px solid rgba(248,113,113,0.2);
    }
    .btn-m-hapus:hover { background: rgba(248,113,113,0.2); }

    .btn-m-restore {
        background: rgba(74,222,128,0.1);
        color: #4ade80;
        border: 1px solid rgba(74,222,128,0.18);
    }
    .btn-m-restore:hover { background: rgba(74,222,128,0.2); }

    .btn-m-qr {
        background: rgba(56,189,248,0.1);
        color: #38bdf8;
        border: 1px solid rgba(56,189,248,0.2);
    }
    .btn-m-qr:hover { background: rgba(56,189,248,0.2); }

    .btn-m-force {
        background: rgba(248,113,113,0.08);
        color: #f87171;
        border: 1px solid rgba(248,113,113,0.18);
    }
    .btn-m-force:hover { background: rgba(248,113,113,0.18); }

    /* ============================================================
       EMPTY STATE
    ============================================================ */
    .murid-empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
    }

    .murid-empty-icon {
        font-size: 2.5rem;
        color: rgba(74,222,128,0.2);
        display: block;
        margin-bottom: 0.75rem;
    }

    .murid-empty-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: rgba(255,255,255,0.45);
        margin: 0 0 0.3rem;
    }

    .murid-empty-text {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.28);
        margin: 0;
    }

    .murid-deleted-text {
        font-size: 0.73rem;
        color: rgba(248,113,113,0.65);
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 767.98px) {
        .m-col-hide-sm { display: none; }
    }

    @media (max-width: 575.98px) {
        .m-col-hide-xs { display: none; }
        .murid-table   { min-width: 420px; }
    }

    /* ============================================================
       MODAL QR CODE
    ============================================================ */
    .modal-qr .modal-content {
        background: #0a2e1c;
        border: 1px solid rgba(56,189,248,0.22);
        border-radius: 18px;
        color: #e2f5ea;
    }
    .modal-qr .modal-header {
        border-bottom: 1px solid rgba(56,189,248,0.12);
        padding: 1.1rem 1.4rem 1rem;
    }
    .modal-qr .modal-title  { font-size: 1rem; font-weight: 700; color: #fff; }
    .modal-qr .modal-footer { border-top: 1px solid rgba(56,189,248,0.12); padding: .9rem 1.4rem; }
    .modal-qr .btn-close    { filter: invert(1) brightness(.7); }
    .modal-qr .modal-body   { padding: 1.5rem 1.4rem; text-align: center; }

    .qr-canvas-wrap {
        display: inline-block;
        background: #fff;
        border-radius: 14px;
        padding: 16px;
        margin-bottom: 1rem;
        box-shadow: 0 4px 24px rgba(0,0,0,0.35);
    }
    .qr-murid-name {
        font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: .2rem;
    }
    .qr-murid-user {
        font-size: .78rem; color: rgba(56,189,248,.75); margin-bottom: 1rem;
    }
    .btn-download-qr {
        display: inline-flex; align-items: center; gap: .5rem;
        background: linear-gradient(135deg, #0369a1, #38bdf8);
        border: none; border-radius: 10px; color: #fff;
        font-size: .875rem; font-weight: 600; padding: .55rem 1.35rem;
        cursor: pointer; font-family: 'Inter', sans-serif; text-decoration: none;
        transition: opacity .18s, transform .18s;
    }
    .btn-download-qr:hover { opacity: .88; transform: translateY(-1px); color: #fff; }
    .btn-modal-sec-qr {
        background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12);
        border-radius: 10px; color: rgba(255,255,255,.72);
        font-size: .875rem; font-weight: 500; padding: .55rem 1.2rem;
        cursor: pointer; font-family: 'Inter', sans-serif;
    }
    .btn-modal-sec-qr:hover { background: rgba(255,255,255,.12); }
</style>

{{-- ================================================================
     TAB NAV
================================================================ --}}
<div class="murid-tab-nav">
    <button
        class="murid-tab-btn {{ $tab === 'aktif' ? 'active' : '' }}"
        data-tab="aktif"
        aria-label="Tab murid aktif"
    >
        <i class="bi bi-person-check-fill"></i>
        Murid Aktif
        @php $jumlahAktif = \App\Models\User::where('role','murid')->count(); @endphp
        <span class="murid-tab-badge">{{ $jumlahAktif }}</span>
    </button>

    <button
        class="murid-tab-btn murid-tab-btn-nonaktif {{ $tab === 'nonaktif' ? 'active' : '' }}"
        data-tab="nonaktif"
        aria-label="Tab murid tidak aktif"
    >
        <i class="bi bi-person-dash-fill"></i>
        Tidak Aktif
        @php $jumlahNonaktif = \App\Models\User::onlyTrashed()->where('role','murid')->count(); @endphp
        <span class="murid-tab-badge">{{ $jumlahNonaktif }}</span>
    </button>
</div>

{{-- ================================================================
     TABLE
================================================================ --}}
<div class="murid-table-wrap">

    @if($murid->isEmpty())

        <div class="murid-empty-state">
            <i class="bi bi-{{ $tab === 'aktif' ? 'people-fill' : 'person-slash' }} murid-empty-icon"></i>
            <p class="murid-empty-title">
                {{ $tab === 'aktif' ? 'Belum ada data murid aktif.' : 'Tidak ada murid yang dinonaktifkan.' }}
            </p>
            <p class="murid-empty-text">
                {{ $tab === 'aktif' ? 'Klik tombol "+ Murid" untuk menambahkan murid baru.' : 'Murid yang dihapus akan muncul di sini.' }}
            </p>
        </div>

    @else

        <table class="murid-table"
               aria-label="Daftar {{ $tab === 'aktif' ? 'murid aktif' : 'murid tidak aktif' }}">
            <thead>
                <tr>
                    <th style="width:48px;">#</th>
                    <th>Nama Murid</th>
                    <th class="m-col-hide-sm">Email</th>
                    <th class="m-col-hide-xs">Status</th>
                    @if($tab === 'nonaktif')
                        <th class="m-col-hide-sm">Dinonaktifkan</th>
                    @endif
                    <th style="width:100px; text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($murid as $index => $item)
                <tr>
                    {{-- Nomor --}}
                    <td style="color:rgba(255,255,255,0.3); font-size:0.78rem;">
                        {{ $index + 1 }}
                    </td>

                    {{-- Avatar + Nama --}}
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($item->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->image))
                                <img
                                    src="{{ asset('storage/' . $item->image) }}"
                                    alt="Foto {{ $item->name }}"
                                    class="murid-avatar"
                                    loading="lazy"
                                >
                            @else
                                <span class="murid-avatar-initials" aria-hidden="true">
                                    {{ strtoupper(mb_substr($item->name, 0, 1)) }}
                                </span>
                            @endif
                            <div style="min-width:0;">
                                <p class="murid-name text-truncate"
                                   style="max-width:180px;" title="{{ $item->name }}">
                                    {{ $item->name }}
                                </p>
                                <p class="murid-username">{{ '@' . $item->username }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Email --}}
                    <td class="m-col-hide-sm">
                        <span class="murid-email">{{ $item->email }}</span>
                    </td>

                    {{-- Status --}}
                    <td class="m-col-hide-xs">
                        @if($tab === 'aktif')
                            <span class="badge-murid-aktif">
                                <i class="bi bi-circle-fill" style="font-size:0.4rem;"></i>
                                Aktif
                            </span>
                        @else
                            <span class="badge-murid-nonaktif">
                                <i class="bi bi-circle-fill" style="font-size:0.4rem;"></i>
                                Tidak Aktif
                            </span>
                        @endif
                    </td>

                    {{-- Tanggal dinonaktifkan (tab nonaktif saja) --}}
                    @if($tab === 'nonaktif')
                    <td class="m-col-hide-sm">
                        <span class="murid-deleted-text">
                            {{ $item->deleted_at ? $item->deleted_at->translatedFormat('d M Y') : '-' }}
                        </span>
                    </td>
                    @endif

                    {{-- Aksi --}}
                    <td>
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            @if($tab === 'aktif')
                                <button
                                    type="button"
                                    class="btn-aksi-m btn-m-qr"
                                    data-qr-id="{{ $item->id }}"
                                    data-qr-nama="{{ $item->name }}"
                                    data-qr-username="{{ $item->username }}"
                                    title="Lihat QR Absen"
                                    aria-label="QR absen {{ $item->name }}"
                                >
                                    <i class="bi bi-qr-code"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn-aksi-m btn-m-edit"
                                    data-edit-id="{{ $item->id }}"
                                    title="Edit data murid"
                                    aria-label="Edit {{ $item->name }}"
                                >
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn-aksi-m btn-m-hapus"
                                    data-delete-id="{{ $item->id }}"
                                    data-delete-nama="{{ $item->name }}"
                                    title="Nonaktifkan murid"
                                    aria-label="Nonaktifkan {{ $item->name }}"
                                >
                                    <i class="bi bi-person-dash-fill"></i>
                                </button>
                            @else
                                <button
                                    type="button"
                                    class="btn-aksi-m btn-m-restore"
                                    data-restore-id="{{ $item->id }}"
                                    data-restore-nama="{{ $item->name }}"
                                    title="Aktifkan kembali"
                                    aria-label="Aktifkan kembali {{ $item->name }}"
                                >
                                    <i class="bi bi-person-check-fill"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn-aksi-m btn-m-force"
                                    data-force-id="{{ $item->id }}"
                                    data-force-nama="{{ $item->name }}"
                                    title="Hapus permanen"
                                    aria-label="Hapus permanen {{ $item->name }}"
                                >
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            @endif
                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>

    @endif

</div>

{{-- ================================================================
     MODAL QR CODE MURID
================================================================ --}}
<div class="modal fade modal-qr" id="modalQrMurid" tabindex="-1" aria-hidden="true" aria-labelledby="modalQrMuridTitle">
    <div class="modal-dialog modal-dialog-centered" style="max-width:360px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalQrMuridTitle">
                    <i class="bi bi-qr-code me-2" style="color:#38bdf8;"></i>
                    QR Code Absen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p class="qr-murid-name" id="qrMuridName">—</p>
                <p class="qr-murid-user" id="qrMuridUser">@—</p>
                <div class="qr-canvas-wrap" id="qrCanvasWrap">
                    {{-- QR akan di-render di sini oleh JS --}}
                </div>
                <p style="font-size:.72rem;color:rgba(255,255,255,.3);margin-top:.75rem;margin-bottom:0;">
                    QR ini unik milik murid tersebut. Scan saat absensi berlangsung.
                </p>
            </div>
            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-modal-sec-qr" data-bs-dismiss="modal">Tutup</button>
                <a id="btnDownloadQr" href="#" download="qr-absen.png" class="btn-download-qr">
                    <i class="bi bi-download"></i> Download QR
                </a>
            </div>
        </div>
    </div>
</div>

{{-- QRCode.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
(function () {
    'use strict';

    var modalQrEl   = document.getElementById('modalQrMurid');
    var btnDownload = document.getElementById('btnDownloadQr');
    var qrWrap      = document.getElementById('qrCanvasWrap');
    var modalQr     = new bootstrap.Modal(modalQrEl);

    function generateQR(userId, nama, username) {
        document.getElementById('qrMuridName').textContent = nama;
        document.getElementById('qrMuridUser').textContent = '@' + username;

        /* Bersihkan QR sebelumnya */
        qrWrap.innerHTML = '';

        /* Reset download link */
        btnDownload.href     = '#';
        btnDownload.download = 'qr-absen-' + username + '.png';

        /* Generate QR baru */
        var qr = new QRCode(qrWrap, {
            text        : String(userId),
            width       : 200,
            height      : 200,
            colorDark   : '#000000',
            colorLight  : '#ffffff',
            correctLevel: QRCode.CorrectLevel.M,
        });

        /* Tunggu render, ambil data URL untuk download */
        setTimeout(function () {
            var img = qrWrap.querySelector('img');
            var cnv = qrWrap.querySelector('canvas');

            if (cnv) {
                btnDownload.href = cnv.toDataURL('image/png');
            } else if (img) {
                img.onload = function () {
                    var c = document.createElement('canvas');
                    c.width  = img.naturalWidth  || 200;
                    c.height = img.naturalHeight || 200;
                    c.getContext('2d').drawImage(img, 0, 0);
                    btnDownload.href = c.toDataURL('image/png');
                };
                if (img.complete && img.naturalWidth) img.onload();
            }
        }, 200);
    }

    /* Delegasi klik tombol QR di tabel */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-qr-id]');
        if (!btn) return;
        generateQR(btn.dataset.qrId, btn.dataset.qrNama, btn.dataset.qrUsername);
        modalQr.show();
    });
}());
</script>
