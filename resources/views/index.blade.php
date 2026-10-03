<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SIKIB - Sistem Informasi Kehadiran Ibnu Sina, SMKS Ibnu Sina">
    <title>SMKS Ibnu Sina - SIKIB</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ============================================================
           RESET & BASE
        ============================================================ */
        *, *::before, *::after {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0faf4;
            color: #1a3a2a;
        }

        /* ============================================================
           HERO SECTION
        ============================================================ */
        .hero-section {
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: linear-gradient(160deg, #064e2e 0%, #0a7c4a 45%, #0d9e5e 100%);
            padding: 2rem 1rem;
        }

        /* ---- Canvas animasi latar ---- */
        #circuit-canvas {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0.55;
            pointer-events: none;
        }

        /* ---- Overlay gradien atas supaya konten tetap terbaca ---- */
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 70% 60% at 50% 40%, rgba(10, 124, 74, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ---- Kartu konten utama ---- */
        .hero-card {
            position: relative;
            z-index: 10;
            text-align: center;
            max-width: 780px;
            width: 100%;
            padding: 4rem 4rem;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.14);
            /* pojok kiri atas melengkung, kanan atas lancip, kanan bawah melengkung, kiri bawah lancip */
            border-radius: 28px 0px 28px 0px;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow:
                0 12px 48px rgba(0, 0, 0, 0.35),
                inset 0 1px 0 rgba(255, 255, 255, 0.14);
            animation: cardEnter 0.8s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes cardEnter {
            from {
                opacity: 0;
                transform: translateY(28px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ---- Logo + Nama Sekolah ---- */
        .school-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .school-logo {
            width: 84px;
            height: 84px;
            object-fit: contain;
            border-radius: 16px;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.35));
            flex-shrink: 0;
        }

        .school-name {
            font-size: clamp(1.2rem, 2.8vw, 1.65rem);
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.04em;
            line-height: 1.25;
            text-align: left;
            text-shadow: 0 1px 6px rgba(0,0,0,0.3);
        }

        /* ---- Divider ---- */
        .hero-divider {
            width: 52px;
            height: 3px;
            background: linear-gradient(90deg, #4ade80, #86efac);
            border-radius: 2px;
            margin: 0 auto 1.6rem;
        }

        /* ---- Judul Sistem ---- */
        .hero-title {
            font-size: clamp(0.85rem, 2vw, 1.05rem);
            font-weight: 500;
            color: white;
            margin-bottom: 1.2rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            text-align:center;
        }

        .hero-subtitle {
            font-size: clamp(1.6rem, 5vw, 2.75rem);
            font-weight: 800;
            color: white;
            line-height: 1.2;
            text-shadow: 0 2px 10px rgba(0,0,0,0.25);
        }

        .hero-subtitle span {
            color: #4ade80;
        }

        /* ---- Tombol ---- */
        .btn-absen {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.8rem 2.4rem;
            font-size: 1rem;
            font-weight: 600;
            color: #064e2e;
            background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
            border: none;
            border-radius: 50px;
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            box-shadow: 0 4px 18px rgba(34, 197, 94, 0.45);
            letter-spacing: 0.01em;
        }

        .btn-absen:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(34, 197, 94, 0.6);
            color: #042a1a;
        }

        .btn-absen:active {
            transform: translateY(-1px);
        }

        .btn-absen svg {
            transition: transform 0.2s ease;
        }

        .btn-absen:hover svg {
            transform: translateX(3px);
        }

        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.45; transform: scale(0.75); }
        }

        /* ============================================================
           FOOTER
        ============================================================ */
        .site-footer {
            background: #0a2e1c;
            color: rgba(255, 255, 255, 0.78);
        }

        .footer-top {
            padding: 3.5rem 0 2.5rem;
        }

        .footer-logo {
            width: 54px;
            height: 54px;
            object-fit: contain;
            border-radius: 10px;
            flex-shrink: 0;
        }

        .footer-school-name {
            font-size: 1rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.04em;
            line-height: 1.3;
        }

        .footer-desc {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.62);
            line-height: 1.75;
            margin-bottom: 0;
        }

        .footer-heading {
            font-size: 0.875rem;
            font-weight: 700;
            color: #4ade80;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 1.25rem;
        }

        .footer-contact-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .footer-contact-list li {
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.55;
        }
        .footer-contact-list li svg {
            color: #4ade80;
            margin-top: 2px;
            flex-shrink: 0;
        }
        .footer-contact-list a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.2s;
        }
        .footer-contact-list a:hover {
            color: #4ade80;
        }
        .footer-map-wrapper {
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid rgba(74, 222, 128, 0.2);
        }
        .footer-map-wrapper iframe {
            display: block;
        }
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1.25rem 0;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.45);
        }

        @media (max-width: 575.98px) {
            .hero-card {
                padding: 2.5rem 1.75rem;
                border-radius: 18px 0px 18px 0px;
            }

            .school-brand {
                gap: 0.85rem;
            }

            .school-logo {
                width: 58px;
                height: 58px;
            }

            .school-name {
                font-size: 1.05rem;
            }
        }

        @media (max-width: 767.98px) {
            .footer-top {
                padding: 2.5rem 0 2rem;
            }
        }
    </style>
