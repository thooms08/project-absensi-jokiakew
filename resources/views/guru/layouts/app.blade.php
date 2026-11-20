<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Panel Guru SIKIB - SMKS Ibnu Sina">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Guru SIKIB</title>

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
        html { height: 100%; }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0; padding: 0;
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
            top: 0; left: 0;
            height: 100vh; width: 240px;
            background: linear-gradient(180deg, #0a2e1c 0%, #062015 100%);
            border-right: 1px solid rgba(74,222,128,0.12);
            display: flex; flex-direction: column;
            z-index: 1000;
            transition: width 0.28s cubic-bezier(0.4,0,0.2,1);
            overflow: hidden;
        }

        .sidebar.collapsed { width: 62px; }

        .sidebar-brand {
            display: flex; align-items: center; gap: 0.85rem;
            padding: 1.25rem 1rem 1rem;
            border-bottom: 1px solid rgba(74,222,128,0.1);
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
            font-size: 0.9rem; font-weight: 800;
            color: #ffffff; letter-spacing: 0.04em; line-height: 1.2;
        }

        .sidebar-brand-sub {
            font-size: 0.65rem; color: #4ade80;
            font-weight: 500; letter-spacing: 0.06em; text-transform: uppercase;
        }

        .sidebar-section-label {
            font-size: 0.62rem; font-weight: 700;
            color: rgba(74,222,128,0.5);
            text-transform: uppercase; letter-spacing: 0.1em;
            padding: 1.1rem 1.1rem 0.35rem;
            white-space: nowrap; overflow: hidden;
            transition: opacity 0.2s, padding 0.28s;
        }

        .sidebar.collapsed .sidebar-section-label {
            opacity: 0; padding: 0.55rem 0 0; height: 0; pointer-events: none;
        }

        .sidebar-nav {
            flex: 1; overflow-y: auto; overflow-x: hidden;
            padding: 0.5rem 0 1rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(74,222,128,0.2) transparent;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(74,222,128,0.2); border-radius: 2px; }

        .nav-item-link {
            display: flex; align-items: center; gap: 0.85rem;
            padding: 0.65rem 1.1rem; margin: 0.15rem 0.5rem;
            border-radius: 10px; color: rgba(255,255,255,0.6);
            text-decoration: none; font-size: 0.875rem; font-weight: 500;
            transition: background 0.18s, color 0.18s;
            white-space: nowrap; overflow: hidden; position: relative;
        }

        .nav-item-link:hover { background: rgba(74,222,128,0.1); color: #ffffff; }

        .nav-item-link.active {
            background: rgba(74,222,128,0.15); color: #4ade80; font-weight: 600;
        }

        .nav-item-link.active::before {
            content: ''; position: absolute;
            left: 0; top: 20%; height: 60%; width: 3px;
            background: #4ade80; border-radius: 0 2px 2px 0;
        }

        .nav-icon { font-size: 1.1rem; flex-shrink: 0; width: 20px; text-align: center; }

        .nav-label { transition: opacity 0.2s, width 0.28s; overflow: hidden; white-space: nowrap; }
        .sidebar.collapsed .nav-label { opacity: 0; width: 0; }

        .sidebar.collapsed .nav-item-link {
            justify-content: center; padding: 0.65rem;
            margin: 0.15rem auto; width: 42px; border-radius: 10px;
        }

        .sidebar-footer {
            padding: 0.75rem 0.5rem;
            border-top: 1px solid rgba(74,222,128,0.1);
            flex-shrink: 0;
        }

        .sidebar-footer form { margin: 0; }

        .btn-logout {
            display: flex; align-items: center; gap: 0.85rem;
            padding: 0.65rem 1.1rem; border-radius: 10px;
            color: rgba(255,100,100,0.75); background: none; border: none;
            font-size: 0.875rem; font-weight: 500; cursor: pointer;
            width: 100%; text-align: left;
            transition: background 0.18s, color 0.18s;
            white-space: nowrap; overflow: hidden; font-family: 'Inter', sans-serif;
        }

        .btn-logout:hover { background: rgba(248,113,113,0.12); color: #f87171; }

        .sidebar.collapsed .btn-logout {
            justify-content: center; padding: 0.65rem; width: 42px; margin: 0 auto;
        }

        .sidebar.collapsed .btn-logout .nav-label { opacity: 0; width: 0; }

        /* ============================================================
           TOGGLE BUTTON (Desktop)
        ============================================================ */
        .sidebar-toggle {
            position: fixed; top: 50%; left: 226px; transform: translateY(-50%);
            z-index: 1100;
            width: 28px; height: 28px; border-radius: 50%;
            background: #0a7c4a; border: 1px solid rgba(74,222,128,0.35);
            color: #4ade80; display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: left 0.28s cubic-bezier(0.4,0,0.2,1), background 0.18s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.35);
        }

        .sidebar-toggle:hover { background: #0d9e5e; }
        .sidebar-toggle.collapsed { left: 48px; }
        .sidebar-toggle i { font-size: 0.85rem; transition: transform 0.28s; }
        .sidebar-toggle.collapsed i { transform: rotate(180deg); }

        /* ============================================================
           MAIN WRAPPER
        ============================================================ */
        .main-wrapper {
            margin-left: 240px; flex: 1; min-width: 0;
            display: flex; flex-direction: column;
            transition: margin-left 0.28s cubic-bezier(0.4,0,0.2,1);
        }

        .main-wrapper.expanded { margin-left: 62px; }

        /* ============================================================
           TOPBAR
        ============================================================ */
        .topbar {
            position: sticky; top: 0; z-index: 900;
            background: rgba(10,46,28,0.92);
            backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(74,222,128,0.1);
            padding: 0.75rem 1.75rem;
            display: flex; align-items: center; justify-content: space-between; gap: 1rem;
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
            overflow: hidden;
        }

        .topbar-avatar img {
            width: 100%; height: 100%; object-fit: cover;
        }

        .topbar-badge {
            font-size: 0.68rem; font-weight: 600; color: #4ade80;
            background: rgba(74,222,128,0.12); border: 1px solid rgba(74,222,128,0.25);
            border-radius: 50px; padding: 0.15rem 0.55rem;
            letter-spacing: 0.04em; text-transform: uppercase;
        }

        .main-content { flex: 1; padding: 1.75rem; }

        /* ============================================================
           AVATAR DROPDOWN (Profile & Logout)
        ============================================================ */
        .avatar-dropdown-wrap {
            position: relative;
        }

        .avatar-dropdown-trigger {
            display: flex; align-items: center; gap: 0.6rem;
            font-size: 0.85rem; color: rgba(255,255,255,0.72);
            cursor: pointer; background: none; border: none;
            padding: 0; font-family: 'Inter', sans-serif;
            border-radius: 50px;
            transition: color 0.18s;
        }

        .avatar-dropdown-trigger:hover { color: #ffffff; }

        .avatar-dropdown-trigger .avatar-caret {
            font-size: 0.7rem; color: rgba(255,255,255,0.4);
            transition: transform 0.2s;
        }

        .avatar-dropdown-trigger.open .avatar-caret {
            transform: rotate(180deg);
        }

        .avatar-dropdown-menu {
            display: none;
            position: absolute; top: calc(100% + 10px); right: 0;
            min-width: 180px;
            background: #0a2e1c;
            border: 1px solid rgba(74,222,128,0.16);
            border-radius: 12px;
            box-shadow: 0 12px 40px rgba(0,0,0,0.5);
            z-index: 1200;
            overflow: hidden;
            animation: dropFadeIn 0.18s ease;
        }

        @keyframes dropFadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .avatar-dropdown-menu.show { display: block; }

        .avatar-drop-header {
            padding: 0.9rem 1rem 0.7rem;
            border-bottom: 1px solid rgba(74,222,128,0.1);
        }

        .avatar-drop-name {
            font-size: 0.82rem; font-weight: 700; color: #ffffff;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }

        .avatar-drop-role {
            font-size: 0.68rem; color: rgba(74,222,128,0.65);
            font-weight: 500; text-transform: uppercase; letter-spacing: 0.05em;
        }

        .avatar-drop-item {
            display: flex; align-items: center; gap: 0.65rem;
            padding: 0.6rem 1rem; color: rgba(255,255,255,0.7);
            text-decoration: none; font-size: 0.82rem; font-weight: 500;
            transition: background 0.15s, color 0.15s;
            cursor: pointer; background: none; border: none;
            width: 100%; text-align: left; font-family: 'Inter', sans-serif;
        }

        .avatar-drop-item:hover { background: rgba(74,222,128,0.1); color: #ffffff; }

        .avatar-drop-item.danger { color: rgba(255,100,100,0.75); }
        .avatar-drop-item.danger:hover { background: rgba(248,113,113,0.1); color: #f87171; }

        .avatar-drop-item i { font-size: 0.95rem; width: 16px; text-align: center; }

        .avatar-drop-divider {
            height: 1px; background: rgba(74,222,128,0.08); margin: 0.2rem 0;
        }

        /* ============================================================
           BOTTOM BAR (Mobile Only)
        ============================================================ */
        .bottom-bar {
            display: none;
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 1000;
            background: linear-gradient(180deg, #0a2e1c 0%, #062015 100%);
            border-top: 1px solid rgba(74,222,128,0.12);
            padding: 0.5rem 0.5rem calc(0.5rem + env(safe-area-inset-bottom));
        }

        .bottom-item {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; gap: 0.18rem;
            padding: 0.42rem 0.25rem; border-radius: 10px;
            color: rgba(255,255,255,0.45); text-decoration: none;
            font-size: 0.6rem; font-weight: 600;
            transition: color 0.18s, background 0.18s;
            min-width: 0; border: none; background: none; cursor: pointer;
            font-family: 'Inter', sans-serif;
        }

        .bottom-item i { font-size: 1.2rem; }

        .bottom-item:hover,
        .bottom-item.active { color: #4ade80; background: rgba(74,222,128,0.1); }

        /* ============================================================
           AVATAR DROPDOWN (Mobile — di dalam bottom bar)
        ============================================================ */
        .bottom-avatar-wrap {
            position: relative; flex: 1;
            display: flex; justify-content: center;
        }

        .bottom-avatar-menu {
            display: none;
            position: absolute; bottom: calc(100% + 10px); right: 0;
            min-width: 180px;
            background: #0a2e1c;
            border: 1px solid rgba(74,222,128,0.16);
            border-radius: 12px;
            box-shadow: 0 -8px 32px rgba(0,0,0,0.55);
            z-index: 1300;
            overflow: hidden;
            animation: dropFadeInUp 0.18s ease;
        }

        @keyframes dropFadeInUp {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .bottom-avatar-menu.show { display: block; }

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
                transition: transform 0.28s cubic-bezier(0.4,0,0.2,1);
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

            /* Sembunyikan avatar user desktop di topbar saat mobile */
            .topbar-user-desktop { display: none !important; }
        }

        @media (min-width: 992px) {
            .topbar-menu-btn { display: none !important; }
            /* Bottom bar tetap hilang di desktop */
            .bottom-bar { display: none !important; }
        }

        .topbar-menu-btn {
            background: rgba(74,222,128,0.1); border: 1px solid rgba(74,222,128,0.2);
            border-radius: 8px; padding: 0.4rem 0.55rem;
            color: #4ade80; cursor: pointer; display: none; align-items: center;
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="sidebar-backdrop" id="sidebarBackdrop"></div>

<!-- form logout (dipakai di sidebar footer & avatar dropdown) -->
<form method="POST" action="{{ route('logout') }}" id="guruLogoutForm" style="display:none;">
    @csrf
</form>

<!-- ================================================================
     SIDEBAR
================================================================ -->
<aside class="sidebar" id="sidebar">

    <a href="{{ route('guru.dashboard') }}" class="sidebar-brand">
        @php
            $logoPath = $profileSekolah->logo
                ? route('admin.profile.logo', ['filename' => basename($profileSekolah->logo)])
                : asset('logo.jpeg');
        @endphp
        <img src="{{ $logoPath }}" alt="Logo {{ $profileSekolah->nama_sekolah ?? 'Sekolah' }}" class="sidebar-brand-logo">
        <div class="sidebar-brand-text">
            <span class="sidebar-brand-title">{{ $profileSekolah->nama_sekolah ?? 'Nama Sekolah' }}</span>
            <span class="sidebar-brand-sub">Panel Guru</span>
        </div>
    </a>

    <nav class="sidebar-nav" aria-label="Menu guru">

        <div class="sidebar-section-label">Menu Utama</div>

        <a href="{{ route('guru.dashboard') }}"
           class="nav-item-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}"
           title="Beranda">
            <i class="bi bi-grid-1x2-fill nav-icon"></i>
            <span class="nav-label">Beranda</span>
        </a>

        <div class="sidebar-section-label">Absensi</div>

        <a href="{{ route('guru.absen_guru') }}"
           class="nav-item-link {{ request()->routeIs('guru.absen_guru') ? 'active' : '' }}"
           title="Absensi Saya">
            <i class="bi bi-clipboard2-check-fill nav-icon"></i>
            <span class="nav-label">Absensi Saya</span>
        </a>

        <a href="{{ route('guru.absen_murid') }}"
           class="nav-item-link {{ request()->routeIs('guru.absen_murid') ? 'active' : '' }}"
           title="Absensi Murid">
            <i class="bi bi-clipboard2-pulse-fill nav-icon"></i>
            <span class="nav-label">Absensi Murid</span>
        </a>

        <div class="sidebar-section-label">Akademik</div>

        <a href="{{ route('guru.jadwal_mengajar') }}"
           class="nav-item-link {{ request()->routeIs('guru.jadwal_mengajar') ? 'active' : '' }}"
           title="Jadwal Mengajar">
            <i class="bi bi-calendar2-week-fill nav-icon"></i>
            <span class="nav-label">Jadwal Mengajar</span>
        </a>

        <a href="{{ route('guru.data_kelas') }}"
           class="nav-item-link {{ request()->routeIs('guru.data_kelas', 'guru.data_kelas.detail') ? 'active' : '' }}"
           title="Data Kelas">
            <i class="bi bi-mortarboard-fill nav-icon"></i>
            <span class="nav-label">Data Kelas</span>
        </a>

        <a href="{{ route('guru.data_murid') }}"
           class="nav-item-link {{ request()->routeIs('guru.data_murid*') ? 'active' : '' }}"
           title="Data Murid">
            <i class="bi bi-people-fill nav-icon"></i>
            <span class="nav-label">Data Murid</span>
        </a>

        <div class="sidebar-section-label">Akun</div>

        <a href="{{ route('guru.profile') }}"
           class="nav-item-link {{ request()->routeIs('guru.profile') ? 'active' : '' }}"
           title="Profil">
            <i class="bi bi-person-circle nav-icon"></i>
            <span class="nav-label">Profil Saya</span>
        </a>

    </nav>

    <div class="sidebar-footer">
        <button type="button" class="btn-logout" onclick="confirmLogout()" title="Log Out">
            <i class="bi bi-box-arrow-left nav-icon"></i>
            <span class="nav-label">Log Out</span>
        </button>
    </div>

</aside>

<button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
    <i class="bi bi-chevron-left"></i>
</button>

<!-- ================================================================
     MAIN WRAPPER
================================================================ -->
<div class="main-wrapper" id="mainWrapper">

    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="topbar-menu-btn" id="mobileMenuBtn" aria-label="Buka menu">
                <i class="bi bi-list" style="font-size:1.15rem;"></i>
            </button>
            <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
        </div>

        <div class="topbar-right">
            <span class="topbar-badge">Guru</span>

            {{-- Avatar dropdown (desktop) --}}
            <div class="avatar-dropdown-wrap topbar-user-desktop" id="avatarDropWrap">
                <button class="avatar-dropdown-trigger" id="avatarDropTrigger" aria-haspopup="true" aria-expanded="false">
                    <div class="topbar-avatar" id="topbarAvatar">
                        @php
                            $guruImg = Auth::user()->image ?? null;
                            $guruFotoUrl = $guruImg
                                ? route('guru.profile.foto', ['filename' => basename($guruImg)])
                                : null;
                        @endphp
                        @if($guruFotoUrl)
                            <img src="{{ $guruFotoUrl }}" alt="Foto profil">
                        @else
                            {{ strtoupper(substr(Auth::user()->name ?? 'G', 0, 1)) }}
                        @endif
                    </div>
                    <span class="d-none d-sm-inline">{{ Auth::user()->name ?? 'Guru' }}</span>
                    <i class="bi bi-chevron-down avatar-caret"></i>
                </button>

                <div class="avatar-dropdown-menu" id="avatarDropMenu" role="menu">
                    <div class="avatar-drop-header">
                        <div class="avatar-drop-name">{{ Auth::user()->name ?? 'Guru' }}</div>
                        <div class="avatar-drop-role">{{ Auth::user()->role ?? 'Guru' }}</div>
                    </div>
                    <a href="{{ route('guru.profile') }}" class="avatar-drop-item" role="menuitem">
                        <i class="bi bi-person-circle"></i>
                        Profil Saya
                    </a>
                    <div class="avatar-drop-divider"></div>
                    <button type="button" class="avatar-drop-item danger" onclick="confirmLogout()" role="menuitem">
                        <i class="bi bi-box-arrow-left"></i>
                        Log Out
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        @yield('content')
    </main>

</div>
<!-- akhir main-wrapper -->

<!-- ================================================================
     BOTTOM BAR (Mobile Saja)
================================================================ -->
<nav class="bottom-bar" aria-label="Navigasi bawah">
    <a href="{{ route('guru.dashboard') }}"
       class="bottom-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Beranda</span>
    </a>
    <a href="{{ route('guru.absen_guru') }}"
       class="bottom-item {{ request()->routeIs('guru.absen_guru') ? 'active' : '' }}">
        <i class="bi bi-clipboard2-check-fill"></i>
        <span>Absensi</span>
    </a>
    <a href="{{ route('guru.absen_murid') }}"
       class="bottom-item {{ request()->routeIs('guru.absen_murid') ? 'active' : '' }}">
        <i class="bi bi-clipboard2-pulse-fill"></i>
        <span>Absen Murid</span>
    </a>
    <a href="{{ route('guru.jadwal_mengajar') }}"
       class="bottom-item {{ request()->routeIs('guru.jadwal_mengajar') ? 'active' : '' }}">
        <i class="bi bi-calendar2-week-fill"></i>
        <span>Jadwal</span>
    </a>

    {{-- Avatar + dropdown profile/logout --}}
    <div class="bottom-avatar-wrap" id="bottomAvatarWrap">
        <button class="bottom-item {{ request()->routeIs('guru.profile') ? 'active' : '' }}"
                id="bottomAvatarBtn" aria-haspopup="true" aria-expanded="false"
                style="font-family:'Inter',sans-serif;">
            @php
                $guruImgBottom = Auth::user()->image ?? null;
                $guruFotoUrlBottom = $guruImgBottom
                    ? route('guru.profile.foto', ['filename' => basename($guruImgBottom)])
                    : null;
            @endphp
            @if($guruFotoUrlBottom)
                <img src="{{ $guruFotoUrlBottom }}" alt="Avatar"
                     style="width:28px;height:28px;border-radius:50%;object-fit:cover;border:2px solid rgba(74,222,128,0.4);">
            @else
                <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#4ade80,#22c55e);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;color:#064e2e;border:2px solid rgba(74,222,128,0.4);">
                    {{ strtoupper(substr(Auth::user()->name ?? 'G', 0, 1)) }}
                </div>
            @endif
            <span>Akun</span>
        </button>

        <div class="bottom-avatar-menu" id="bottomAvatarMenu" role="menu">
            <div class="avatar-drop-header">
                <div class="avatar-drop-name">{{ Auth::user()->name ?? 'Guru' }}</div>
                <div class="avatar-drop-role">{{ Auth::user()->role ?? 'Guru' }}</div>
            </div>
            <a href="{{ route('guru.profile') }}" class="avatar-drop-item" role="menuitem">
                <i class="bi bi-person-circle"></i>
                Profil Saya
            </a>
            <div class="avatar-drop-divider"></div>
            <button type="button" class="avatar-drop-item danger" onclick="confirmLogout()" role="menuitem">
                <i class="bi bi-box-arrow-left"></i>
                Log Out
            </button>
        </div>
    </div>
</nav>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
(function () {
    'use strict';

    var sidebar  = document.getElementById('sidebar');
    var toggle   = document.getElementById('sidebarToggle');
    var wrapper  = document.getElementById('mainWrapper');
    var STORE_KEY = 'sikib_guru_sidebar_collapsed';

    /* Restore sidebar state */
    if (localStorage.getItem(STORE_KEY) === '1') {
        sidebar.classList.add('collapsed');
        if (toggle) toggle.classList.add('collapsed');
        wrapper.classList.add('expanded');
    }

    if (toggle) {
        toggle.addEventListener('click', function () {
            var collapsed = sidebar.classList.toggle('collapsed');
            toggle.classList.toggle('collapsed', collapsed);
            wrapper.classList.toggle('expanded', collapsed);
            localStorage.setItem(STORE_KEY, collapsed ? '1' : '0');
        });
    }

    /* ----------------------------------------------------------------
       SIDEBAR MOBILE
    ---------------------------------------------------------------- */
    var mobileBtn = document.getElementById('mobileMenuBtn');
    var backdrop  = document.getElementById('sidebarBackdrop');

    function openSidebar() {
        sidebar.classList.add('mobile-open');
        backdrop.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('mobile-open');
        backdrop.classList.remove('show');
        document.body.style.overflow = '';
    }

    if (mobileBtn) mobileBtn.addEventListener('click', openSidebar);
    if (backdrop)  backdrop.addEventListener('click', closeSidebar);

    /* ----------------------------------------------------------------
       AVATAR DROPDOWN — Desktop (topbar)
    ---------------------------------------------------------------- */
    var avatarWrap    = document.getElementById('avatarDropWrap');
    var avatarTrigger = document.getElementById('avatarDropTrigger');
    var avatarMenu    = document.getElementById('avatarDropMenu');

    function toggleDesktopMenu() {
        var open = avatarMenu.classList.toggle('show');
        avatarTrigger.classList.toggle('open', open);
        avatarTrigger.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    if (avatarTrigger) {
        avatarTrigger.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleDesktopMenu();
            /* tutup bottom dropdown jika terbuka */
            if (bottomMenu) bottomMenu.classList.remove('show');
        });
    }

    /* ----------------------------------------------------------------
       AVATAR DROPDOWN — Mobile (bottom bar)
    ---------------------------------------------------------------- */
    var bottomBtn  = document.getElementById('bottomAvatarBtn');
    var bottomMenu = document.getElementById('bottomAvatarMenu');

    if (bottomBtn) {
        bottomBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            var open = bottomMenu.classList.toggle('show');
            bottomBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
            /* tutup desktop dropdown jika terbuka */
            if (avatarMenu) avatarMenu.classList.remove('show');
            if (avatarTrigger) avatarTrigger.classList.remove('open');
        });
    }

    /* Klik di luar — tutup semua dropdown */
    document.addEventListener('click', function (e) {
        if (avatarWrap && !avatarWrap.contains(e.target)) {
            if (avatarMenu) avatarMenu.classList.remove('show');
            if (avatarTrigger) { avatarTrigger.classList.remove('open'); avatarTrigger.setAttribute('aria-expanded','false'); }
        }
        var bottomWrap = document.getElementById('bottomAvatarWrap');
        if (bottomWrap && !bottomWrap.contains(e.target)) {
            if (bottomMenu) { bottomMenu.classList.remove('show'); bottomBtn && bottomBtn.setAttribute('aria-expanded','false'); }
        }
    });

    /* ----------------------------------------------------------------
       LOGOUT
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
                document.getElementById('guruLogoutForm').submit();
            }
        });
    };

    /* ================================================================
       INIT
    ================================================================ */

}());
</script>

@stack('scripts')

</body>
</html>
