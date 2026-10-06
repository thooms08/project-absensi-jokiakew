@extends('murid.layouts.app')

@section('title', 'Absensi')
@section('page-title', 'Absensi')

@push('styles')
<style>
    .info-bar {
        display: flex; align-items: center; gap: .75rem; flex-wrap: wrap;
        background: rgba(255,255,255,.03); border: 1px solid rgba(129,140,248,.12);
        border-radius: 12px; padding: .7rem 1rem; margin-bottom: 1.5rem; font-size: .82rem;
    }

    .info-hari   { font-weight: 700; color: #818cf8; }
    .info-tgl    { color: rgba(226,232,255,.5); }
    .info-dot    { color: rgba(226,232,255,.18); }

    .btn-refresh-kartu {
        margin-left: auto;
        display: inline-flex; align-items: center; gap: .35rem;
        background: rgba(129,140,248,.08); border: 1px solid rgba(129,140,248,.18);
        border-radius: 8px; color: rgba(129,140,248,.75); font-size: .78rem; font-weight: 600;
        padding: .35rem .75rem; cursor: pointer; font-family: 'Inter',sans-serif;
        transition: background .15s;
    }

    .btn-refresh-kartu:hover { background: rgba(129,140,248,.15); color: #818cf8; }

    #kartuContainer {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px,1fr));
        gap: 1.1rem;
    }

    .kartu-loading {
        grid-column: 1/-1; text-align: center;
        padding: 3rem 1rem; color: rgba(226,232,255,.35); font-size: .875rem;
    }

    .spinner-purple {
        width: 24px; height: 24px;
        border: 3px solid rgba(129,140,248,.15);
        border-top-color: #818cf8;
        border-radius: 50%; animation: spin .65s linear infinite;
        display: inline-block; vertical-align: middle; margin-right: .5rem;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    /* Modal */
    .modal-indigo .modal-content {
        background: #13132e; border: 1px solid rgba(129,140,248,.22);
        border-radius: 16px; color: #e2e8ff;
    }

    .modal-indigo .modal-header { border-bottom: 1px solid rgba(129,140,248,.12); padding: 1.1rem 1.4rem 1rem; }
    .modal-indigo .modal-title  { font-size: 1rem; font-weight: 700; color: #fff; }
    .modal-indigo .modal-footer { border-top: 1px solid rgba(129,140,248,.12); padding: .9rem 1.4rem; }
    .modal-indigo .btn-close    { filter: invert(1) brightness(.7); }
    .modal-indigo .modal-body   { padding: 1.25rem 1.4rem; }

    /* Kamera */
    .kamera-wrap {
        position: relative; width: 100%; aspect-ratio: 3/4;
        background: #000; border-radius: 12px; overflow: hidden; margin-bottom: .85rem;
    }

    #videoSelfie, #videoIzin {
        width: 100%; height: 100%; object-fit: cover; display: block;
        transform: scaleX(-1); /* mirror kamera depan */
    }

    #previewSelfie, #previewIzin {
        width: 100%; border-radius: 12px; display: none; margin-bottom: .85rem;
    }

    .kamera-btn-row { display: flex; gap: .65rem; justify-content: center; flex-wrap: wrap; }

    .btn-ambil {
        display: inline-flex; align-items: center; gap: .4rem;
        background: rgba(129,140,248,.12); border: 1px solid rgba(129,140,248,.3);
        border-radius: 10px; color: #818cf8; font-size: .875rem; font-weight: 600;
        padding: .5rem 1.1rem; cursor: pointer; font-family: 'Inter',sans-serif;
        transition: background .15s;
    }

    .btn-ambil:hover { background: rgba(129,140,248,.22); }

    .btn-ulang {
        display: inline-flex; align-items: center; gap: .4rem;
        background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12);
        border-radius: 10px; color: rgba(226,232,255,.65); font-size: .875rem;
        font-weight: 500; padding: .5rem 1rem; cursor: pointer; font-family: 'Inter',sans-serif;
        transition: background .15s;
    }

    .btn-ulang:hover { background: rgba(255,255,255,.12); }

    /* Form */
    .form-label-ind { font-size: .8rem; font-weight: 600; color: rgba(226,232,255,.65); margin-bottom: .4rem; display: block; }

    .form-control-ind {
        background: rgba(255,255,255,.06); border: 1px solid rgba(129,140,248,.18);
        border-radius: 10px; color: #e2e8ff; font-size: .875rem; padding: .55rem .85rem;
        width: 100%; outline: none; font-family: 'Inter',sans-serif;
        transition: border-color .18s;
    }

    .form-control-ind::placeholder { color: rgba(226,232,255,.28); }
    .form-control-ind:focus { border-color: rgba(129,140,248,.5); background: rgba(255,255,255,.09); }

    .field-fb { font-size: .75rem; color: #f87171; margin-top: .28rem; min-height: 1rem; }

    /* Kode display */
    .kode-hint { font-size: .75rem; color: rgba(226,232,255,.35); text-align: center; margin-bottom: .6rem; }

    /* Tombol modal */
    .btn-mp { background: linear-gradient(135deg,#4f46e5,#818cf8); border: none; border-radius: 10px; color: #fff; font-size: .875rem; font-weight: 600; padding: .55rem 1.35rem; cursor: pointer; transition: opacity .18s; font-family: 'Inter',sans-serif; }
    .btn-mp:hover { opacity: .87; }
    .btn-mp:disabled { opacity: .45; cursor: not-allowed; }
    .btn-ms { background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); border-radius: 10px; color: rgba(226,232,255,.72); font-size: .875rem; font-weight: 500; padding: .55rem 1.2rem; cursor: pointer; font-family: 'Inter',sans-serif; }
    .btn-ms:hover { background: rgba(255,255,255,.12); }

    .modal-jadwal-info {
        background: rgba(255,255,255,.04); border: 1px solid rgba(129,140,248,.12);
        border-radius: 10px; padding: .7rem .9rem; margin-bottom: 1rem; font-size: .82rem;
    }

    .modal-jadwal-info .kls-nm  { font-weight: 700; color: #fff; font-size: .9rem; }
    .modal-jadwal-info .mpl-nm  { color: #818cf8; font-size: .78rem; }
    .modal-jadwal-info .jam-nm  { color: rgba(129,140,248,.65); font-size: .75rem; }

    @media (max-width: 575.98px) { #kartuContainer { grid-template-columns: 1fr; } .modal-dialog { margin: .5rem; } }
</style>
@endpush

@section('content')

@php
    $hariMap = [1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu',0=>'Minggu'];
    $hariIni = $hariMap[date('w')] ?? '-';
    $tglIni  = \Carbon\Carbon::today()->translatedFormat('d F Y');
@endphp

<div class="info-bar">
    <i class="bi bi-calendar2-week-fill" style="color:#818cf8;font-size:1rem;"></i>
    <span class="info-hari">{{ $hariIni }}</span>
    <span class="info-dot">•</span>
    <span class="info-tgl">{{ $tglIni }}</span>
    <button type="button" class="btn-refresh-kartu" id="btnRefreshKartu">
        <i class="bi bi-arrow-clockwise"></i> Perbarui
    </button>
</div>

<div id="kartuContainer">
    <div class="kartu-loading"><span class="spinner-purple"></span> Memuat jadwal hari ini...</div>
</div>

{{-- ================================================================
     MODAL HADIR (selfie + kode)
================================================================ --}}
<div class="modal fade modal-indigo" id="modalHadir" tabindex="-1" aria-hidden="true"
     data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-camera-fill me-2" style="color:#818cf8;"></i>
                    Absen Hadir
                </h5>
                <button type="button" class="btn-close" id="btnTutupHadir"></button>
            </div>
            <div class="modal-body">
                <div class="modal-jadwal-info" id="hadirInfo">
                    <div class="kls-nm" id="hadirKelas">–</div>
                    <div class="mpl-nm" id="hadirMapel">–</div>
                    <div class="jam-nm" id="hadirJam">–</div>
                </div>

                {{-- Step 1: Selfie --}}
                <div id="stepSelfie">
                    <p class="kode-hint">Ambil selfie sebagai bukti kehadiran (kamera depan).</p>
                    <div class="kamera-wrap"><video id="videoSelfie" autoplay playsinline muted></video></div>
                    <img id="previewSelfie" src="" alt="Selfie preview">
                    <canvas id="canvasSelfie" style="display:none;"></canvas>
                    <div class="kamera-btn-row">
                        <button type="button" class="btn-ambil" id="btnAmbilSelfie">
                            <i class="bi bi-camera"></i> Ambil Foto
                        </button>
                        <button type="button" class="btn-ulang" id="btnUlangSelfie" style="display:none;">
                            <i class="bi bi-arrow-counterclockwise"></i> Ulangi
                        </button>
                    </div>
                </div>

                {{-- Step 2: Kode absen --}}
                <div id="stepKode" style="display:none;">
                    <p class="kode-hint">Masukkan kode 6 digit yang diberikan guru.</p>
                    <div class="mb-3">
                        <label class="form-label-ind" for="inputKode">Kode Absen <span style="color:#f87171;">*</span></label>
                        <input type="text" id="inputKode" class="form-control-ind"
                               placeholder="Contoh: ABC123" maxlength="6"
                               style="text-transform:uppercase;letter-spacing:.2em;font-size:1.1rem;font-weight:700;text-align:center;">
                        <div class="field-fb" id="fbKode"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-ms" id="btnBatalHadir">Batal</button>
                <button type="button" class="btn-mp" id="btnLanjutKode" disabled>
                    Lanjut <i class="bi bi-arrow-right ms-1"></i>
                </button>
                <button type="button" class="btn-mp" id="btnSubmitHadir" style="display:none;" disabled>
                    <i class="bi bi-check-lg me-1"></i> Kirim Absen
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ================================================================
     MODAL IZIN (keterangan + foto bukti opsional)
================================================================ --}}
<div class="modal fade modal-indigo" id="modalIzin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-envelope-fill me-2" style="color:#fbbf24;"></i>
                    Pengajuan Izin
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="modal-jadwal-info" id="izinInfo">
                    <div class="kls-nm" id="izinKelas">–</div>
                    <div class="mpl-nm" id="izinMapel">–</div>
                    <div class="jam-nm" id="izinJam">–</div>
                </div>

                <div class="mb-3">
                    <label class="form-label-ind" for="inputAlasan">Keterangan Izin <span style="color:#f87171;">*</span></label>
                    <textarea id="inputAlasan" class="form-control-ind" rows="3"
                              placeholder="Tuliskan alasan izin Anda..." maxlength="500"
                              style="resize:vertical;min-height:80px;"></textarea>
                    <div class="field-fb" id="fbAlasan"></div>
                </div>

                <div>
                    <label class="form-label-ind">Foto Bukti <span style="color:rgba(226,232,255,.35);">(opsional)</span></label>
                    <div class="kamera-wrap" id="izinKameraWrap"><video id="videoIzin" autoplay playsinline muted></video></div>
                    <img id="previewIzin" src="" alt="Foto izin">
                    <canvas id="canvasIzin" style="display:none;"></canvas>
                    <div class="kamera-btn-row" style="margin-top:.5rem;">
                        <button type="button" class="btn-ambil" id="btnAmbilIzin" style="font-size:.8rem;padding:.4rem .9rem;">
                            <i class="bi bi-camera"></i> Ambil Foto Bukti
                        </button>
                        <button type="button" class="btn-ulang" id="btnUlangIzin" style="display:none;font-size:.8rem;padding:.4rem .9rem;">
                            <i class="bi bi-arrow-counterclockwise"></i> Ulangi
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-ms" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn-mp" id="btnSubmitIzin"
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

    var CSRF = (document.querySelector('meta[name="csrf-token"]')||{}).content||'{{ csrf_token() }}';
    var activeJadwalId = null;
    var selfieBase64   = null;
    var fotoIzinBase64 = null;
    var streamSelfie   = null;
    var streamIzin     = null;
    var refreshTimer   = null;

    /* ============================================================
       LOAD KARTU
    ============================================================ */
    function loadKartu() {
        document.getElementById('kartuContainer').innerHTML =
            '<div class="kartu-loading"><span class="spinner-purple"></span> Memuat jadwal hari ini...</div>';

        fetch('{{ route("murid.absen.kartu") }}', { headers: {'X-Requested-With':'XMLHttpRequest'} })
        .then(function(r){ return r.text(); })
        .then(function(html){ document.getElementById('kartuContainer').innerHTML = html; bindKartuEvents(); })
        .catch(function(){ document.getElementById('kartuContainer').innerHTML = '<div class="kartu-loading" style="color:#f87171;">Gagal memuat jadwal.</div>'; });
    }

    document.getElementById('btnRefreshKartu').addEventListener('click', function(){
        var i=this.querySelector('i');
        i.style.transition='transform .5s'; i.style.transform='rotate(360deg)';
        setTimeout(function(){i.style.transform='';},500);
        loadKartu();
    });

    function startAutoRefresh() {
        clearInterval(refreshTimer);
        refreshTimer = setInterval(loadKartu, 60000);
    }

    /* ============================================================
       BIND EVENTS KARTU
    ============================================================ */
    function bindKartuEvents() {
        var c = document.getElementById('kartuContainer');
        c.querySelectorAll('[data-btn-hadir]').forEach(function(btn){
            btn.addEventListener('click', function(){
                bukaModalHadir(this.dataset.btnHadir, this.dataset.kelas, this.dataset.mapel, this.dataset.jam);
            });
        });
        c.querySelectorAll('[data-btn-izin]').forEach(function(btn){
            btn.addEventListener('click', function(){
                bukaModalIzin(this.dataset.btnIzin, this.dataset.kelas, this.dataset.mapel, this.dataset.jam);
            });
        });
    }

    /* ============================================================
       MODAL HADIR
    ============================================================ */
    var modalHadirEl = document.getElementById('modalHadir');
    var modalHadir   = new bootstrap.Modal(modalHadirEl);

    function bukaModalHadir(jadwalId, kelas, mapel, jam) {
        activeJadwalId = jadwalId; selfieBase64 = null;
        document.getElementById('hadirKelas').textContent = kelas||'–';
        document.getElementById('hadirMapel').textContent = mapel||'–';
        document.getElementById('hadirJam').textContent   = jam||'–';
        resetSelfie();
        gotoStepSelfie();
        modalHadir.show();
        modalHadirEl.addEventListener('shown.bs.modal', startSelfie, {once:true});
    }

    function startSelfie() {
        navigator.mediaDevices.getUserMedia({ video:{ facingMode:'user', width:{ideal:720}, height:{ideal:960} }, audio:false })
        .then(function(s){ streamSelfie=s; document.getElementById('videoSelfie').srcObject=s; })
        .catch(function(e){ document.querySelector('#stepSelfie .kode-hint').textContent='Kamera tidak tersedia: '+e.message; });
    }

    function stopSelfie() { if(streamSelfie){ streamSelfie.getTracks().forEach(function(t){t.stop();}); streamSelfie=null; } document.getElementById('videoSelfie').srcObject=null; }

    function resetSelfie() {
        selfieBase64=null;
        document.getElementById('previewSelfie').style.display='none';
        document.getElementById('previewSelfie').src='';
        document.getElementById('videoSelfie').closest('.kamera-wrap').style.display='';
        document.getElementById('btnAmbilSelfie').style.display='';
        document.getElementById('btnUlangSelfie').style.display='none';
        document.getElementById('btnLanjutKode').disabled=true;
    }

    function gotoStepSelfie() {
        document.getElementById('stepSelfie').style.display='';
        document.getElementById('stepKode').style.display='none';
        document.getElementById('btnLanjutKode').style.display='';
        document.getElementById('btnSubmitHadir').style.display='none';
        document.getElementById('inputKode').value='';
        document.getElementById('fbKode').textContent='';
    }

    function gotoStepKode() {
        stopSelfie();
        document.getElementById('stepSelfie').style.display='none';
        document.getElementById('stepKode').style.display='';
        document.getElementById('btnLanjutKode').style.display='none';
        document.getElementById('btnSubmitHadir').style.display='';
        document.getElementById('btnSubmitHadir').disabled=false;
        setTimeout(function(){ document.getElementById('inputKode').focus(); }, 200);
    }

    document.getElementById('btnAmbilSelfie').addEventListener('click', function(){
        if(!streamSelfie){ Swal.fire({icon:'warning',title:'Kamera belum aktif',background:'#13132e',color:'#e2e8ff'}); return; }
        var v=document.getElementById('videoSelfie');
        var c=document.getElementById('canvasSelfie');
        c.width=v.videoWidth||480; c.height=v.videoHeight||640;
        var ctx=c.getContext('2d');
        ctx.translate(c.width,0); ctx.scale(-1,1);
        ctx.drawImage(v,0,0,c.width,c.height);
        selfieBase64=c.toDataURL('image/jpeg',.88);
        document.getElementById('previewSelfie').src=selfieBase64;
        document.getElementById('previewSelfie').style.display='block';
        document.getElementById('videoSelfie').closest('.kamera-wrap').style.display='none';
        document.getElementById('btnAmbilSelfie').style.display='none';
        document.getElementById('btnUlangSelfie').style.display='';
        document.getElementById('btnLanjutKode').disabled=false;
    });

    document.getElementById('btnUlangSelfie').addEventListener('click', function(){
        resetSelfie();
        if(!streamSelfie) startSelfie();
    });

    document.getElementById('btnLanjutKode').addEventListener('click', function(){
        if(!selfieBase64){ Swal.fire({icon:'warning',title:'Ambil foto terlebih dahulu',background:'#13132e',color:'#e2e8ff'}); return; }
        gotoStepKode();
    });

    document.getElementById('btnSubmitHadir').addEventListener('click', function(){
        var kode=document.getElementById('inputKode').value.trim().toUpperCase();
        if(!kode||kode.length!==6){ document.getElementById('fbKode').textContent='Masukkan kode 6 digit.'; return; }
        document.getElementById('fbKode').textContent='';

        var btn=this; btn.disabled=true;
        btn.innerHTML='<span class="spinner-purple" style="width:14px;height:14px;border-width:2px;margin-right:5px;"></span> Mengirim...';

        fetch('{{ route("murid.absen.store") }}',{
            method:'POST',
            headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest','Content-Type':'application/json'},
            body:JSON.stringify({ jadwal_id:activeJadwalId, status:'hadir', kode_absen:kode, foto_selfie:selfieBase64 })
        })
        .then(function(r){return r.json();})
        .then(function(d){
            btn.disabled=false; btn.innerHTML='<i class="bi bi-check-lg me-1"></i> Kirim Absen';
            if(d.success){ stopSelfie(); modalHadir.hide(); Swal.fire({icon:'success',title:'Berhasil!',text:d.message,timer:2200,showConfirmButton:false,background:'#13132e',color:'#e2e8ff'}).then(loadKartu); }
            else { document.getElementById('fbKode').textContent=d.message||'Terjadi kesalahan.'; }
        })
        .catch(function(){ btn.disabled=false; btn.innerHTML='<i class="bi bi-check-lg me-1"></i> Kirim Absen'; Swal.fire({icon:'error',title:'Koneksi gagal',background:'#13132e',color:'#e2e8ff'}); });
    });

    document.getElementById('btnTutupHadir').addEventListener('click', function(){ stopSelfie(); modalHadir.hide(); });
    document.getElementById('btnBatalHadir').addEventListener('click', function(){ stopSelfie(); modalHadir.hide(); });
    modalHadirEl.addEventListener('hidden.bs.modal', function(){ stopSelfie(); resetSelfie(); });

    /* ============================================================
       MODAL IZIN
    ============================================================ */
    var modalIzinEl = document.getElementById('modalIzin');
    var modalIzin   = new bootstrap.Modal(modalIzinEl);

    function bukaModalIzin(jadwalId, kelas, mapel, jam) {
        activeJadwalId=jadwalId; fotoIzinBase64=null;
        document.getElementById('izinKelas').textContent=kelas||'–';
        document.getElementById('izinMapel').textContent=mapel||'–';
        document.getElementById('izinJam').textContent=jam||'–';
        document.getElementById('inputAlasan').value='';
        document.getElementById('fbAlasan').textContent='';
        resetFotoIzin();
        modalIzin.show();
        modalIzinEl.addEventListener('shown.bs.modal', startIzinKamera, {once:true});
    }

    function startIzinKamera() {
        navigator.mediaDevices.getUserMedia({ video:{ facingMode:'environment' }, audio:false })
        .then(function(s){ streamIzin=s; document.getElementById('videoIzin').srcObject=s; })
        .catch(function(){}); /* kamera belakang opsional */
    }

    function stopIzinKamera() { if(streamIzin){ streamIzin.getTracks().forEach(function(t){t.stop();}); streamIzin=null; } document.getElementById('videoIzin').srcObject=null; }

    function resetFotoIzin() {
        fotoIzinBase64=null;
        document.getElementById('previewIzin').style.display='none';
        document.getElementById('previewIzin').src='';
        document.getElementById('izinKameraWrap').style.display='';
        document.getElementById('btnAmbilIzin').style.display='';
        document.getElementById('btnUlangIzin').style.display='none';
    }

    document.getElementById('btnAmbilIzin').addEventListener('click', function(){
        if(!streamIzin){ Swal.fire({icon:'warning',title:'Kamera tidak tersedia',background:'#13132e',color:'#e2e8ff'}); return; }
        var v=document.getElementById('videoIzin');
        var c=document.getElementById('canvasIzin');
        c.width=v.videoWidth||480; c.height=v.videoHeight||640;
        c.getContext('2d').drawImage(v,0,0,c.width,c.height);
        fotoIzinBase64=c.toDataURL('image/jpeg',.88);
        document.getElementById('previewIzin').src=fotoIzinBase64;
        document.getElementById('previewIzin').style.display='block';
        document.getElementById('izinKameraWrap').style.display='none';
        document.getElementById('btnAmbilIzin').style.display='none';
        document.getElementById('btnUlangIzin').style.display='';
    });

    document.getElementById('btnUlangIzin').addEventListener('click', function(){ resetFotoIzin(); if(!streamIzin) startIzinKamera(); });
    modalIzinEl.addEventListener('hidden.bs.modal', function(){ stopIzinKamera(); resetFotoIzin(); });

    document.getElementById('btnSubmitIzin').addEventListener('click', function(){
        var alasan=document.getElementById('inputAlasan').value.trim();
        if(!alasan){ document.getElementById('fbAlasan').textContent='Keterangan izin wajib diisi.'; document.getElementById('inputAlasan').focus(); return; }
        document.getElementById('fbAlasan').textContent='';

        var btn=this; btn.disabled=true;
        btn.innerHTML='<span class="spinner-purple" style="width:14px;height:14px;border-width:2px;margin-right:5px;"></span> Mengirim...';

        fetch('{{ route("murid.absen.store") }}',{
            method:'POST',
            headers:{'X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest','Content-Type':'application/json'},
            body:JSON.stringify({ jadwal_id:activeJadwalId, status:'izin', alasan:alasan, foto_izin:fotoIzinBase64||null })
        })
        .then(function(r){return r.json();})
        .then(function(d){
            btn.disabled=false; btn.innerHTML='<i class="bi bi-send-fill me-1"></i> Kirim Izin';
            if(d.success){ stopIzinKamera(); modalIzin.hide(); Swal.fire({icon:'success',title:'Izin Terkirim!',text:d.message,timer:2200,showConfirmButton:false,background:'#13132e',color:'#e2e8ff'}).then(loadKartu); }
            else { document.getElementById('fbAlasan').textContent=d.message||'Terjadi kesalahan.'; }
        })
        .catch(function(){ btn.disabled=false; btn.innerHTML='<i class="bi bi-send-fill me-1"></i> Kirim Izin'; Swal.fire({icon:'error',title:'Koneksi gagal',background:'#13132e',color:'#e2e8ff'}); });
    });

    /* ============================================================
       INIT
    ============================================================ */
    loadKartu();
    startAutoRefresh();
}());
</script>
@endpush
