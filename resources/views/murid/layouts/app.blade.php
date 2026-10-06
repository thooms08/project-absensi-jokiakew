<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Murid SIKIB</title>

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
        /* ============================================================
           RESET & BASE (Disesuaikan dengan tema Admin)
        ============================================================ */
        *, *::before, *::after { box-sizing: border-box; }

        html { height: 100%; }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background: #0d1f14;
            color: #e2f5ea;
            min-height: 100vh;
            display: flex;
        }

        /* ============================================================
           SIDEBAR
        ============================================================ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 240px;
            background: linear-gradient(180deg, #0a2e1c 0%, #062015 100%);
            border-right: 1px solid rgba(74, 222, 128, 0.12);
            display: flex;
            flex-direction: column;
            z-index: 1000;
            transition: width 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .sidebar.collapsed { width: 62px; }

        /* ---- Brand sidebar ---- */
        .sidebar-brand {
            display: flex; align-items: center; gap: 0.85rem;
            padding: 1.25rem 1rem 1rem;
            border-bottom: 1px solid rgba(74, 222, 128, 0.1);
            text-decoration: none; flex-shrink: 0;
            overflow: hidden; min-width: 0;
        }

        .sidebar-brand-logo {
            width: 36px; height: 36px;
            object-fit: contain; border-radius: 8px; flex-shrink: 0;
        }

        .sidebar-brand-text {
            display: flex; flex-direction: column;
            min-width: 0; white-space: nowrap;
        }

        .sidebar-brand-title {
            font-size: 0.9rem; font-weight: 800; color: #ffffff;
            letter-spacing: 0.04em; line-height: 1.2;
        }

        .sidebar-brand-sub {
            font-size: 0.65rem; color: #4ade80; font-weight: 500;
            letter-spacing: 0.06em; text-transform: uppercase;
        }

        .sidebar.collapsed .sidebar-brand-text { opacity: 0; width: 0; }

        /* ---- Label section ---- */
        .sidebar-section-label {
            font-size: 0.62rem; font-weight: 700;
            color: rgba(74, 222, 128, 0.5); text-transform: uppercase;
            letter-spacing: 0.1em; padding: 1.1rem 1.1rem 0.35rem;
            white-space: nowrap; overflow: hidden;
            transition: opacity 0.2s, padding 0.28s;
        }

        .sidebar.collapsed .sidebar-section-label {
            opacity: 0; padding: 0.55rem 0 0; height: 0; pointer-events: none;
        }

        /* ---- Nav items ---- */
        .sidebar-nav {
            flex: 1; overflow-y: auto; overflow-x: hidden;
            padding: 0.5rem 0 1rem;
            scrollbar-width: thin; scrollbar-color: rgba(74,222,128,0.2) transparent;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(74,222,128,0.2); border-radius: 2px;
        }

        .nav-item-link {
            display: flex; align-items: center; gap: 0.85rem;
            padding: 0.65rem 1.1rem; margin: 0.15rem 0.5rem;
            border-radius: 10px; color: rgba(255, 255, 255, 0.6);
            text-decoration: none; font-size: 0.875rem; font-weight: 500;
            transition: background 0.18s, color 0.18s;
            white-space: nowrap; overflow: hidden; position: relative;
        }

        .nav-item-link:hover { background: rgba(74, 222, 128, 0.1); color: #ffffff; }

        .nav-item-link.active {
            background: rgba(74, 222, 128, 0.15); color: #4ade80; font-weight: 600;
        }

        .nav-item-link.active::before {
            content: ''; position: absolute; left: 0; top: 20%;
            height: 60%; width: 3px; background: #4ade80; border-radius: 0 2px 2px 0;
        }

        .nav-icon { font-size: 1.1rem; flex-shrink: 0; width: 20px; text-align: center; }

        .nav-label { transition: opacity 0.2s, width 0.28s; overflow: hidden; white-space: nowrap; }

        .sidebar.collapsed .nav-label { opacity: 0; width: 0; }

        .sidebar.collapsed .nav-item-link {
            justify-content: center; padding: 0.65rem;
            margin: 0.15rem auto; width: 42px; border-radius: 10px;
        }

        /* ---- Logout di bawah ---- */
        .sidebar-footer {
            padding: 0.75rem 0.5rem;
            border-top: 1px solid rgba(74, 222, 128, 0.1); flex-shrink: 0;
        }

        .sidebar-footer form { margin: 0; }

        .btn-logout {
            display: flex; align-items: center; gap: 0.85rem;
            padding: 0.65rem 1.1rem; border-radius: 10px;
            color: rgba(255, 100, 100, 0.75); background: none; border: none;
            font-size: 0.875rem; font-weight: 500; cursor: pointer;
            width: 100%; text-align: left; transition: background 0.18s, color 0.18s;
            white-space: nowrap; overflow: hidden; font-family: 'Inter', sans-serif;
        }

        .btn-logout:hover { background: rgba(248, 113, 113, 0.12); color: #f87171; }

        .sidebar.collapsed .btn-logout {
            justify-content: center; padding: 0.65rem; width: 42px; margin: 0 auto;
        }
        .sidebar.collapsed .btn-logout .nav-label { opacity: 0; width: 0; }

        /* ============================================================
           TOGGLE BUTTON
        ============================================================ */
        .sidebar-toggle {
            position: fixed; top: 1rem; left: 200px; z-index: 1100;
            width: 28px; height: 28px; border-radius: 50%;
            background: #0a7c4a; border: 1px solid rgba(74, 222, 128, 0.35);
            color: #4ade80; display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: left 0.28s cubic-bezier(0.4, 0, 0.2, 1), background 0.18s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.35);
        }

        .sidebar-toggle:hover { background: #0d9e5e; }
        .sidebar-toggle.collapsed { left: 47px; }
        .sidebar-toggle i { font-size: 0.85rem; transition: transform 0.28s; }
        .sidebar-toggle.collapsed i { transform: rotate(180deg); }

        /* ============================================================
           MAIN CONTENT AREA
        ============================================================ */
        .main-wrapper {
            margin-left: 240px; flex: 1; min-width: 0;
            display: flex; flex-direction: column;
            transition: margin-left 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-wrapper.expanded { margin-left: 62px; }

        /* ---- Topbar ---- */
        .topbar {
            position: sticky; top: 0; z-index: 900;
            background: rgba(10, 46, 28, 0.92);
            backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(74, 222, 128, 0.1);
            padding: 0.75rem 1.75rem; display: flex;
            align-items: center; justify-content: space-between; gap: 1rem;
        }

        .topbar-title { font-size: 1.05rem; font-weight: 700; color: #ffffff; margin: 0; }

        .topbar-right { display: flex; align-items: center; gap: 1rem; }

        .topbar-user {
            display: flex; align-items: center; gap: 0.6rem;
            font-size: 0.85rem; color: rgba(255,255,255,0.72);
        }

        .topbar-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, #4ade80, #22c55e);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; font-weight: 700; color: #064e2e; flex-shrink: 0;
        }

        .topbar-badge {
            font-size: 0.68rem; font-weight: 600; color: #4ade80;
            background: rgba(74,222,128,0.12); border: 1px solid rgba(74,222,128,0.25);
            border-radius: 50px; padding: 0.15rem 0.55rem;
            letter-spacing: 0.04em; text-transform: uppercase;
        }

        /* ---- Content ---- */
        .main-content { flex: 1; padding: 1.75rem; }

        /* ============================================================
           BOTTOM BAR (MOBILE ONLY)
        ============================================================ */
        .bottom-bar {
            display: none; /* Hidden by default di desktop */
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 1000;
            background: linear-gradient(180deg, #0a2e1c 0%, #062015 100%);
            border-top: 1px solid rgba(74,222,128,0.12);
            padding: .5rem .5rem calc(.5rem + env(safe-area-inset-bottom));
        }

        .bottom-item {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; gap: .18rem;
            padding: .42rem .25rem; border-radius: 10px;
            color: rgba(255,255,255,.45); text-decoration: none;
            font-size: .6rem; font-weight: 600;
            transition: color .18s, background .18s;
            min-width: 0; border: none; background: none; cursor: pointer;
            font-family: 'Inter',sans-serif;
        }

        .bottom-item i { font-size: 1.2rem; }

        .bottom-item:hover,
        .bottom-item.active { color: #4ade80; background: rgba(74,222,128,.1); }

        /* ============================================================
           OVERLAY MOBILE
        ============================================================ */
        .sidebar-backdrop {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.55); z-index: 999;
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%); width: 240px;
                transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            }
            .sidebar.mobile-open { transform: translateX(0); }
            .sidebar-backdrop.show { display: block; }
            .sidebar-toggle { display: none; }
            .main-wrapper { margin-left: 0 !important; }
            
            /* Bottom bar tampil di mobile */
            .bottom-bar { display: flex; }
            
            /* Beri ruang agar konten tidak tertutup bottom bar */
            .main-content { padding: 1.25rem 1rem 5.5rem; }
            .topbar-menu-btn { display: flex !important; }
        }

        @media (min-width: 992px) {
            .topbar-menu-btn { display: none !important; }
            /* Memastikan bottom bar tetap hilang di desktop */
            .bottom-bar { display: none !important; }
        }

        .topbar-menu-btn {
            background: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.2);
            border-radius: 8px; padding: 0.4rem 0.55rem; color: #4ade80;
            cursor: pointer; display: none; align-items: center;
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Backdrop mobile -->
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- ================================================================
     SIDEBAR
================================================================ -->
<aside class="sidebar" id="sidebar">

    <a href="{{ route('murid.dashboard') }}" class="sidebar-brand">
        <img src="{{ asset('logo.jpeg') }}" alt="Logo SMKS Ibnu Sina" class="sidebar-brand-logo">
        <div class="sidebar-brand-text">
            <span class="sidebar-brand-title">SMKS Ibnu Sina</span>
            <span class="sidebar-brand-sub">Portal Murid</span>
        </div>
    </a>

    <nav class="sidebar-nav" aria-label="Menu murid">

        <div class="sidebar-section-label">Menu Utama</div>

        <a href="{{ route('murid.dashboard') }}"
           class="nav-item-link {{ request()->routeIs('murid.dashboard') ? 'active' : '' }}"
           title="Beranda">
            <i class="bi bi-grid-1x2-fill nav-icon"></i>
            <span class="nav-label">Beranda</span>
        </a>

        <a href="{{ route('murid.absen') }}"
           class="nav-item-link {{ request()->routeIs('murid.absen') ? 'active' : '' }}"
           title="Absensi">
            <i class="bi bi-clipboard2-check-fill nav-icon"></i>
            <span class="nav-label">Absensi</span>
        </a>

        <a href="{{ route('murid.riwayat_absen') }}"
           class="nav-item-link {{ request()->routeIs('murid.riwayat_absen') ? 'active' : '' }}"
           title="Riwayat Absen">
            <i class="bi bi-clock-history nav-icon"></i>
            <span class="nav-label">Riwayat Absen</span>
        </a>

        <div class="sidebar-section-label">Akun</div>

        <a href="#" class="nav-item-link" title="Profil">
            <i class="bi bi-person-circle nav-icon"></i>
            <span class="nav-label">Profil</span>
        </a>

    </nav>

    <!-- Logout -->
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}" id="sidebarLogoutForm">
            @csrf
            <button type="button" class="btn-logout" onclick="confirmLogout()" title="Log Out">
                <i class="bi bi-box-arrow-left nav-icon"></i>
                <span class="nav-label">Log Out</span>
            </button>
        </form>
    </div>

</aside>

<!-- Toggle button desktop -->
<button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
    <i class="bi bi-chevron-left"></i>
</button>

<!-- ================================================================
     MAIN WRAPPER
================================================================ -->
<div class="main-wrapper" id="mainWrapper">

    <!-- Topbar -->
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="topbar-menu-btn" id="mobileMenuBtn" aria-label="Buka menu">
                <i class="bi bi-list" style="font-size:1.15rem;"></i>
            </button>
            <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
        </div>

        <div class="topbar-right">
            <span class="topbar-badge">Murid</span>
            <div class="topbar-user">
                <div class="topbar-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'M', 0, 1)) }}
                </div>
                <span class="d-none d-sm-inline">{{ Auth::user()->name ?? 'Murid' }}</span>
            </div>
        </div>
    </header>

    <!-- Konten halaman -->
    <main class="main-content">
        @yield('content')
    </main>

</div>
<!-- akhir main-wrapper -->

<!-- ================================================================
     BOTTOM BAR (Mobile Saja)
================================================================ -->
<nav class="bottom-bar" aria-label="Navigasi bawah">
    <a href="{{ route('murid.dashboard') }}"
       class="bottom-item {{ request()->routeIs('murid.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Beranda</span>
    </a>
    <a href="{{ route('murid.absen') }}"
       class="bottom-item {{ request()->routeIs('murid.absen') ? 'active' : '' }}">
        <i class="bi bi-clipboard2-check-fill"></i>
        <span>Absensi</span>
    </a>
    <a href="{{ route('murid.riwayat_absen') }}"
       class="bottom-item {{ request()->routeIs('murid.riwayat_absen') ? 'active' : '' }}">
        <i class="bi bi-clock-history"></i>
        <span>Riwayat</span>
    </a>
    <a href="#" class="bottom-item">
        <i class="bi bi-person-circle"></i>
        <span>Profil</span>
    </a>
</nav>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
(function () {
    'use strict';

    /* ----------------------------------------------------------------
       SIDEBAR TOGGLE (Desktop)
    ---------------------------------------------------------------- */
    var sidebar  = document.getElementById('sidebar');
    var toggle   = document.getElementById('sidebarToggle');
    var wrapper  = document.getElementById('mainWrapper');
    var STORE_KEY = 'sikib_murid_sidebar_collapsed';

    if (localStorage.getItem(STORE_KEY) === '1') {
        sidebar.classList.add('collapsed');
        if(toggle) toggle.classList.add('collapsed');
        wrapper.classList.add('expanded');
    }

    if(toggle) {
        toggle.addEventListener('click', function () {
            var isCollapsed = sidebar.classList.toggle('collapsed');
            toggle.classList.toggle('collapsed', isCollapsed);
            wrapper.classList.toggle('expanded', isCollapsed);
            localStorage.setItem(STORE_KEY, isCollapsed ? '1' : '0');
        });
    }

    /* ----------------------------------------------------------------
       SIDEBAR MOBILE
    ---------------------------------------------------------------- */
    var mobileBtn = document.getElementById('mobileMenuBtn');
    var backdrop  = document.getElementById('sidebarBackdrop');

    function openMobileSidebar() {
        sidebar.classList.add('mobile-open');
        backdrop.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileSidebar() {
        sidebar.classList.remove('mobile-open');
        backdrop.classList.remove('show');
        document.body.style.overflow = '';
    }

    if (mobileBtn) mobileBtn.addEventListener('click', openMobileSidebar);
    if (backdrop) backdrop.addEventListener('click', closeMobileSidebar);

    /* ----------------------------------------------------------------
       LOGOUT KONFIRMASI
    ---------------------------------------------------------------- */
    window.confirmLogout = function () {
        Swal.fire({
            title: 'Keluar dari sistem?',
            text: 'Sesi Anda akan diakhiri.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, keluar',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#22c55e',
            cancelButtonColor: 'rgba(255,255,255,0.1)',
            background: '#0a2e1c',
            color: '#ffffff',
        }).then(function (result) {
            if (result.isConfirmed) {
                document.getElementById('sidebarLogoutForm').submit();
            }
        });
    };

}());
</script>

@stack('scripts')
</body>
</html>