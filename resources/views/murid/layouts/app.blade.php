<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Murid SIKIB</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        /* ============================================================
           BASE
        ============================================================ */
        *, *::before, *::after { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #0d0d2b;
            color: #e2e8ff;
            min-height: 100vh;
            display: flex;
        }

        /* ============================================================
           WARNA UTAMA  — tema indigo/ungu
        ============================================================ */
        :root {
            --m-primary:   #818cf8;   /* indigo-400  */
            --m-primary2:  #6366f1;   /* indigo-500  */
            --m-primary3:  #4f46e5;   /* indigo-600  */
            --m-bg:        #0d0d2b;
            --m-surface:   #13132e;
            --m-surface2:  #1a1a40;
            --m-border:    rgba(129,140,248,0.18);
            --m-text:      #e2e8ff;
            --m-muted:     rgba(226,232,255,0.45);
        }

        /* ============================================================
           SIDEBAR  (desktop ≥ 992px)
        ============================================================ */
        .m-sidebar {
            position: fixed; top: 0; left: 0;
            width: 230px; height: 100vh;
            background: var(--m-surface);
            border-right: 1px solid var(--m-border);
            display: flex; flex-direction: column;
            z-index: 1000;
            transition: width .28s cubic-bezier(.4,0,.2,1);
            overflow: hidden;
        }

        .m-sidebar.collapsed { width: 62px; }

        /* Brand */
        .m-brand {
            display: flex; align-items: center; gap: .85rem;
            padding: 1.25rem 1rem 1rem;
            border-bottom: 1px solid var(--m-border);
            text-decoration: none; flex-shrink: 0;
            min-width: 0; overflow: hidden;
        }

        .m-brand-logo {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--m-primary3), var(--m-primary));
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; color: #fff; flex-shrink: 0;
        }

        .m-brand-text { display: flex; flex-direction: column; min-width: 0; white-space: nowrap; }
        .m-brand-title { font-size: .9rem; font-weight: 800; color: #fff; }
        .m-brand-sub   { font-size: .62rem; color: var(--m-primary); text-transform: uppercase; letter-spacing: .06em; }

        .m-sidebar.collapsed .m-brand-text { opacity: 0; width: 0; }

        /* Nav */
        .m-nav { flex: 1; overflow-y: auto; overflow-x: hidden; padding: .5rem 0 1rem; scrollbar-width: thin; scrollbar-color: var(--m-border) transparent; }

        .m-section-label {
            font-size: .6rem; font-weight: 700; color: rgba(129,140,248,.5);
            text-transform: uppercase; letter-spacing: .1em;
            padding: 1rem 1rem .3rem; white-space: nowrap; overflow: hidden;
        }

        .m-sidebar.collapsed .m-section-label { opacity: 0; height: 0; padding: 0; }

        .m-nav-link {
            display: flex; align-items: center; gap: .8rem;
            padding: .6rem 1rem; margin: .12rem .5rem;
            border-radius: 10px; color: var(--m-muted);
            text-decoration: none; font-size: .875rem; font-weight: 500;
            transition: background .18s, color .18s; white-space: nowrap; overflow: hidden;
            position: relative;
        }

        .m-nav-link:hover { background: rgba(129,140,248,.1); color: var(--m-text); }

        .m-nav-link.active {
            background: rgba(129,140,248,.15); color: var(--m-primary);
            font-weight: 600;
        }

        .m-nav-link.active::before {
            content: ''; position: absolute; left: 0; top: 20%; height: 60%;
            width: 3px; background: var(--m-primary); border-radius: 0 2px 2px 0;
        }

        .m-nav-icon { font-size: 1.05rem; flex-shrink: 0; width: 20px; text-align: center; }
        .m-nav-label { transition: opacity .2s, width .28s; overflow: hidden; white-space: nowrap; }
        .m-sidebar.collapsed .m-nav-label { opacity: 0; width: 0; }

        .m-sidebar.collapsed .m-nav-link {
            justify-content: center; padding: .6rem;
            margin: .12rem auto; width: 42px;
        }

        /* Logout */
        .m-footer {
            padding: .75rem .5rem;
            border-top: 1px solid var(--m-border); flex-shrink: 0;
        }

        .m-btn-logout {
            display: flex; align-items: center; gap: .8rem;
            padding: .6rem 1rem; border-radius: 10px;
            color: rgba(248,113,113,.75); background: none; border: none;
            font-size: .875rem; font-weight: 500; cursor: pointer;
            width: 100%; font-family: 'Inter',sans-serif;
            transition: background .18s, color .18s; white-space: nowrap; overflow: hidden;
        }

        .m-btn-logout:hover { background: rgba(248,113,113,.12); color: #f87171; }
        .m-sidebar.collapsed .m-btn-logout { justify-content: center; padding: .6rem; width: 42px; margin: 0 auto; }
        .m-sidebar.collapsed .m-btn-logout .m-nav-label { opacity: 0; width: 0; }

        /* Toggle btn */
        .m-toggle {
            position: fixed; top: 1rem; left: 192px; z-index: 1100;
            width: 28px; height: 28px; border-radius: 50%;
            background: var(--m-primary3); border: 1px solid rgba(129,140,248,.35);
            color: #fff; display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: .82rem;
            transition: left .28s cubic-bezier(.4,0,.2,1);
            box-shadow: 0 2px 8px rgba(0,0,0,.4);
        }

        .m-toggle.collapsed { left: 47px; }
        .m-toggle i { transition: transform .28s; }
        .m-toggle.collapsed i { transform: rotate(180deg); }

        /* ============================================================
           MAIN WRAPPER
        ============================================================ */
        .m-main { margin-left: 230px; flex: 1; min-width: 0; display: flex; flex-direction: column; transition: margin-left .28s cubic-bezier(.4,0,.2,1); }
        .m-main.expanded { margin-left: 62px; }

        /* Topbar */
        .m-topbar {
            position: sticky; top: 0; z-index: 900;
            background: rgba(13,13,43,.92); backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--m-border);
            padding: .75rem 1.75rem;
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        }

        .m-topbar-title { font-size: 1rem; font-weight: 700; color: #fff; margin: 0; }

        .m-topbar-right { display: flex; align-items: center; gap: 1rem; }

        .m-badge-role {
            font-size: .68rem; font-weight: 600; color: var(--m-primary);
            background: rgba(129,140,248,.12); border: 1px solid rgba(129,140,248,.25);
            border-radius: 50px; padding: .15rem .55rem;
            text-transform: uppercase; letter-spacing: .04em;
        }

        .m-topbar-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, var(--m-primary3), var(--m-primary));
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem; font-weight: 700; color: #fff; flex-shrink: 0;
        }

        .m-content { flex: 1; padding: 1.75rem; }

        /* ============================================================
           BOTTOM BAR  (mobile < 992px)
        ============================================================ */
        .m-bottom-bar {
            display: none;
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 1000;
            background: var(--m-surface);
            border-top: 1px solid var(--m-border);
            padding: .5rem .5rem calc(.5rem + env(safe-area-inset-bottom));
            gap: 0;
        }

        .m-bottom-item {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; gap: .18rem;
            padding: .45rem .25rem; border-radius: 10px;
            color: var(--m-muted); text-decoration: none; font-size: .6rem;
            font-weight: 600; transition: color .18s, background .18s;
            min-width: 0; border: none; background: none; cursor: pointer;
            font-family: 'Inter',sans-serif;
        }

        .m-bottom-item i { font-size: 1.25rem; }
        .m-bottom-item:hover, .m-bottom-item.active { color: var(--m-primary); background: rgba(129,140,248,.1); }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 991.98px) {
            .m-sidebar { transform: translateX(-100%); width: 230px; transition: transform .28s cubic-bezier(.4,0,.2,1); }
            .m-sidebar.mobile-open { transform: translateX(0); }
            .m-toggle { display: none; }
            .m-main { margin-left: 0 !important; }
            .m-bottom-bar { display: flex; }
            .m-content { padding: 1.25rem 1rem 5.5rem; }
            .m-topbar-menu-btn { display: flex !important; }
        }

        @media (min-width: 992px) {
            .m-topbar-menu-btn { display: none !important; }
        }

        .m-topbar-menu-btn {
            background: rgba(129,140,248,.1); border: 1px solid rgba(129,140,248,.2);
            border-radius: 8px; padding: .4rem .55rem; color: var(--m-primary);
            cursor: pointer; display: none; align-items: center;
        }

        /* Backdrop */
        .m-backdrop {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.55); z-index: 999;
        }

        .m-backdrop.show { display: block; }
    </style>

    @stack('styles')
