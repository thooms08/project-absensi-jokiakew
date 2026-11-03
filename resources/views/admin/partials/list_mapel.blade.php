<style>
    .mapel-table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .mapel-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.855rem;
    }

    .mapel-table thead th {
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

    .mapel-table tbody td {
        padding: 0.78rem 1.1rem;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.78);
        vertical-align: middle;
    }

    .mapel-table tbody tr:last-child td { border-bottom: none; }

    .mapel-table tbody tr:hover td { background: rgba(74,222,128,0.035); }

    /* Nomor */
    .mapel-num {
        color: rgba(255,255,255,0.3);
        font-size: 0.78rem;
        width: 52px;
    }

    /* Nama */
    .mapel-nama {
        font-weight: 600;
        color: #ffffff;
    }

    /* Aksi */
    .btn-aksi-mp {
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
        flex-shrink: 0;
    }

    .btn-aksi-mp:hover { transform: translateY(-1px); }

    .btn-mp-edit {
        background: rgba(74,222,128,0.12);
        color: #4ade80;
        border: 1px solid rgba(74,222,128,0.2);
    }

    .btn-mp-edit:hover { background: rgba(74,222,128,0.22); }

    .btn-mp-hapus {
        background: rgba(248,113,113,0.1);
        color: #f87171;
        border: 1px solid rgba(248,113,113,0.2);
    }

    .btn-mp-hapus:hover { background: rgba(248,113,113,0.2); }

    /* Empty state */
    .mapel-empty {
        text-align: center;
        padding: 3rem 1.5rem;
    }

    .mapel-empty-icon {
        font-size: 2.5rem;
        color: rgba(74,222,128,0.2);
        display: block;
        margin-bottom: 0.75rem;
    }

    .mapel-empty-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: rgba(255,255,255,0.45);
        margin: 0 0 0.3rem;
    }

    .mapel-empty-text {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.28);
        margin: 0;
    }

    /* Baris tersembunyi (limit 20) */
    .mapel-row-hidden { display: none; }
    .btn-lihat-semua-wrap {
        text-align: center;
        padding: .9rem 1.25rem;
        border-top: 1px solid rgba(74,222,128,.08);
    }
    .btn-lihat-semua {
        display: inline-flex; align-items: center; gap: .45rem;
        background: rgba(74,222,128,.07);
        border: 1px solid rgba(74,222,128,.2);
        border-radius: 10px;
        color: rgba(74,222,128,.85);
        font-size: .8rem; font-weight: 600;
        padding: .48rem 1.1rem; cursor: pointer;
        font-family: 'Inter', sans-serif;
        transition: background .18s;
    }
    .btn-lihat-semua:hover { background: rgba(74,222,128,.14); }
</style>

<div class="mapel-table-wrap">

    @if($mapel->isEmpty())

        <div class="mapel-empty">
            <i class="bi bi-book mapel-empty-icon"></i>
            <p class="mapel-empty-title">Belum ada data mata pelajaran.</p>
            <p class="mapel-empty-text">Klik tombol "+ Mapel" untuk menambahkan mata pelajaran baru.</p>
        </div>

    @else

        <table class="mapel-table" aria-label="Daftar mata pelajaran">
            <thead>
                <tr>
                    <th class="mapel-num">#</th>
                    <th>Nama Mata Pelajaran</th>
                    <th style="width:90px; text-align:right; padding-right:1.25rem;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mapel as $index => $item)
                <tr class="{{ $index >= 20 ? 'mapel-row-hidden' : '' }}">
                    <td class="mapel-num">{{ $index + 1 }}</td>

                    <td>
                        <span class="mapel-nama">{{ $item->nama_mapel }}</span>
                    </td>

                    <td>
                        <div class="d-flex align-items-center justify-content-end gap-1"
                             style="padding-right:0.25rem;">
                            <button
                                type="button"
                                class="btn-aksi-mp btn-mp-edit"
                                data-edit-id="{{ $item->id }}"
                                title="Edit"
                                aria-label="Edit {{ $item->nama_mapel }}"
                            >
                                <i class="bi bi-pencil-fill"></i>
                            </button>
                            <button
                                type="button"
                                class="btn-aksi-mp btn-mp-hapus"
                                data-delete-id="{{ $item->id }}"
                                data-delete-nama="{{ $item->nama_mapel }}"
                                title="Hapus"
                                aria-label="Hapus {{ $item->nama_mapel }}"
                            >
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

@if($mapel->count() > 20)
<div class="btn-lihat-semua-wrap" id="mapelLihatWrap">
    <button type="button" class="btn-lihat-semua" id="btnMapelLihatSemua">
        <i class="bi bi-chevron-down"></i>
        Lihat Semua Data ({{ $mapel->count() }})
    </button>
</div>
@endif

<script>
(function(){
    var btn = document.getElementById('btnMapelLihatSemua');
    if (!btn) return;
    btn.addEventListener('click', function () {
        document.querySelectorAll('.mapel-row-hidden').forEach(function (r) { r.style.display = ''; });
        document.getElementById('mapelLihatWrap').style.display = 'none';
    });
}());
</script>
