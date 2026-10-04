<style>
    /* ============================================================
       TABLE
    ============================================================ */
    .kelas-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

    .kelas-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.855rem;
    }

    .kelas-table thead th {
        font-size: 0.68rem;
        font-weight: 700;
        color: rgba(74,222,128,0.6);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 0.85rem 1.1rem;
        border-bottom: 1px solid rgba(74,222,128,0.1);
        white-space: nowrap;
        background: rgba(255,255,255,0.02);
    }

    .kelas-table thead th:first-child { padding-left: 1.25rem; }
    .kelas-table thead th:last-child  { padding-right: 1.25rem; text-align: right; }

    .kelas-table tbody td {
        padding: 0.78rem 1.1rem;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.78);
        vertical-align: middle;
    }

    .kelas-table tbody td:first-child { padding-left: 1.25rem; }
    .kelas-table tbody td:last-child  { padding-right: 1.25rem; }

    .kelas-table tbody tr:last-child td { border-bottom: none; }
    .kelas-table tbody tr:hover td      { background: rgba(74,222,128,0.035); }

    /* ============================================================
       CELLS
    ============================================================ */
    .kelas-nama { font-weight: 600; color: #ffffff; }

    .wali-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.78rem;
        color: rgba(255,255,255,0.7);
    }

    .wali-badge-none {
        font-size: 0.78rem;
        color: rgba(255,255,255,0.28);
        font-style: italic;
    }

    /* Peran guru di kelas ini */
    .peran-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.2rem 0.6rem;
        border-radius: 50px;
        white-space: nowrap;
    }

    .peran-wali  {
        background: rgba(251,191,36,0.12);
        color: #fbbf24;
        border: 1px solid rgba(251,191,36,0.25);
    }

    .peran-guru  {
        background: rgba(74,222,128,0.12);
        color: #4ade80;
        border: 1px solid rgba(74,222,128,0.22);
    }

    /* Count chips */
    .count-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        border-radius: 50px;
        padding: 0.18rem 0.6rem;
        font-size: 0.72rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .count-chip-murid {
        background: rgba(34,211,238,0.1);
        color: #22d3ee;
        border: 1px solid rgba(34,211,238,0.18);
    }

    .count-chip-zero {
        background: rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.25);
        border: 1px solid rgba(255,255,255,0.07);
    }

    /* Tombol detail */
    .btn-k-detail {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px; height: 32px;
        border-radius: 8px;
        background: rgba(34,211,238,0.1);
        color: #22d3ee;
        border: 1px solid rgba(34,211,238,0.2);
        font-size: 0.85rem;
        cursor: pointer;
        transition: background 0.18s, transform 0.15s;
        text-decoration: none;
        flex-shrink: 0;
    }

    .btn-k-detail:hover {
        background: rgba(34,211,238,0.2);
        transform: translateY(-1px);
    }

    /* ============================================================
       EMPTY STATE
    ============================================================ */
    .kelas-empty {
        text-align: center;
        padding: 3rem 1.5rem;
    }

    .kelas-empty-icon {
        font-size: 2.5rem;
        color: rgba(74,222,128,0.2);
        display: block;
        margin-bottom: 0.75rem;
    }

    .kelas-empty-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: rgba(255,255,255,0.45);
        margin: 0 0 0.3rem;
    }

    .kelas-empty-text {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.28);
        margin: 0;
    }

    /* ============================================================
       RESPONSIVE
    ============================================================ */
    @media (max-width: 767.98px) { .k-col-hide-sm { display: none; } }
    @media (max-width: 575.98px) { .k-col-hide-xs { display: none; } }
</style>

@php $guruId = auth()->id(); @endphp

<div class="kelas-table-wrap">

    @if($kelas->isEmpty())
        <div class="kelas-empty">
            <i class="bi bi-door-open kelas-empty-icon"></i>
            <p class="kelas-empty-title">Anda belum terdaftar di kelas manapun.</p>
            <p class="kelas-empty-text">Hubungi admin untuk ditambahkan ke kelas.</p>
        </div>
    @else
        <table class="kelas-table" aria-label="Daftar kelas saya">
            <thead>
                <tr>
                    <th style="width:52px;">#</th>
                    <th>Nama Kelas</th>
                    <th class="k-col-hide-xs">Wali Kelas</th>
                    <th class="k-col-hide-sm">Peran Saya</th>
                    <th class="k-col-hide-sm" style="text-align:center;">Murid</th>
                    <th style="text-align:right;">Detail</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kelas as $i => $item)
                @php
                    /* Peran guru ini di kelas: wali_kelas atau guru_kelas */
                    $pesertaGuru = $item->peserta->firstWhere('user_id', $guruId);
                    $isWali      = $pesertaGuru && $pesertaGuru->label === 'wali_kelas';
                    $totalMurid  = $item->murid->count();
                @endphp
                <tr>
                    {{-- No --}}
                    <td style="color:rgba(255,255,255,0.3); font-size:0.78rem;">{{ $i + 1 }}</td>

                    {{-- Nama Kelas --}}
                    <td>
                        <span class="kelas-nama">{{ $item->nama_kelas }}</span>
                    </td>

                    {{-- Wali Kelas --}}
                    <td class="k-col-hide-xs">
                        @if($item->waliKelas && $item->waliKelas->user)
                            <span class="wali-badge">
                                <i class="bi bi-person-fill" style="color:#4ade80;font-size:0.8rem;"></i>
                                {{ $item->waliKelas->user->name }}
                            </span>
                        @else
                            <span class="wali-badge-none">Belum ditentukan</span>
                        @endif
                    </td>

                    {{-- Peran guru ini --}}
                    <td class="k-col-hide-sm">
                        @if($isWali)
                            <span class="peran-badge peran-wali">
                                <i class="bi bi-star-fill" style="font-size:0.55rem;"></i>
                                Wali Kelas
                            </span>
                        @else
                            <span class="peran-badge peran-guru">
                                <i class="bi bi-person-fill" style="font-size:0.55rem;"></i>
                                Guru
                            </span>
                        @endif
                    </td>

                    {{-- Total Murid --}}
                    <td class="k-col-hide-sm" style="text-align:center;">
                        <span class="count-chip {{ $totalMurid > 0 ? 'count-chip-murid' : 'count-chip-zero' }}">
                            <i class="bi bi-people-fill" style="font-size:0.65rem;"></i>
                            {{ $totalMurid }}
                        </span>
                    </td>

                    {{-- Aksi: hanya tombol Detail (tanpa edit/hapus) --}}
                    <td style="text-align:right; padding-right:1rem;">
                        <a href="{{ route('guru.data_kelas.detail', $item->uuid) }}"
                           class="btn-k-detail"
                           title="Detail kelas {{ $item->nama_kelas }}"
                           aria-label="Detail {{ $item->nama_kelas }}">
                            <i class="bi bi-eye-fill"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
