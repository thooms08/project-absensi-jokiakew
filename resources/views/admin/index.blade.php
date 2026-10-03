@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    /* ============================================================
       STAT CARDS
    ============================================================ */
    .stat-grid {
        display: grid;
        /* Desktop: 5 kolom sama rata */
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
        margin-bottom: 1.75rem;
    }

    /* Tablet landscape: 3 + 2 */
    @media (max-width: 1199.98px) {
        .stat-grid { grid-template-columns: repeat(3, 1fr); }
    }

    /* Tablet portrait: 2 + 3 */
    @media (max-width: 767.98px) {
        .stat-grid { grid-template-columns: repeat(2, 1fr); }
    }

    /* Mobile kecil: tetap 2 kolom, angka lebih kecil */
    @media (max-width: 399.98px) {
        .stat-grid { grid-template-columns: repeat(2, 1fr); gap: 0.65rem; }
    }

    .stat-card {
        background: linear-gradient(145deg, rgba(255,255,255,0.055) 0%, rgba(255,255,255,0.025) 100%);
        border: 1px solid rgba(74, 222, 128, 0.13);
        border-radius: 16px 0 16px 0;
        padding: 1.2rem 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.18s ease, box-shadow 0.18s ease;
        min-width: 0; /* cegah overflow di grid */
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 28px rgba(0,0,0,0.3);
    }

    .stat-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(74,222,128,0.05) 0%, transparent 60%);
        pointer-events: none;
    }

    .stat-icon-wrap {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    @media (max-width: 767.98px) {
        .stat-card    { padding: 1rem; gap: 0.65rem; }
        .stat-icon-wrap { width: 36px; height: 36px; font-size: 1rem; border-radius: 8px; }
    }

    @media (max-width: 399.98px) {
        .stat-card    { padding: 0.85rem 0.75rem; }
        .stat-icon-wrap { display: none; } /* sembunyikan ikon agar angka lebih lega */
    }

    .stat-icon-green  { background: rgba(74,222,128,0.15); color: #4ade80; }
    .stat-icon-teal   { background: rgba(34,211,238,0.15); color: #22d3ee; }
    .stat-icon-amber  { background: rgba(251,191,36,0.15);  color: #fbbf24; }
    .stat-icon-red    { background: rgba(248,113,113,0.15); color: #f87171; }
    .stat-icon-sky    { background: rgba(125,211,252,0.15); color: #7dd3fc; }

    .stat-body { min-width: 0; flex: 1; }

    .stat-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: rgba(255,255,255,0.5);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .stat-value {
        font-size: clamp(1.25rem, 2.5vw, 1.85rem);
        font-weight: 800;
        color: #ffffff;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-change {
        font-size: 0.7rem;
        color: rgba(255,255,255,0.45);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .stat-change.up   { color: #4ade80; }
    .stat-change.down { color: #f87171; }

    /* ============================================================
       SECTION HEADING
    ============================================================ */
    .section-heading {
        font-size: 0.78rem;
        font-weight: 700;
        color: rgba(74, 222, 128, 0.7);
        text-transform: uppercase;
        letter-spacing: 0.09em;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.55rem;
    }

    .section-heading::after {
        content: '';
        flex: 1;
        height: 1px;
        background: rgba(74,222,128,0.12);
    }

    /* ============================================================
       PANEL (card generik)
    ============================================================ */
    .panel {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(74,222,128,0.12);
        border-radius: 16px 0 16px 0;
        padding: 1.35rem 1.5rem;
        height: 100%;
    }

    .panel-title {
        font-size: 0.875rem;
        font-weight: 700;
        color: #e2f5ea;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .panel-title-badge {
        font-size: 0.68rem;
        font-weight: 600;
        padding: 0.18rem 0.55rem;
        border-radius: 50px;
        background: rgba(74,222,128,0.12);
        color: #4ade80;
        border: 1px solid rgba(74,222,128,0.22);
        letter-spacing: 0.04em;
    }

    /* ============================================================
       TABEL RINGKAS
    ============================================================ */
    .mini-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.83rem;
    }

    .mini-table thead th {
        font-size: 0.68rem;
        font-weight: 700;
        color: rgba(74,222,128,0.65);
        text-transform: uppercase;
        letter-spacing: 0.07em;
        padding: 0 0.5rem 0.65rem;
        border-bottom: 1px solid rgba(74,222,128,0.12);
        white-space: nowrap;
    }

    .mini-table tbody td {
        padding: 0.6rem 0.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        color: rgba(255,255,255,0.78);
        vertical-align: middle;
    }

    .mini-table tbody tr:last-child td {
        border-bottom: none;
    }

    .mini-table tbody tr:hover td {
        background: rgba(74,222,128,0.04);
    }

    /* ============================================================
       STATUS BADGES
    ============================================================ */
    .badge-hadir  { background: rgba(74,222,128,0.15); color: #4ade80;  border: 1px solid rgba(74,222,128,0.25);  border-radius: 50px; padding: 0.18rem 0.6rem; font-size: 0.7rem; font-weight: 600; }
    .badge-tidak  { background: rgba(248,113,113,0.15); color: #f87171; border: 1px solid rgba(248,113,113,0.25); border-radius: 50px; padding: 0.18rem 0.6rem; font-size: 0.7rem; font-weight: 600; }
    .badge-izin   { background: rgba(251,191,36,0.15);  color: #fbbf24; border: 1px solid rgba(251,191,36,0.25);  border-radius: 50px; padding: 0.18rem 0.6rem; font-size: 0.7rem; font-weight: 600; }

    /* ============================================================
       AVATAR INITIALS
    ============================================================ */
    .avatar-initials {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0a7c4a, #4ade80);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.72rem;
        font-weight: 700;
        color: #064e2e;
        flex-shrink: 0;
    }

    /* ============================================================
       PROGRESS BAR
    ============================================================ */
    .prog-bar-wrap {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.85rem;
    }

    .prog-bar-label {
        font-size: 0.78rem;
        color: rgba(255,255,255,0.65);
        width: 52px;
        flex-shrink: 0;
    }

    .prog-bar-track {
        flex: 1;
        height: 7px;
        background: rgba(255,255,255,0.08);
        border-radius: 4px;
        overflow: hidden;
    }

    .prog-bar-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 1s ease;
    }

    .prog-val {
        font-size: 0.78rem;
        font-weight: 700;
        color: #ffffff;
        width: 34px;
        text-align: right;
        flex-shrink: 0;
    }

    /* ============================================================
       DONUT LEGEND
    ============================================================ */
    .legend-list {
        list-style: none;
        padding: 0;
        margin: 1rem 0 0;
        display: flex;
        flex-direction: column;
        gap: 0.55rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.82rem;
        color: rgba(255,255,255,0.72);
    }

    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .legend-pct {
        margin-left: auto;
        font-weight: 700;
        color: #ffffff;
    }

    /* ============================================================
       ACTIVITY FEED
    ============================================================ */
    .activity-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .activity-item {
        display: flex;
        align-items: flex-start;
        gap: 0.85rem;
        padding: 0.7rem 0;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        position: relative;
    }

    .activity-item:last-child { border-bottom: none; }

    .activity-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
        margin-top: 5px;
    }

    .activity-text {
        font-size: 0.82rem;
        color: rgba(255,255,255,0.72);
        line-height: 1.45;
        flex: 1;
    }

    .activity-text strong { color: #ffffff; font-weight: 600; }

    .activity-time {
        font-size: 0.7rem;
        color: rgba(255,255,255,0.35);
        white-space: nowrap;
        margin-top: 2px;
        flex-shrink: 0;
    }

    /* ============================================================
       CHART CONTAINER
    ============================================================ */
    .chart-container {
        position: relative;
        width: 100%;
        height: 220px;
    }

    .chart-container-donut {
        position: relative;
        width: 140px;
        height: 140px;
        margin: 0 auto;
    }

    /* Mobile: kurangi tinggi chart agar proporsional */
    @media (max-width: 767.98px) {
        .chart-container        { height: 180px; }
        .chart-container-donut  { width: 120px; height: 120px; }
    }

    @media (max-width: 575.98px) {
        .chart-container        { height: 160px; }
        .chart-container-donut  { width: 110px; height: 110px; }
    }

    /* ============================================================
       RESPONSIVE TWEAKS
    ============================================================ */
    /* Sembunyikan kolom tabel yang tidak penting di mobile */
    @media (max-width: 575.98px) {
        .mini-table .col-hide-xs { display: none; }
    }
</style>
@endpush

@section('content')

{{-- ================================================================
     STAT CARDS
================================================================ --}}
<div class="stat-grid">

    <div class="stat-card">
        <div class="stat-icon-wrap stat-icon-green">
            <i class="bi bi-person-lines-fill"></i>
        </div>
        <div class="stat-body">
            <div class="stat-label">Total Guru</div>
            <div class="stat-value">24</div>
            <div class="stat-change up">
                <i class="bi bi-arrow-up-short"></i>2 bulan ini
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrap stat-icon-teal">
            <i class="bi bi-people-fill"></i>
        </div>
        <div class="stat-body">
            <div class="stat-label">Total Murid</div>
            <div class="stat-value">312</div>
            <div class="stat-change up">
                <i class="bi bi-arrow-up-short"></i>18 bulan ini
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrap stat-icon-sky">
            <i class="bi bi-check2-circle"></i>
        </div>
        <div class="stat-body">
            <div class="stat-label">Hadir Hari Ini</div>
            <div class="stat-value">286</div>
            <div class="stat-change up">
                <i class="bi bi-arrow-up-short"></i>91.7%
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrap stat-icon-red">
            <i class="bi bi-x-circle"></i>
        </div>
        <div class="stat-body">
            <div class="stat-label">Tidak Hadir</div>
            <div class="stat-value">18</div>
            <div class="stat-change down">
                <i class="bi bi-arrow-down-short"></i>5.8%
            </div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrap stat-icon-amber">
            <i class="bi bi-journal-text"></i>
        </div>
        <div class="stat-body">
            <div class="stat-label">Izin</div>
            <div class="stat-value">8</div>
            <div class="stat-change" style="color:rgba(255,255,255,.4);">2.6% dari total</div>
        </div>
    </div>

</div>

{{-- ================================================================
     BARIS 1: Grafik kehadiran + Donut
================================================================ --}}
<p class="section-heading">Statistik Kehadiran</p>

<div class="row g-4 mb-4">

    {{-- Grafik garis mingguan --}}
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-title">
                Kehadiran 7 Hari Terakhir
                <span class="panel-title-badge">Murid + Guru</span>
            </div>
            <div class="chart-container">
                <canvas id="chartLine"></canvas>
            </div>
        </div>
    </div>

    {{-- Donut kehadiran hari ini --}}
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-title">Komposisi Hari Ini</div>
            <div class="chart-container-donut">
                <canvas id="chartDonut"></canvas>
            </div>
            <ul class="legend-list">
                <li class="legend-item">
                    <span class="legend-dot" style="background:#4ade80;"></span>
                    Hadir
                    <span class="legend-pct">286</span>
                </li>
                <li class="legend-item">
                    <span class="legend-dot" style="background:#f87171;"></span>
                    Tidak Hadir
                    <span class="legend-pct">18</span>
                </li>
                <li class="legend-item">
                    <span class="legend-dot" style="background:#fbbf24;"></span>
                    Izin
                    <span class="legend-pct">8</span>
                </li>
            </ul>
        </div>
    </div>

</div>

{{-- ================================================================
     BARIS 2: Grafik batang bulanan + Progress per kelas
================================================================ --}}
<p class="section-heading">Rekapitulasi Bulanan</p>

<div class="row g-4 mb-4">

    {{-- Bar chart bulanan --}}
    <div class="col-lg-7">
        <div class="panel">
            <div class="panel-title">
                Absensi Per Bulan (2026)
                <span class="panel-title-badge">Murid</span>
            </div>
            <div class="chart-container">
                <canvas id="chartBar"></canvas>
            </div>
        </div>
    </div>

    {{-- Progress kehadiran per kelas --}}
    <div class="col-lg-5">
        <div class="panel">
            <div class="panel-title">Tingkat Kehadiran Per Kelas</div>

            <div class="prog-bar-wrap">
                <span class="prog-bar-label">X RPL</span>
                <div class="prog-bar-track">
                    <div class="prog-bar-fill" style="width:94%;background:linear-gradient(90deg,#4ade80,#22c55e);"></div>
                </div>
                <span class="prog-val">94%</span>
            </div>

            <div class="prog-bar-wrap">
                <span class="prog-bar-label">XI RPL</span>
                <div class="prog-bar-track">
                    <div class="prog-bar-fill" style="width:88%;background:linear-gradient(90deg,#4ade80,#22c55e);"></div>
                </div>
                <span class="prog-val">88%</span>
            </div>

            <div class="prog-bar-wrap">
                <span class="prog-bar-label">XII RPL</span>
                <div class="prog-bar-track">
                    <div class="prog-bar-fill" style="width:91%;background:linear-gradient(90deg,#4ade80,#22c55e);"></div>
                </div>
                <span class="prog-val">91%</span>
            </div>

            <div class="prog-bar-wrap">
                <span class="prog-bar-label">X TKJ</span>
                <div class="prog-bar-track">
                    <div class="prog-bar-fill" style="width:79%;background:linear-gradient(90deg,#fbbf24,#f59e0b);"></div>
                </div>
                <span class="prog-val">79%</span>
            </div>

            <div class="prog-bar-wrap">
                <span class="prog-bar-label">XI TKJ</span>
                <div class="prog-bar-track">
                    <div class="prog-bar-fill" style="width:85%;background:linear-gradient(90deg,#4ade80,#22c55e);"></div>
                </div>
                <span class="prog-val">85%</span>
            </div>

            <div class="prog-bar-wrap">
                <span class="prog-bar-label">XII TKJ</span>
                <div class="prog-bar-track">
                    <div class="prog-bar-fill" style="width:72%;background:linear-gradient(90deg,#f87171,#ef4444);"></div>
                </div>
                <span class="prog-val">72%</span>
            </div>

            <div class="prog-bar-wrap" style="margin-bottom:0;">
                <span class="prog-bar-label">X AK</span>
                <div class="prog-bar-track">
                    <div class="prog-bar-fill" style="width:96%;background:linear-gradient(90deg,#4ade80,#22c55e);"></div>
                </div>
                <span class="prog-val">96%</span>
            </div>
        </div>
    </div>

</div>

{{-- ================================================================
     BARIS 3: Tabel absen guru + Aktivitas terbaru
================================================================ --}}
<p class="section-heading">Absensi dan Aktivitas</p>

<div class="row g-4 mb-2">

    {{-- Tabel ringkas absensi guru hari ini --}}
    <div class="col-lg-7">
        <div class="panel">
            <div class="panel-title">
                Absensi Guru Hari Ini
                <a href="{{ route('admin.absen_guru') }}" style="font-size:0.75rem;color:#4ade80;text-decoration:none;font-weight:600;">
                    Lihat semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div style="overflow-x:auto;">
                <table class="mini-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Guru</th>
                            <th class="col-hide-xs">Mata Pelajaran</th>
                            <th class="col-hide-xs">Waktu</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $dummyGuru = [
                                ['Budi Santoso, S.Pd',   'Matematika',    '07:12',  'hadir'],
                                ['Sari Dewi, S.Kom',     'Pemrograman Web','07:18', 'hadir'],
                                ['Ahmad Fauzi, S.T',     'Jaringan Dasar','07:05',  'hadir'],
                                ['Rina Marlina, M.Pd',   'Bahasa Indonesia','--',   'tidak'],
                                ['Hendra Kusuma, S.Pd',  'PKN',           '07:30',  'hadir'],
                                ['Dewi Anggraini, S.E',  'Akuntansi',     '07:22',  'hadir'],
                                ['Rudi Hartono, S.Pd',   'Olahraga',      '--',     'izin'],
                                ['Fitri Handayani, S.T', 'Basis Data',    '07:08',  'hadir'],
                            ];
                        @endphp
                        @foreach($dummyGuru as $i => $guru)
                        <tr>
                            <td style="color:rgba(255,255,255,0.35);font-size:0.75rem;">{{ $i + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar-initials">{{ strtoupper(substr($guru[0], 0, 1)) }}</span>
                                    <span>{{ $guru[0] }}</span>
                                </div>
                            </td>
                            <td class="col-hide-xs" style="color:rgba(255,255,255,0.55);">{{ $guru[1] }}</td>
                            <td class="col-hide-xs" style="font-size:0.78rem;color:rgba(255,255,255,0.5);">{{ $guru[2] }}</td>
                            <td>
                                <span class="badge-{{ $guru[3] }}">
                                    {{ ucfirst($guru[3] === 'tidak' ? 'Tidak Hadir' : $guru[3]) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Feed aktivitas terbaru --}}
    <div class="col-lg-5">
        <div class="panel">
            <div class="panel-title">Aktivitas Terbaru</div>
            <ul class="activity-list">
                <li class="activity-item">
                    <span class="activity-dot" style="background:#4ade80;"></span>
                    <div class="activity-text">
                        <strong>Sari Dewi</strong> melakukan absensi hadir dengan foto bukti.
                    </div>
                    <span class="activity-time">07:18</span>
                </li>
                <li class="activity-item">
                    <span class="activity-dot" style="background:#22d3ee;"></span>
                    <div class="activity-text">
                        <strong>32 murid</strong> kelas X RPL telah melakukan absensi.
                    </div>
                    <span class="activity-time">07:15</span>
                </li>
                <li class="activity-item">
                    <span class="activity-dot" style="background:#4ade80;"></span>
                    <div class="activity-text">
                        <strong>Ahmad Fauzi</strong> melakukan absensi hadir dengan foto bukti.
                    </div>
                    <span class="activity-time">07:05</span>
                </li>
                <li class="activity-item">
                    <span class="activity-dot" style="background:#fbbf24;"></span>
                    <div class="activity-text">
                        <strong>Rudi Hartono</strong> mengajukan izin tidak hadir hari ini.
                    </div>
                    <span class="activity-time">06:55</span>
                </li>
                <li class="activity-item">
                    <span class="activity-dot" style="background:#22d3ee;"></span>
                    <div class="activity-text">
                        Kode absensi baru dibuat untuk kelas <strong>XI TKJ</strong>.
                    </div>
                    <span class="activity-time">06:45</span>
                </li>
                <li class="activity-item">
                    <span class="activity-dot" style="background:#f87171;"></span>
                    <div class="activity-text">
                        <strong>Rina Marlina</strong> tidak hadir tanpa keterangan.
                    </div>
                    <span class="activity-time">kemarin</span>
                </li>
                <li class="activity-item">
                    <span class="activity-dot" style="background:#4ade80;"></span>
                    <div class="activity-text">
                        <strong>Admin</strong> menambahkan 3 data murid baru ke sistem.
                    </div>
                    <span class="activity-time">kemarin</span>
                </li>
            </ul>
        </div>
    </div>

</div>

@endsection

@push('scripts')
{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function () {
    'use strict';

    /* Warna konsisten tema hijau */
    var GREEN      = '#4ade80';
    var GREEN_DARK = '#22c55e';
    var TEAL       = '#22d3ee';
    var AMBER      = '#fbbf24';
    var RED        = '#f87171';
    var GRID_LINE  = 'rgba(74,222,128,0.08)';
    var TICK_COLOR = 'rgba(255,255,255,0.38)';

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color       = TICK_COLOR;

    /* ---- Util: deteksi mobile ---- */
    var isMobile = window.innerWidth < 576;
    var isTablet = window.innerWidth < 768;

    /* ---- Util gradient ---- */
    function makeGradient(ctx, colorTop, colorBot) {
        var g = ctx.createLinearGradient(0, 0, 0, 220);
        g.addColorStop(0,   colorTop);
        g.addColorStop(1,   colorBot);
        return g;
    }

    /* ================================================================
       GRAFIK GARIS: kehadiran 7 hari terakhir
    ================================================================ */
    var ctxLine = document.getElementById('chartLine').getContext('2d');

    var gradMurid = makeGradient(ctxLine, 'rgba(74,222,128,0.35)', 'rgba(74,222,128,0)');
    var gradGuru  = makeGradient(ctxLine, 'rgba(34,211,238,0.28)', 'rgba(34,211,238,0)');

    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Senin', 'Selasa'],
            datasets: [
                {
                    label: 'Murid Hadir',
                    data: [295, 288, 301, 278, 310, 293, 286],
                    borderColor: GREEN,
                    backgroundColor: gradMurid,
                    borderWidth: 2.2,
                    pointBackgroundColor: GREEN,
                    pointRadius: isMobile ? 2 : 4,
                    pointHoverRadius: isMobile ? 4 : 6,
                    tension: 0.42,
                    fill: true,
                },
                {
                    label: 'Guru Hadir',
                    data: [22, 21, 23, 20, 24, 22, 22],
                    borderColor: TEAL,
                    backgroundColor: gradGuru,
                    borderWidth: 2.2,
                    pointBackgroundColor: TEAL,
                    pointRadius: isMobile ? 2 : 4,
                    pointHoverRadius: isMobile ? 4 : 6,
                    tension: 0.42,
                    fill: true,
                    yAxisID: 'yGuru',
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: isMobile ? 'bottom' : 'top',
                    labels: {
                        color: TICK_COLOR,
                        boxWidth: 8,
                        padding: isMobile ? 8 : 16,
                        font: { size: isMobile ? 9 : 11 }
                    }
                },
                tooltip: {
                    backgroundColor: '#0a2e1c',
                    borderColor: 'rgba(74,222,128,0.2)',
                    borderWidth: 1,
                    titleColor: '#ffffff',
                    bodyColor: 'rgba(255,255,255,0.75)',
                    padding: 8,
                }
            },
            scales: {
                x: {
                    grid:  { color: GRID_LINE },
                    ticks: {
                        color: TICK_COLOR,
                        font: { size: isMobile ? 9 : 11 },
                        maxRotation: isMobile ? 45 : 0,
                        maxTicksLimit: isMobile ? 5 : 7
                    }
                },
                y: {
                    grid:   { color: GRID_LINE },
                    ticks:  { color: TICK_COLOR, font: { size: isMobile ? 9 : 11 }, maxTicksLimit: 5 },
                    title:  { display: !isMobile, text: 'Murid', color: 'rgba(74,222,128,0.5)', font: { size: 10 } }
                },
                yGuru: {
                    display:  !isMobile, /* sembunyikan axis kanan di mobile */
                    position: 'right',
                    grid:     { drawOnChartArea: false },
                    ticks:    { color: TICK_COLOR, font: { size: 10 }, maxTicksLimit: 5 },
                    title:    { display: !isMobile, text: 'Guru', color: 'rgba(34,211,238,0.5)', font: { size: 10 } }
                }
            }
        }
    });

    /* ================================================================
       DONUT: komposisi kehadiran hari ini
    ================================================================ */
    var ctxDonut = document.getElementById('chartDonut').getContext('2d');

    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Tidak Hadir', 'Izin'],
            datasets: [{
                data: [286, 18, 8],
                backgroundColor: [
                    'rgba(74,222,128,0.85)',
                    'rgba(248,113,113,0.85)',
                    'rgba(251,191,36,0.85)',
                ],
                borderColor: 'transparent',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0a2e1c',
                    borderColor: 'rgba(74,222,128,0.2)',
                    borderWidth: 1,
                    titleColor: '#ffffff',
                    bodyColor: 'rgba(255,255,255,0.75)',
                    padding: 10,
                }
            }
        }
    });

    /* ================================================================
       BAR CHART: absensi per bulan 2026
    ================================================================ */
    var ctxBar = document.getElementById('chartBar').getContext('2d');

    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep'],
            datasets: [
                {
                    label: 'Hadir',
                    data: [4820, 4750, 4900, 4680, 4830, 4770, 4890, 4810, 4720],
                    backgroundColor: 'rgba(74,222,128,0.75)',
                    borderRadius: 5,
                    borderSkipped: false,
                },
                {
                    label: 'Tidak Hadir',
                    data: [280, 340, 210, 390, 255, 310, 230, 270, 350],
                    backgroundColor: 'rgba(248,113,113,0.65)',
                    borderRadius: 5,
                    borderSkipped: false,
                },
                {
                    label: 'Izin',
                    data: [120, 130, 110, 150, 135, 140, 100, 140, 150],
                    backgroundColor: 'rgba(251,191,36,0.65)',
                    borderRadius: 5,
                    borderSkipped: false,
                },
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: isMobile ? 'bottom' : 'top',
                    labels: {
                        color: TICK_COLOR,
                        boxWidth: 8,
                        padding: isMobile ? 8 : 14,
                        font: { size: isMobile ? 9 : 11 }
                    }
                },
                tooltip: {
                    backgroundColor: '#0a2e1c',
                    borderColor: 'rgba(74,222,128,0.2)',
                    borderWidth: 1,
                    titleColor: '#ffffff',
                    bodyColor: 'rgba(255,255,255,0.75)',
                    padding: 8,
                }
            },
            scales: {
                x: {
                    stacked: false,
                    grid:    { color: GRID_LINE },
                    ticks:   {
                        color: TICK_COLOR,
                        font: { size: isMobile ? 9 : 11 },
                        maxRotation: isMobile ? 45 : 0
                    }
                },
                y: {
                    stacked: false,
                    grid:    { color: GRID_LINE },
                    ticks:   {
                        color: TICK_COLOR,
                        font: { size: isMobile ? 9 : 11 },
                        maxTicksLimit: isMobile ? 4 : 6
                    }
                }
            }
        }
    });

}());
</script>
@endpush
