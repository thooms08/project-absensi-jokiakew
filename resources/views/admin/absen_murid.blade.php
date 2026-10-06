@extends('admin.layouts.app')

@section('title', 'Absensi Murid')
@section('page-title', 'Absensi Murid')

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

    .page-header-left h2 { font-size: 1.15rem; font-weight: 700; color: #fff; margin: 0 0 .2rem; }
    .page-header-left p  { font-size: 0.8rem; color: rgba(255,255,255,.45); margin: 0; }

    /* Filter */
    .action-bar {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        flex-wrap: wrap;
        margin-bottom: 1.25rem;
    }

    .search-wrap { position:relative; flex:1; min-width:200px; max-width:320px; }
    .search-wrap .bi-search { position:absolute;left:.85rem;top:50%;transform:translateY(-50%);color:rgba(74,222,128,.5);font-size:.85rem;pointer-events:none; }
    .search-input { width:100%;background:rgba(255,255,255,.05);border:1px solid rgba(74,222,128,.18);border-radius:10px;color:#e2f5ea;font-size:.875rem;padding:.5rem .85rem .5rem 2.3rem;font-family:'Inter',sans-serif;outline:none;transition:border-color .18s; }
    .search-input::placeholder { color:rgba(255,255,255,.28); }
    .search-input:focus { border-color:rgba(74,222,128,.45);background:rgba(255,255,255,.07); }

    .input-bulan {
        background:rgba(255,255,255,.05);border:1px solid rgba(74,222,128,.18);border-radius:10px;
        color:#e2f5ea;font-size:.855rem;padding:.5rem .85rem;font-family:'Inter',sans-serif;
        outline:none;cursor:pointer;transition:border-color .18s;
    }

    .input-bulan:focus { border-color:rgba(74,222,128,.45); }
    .input-bulan::-webkit-calendar-picker-indicator {
        filter:invert(.6) sepia(1) saturate(2) hue-rotate(80deg);cursor:pointer;
    }

    /* Container */
    .list-container {
        background:rgba(255,255,255,.04);border:1px solid rgba(74,222,128,.12);
        border-radius:16px 0 16px 0;overflow:hidden;
    }

    .list-loading { text-align:center;padding:2.5rem 1rem;color:rgba(255,255,255,.35);font-size:.875rem; }
    .spinner-green { width:26px;height:26px;border:3px solid rgba(74,222,128,.15);border-top-color:#4ade80;border-radius:50%;animation:spin .65s linear infinite;display:inline-block;vertical-align:middle;margin-right:.5rem; }
    @keyframes spin { to { transform:rotate(360deg); } }

    /* Modal */
    .modal-dark .modal-content { background:#0a2e1c;border:1px solid rgba(74,222,128,.18);border-radius:16px;color:#e2f5ea; }
    .modal-dark .modal-header  { border-bottom:1px solid rgba(74,222,128,.12);padding:1.1rem 1.4rem 1rem; }
    .modal-dark .modal-title   { font-size:1rem;font-weight:700;color:#fff; }
    .modal-dark .modal-footer  { border-top:1px solid rgba(74,222,128,.12);padding:.9rem 1.4rem; }
    .modal-dark .btn-close     { filter:invert(1) brightness(.7); }
    .modal-dark .modal-body    { padding:1.25rem 1.4rem; }

    .modal-table { width:100%;border-collapse:collapse;font-size:.845rem; }
    .modal-table thead th { font-size:.67rem;font-weight:700;color:rgba(74,222,128,.6);text-transform:uppercase;letter-spacing:.08em;padding:.65rem .85rem;border-bottom:1px solid rgba(74,222,128,.1);white-space:nowrap;background:rgba(255,255,255,.02); }
    .modal-table tbody td { padding:.65rem .85rem;border-bottom:1px solid rgba(255,255,255,.04);vertical-align:middle; }
    .modal-table tbody tr:last-child td { border-bottom:none; }
    .modal-table tbody tr:hover td { background:rgba(74,222,128,.03); }

    .sbadge { display:inline-flex;align-items:center;gap:.25rem;border-radius:50px;padding:.15rem .55rem;font-size:.7rem;font-weight:700;white-space:nowrap; }
    .sbadge-hadir { background:rgba(34,197,94,.12);color:#4ade80;border:1px solid rgba(34,197,94,.25); }
    .sbadge-izin  { background:rgba(251,191,36,.1);color:#fbbf24;border:1px solid rgba(251,191,36,.22); }
    .sbadge-tidak { background:rgba(248,113,113,.1);color:#f87171;border:1px solid rgba(248,113,113,.22); }

    #previewFotoImg { width:100%;border-radius:10px;max-height:70vh;object-fit:contain;display:block; }

    .btn-modal-secondary { background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:10px;color:rgba(255,255,255,.72);font-size:.875rem;font-weight:500;padding:.55rem 1.2rem;cursor:pointer;font-family:'Inter',sans-serif; }
    .btn-modal-secondary:hover { background:rgba(255,255,255,.12); }

    @media (max-width:575.98px) { .modal-dialog { margin:.5rem; } }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h2>Absensi Murid</h2>
        <p>Rekap kehadiran murid per bulan — hanya lihat</p>
    </div>
</div>

<div class="action-bar">
    <div class="search-wrap">
        <i class="bi bi-search"></i>
        <input type="text" id="searchInput" class="search-input"
               placeholder="Cari nama murid..." autocomplete="off">
    </div>
    <input type="month" id="inputBulan" class="input-bulan"
           value="{{ date('Y-m') }}" title="Filter bulan">
</div>

<div class="list-container" id="listContainer">
    <div class="list-loading"><span class="spinner-green"></span> Memuat data...</div>
</div>

{{-- Modal Detail --}}
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

{{-- Modal Preview Foto --}}
<div class="modal fade modal-dark" id="modalPreviewFoto" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-image me-2" style="color:#4ade80;"></i><span id="previewFotoLabel">Foto Bukti</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:1rem;">
                <img id="previewFotoImg" src="" alt="Foto bukti">
                <div id="previewFotoMeta" style="margin-top:.75rem;font-size:.78rem;color:rgba(255,255,255,.5);text-align:center;"></div>
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
    var modalDetail  = new bootstrap.Modal(document.getElementById('modalDetail'));
    var modalPreview = new bootstrap.Modal(document.getElementById('modalPreviewFoto'));

    function loadList(search, bulan) {
        search = search || '';
        bulan  = bulan  || document.getElementById('inputBulan').value;
        var c  = document.getElementById('listContainer');
        c.innerHTML = '<div class="list-loading"><span class="spinner-green"></span> Memuat data...</div>';

        fetch('{{ route("admin.absen_murid.list") }}?search='+encodeURIComponent(search)+'&bulan='+encodeURIComponent(bulan), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r){ return r.text(); })
        .then(function(html){ c.innerHTML = html; bindListEvents(); })
        .catch(function(){ c.innerHTML = '<div class="list-loading" style="color:#f87171;">Gagal memuat data.</div>'; });
    }

    document.getElementById('searchInput').addEventListener('input', function(){
        clearTimeout(searchTimer);
        var val = this.value.trim();
        searchTimer = setTimeout(function(){ loadList(val); }, 350);
    });

    document.getElementById('inputBulan').addEventListener('change', function(){
        loadList(document.getElementById('searchInput').value.trim(), this.value);
    });

    function bindListEvents() {
        var c = document.getElementById('listContainer');
        c.querySelectorAll('[data-detail-id]').forEach(function(btn){
            btn.addEventListener('click', function(){ bukaDetail(this.dataset.detailId, this.dataset.nama); });
        });
    }

    function bukaDetail(userId, nama) {
        document.getElementById('modalDetailNama').textContent = nama || 'Detail Absensi';
        document.getElementById('modalDetailBody').innerHTML =
            '<div class="list-loading"><span class="spinner-green"></span> Memuat...</div>';
        modalDetail.show();

        var bulan = document.getElementById('inputBulan').value;
        fetch('{{ url("admin/absen-murid/detail") }}/'+userId+'?bulan='+encodeURIComponent(bulan), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r){ return r.json(); })
        .then(function(d){ renderDetailTable(d); })
        .catch(function(){ document.getElementById('modalDetailBody').innerHTML = '<p style="color:#f87171;text-align:center;">Gagal memuat.</p>'; });
    }

    function renderDetailTable(data) {
        var absens = data.absens || [];
        if (!absens.length) {
            document.getElementById('modalDetailBody').innerHTML =
                '<p style="text-align:center;color:rgba(255,255,255,.35);padding:1.5rem 0;">Belum ada data absensi.</p>';
            return;
        }

        var html = '<div style="overflow-x:auto;"><table class="modal-table"><thead><tr>'
            + '<th>Tanggal</th><th>Hari</th><th>Kelas</th><th>Mapel</th><th>Jam</th><th>Status</th><th>Ket.</th><th>Foto</th>'
            + '</tr></thead><tbody>';

        absens.forEach(function(a) {
            var cls = a.status==='hadir' ? 'sbadge-hadir' : a.status==='izin' ? 'sbadge-izin' : 'sbadge-tidak';
            var ico = a.status==='hadir' ? 'bi-check-circle-fill' : a.status==='izin' ? 'bi-envelope-fill' : 'bi-x-circle-fill';
            html += '<tr>'
                + '<td style="font-size:.78rem;color:rgba(255,255,255,.65);white-space:nowrap;">'+escHtml(a.tanggal)+'</td>'
                + '<td>'+escHtml(a.hari)+'</td>'
                + '<td style="font-weight:600;color:#22d3ee;">'+escHtml(a.kelas)+'</td>'
                + '<td style="color:#fbbf24;font-size:.8rem;">'+escHtml(a.mapel)+'</td>'
                + '<td style="white-space:nowrap;font-size:.78rem;color:rgba(74,222,128,.7);">'+escHtml(a.jam_mulai)+'–'+escHtml(a.jam_berakhir)+'</td>'
                + '<td><span class="sbadge '+cls+'"><i class="bi '+ico+'" style="font-size:.6rem;"></i>'+escHtml(a.status)+'</span></td>'
                + '<td style="font-size:.78rem;color:rgba(255,255,255,.5);max-width:160px;">'+(a.alasan && a.alasan!=='-' ? escHtml(a.alasan) : '<span style="opacity:.3;">–</span>')+'</td>'
                + '<td>'
                + (a.foto_selfie_url ? '<a href="'+escHtml(a.foto_selfie_url)+'" target="_blank" style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:6px;background:rgba(74,222,128,.1);color:#4ade80;border:1px solid rgba(74,222,128,.2);text-decoration:none;" title="Foto selfie"><i class="bi bi-camera-fill" style="font-size:.75rem;"></i></a>' : '')
                + (a.foto_izin_url ? '<a href="'+escHtml(a.foto_izin_url)+'" target="_blank" style="display:inline-flex;align-items:center;justify-content:center;width:28px;height:28px;border-radius:6px;background:rgba(251,191,36,.08);color:#fbbf24;border:1px solid rgba(251,191,36,.2);text-decoration:none;margin-left:3px;" title="Foto izin"><i class="bi bi-image" style="font-size:.75rem;"></i></a>' : '')
                + '</td>'
                + '</tr>';
        });

        html += '</tbody></table></div>';
        document.getElementById('modalDetailBody').innerHTML = html;
    }

    function escHtml(s) {
        return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    loadList('', '{{ date("Y-m") }}');
}());
</script>
@endpush
