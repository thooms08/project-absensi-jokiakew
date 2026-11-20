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

    .kartu-hadir::before    { background: #4ade80; }
    .kartu-izin::before     { background: #fbbf24; }
    .kartu-tidak::before    { background: #f87171; }
    .kartu-menunggu::before { background: rgba(255,255,255,.12); }
    .kartu-aktif::before    { background: rgba(74, 222, 128, .4); }
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

    .s-hadir  { background: rgba(34,197,94,.15);   color: #4ade80; border: 1px solid rgba(34,197,94,.3); }
    .s-izin   { background: rgba(251,191,36,.12);  color: #fbbf24; border: 1px solid rgba(251,191,36,.28); }
    .s-tidak  { background: rgba(248,113,113,.12); color: #f87171; border: 1px solid rgba(248,113,113,.25); }
    .s-tunggu { background: rgba(255,255,255,.05); color: rgba(226, 245, 234, .4); border: 1px solid rgba(255,255,255,.1); }
    .s-aktif  { background: rgba(74, 222, 128, .08); color: rgba(74,222,128,.65); border: 1px solid rgba(74,222,128,.18); }

    .kartu-alasan {
        font-size: .76rem; color: rgba(226, 245, 234, .45);
        background: rgba(255,255,255,.03); border-radius: 8px;
        padding: .45rem .65rem; font-style: italic;
    }

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
        <p>Tidak ada jadwal hari ini.</p>
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
                <i class="bi bi-qr-code-scan" style="font-size:.7rem;"></i>Menunggu Scan QR Guru
            </span>

        @elseif($belumMulai)
            <span class="status-badge s-tunggu">
                <i class="bi bi-hourglass-split" style="font-size:.7rem;"></i>Menunggu Jam {{ $jamMulai }}
            </span>
            <div class="kartu-countdown">
                Jadwal dimulai pukul <span>{{ $jamMulai }}</span>
            </div>

        @else
            <span class="status-badge s-tidak">
                <i class="bi bi-x-circle-fill" style="font-size:.7rem;"></i>Tidak Hadir
            </span>
        @endif

    </div>
    @endforeach
@endif