</head>
<body>

    <!-- ============================================================
         HERO SECTION
    ============================================================ -->
    <section class="hero-section" aria-label="Halaman utama SIKIB">
        <canvas id="circuit-canvas" aria-hidden="true"></canvas>
        <div class="hero-overlay" aria-hidden="true"></div>
        <div class="hero-card">
            <div class="school-brand">
                <img
                    src="{{ asset('logo.jpeg') }}"
                    alt="Logo SMKS Ibnu Sina"
                    class="school-logo"
                >
                <div class="school-name">SMKS IBNU SINA</div>
            </div>
            <div class="hero-divider" aria-hidden="true"></div>
             <h1 class="hero-subtitle">
                <span>SIKIS</span>
            </h1>
            <p class="hero-title">Sistem Informasi Kehadiran Ibnu Sina</p>
            @auth
                @php
                    $dashboardUrl = match(Auth::user()->role) {
                        'admin' => route('admin.dashboard'),
                        'guru'  => route('guru.dashboard'),
                        'murid' => route('murid.dashboard'),
                        default => route('login'),
                    };
                @endphp
                <a href="{{ $dashboardUrl }}" class="btn-absen">
            @else
                <a href="{{ route('login') }}" class="btn-absen">
            @endauth
                Absen Sekarang
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                </svg>
            </a>
        </div>
    </section>

    <!-- ============================================================
         FOOTER
    ============================================================ -->
    @include('layouts.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            'use strict';

            var canvas  = document.getElementById('circuit-canvas');
            var ctx     = canvas.getContext('2d');
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

            /* ---- Resize canvas ---- */
            function resize() {
                W = canvas.width  = canvas.offsetWidth;
                H = canvas.height = canvas.offsetHeight;
            }

            window.addEventListener('resize', function () {
                resize();
                buildGraph();
            });
            function buildGraph() {
                nodes = [];
                edges = [];
                packets = [];

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
                        var dx = nodes[i].x - nodes[j].x;
                        var dy = nodes[i].y - nodes[j].y;
                        var dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < CONNECT_RADIUS) {
                            edges.push({ a: i, b: j, dist: dist });
                        }
                    }
                }
            }

            function spawnInitialPackets() {
                for (var k = 0; k < 22; k++) {
                    spawnPacket();
                }
            }

            function spawnPacket() {
                if (edges.length === 0) return;
                var edge    = edges[Math.floor(Math.random() * edges.length)];
                var reverse = Math.random() > 0.5;
                packets.push({
                    edge:    edge,
                    t:       Math.random(),           /* posisi 0..1 di sepanjang edge */
                    dir:     reverse ? -1 : 1,
                    speed:   PACKET_SPEED * (0.6 + Math.random() * 0.8)
                });
            }

            function tick() {
                ctx.clearRect(0, 0, W, H);
                for (var i = 0; i < nodes.length; i++) {
                    var n = nodes[i];
                    n.x += n.vx;
                    n.y += n.vy;

                    /* Pantulan di tepi */
                    if (n.x < 0 || n.x > W) n.vx *= -1;
                    if (n.y < 0 || n.y > H) n.vy *= -1;
                    n.x = Math.max(0, Math.min(W, n.x));
                    n.y = Math.max(0, Math.min(H, n.y));
                }

                rebuildEdges();
                for (var e = 0; e < edges.length; e++) {
                    var edge = edges[e];
                    var na   = nodes[edge.a];
                    var nb   = nodes[edge.b];
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

                var alivePackets = [];
                for (var p = 0; p < packets.length; p++) {
                    var pk   = packets[p];
                    pk.t    += (pk.speed / 600) * pk.dir;

                    if (pk.t < 0 || pk.t > 1) {
                        spawnPacket(); 
                        continue;
                    }

                    alivePackets.push(pk);

                    var pa = nodes[pk.edge.a];
                    var pb = nodes[pk.edge.b];
                    var px = pa.x + (pb.x - pa.x) * pk.t;
                    var py = pa.y + (pb.y - pa.y) * pk.t;

                    ctx.beginPath();
                    ctx.arc(px, py, 3.2, 0, Math.PI * 2);
                    ctx.fillStyle = PACKET_COLOR;
                    ctx.fill();

                    var trailLen = 0.08;
                    var tx = pa.x + (pb.x - pa.x) * Math.max(0, Math.min(1, pk.t - trailLen * pk.dir));
                    var ty = pa.y + (pb.y - pa.y) * Math.max(0, Math.min(1, pk.t - trailLen * pk.dir));
                    var grad = ctx.createLinearGradient(tx, ty, px, py);
                    grad.addColorStop(0, hexToRgba(PACKET_COLOR, 0));
                    grad.addColorStop(1, hexToRgba(PACKET_COLOR, 0.82));
                    ctx.beginPath();
                    ctx.moveTo(tx, ty);
                    ctx.lineTo(px, py);
                    ctx.strokeStyle = grad;
                    ctx.lineWidth   = 2;
                    ctx.stroke();
                }
                packets = alivePackets;

                raf = requestAnimationFrame(tick);
            }

            function hexToRgba(hex, alpha) {
                var r = parseInt(hex.slice(1, 3), 16);
                var g = parseInt(hex.slice(3, 5), 16);
                var b = parseInt(hex.slice(5, 7), 16);
                return 'rgba(' + r + ',' + g + ',' + b + ',' + alpha + ')';
            }

            document.addEventListener('visibilitychange', function () {
                if (document.hidden) {
                    cancelAnimationFrame(raf);
                } else {
                    raf = requestAnimationFrame(tick);
                }
            });

            resize();
            buildGraph();
            tick();
        }());
    </script>

</body>
</html>
