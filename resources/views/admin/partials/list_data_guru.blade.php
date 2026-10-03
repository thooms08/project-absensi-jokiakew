<style>
    /* ============================================================
       TAB NAV
    ============================================================ */
    .guru-tab-nav {
        display: flex;
        align-items: center;
        gap: 0;
        border-bottom: 1px solid rgba(74,222,128,0.12);
        padding: 0 1.25rem;
        flex-wrap: wrap;
    }

    .guru-tab-btn {
        position: relative;
        background: none;
        border: none;
        border-bottom: 2px solid transparent;
        color: rgba(255,255,255,0.45);
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.85rem 1rem;
        cursor: pointer;
        transition: color 0.18s, border-color 0.18s;
        white-space: nowrap;
        font-family: 'Inter', sans-serif;
        display: flex;
        align-items: center;
        gap: 0.45rem;
        margin-bottom: -1px;
    }

    .guru-tab-btn:hover {
        color: rgba(255,255,255,0.78);
    }

    .guru-tab-btn.active {
        color: #4ade80;
        border-bottom-color: #4ade80;
    }

    .guru-tab-badge {
        font-size: 0.65rem;
        font-weight: 700;
        padding: 0.12rem 0.48rem;
        border-radius: 50px;
        background: rgba(74,222,128,0.12);
        color: rgba(255,255,255,0.5);
        border: 1px solid rgba(74,222,128,0.15);
        line-height: 1.4;
    }

    .guru-tab-btn.active .guru-tab-badge {
        background: rgba(74,222,128,0.18);
        color: #4ade80;
        border-color: rgba(74,222,128,0.3);
    }

    .guru-tab-btn-nonaktif .guru-tab-badge {
        background: rgba(248,113,113,0.1);
        color: rgba(248,113,113,0.65);
        border-color: rgba(248,113,113,0.2);
    }

    .guru-tab-btn-nonaktif.active .guru-tab-badge {
        background: rgba(248,113,113,0.18);
        color: #f87171;
        border-color: rgba(248,113,113,0.35);
    }

    .guru-tab-btn-nonaktif.active {
        color: #f87171;
        border-bottom-color: #f87171;
    }

    /* ============================================================
       TABLE AREA
    ============================================================ */
    .guru-table-wrap {
        padding: 0;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .guru-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.84rem;
        min-width: 560px;
    }

    .guru-table thead th {
        font-size: 0.67rem;
        font-weight: 700;
        color: rgba(74,222,128,0.6);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 0.85rem 1rem;
        border-bottom: 1px solid rgba(74,222,128,0.1);
        white-space: nowrap;
        background: rgba(255,255,255,0.02);
    }

    .guru-table thead th:first-child { padding-left: 1.25rem; }
    .guru-table thead th:last-child  { padding-right: 1.25rem; }

    .guru-table tbody td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.78);
        vertical-align: middle;
    }

    .guru-table tbody td:first-child { padding-left: 1.25rem; }
    .guru-table tbody td:last-child  { padding-right: 1.25rem; }

    .guru-table tbody tr:last-child td { border-bottom: none; }

    .guru-table tbody tr:hover td {
        background: rgba(74,222,128,0.035);
    }

    /* ============================================================
       AVATAR
    ============================================================ */
    .guru-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(74,222,128,0.25);
        flex-shrink: 0;
    }

    .guru-avatar-initials {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0a7c4a, #4ade80);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.82rem;
        font-weight: 700;
        color: #064e2e;
        flex-shrink: 0;
        border: 2px solid rgba(74,222,128,0.2);
    }

    .guru-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: #ffffff;
        margin: 0 0 0.1rem;
        line-height: 1.3;
    }

    .guru-username {
        font-size: 0.73rem;
        color: rgba(74,222,128,0.65);
        margin: 0;
    }

    .guru-email {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.5);
    }

    /* ============================================================
       STATUS BADGE
    ============================================================ */
    .badge-aktif {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: rgba(74,222,128,0.12);
        color: #4ade80;
        border: 1px solid rgba(74,222,128,0.22);
        border-radius: 50px;
        padding: 0.2rem 0.65rem;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-nonaktif {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: rgba(248,113,113,0.1);
        color: #f87171;
        border: 1px solid rgba(248,113,113,0.22);
        border-radius: 50px;
        padding: 0.2rem 0.65rem;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
    }

    /* ============================================================
       TOMBOL AKSI
    ============================================================ */
    .btn-aksi {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 0.85rem;
        transition: background 0.18s, transform 0.15s;
        padding: 0;
        text-decoration: none;
        flex-shrink: 0;
    }

    .btn-aksi:hover { transform: translateY(-1px); }

    .btn-edit {
        background: rgba(74,222,128,0.12);
        color: #4ade80;
        border: 1px solid rgba(74,222,128,0.2);
    }

    .btn-edit:hover { background: rgba(74,222,128,0.22); }

    .btn-hapus {
        background: rgba(248,113,113,0.1);
        color: #f87171;
        border: 1px solid rgba(248,113,113,0.2);
    }

    .btn-hapus:hover { background: rgba(248,113,113,0.2); }

    .btn-restore {
        background: rgba(74,222,128,0.1);
        color: #4ade80;
        border: 1px solid rgba(74,222,128,0.18);
    }

    .btn-restore:hover { background: rgba(74,222,128,0.2); }

    .btn-force {
        background: rgba(248,113,113,0.08);
        color: #f87171;
        border: 1px solid rgba(248,113,113,0.18);
    }

    .btn-force:hover { background: rgba(248,113,113,0.18); }

    /* ============================================================
       EMPTY STATE
    ============================================================ */
    .empty-state {
        text-align: center;
        padding: 3rem 1.5rem;
    }

    .empty-state-icon {
        font-size: 2.5rem;
        color: rgba(74,222,128,0.2);
        display: block;
        margin-bottom: 0.75rem;
    }

    .empty-state-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: rgba(255,255,255,0.45);
        margin: 0 0 0.3rem;
    }

    .empty-state-text {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.28);
        margin: 0;
    }

    /* ============================================================
       NO FOTO DIHAPUS label
    ============================================================ */
    .deleted-at-text {
        font-size: 0.73rem;
        color: rgba(248,113,113,0.65);
    }

    /* ============================================================
       MAPEL BADGES
    ============================================================ */
    .mapel-badge-wrap {
        display: flex;
        flex-wrap: wrap;
        gap: 0.3rem;
    }

    .mapel-badge {
        display: inline-flex;
        align-items: center;
        background: rgba(251,191,36,0.1);
        color: #fbbf24;
        border: 1px solid rgba(251,191,36,0.22);
        border-radius: 50px;
        padding: 0.14rem 0.55rem;
        font-size: 0.68rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .mapel-empty-text {
        font-size: 0.75rem;
        color: rgba(255,255,255,0.25);
        font-style: italic;
    }

    /* ============================================================
       RESPONSIVE: sembunyikan kolom di mobile
    ============================================================ */
    @media (max-width: 767.98px) {
        .col-hide-sm { display: none; }
    }

    @media (max-width: 575.98px) {
        .col-hide-xs { display: none; }
        .guru-table  { min-width: 420px; }
    }
</style>

{{-- ================================================================
     TAB NAV
================================================================ --}}
<div class="guru-tab-nav">
    <button
        class="guru-tab-btn {{ $tab === 'aktif' ? 'active' : '' }}"
        data-tab="aktif"
        aria-label="Tab guru aktif"
    >
        <i class="bi bi-person-check-fill"></i>
        Guru Aktif
        @php $jumlahAktif = \App\Models\User::where('role','guru')->count(); @endphp
        <span class="guru-tab-badge">{{ $jumlahAktif }}</span>
    </button>

    <button
        class="guru-tab-btn guru-tab-btn-nonaktif {{ $tab === 'nonaktif' ? 'active' : '' }}"
        data-tab="nonaktif"
        aria-label="Tab guru tidak aktif"
    >
        <i class="bi bi-person-dash-fill"></i>
        Tidak Aktif
        @php $jumlahNonaktif = \App\Models\User::onlyTrashed()->where('role','guru')->count(); @endphp
        <span class="guru-tab-badge">{{ $jumlahNonaktif }}</span>
    </button>
</div>

{{-- ================================================================
     TABLE
================================================================ --}}
<div class="guru-table-wrap">

    @if($guru->isEmpty())

        <div class="empty-state">
            <i class="bi bi-{{ $tab === 'aktif' ? 'person-lines-fill' : 'person-slash' }} empty-state-icon"></i>
            <p class="empty-state-title">
                {{ $tab === 'aktif' ? 'Belum ada data guru aktif.' : 'Tidak ada guru yang dinonaktifkan.' }}
            </p>
            <p class="empty-state-text">
                {{ $tab === 'aktif' ? 'Klik tombol "+ Guru" untuk menambahkan guru baru.' : 'Guru yang dihapus akan muncul di sini.' }}
            </p>
        </div>

    @else

        <table class="guru-table" aria-label="Daftar {{ $tab === 'aktif' ? 'guru aktif' : 'guru tidak aktif' }}">
            <thead>
                <tr>
                    <th style="width:48px;">#</th>
                    <th>Nama Guru</th>
                    <th class="col-hide-sm">Email</th>
                    <th class="col-hide-sm">Mata Pelajaran</th>
                    <th class="col-hide-xs">Status</th>
                    @if($tab === 'nonaktif')
                        <th class="col-hide-sm">Dinonaktifkan</th>
                    @endif
                    <th style="width:100px; text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($guru as $index => $item)
                <tr>
                    {{-- Nomor --}}
                    <td style="color:rgba(255,255,255,0.3); font-size:0.78rem;">
                        {{ $index + 1 }}
                    </td>

                    {{-- Avatar + Nama --}}
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($item->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->image))
                                <img
                                    src="{{ asset('storage/' . $item->image) }}"
                                    alt="Foto {{ $item->name }}"
                                    class="guru-avatar"
                                    loading="lazy"
                                >
                            @else
                                <span class="guru-avatar-initials" aria-hidden="true">
                                    {{ strtoupper(mb_substr($item->name, 0, 1)) }}
                                </span>
                            @endif
                            <div style="min-width:0;">
                                <p class="guru-name text-truncate" style="max-width:180px;" title="{{ $item->name }}">
                                    {{ $item->name }}
                                </p>
                                <p class="guru-username">{{ '@' . $item->username }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Email --}}
                    <td class="col-hide-sm">
                        <span class="guru-email">{{ $item->email }}</span>
                    </td>

                    {{-- Mata Pelajaran --}}
                    <td class="col-hide-sm">
                        @if($item->mapel && $item->mapel->count())
                            <div class="mapel-badge-wrap">
                                @foreach($item->mapel as $mp)
                                    <span class="mapel-badge">{{ $mp->nama_mapel }}</span>
                                @endforeach
                            </div>
                        @else
                            <span class="mapel-empty-text">Belum diatur</span>
                        @endif
                    </td>

                    {{-- Status --}}
                    <td class="col-hide-xs">
                        @if($tab === 'aktif')
                            <span class="badge-aktif">
                                <i class="bi bi-circle-fill" style="font-size:0.4rem;"></i>
                                Aktif
                            </span>
                        @else
                            <span class="badge-nonaktif">
                                <i class="bi bi-circle-fill" style="font-size:0.4rem;"></i>
                                Tidak Aktif
                            </span>
                        @endif
                    </td>

                    {{-- Tanggal dinonaktifkan (tab nonaktif saja) --}}
                    @if($tab === 'nonaktif')
                    <td class="col-hide-sm">
                        <span class="deleted-at-text">
                            {{ $item->deleted_at ? $item->deleted_at->translatedFormat('d M Y') : '-' }}
                        </span>
                    </td>
                    @endif

                    {{-- Aksi --}}
                    <td>
                        <div class="d-flex align-items-center justify-content-end gap-1">
                            @if($tab === 'aktif')
                                {{-- Edit --}}
                                <button
                                    type="button"
                                    class="btn-aksi btn-edit"
                                    data-edit-id="{{ $item->id }}"
                                    title="Edit data guru"
                                    aria-label="Edit {{ $item->name }}"
                                >
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                {{-- Nonaktifkan --}}
                                <button
                                    type="button"
                                    class="btn-aksi btn-hapus"
                                    data-delete-id="{{ $item->id }}"
                                    data-delete-nama="{{ $item->name }}"
                                    title="Nonaktifkan guru"
                                    aria-label="Nonaktifkan {{ $item->name }}"
                                >
                                    <i class="bi bi-person-dash-fill"></i>
                                </button>
                            @else
                                {{-- Pulihkan --}}
                                <button
                                    type="button"
                                    class="btn-aksi btn-restore"
                                    data-restore-id="{{ $item->id }}"
                                    data-restore-nama="{{ $item->name }}"
                                    title="Aktifkan kembali"
                                    aria-label="Aktifkan kembali {{ $item->name }}"
                                >
                                    <i class="bi bi-person-check-fill"></i>
                                </button>
                                {{-- Hapus permanen --}}
                                <button
                                    type="button"
                                    class="btn-aksi btn-force"
                                    data-force-id="{{ $item->id }}"
                                    data-force-nama="{{ $item->name }}"
                                    title="Hapus permanen"
                                    aria-label="Hapus permanen {{ $item->name }}"
                                >
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                            @endif
                        </div>
                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>

    @endif

</div>
