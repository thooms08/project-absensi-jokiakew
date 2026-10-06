<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Panel Admin SIKIB - SMKS Ibnu Sina">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin SIKIB</title>

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
           RESET & BASE
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

        .sidebar.collapsed {
            width: 62px;
        }

        /* ---- Brand sidebar ---- */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 1.25rem 1rem 1rem;
            border-bottom: 1px solid rgba(74, 222, 128, 0.1);
            text-decoration: none;
            flex-shrink: 0;
            overflow: hidden;
            min-width: 0;
        }

        .sidebar-brand-logo {
            width: 36px;
            height: 36px;
            object-fit: contain;
            border-radius: 8px;
            flex-shrink: 0;
        }

        .sidebar-brand-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
            white-space: nowrap;
        }

        .sidebar-brand-title {
            font-size: 0.9rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.04em;
            line-height: 1.2;
        }

        .sidebar-brand-sub {
            font-size: 0.65rem;
            color: #4ade80;
            font-weight: 500;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        /* ---- Label section ---- */
        .sidebar-section-label {
            font-size: 0.62rem;
            font-weight: 700;
            color: rgba(74, 222, 128, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 1.1rem 1.1rem 0.35rem;
            white-space: nowrap;
            overflow: hidden;
            transition: opacity 0.2s, padding 0.28s;
        }

        .sidebar.collapsed .sidebar-section-label {
            opacity: 0;
            padding: 0.55rem 0 0;
            height: 0;
            pointer-events: none;
        }

        /* ---- Nav items ---- */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0.5rem 0 1rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(74,222,128,0.2) transparent;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(74,222,128,0.2);
            border-radius: 2px;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.65rem 1.1rem;
            margin: 0.15rem 0.5rem;
            border-radius: 10px;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background 0.18s, color 0.18s;
            white-space: nowrap;
            overflow: hidden;
            position: relative;
        }

        .nav-item-link:hover {
            background: rgba(74, 222, 128, 0.1);
            color: #ffffff;
        }

        .nav-item-link.active {
            background: rgba(74, 222, 128, 0.15);
            color: #4ade80;
            font-weight: 600;
        }

        .nav-item-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            height: 60%;
            width: 3px;
            background: #4ade80;
            border-radius: 0 2px 2px 0;
        }

        .nav-icon {
            font-size: 1.1rem;
            flex-shrink: 0;
            width: 20px;
            text-align: center;
        }

        .nav-label {
            transition: opacity 0.2s, width 0.28s;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar.collapsed .nav-label {
            opacity: 0;
            width: 0;
        }

        /* Tooltip saat collapsed */
        .sidebar.collapsed .nav-item-link {
            justify-content: center;
            padding: 0.65rem;
            margin: 0.15rem auto;
            width: 42px;
            border-radius: 10px;
        }

        /* ---- Logout di bawah ---- */
        .sidebar-footer {
            padding: 0.75rem 0.5rem;
            border-top: 1px solid rgba(74, 222, 128, 0.1);
            flex-shrink: 0;
        }

        .sidebar-footer form {
            margin: 0;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.65rem 1.1rem;
            border-radius: 10px;
            color: rgba(255, 100, 100, 0.75);
            background: none;
            border: none;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            text-align: left;
            transition: background 0.18s, color 0.18s;
            white-space: nowrap;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
        }

        .btn-logout:hover {
            background: rgba(248, 113, 113, 0.12);
            color: #f87171;
        }

        .sidebar.collapsed .btn-logout {
            justify-content: center;
            padding: 0.65rem;
            width: 42px;
            margin: 0 auto;
        }

        .sidebar.collapsed .btn-logout .nav-label {
            opacity: 0;
            width: 0;
        }

        /* ============================================================
           TOGGLE BUTTON
        ============================================================ */
        .sidebar-toggle {
            position: fixed;
            top: 1rem;
            left: 200px;
            z-index: 1100;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #0a7c4a;
            border: 1px solid rgba(74, 222, 128, 0.35);
            color: #4ade80;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: left 0.28s cubic-bezier(0.4, 0, 0.2, 1), background 0.18s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.35);
        }

        .sidebar-toggle:hover {
            background: #0d9e5e;
        }

        .sidebar-toggle.collapsed {
            left: 47px;
        }

        .sidebar-toggle i {
            font-size: 0.85rem;
            transition: transform 0.28s;
        }

        .sidebar-toggle.collapsed i {
            transform: rotate(180deg);
        }

        /* ============================================================
           MAIN CONTENT AREA
        ============================================================ */
        .main-wrapper {
            margin-left: 240px;
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .main-wrapper.expanded {
            margin-left: 62px;
        }

        /* ---- Topbar ---- */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 900;
            background: rgba(10, 46, 28, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(74, 222, 128, 0.1);
            padding: 0.75rem 1.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .topbar-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* ---- Notifikasi lonceng ---- */
        .notif-btn-wrap {
            position: relative;
        }

        .notif-btn {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(74,222,128,0.08);
            border: 1px solid rgba(74,222,128,0.18);
            color: rgba(255,255,255,0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1rem;
            transition: background 0.18s, color 0.18s;
            position: relative;
            flex-shrink: 0;
        }

        .notif-btn:hover {
            background: rgba(74,222,128,0.16);
            color: #4ade80;
        }

        .notif-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 17px;
            height: 17px;
            background: #f87171;
            border-radius: 50px;
            font-size: 0.58rem;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 3px;
            border: 2px solid #0a2e1c;
            line-height: 1;
        }

        .notif-badge.hidden { display: none; }

        @keyframes notifRing {
            0%   { transform: rotate(0deg); }
            15%  { transform: rotate(18deg); }
            30%  { transform: rotate(-15deg); }
            45%  { transform: rotate(12deg); }
            60%  { transform: rotate(-8deg); }
            75%  { transform: rotate(5deg); }
            100% { transform: rotate(0deg); }
        }

        .notif-ring i { animation: notifRing 0.8s ease; }

        /* Dropdown notifikasi */
        .notif-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 320px;
            background: #0a2e1c;
            border: 1px solid rgba(74,222,128,0.16);
            border-radius: 14px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.45);
            z-index: 1200;
            overflow: hidden;
            display: none;
            animation: dropIn 0.18s ease;
        }

        @keyframes dropIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .notif-dropdown.open { display: block; }

        .notif-dropdown-header {
            padding: 0.8rem 1rem 0.65rem;
            font-size: 0.8rem;
            font-weight: 700;
            color: #fff;
            border-bottom: 1px solid rgba(74,222,128,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .notif-dropdown-header span {
            font-size: 0.68rem;
            color: rgba(74,222,128,0.6);
            font-weight: 600;
        }

        .notif-list {
            max-height: 320px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(74,222,128,0.2) transparent;
        }

        .notif-list::-webkit-scrollbar { width: 4px; }
        .notif-list::-webkit-scrollbar-thumb { background: rgba(74,222,128,0.2); border-radius: 2px; }

        .notif-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            transition: background 0.15s;
        }

        .notif-item:last-child { border-bottom: none; }
        .notif-item:hover { background: rgba(74,222,128,0.04); }

        .notif-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg,#0a7c4a,#4ade80);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.78rem;
            font-weight: 700;
            color: #064e2e;
            flex-shrink: 0;
        }

        .notif-body {
            flex: 1;
            min-width: 0;
        }

        .notif-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notif-meta {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.38);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Label role (Guru / Murid) di item notif */
        .notif-role-label {
            display: inline-flex;
            align-items: center;
            gap: 0.2rem;
            font-size: 0.62rem;
            font-weight: 700;
            padding: 0.1rem 0.45rem;
            border-radius: 50px;
            margin-top: 0.18rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .notif-role-guru {
            background: rgba(74,222,128,0.12);
            color: #4ade80;
            border: 1px solid rgba(74,222,128,0.25);
        }

        .notif-role-murid {
            background: rgba(34,211,238,0.1);
            color: #22d3ee;
            border: 1px solid rgba(34,211,238,0.25);
        }

        .notif-actions {
            display: flex;
            gap: 0.35rem;
            flex-shrink: 0;
        }

        .notif-action-btn {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.78rem;
            transition: background 0.15s, transform 0.15s;
        }

        .notif-action-btn:hover { transform: scale(1.12); }

        .btn-approve {
            background: rgba(74,222,128,0.15);
            color: #4ade80;
            border: 1px solid rgba(74,222,128,0.25);
        }

        .btn-approve:hover { background: rgba(74,222,128,0.28); }

        .btn-reject {
            background: rgba(248,113,113,0.12);
            color: #f87171;
            border: 1px solid rgba(248,113,113,0.22);
        }

        .btn-reject:hover { background: rgba(248,113,113,0.25); }

        .notif-empty {
            text-align: center;
            padding: 1.5rem 1rem;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.28);
        }

        .notif-loading {
            text-align: center;
            padding: 1.25rem;
            font-size: 0.78rem;
            color: rgba(255,255,255,0.3);
        }

        /* mobile: dropdown lebih kecil */
        @media (max-width: 479.98px) {
            .notif-dropdown { width: 280px; right: -60px; }
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.72);
        }

        .topbar-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4ade80, #22c55e);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 700;
            color: #064e2e;
            flex-shrink: 0;
        }

        .topbar-badge {
            font-size: 0.68rem;
            font-weight: 600;
            color: #4ade80;
            background: rgba(74,222,128,0.12);
            border: 1px solid rgba(74,222,128,0.25);
            border-radius: 50px;
            padding: 0.15rem 0.55rem;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        /* ---- Content ---- */
        .main-content {
            flex: 1;
            padding: 1.75rem;
        }

        /* ============================================================
           OVERLAY MOBILE
        ============================================================ */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 999;
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                width: 240px;
                transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .sidebar-backdrop.show {
                display: block;
            }

            .sidebar-toggle {
                display: none;
            }

            .main-wrapper {
                margin-left: 0 !important;
            }

            .topbar-menu-btn {
                display: flex !important;
            }
        }

        @media (min-width: 992px) {
            .topbar-menu-btn {
                display: none !important;
            }
        }

        .topbar-menu-btn {
            background: rgba(74,222,128,0.1);
            border: 1px solid rgba(74,222,128,0.2);
            border-radius: 8px;
            padding: 0.4rem 0.55rem;
            color: #4ade80;
            cursor: pointer;
            display: none;
            align-items: center;
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

    <!-- Brand -->
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
        <img src="{{ asset('logo.png') }}" alt="Logo SMKS Ibnu Sina" class="sidebar-brand-logo">
        <div class="sidebar-brand-text">
            <span class="sidebar-brand-title">SMKS Ibnu Sina</span>
            <span class="sidebar-brand-sub">Panel Admin</span>
        </div>
    </a>

    <!-- Nav -->
    <nav class="sidebar-nav" aria-label="Menu admin">

        <div class="sidebar-section-label">Menu Utama</div>

        <a href="{{ route('admin.dashboard') }}"
           class="nav-item-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
           title="Beranda">
            <i class="bi bi-grid-1x2-fill nav-icon"></i>
            <span class="nav-label">Beranda</span>
        </a>

        <div class="sidebar-section-label">Data</div>

        <a href="{{ route('admin.data_guru') }}"
           class="nav-item-link {{ request()->routeIs('admin.data_guru') ? 'active' : '' }}"
           title="Data Guru">
            <i class="bi bi-person-lines-fill nav-icon"></i>
            <span class="nav-label">Data Guru</span>
        </a>

        <a href="{{ route('admin.data_murid') }}"
           class="nav-item-link {{ request()->routeIs('admin.data_murid') ? 'active' : '' }}"
           title="Data Murid">
            <i class="bi bi-people-fill nav-icon"></i>
            <span class="nav-label">Data Murid</span>
        </a>

        <div class="sidebar-section-label">Absensi</div>

        <a href="{{ route('admin.absen_guru') }}"
           class="nav-item-link {{ request()->routeIs('admin.absen_guru') ? 'active' : '' }}"
           title="Absensi Guru">
            <i class="bi bi-clipboard2-check-fill nav-icon"></i>
            <span class="nav-label">Absensi Guru</span>
        </a>

        <a href="{{ route('admin.absen_murid') }}"
           class="nav-item-link {{ request()->routeIs('admin.absen_murid') ? 'active' : '' }}"
           title="Absensi Murid">
            <i class="bi bi-clipboard2-pulse-fill nav-icon"></i>
            <span class="nav-label">Absensi Murid</span>
        </a>

        <div class="sidebar-section-label">Akademik</div>

        <a href="{{ route('admin.mapel') }}"
           class="nav-item-link {{ request()->routeIs('admin.mapel') ? 'active' : '' }}"
           title="Mata Pelajaran">
            <i class="bi bi-book-fill nav-icon"></i>
            <span class="nav-label">Mata Pelajaran</span>
        </a>

        <a href="{{ route('admin.kelas') }}"
           class="nav-item-link {{ request()->routeIs('admin.kelas', 'admin.kelas.detail') ? 'active' : '' }}"
           title="Kelas">
            <i class="bi bi-door-open-fill nav-icon"></i>
            <span class="nav-label">Kelas</span>
        </a>

        <a href="{{ route('admin.jadwal_mengajar') }}"
           class="nav-item-link {{ request()->routeIs('admin.jadwal_mengajar') ? 'active' : '' }}"
           title="Jadwal Mengajar">
            <i class="bi bi-calendar2-week-fill nav-icon"></i>
            <span class="nav-label">Jadwal Mengajar</span>
        </a>

        <div class="sidebar-section-label">Akun</div>

        <a href="{{ route('admin.profile') }}"
           class="nav-item-link {{ request()->routeIs('admin.profile') ? 'active' : '' }}"
           title="Profil">
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
            <!-- Hamburger mobile -->
            <button class="topbar-menu-btn" id="mobileMenuBtn" aria-label="Buka menu">
                <i class="bi bi-list" style="font-size:1.15rem;"></i>
            </button>
            <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
        </div>

        <div class="topbar-right">
            <span class="topbar-badge">Admin</span>

            <!-- Lonceng notifikasi (guru + murid) -->
            <div class="notif-btn-wrap" id="notifBtnWrap">
                <button class="notif-btn" id="notifBtn"
                        aria-label="Notifikasi pendaftaran" title="Pendaftaran Guru dan Murid">
                    <i class="bi bi-bell-fill"></i>
                    <span class="notif-badge hidden" id="notifBadge">0</span>
                </button>

                <!-- Dropdown gabungan -->
                <div class="notif-dropdown" id="notifDropdown"
                     role="dialog" aria-label="Notifikasi pendaftaran">
                    <div class="notif-dropdown-header">
                        Pengajuan Pendaftaran
                        <span id="notifCountLabel">0 pending</span>
                    </div>
                    <div class="notif-list" id="notifList">
                        <div class="notif-loading">Memuat...</div>
                    </div>
                </div>
            </div>

            <div class="topbar-user">
                <div class="topbar-avatar">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <span class="d-none d-sm-inline">{{ Auth::user()->name ?? 'Admin' }}</span>
            </div>
        </div>
    </header>

    <!-- Konten halaman -->
    <main class="main-content">
        @yield('content')
    </main>

</div>
<!-- akhir main-wrapper -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
(function () {
    'use strict';

    /* ----------------------------------------------------------------
       SIDEBAR TOGGLE  (desktop)
    ---------------------------------------------------------------- */
    var sidebar  = document.getElementById('sidebar');
    var toggle   = document.getElementById('sidebarToggle');
    var wrapper  = document.getElementById('mainWrapper');
    var STORE_KEY = 'sikib_sidebar_collapsed';

    /* Restore state dari localStorage */
    if (localStorage.getItem(STORE_KEY) === '1') {
        sidebar.classList.add('collapsed');
        toggle.classList.add('collapsed');
        wrapper.classList.add('expanded');
    }

    toggle.addEventListener('click', function () {
        var isCollapsed = sidebar.classList.toggle('collapsed');
        toggle.classList.toggle('collapsed', isCollapsed);
        wrapper.classList.toggle('expanded', isCollapsed);
        localStorage.setItem(STORE_KEY, isCollapsed ? '1' : '0');
    });

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

    if (mobileBtn) {
        mobileBtn.addEventListener('click', openMobileSidebar);
    }

    backdrop.addEventListener('click', closeMobileSidebar);

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

<script>
/* ================================================================
   NOTIFIKASI LONCENG GABUNGAN  –  Pendaftaran Guru + Murid
================================================================ */
(function () {
    'use strict';

    var CSRF       = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
    var btn        = document.getElementById('notifBtn');
    var wrap       = document.getElementById('notifBtnWrap');
    var dropdown   = document.getElementById('notifDropdown');
    var badge      = document.getElementById('notifBadge');
    var listEl     = document.getElementById('notifList');
    var countLabel = document.getElementById('notifCountLabel');
    var isOpen     = false;

    /* Simpan jumlah terakhir per role agar suara hanya bunyi saat ada tambahan */
    var STORE_GURU  = 'sikib_notif_guru_count';
    var STORE_MURID = 'sikib_notif_murid_count';
    var STORE_ABSEN = 'sikib_notif_absen_count';
    var lastGuru    = parseInt(sessionStorage.getItem(STORE_GURU)  || '0', 10);
    var lastMurid   = parseInt(sessionStorage.getItem(STORE_MURID) || '0', 10);
    var lastAbsen   = parseInt(sessionStorage.getItem(STORE_ABSEN) || '0', 10);

    if (!btn) return;

    var audio = new Audio('{{ asset("notif_admin.mp3") }}');
    audio.volume = 0.65;

    function playNotif() { audio.currentTime = 0; audio.play().catch(function(){}); }

    /* ---- Toggle dropdown ---- */
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        isOpen = !isOpen;
        dropdown.classList.toggle('open', isOpen);
        if (isOpen) fetchList();
    });

    document.addEventListener('click', function (e) {
        if (!wrap.contains(e.target)) {
            isOpen = false;
            dropdown.classList.remove('open');
        }
    });

    /* ---- Fetch jumlah badge gabungan (polling 10 detik) ---- */
    function fetchCount() {
        Promise.all([
            fetch('{{ route("admin.notif.count") }}',             { headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(function(r){ return r.json(); }),
            fetch('{{ route("admin.notif_murid.count") }}',       { headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(function(r){ return r.json(); }),
            fetch('{{ route("admin.absen_guru.notif_count") }}',  { headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(function(r){ return r.json(); })
        ])
        .then(function (results) {
            var cGuru   = parseInt(results[0].count, 10) || 0;
            var cMurid  = parseInt(results[1].count, 10) || 0;
            var cAbsen  = parseInt(results[2].count, 10) || 0;
            var total   = cGuru + cMurid + cAbsen;

            /* Suara hanya jika ada pertambahan di salah satu sumber */
            if (cGuru > lastGuru || cMurid > lastMurid || cAbsen > lastAbsen) {
                playNotif();
                btn.classList.add('notif-ring');
                setTimeout(function () { btn.classList.remove('notif-ring'); }, 800);
                if (isOpen) fetchList();
            }

            lastGuru  = cGuru;  sessionStorage.setItem(STORE_GURU,  cGuru);
            lastMurid = cMurid; sessionStorage.setItem(STORE_MURID, cMurid);
            lastAbsen = cAbsen; sessionStorage.setItem(STORE_ABSEN, cAbsen);

            if (total > 0) {
                badge.textContent = total > 99 ? '99+' : total;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        })
        .catch(function(){});
    }

    /* ---- Fetch list gabungan ---- */
    function fetchList() {
        listEl.innerHTML = '<div class="notif-loading">Memuat...</div>';

        Promise.all([
            fetch('{{ route("admin.notif.list") }}',              { headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(function(r){ return r.json(); }),
            fetch('{{ route("admin.notif_murid.list") }}',        { headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(function(r){ return r.json(); }),
            fetch('{{ route("admin.absen_guru.notif_list") }}',   { headers: { 'X-Requested-With': 'XMLHttpRequest' } }).then(function(r){ return r.json(); })
        ])
        .then(function (results) {
            /* Tandai setiap item dengan role-nya */
            var guruItems  = (results[0].list || []).map(function(i){ return Object.assign({}, i, { _type: 'reg_guru'  }); });
            var muridItems = (results[1].list || []).map(function(i){ return Object.assign({}, i, { _type: 'reg_murid' }); });
            var absenItems = (results[2].list || []).map(function(i){ return Object.assign({}, i, { _type: 'absen'     }); });

            /* Gabung & urutkan berdasarkan id DESC (terbaru dahulu) */
            var all = guruItems.concat(muridItems).concat(absenItems).sort(function(a,b){ return b.id - a.id; });

            countLabel.textContent = all.length + ' pending';

            if (!all.length) {
                listEl.innerHTML = '<div class="notif-empty">'
                    + '<i class="bi bi-bell-slash" style="font-size:1.4rem;display:block;margin-bottom:0.4rem;opacity:0.3;"></i>'
                    + 'Tidak ada pengajuan baru.</div>';
                return;
            }

            var html = '';
            all.forEach(function (item) {
                var isGuru    = item._type === 'reg_guru';
                var isMurid   = item._type === 'reg_murid';
                var isAbsen   = item._type === 'absen';

                if (isAbsen) {
                    /* ---- Notif ABSEN GURU ---- */
                    var statusColor = item.status === 'hadir' ? '#4ade80' : '#fbbf24';
                    var statusIcon  = item.status === 'hadir' ? 'bi-check-circle-fill' : 'bi-envelope-fill';
                    var statusTxt   = item.status === 'hadir' ? 'Hadir' : 'Izin';

                    html += '<div class="notif-item" id="notif-item-absen-' + item.id + '">'
                        + '<div class="notif-avatar" style="background:linear-gradient(135deg,#0a5878,#22d3ee);color:#0a2040;">' + escHtml(item.avatar) + '</div>'
                        + '<div class="notif-body">'
                        +   '<div class="notif-name">' + escHtml(item.nama) + '</div>'
                        +   '<div class="notif-meta" style="color:rgba(255,255,255,0.38);">'
                        +     escHtml(item.kelas) + ' &bull; ' + escHtml(item.mapel)
                        +     ' &bull; ' + escHtml(item.jam_mulai)
                        +   '</div>'
                        +   '<div class="notif-meta" style="margin-top:0.1rem;color:rgba(255,255,255,0.28);">'
                        +     '<i class="bi bi-clock me-1" style="font-size:0.65rem;"></i>' + escHtml(item.waktu)
                        +   '</div>'
                        +   '<span class="notif-role-label" style="background:rgba(34,211,238,0.1);color:#22d3ee;border:1px solid rgba(34,211,238,0.25);">'
                        +     '<i class="bi ' + statusIcon + '" style="color:' + statusColor + ';"></i>' + statusTxt
                        +   '</span>'
                        + '</div>'
                        + '<div class="notif-actions" style="flex-direction:column;gap:0.3rem;">'
                        +   (item.status === 'hadir' && item.foto_url
                                ? '<a class="notif-action-btn btn-approve" href="' + escHtml(item.foto_url) + '" target="_blank" title="Lihat foto bukti"><i class="bi bi-image"></i></a>'
                                : '')
                        +   (item.status === 'izin' && item.alasan
                                ? '<button class="notif-action-btn btn-approve" onclick="lihatAlasanIzin(' + item.id + ',\'' + escAttrInline(item.nama) + '\',\'' + escAttrInline(item.alasan) + '\')" title="Lihat alasan izin"><i class="bi bi-chat-text"></i></button>'
                                : '')
                        +   '<button class="notif-action-btn" onclick="konfirmasiAbsen(' + item.id + ')" title="Konfirmasi &amp; hapus notifikasi" style="background:rgba(74,222,128,0.1);color:#4ade80;border:1px solid rgba(74,222,128,0.22);"><i class="bi bi-check2-all"></i></button>'
                        + '</div>'
                        + '</div>';
                } else {
                    /* ---- Notif PENDAFTARAN GURU/MURID ---- */
                    var roleClass = isGuru ? 'notif-role-guru' : 'notif-role-murid';
                    var roleIcon  = isGuru ? 'bi-person-lines-fill' : 'bi-people-fill';
                    var roleTxt   = isGuru ? 'Guru' : 'Murid';
                    var approveFn = isGuru ? 'notifApprove' : 'notifMuridApprove';
                    var rejectFn  = isGuru ? 'notifReject'  : 'notifMuridReject';
                    var itemId    = 'notif-item-' + (isGuru ? 'guru' : 'murid') + '-' + item.id;

                    html += '<div class="notif-item" id="' + itemId + '">'
                        + '<div class="notif-avatar">' + escHtml(item.avatar) + '</div>'
                        + '<div class="notif-body">'
                        +   '<div class="notif-name">' + escHtml(item.name) + '</div>'
                        +   '<div class="notif-meta">@' + escHtml(item.username) + ' &bull; ' + escHtml(item.created_at) + '</div>'
                        +   '<span class="notif-role-label ' + roleClass + '">'
                        +     '<i class="bi ' + roleIcon + '"></i>' + roleTxt
                        +   '</span>'
                        + '</div>'
                        + '<div class="notif-actions">'
                        +   '<button class="notif-action-btn btn-approve" onclick="' + approveFn + '(' + item.id + ')" title="Setujui"><i class="bi bi-check-lg"></i></button>'
                        +   '<button class="notif-action-btn btn-reject"  onclick="' + rejectFn  + '(' + item.id + ')" title="Tolak"><i class="bi bi-x-lg"></i></button>'
                        + '</div>'
                        + '</div>';
                }
            });
            listEl.innerHTML = html;
        })
        .catch(function () {
            listEl.innerHTML = '<div class="notif-empty">Gagal memuat notifikasi.</div>';
        });
    }

    /* ---- Hapus item dari list setelah aksi ---- */
    function removeItem(role, id) {
        var el = document.getElementById('notif-item-' + role + '-' + id);
        if (el) el.remove();
        var rem = listEl.querySelectorAll('.notif-item').length;
        if (!rem) {
            listEl.innerHTML = '<div class="notif-empty">'
                + '<i class="bi bi-bell-slash" style="font-size:1.4rem;display:block;margin-bottom:0.4rem;opacity:0.3;"></i>'
                + 'Tidak ada pengajuan baru.</div>';
        }
        countLabel.textContent = rem + ' pending';
    }

    /* ---- Lihat alasan izin guru ---- */
    window.lihatAlasanIzin = function (id, nama, alasan) {
        Swal.fire({
            icon: 'info',
            title: escHtml(nama) + ' — Izin',
            html: '<div style="text-align:left;background:rgba(255,255,255,0.05);border-radius:8px;padding:0.75rem 1rem;font-size:0.875rem;color:#e2f5ea;">'
                + '<i class="bi bi-chat-quote me-2" style="color:rgba(251,191,36,0.7);"></i>'
                + escHtml(alasan)
                + '</div>',
            confirmButtonText: 'Tutup',
            confirmButtonColor: '#22c55e',
            background: '#0a2e1c',
            color: '#fff',
        });
    };

    /* ---- Konfirmasi & hapus notif absen dari dropdown ---- */
    window.konfirmasiAbsen = function (id) {
        /* POST ke server terlebih dahulu agar dikonfirmasi_at terisi */
        fetch('{{ url("admin/absen-guru/notif") }}/' + id + '/konfirmasi', {
            method:  'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' }
        })
        .then(function () {
            /* Hapus dari DOM dengan animasi setelah server merespons */
            var el = document.getElementById('notif-item-absen-' + id);
            if (el) {
                el.style.transition = 'opacity 0.3s, transform 0.3s';
                el.style.opacity    = '0';
                el.style.transform  = 'translateX(20px)';
                setTimeout(function () {
                    el.remove();
                    var remaining = listEl.querySelectorAll('.notif-item').length;
                    if (!remaining) {
                        listEl.innerHTML = '<div class="notif-empty">'
                            + '<i class="bi bi-bell-slash" style="font-size:1.4rem;display:block;margin-bottom:0.4rem;opacity:0.3;"></i>'
                            + 'Tidak ada notifikasi baru.</div>';
                    }
                    countLabel.textContent = remaining + ' notifikasi';
                }, 300);
            }
            /* Update badge */
            var cur = parseInt(badge.textContent, 10) || 0;
            if (cur > 1) {
                badge.textContent = cur - 1;
            } else {
                badge.classList.add('hidden');
            }
            /* Reset lastAbsen agar polling berikutnya tidak memicu suara lagi */
            lastAbsen = Math.max(0, lastAbsen - 1);
            sessionStorage.setItem(STORE_ABSEN, lastAbsen);
        })
        .catch(function () {
            /* Tetap hapus dari DOM meski request gagal */
            var el = document.getElementById('notif-item-absen-' + id);
            if (el) el.remove();
        });
    };

    function escAttrInline(str) {
        return String(str || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
    }

    /* ---- Approve / Reject GURU ---- */
    window.notifApprove = function (id) {
        Swal.fire({
            icon: 'question', title: 'Setujui pendaftaran guru?',
            text: 'Guru ini akan diaktifkan sebagai anggota.',
            showCancelButton: true, confirmButtonText: 'Setujui', cancelButtonText: 'Batal',
            confirmButtonColor: '#22c55e', cancelButtonColor: 'rgba(255,255,255,0.1)',
            background: '#0a2e1c', color: '#fff',
        }).then(function (result) {
            if (!result.isConfirmed) return;
            fetch('{{ url("admin/notif") }}/' + id + '/approve', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' }
            }).then(function(r){ return r.json(); }).then(function(data) {
                if (data.success) {
                    removeItem('guru', id); fetchCount();
                    Swal.fire({ icon:'success', title:'Disetujui', text: data.message, timer:1800, showConfirmButton:false, background:'#0a2e1c', color:'#fff' });
                }
            });
        });
    };

    window.notifReject = function (id) {
        Swal.fire({
            icon: 'warning', title: 'Tolak pendaftaran guru?',
            text: 'Data guru dan akunnya akan dihapus permanen.',
            showCancelButton: true, confirmButtonText: 'Tolak', cancelButtonText: 'Batal',
            confirmButtonColor: '#f87171', cancelButtonColor: 'rgba(255,255,255,0.1)',
            background: '#0a2e1c', color: '#fff',
        }).then(function (result) {
            if (!result.isConfirmed) return;
            fetch('{{ url("admin/notif") }}/' + id + '/reject', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' }
            }).then(function(r){ return r.json(); }).then(function(data) {
                if (data.success) {
                    removeItem('guru', id); fetchCount();
                    Swal.fire({ icon:'success', title:'Ditolak', text: data.message, timer:1800, showConfirmButton:false, background:'#0a2e1c', color:'#fff' });
                }
            });
        });
    };

    /* ---- Approve / Reject MURID ---- */
    window.notifMuridApprove = function (id) {
        Swal.fire({
            icon: 'question', title: 'Setujui pendaftaran murid?',
            text: 'Murid ini akan diaktifkan sebagai anggota.',
            showCancelButton: true, confirmButtonText: 'Setujui', cancelButtonText: 'Batal',
            confirmButtonColor: '#22c55e', cancelButtonColor: 'rgba(255,255,255,0.1)',
            background: '#0a2e1c', color: '#fff',
        }).then(function (result) {
            if (!result.isConfirmed) return;
            fetch('{{ url("admin/notif-murid") }}/' + id + '/approve', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' }
            }).then(function(r){ return r.json(); }).then(function(data) {
                if (data.success) {
                    removeItem('murid', id); fetchCount();
                    Swal.fire({ icon:'success', title:'Disetujui', text: data.message, timer:1800, showConfirmButton:false, background:'#0a2e1c', color:'#fff' });
                }
            });
        });
    };

    window.notifMuridReject = function (id) {
        Swal.fire({
            icon: 'warning', title: 'Tolak pendaftaran murid?',
            text: 'Data murid dan akunnya akan dihapus permanen.',
            showCancelButton: true, confirmButtonText: 'Tolak', cancelButtonText: 'Batal',
            confirmButtonColor: '#f87171', cancelButtonColor: 'rgba(255,255,255,0.1)',
            background: '#0a2e1c', color: '#fff',
        }).then(function (result) {
            if (!result.isConfirmed) return;
            fetch('{{ url("admin/notif-murid") }}/' + id + '/reject', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' }
            }).then(function(r){ return r.json(); }).then(function(data) {
                if (data.success) {
                    removeItem('murid', id); fetchCount();
                    Swal.fire({ icon:'success', title:'Ditolak', text: data.message, timer:1800, showConfirmButton:false, background:'#0a2e1c', color:'#fff' });
                }
            });
        });
    };

    function escHtml(str) {
        return String(str)
            .replace(/&/g,'&amp;').replace(/</g,'&lt;')
            .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    /* ---- Poll setiap 10 detik ---- */
    fetchCount();
    setInterval(fetchCount, 10000);

}());
</script>

</body>
</html>