</head>
<body>

<div class="m-backdrop" id="mBackdrop"></div>

<!-- ================================================================
     SIDEBAR
================================================================ -->
<aside class="m-sidebar" id="mSidebar">
    <a href="{{ route('murid.dashboard') }}" class="m-brand">
        <div class="m-brand-logo"><i class="bi bi-mortarboard-fill"></i></div>
        <div class="m-brand-text">
            <span class="m-brand-title">SIKIB</span>
            <span class="m-brand-sub">Portal Murid</span>
        </div>
    </a>

    <nav class="m-nav">
        <div class="m-section-label">Menu</div>

        <a href="{{ route('murid.dashboard') }}"
           class="m-nav-link {{ request()->routeIs('murid.dashboard') ? 'active' : '' }}"
           title="Beranda">
            <i class="bi bi-grid-1x2-fill m-nav-icon"></i>
            <span class="m-nav-label">Beranda</span>
        </a>

        <a href="{{ route('murid.absen') }}"
           class="m-nav-link {{ request()->routeIs('murid.absen') ? 'active' : '' }}"
           title="Absensi">
            <i class="bi bi-clipboard2-check-fill m-nav-icon"></i>
            <span class="m-nav-label">Absensi</span>
        </a>

        <a href="{{ route('murid.riwayat_absen') }}"
           class="m-nav-link {{ request()->routeIs('murid.riwayat_absen') ? 'active' : '' }}"
           title="Riwayat">
            <i class="bi bi-clock-history m-nav-icon"></i>
            <span class="m-nav-label">Riwayat</span>
        </a>

        <div class="m-section-label">Akun</div>

        <a href="#" class="m-nav-link" title="Profil">
            <i class="bi bi-person-circle m-nav-icon"></i>
            <span class="m-nav-label">Profil</span>
        </a>
    </nav>

    <div class="m-footer">
        <form method="POST" action="{{ route('logout') }}" id="mLogoutForm">
            @csrf
            <button type="button" class="m-btn-logout" onclick="mConfirmLogout()" title="Log Out">
                <i class="bi bi-box-arrow-left m-nav-icon"></i>
                <span class="m-nav-label">Log Out</span>
            </button>
        </form>
    </div>
