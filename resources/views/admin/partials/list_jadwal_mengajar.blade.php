<style>
    /* ============================================================
       TABLE WRAPPER
    ============================================================ */
    .jadwal-table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .jadwal-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.845rem;
        min-width: 620px;
    }

    .jadwal-table thead th {
        font-size: 0.67rem;
        font-weight: 700;
        color: rgba(74,222,128,0.6);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 0.85rem 1.1rem;
        border-bottom: 1px solid rgba(74,222,128,0.1);
        white-space: nowrap;
        background: rgba(255,255,255,0.02);
    }

    .jadwal-table thead th:first-child { padding-left: 1.25rem; }
    .jadwal-table thead th:last-child  { padding-right: 1.25rem; text-align: right; }

    .jadwal-table tbody td {
        padding: 0.8rem 1.1rem;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.78);
        vertical-align: middle;
    }

    .jadwal-table tbody td:first-child { padding-left: 1.25rem; }
    .jadwal-table tbody td:last-child  { padding-right: 1.25rem; }

    .jadwal-table tbody tr:last-child td { border-bottom: none; }
    .jadwal-table tbody tr:hover td     { background: rgba(74,222,128,0.03); }

    /* ============================================================
       GURU CELL
    ============================================================ */
    .guru-avatar-sm {
        width: 34px; height: 34px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(74,222,128,0.22);
        flex-shrink: 0;
    }

    .guru-avatar-init-sm {
        width: 34px; height: 34px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0a7c4a, #4ade80);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.78rem; font-weight: 700; color: #064e2e;
        flex-shrink: 0;
        border: 2px solid rgba(74,222,128,0.2);
    }

    .guru-name-sm {
        font-size: 0.875rem;
        font-weight: 600;
        color: #ffffff;
        margin: 0 0 0.08rem;
        line-height: 1.3;
    }

    .guru-username-sm {
        font-size: 0.72rem;
        color: rgba(74,222,128,0.6);
        margin: 0;
    }

    /* ============================================================
       BADGE MULTI (mapel & kelas)
    ============================================================ */
    .badge-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 0.28rem;
    }

    .badge-mapel {
        display: inline-flex; align-items: center;
        background: rgba(251,191,36,0.1);
        color: #fbbf24;
        border: 1px solid rgba(251,191,36,0.22);
        border-radius: 50px;
        padding: 0.13rem 0.55rem;
        font-size: 0.68rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-kelas {
        display: inline-flex; align-items: center;
        background: rgba(34,211,238,0.1);
        color: #22d3ee;
        border: 1px solid rgba(34,211,238,0.18);
        border-radius: 50px;
        padding: 0.13rem 0.55rem;
        font-size: 0.68rem;
        font-weight: 600;
        white-space: nowrap;
    }

    /* ============================================================
       JAM SLOT  (per jadwal dalam 1 baris guru)
    ============================================================ */
    .jam-slot-list {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .jam-slot {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 8px;
        padding: 0.28rem 0.65rem;
        font-size: 0.78rem;
        color: rgba(255,255,255,0.7);
        white-space: nowrap;
        width: fit-content;
    }

    .jam-slot i {
        color: rgba(74,222,128,0.55);
        font-size: 0.72rem;
    }

    .jam-slot-kelas {
        font-size: 0.7rem;
        color: rgba(34,211,238,0.75);
        font-weight: 600;
    }

    .jam-slot-mapel {
        font-size: 0.7rem;
        color: rgba(251,191,36,0.75);
        font-weight: 600;
    }

    /* ============================================================
       TANGGAL CELL
    ============================================================ */
    .tgl-text {
        font-size: 0.82rem;
        color: rgba(255,255,255,0.65);
        white-space: nowrap;
    }

    .tgl-hari {
        font-size: 0.7rem;
        color: rgba(74,222,128,0.55);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* ============================================================
       AKSI BUTTONS
    ============================================================ */
    .btn-aksi-jadwal {
        display: inline-flex; align-items: center; justify-content: center;
        width: 30px; height: 30px;
        border-radius: 8px; border: none; cursor: pointer;
        font-size: 0.82rem;
        transition: background 0.15s, transform 0.12s;
        padding: 0; flex-shrink: 0;
    }

    .btn-aksi-jadwal:hover { transform: translateY(-1px); }

    .btn-j-edit {
        background: rgba(74,222,128,0.12); color: #4ade80;
        border: 1px solid rgba(74,222,128,0.2);
    }

    .btn-j-edit:hover { background: rgba(74,222,128,0.22); }

    .btn-j-hapus {
        background: rgba(248,113,113,0.1); color: #f87171;
        border: 1px solid rgba(248,113,113,0.2);
    }

    .btn-j-hapus:hover { background: rgba(248,113,113,0.2); }

    /* ============================================================
       EMPTY STATE
    ============================================================ */
    .jadwal-empty {
        text-align: center;
        padding: 3.5rem 1.5rem;
    }

    .jadwal-empty-icon {
        font-size: 2.6rem;
        color: rgba(74,222,128,0.18);
        display: block;
        margin-bottom: 0.75rem;
    }

    .jadwal-empty-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: rgba(255,255,255,0.4);
        margin: 0 0 0.3rem;
    }

    .jadwal-empty-sub {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.25);
        margin: 0;
    }

    /* ============================================================
       HEADER TOTAL
    ============================================================ */
    .jadwal-header-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.8rem 1.25rem;
        border-bottom: 1px solid rgba(74,222,128,0.08);
        background: rgba(255,255,255,0.02);
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .jadwal-header-title {
        font-size: 0.82rem;
        font-weight: 600;
        color: rgba(255,255,255,0.7);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .jadwal-count-badge {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.12rem 0.5rem;
        border-radius: 50px;
        background: rgba(74,222,128,0.12);
        color: #4ade80;
        border: 1px solid rgba(74,222,128,0.2);
    }

    .jadwal-date-badge {
        font-size: 0.75rem;
        font-weight: 600;
        color: rgba(255,255,255,0.4);
        white-space: nowrap;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 767.98px) {
        .j-col-hide-sm { display: none; }
    }

    @media (max-width: 575.98px) {
        .j-col-hide-xs { display: none; }
        .jadwal-table  { min-width: 480px; }
    }
</style>

@php
    $HARI_LABEL = [
        'Senin'  => 'Senin',
        'Selasa' => 'Selasa',
        'Rabu'   => 'Rabu',
        'Kamis'  => 'Kamis',
        'Jumat'  => 'Jumat',
        'Sabtu'  => 'Sabtu',
    ];
    $DOW_TO_HARI = [1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu'];
    $hariSaatIni = $DOW_TO_HARI[date('N')] ?? 'Senin';
    $totalGuru = $grouped->count();
    $totalJam  = $grouped->sum(fn($g) => count($g['jam_range']));
@endphp

{{-- ================================================================
     HEADER BAR
================================================================ --}}
<div class="jadwal-header-bar">
    <div class="jadwal-header-title">
        <i class="bi bi-calendar2-week-fill" style="color:#4ade80;"></i>
        Jadwal {{ $hariAktif }}
        <span class="jadwal-count-badge">{{ $totalGuru }} guru · {{ $totalJam }} sesi</span>
    </div>
    @if($hariAktif === $hariSaatIni)
        <span class="jadwal-date-badge" style="color:rgba(74,222,128,0.6);">
            <i class="bi bi-circle-fill me-1" style="font-size:0.5rem;"></i>Hari ini
        </span>
    @endif
</div>

{{-- ================================================================
     TABLE / EMPTY
================================================================ --}}
<div class="jadwal-table-wrap">

    @if($grouped->isEmpty())
        <div class="jadwal-empty">
            <i class="bi bi-calendar2-x jadwal-empty-icon"></i>
            <p class="jadwal-empty-title">Tidak ada jadwal mengajar pada hari {{ $hariAktif }}.</p>
            <p class="jadwal-empty-sub">Klik tombol "+ Jadwal" untuk menambahkan jadwal baru.</p>
        </div>
    @else
        <table class="jadwal-table" aria-label="Daftar jadwal mengajar">
            <thead>
                <tr>
                    <th style="width:46px;">#</th>
                    <th>Nama Guru</th>
                    <th class="j-col-hide-xs">Mata Pelajaran</th>
                    <th class="j-col-hide-xs">Kelas</th>
                    <th>Jam Mengajar</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grouped as $no => $row)
                @php
                    /** @var \App\Models\User $guru */
                    $guru      = $row['guru'];
                    $mapelList = $row['mapel'];
                    $kelasList = $row['kelas'];
                    $jamList   = $row['jam_range'];
                @endphp
                <tr>
                    {{-- No --}}
                    <td style="color:rgba(255,255,255,0.3); font-size:0.77rem;">{{ $no + 1 }}</td>

                    {{-- Guru --}}
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($guru && $guru->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($guru->image))
                                <img src="{{ asset('storage/'.$guru->image) }}"
                                     alt="Foto {{ $guru->name }}"
                                     class="guru-avatar-sm">
                            @else
                                <span class="guru-avatar-init-sm" aria-hidden="true">
                                    {{ strtoupper(mb_substr($guru->name ?? 'G', 0, 1)) }}
                                </span>
                            @endif
                            <div style="min-width:0;">
                                <p class="guru-name-sm text-truncate" style="max-width:160px;"
                                   title="{{ $guru->name ?? '-' }}">
                                    {{ $guru->name ?? '-' }}
                                </p>
                                <p class="guru-username-sm">{{ '@'.($guru->username ?? '-') }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Mata Pelajaran (badges) --}}
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

                    {{-- Kelas (badges) --}}
                    <td class="j-col-hide-xs">
                        @if($kelasList->isNotEmpty())
                            <div class="badge-wrap">
                                @foreach($kelasList as $kls)
                                    <span class="badge-kelas">{{ $kls->nama_kelas }}</span>
                                @endforeach
                            </div>
                        @else
                            <span style="font-size:0.75rem;color:rgba(255,255,255,0.22);font-style:italic;">–</span>
                        @endif
                    </td>

                    {{-- Jam Mengajar (per sesi) --}}
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
                                    {{-- Tampilkan kelas & mapel spesifik tiap sesi jika guru punya lebih dari 1 --}}
                                    @if(count($jamList) > 1)
                                        <span class="jam-slot-kelas">
                                            {{ $jam['kelas']->nama_kelas ?? '-' }}
                                        </span>
                                        <span style="color:rgba(255,255,255,0.2);">·</span>
                                        <span class="jam-slot-mapel">
                                            {{ $jam['mapel']->nama_mapel ?? '-' }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </td>

                    {{-- Aksi --}}
                    <td>
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            @foreach($jamList as $jam)
                                @if(count($jamList) === 1)
                                    {{-- Satu sesi: tombol langsung di baris --}}
                                    <button
                                        type="button"
                                        class="btn-aksi-jadwal btn-j-edit"
                                        data-edit-id="{{ $jam['id'] }}"
                                        title="Edit jadwal"
                                        aria-label="Edit jadwal {{ $guru->name ?? '' }}">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn-aksi-jadwal btn-j-hapus"
                                        data-delete-id="{{ $jam['id'] }}"
                                        data-delete-label="{{ ($guru->name ?? '') }} – {{ ($jam['kelas']->nama_kelas ?? '') }}"
                                        title="Hapus jadwal"
                                        aria-label="Hapus jadwal {{ $guru->name ?? '' }}">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                @else
                                    {{-- Multi-sesi: satu tombol hapus per sesi saja --}}
                                    <button
                                        type="button"
                                        class="btn-aksi-jadwal btn-j-edit"
                                        data-edit-id="{{ $jam['id'] }}"
                                        title="Edit sesi {{ ($jam['kelas']->nama_kelas ?? '') }}"
                                        style="margin-bottom:0.2rem;"
                                        aria-label="Edit sesi {{ $jam['kelas']->nama_kelas ?? '' }}">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>
                                    <button
                                        type="button"
                                        class="btn-aksi-jadwal btn-j-hapus"
                                        data-delete-id="{{ $jam['id'] }}"
                                        data-delete-label="{{ ($guru->name ?? '') }} – {{ ($jam['kelas']->nama_kelas ?? '') }}"
                                        title="Hapus sesi {{ ($jam['kelas']->nama_kelas ?? '') }}"
                                        style="margin-bottom:0.2rem;"
                                        aria-label="Hapus sesi {{ $jam['kelas']->nama_kelas ?? '' }}">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                @endif
                            @endforeach
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
