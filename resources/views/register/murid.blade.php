<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Daftar sebagai Murid - SIKIB SMKS Ibnu Sina">
    <title>Daftar Murid - SMKS Ibnu Sina</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(160deg, #064e2e 0%, #0a7c4a 45%, #0d9e5e 100%);
            min-height: 100vh;
            color: #e2f5ea;
        }

        #circuit-canvas {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0.5;
            pointer-events: none;
            z-index: 0;
        }

        .page-overlay {
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse 70% 60% at 50% 40%, rgba(10,124,74,0.12) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        /* ============================================================
           LAYOUT
        ============================================================ */
        .register-page {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .register-card {
            width: 100%;
            max-width: 860px;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.13);
            border-radius: 28px 0 28px 0;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow: 0 16px 52px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.1);
            overflow: hidden;
            animation: cardIn 0.75s cubic-bezier(0.22,1,0.36,1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card-inner {
            display: flex;
            min-height: 540px;
        }

        /* ============================================================
           KOLOM KIRI
        ============================================================ */
        .card-left {
            flex: 0 0 40%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1.75rem;
            background: rgba(0,0,0,0.18);
            border-right: 1px solid rgba(255,255,255,0.09);
            text-align: center;
            gap: 0;
        }

        .school-logo {
            width: 88px;
            height: 88px;
            object-fit: contain;
            border-radius: 18px;
            filter: drop-shadow(0 4px 14px rgba(0,0,0,0.4));
            margin-bottom: 1.1rem;
        }

        .school-name {
            font-size: 1.2rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.05em;
            line-height: 1.2;
            margin-bottom: 0.85rem;
        }

        .brand-divider {
            width: 40px;
            height: 3px;
            background: linear-gradient(90deg, #4ade80, #86efac);
            border-radius: 2px;
            margin: 0 auto 0.85rem;
        }

        .brand-name {
            font-size: 1.85rem;
            font-weight: 800;
            color: #4ade80;
            letter-spacing: 0.06em;
            line-height: 1;
            margin-bottom: 0.4rem;
            text-shadow: 0 2px 12px rgba(74,222,128,0.3);
        }

        .brand-desc {
            font-size: 0.73rem;
            color: rgba(255,255,255,0.55);
            text-transform: uppercase;
            letter-spacing: 0.04em;
            line-height: 1.55;
        }

        .register-info {
            margin-top: 1.5rem;
            background: rgba(74,222,128,0.08);
            border: 1px solid rgba(74,222,128,0.18);
            border-radius: 10px;
            padding: 0.85rem 1rem;
            font-size: 0.78rem;
            color: rgba(255,255,255,0.6);
            line-height: 1.55;
            text-align: left;
        }

        .register-info strong { color: #4ade80; font-weight: 600; }

        /* ============================================================
           KOLOM KANAN
        ============================================================ */
        .card-right {
            flex: 1;
            padding: 2.5rem 2.25rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-heading {
            font-size: 1.25rem;
            font-weight: 700;
            color: #fff;
            margin: 0 0 0.25rem;
        }

        .form-subheading {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.48);
            margin: 0 0 1.75rem;
        }

        /* ============================================================
           STEP INDICATOR
        ============================================================ */
        .step-indicator {
            display: flex;
            align-items: center;
            margin-bottom: 1.75rem;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.3rem;
            flex: 1;
        }

        .step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.82rem;
            font-weight: 700;
            border: 2px solid rgba(74,222,128,0.2);
            background: rgba(255,255,255,0.05);
            color: rgba(255,255,255,0.3);
            transition: all 0.25s;
            position: relative;
            z-index: 1;
        }

        .step-circle.active  { border-color: #4ade80; background: rgba(74,222,128,0.15); color: #4ade80; }
        .step-circle.done    { border-color: #22c55e; background: #22c55e; color: #064e2e; }

        .step-label {
            font-size: 0.65rem;
            font-weight: 600;
            color: rgba(255,255,255,0.3);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            text-align: center;
            white-space: nowrap;
        }

        .step-label.active { color: #4ade80; }
        .step-label.done   { color: #22c55e; }

        .step-line {
            flex: 1;
            height: 2px;
            background: rgba(74,222,128,0.12);
            margin-bottom: 1.3rem;
            transition: background 0.25s;
        }

        .step-line.done { background: #22c55e; }

        /* ============================================================
           FORM ELEMENTS
        ============================================================ */
        .field-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: rgba(255,255,255,0.65);
            letter-spacing: 0.03em;
            text-transform: uppercase;
            margin-bottom: 0.38rem;
            display: block;
        }

        .field-input {
            width: 100%;
            padding: 0.65rem 1rem;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.16);
            border-radius: 10px;
            color: #fff;
            font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .field-input::placeholder { color: rgba(255,255,255,0.28); }

        .field-input:focus {
            border-color: #4ade80;
            background: rgba(74,222,128,0.07);
            box-shadow: 0 0 0 3px rgba(74,222,128,0.13);
        }

        .field-input.is-valid   { border-color: rgba(74,222,128,0.6); }
        .field-input.is-invalid { border-color: rgba(248,113,113,0.6); }

        .pwd-wrap { position: relative; }
        .pwd-wrap .field-input { padding-right: 2.75rem; }

        .btn-eye-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255,255,255,0.38);
            cursor: pointer;
            padding: 0;
            line-height: 1;
            transition: color 0.18s;
        }

        .btn-eye-toggle:hover { color: #4ade80; }

        .field-feedback {
            font-size: 0.73rem;
            margin-top: 0.28rem;
            min-height: 0.9rem;
        }

        .field-feedback.valid   { color: #4ade80; }
        .field-feedback.invalid { color: #f87171; }
        .field-feedback.warning { color: #fbbf24; }
        .field-feedback.muted   { color: rgba(255,255,255,0.35); }

        .pwd-hint {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.35);
            margin-top: 0.3rem;
            margin-bottom: 0;
        }

        /* ============================================================
           FOTO UPLOAD
        ============================================================ */
        .foto-zone {
            border: 2px dashed rgba(74,222,128,0.22);
            border-radius: 12px;
            padding: 1.1rem;
            text-align: center;
            cursor: pointer;
            transition: border-color 0.18s, background 0.18s;
        }

        .foto-zone:hover {
            border-color: rgba(74,222,128,0.45);
            background: rgba(74,222,128,0.04);
        }

        .foto-zone-icon {
            font-size: 1.75rem;
            color: rgba(74,222,128,0.4);
            display: block;
            margin-bottom: 0.4rem;
        }

        .foto-zone-text {
            font-size: 0.78rem;
            color: rgba(255,255,255,0.42);
            margin: 0;
        }

        .foto-zone-text span { color: #4ade80; font-weight: 600; }

        .foto-preview-outer {
            display: inline-block;
            position: relative;
            margin-top: 0.65rem;
        }

        .foto-preview-img {
            width: 82px;
            height: 82px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(74,222,128,0.35);
        }

        .foto-remove-btn {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #f87171;
            border: none;
            color: #fff;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.15s;
            line-height: 1;
        }

        .foto-remove-btn:hover { background: #ef4444; }

        /* ============================================================
           TOMBOL
        ============================================================ */
        .btn-primary-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.45rem;
            width: 100%;
            padding: 0.78rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: #064e2e;
            background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            letter-spacing: 0.02em;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 18px rgba(34,197,94,0.4);
            font-family: 'Inter', sans-serif;
        }

        .btn-primary-action:hover { transform: translateY(-2px); box-shadow: 0 8px 26px rgba(34,197,94,0.55); }
        .btn-primary-action:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        .btn-secondary-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.65rem 1.35rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: rgba(255,255,255,0.65);
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.14);
            border-radius: 50px;
            cursor: pointer;
            transition: background 0.18s;
            font-family: 'Inter', sans-serif;
        }

        .btn-secondary-action:hover { background: rgba(255,255,255,0.12); }

        .btn-action-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }

        /* ============================================================
           SPINNER
        ============================================================ */
        .spinner-sm {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 2px solid rgba(6,78,46,0.3);
            border-top-color: #064e2e;
            border-radius: 50%;
            animation: spin 0.65s linear infinite;
            vertical-align: middle;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ============================================================
           LINK BAWAH
        ============================================================ */
        .bottom-link {
            text-align: center;
            margin-top: 1.35rem;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.45);
        }

        .bottom-link a {
            color: #4ade80;
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.18s;
        }

        .bottom-link a:hover { opacity: 0.8; }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 767.98px) {
            .register-card { border-radius: 18px 0 18px 0; }
            .card-inner { flex-direction: column; min-height: unset; }

            .card-left {
                flex: none;
                border-right: none;
                border-bottom: 1px solid rgba(255,255,255,0.09);
                padding: 1.5rem 1.25rem;
                flex-direction: row;
                flex-wrap: wrap;
                gap: 0.85rem;
                justify-content: center;
                align-items: center;
            }

            .school-logo  { width: 54px; height: 54px; margin-bottom: 0; }
            .school-name  { font-size: 0.95rem; margin-bottom: 0.5rem; }
            .brand-name   { font-size: 1.45rem; }
            .brand-desc   { font-size: 0.68rem; }
            .brand-divider { margin: 0.5rem auto 0.5rem; }
            .register-info { display: none; }

            .card-right { padding: 1.5rem 1.25rem; }
            .form-heading { font-size: 1.05rem; }
        }

        @media (max-width: 479.98px) {
            .card-right { padding: 1.25rem 1rem; }
        }
    </style>
</head>
<body>

<canvas id="circuit-canvas" aria-hidden="true"></canvas>
<div class="page-overlay" aria-hidden="true"></div>

<div class="register-page" aria-label="Halaman pendaftaran murid">
    <div class="register-card">
        <div class="card-inner">

            <!-- ================================================
                 KOLOM KIRI
            ================================================ -->
            <div class="card-left">
                <img src="{{ asset('logo.jpeg') }}" alt="Logo SMKS Ibnu Sina" class="school-logo">
                <div>
                    <div class="school-name">SMKS IBNU SINA</div>
                    <div class="brand-divider" aria-hidden="true"></div>
                    <div class="brand-name">SIKIB</div>
                    <div class="brand-desc">Sistem Informasi<br>Kehadiran Ibnu Sina</div>
                    <div class="register-info">
                        <strong>Pendaftaran Murid</strong><br>
                        Isi formulir di sebelah kanan untuk membuat akun sebagai murid.<br><br>
                        Setelah mendaftar, gunakan username dan password untuk masuk ke sistem absensi.
                    </div>
                </div>
            </div>

            <!-- ================================================
                 KOLOM KANAN
            ================================================ -->
            <div class="card-right">
                <h1 class="form-heading">Pendaftaran Murid</h1>
                <p class="form-subheading">Lengkapi data diri dan buat akun Anda</p>

                <!-- Step Indicator -->
                <div class="step-indicator" id="stepIndicator">
                    <div class="step-item">
                        <div class="step-circle active" id="sCircle1">1</div>
                        <span class="step-label active" id="sLabel1">Data Diri</span>
                    </div>
                    <div class="step-line" id="sLine1"></div>
                    <div class="step-item">
                        <div class="step-circle" id="sCircle2">2</div>
                        <span class="step-label" id="sLabel2">Akun</span>
                    </div>
                </div>

                <form id="registerForm" enctype="multipart/form-data" novalidate>
                    @csrf

                    <!-- STEP 1: DATA DIRI -->
                    <div id="step1">

                        <div class="mb-3">
                            <label class="field-label" for="rNama">
                                Nama Lengkap <span style="color:#f87171;">*</span>
                            </label>
                            <input type="text" id="rNama" name="name" class="field-input"
                                placeholder="Contoh: Ahmad Fauzi"
                                maxlength="255" required autocomplete="name">
                            <div class="field-feedback" id="fbNama"></div>
                        </div>

                        <div class="mb-3">
                            <label class="field-label" for="rEmail">
                                Alamat Email <span style="color:#f87171;">*</span>
                            </label>
                            <input type="email" id="rEmail" name="email" class="field-input"
                                placeholder="murid@sekolah.sch.id"
                                maxlength="255" required autocomplete="email">
                            <div class="field-feedback" id="fbEmail"></div>
                        </div>

                        <div class="mb-1">
                            <label class="field-label">
                                Foto Profil
                                <span style="color:rgba(255,255,255,0.3);">(opsional)</span>
                            </label>
                            <div class="foto-zone" id="fotoZone" role="button" tabindex="0"
                                 aria-label="Klik untuk unggah foto profil">
                                <i class="bi bi-camera foto-zone-icon"></i>
                                <p class="foto-zone-text">
                                    <span>Klik untuk unggah</span> atau seret ke sini<br>
                                    <span style="color:rgba(255,255,255,0.3);font-size:0.68rem;">
                                        JPG, PNG, WEBP, maks. 2 MB
                                    </span>
                                </p>
                                <input type="file" id="rFoto" name="image"
                                    accept="image/jpeg,image/png,image/webp"
                                    style="display:none;" aria-label="Upload foto profil">
                            </div>
                            <div class="text-center" id="fotoPreviewWrap" style="display:none;">
                                <div class="foto-preview-outer">
                                    <img src="" id="fotoPreviewImg" class="foto-preview-img"
                                         alt="Preview foto profil">
                                    <button type="button" class="foto-remove-btn"
                                            id="btnRemoveFoto" aria-label="Hapus foto">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="field-feedback" id="fbFoto"></div>
                        </div>

                    </div>
                    <!-- /step1 -->

                    <!-- STEP 2: AKUN -->
                    <div id="step2" style="display:none;">

                        <div class="mb-3">
                            <label class="field-label" for="rUsername">
                                Username <span style="color:#f87171;">*</span>
                            </label>
                            <input type="text" id="rUsername" name="username" class="field-input"
                                placeholder="Contoh: ahmad_fauzi"
                                maxlength="60" autocomplete="username" required>
                            <div class="field-feedback" id="fbUsername"></div>
                        </div>

                        <div class="mb-3">
                            <label class="field-label" for="rPassword">
                                Password <span style="color:#f87171;">*</span>
                            </label>
                            <div class="pwd-wrap">
                                <input type="password" id="rPassword" name="password"
                                    class="field-input"
                                    placeholder="Min. 8 karakter (huruf + angka)"
                                    maxlength="128" autocomplete="new-password" required>
                                <button type="button" class="btn-eye-toggle"
                                        id="togglePwd1" aria-label="Tampilkan password">
                                    <i class="bi bi-eye" id="eyeIcon1"></i>
                                </button>
                            </div>
                            <p class="pwd-hint">Minimal 8 karakter, kombinasi huruf dan angka.</p>
                            <div class="field-feedback" id="fbPassword"></div>
                        </div>

                        <div class="mb-1">
                            <label class="field-label" for="rPasswordConfirm">
                                Konfirmasi Password <span style="color:#f87171;">*</span>
                            </label>
                            <div class="pwd-wrap">
                                <input type="password" id="rPasswordConfirm"
                                    name="password_confirmation"
                                    class="field-input"
                                    placeholder="Ulangi password"
                                    maxlength="128" autocomplete="new-password" required>
                                <button type="button" class="btn-eye-toggle"
                                        id="togglePwd2" aria-label="Tampilkan konfirmasi password">
                                    <i class="bi bi-eye" id="eyeIcon2"></i>
                                </button>
                            </div>
                            <div class="field-feedback" id="fbPasswordConfirm"></div>
                        </div>

                    </div>
                    <!-- /step2 -->

                    <!-- Tombol Aksi -->
                    <div class="btn-action-row">
                        <button type="button" class="btn-secondary-action"
                                id="btnBack" style="display:none;">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </button>
                        <button type="button" class="btn-primary-action"
                                id="btnNext" style="flex:1;">
                            Lanjut <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>

                </form>

                <div class="bottom-link">
                    Sudah punya akun?
                    <a href="{{ route('login') }}">Masuk di sini</a>
                </div>

            </div>
            <!-- /card-right -->

        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
(function () {
    'use strict';

    /* ============================================================
       CANVAS ANIMASI
    ============================================================ */
    var canvas = document.getElementById('circuit-canvas');
    var ctx    = canvas.getContext('2d');
    var W, H, nodes = [], packets = [], edges = [], raf;

    function resize() { W = canvas.width = window.innerWidth; H = canvas.height = window.innerHeight; }
    window.addEventListener('resize', function () { resize(); buildGraph(); });

    function buildGraph() {
        nodes = []; edges = []; packets = [];
        for (var i = 0; i < 50; i++) {
            nodes.push({ x: Math.random()*W, y: Math.random()*H,
                vx: (Math.random()-0.5)*0.45, vy: (Math.random()-0.5)*0.45,
                r: Math.random()*2.5+2 });
        }
        rebuildEdges();
        for (var k = 0; k < 20; k++) spawnPacket();
    }

    function rebuildEdges() {
        edges = [];
        for (var i = 0; i < nodes.length; i++) {
            for (var j = i+1; j < nodes.length; j++) {
                var dx=nodes[i].x-nodes[j].x, dy=nodes[i].y-nodes[j].y, d=Math.sqrt(dx*dx+dy*dy);
                if (d < 185) edges.push({a:i,b:j,dist:d});
            }
        }
    }

    function spawnPacket() {
        if (!edges.length) return;
        var e = edges[Math.floor(Math.random()*edges.length)];
        packets.push({ edge:e, t:Math.random(), dir:Math.random()>0.5?-1:1,
            speed: 2.2*(0.6+Math.random()*0.8) });
    }

    function hex2rgba(hex, a) {
        var r=parseInt(hex.slice(1,3),16), g=parseInt(hex.slice(3,5),16),
            b=parseInt(hex.slice(5,7),16);
        return 'rgba('+r+','+g+','+b+','+a+')';
    }

    function tick() {
        ctx.clearRect(0,0,W,H);
        for (var i=0;i<nodes.length;i++) {
            var n=nodes[i]; n.x+=n.vx; n.y+=n.vy;
            if(n.x<0||n.x>W)n.vx*=-1; if(n.y<0||n.y>H)n.vy*=-1;
            n.x=Math.max(0,Math.min(W,n.x)); n.y=Math.max(0,Math.min(H,n.y));
        }
        rebuildEdges();
        for (var e=0;e<edges.length;e++) {
            var ed=edges[e],na=nodes[ed.a],nb=nodes[ed.b],al=1-ed.dist/185;
            ctx.beginPath(); ctx.moveTo(na.x,na.y); ctx.lineTo(nb.x,nb.y);
            ctx.strokeStyle=hex2rgba('#4ade80',al*0.8); ctx.lineWidth=1.2; ctx.stroke();
        }
        for (var i=0;i<nodes.length;i++) {
            var n=nodes[i];
            ctx.beginPath(); ctx.arc(n.x,n.y,n.r,0,Math.PI*2);
            ctx.fillStyle=hex2rgba('#86efac',0.9); ctx.fill();
        }
        var alive=[];
        for (var p=0;p<packets.length;p++) {
            var pk=packets[p]; pk.t+=(pk.speed/600)*pk.dir;
            if(pk.t<0||pk.t>1){spawnPacket();continue;} alive.push(pk);
            var pa=nodes[pk.edge.a],pb=nodes[pk.edge.b];
            var px=pa.x+(pb.x-pa.x)*pk.t, py=pa.y+(pb.y-pa.y)*pk.t;
            ctx.beginPath(); ctx.arc(px,py,3,0,Math.PI*2);
            ctx.fillStyle='#ffffff'; ctx.fill();
        }
        packets=alive; raf=requestAnimationFrame(tick);
    }

    document.addEventListener('visibilitychange', function () {
        if (document.hidden) cancelAnimationFrame(raf); else raf=requestAnimationFrame(tick);
    });

    resize(); buildGraph(); tick();

    /* ============================================================
       STATE
    ============================================================ */
    var currentStep   = 1;
    var usernameOk    = false;
    var usernameTimer = null;

    /* ============================================================
       HELPERS
    ============================================================ */
    function setFb(elId, type, msg) {
        var el = document.getElementById(elId);
        if (!el) return;
        el.className   = 'field-feedback ' + type;
        el.textContent = msg;
    }

    function clearFb(elId) {
        var el = document.getElementById(elId);
        if (!el) return;
        el.className   = 'field-feedback muted';
        el.textContent = '';
    }

    function setInputState(elId, state) {
        var el = document.getElementById(elId);
        if (!el) return;
        el.classList.remove('is-valid', 'is-invalid');
        if (state) el.classList.add(state);
    }

    /* ============================================================
       GOTO STEP
    ============================================================ */
    function goToStep(step) {
        currentStep = step;
        var s1   = document.getElementById('step1');
        var s2   = document.getElementById('step2');
        var c1   = document.getElementById('sCircle1');
        var c2   = document.getElementById('sCircle2');
        var l1   = document.getElementById('sLabel1');
        var l2   = document.getElementById('sLabel2');
        var ln   = document.getElementById('sLine1');
        var back = document.getElementById('btnBack');
        var next = document.getElementById('btnNext');

        if (step === 1) {
            s1.style.display = ''; s2.style.display = 'none';
            c1.className = 'step-circle active'; l1.className = 'step-label active';
            c2.className = 'step-circle';        l2.className = 'step-label';
            ln.className = 'step-line';
            back.style.display = 'none'; next.style.flex = '1';
            next.innerHTML = 'Lanjut <i class="bi bi-arrow-right ms-1"></i>';
        } else {
            s1.style.display = 'none'; s2.style.display = '';
            c1.className = 'step-circle done';
            c1.innerHTML = '<i class="bi bi-check-lg"></i>';
            l1.className = 'step-label done';
            c2.className = 'step-circle active'; l2.className = 'step-label active';
            ln.className = 'step-line done';
            back.style.display = ''; next.style.flex = '1';
            next.innerHTML = '<i class="bi bi-check-lg me-1"></i> Daftar';
        }
    }

    /* ============================================================
       VALIDASI STEP 1
    ============================================================ */
    function validateStep1() {
        var ok = true;

        var nama = document.getElementById('rNama').value.trim();
        if (!nama) {
            setInputState('rNama', 'is-invalid');
            setFb('fbNama', 'invalid', 'Nama lengkap wajib diisi.');
            ok = false;
        } else { setInputState('rNama', 'is-valid'); clearFb('fbNama'); }

        var email = document.getElementById('rEmail').value.trim();
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            setInputState('rEmail', 'is-invalid');
            setFb('fbEmail', 'invalid', 'Masukkan alamat email yang valid.');
            ok = false;
        } else { setInputState('rEmail', 'is-valid'); clearFb('fbEmail'); }

        return ok;
    }

    /* ============================================================
       FOTO UPLOAD
    ============================================================ */
    var fotoZone    = document.getElementById('fotoZone');
    var fotoInput   = document.getElementById('rFoto');
    var previewWrap = document.getElementById('fotoPreviewWrap');
    var previewImg  = document.getElementById('fotoPreviewImg');

    fotoZone.addEventListener('click', function () { fotoInput.click(); });
    fotoZone.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); fotoInput.click(); }
    });
    fotoZone.addEventListener('dragover', function (e) {
        e.preventDefault(); fotoZone.style.borderColor = 'rgba(74,222,128,0.6)';
    });
    fotoZone.addEventListener('dragleave', function () { fotoZone.style.borderColor = ''; });
    fotoZone.addEventListener('drop', function (e) {
        e.preventDefault(); fotoZone.style.borderColor = '';
        if (e.dataTransfer.files[0]) handleFoto(e.dataTransfer.files[0]);
    });
    fotoInput.addEventListener('change', function () {
        if (this.files[0]) handleFoto(this.files[0]);
    });

    document.getElementById('btnRemoveFoto').addEventListener('click', function (e) {
        e.stopPropagation();
        fotoInput.value = ''; previewImg.src = '';
        fotoZone.style.display = ''; previewWrap.style.display = 'none';
        clearFb('fbFoto');
    });

    function handleFoto(file) {
        var allowed = ['image/jpeg','image/jpg','image/png','image/webp'];
        if (!allowed.includes(file.type)) {
            setFb('fbFoto', 'invalid', 'Format tidak didukung. Gunakan JPG, PNG, atau WEBP.');
            return;
        }
        if (file.size > 2*1024*1024) {
            setFb('fbFoto', 'invalid', 'Ukuran file melebihi 2 MB.'); return;
        }
        clearFb('fbFoto');
        var reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;
            fotoZone.style.display = 'none';
            previewWrap.style.display = '';
        };
        reader.readAsDataURL(file);
    }

    /* ============================================================
       USERNAME POLLING
    ============================================================ */
    document.getElementById('rUsername').addEventListener('input', function () {
        var val = this.value.trim();
        clearTimeout(usernameTimer);
        usernameOk = false;

        if (!val) { clearFb('fbUsername'); setInputState('rUsername', null); return; }

        setFb('fbUsername', 'muted', 'Memeriksa...');
        var inputEl = this;

        usernameTimer = setTimeout(function () {
            fetch('{{ route("register.murid.check_username") }}'
                  + '?username=' + encodeURIComponent(val),
                  { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                usernameOk = data.available;
                if (data.available) {
                    setInputState('rUsername', 'is-valid');
                    setFb('fbUsername', 'valid', data.message);
                } else {
                    setInputState('rUsername', 'is-invalid');
                    setFb('fbUsername', 'invalid', data.message);
                }
            })
            .catch(function () {
                setFb('fbUsername', 'warning', 'Tidak dapat memeriksa username saat ini.');
            });
        }, 450);
    });

    /* ============================================================
       PASSWORD TOGGLE + VALIDASI REALTIME
    ============================================================ */
    document.getElementById('togglePwd1').addEventListener('click', function () {
        var inp = document.getElementById('rPassword');
        var ico = document.getElementById('eyeIcon1');
        if (inp.type === 'password') { inp.type = 'text'; ico.className = 'bi bi-eye-slash'; }
        else { inp.type = 'password'; ico.className = 'bi bi-eye'; }
    });

    document.getElementById('togglePwd2').addEventListener('click', function () {
        var inp = document.getElementById('rPasswordConfirm');
        var ico = document.getElementById('eyeIcon2');
        if (inp.type === 'password') { inp.type = 'text'; ico.className = 'bi bi-eye-slash'; }
        else { inp.type = 'password'; ico.className = 'bi bi-eye'; }
    });

    function validatePassword() {
        var val = document.getElementById('rPassword').value;
        if (!val) { clearFb('fbPassword'); setInputState('rPassword', null); return false; }
        if (val.length < 8) {
            setInputState('rPassword', 'is-invalid');
            setFb('fbPassword', 'invalid', 'Password minimal 8 karakter.');
            return false;
        }
        if (!/[a-zA-Z]/.test(val) || !/\d/.test(val)) {
            setInputState('rPassword', 'is-invalid');
            setFb('fbPassword', 'invalid', 'Password harus mengandung huruf dan angka.');
            return false;
        }
        setInputState('rPassword', 'is-valid'); clearFb('fbPassword');
        return true;
    }

    function validateConfirm() {
        var pwd = document.getElementById('rPassword').value;
        var con = document.getElementById('rPasswordConfirm').value;
        if (!con) { clearFb('fbPasswordConfirm'); setInputState('rPasswordConfirm', null); return false; }
        if (pwd !== con) {
            setInputState('rPasswordConfirm', 'is-invalid');
            setFb('fbPasswordConfirm', 'invalid', 'Password tidak cocok.');
            return false;
        }
        setInputState('rPasswordConfirm', 'is-valid');
        setFb('fbPasswordConfirm', 'valid', 'Password cocok.');
        return true;
    }

    document.getElementById('rPassword').addEventListener('input', function () {
        validatePassword(); validateConfirm();
    });
    document.getElementById('rPasswordConfirm').addEventListener('input', validateConfirm);

    /* ============================================================
       TOMBOL NEXT / BACK
    ============================================================ */
    document.getElementById('btnNext').addEventListener('click', function () {
        if (currentStep === 1) {
            if (validateStep1()) goToStep(2);
        } else {
            submitRegister();
        }
    });

    document.getElementById('btnBack').addEventListener('click', function () {
        if (currentStep === 2) goToStep(1);
    });

    /* ============================================================
       SUBMIT REGISTRASI
    ============================================================ */
    function submitRegister() {
        var uname = document.getElementById('rUsername').value.trim();
        var pwdOk = validatePassword();
        var conOk = validateConfirm();

        if (!uname) {
            setInputState('rUsername', 'is-invalid');
            setFb('fbUsername', 'invalid', 'Username wajib diisi.'); return;
        }
        if (!usernameOk) {
            setInputState('rUsername', 'is-invalid');
            setFb('fbUsername', 'invalid', 'Periksa kembali username Anda.'); return;
        }
        if (!pwdOk || !conOk) return;

        var btn = document.getElementById('btnNext');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-sm"></span>&nbsp;Mendaftarkan...';

        fetch('{{ route("register.murid.post") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN':     '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: new FormData(document.getElementById('registerForm'))
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Daftar';

            if (data.success) {
                window.location.href = '{{ route("login") }}';
            } else {
                var msgs = [];
                if (data.errors)
                    Object.values(data.errors).forEach(function (arr) { msgs = msgs.concat(arr); });
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal mendaftar',
                    html: msgs.length ? msgs.join('<br>') : (data.message || 'Terjadi kesalahan.'),
                    confirmButtonColor: '#22c55e',
                    background: '#0a2e1c',
                    color: '#ffffff',
                });
            }
        })
        .catch(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Daftar';
            Swal.fire({
                icon: 'error', title: 'Koneksi gagal',
                text: 'Tidak dapat terhubung ke server.',
                confirmButtonColor: '#22c55e', background: '#0a2e1c', color: '#fff',
            });
        });
    }

    /* ============================================================
       INIT
    ============================================================ */
    goToStep(1);

}());
</script>
</body>
</html>
