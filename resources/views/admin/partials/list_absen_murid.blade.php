<style>
    .am-table-wrap { overflow-x:auto;-webkit-overflow-scrolling:touch; }
    .am-table { width:100%;border-collapse:collapse;font-size:.855rem;min-width:540px; }
    .am-table thead th { font-size:.67rem;font-weight:700;color:rgba(74,222,128,.6);text-transform:uppercase;letter-spacing:.08em;padding:.85rem 1.1rem;border-bottom:1px solid rgba(74,222,128,.1);white-space:nowrap;background:rgba(255,255,255,.02); }
    .am-table thead th:first-child { padding-left:1.25rem; }
    .am-table thead th:last-child  { padding-right:1.25rem;text-align:right; }
    .am-table tbody td { padding:.78rem 1.1rem;border-bottom:1px solid rgba(255,255,255,.04);color:rgba(255,255,255,.78);vertical-align:middle; }
    .am-table tbody td:first-child { padding-left:1.25rem; }
    .am-table tbody td:last-child  { padding-right:1.25rem; }
    .am-table tbody tr:last-child td { border-bottom:none; }
    .am-table tbody tr:hover td { background:rgba(74,222,128,.03); }

    .am-avatar { width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#0a5878,#22d3ee);display:inline-flex;align-items:center;justify-content:center;font-size:.8rem;font-weight:700;color:#0a2040;flex-shrink:0;border:2px solid rgba(34,211,238,.2); }
    .am-name { font-weight:600;color:#fff;font-size:.875rem; }
    .am-uname { font-size:.72rem;color:rgba(34,211,238,.6); }

    .cc { display:inline-flex;align-items:center;gap:.25rem;border-radius:50px;padding:.2rem .65rem;font-size:.75rem;font-weight:700;white-space:nowrap; }
    .cc-hadir { background:rgba(34,197,94,.12);color:#4ade80;border:1px solid rgba(34,197,94,.22); }
    .cc-izin  { background:rgba(251,191,36,.1);color:#fbbf24;border:1px solid rgba(251,191,36,.2); }
    .cc-tidak { background:rgba(248,113,113,.1);color:#f87171;border:1px solid rgba(248,113,113,.2); }
    .cc-zero  { background:rgba(255,255,255,.04);color:rgba(255,255,255,.25);border:1px solid rgba(255,255,255,.07); }

    .btn-am { display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border-radius:8px;border:none;cursor:pointer;font-size:.85rem;transition:background .15s,transform .12s;padding:0;flex-shrink:0; }
    .btn-am:hover { transform:translateY(-1px); }
    .btn-am-detail { background:rgba(74,222,128,.1);color:#4ade80;border:1px solid rgba(74,222,128,.2); }
    .btn-am-detail:hover { background:rgba(74,222,128,.2); }

    .am-header-bar { display:flex;align-items:center;justify-content:space-between;padding:.8rem 1.25rem;border-bottom:1px solid rgba(74,222,128,.08);background:rgba(255,255,255,.02);gap:.75rem;flex-wrap:wrap; }
    .am-header-title { font-size:.82rem;font-weight:600;color:rgba(255,255,255,.7);display:flex;align-items:center;gap:.5rem; }
    .am-header-badge { font-size:.65rem;font-weight:700;padding:.12rem .5rem;border-radius:50px;background:rgba(74,222,128,.12);color:#4ade80;border:1px solid rgba(74,222,128,.2); }
    .am-header-bulan { font-size:.75rem;color:rgba(255,255,255,.38);white-space:nowrap; }

    .am-empty { text-align:center;padding:3rem 1.5rem; }
    .am-empty-icon { font-size:2.5rem;color:rgba(74,222,128,.18);display:block;margin-bottom:.75rem; }
    .am-empty-title { font-size:.95rem;font-weight:600;color:rgba(255,255,255,.4);margin:0 0 .3rem; }
    .am-empty-sub   { font-size:.8rem;color:rgba(255,255,255,.25);margin:0; }

    @media (max-width:767.98px) { .am-col-hide-sm { display:none; } }
    @media (max-width:575.98px) { .am-col-hide-xs { display:none; } .am-table { min-width:420px; } }
</style>

@php
    use Carbon\Carbon;
    try { $labelBulan = Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y'); }
    catch (\Exception $e) { $labelBulan = $bulan; }
@endphp

<div class="am-header-bar">
    <div class="am-header-title">
        <i class="bi bi-clipboard2-check-fill" style="color:#4ade80;"></i>
        Rekap Kehadiran Murid
        <span class="am-header-badge">{{ $rekap->count() }} murid</span>
    </div>
    <span class="am-header-bulan"><i class="bi bi-calendar3 me-1"></i>{{ $labelBulan }}</span>
</div>

<div class="am-table-wrap">
    @if($rekap->isEmpty())
        <div class="am-empty">
            <i class="bi bi-people-fill am-empty-icon"></i>
            <p class="am-empty-title">Belum ada data murid.</p>
            <p class="am-empty-sub">Tambahkan murid di halaman Data Murid.</p>
        </div>
    @else
        <table class="am-table" aria-label="Rekap absensi murid">
            <thead>
                <tr>
                    <th style="width:48px;">#</th>
                    <th>Nama Murid</th>
                    <th style="text-align:center;">Hadir</th>
                    <th style="text-align:center;">Izin</th>
                    <th style="text-align:center;">Tidak Hadir</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekap as $i => $row)
                @php
                    $murid  = $row['murid'];
                    $hadir  = $row['total_hadir'];
                    $izin   = $row['total_izin'];
                    $tidak  = $row['total_tidak'];
                @endphp
                <tr>
                    <td style="color:rgba(255,255,255,.3);font-size:.78rem;">{{ $i + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="am-avatar">{{ strtoupper(mb_substr($murid->name, 0, 1)) }}</span>
                            <div style="min-width:0;">
                                <div class="am-name text-truncate" style="max-width:180px;">{{ $murid->name }}</div>
                                <div class="am-uname">&#64;{{ $murid->username }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="text-align:center;">
                        <span class="cc {{ $hadir > 0 ? 'cc-hadir' : 'cc-zero' }}">
                            <i class="bi bi-check-circle-fill" style="font-size:.6rem;"></i>{{ $hadir }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <span class="cc {{ $izin > 0 ? 'cc-izin' : 'cc-zero' }}">
                            <i class="bi bi-envelope-fill" style="font-size:.6rem;"></i>{{ $izin }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <span class="cc {{ $tidak > 0 ? 'cc-tidak' : 'cc-zero' }}">
                            <i class="bi bi-x-circle-fill" style="font-size:.6rem;"></i>{{ $tidak }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            <button type="button" class="btn-am btn-am-detail"
                                    data-detail-id="{{ $murid->id }}"
                                    data-nama="{{ $murid->name }}"
                                    title="Detail absensi {{ $murid->name }}">
                                <i class="bi bi-list-ul"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
