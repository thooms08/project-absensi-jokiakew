@extends('admin.layouts.app')

@section('title', 'Absensi Guru')
@section('page-title', 'Absensi Guru')

@push('styles')
<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.85rem;
        margin-bottom: 1.5rem;
    }

    .page-header-left h2 { font-size:1.15rem; font-weight:700; color:#fff; margin:0 0 0.2rem; }
    .page-header-left p  { font-size:0.8rem; color:rgba(255,255,255,0.45); margin:0; }

    /* Search + filter bulan */
    .action-bar {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        flex-wrap: wrap;
        margin-bottom: 1.25rem;
    }

    .search-wrap { position:relative; flex:1; min-width:200px; max-width:320px; }
    .search-wrap .bi-search { position:absolute; left:0.85rem; top:50%; transform:translateY(-50%); color:rgba(74,222,128,0.5); font-size:0.85rem; pointer-events:none; }
    .search-input { width:100%; background:rgba(255,255,255,0.05); border:1px solid rgba(74,222,128,0.18); border-radius:10px; color:#e2f5ea; font-size:0.875rem; padding:0.5rem 0.85rem 0.5rem 2.3rem; font-family:'Inter',sans-serif; outline:none; transition:border-color 0.18s; }
    .search-input::placeholder { color:rgba(255,255,255,0.28); }
    .search-input:focus { border-color:rgba(74,222,128,0.45); background:rgba(255,255,255,0.07); }

    .input-bulan {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(74,222,128,0.18);
        border-radius: 10px;
        color: #e2f5ea;
        font-size: 0.855rem;
        padding: 0.5rem 0.85rem;
        font-family: 'Inter', sans-serif;
        outline: none;
        cursor: pointer;
        transition: border-color 0.18s;
    }

    .input-bulan:focus { border-color: rgba(74,222,128,0.45); }
    .input-bulan::-webkit-calendar-picker-indicator {
        filter: invert(0.6) sepia(1) saturate(2) hue-rotate(80deg);
        cursor: pointer;
    }

    /* Container */
    .list-container {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(74,222,128,0.12);
        border-radius: 16px 0 16px 0;
        overflow: hidden;
    }

    .list-loading { text-align:center; padding:2.5rem 1rem; color:rgba(255,255,255,0.35); font-size:0.875rem; }
    .spinner-green { width:26px; height:26px; border:3px solid rgba(74,222,128,0.15); border-top-color:#4ade80; border-radius:50%; animation:spin 0.65s linear infinite; display:inline-block; vertical-align:middle; margin-right:0.5rem; }
    @keyframes spin { to { transform:rotate(360deg); } }

    /* Modal gelap */
    .modal-dark .modal-content  { background:#0a2e1c; border:1px solid rgba(74,222,128,0.18); border-radius:16px; color:#e2f5ea; }
    .modal-dark .modal-header   { border-bottom:1px solid rgba(74,222,128,0.12); padding:1.1rem 1.4rem 1rem; }
    .modal-dark .modal-title    { font-size:1rem; font-weight:700; color:#fff; }
    .modal-dark .modal-footer   { border-top:1px solid rgba(74,222,128,0.12); padding:0.9rem 1.4rem; }
    .modal-dark .btn-close      { filter:invert(1) brightness(0.7); }
    .modal-dark .modal-body     { padding:1.25rem 1.4rem; }

    /* Tabel di dalam modal */
    .modal-table { width:100%; border-collapse:collapse; font-size:0.845rem; }
    .modal-table thead th { font-size:0.67rem; font-weight:700; color:rgba(74,222,128,0.6); text-transform:uppercase; letter-spacing:0.08em; padding:0.65rem 0.85rem; border-bottom:1px solid rgba(74,222,128,0.1); white-space:nowrap; background:rgba(255,255,255,0.02); }
    .modal-table tbody td { padding:0.65rem 0.85rem; border-bottom:1px solid rgba(255,255,255,0.04); vertical-align:middle; }
    .modal-table tbody tr:last-child td { border-bottom:none; }
    .modal-table tbody tr:hover td { background:rgba(74,222,128,0.03); }

    /* Status badge kecil */
    .sbadge { display:inline-flex; align-items:center; gap:0.25rem; border-radius:50px; padding:0.15rem 0.55rem; font-size:0.7rem; font-weight:700; white-space:nowrap; }
    .sbadge-hadir { background:rgba(34,197,94,0.12); color:#4ade80; border:1px solid rgba(34,197,94,0.25); }
    .sbadge-izin  { background:rgba(251,191,36,0.1);  color:#fbbf24; border:1px solid rgba(251,191,36,0.22); }
    .sbadge-tidak { background:rgba(248,113,113,0.1); color:#f87171; border:1px solid rgba(248,113,113,0.22); }

    /* Grid foto */
    .foto-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(130px,1fr)); gap:0.85rem; }
    .foto-item { border-radius:10px; overflow:hidden; border:1px solid rgba(74,222,128,0.12); cursor:pointer; transition:transform 0.18s, border-color 0.18s; background:rgba(255,255,255,0.03); }
    .foto-item:hover { transform:translateY(-2px); border-color:rgba(74,222,128,0.35); }
    .foto-item img { width:100%; aspect-ratio:4/3; object-fit:cover; display:block; }
    .foto-item-caption { padding:0.35rem 0.5rem; }
    .foto-item-tgl { font-size:0.7rem; font-weight:600; color:rgba(255,255,255,0.65); line-height:1.3; }
    .foto-item-meta { font-size:0.65rem; color:rgba(74,222,128,0.55); margin-top:0.1rem; }

    /* Preview foto besar */
    #previewFotoImg { width:100%; border-radius:10px; max-height:70vh; object-fit:contain; display:block; }

    .btn-modal-secondary { background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.12); border-radius:10px; color:rgba(255,255,255,0.72); font-size:0.875rem; font-weight:500; padding:0.55rem 1.2rem; cursor:pointer; transition:background 0.18s; font-family:'Inter',sans-serif; }
    .btn-modal-secondary:hover { background:rgba(255,255,255,0.12); }

    @media (max-width:575.98px) {
        .page-header { flex-direction:column; align-items:flex-start; }
        .modal-dialog { margin:0.5rem; }
        .foto-grid { grid-template-columns:repeat(auto-fill,minmax(110px,1fr)); }
    }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h2>Absensi Guru</h2>
        <p>Rekap kehadiran guru per bulan — hanya lihat</p>
    </div>
</div>

<div class="action-bar">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" id="searchInput" class="search-input"
               placeholder="Cari nama guru..." autocomplete="off">
    </div>

    <input type="month" id="inputBulan" class="input-bulan"
           value="{{ date('Y-m') }}" title="Filter bulan">
</div>

<div class="list-container" id="listContainer">
    <div class="list-loading"><span class="spinner-green"></span> Memuat data...</div>
</div>

{{-- ================================================================
     MODAL DETAIL ABSEN GURU
================================================================ --}}
<div class="modal fade modal-dark" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-clipboard2-check-fill me-2" style="color:#4ade80;"></i>
                    <span id="modalDetailNama">Detail Absensi</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalDetailBody">
                <div class="list-loading"><span class="spinner-green"></span> Memuat...</div>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn-modal-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================
     MODAL GRID FOTO / DOKUMEN
================================================================ --}}
<div class="modal fade modal-dark" id="modalFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-images me-2" style="color:#4ade80;"></i>
                    <span id="modalFotoNama">Bukti Kehadiran</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalFotoBody">
                <div class="list-loading"><span class="spinner-green"></span> Memuat...</div>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn-modal-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================
     MODAL PREVIEW FOTO TUNGGAL
================================================================ --}}
<div class="modal fade modal-dark" id="modalPreviewFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-image me-2" style="color:#4ade80;"></i>
                    <span id="previewFotoLabel">Bukti Foto</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1rem;">
                <img id="previewFotoImg" src="" alt="Bukti foto kehadiran" />
                <div id="previewFotoMeta" style="margin-top:0.75rem; font-size:0.78rem; color:rgba(255,255,255,0.5); text-align:center;"></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var searchTimer = null;

    /* ============================================================
       LOAD LIST
    ============================================================ */
    function loadList(search, bulan) {
        search = search || '';
        bulan  = bulan  || document.getElementById('inputBulan').value;

        var container = document.getElementById('listContainer');
        container.innerHTML = '<div class="list-loading"><span class="spinner-green"></span> Memuat data...</div>';

        fetch('{{ route("admin.absen_guru.list") }}?search=' + encodeURIComponent(search) + '&bulan=' + encodeURIComponent(bulan), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.text(); })
        .then(function (html) {
            container.innerHTML = html;
            bindListEvents();
        })
        .catch(function () {
            container.innerHTML = '<div class="list-loading" style="color:#f87171;">Gagal memuat data.</div>';
        });
    }

    /* ============================================================
       EVENTS
    ============================================================ */
    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchTimer);
        var val = this.value.trim();
        searchTimer = setTimeout(function () { loadList(val); }, 350);
    });

    document.getElementById('inputBulan').addEventListener('change', function () {
        loadList(document.getElementById('searchInput').value.trim(), this.value);
    });

    function bindListEvents() {
        var c = document.getElementById('listContainer');
        c.querySelectorAll('[data-detail-id]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                bukaModalDetail(this.dataset.detailId, this.dataset.nama);
            });
        });
        c.querySelectorAll('[data-foto-id]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                bukaModalFoto(this.dataset.fotoId, this.dataset.nama);
            });
        });
    }

    /* ============================================================
       MODAL DETAIL
    ============================================================ */
    var modalDetail    = new bootstrap.Modal(document.getElementById('modalDetail'));
    var modalFoto      = new bootstrap.Modal(document.getElementById('modalFoto'));
    var modalPreview   = new bootstrap.Modal(document.getElementById('modalPreviewFoto'));

    function bukaModalDetail(userId, nama) {
        document.getElementById('modalDetailNama').textContent = nama || 'Detail Absensi';
        document.getElementById('modalDetailBody').innerHTML =
            '<div class="list-loading"><span class="spinner-green"></span> Memuat...</div>';
        modalDetail.show();

        var bulan = document.getElementById('inputBulan').value;
        fetch('{{ url("admin/absen-guru/detail") }}/' + userId + '?bulan=' + encodeURIComponent(bulan), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            renderDetailTable(data);
        })
        .catch(function () {
            document.getElementById('modalDetailBody').innerHTML =
                '<p style="color:#f87171;text-align:center;">Gagal memuat data.</p>';
        });
    }

    function renderDetailTable(data) {
        var absens = data.absens || [];
        if (!absens.length) {
            document.getElementById('modalDetailBody').innerHTML =
                '<p style="text-align:center;color:rgba(255,255,255,0.35);padding:1.5rem 0;">Belum ada data absensi pada periode ini.</p>';
            return;
        }

        var html = '<div style="overflow-x:auto;">'
            + '<table class="modal-table"><thead><tr>'
            + '<th>Tanggal</th><th>Hari</th><th>Kelas</th><th>Mapel</th><th>Jam</th><th>Status</th><th>Keterangan</th>'
            + '</tr></thead><tbody>';

        absens.forEach(function (a) {
            var statusClass = a.status === 'hadir' ? 'sbadge-hadir'
                            : a.status === 'izin'  ? 'sbadge-izin'
                            : 'sbadge-tidak';
            var statusIcon  = a.status === 'hadir' ? 'bi-check-circle-fill'
                            : a.status === 'izin'  ? 'bi-envelope-fill'
                            : 'bi-x-circle-fill';

            html += '<tr>'
                + '<td style="white-space:nowrap;font-size:0.8rem;color:rgba(255,255,255,0.65);">' + escHtml(a.tanggal) + '</td>'
                + '<td style="white-space:nowrap;">' + escHtml(a.hari) + '</td>'
                + '<td><span style="font-weight:600;color:#22d3ee;">' + escHtml(a.kelas) + '</span></td>'
                + '<td><span style="color:#fbbf24;font-size:0.8rem;">' + escHtml(a.mapel) + '</span></td>'
                + '<td style="white-space:nowrap;font-size:0.78rem;color:rgba(74,222,128,0.7);">' + escHtml(a.jam_mulai) + '–' + escHtml(a.jam_berakhir) + '</td>'
                + '<td><span class="sbadge ' + statusClass + '"><i class="bi ' + statusIcon + '" style="font-size:0.6rem;"></i>' + escHtml(a.status) + '</span></td>'
                + '<td style="font-size:0.78rem;color:rgba(255,255,255,0.5);max-width:180px;">'
                +   (a.alasan && a.alasan !== '-' ? '<i class="bi bi-chat-quote me-1" style="color:rgba(255,255,255,0.28);"></i>' + escHtml(a.alasan) : '<span style="opacity:0.3;">–</span>')
                + '</td>'
                + '</tr>';
        });

        html += '</tbody></table></div>';
        document.getElementById('modalDetailBody').innerHTML = html;
    }

    /* ============================================================
       MODAL FOTO / DOKUMEN
    ============================================================ */
    function bukaModalFoto(userId, nama) {
        document.getElementById('modalFotoNama').textContent = (nama || 'Bukti Kehadiran') + ' — Bukti Foto';
        document.getElementById('modalFotoBody').innerHTML =
            '<div class="list-loading"><span class="spinner-green"></span> Memuat...</div>';
        modalFoto.show();

        var bulan = document.getElementById('inputBulan').value;
        fetch('{{ url("admin/absen-guru/foto") }}/' + userId + '?bulan=' + encodeURIComponent(bulan), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            renderFotoGrid(data);
        })
        .catch(function () {
            document.getElementById('modalFotoBody').innerHTML =
                '<p style="color:#f87171;text-align:center;">Gagal memuat foto.</p>';
        });
    }

    function renderFotoGrid(data) {
        var fotos = data.fotos || [];
        if (!fotos.length) {
            document.getElementById('modalFotoBody').innerHTML =
                '<p style="text-align:center;color:rgba(255,255,255,0.35);padding:2rem 0;">'
                + '<i class="bi bi-images" style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.2;"></i>'
                + 'Belum ada foto bukti kehadiran pada periode ini.</p>';
            return;
        }

        var html = '<div class="foto-grid">';
        fotos.forEach(function (f) {
            html += '<div class="foto-item" onclick="previewFoto(\'' + escAttr(f.foto_url) + '\',\'' + escAttr(f.tanggal) + '\',\'' + escAttr(f.kelas) + '\',\'' + escAttr(f.mapel) + '\')">'
                + '<img src="' + escAttr(f.foto_url) + '" alt="Bukti foto" loading="lazy">'
                + '<div class="foto-item-caption">'
                +   '<div class="foto-item-tgl">' + escHtml(f.tanggal) + '</div>'
                +   '<div class="foto-item-meta">' + escHtml(f.kelas) + ' · ' + escHtml(f.mapel) + '</div>'
                + '</div>'
                + '</div>';
        });
        html += '</div>';
        document.getElementById('modalFotoBody').innerHTML = html;
    }

    /* ============================================================
       MODAL PREVIEW FOTO TUNGGAL
    ============================================================ */
    window.previewFoto = function (url, tanggal, kelas, mapel) {
        document.getElementById('previewFotoImg').src     = url;
        document.getElementById('previewFotoLabel').textContent = 'Bukti Foto — ' + kelas;
        document.getElementById('previewFotoMeta').textContent  = tanggal + ' · ' + kelas + ' · ' + mapel;
        modalPreview.show();
    };

    /* ============================================================
       UTIL
    ============================================================ */
    function escHtml(str) {
        return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function escAttr(str) {
        return String(str || '').replace(/'/g, '&#39;').replace(/"/g, '&quot;');
    }

    /* ============================================================
       INIT
    ============================================================ */
    loadList('', '{{ date("Y-m") }}');

}());
</script>
@endpush
