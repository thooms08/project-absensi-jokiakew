@extends('murid.layouts.app')

@section('title', 'Riwayat Absen')
@section('page-title', 'Riwayat Absen')

@push('styles')
<style>
    .action-bar { display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;margin-bottom:1.25rem; }
    .input-bulan {
        background:rgba(255,255,255,.05);border:1px solid rgba(74, 222, 128, .18);
        border-radius:10px;color:#e2f5ea;font-size:.855rem;padding:.5rem .85rem;
        font-family:'Inter',sans-serif;outline:none;cursor:pointer;transition:border-color .18s;
    }
    .input-bulan:focus { border-color:rgba(74, 222, 128, .45); }
    .input-bulan::-webkit-calendar-picker-indicator {
        /* Diubah ke hijau */
        filter:invert(.7) sepia(1) saturate(3) hue-rotate(80deg);cursor:pointer;
    }

    .list-container {
        background:rgba(255,255,255,.04);border:1px solid rgba(74, 222, 128, .12);
        border-radius:16px 0 16px 0;overflow:hidden;
    }

    .list-loading { text-align:center;padding:2.5rem 1rem;color:rgba(226, 245, 234, .35);font-size:.875rem; }

    .spinner-green {
        width:24px;height:24px;border:3px solid rgba(74, 222, 128, .15);border-top-color:#4ade80;
        border-radius:50%;animation:spin .65s linear infinite;display:inline-block;vertical-align:middle;margin-right:.5rem;
    }

    @keyframes spin { to { transform:rotate(360deg); } }
</style>
@endpush

@section('content')

<div class="action-bar">
    <input type="month" id="inputBulan" class="input-bulan"
           value="{{ date('Y-m') }}" title="Filter bulan">
</div>

<div class="list-container" id="listContainer">
    <div class="list-loading"><span class="spinner-green"></span> Memuat riwayat...</div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    'use strict';

    var searchTimer = null;

    function loadList(bulan) {
        bulan = bulan || document.getElementById('inputBulan').value;
        var c = document.getElementById('listContainer');
        c.innerHTML = '<div class="list-loading"><span class="spinner-green"></span> Memuat riwayat...</div>';

        fetch('{{ url("murid/absen/summary") }}?bulan=' + encodeURIComponent(bulan), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r){ return r.json(); })
        .then(function(d){
            var html = '<div style="padding:1.25rem;">'
                + '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:.85rem;margin-bottom:1.5rem;">'
                + chip('bi-check-circle-fill','#4ade80','rgba(34,197,94,.12)',d.hadir,'Hadir')
                + chip('bi-envelope-fill','#fbbf24','rgba(251,191,36,.1)',d.izin,'Izin')
                + chip('bi-x-circle-fill','#f87171','rgba(248,113,113,.1)',d.tidak,'Tidak Hadir')
                // Chip Total diganti ke emerald green
                + chip('bi-bar-chart-fill','#10b981','rgba(16,185,129,.1)',d.total,'Total') 
                + '</div>'
                + '<p style="font-size:.78rem;color:rgba(226, 245, 234, .28);text-align:center;">Detail riwayat per sesi akan ditampilkan di sini.</p>'
                + '</div>';
            c.innerHTML = html;
        })
        .catch(function(){
            c.innerHTML = '<div class="list-loading" style="color:#f87171;">Gagal memuat riwayat.</div>';
        });
    }

    function chip(icon, color, bg, val, label) {
        return '<div style="background:' + bg + ';border:1px solid ' + color.replace('#','rgba(') + ',0.22);border-radius:12px;padding:1rem;text-align:center;">'
            + '<i class="bi ' + icon + '" style="font-size:1.2rem;color:' + color + ';display:block;margin-bottom:.35rem;"></i>'
            + '<div style="font-size:1.5rem;font-weight:800;color:#fff;">' + (val||0) + '</div>'
            + '<div style="font-size:.7rem;font-weight:600;color:rgba(226, 245, 234, .45);text-transform:uppercase;letter-spacing:.05em;">' + label + '</div>'
            + '</div>';
    }

    document.getElementById('inputBulan').addEventListener('change', function(){ loadList(this.value); });

    loadList();
}());
</script>
@endpush