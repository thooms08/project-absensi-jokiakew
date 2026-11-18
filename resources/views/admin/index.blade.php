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
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
        margin-bottom: 1.75rem;
    }
    @media (max-width: 1199.98px) { .stat-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 767.98px)  { .stat-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 399.98px)  { .stat-grid { grid-template-columns: repeat(2, 1fr); gap: 0.65rem; } }

    .stat-card {
        background: linear-gradient(145deg, rgba(255,255,255,.055) 0%, rgba(255,255,255,.025) 100%);
        border: 1px solid rgba(74,222,128,.13);
        border-radius: 16px 0 16px 0;
        padding: 1.2rem 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: .85rem;
        position: relative;
        overflow: hidden;
        transition: transform .18s, box-shadow .18s;
        min-width: 0;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(0,0,0,.3); }
    .stat-card::after {
        content: ''; position: absolute; inset: 0;
        background: linear-gradient(135deg, rgba(74,222,128,.05) 0%, transparent 60%);
        pointer-events: none;
    }

    .stat-icon-wrap {
        width: 42px; height: 42px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; flex-shrink: 0;
    }
    @media (max-width: 767.98px) {
        .stat-card { padding: 1rem; gap: .65rem; }
        .stat-icon-wrap { width: 36px; height: 36px; font-size: 1rem; border-radius: 8px; }
    }
    @media (max-width: 399.98px) {
        .stat-card { padding: .85rem .75rem; }
        .stat-icon-wrap { display: none; }
    }

    .stat-icon-green { background: rgba(74,222,128,.15); color: #4ade80; }
    .stat-icon-teal  { background: rgba(34,211,238,.15);  color: #22d3ee; }
    .stat-icon-amber { background: rgba(251,191,36,.15);  color: #fbbf24; }
    .stat-icon-red   { background: rgba(248,113,113,.15); color: #f87171; }
    .stat-icon-sky   { background: rgba(125,211,252,.15); color: #7dd3fc; }

    .stat-body { min-width: 0; flex: 1; }
    .stat-label {
        font-size: .7rem; font-weight: 600; color: rgba(255,255,255,.5);
        text-transform: uppercase; letter-spacing: .06em;
        margin-bottom: .25rem; white-space: nowrap;
        overflow: hidden; text-overflow: ellipsis;
    }
    .stat-value {
        font-size: clamp(1.25rem, 2.5vw, 1.85rem);
        font-weight: 800; color: #fff;
        line-height: 1; margin-bottom: .25rem;
    }
    .stat-change { font-size: .7rem; color: rgba(255,255,255,.45); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .stat-change.up   { color: #4ade80; }
    .stat-change.down { color: #f87171; }

    /* ============================================================
       SKELETON LOADING
    ============================================================ */
    @keyframes shimmer {
        0%   { background-position: -600px 0; }
        100% { background-position: 600px 0; }
    }
    .skeleton {
        background: linear-gradient(90deg,
            rgba(255,255,255,.06) 25%,
            rgba(255,255,255,.12) 50%,
            rgba(255,255,255,.06) 75%);
        background-size: 1200px 100%;
        animation: shimmer 1.4s infinite linear;
        border-radius: 6px;
        display: inline-block;
    }
    .sk-val  { width: 70%; height: 1.6rem; }
    .sk-lbl  { width: 90%; height: .75rem; margin-bottom: .4rem; }
    .sk-chg  { width: 55%; height: .65rem; }
    .sk-row  { width: 100%; height: .85rem; margin-bottom: .5rem; }
    .sk-row:last-child { margin-bottom: 0; }
    .sk-chart { width: 100%; height: 100%; border-radius: 10px; }

    /* ============================================================
       SECTION HEADING
    ============================================================ */
    .section-heading {
        font-size: .78rem; font-weight: 700;
        color: rgba(74,222,128,.7); text-transform: uppercase; letter-spacing: .09em;
        margin-bottom: 1rem; display: flex; align-items: center; gap: .55rem;
    }
    .section-heading::after { content: ''; flex: 1; height: 1px; background: rgba(74,222,128,.12); }

    /* Badge sekolah di section heading */
    .sekolah-badge {
        font-size: .68rem; font-weight: 700; padding: .15rem .6rem;
        border-radius: 50px; letter-spacing: .06em; text-transform: uppercase;
        background: rgba(74,222,128,.12); color: #4ade80;
        border: 1px solid rgba(74,222,128,.28);
        transition: background .2s, color .2s;
    }

    /* ============================================================
       PANEL
    ============================================================ */
    .panel {
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(74,222,128,.12);
        border-radius: 16px 0 16px 0;
        padding: 1.35rem 1.5rem;
        height: 100%;
    }
    .panel-title {
        font-size: .875rem; font-weight: 700; color: #e2f5ea;
        margin-bottom: 1.25rem;
        display: flex; align-items: center; justify-content: space-between; gap: .5rem;
    }
    .panel-title-badge {
        font-size: .68rem; font-weight: 600; padding: .18rem .55rem;
        border-radius: 50px; background: rgba(74,222,128,.12); color: #4ade80;
        border: 1px solid rgba(74,222,128,.22); letter-spacing: .04em;
    }

    /* ============================================================
       TABEL RINGKAS
    ============================================================ */
    .mini-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
    .mini-table thead th {
        font-size: .68rem; font-weight: 700; color: rgba(74,222,128,.65);
        text-transform: uppercase; letter-spacing: .07em;
        padding: 0 .5rem .65rem;
        border-bottom: 1px solid rgba(74,222,128,.12); white-space: nowrap;
    }
    .mini-table tbody td {
        padding: .6rem .5rem; border-bottom: 1px solid rgba(255,255,255,.05);
        color: rgba(255,255,255,.78); vertical-align: middle;
    }
    .mini-table tbody tr:last-child td { border-bottom: none; }
    .mini-table tbody tr:hover td { background: rgba(74,222,128,.04); }

    .badge-hadir { background: rgba(74,222,128,.15);  color: #4ade80;  border: 1px solid rgba(74,222,128,.25);  border-radius: 50px; padding: .18rem .6rem; font-size: .7rem; font-weight: 600; white-space: nowrap; }
    .badge-tidak { background: rgba(248,113,113,.15); color: #f87171; border: 1px solid rgba(248,113,113,.25); border-radius: 50px; padding: .18rem .6rem; font-size: .7rem; font-weight: 600; white-space: nowrap; }
    .badge-izin  { background: rgba(251,191,36,.15);  color: #fbbf24; border: 1px solid rgba(251,191,36,.25);  border-radius: 50px; padding: .18rem .6rem; font-size: .7rem; font-weight: 600; white-space: nowrap; }

    .avatar-initials {
        width: 30px; height: 30px; border-radius: 50%;
        background: linear-gradient(135deg,#0a7c4a,#4ade80);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .72rem; font-weight: 700; color: #064e2e; flex-shrink: 0;
    }

    /* ============================================================
       DONUT LEGEND
    ============================================================ */
    .legend-list { list-style: none; padding: 0; margin: 1rem 0 0; display: flex; flex-direction: column; gap: .55rem; }
    .legend-item { display: flex; align-items: center; gap: .6rem; font-size: .82rem; color: rgba(255,255,255,.72); }
    .legend-dot  { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
    .legend-pct  { margin-left: auto; font-weight: 700; color: #fff; }

    /* ============================================================
       ACTIVITY FEED
    ============================================================ */
    .activity-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0; }
    .activity-item {
        display: flex; align-items: flex-start; gap: .85rem;
        padding: .7rem 0; border-bottom: 1px solid rgba(255,255,255,.05); position: relative;
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; margin-top: 5px; }
    .activity-text { font-size: .82rem; color: rgba(255,255,255,.72); line-height: 1.45; flex: 1; }
    .activity-text strong { color: #fff; font-weight: 600; }
    .activity-time { font-size: .7rem; color: rgba(255,255,255,.35); white-space: nowrap; margin-top: 2px; flex-shrink: 0; }

    /* ============================================================
       CHART CONTAINER
    ============================================================ */
    .chart-container        { position: relative; width: 100%; height: 220px; }
    .chart-container-donut  { position: relative; width: 140px; height: 140px; margin: 0 auto; }
    @media (max-width: 767.98px) {
        .chart-container       { height: 180px; }
        .chart-container-donut { width: 120px; height: 120px; }
    }
    @media (max-width: 575.98px) {
        .chart-container       { height: 160px; }
        .chart-container-donut { width: 110px; height: 110px; }
        .mini-table .col-hide-xs { display: none; }
    }
</style>
@endpush

@section('content')

{{-- ================================================================
     STAT CARDS
================================================================ --}}
<div class="stat-grid" id="statGrid">

    <div class="stat-card">
        <div class="stat-icon-wrap stat-icon-green"><i class="bi bi-person-lines-fill"></i></div>
        <div class="stat-body">
            <div class="stat-label">Total Guru</div>
            <div class="stat-value" id="statTotalGuru"><span class="skeleton sk-val"></span></div>
            <div class="stat-change" id="statGuruSub">&nbsp;</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrap stat-icon-teal"><i class="bi bi-people-fill"></i></div>
        <div class="stat-body">
            <div class="stat-label">Total Murid</div>
            <div class="stat-value" id="statTotalMurid"><span class="skeleton sk-val"></span></div>
            <div class="stat-change" id="statMuridSub">&nbsp;</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrap stat-icon-sky"><i class="bi bi-check2-circle"></i></div>
        <div class="stat-body">
            <div class="stat-label">Hadir Hari Ini</div>
            <div class="stat-value" id="statHadir"><span class="skeleton sk-val"></span></div>
            <div class="stat-change up" id="statHadirPct">&nbsp;</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrap stat-icon-red"><i class="bi bi-x-circle"></i></div>
        <div class="stat-body">
            <div class="stat-label">Tidak Hadir</div>
            <div class="stat-value" id="statTidak"><span class="skeleton sk-val"></span></div>
            <div class="stat-change down" id="statTidakSub">&nbsp;</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon-wrap stat-icon-amber"><i class="bi bi-journal-text"></i></div>
        <div class="stat-body">
            <div class="stat-label">Izin</div>
            <div class="stat-value" id="statIzin"><span class="skeleton sk-val"></span></div>
            <div class="stat-change" id="statIzinSub" style="color:rgba(255,255,255,.4);">&nbsp;</div>
        </div>
    </div>

</div>

{{-- ================================================================
     BARIS 1: Grafik garis + Donut
================================================================ --}}
<p class="section-heading">
    <i class="bi bi-graph-up" style="color:#4ade80;"></i>
    Statistik Kehadiran
    <span class="sekolah-badge" id="badgeJenis1">…</span>
</p>

<div class="row g-4 mb-4">

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
                    <span class="legend-pct" id="legendHadir">–</span>
                </li>
                <li class="legend-item">
                    <span class="legend-dot" style="background:#f87171;"></span>
                    Tidak Hadir
                    <span class="legend-pct" id="legendTidak">–</span>
                </li>
                <li class="legend-item">
                    <span class="legend-dot" style="background:#fbbf24;"></span>
                    Izin
                    <span class="legend-pct" id="legendIzin">–</span>
                </li>
            </ul>
        </div>
    </div>

</div>

{{-- ================================================================
     BARIS 2: Bar bulanan + Tabel guru
================================================================ --}}
<p class="section-heading">
    <i class="bi bi-bar-chart-fill" style="color:#4ade80;"></i>
    Rekapitulasi Bulanan
    <span class="sekolah-badge" id="badgeJenis2">…</span>
</p>

<div class="row g-4 mb-4">

    <div class="col-lg-7">
        <div class="panel">
            <div class="panel-title">
                Absensi Murid Per Bulan
                <span class="panel-title-badge" id="barBadge">…</span>
            </div>
            <div class="chart-container">
                <canvas id="chartBar"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="panel">
            <div class="panel-title">
                Absensi Guru Hari Ini
                <a href="{{ route('admin.absen_guru') }}"
                   style="font-size:.75rem;color:#4ade80;text-decoration:none;font-weight:600;">
                    Lihat semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div style="overflow-x:auto;" id="tabelGuruWrap">
                <div style="text-align:center;padding:1.5rem;color:rgba(255,255,255,.3);font-size:.82rem;">
                    <span class="skeleton sk-row" style="display:block;"></span>
                    <span class="skeleton sk-row" style="display:block;"></span>
                    <span class="skeleton sk-row" style="display:block;"></span>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ================================================================
     BARIS 3: Aktivitas terbaru
================================================================ --}}
<p class="section-heading">
    <i class="bi bi-activity" style="color:#4ade80;"></i>
    Aktivitas Terbaru
    <span class="sekolah-badge" id="badgeJenis3">…</span>
</p>

<div class="row g-4 mb-2">
    <div class="col-12">
        <div class="panel">
            <ul class="activity-list" id="activityList">
                @foreach(range(1,5) as $_)
                <li class="activity-item">
                    <span class="activity-dot" style="background:rgba(74,222,128,.2);"></span>
                    <div class="activity-text"><span class="skeleton sk-row" style="display:block;width:70%;"></span></div>
                    <span class="activity-time"><span class="skeleton" style="width:50px;height:.65rem;display:inline-block;"></span></span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function () {
    'use strict';

    /* ================================================================
       KONSTANTA WARNA
    ================================================================ */
    var GREEN      = '#4ade80';
    var GREEN_DARK = '#22c55e';
    var TEAL       = '#22d3ee';
    var AMBER      = '#fbbf24';
    var RED        = '#f87171';
    var GRID_LINE  = 'rgba(74,222,128,0.08)';
    var TICK_COLOR = 'rgba(255,255,255,0.38)';

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color       = TICK_COLOR;

    var isMobile = window.innerWidth < 576;

    /* ================================================================
       INSTANCE CHART (disimpan agar bisa di-destroy saat reload)
    ================================================================ */
    var chartLine  = null;
    var chartDonut = null;
    var chartBar   = null;

    /* ================================================================
       HELPER: buat gradient vertikal
    ================================================================ */
    function gradient(ctx, colorTop, colorBot) {
        var g = ctx.createLinearGradient(0, 0, 0, 220);
        g.addColorStop(0, colorTop);
        g.addColorStop(1, colorBot);
        return g;
    }

    /* ================================================================
       HELPER: escape HTML sederhana
    ================================================================ */
    function esc(s) {
        return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    /* ================================================================
       RENDER STAT CARDS
    ================================================================ */
    function renderStats(s, label) {
        document.getElementById('statTotalGuru').textContent  = s.total_guru;
        document.getElementById('statTotalMurid').textContent = s.total_murid;
        document.getElementById('statHadir').textContent      = s.hadir;
        document.getElementById('statTidak').textContent      = s.tidak;
        document.getElementById('statIzin').textContent       = s.izin;

        document.getElementById('statGuruSub').innerHTML =
            '<i class="bi bi-building me-1"></i>' + label;
        document.getElementById('statMuridSub').innerHTML =
            '<i class="bi bi-building me-1"></i>' + label;
        document.getElementById('statHadirPct').innerHTML =
            '<i class="bi bi-arrow-up-short"></i>' + s.pct_hadir + '% dari total';
        document.getElementById('statTidakSub').innerHTML =
            s.tidak + ' orang absen';
        document.getElementById('statIzinSub').textContent =
            s.izin + ' pengajuan izin';
    }

    /* ================================================================
       RENDER BADGE SEKOLAH
    ================================================================ */
    function renderBadges(label) {
        ['badgeJenis1','badgeJenis2','badgeJenis3'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.textContent = label;
        });
        var bb = document.getElementById('barBadge');
        if (bb) bb.textContent = 'Murid ' + label;
    }

    /* ================================================================
       RENDER GRAFIK GARIS (7 hari)
    ================================================================ */
    function renderLine(lineData) {
        var ctx = document.getElementById('chartLine').getContext('2d');

        if (chartLine) { chartLine.destroy(); chartLine = null; }

        var gradMurid = gradient(ctx, 'rgba(74,222,128,0.35)', 'rgba(74,222,128,0)');
        var gradGuru  = gradient(ctx, 'rgba(34,211,238,0.28)', 'rgba(34,211,238,0)');

        chartLine = new Chart(ctx, {
            type: 'line',
            data: {
                labels:   lineData.labels,
                datasets: [
                    {
                        label: 'Murid Hadir',
                        data:  lineData.murid_hadir,
                        borderColor: GREEN, backgroundColor: gradMurid,
                        borderWidth: 2.2, tension: 0.42, fill: true,
                        pointBackgroundColor: GREEN,
                        pointRadius: isMobile ? 2 : 4,
                        pointHoverRadius: isMobile ? 4 : 6,
                    },
                    {
                        label: 'Guru Hadir',
                        data:  lineData.guru_hadir,
                        borderColor: TEAL, backgroundColor: gradGuru,
                        borderWidth: 2.2, tension: 0.42, fill: true,
                        pointBackgroundColor: TEAL,
                        pointRadius: isMobile ? 2 : 4,
                        pointHoverRadius: isMobile ? 4 : 6,
                        yAxisID: 'yGuru',
                    },
                ],
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                animation: { duration: 500 },
                plugins: {
                    legend: {
                        position: isMobile ? 'bottom' : 'top',
                        labels: { color: TICK_COLOR, boxWidth: 8, padding: isMobile ? 8 : 16, font: { size: isMobile ? 9 : 11 } }
                    },
                    tooltip: { backgroundColor: '#0a2e1c', borderColor: 'rgba(74,222,128,0.2)', borderWidth: 1, titleColor: '#fff', bodyColor: 'rgba(255,255,255,0.75)', padding: 8 }
                },
                scales: {
                    x: { grid: { color: GRID_LINE }, ticks: { color: TICK_COLOR, font: { size: isMobile ? 9 : 11 }, maxRotation: isMobile ? 45 : 0, maxTicksLimit: 7 } },
                    y: {
                        grid: { color: GRID_LINE },
                        ticks: { color: TICK_COLOR, font: { size: isMobile ? 9 : 11 }, maxTicksLimit: 5 },
                        title: { display: !isMobile, text: 'Murid', color: 'rgba(74,222,128,0.5)', font: { size: 10 } },
                        min: 0,
                    },
                    yGuru: {
                        display: !isMobile, position: 'right',
                        grid: { drawOnChartArea: false },
                        ticks: { color: TICK_COLOR, font: { size: 10 }, maxTicksLimit: 5 },
                        title: { display: !isMobile, text: 'Guru', color: 'rgba(34,211,238,0.5)', font: { size: 10 } },
                        min: 0,
                    }
                }
            }
        });
    }

    /* ================================================================
       RENDER DONUT (komposisi hari ini)
    ================================================================ */
    function renderDonut(s) {
        var ctx = document.getElementById('chartDonut').getContext('2d');

        if (chartDonut) { chartDonut.destroy(); chartDonut = null; }

        document.getElementById('legendHadir').textContent = s.hadir;
        document.getElementById('legendTidak').textContent = s.tidak;
        document.getElementById('legendIzin').textContent  = s.izin;

        chartDonut = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels:   ['Hadir', 'Tidak Hadir', 'Izin'],
                datasets: [{
                    data:            [s.hadir, s.tidak, s.izin],
                    backgroundColor: ['rgba(74,222,128,0.85)', 'rgba(248,113,113,0.85)', 'rgba(251,191,36,0.85)'],
                    borderColor:     'transparent',
                    hoverOffset:     6,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                cutout: '70%',
                animation: { duration: 500 },
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#0a2e1c', borderColor: 'rgba(74,222,128,0.2)', borderWidth: 1, titleColor: '#fff', bodyColor: 'rgba(255,255,255,0.75)', padding: 10 }
                }
            }
        });
    }

    /* ================================================================
       RENDER BAR CHART (per bulan)
    ================================================================ */
    function renderBar(barData) {
        var ctx = document.getElementById('chartBar').getContext('2d');

        if (chartBar) { chartBar.destroy(); chartBar = null; }

        chartBar = new Chart(ctx, {
            type: 'bar',
            data: {
                labels:   barData.labels,
                datasets: [
                    { label: 'Hadir',       data: barData.hadir,  backgroundColor: 'rgba(74,222,128,0.75)', borderRadius: 5, borderSkipped: false },
                    { label: 'Tidak Hadir', data: barData.tidak,  backgroundColor: 'rgba(248,113,113,0.65)', borderRadius: 5, borderSkipped: false },
                    { label: 'Izin',        data: barData.izin,   backgroundColor: 'rgba(251,191,36,0.65)',  borderRadius: 5, borderSkipped: false },
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                animation: { duration: 500 },
                plugins: {
                    legend: {
                        position: isMobile ? 'bottom' : 'top',
                        labels: { color: TICK_COLOR, boxWidth: 8, padding: isMobile ? 8 : 14, font: { size: isMobile ? 9 : 11 } }
                    },
                    tooltip: { backgroundColor: '#0a2e1c', borderColor: 'rgba(74,222,128,0.2)', borderWidth: 1, titleColor: '#fff', bodyColor: 'rgba(255,255,255,0.75)', padding: 8 }
                },
                scales: {
                    x: { grid: { color: GRID_LINE }, ticks: { color: TICK_COLOR, font: { size: isMobile ? 9 : 11 }, maxRotation: isMobile ? 45 : 0 } },
                    y: { grid: { color: GRID_LINE }, ticks: { color: TICK_COLOR, font: { size: isMobile ? 9 : 11 }, maxTicksLimit: isMobile ? 4 : 6 }, min: 0 }
                }
            }
        });
    }

    /* ================================================================
       RENDER TABEL GURU HARI INI
    ================================================================ */
    function renderTabelGuru(rows) {
        var wrap = document.getElementById('tabelGuruWrap');

        if (!rows || rows.length === 0) {
            wrap.innerHTML = '<p style="text-align:center;padding:1.5rem;font-size:.82rem;color:rgba(255,255,255,.3);">Belum ada data absensi hari ini.</p>';
            return;
        }

        var html = '<table class="mini-table"><thead><tr>'
            + '<th>#</th><th>Nama Guru</th>'
            + '<th class="col-hide-xs">Mata Pelajaran</th>'
            + '<th class="col-hide-xs">Waktu</th>'
            + '<th>Status</th>'
            + '</tr></thead><tbody>';

        rows.forEach(function(g, i) {
            var badgeClass = 'badge-' + (g.status === 'tidak' ? 'tidak' : g.status);
            var badgeLabel = g.status === 'tidak' ? 'Tidak Hadir' : (g.status.charAt(0).toUpperCase() + g.status.slice(1));
            html += '<tr>'
                + '<td style="color:rgba(255,255,255,.35);font-size:.75rem;">' + (i + 1) + '</td>'
                + '<td><div class="d-flex align-items-center gap-2">'
                +   '<span class="avatar-initials">' + esc(g.avatar) + '</span>'
                +   '<span>' + esc(g.nama) + '</span>'
                + '</div></td>'
                + '<td class="col-hide-xs" style="color:rgba(255,255,255,.55);">' + esc(g.mapel) + '</td>'
                + '<td class="col-hide-xs" style="font-size:.78rem;color:rgba(255,255,255,.5);">' + esc(g.waktu) + '</td>'
                + '<td><span class="' + badgeClass + '">' + esc(badgeLabel) + '</span></td>'
                + '</tr>';
        });

        html += '</tbody></table>';
        wrap.innerHTML = html;
    }

    /* ================================================================
       RENDER AKTIVITAS
    ================================================================ */
    function renderAktivitas(list) {
        var ul = document.getElementById('activityList');

        if (!list || list.length === 0) {
            ul.innerHTML = '<li class="activity-item"><div class="activity-text" style="color:rgba(255,255,255,.3);">Belum ada aktivitas.</div></li>';
            return;
        }

        var html = '';
        list.forEach(function(a) {
            var typeLabel = a.type === 'guru' ? 'melakukan absensi' : 'melakukan absensi';
            var statusLabel = a.status === 'hadir'
                ? 'hadir ✓'
                : (a.status === 'izin' ? 'mengajukan izin' : 'tidak hadir');

            html += '<li class="activity-item">'
                + '<span class="activity-dot" style="background:' + esc(a.dot) + ';"></span>'
                + '<div class="activity-text">'
                +   '<strong>' + esc(a.nama) + '</strong> '
                +   '(' + (a.type === 'guru' ? 'Guru' : 'Murid') + ') '
                +   statusLabel + '.'
                + '</div>'
                + '<span class="activity-time">' + esc(a.waktu) + '</span>'
                + '</li>';
        });

        ul.innerHTML = html;
    }

    /* ================================================================
       MAIN: FETCH & RENDER SEMUA DATA
    ================================================================ */
    var DATA_URL = '{{ route("admin.dashboard.data") }}';

    function loadDashboard() {
        fetch(DATA_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(d) {
                renderBadges(d.label);
                renderStats(d.stats, d.label);
                renderLine(d.line);
                renderDonut(d.stats);
                renderBar(d.bar);
                renderTabelGuru(d.tabel_guru);
                renderAktivitas(d.aktivitas);
            })
            .catch(function(e) {
                console.error('Dashboard fetch error', e);
            });
    }

    /* Muat pertama kali */
    loadDashboard();

    /* ================================================================
       REAL-TIME: reload saat admin switch SMK ↔ SMP dari sidebar
    ================================================================ */
    document.addEventListener('sekolahChanged', function() {
        loadDashboard();
    });

}());
</script>
@endpush
