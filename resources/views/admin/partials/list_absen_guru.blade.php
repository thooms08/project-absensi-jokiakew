<style>
    .ag-table-wrap { overflow-x:auto; -webkit-overflow-scrolling:touch; }

    .ag-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.855rem;
        min-width: 560px;
    }

    .ag-table thead th {
        font-size: 0.67rem; font-weight:700;
        color: rgba(74,222,128,0.6);
        text-transform: uppercase; letter-spacing:0.08em;
        padding: 0.85rem 1.1rem;
        border-bottom: 1px solid rgba(74,222,128,0.1);
        white-space: nowrap;
        background: rgba(255,255,255,0.02);
    }

    .ag-table thead th:first-child { padding-left:1.25rem; }
    .ag-table thead th:last-child  { padding-right:1.25rem; text-align:right; }

    .ag-table tbody td {
        padding: 0.78rem 1.1rem;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.78);
        vertical-align: middle;
    }

    .ag-table tbody td:first-child { padding-left:1.25rem; }
    .ag-table tbody td:last-child  { padding-right:1.25rem; }
    .ag-table tbody tr:last-child td { border-bottom:none; }
    .ag-table tbody tr:hover td     { background:rgba(74,222,128,0.03); }

    /* Avatar */
    .ag-avatar {
        width:36px; height:36px; border-radius:50%;
        background: linear-gradient(135deg,#0a7c4a,#4ade80);
        display: inline-flex; align-items:center; justify-content:center;
        font-size:0.8rem; font-weight:700; color:#064e2e; flex-shrink:0;
        border: 2px solid rgba(74,222,128,0.2);
    }

    .ag-name { font-weight:600; color:#fff; font-size:0.875rem; }
    .ag-uname { font-size:0.72rem; color:rgba(74,222,128,0.6); }

    /* Count chips */
    .cc { display:inline-flex; align-items:center; gap:0.25rem; border-radius:50px; padding:0.2rem 0.65rem; font-size:0.75rem; font-weight:700; white-space:nowrap; }
    .cc-hadir { background:rgba(34,197,94,0.12); color:#4ade80; border:1px solid rgba(34,197,94,0.22); }
    .cc-izin  { background:rgba(251,191,36,0.1); color:#fbbf24; border:1px solid rgba(251,191,36,0.2); }
    .cc-tidak { background:rgba(248,113,113,0.1); color:#f87171; border:1px solid rgba(248,113,113,0.2); }
    .cc-zero  { background:rgba(255,255,255,0.04); color:rgba(255,255,255,0.25); border:1px solid rgba(255,255,255,0.07); }

    /* Tombol aksi */
    .btn-ag {
        display:inline-flex; align-items:center; justify-content:center;
        width:32px; height:32px; border-radius:8px; border:none;
        cursor:pointer; font-size:0.85rem;
        transition:background 0.15s, transform 0.12s;
        padding:0; flex-shrink:0;
    }

    .btn-ag:hover { transform:translateY(-1px); }

    .btn-ag-detail { background:rgba(74,222,128,0.1); color:#4ade80; border:1px solid rgba(74,222,128,0.2); }
    .btn-ag-detail:hover { background:rgba(74,222,128,0.2); }

    .btn-ag-foto { background:rgba(34,211,238,0.1); color:#22d3ee; border:1px solid rgba(34,211,238,0.18); }
    .btn-ag-foto:hover { background:rgba(34,211,238,0.2); }

    /* Header bar */
    .ag-header-bar {
        display:flex; align-items:center; justify-content:space-between;
        padding:0.8rem 1.25rem;
        border-bottom:1px solid rgba(74,222,128,0.08);
        background:rgba(255,255,255,0.02);
        gap:0.75rem; flex-wrap:wrap;
    }

    .ag-header-title { font-size:0.82rem; font-weight:600; color:rgba(255,255,255,0.7); display:flex; align-items:center; gap:0.5rem; }
    .ag-header-badge { font-size:0.65rem; font-weight:700; padding:0.12rem 0.5rem; border-radius:50px; background:rgba(74,222,128,0.12); color:#4ade80; border:1px solid rgba(74,222,128,0.2); }
    .ag-header-bulan { font-size:0.75rem; color:rgba(255,255,255,0.38); white-space:nowrap; }

    /* Empty */
    .ag-empty { text-align:center; padding:3rem 1.5rem; }
    .ag-empty-icon { font-size:2.5rem; color:rgba(74,222,128,0.18); display:block; margin-bottom:0.75rem; }
    .ag-empty-title { font-size:0.95rem; font-weight:600; color:rgba(255,255,255,0.4); margin:0 0 0.3rem; }
    .ag-empty-sub   { font-size:0.8rem; color:rgba(255,255,255,0.25); margin:0; }

    @media (max-width:767.98px) { .ag-col-hide-sm { display:none; } }
    @media (max-width:575.98px) { .ag-col-hide-xs { display:none; } .ag-table { min-width:420px; } }
</style>

@php
    use Carbon\Carbon;
    try {
        $labelBulan = Carbon::createFromFormat('Y-m', $bulan)->translatedFormat('F Y');
    } catch (\Exception $e) {
        $labelBulan = $bulan;
    }
@endphp

{{-- HEADER BAR --}}
<div class="ag-header-bar">
    <div class="ag-header-title">
        <i class="bi bi-clipboard2-check-fill" style="color:#4ade80;"></i>
        Rekap Kehadiran Guru
        <span class="ag-header-badge">{{ $rekap->count() }} guru</span>
    </div>
    <span class="ag-header-bulan">
        <i class="bi bi-calendar3 me-1"></i>{{ $labelBulan }}
    </span>
</div>

<div class="ag-table-wrap">

    @if($rekap->isEmpty())
        <div class="ag-empty">
            <i class="bi bi-person-lines-fill ag-empty-icon"></i>
            <p class="ag-empty-title">Belum ada data guru.</p>
            <p class="ag-empty-sub">Tambahkan guru terlebih dahulu di halaman Data Guru.</p>
        </div>
    @else
        <table class="ag-table" aria-label="Rekap absensi guru">
            <thead>
                <tr>
                    <th style="width:48px;">#</th>
                    <th>Nama Guru</th>
                    <th style="text-align:center;">Hadir</th>
                    <th style="text-align:center;">Izin</th>
                    <th style="text-align:center;">Tidak Hadir</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekap as $i => $row)
                @php
                    $guru   = $row['guru'];
                    $hadir  = $row['total_hadir'];
                    $izin   = $row['total_izin'];
                    $tidak  = $row['total_tidak'];
                @endphp
                <tr>
                    {{-- No --}}
                    <td style="color:rgba(255,255,255,0.3);font-size:0.78rem;">{{ $i + 1 }}</td>

                    {{-- Guru --}}
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="ag-avatar">{{ strtoupper(mb_substr($guru->name, 0, 1)) }}</span>
                            <div style="min-width:0;">
                                <div class="ag-name text-truncate" style="max-width:180px;">{{ $guru->name }}</div>
                                <div class="ag-uname">&#64;{{ $guru->username }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- Hadir --}}
                    <td style="text-align:center;">
                        <span class="cc {{ $hadir > 0 ? 'cc-hadir' : 'cc-zero' }}">
                            <i class="bi bi-check-circle-fill" style="font-size:0.6rem;"></i>
                            {{ $hadir }}
                        </span>
                    </td>

                    {{-- Izin --}}
                    <td style="text-align:center;">
                        <span class="cc {{ $izin > 0 ? 'cc-izin' : 'cc-zero' }}">
                            <i class="bi bi-envelope-fill" style="font-size:0.6rem;"></i>
                            {{ $izin }}
                        </span>
                    </td>

                    {{-- Tidak --}}
                    <td style="text-align:center;">
                        <span class="cc {{ $tidak > 0 ? 'cc-tidak' : 'cc-zero' }}">
                            <i class="bi bi-x-circle-fill" style="font-size:0.6rem;"></i>
                            {{ $tidak }}
                        </span>
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            {{-- Detail (tabel rincian) --}}
                            <button
                                type="button"
                                class="btn-ag btn-ag-detail"
                                data-detail-id="{{ $guru->id }}"
                                data-nama="{{ $guru->name }}"
                                title="Lihat detail absensi {{ $guru->name }}"
                                aria-label="Detail {{ $guru->name }}"
                            >
                                <i class="bi bi-list-ul"></i>
                            </button>

                            {{-- Dokumen (grid foto bukti) --}}
                            <button
                                type="button"
                                class="btn-ag btn-ag-foto"
                                data-foto-id="{{ $guru->id }}"
                                data-nama="{{ $guru->name }}"
                                title="Lihat foto bukti kehadiran {{ $guru->name }}"
                                aria-label="Foto {{ $guru->name }}"
                            >
                                <i class="bi bi-images"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