</aside>

<!-- Toggle desktop -->
<button class="m-toggle" id="mToggle" aria-label="Toggle sidebar">
    <i class="bi bi-chevron-left"></i>
</button>

<!-- ================================================================
     MAIN WRAPPER
================================================================ -->
<div class="m-main" id="mMain">

    <!-- Topbar -->
    <header class="m-topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="m-topbar-menu-btn" id="mMenuBtn" aria-label="Menu">
                <i class="bi bi-list" style="font-size:1.1rem;"></i>
            </button>
            <h1 class="m-topbar-title">@yield('page-title', 'Dashboard')</h1>
        </div>

        <div class="m-topbar-right">
            <span class="m-badge-role">Murid</span>
            <div class="d-flex align-items-center gap-2">
                <div class="m-topbar-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'M', 0, 1)) }}
                </div>
                <span class="d-none d-sm-inline" style="font-size:.85rem;color:var(--m-muted);">
                    {{ Auth::user()->name ?? 'Murid' }}
                </span>
            </div>
        </div>
    </header>

    <!-- Konten -->
    <main class="m-content">
        @yield('content')
    </main>
</div>

<!-- ================================================================
     BOTTOM BAR  (mobile)
================================================================ -->
<nav class="m-bottom-bar d-flex" aria-label="Navigasi bawah">
    <a href="{{ route('murid.dashboard') }}"
       class="m-bottom-item {{ request()->routeIs('murid.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Beranda</span>
    </a>
    <a href="{{ route('murid.absen') }}"
       class="m-bottom-item {{ request()->routeIs('murid.absen') ? 'active' : '' }}">
        <i class="bi bi-clipboard2-check-fill"></i>
        <span>Absensi</span>
    </a>
    <a href="{{ route('murid.riwayat_absen') }}"
       class="m-bottom-item {{ request()->routeIs('murid.riwayat_absen') ? 'active' : '' }}">
        <i class="bi bi-clock-history"></i>
        <span>Riwayat</span>
    </a>
    <a href="#"
       class="m-bottom-item">
        <i class="bi bi-person-circle"></i>
        <span>Profil</span>
    </a>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
(function () {
    'use strict';

    /* ---- Sidebar desktop toggle ---- */
    var sidebar  = document.getElementById('mSidebar');
    var toggle   = document.getElementById('mToggle');
    var mainWrap = document.getElementById('mMain');
    var STORE    = 'sikib_murid_sidebar';

    if (localStorage.getItem(STORE) === '1') {
        sidebar.classList.add('collapsed');
        toggle.classList.add('collapsed');
        mainWrap.classList.add('expanded');
    }

    if (toggle) {
        toggle.addEventListener('click', function () {
            var c = sidebar.classList.toggle('collapsed');
            this.classList.toggle('collapsed', c);
            mainWrap.classList.toggle('expanded', c);
            localStorage.setItem(STORE, c ? '1' : '0');
        });
    }

    /* ---- Sidebar mobile ---- */
    var menuBtn  = document.getElementById('mMenuBtn');
    var backdrop = document.getElementById('mBackdrop');

    function openMobile()  { sidebar.classList.add('mobile-open'); backdrop.classList.add('show'); document.body.style.overflow='hidden'; }
    function closeMobile() { sidebar.classList.remove('mobile-open'); backdrop.classList.remove('show'); document.body.style.overflow=''; }

    if (menuBtn)  menuBtn.addEventListener('click', openMobile);
    backdrop.addEventListener('click', closeMobile);

    /* ---- Logout ---- */
    window.mConfirmLogout = function () {
        Swal.fire({
            title: 'Keluar?', text: 'Sesi Anda akan diakhiri.',
            icon: 'question', showCancelButton: true,
            confirmButtonText: 'Ya, keluar', cancelButtonText: 'Batal',
            confirmButtonColor: '#6366f1', cancelButtonColor: 'rgba(255,255,255,0.1)',
            background: '#13132e', color: '#e2e8ff',
        }).then(function (r) { if (r.isConfirmed) document.getElementById('mLogoutForm').submit(); });
    };
}());
</script>

@stack('scripts')
</body>
</html>
