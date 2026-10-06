<style>
    .kartu-absen {
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(74, 222, 128, .13);
        border-radius: 16px 0 16px 0;
        padding: 1.2rem 1.25rem 1rem;
        display: flex; flex-direction: column; gap: .85rem;
        position: relative; overflow: hidden;
        transition: border-color .2s, box-shadow .2s;
    }

    .kartu-absen::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; border-radius: 4px 0 0 4px;
    }

    .kartu-aktif::before    { background: #4ade80; }
    .kartu-hadir::before    { background: #4ade80; }
    .kartu-izin::before     { background: #fbbf24; }
    .kartu-tidak::before    { background: #f87171; }
    .kartu-menunggu::before { background: rgba(255,255,255,.12); }
    .kartu-lewat::before    { background: rgba(255,255,255,.12); }

    .kartu-absen:hover { border-color: rgba(74, 222, 128, .3); box-shadow: 0 4px 20px rgba(0,0,0,.2); }

    .kartu-jam {
        display: inline-flex; align-items: center; gap: .35rem;
        font-size: .72rem; font-weight: 700; color: rgba(74, 222, 128, .75);
        background: rgba(74, 222, 128, .08); border: 1px solid rgba(74, 222, 128, .15);
        border-radius: 6px; padding: .18rem .55rem; width: fit-content;
    }

    .kartu-kelas { font-size: 1.05rem; font-weight: 800; color: #fff; margin: 0; }

    .kartu-mapel {
        display: inline-flex; align-items: center; gap: .3rem;
        background: rgba(74, 222, 128, .1); border: 1px solid rgba(74, 222, 128, .18);
        border-radius: 50px; padding: .15rem .6rem;
        font-size: .73rem; font-weight: 600; color: #4ade80; width: fit-content;
    }

    .kartu-guru {
        display: inline-flex; align-items: center; gap: .3rem;
        font-size: .73rem; color: rgba(226, 245, 234, .4);
        margin-top: .1rem;
    }

    .status-badge {
        display: inline-flex; align-items: center; gap: .35rem;
        border-radius: 50px; padding: .25rem .75rem;
        font-size: .75rem; font-weight: 700; width: fit-content;
    }

    .s-hadir   { background: rgba(34,197,94,.15);  color: #4ade80; border: 1px solid rgba(34,197,94,.3); }
    .s-izin    { background: rgba(251,191,36,.12); color: #fbbf24; border: 1px solid rgba(251,191,36,.28); }
    .s-tidak   { background: rgba(248,113,113,.12); color: #f87171; border: 1px solid rgba(248,113,113,.25); }
    .s-aktif   { background: rgba(74, 222, 128, .12); color: #4ade80; border: 1px solid rgba(74, 222, 128, .25); animation: pulseGreen 2s ease-in-out infinite; }
    .s-tunggu  { background: rgba(255,255,255,.05); color: rgba(226, 245, 234, .4); border: 1px solid rgba(255,255,255,.1); }

    @keyframes pulseGreen {
        0%,100% { box-shadow: 0 0 0 0 rgba(74, 222, 128, 0); }
        50%      { box-shadow: 0 0 0 4px rgba(74, 222, 128, .15); }
    }

    .kartu-aksi { display: flex; gap: .6rem; flex-wrap: wrap; margin-top: .25rem; }

    .btn-hadir-m {
        flex:1; min-width:100px; display: inline-flex; align-items: center; justify-content: center; gap: .45rem;
        background: linear-gradient(135deg, #16a34a, #4ade80);
        border: none; border-radius: 10px; color: #fff; font-size: .875rem; font-weight: 600;
        padding: .6rem 1rem; cursor: pointer; font-family: 'Inter',sans-serif;
        transition: opacity .18s, transform .15s;
    }

    .btn-hadir-m:hover { opacity: .88; transform: translateY(-1px); }

    .btn-izin-m {
        flex:1; min-width:100px; display: inline-flex; align-items: center; justify-content: center; gap: .45rem;
        background: rgba(251,191,36,.1); border: 1px solid rgba(251,191,36,.28);
        border-radius: 10px; color: #fbbf24; font-size: .875rem; font-weight: 600;
        padding: .6rem 1rem; cursor: pointer; font-family: 'Inter',sans-serif;
        transition: background .18s, transform .15s;
    }

    .btn-izin-m:hover { background: rgba(251,191,36,.18); transform: translateY(-1px); }

    .btn-disabled-m {
        flex:1; display: inline-flex; align-items: center; justify-content: center; gap: .45rem;
        background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
        border-radius: 10px; color: rgba(226, 245, 234, .25); font-size: .82rem; font-weight: 500;
        padding: .6rem 1rem; cursor: not-allowed; font-family: 'Inter',sans-serif;
    }

    .kartu-alasan { font-size: .76rem; color: rgba(226, 245, 234, .45); background: rgba(255,255,255,.03); border-radius: 8px; padding: .45rem .65rem; font-style: italic; }

    .kartu-countdown { font-size: .72rem; color: rgba(226, 245, 234, .3); }
    .kartu-countdown span { color: rgba(74, 222, 128, .7); font-weight: 600; }

    .kartu-empty {
        grid-column: 1/-1; text-align: center; padding: 4rem 1.5rem;
    }

    .kartu-empty i { font-size: 3rem; color: rgba(74, 222, 128, .18); display: block; margin-bottom: 1rem; }
    .kartu-empty p { font-size: .95rem; font-weight: 600; color: rgba(226, 245, 234, .38); margin: 0 0 .4rem; }
    .kartu-empty small { font-size: .8rem; color: rgba(226, 245, 234, .22); }
</style>

@if($kartuList->isEmpty())
    <div class="kartu-empty">
        <i class="bi bi-calendar2-x"></i>
        <p>Tidak ada jadwal mengajar hari ini.</p>
        <small>Silakan cek kembali besok.</small>
    </div>
@else
    @foreach($kartuList as $item)
    @php
        $jadwal       = $item['jadwal'];
        $absen        = $item['absen'];
        $dalamJendela = $item['dalam_jendela'];
        $belumMulai   = $item['belum_mulai'];

        $jamMulai    = substr($jadwal->waktu_mulai, 0, 5);
        $jamBerakhir = substr($jadwal->waktu_berakhir, 0, 5);
        $jamLabel    = $jamMulai . ' – ' . $jamBerakhir;

        $cls = $absen
            ? 'kartu-' . $absen->status
            : ($dalamJendela ? 'kartu-aktif' : ($belumMulai ? 'kartu-menunggu' : 'kartu-lewat'));
    @endphp

    <div class="kartu-absen {{ $cls }}">

        <div class="kartu-jam">
            <i class="bi bi-clock" style="font-size:.7rem;"></i>{{ $jamLabel }}
        </div>

        <div>
            <p class="kartu-kelas">{{ $jadwal->kelas?->nama_kelas ?? '-' }}</p>
            <span class="kartu-mapel">
                <i class="bi bi-book-fill" style="font-size:.6rem;"></i>
                {{ $jadwal->mapel?->nama_mapel ?? '-' }}
            </span>
            <div class="kartu-guru">
                <i class="bi bi-person-fill" style="font-size:.7rem;"></i>
                {{ $jadwal->guru?->name ?? '-' }}
            </div>
        </div>

        @if($absen)
            @if($absen->status === 'hadir')
                <span class="status-badge s-hadir">
                    <i class="bi bi-check-circle-fill" style="font-size:.7rem;"></i>Hadir
                </span>
            @elseif($absen->status === 'izin')
                <span class="status-badge s-izin">
                    <i class="bi bi-envelope-fill" style="font-size:.7rem;"></i>Izin
                </span>
                @if($absen->alasan)
                    <div class="kartu-alasan">
                        <i class="bi bi-chat-quote me-1" style="font-size:.7rem;"></i>"{{ $absen->alasan }}"
                    </div>
                @endif
            @else
                <span class="status-badge s-tidak">
                    <i class="bi bi-x-circle-fill" style="font-size:.7rem;"></i>Tidak Hadir
                </span>
            @endif

        @elseif($dalamJendela)
            <span class="status-badge s-aktif">
                <i class="bi bi-circle-fill" style="font-size:.5rem;"></i>Waktu Absen Aktif
            </span>
            <div class="kartu-aksi">
                <button type="button" class="btn-hadir-m"
                        data-btn-hadir="{{ $jadwal->id }}"
                        data-kelas="{{ $jadwal->kelas?->nama_kelas }}"
                        data-mapel="{{ $jadwal->mapel?->nama_mapel }}"
                        data-jam="{{ $jamLabel }}">
                    <i class="bi bi-camera-fill"></i> Hadir
                </button>
                <button type="button" class="btn-izin-m"
                        data-btn-izin="{{ $jadwal->id }}"
                        data-kelas="{{ $jadwal->kelas?->nama_kelas }}"
                        data-mapel="{{ $jadwal->mapel?->nama_mapel }}"
                        data-jam="{{ $jamLabel }}">
                    <i class="bi bi-envelope-fill"></i> Izin
                </button>
            </div>

        @elseif($belumMulai)
            <span class="status-badge s-tunggu">
                <i class="bi bi-hourglass-split" style="font-size:.7rem;"></i>Menunggu Jam {{ $jamMulai }}
            </span>
            <div class="kartu-countdown">
                Absen tersedia mulai pukul <span>{{ $jamMulai }}</span>
            </div>
            <div class="kartu-aksi">
                <span class="btn-disabled-m"><i class="bi bi-lock-fill"></i> Belum Bisa Absen</span>
            </div>

        @else
            <span class="status-badge s-tidak">
                <i class="bi bi-x-circle-fill" style="font-size:.7rem;"></i>Tidak Hadir
            </span>
        @endif

    </div>
    @endforeach
@endif