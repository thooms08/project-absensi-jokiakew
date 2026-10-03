<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login SIKIB - Sistem Informasi Kehadiran Ibnu Sina">
    <title>Login - SMKS Ibnu Sina</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        /* ============================================================
           RESET & BASE
        ============================================================ */
        *, *::before, *::after { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0faf4;
            color: #1a3a2a;
        }

        /* ============================================================
           HALAMAN LOGIN  -  latar identik dengan index.blade.php
        ============================================================ */
        .login-page {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(160deg, #064e2e 0%, #0a7c4a 45%, #0d9e5e 100%);
            padding: 2rem 1rem;
        }

        /* Canvas animasi (identik index) */
        #circuit-canvas {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0.55;
            pointer-events: none;
        }

        /* Overlay gradien */
        .login-overlay {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 70% 60% at 50% 40%, rgba(10,124,74,0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ============================================================
           CARD LOGIN
        ============================================================ */
        .login-card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 900px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 28px 0px 28px 0px;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow:
                0 12px 48px rgba(0, 0, 0, 0.35),
                inset 0 1px 0 rgba(255, 255, 255, 0.14);
            animation: cardEnter 0.8s cubic-bezier(0.22, 1, 0.36, 1) both;
            overflow: hidden;
        }

        @keyframes cardEnter {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ---- Layout dua kolom dalam card ---- */
        .card-inner {
            display: flex;
            min-height: 520px;
        }

        /* ---- Kolom kiri: identitas sekolah ---- */
        .card-left {
            flex: 0 0 42%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            background: rgba(0, 0, 0, 0.18);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            gap: 0;
        }

        .login-school-logo {
            width: 96px;
            height: 96px;
            object-fit: contain;
            border-radius: 18px;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,0.4));
            margin-bottom: 1.25rem;
        }

        .login-school-name {
            font-size: 1.3rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.05em;
            line-height: 1.2;
            margin-bottom: 1rem;
            text-shadow: 0 1px 6px rgba(0,0,0,0.3);
        }

        .login-divider {
            width: 44px;
            height: 3px;
            background: linear-gradient(90deg, #4ade80, #86efac);
            border-radius: 2px;
            margin: 0 auto 1rem;
        }

        .login-system-name {
            font-size: 2rem;
            font-weight: 800;
            color: #4ade80;
            letter-spacing: 0.06em;
            line-height: 1;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 12px rgba(74,222,128,0.3);
        }

        .login-system-desc {
            font-size: 0.78rem;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.6);
            letter-spacing: 0.04em;
            line-height: 1.5;
            text-transform: uppercase;
        }

        /* ---- Kolom kanan: form ---- */
        .card-right {
            flex: 1;
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-heading {
            font-size: 1.35rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.4rem;
        }

        .form-subheading {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.55);
            margin-bottom: 2rem;
        }

        /* ---- Input group ---- */
        .form-label-custom {
            font-size: 0.8rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.75);
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin-bottom: 0.4rem;
            display: block;
        }

        .input-custom {
            width: 100%;
            padding: 0.72rem 1rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 10px;
            color: #ffffff;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .input-custom::placeholder {
            color: rgba(255, 255, 255, 0.3);
        }

        .input-custom:focus {
            border-color: #4ade80;
            background: rgba(74, 222, 128, 0.07);
            box-shadow: 0 0 0 3px rgba(74, 222, 128, 0.15);
        }

        .input-custom.is-invalid-custom {
            border-color: #f87171;
            box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.18);
        }

        /* ---- Password wrapper (eye toggle) ---- */
        .password-wrapper {
            position: relative;
        }

        .password-wrapper .input-custom {
            padding-right: 3rem;
        }

        .btn-eye {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.45);
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }

        .btn-eye:hover {
            color: #4ade80;
        }

        /* ---- Captcha ---- */
        .captcha-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .captcha-question {
            flex-shrink: 0;
            background: rgba(74, 222, 128, 0.12);
            border: 1px solid rgba(74, 222, 128, 0.3);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: #4ade80;
            letter-spacing: 0.08em;
            min-width: 80px;
            text-align: center;
        }

        .captcha-input {
            flex: 1;
        }

        .btn-captcha-refresh {
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 8px;
            padding: 0.5rem 0.65rem;
            color: rgba(255, 255, 255, 0.55);
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: color 0.2s, background 0.2s;
        }

        .btn-captcha-refresh:hover {
            background: rgba(74, 222, 128, 0.1);
            color: #4ade80;
        }

        /* ---- Checkbox remember me ---- */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .checkbox-custom {
            width: 17px;
            height: 17px;
            accent-color: #4ade80;
            cursor: pointer;
            flex-shrink: 0;
        }

        .remember-label {
            font-size: 0.83rem;
            color: rgba(255, 255, 255, 0.65);
            cursor: pointer;
            user-select: none;
        }

        /* ---- Tombol submit ---- */
        .btn-login {
            width: 100%;
            padding: 0.82rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: #064e2e;
            background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            letter-spacing: 0.02em;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 18px rgba(34, 197, 94, 0.45);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 26px rgba(34, 197, 94, 0.6);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        /* ---- Kembali ke beranda ---- */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            transition: color 0.2s;
            margin-top: 1.25rem;
        }

        .back-link:hover {
            color: #4ade80;
        }

        /* ---- Spinner loading ---- */
        .spinner-border-sm-custom {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(6, 78, 46, 0.3);
            border-top-color: #064e2e;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 767.98px) {
            .login-card {
                border-radius: 18px 0px 18px 0px;
            }

            .card-inner {
                flex-direction: column;
                min-height: unset;
            }

            .card-left {
                flex: none;
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                padding: 2rem 1.5rem;
                flex-direction: row;
                flex-wrap: wrap;
                gap: 1rem;
                justify-content: center;
            }

            .login-school-logo {
                width: 64px;
                height: 64px;
                margin-bottom: 0;
            }

            .card-right {
                padding: 2rem 1.5rem;
            }
        }

        @media (max-width: 400px) {
            .card-left {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<div class="login-page" aria-label="Halaman login SIKIB">

    <!-- Canvas animasi (identik dengan index) -->
    <canvas id="circuit-canvas" aria-hidden="true"></canvas>
    <div class="login-overlay" aria-hidden="true"></div>

    <!-- Card login -->
    <div class="login-card">
        <div class="card-inner">

            <!-- ====================================================
                 KOLOM KIRI: Identitas sekolah
            ==================================================== -->
            <div class="card-left">
                <img
                    src="{{ asset('logo.jpeg') }}"
                    alt="Logo SMKS Ibnu Sina"
                    class="login-school-logo"
                >
                <div>
                    <div class="login-school-name">SMKS IBNU SINA</div>
                    <div class="login-divider" aria-hidden="true"></div>
                    <div class="login-system-name">SIKIB</div>
                    <div class="login-system-desc">Sistem Informasi<br>Kehadiran Ibnu Sina</div>
                </div>
            </div>

            <!-- ====================================================
                 KOLOM KANAN: Form login
            ==================================================== -->
            <div class="card-right">
                <h1 class="form-heading">Masuk ke Akun</h1>
                <p class="form-subheading">Masukkan kredensial Anda untuk melanjutkan</p>

                <form id="loginForm" novalidate>
                    @csrf

                    <!-- Username / Email -->
                    <div class="mb-3">
                        <label for="identity" class="form-label-custom">Username / Email</label>
                        <input
                            type="text"
                            id="identity"
                            name="identity"
                            class="input-custom"
                            placeholder="Masukkan username atau email"
                            autocomplete="username"
                            required
                        >
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label-custom">Password</label>
                        <div class="password-wrapper">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="input-custom"
                                placeholder="Masukkan password"
                                autocomplete="current-password"
                                required
                            >
                            <button
                                type="button"
                                class="btn-eye"
                                id="togglePassword"
                                aria-label="Tampilkan password"
                                title="Tampilkan / sembunyikan password"
                            >
                                <!-- Ikon mata terbuka -->
                                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                </svg>
                                <!-- Ikon mata tertutup (tersembunyi default) -->
                                <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" style="display:none;">
                                    <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
                                    <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
                                    <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12-.708.708z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Captcha -->
                    <div class="mb-3">
                        <label class="form-label-custom">Verifikasi</label>
                        <div class="captcha-row">
                            <div class="captcha-question" id="captchaQuestion" aria-live="polite">
                                {{ session('captcha_question', '? + ? = ?') }}
                            </div>
                            <input
                                type="number"
                                id="captcha"
                                name="captcha"
                                class="input-custom captcha-input"
                                placeholder="Jawaban"
                                min="1"
                                max="10"
                                required
                            >
                            <button
                                type="button"
                                class="btn-captcha-refresh"
                                id="refreshCaptcha"
                                aria-label="Refresh captcha"
                                title="Ganti soal"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                                    <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember me -->
                    <div class="mb-4 remember-row">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            class="checkbox-custom"
                            value="1"
                        >
                        <label for="remember" class="remember-label">
                            Ingat saya selama 3 jam
                        </label>
                    </div>

                    <!-- Tombol login -->
                    <button type="submit" class="btn-login" id="btnLogin">
                        <span id="btnLoginText">Masuk</span>
                    </button>

                    <!-- Kembali ke beranda -->
                    <div class="text-center">
                        <a href="{{ route('home') }}" class="back-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                            </svg>
                            Kembali ke Beranda
                        </a>
                    </div>

                </form>
            </div>
            <!-- akhir kolom kanan -->

        </div>
    </div>
    <!-- akhir login-card -->

</div>
<!-- akhir login-page -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
(function () {
    'use strict';

    /* ================================================================
       ANIMASI CANVAS  -  100% identik dengan index.blade.php
    ================================================================ */
    var canvas = document.getElementById('circuit-canvas');
    var ctx    = canvas.getContext('2d');
    var W, H;

    var NODE_COUNT     = 55;
    var CONNECT_RADIUS = 190;
    var PACKET_SPEED   = 2.2;
    var LINE_COLOR     = '#4ade80';
    var NODE_COLOR     = '#86efac';
    var PACKET_COLOR   = '#ffffff';

    var nodes   = [];
    var packets = [];
    var edges   = [];
    var raf;

    function resize() {
        W = canvas.width  = canvas.offsetWidth;
        H = canvas.height = canvas.offsetHeight;
    }

    window.addEventListener('resize', function () { resize(); buildGraph(); });

    function buildGraph() {
        nodes = []; edges = []; packets = [];
        for (var i = 0; i < NODE_COUNT; i++) {
            nodes.push({
                x:  Math.random() * W,
                y:  Math.random() * H,
                vx: (Math.random() - 0.5) * 0.45,
                vy: (Math.random() - 0.5) * 0.45,
                r:  Math.random() * 2.5 + 2
            });
        }
        rebuildEdges();
        spawnInitialPackets();
    }

    function rebuildEdges() {
        edges = [];
        for (var i = 0; i < nodes.length; i++) {
            for (var j = i + 1; j < nodes.length; j++) {
                var dx   = nodes[i].x - nodes[j].x;
                var dy   = nodes[i].y - nodes[j].y;
                var dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < CONNECT_RADIUS) edges.push({ a: i, b: j, dist: dist });
            }
        }
    }

    function spawnInitialPackets() {
        for (var k = 0; k < 22; k++) spawnPacket();
    }

    function spawnPacket() {
        if (edges.length === 0) return;
        var edge = edges[Math.floor(Math.random() * edges.length)];
        packets.push({
            edge:  edge,
            t:     Math.random(),
            dir:   Math.random() > 0.5 ? -1 : 1,
            speed: PACKET_SPEED * (0.6 + Math.random() * 0.8)
        });
    }

    function tick() {
        ctx.clearRect(0, 0, W, H);

        for (var i = 0; i < nodes.length; i++) {
            var n = nodes[i];
            n.x += n.vx; n.y += n.vy;
            if (n.x < 0 || n.x > W) n.vx *= -1;
            if (n.y < 0 || n.y > H) n.vy *= -1;
            n.x = Math.max(0, Math.min(W, n.x));
            n.y = Math.max(0, Math.min(H, n.y));
        }

        rebuildEdges();

        for (var e = 0; e < edges.length; e++) {
            var edge  = edges[e];
            var na    = nodes[edge.a];
            var nb    = nodes[edge.b];
            var alpha = 1 - edge.dist / CONNECT_RADIUS;
            ctx.beginPath();
            ctx.moveTo(na.x, na.y);
            ctx.lineTo(nb.x, nb.y);
            ctx.strokeStyle = hexToRgba(LINE_COLOR, alpha * 0.85);
            ctx.lineWidth   = 1.2;
            ctx.stroke();
        }

        for (var i = 0; i < nodes.length; i++) {
            var n = nodes[i];
            ctx.beginPath();
            ctx.arc(n.x, n.y, n.r, 0, Math.PI * 2);
            ctx.fillStyle = hexToRgba(NODE_COLOR, 0.92);
            ctx.fill();
            ctx.beginPath();
            ctx.arc(n.x, n.y, n.r + 3.5, 0, Math.PI * 2);
            ctx.strokeStyle = hexToRgba(NODE_COLOR, 0.32);
            ctx.lineWidth = 1.2;
            ctx.stroke();
        }

        var alive = [];
        for (var p = 0; p < packets.length; p++) {
            var pk = packets[p];
            pk.t  += (pk.speed / 600) * pk.dir;
            if (pk.t < 0 || pk.t > 1) { spawnPacket(); continue; }
            alive.push(pk);

            var pa = nodes[pk.edge.a];
            var pb = nodes[pk.edge.b];
            var px = pa.x + (pb.x - pa.x) * pk.t;
            var py = pa.y + (pb.y - pa.y) * pk.t;

            ctx.beginPath();
            ctx.arc(px, py, 3.2, 0, Math.PI * 2);
            ctx.fillStyle = PACKET_COLOR;
            ctx.fill();

            var tl  = 0.08;
            var tx  = pa.x + (pb.x - pa.x) * Math.max(0, Math.min(1, pk.t - tl * pk.dir));
            var ty  = pa.y + (pb.y - pa.y) * Math.max(0, Math.min(1, pk.t - tl * pk.dir));
            var g   = ctx.createLinearGradient(tx, ty, px, py);
            g.addColorStop(0, hexToRgba(PACKET_COLOR, 0));
            g.addColorStop(1, hexToRgba(PACKET_COLOR, 0.82));
            ctx.beginPath();
            ctx.moveTo(tx, ty);
            ctx.lineTo(px, py);
            ctx.strokeStyle = g;
            ctx.lineWidth   = 2;
            ctx.stroke();
        }
        packets = alive;
        raf = requestAnimationFrame(tick);
    }

    function hexToRgba(hex, alpha) {
        var r = parseInt(hex.slice(1, 3), 16);
        var g = parseInt(hex.slice(3, 5), 16);
        var b = parseInt(hex.slice(5, 7), 16);
        return 'rgba(' + r + ',' + g + ',' + b + ',' + alpha + ')';
    }

    document.addEventListener('visibilitychange', function () {
        if (document.hidden) cancelAnimationFrame(raf);
        else raf = requestAnimationFrame(tick);
    });

    resize();
    buildGraph();
    tick();

    /* ================================================================
       EYE TOGGLE PASSWORD
    ================================================================ */
    var toggleBtn  = document.getElementById('togglePassword');
    var pwdInput   = document.getElementById('password');
    var eyeOpen    = document.getElementById('eyeOpen');
    var eyeClosed  = document.getElementById('eyeClosed');

    toggleBtn.addEventListener('click', function () {
        var isPassword = pwdInput.type === 'password';
        pwdInput.type  = isPassword ? 'text' : 'password';
        eyeOpen.style.display   = isPassword ? 'none'  : '';
        eyeClosed.style.display = isPassword ? ''      : 'none';
        toggleBtn.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
    });

    /* ================================================================
       REFRESH CAPTCHA
    ================================================================ */
    var captchaQuestion = document.getElementById('captchaQuestion');
    var captchaInput    = document.getElementById('captcha');

    document.getElementById('refreshCaptcha').addEventListener('click', function () {
        fetch('{{ route("captcha.refresh") }}', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            captchaQuestion.textContent = data.question;
            captchaInput.value = '';
            captchaInput.focus();
        });
    });

    /* ================================================================
       FORM SUBMIT  -  AJAX + SweetAlert2
    ================================================================ */
    var form    = document.getElementById('loginForm');
    var btnText = document.getElementById('btnLoginText');
    var btnEl   = document.getElementById('btnLogin');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        /* Validasi sisi klien sederhana */
        var identity = document.getElementById('identity').value.trim();
        var password = document.getElementById('password').value;
        var captcha  = captchaInput.value.trim();

        if (!identity || !password || !captcha) {
            Swal.fire({
                icon: 'warning',
                title: 'Form belum lengkap',
                text: 'Harap isi semua field sebelum melanjutkan.',
                confirmButtonColor: '#22c55e',
                background: '#0a2e1c',
                color: '#ffffff',
            });
            return;
        }

        /* Tampilkan spinner */
        btnEl.disabled  = true;
        btnText.innerHTML = '<span class="spinner-border-sm-custom"></span>&nbsp;Memproses...';

        var formData = new FormData(form);

        fetch('{{ route("login.post") }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            btnEl.disabled  = false;
            btnText.innerHTML = 'Masuk';

            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Login berhasil',
                    text: 'Mengalihkan ke dashboard...',
                    timer: 1400,
                    showConfirmButton: false,
                    background: '#0a2e1c',
                    color: '#ffffff',
                    iconColor: '#4ade80',
                }).then(function () {
                    window.location.href = data.redirect;
                });
                return;
            }

            /* Mapping pesan error ke SweetAlert2 */
            var messages = {
                identity_error: 'Username / email tidak ditemukan di sistem.',
                password_error: 'Password yang Anda masukkan salah.',
                captcha_error:  'Jawaban captcha salah. Silakan coba lagi.',
            };

            var msg = messages[data.status] || (data.message || 'Terjadi kesalahan, silakan coba lagi.');

            Swal.fire({
                icon: 'error',
                title: 'Login gagal',
                text: msg,
                confirmButtonText: 'Coba lagi',
                confirmButtonColor: '#22c55e',
                background: '#0a2e1c',
                color: '#ffffff',
            });

            /* Reset captcha setelah error */
            fetch('{{ route("captcha.refresh") }}', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                captchaQuestion.textContent = d.question;
                captchaInput.value = '';
            });
        })
        .catch(function () {
            btnEl.disabled  = false;
            btnText.innerHTML = 'Masuk';
            Swal.fire({
                icon: 'error',
                title: 'Koneksi bermasalah',
                text: 'Gagal menghubungi server. Periksa koneksi internet Anda.',
                confirmButtonColor: '#22c55e',
                background: '#0a2e1c',
                color: '#ffffff',
            });
        });
    });

}());
</script>

</body>
</html>
