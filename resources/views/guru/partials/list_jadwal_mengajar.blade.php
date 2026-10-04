<style>
    .jadwal-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

    .jadwal-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.845rem;
        min-width: 420px;
    }

    .jadwal-table thead th {
        font-size: 0.67rem; font-weight: 700;
        color: rgba(74,222,128,0.6);
        text-transform: uppercase; letter-spacing: 0.08em;
        padding: 0.85rem 1.1rem;
        border-bottom: 1px solid rgba(74,222,128,0.1);
        white-space: nowrap; background: rgba(255,255,255,0.02);
    }

    .jadwal-table thead th:first-child { padding-left: 1.25rem; }
    .jadwal-table thead th:last-child  { padding-right: 1.25rem; }

    .jadwal-table tbody td {
        padding: 0.82rem 1.1rem;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.78); vertical-align: middle;
    }

    .jadwal-table tbody td:first-child { padding-left: 1.25rem; }
    .jadwal-table tbody td:last-child  { padding-right: 1.25rem; }
    .jadwal-table tbody tr:last-child td { border-bottom: none; }
    .jadwal-table tbody tr:hover td { background: rgba(74,222,128,0.03); }

    .kelas-badge {
        display: inline-flex; align-items: center; gap: 0.3rem;
        background: rgba(34,211,238,0.1); color: #22d3ee;
        border: 1px solid rgba(34,211,238,0.2);
        border-radius: 8px; padding: 0.3rem 0.7rem;
        font-size: 0.82rem; font-weight: 700; white-space: nowrap;
    }

    .badge-wrap { display: flex; flex-wrap: wrap; gap: 0.3rem; }

    .badge-mapel {
        display: inline-flex; align-items: center;
        background: rgba(251,191,36,0.1); color: #fbbf24;
        border: 1px solid rgba(251,191,36,0.22);
        border-radius: 50px; padding: 0.13rem 0.55rem;
        font-size: 0.68rem; font-weight: 600; white-space: nowrap;
    }

    .jam-slot-list { display: flex; flex-direction: column; gap: 0.32rem; }

    .jam-slot {
        display: inline-flex; align-items: center; gap: 0.45rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 8px; padding: 0.28rem 0.65rem;
        font-size: 0.78rem; color: rgba(255,255,255,0.72);
        white-space: nowrap; width: fit-content;
    }

    .jam-slot i { color: rgba(74,222,128,0.55); font-size: 0.72rem; }
    .jam-slot-mapel { font-size: 0.7rem; color: rgba(251,191,36,0.75); font-weight: 600; }

    .jadwal-header-bar {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.8rem 1.25rem;
        border-bottom: 1px solid rgba(74,222,128,0.08);
        background: rgba(255,255,255,0.02);
        gap: 0.75rem; flex-wrap: wrap;
    }

    .jadwal-header-title {
        font-size: 0.82rem; font-weight: 600;
        color: rgba(255,255,255,0.7);
        display: flex; align-items: center; gap: 0.5rem;
    }

    .jadwal-count-badge {
        font-size: 0.65rem; font-weight: 700;
        padding: 0.12rem 0.5rem; border-radius: 50px;
        background: rgba(74,222,128,0.12); color: #4ade80;
        border: 1px solid rgba(74,222,128,0.2);
    }

    .jadwal-date-badge {
        font-size: 0.75rem; font-weight: 600;
        color: rgba(255,255,255,0.4); white-space: nowrap;
    }

    .jadwal-empty { text-align: center; padding: 3.5rem 1.5rem; }

    .jadwal-empty-icon {
        font-size: 2.6rem; color: rgba(74,222,128,0.18);
        display: block; margin-bottom: 0.75rem;
    }

    .jadwal-empty-title {
        font-size: 0.95rem; font-weight: 600;
        color: rgba(255,255,255,0.4); margin: 0 0 0.3rem;
    }

    .jadwal-empty-sub { font-size: 0.8rem; color: rgba(255,255,255,0.25); margin: 0; }

    @media (max-width: 575.98px) {
        .j-col-hide-xs { display: none; }
        .jadwal-table  { min-width: 340px; }
    }
</style>

@php
    $DOW_TO_HARI = [1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu'];
    $hariSaatIni = $DOW_TO_HARI[date('N')] ?? 'Senin';
    $totalKelas  = $grouped->count();
    $totalSesi   = $grouped->sum(fn($g) => count($g['jam_range']));
@endphp

{{-- HEADER BAR --}}
<div class="jadwal-header-bar">
    <div class="jadwal-header-title">
        <i class="bi bi-calendar2-week-fill" style="color:#4ade80;"></i>
        Jadwal {{ $hariAktif }}
        <span class="jadwal-count-badge">{{ $totalKelas }} kelas · {{ $totalSesi }} sesi</span>
    </div>
    @if($hariAktif === $hariSaatIni)
        <span class="jadwal-date-badge" style="color:rgba(74,222,128,0.6);">
            <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i>Hari ini
        </span>
    @endif
</div>

{{-- TABLE / EMPTY --}}
<div class="jadwal-table-wrap">

    @if($grouped->isEmpty())
        <div class="jadwal-empty">
            <i class="bi bi-calendar2-x jadwal-empty-icon"></i>
            <p class="jadwal-empty-title">Tidak ada jadwal mengajar pada hari {{ $hariAktif }}.</p>
            <p class="jadwal-empty-sub">Silakan pilih hari lain.</p>
        </div>
    @else
        <table class="jadwal-table" aria-label="Jadwal mengajar saya">
            <thead>
                <tr>
                    <th style="width:46px;">#</th>
                    <th>Kelas</th>
                    <th class="j-col-hide-xs">Mata Pelajaran</th>
                    <th>Jam Mengajar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grouped as $no => $row)
                @php
                    $kelas     = $row['kelas'];
                    $mapelList = $row['mapel'];
                    $jamList   = $row['jam_range'];
                @endphp
                <tr>
                    <td style="color:rgba(255,255,255,0.3); font-size:0.77rem;">{{ $no + 1 }}</td>

                    {{-- Kelas --}}
                    <td>
                        <span class="kelas-badge">
                            <i class="bi bi-door-open-fill" style="font-size:0.75rem;"></i>
                            {{ $kelas->nama_kelas ?? '-' }}
                        </span>
                    </td>

                    {{-- Mata Pelajaran --}}
                    <td class="j-col-hide-xs">
                        @if($mapelList->isNotEmpty())
                            <div class="badge-wrap">
                                @foreach($mapelList as $mp)
                                    <span class="badge-mapel">{{ $mp->nama_mapel }}</span>
                                @endforeach
                            </div>
                        @else
                            <span style="font-size:0.75rem;color:rgba(255,255,255,0.22);font-style:italic;">–</span>
                        @endif
                    </td>

                    {{-- Jam per sesi --}}
                    <td>
                        <div class="jam-slot-list">
                            @foreach($jamList as $jam)
                                <div class="jam-slot">
                                    <i class="bi bi-clock"></i>
                                    <span>
                                        {{ substr($jam['waktu_mulai'], 0, 5) }}
                                        –
                                        {{ substr($jam['waktu_berakhir'], 0, 5) }}
                                    </span>
                                    @if(count($jamList) > 1)
                                        <span style="color:rgba(255,255,255,0.2);">·</span>
                                        <span class="jam-slot-mapel">
                                            {{ $jam['mapel']->nama_mapel ?? '-' }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
