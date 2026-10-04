<style>
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

    .kelas-table tbody td {
        padding: 0.78rem 1.1rem;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.78);
        vertical-align: middle;
    }

    .kelas-table tbody tr:last-child td { border-bottom: none; }
    .kelas-table tbody tr:hover td { background: rgba(74,222,128,0.035); }

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

    /* ============================================================
       COUNT CHIPS
    ============================================================ */
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

    .count-chip-guru {
        background: rgba(74,222,128,0.1);
        color: #4ade80;
        border: 1px solid rgba(74,222,128,0.2);
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

    .btn-aksi-kelas {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px; height: 32px;
        border-radius: 8px; border: none;
        cursor: pointer; font-size: 0.85rem;
        transition: background 0.18s, transform 0.15s;
        padding: 0; flex-shrink: 0; text-decoration: none;
    }

    .btn-aksi-kelas:hover { transform: translateY(-1px); }

    .btn-k-detail {
        background: rgba(34,211,238,0.1);
        color: #22d3ee;
        border: 1px solid rgba(34,211,238,0.2);
    }

    .btn-k-detail:hover { background: rgba(34,211,238,0.2); }

    .btn-k-edit {
        background: rgba(74,222,128,0.12);
        color: #4ade80;
        border: 1px solid rgba(74,222,128,0.2);
    }

    .btn-k-edit:hover { background: rgba(74,222,128,0.22); }

    .btn-k-hapus {
        background: rgba(248,113,113,0.1);
        color: #f87171;
        border: 1px solid rgba(248,113,113,0.2);
    }

    .btn-k-hapus:hover { background: rgba(248,113,113,0.2); }

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

    @media (max-width: 575.98px) {
        .k-col-hide-xs { display: none; }
    }

    @media (max-width: 767.98px) {
        .k-col-hide-sm { display: none; }
    }
</style>

<div class="kelas-table-wrap">

    @if($kelas->isEmpty())
        <div class="kelas-empty">
            <i class="bi bi-door-open kelas-empty-icon"></i>
            <p class="kelas-empty-title">Belum ada data kelas.</p>
            <p class="kelas-empty-text">Klik tombol "+ Kelas" untuk menambahkan kelas baru.</p>
        </div>
    @else
        <table class="kelas-table" aria-label="Daftar kelas">
            <thead>
                <tr>
                    <th style="width:52px;">#</th>
                    <th>Nama Kelas</th>
                    <th class="k-col-hide-xs">Wali Kelas</th>
                    <th class="k-col-hide-sm" style="text-align:center;">Guru</th>
                    <th class="k-col-hide-sm" style="text-align:center;">Murid</th>
                    <th style="width:110px; text-align:right; padding-right:1.25rem;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kelas as $i => $item)
                @php
                    $totalGuru  = $item->guru->count();
                    $totalMurid = $item->murid->count();
                @endphp
                <tr>
                    <td style="color:rgba(255,255,255,0.3); font-size:0.78rem;">{{ $i + 1 }}</td>

                    <td>
                        <span class="kelas-nama">{{ $item->nama_kelas }}</span>
                    </td>

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

                    {{-- Total Guru --}}
                    <td class="k-col-hide-sm" style="text-align:center;">
                        <span class="count-chip {{ $totalGuru > 0 ? 'count-chip-guru' : 'count-chip-zero' }}">
                            <i class="bi bi-person-lines-fill" style="font-size:0.65rem;"></i>
                            {{ $totalGuru }}
                        </span>
                    </td>

                    {{-- Total Murid --}}
                    <td class="k-col-hide-sm" style="text-align:center;">
                        <span class="count-chip {{ $totalMurid > 0 ? 'count-chip-murid' : 'count-chip-zero' }}">
                            <i class="bi bi-people-fill" style="font-size:0.65rem;"></i>
                            {{ $totalMurid }}
                        </span>
                    </td>

                    <td>
                        <div class="d-flex align-items-center justify-content-end gap-1"
                             style="padding-right:0.25rem;">
                            <a href="{{ route('admin.kelas.detail', $item->uuid) }}"
                               class="btn-aksi-kelas btn-k-detail"
                               title="Detail kelas"
                               aria-label="Detail {{ $item->nama_kelas }}">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <button type="button"
                                    class="btn-aksi-kelas btn-k-edit"
                                    data-edit-id="{{ $item->id }}"
                                    title="Edit kelas"
                                    aria-label="Edit {{ $item->nama_kelas }}">
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button type="button"
                                    class="btn-aksi-kelas btn-k-hapus"
                                    data-delete-id="{{ $item->id }}"
                                    data-delete-nama="{{ $item->nama_kelas }}"
                                    title="Hapus kelas"
                                    aria-label="Hapus {{ $item->nama_kelas }}">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
