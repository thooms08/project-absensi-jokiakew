<style>
    /* ============================================================
       KARTU JADWAL
    ============================================================ */
    .kartu-jadwal {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(74,222,128,0.13);
        border-radius: 16px 0 16px 0;
        padding: 1.2rem 1.25rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
        transition: border-color 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
    }

    /* Strip warna kiri sesuai status */
    .kartu-jadwal::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 4px;
        border-radius: 4px 0 0 4px;
    }

    .kartu-belum-mulai::before  { background: rgba(255,255,255,0.15); }
    .kartu-dalam-jendela::before{ background: #22c55e; }
    .kartu-hadir::before        { background: #22c55e; }
    .kartu-izin::before         { background: #fbbf24; }
    .kartu-tidak::before        { background: #f87171; }
    .kartu-sudah-lewat::before  { background: rgba(255,255,255,0.15); }

    .kartu-jadwal:hover {
        border-color: rgba(74,222,128,0.28);
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    /* ---- Jam badge ---- */
    .kartu-jam {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.72rem;
        font-weight: 700;
        color: rgba(74,222,128,0.75);
        background: rgba(74,222,128,0.08);
        border: 1px solid rgba(74,222,128,0.15);
        border-radius: 6px;
        padding: 0.18rem 0.55rem;
        width: fit-content;
    }

    /* ---- Nama kelas ---- */
    .kartu-kelas {
        font-size: 1.05rem;
        font-weight: 800;
        color: #ffffff;
        margin: 0;
        line-height: 1.3;
    }

    /* ---- Mapel badge ---- */
    .kartu-mapel {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: rgba(251,191,36,0.1);
        border: 1px solid rgba(251,191,36,0.2);
        border-radius: 50px;
        padding: 0.15rem 0.6rem;
        font-size: 0.73rem;
        font-weight: 600;
        color: #fbbf24;
        width: fit-content;
    }

    /* ---- Status badge ---- */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        border-radius: 50px;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 700;
        width: fit-content;
    }

    .status-hadir {
        background: rgba(34,197,94,0.15);
        color: #4ade80;
        border: 1px solid rgba(34,197,94,0.3);
    }

    .status-izin {
        background: rgba(251,191,36,0.12);
        color: #fbbf24;
        border: 1px solid rgba(251,191,36,0.28);
    }

    .status-tidak {
        background: rgba(248,113,113,0.12);
        color: #f87171;
        border: 1px solid rgba(248,113,113,0.25);
    }

    .status-menunggu {
        background: rgba(255,255,255,0.05);
        color: rgba(255,255,255,0.4);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .status-aktif {
        background: rgba(34,197,94,0.12);
        color: #4ade80;
        border: 1px solid rgba(34,197,94,0.25);
        animation: pulseGreen 2s ease-in-out infinite;
    }

    @keyframes pulseGreen {
        0%, 100% { box-shadow: 0 0 0 0 rgba(34,197,94,0); }
        50%       { box-shadow: 0 0 0 4px rgba(34,197,94,0.15); }
    }

    /* ---- Tombol aksi ---- */
    .kartu-aksi {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
        margin-top: 0.25rem;
    }

    .btn-kartu-hadir {
        flex: 1;
        min-width: 100px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        background: linear-gradient(135deg, #0a7c4a, #22c55e);
        border: none;
        border-radius: 10px;
        color: #fff;
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.6rem 1rem;
        cursor: pointer;
        transition: opacity 0.18s, transform 0.15s;
        font-family: 'Inter', sans-serif;
    }

    .btn-kartu-hadir:hover {
        opacity: 0.88;
        transform: translateY(-1px);
    }

    .btn-kartu-izin {
        flex: 1;
        min-width: 100px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        background: rgba(251,191,36,0.1);
        border: 1px solid rgba(251,191,36,0.28);
        border-radius: 10px;
        color: #fbbf24;
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.6rem 1rem;
        cursor: pointer;
        transition: background 0.18s, transform 0.15s;
        font-family: 'Inter', sans-serif;
    }

    .btn-kartu-izin:hover {
        background: rgba(251,191,36,0.18);
        transform: translateY(-1px);
    }

    .btn-kartu-disabled {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.45rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 10px;
        color: rgba(255,255,255,0.25);
        font-size: 0.82rem;
        font-weight: 500;
        padding: 0.6rem 1rem;
        cursor: not-allowed;
        font-family: 'Inter', sans-serif;
    }

    /* ---- Info keterangan izin ---- */
    .kartu-alasan {
        font-size: 0.76rem;
        color: rgba(255,255,255,0.45);
        background: rgba(255,255,255,0.03);
        border-radius: 8px;
        padding: 0.45rem 0.65rem;
        margin-top: 0.1rem;
        font-style: italic;
    }

    /* ---- Empty state ---- */
    .kartu-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 4rem 1.5rem;
    }

    .kartu-empty-icon {
        font-size: 3rem;
        color: rgba(74,222,128,0.18);
        display: block;
        margin-bottom: 1rem;
    }

    .kartu-empty-title {
        font-size: 1rem;
        font-weight: 600;
        color: rgba(255,255,255,0.4);
        margin: 0 0 0.4rem;
    }

    .kartu-empty-sub {
        font-size: 0.82rem;
        color: rgba(255,255,255,0.25);
        margin: 0;
    }

    /* ---- Timer countdown ---- */
    .kartu-countdown {
        font-size: 0.72rem;
        color: rgba(255,255,255,0.3);
        margin-top: 0.1rem;
    }

    .kartu-countdown span {
        color: rgba(74,222,128,0.6);
        font-weight: 600;
    }
</style>

@php
    use Carbon\Carbon;

    $hariMap = [1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu',0=>'Minggu'];
    $hariIniLabel = $hariMap[date('w')] ?? '-';
@endphp

@if($kartuList->isEmpty())
    <div class="kartu-empty">
        <i class="bi bi-calendar2-x kartu-empty-icon"></i>
        <p class="kartu-empty-title">Tidak ada jadwal mengajar hari ini.</p>
        <p class="kartu-empty-sub">
            @if(in_array(date('N'), [7]))
                Hari Minggu tidak ada jadwal mengajar.
            @else
                Tidak ada jadwal mengajar pada hari {{ $hariIniLabel }}.
            @endif
        </p>
    </div>
@else
    @foreach($kartuList as $item)
    @php
        /** @var \App\Models\JadwalMengajar $jadwal */
        $jadwal       = $item['jadwal'];
        $absen        = $item['absen'];
        $dalamJendela = $item['dalam_jendela'];
        $belumMulai   = $item['belum_mulai'];
        $sudahLewat   = $item['sudah_lewat'];

        $jamMulai    = substr($jadwal->waktu_mulai, 0, 5);
        $jamBerakhir = substr($jadwal->waktu_berakhir, 0, 5);
        $jamLabel    = $jamMulai . ' – ' . $jamBerakhir;

        /* Tentukan class kartu */
        if ($absen) {
            $kartuClass = 'kartu-' . $absen->status;
        } elseif ($dalamJendela) {
            $kartuClass = 'kartu-dalam-jendela';
        } elseif ($sudahLewat) {
            $kartuClass = 'kartu-sudah-lewat';
        } else {
            $kartuClass = 'kartu-belum-mulai';
        }
    @endphp

    <div class="kartu-jadwal {{ $kartuClass }}">

        {{-- Jam --}}
        <div class="kartu-jam">
            <i class="bi bi-clock" style="font-size:0.7rem;"></i>
            {{ $jamLabel }}
        </div>

        {{-- Kelas & Mapel --}}
        <div>
            <p class="kartu-kelas">{{ $jadwal->kelas->nama_kelas ?? '-' }}</p>
            <span class="kartu-mapel">
                <i class="bi bi-book-fill" style="font-size:0.6rem;"></i>
                {{ $jadwal->mapel->nama_mapel ?? '-' }}
            </span>
        </div>

        {{-- Status & Aksi --}}
        @if($absen)
            {{-- Sudah absen --}}
            @if($absen->status === 'hadir')
                <span class="status-badge status-hadir">
                    <i class="bi bi-check-circle-fill" style="font-size:0.7rem;"></i>
                    Hadir
                </span>
            @elseif($absen->status === 'izin')
                <span class="status-badge status-izin">
                    <i class="bi bi-envelope-fill" style="font-size:0.7rem;"></i>
                    Izin
                </span>
                @if($absen->alasan)
                    <div class="kartu-alasan">
                        <i class="bi bi-chat-quote" style="font-size:0.7rem;"></i>
                        "{{ $absen->alasan }}"
                    </div>
                @endif
            @elseif($absen->status === 'tidak')
                <span class="status-badge status-tidak">
                    <i class="bi bi-x-circle-fill" style="font-size:0.7rem;"></i>
                    Tidak Hadir
                </span>
                @if($absen->alasan)
                    <div class="kartu-alasan">{{ $absen->alasan }}</div>
                @endif
            @endif

        @elseif($dalamJendela)
            {{-- Waktunya absen sekarang --}}
            <span class="status-badge status-aktif">
                <i class="bi bi-circle-fill" style="font-size:0.5rem;"></i>
                Waktu Absen Aktif
            </span>
            <div class="kartu-aksi">
                <button
                    type="button"
                    class="btn-kartu-hadir"
                    data-btn-hadir="{{ $jadwal->id }}"
                    data-kelas="{{ $jadwal->kelas->nama_kelas ?? '' }}"
                    data-mapel="{{ $jadwal->mapel->nama_mapel ?? '' }}"
                    data-jam="{{ $jamLabel }}"
                >
                    <i class="bi bi-camera-fill"></i> Hadir
                </button>
                <button
                    type="button"
                    class="btn-kartu-izin"
                    data-btn-izin="{{ $jadwal->id }}"
                    data-kelas="{{ $jadwal->kelas->nama_kelas ?? '' }}"
                    data-mapel="{{ $jadwal->mapel->nama_mapel ?? '' }}"
                    data-jam="{{ $jamLabel }}"
                >
                    <i class="bi bi-envelope-fill"></i> Izin
                </button>
            </div>

        @elseif($belumMulai)
            {{-- Belum waktunya --}}
            <span class="status-badge status-menunggu">
                <i class="bi bi-hourglass-split" style="font-size:0.7rem;"></i>
                Menunggu Jam {{ $jamMulai }}
            </span>
            <div class="kartu-countdown">
                Absen tersedia mulai pukul <span>{{ $jamMulai }}</span>
            </div>
            <div class="kartu-aksi">
                <span class="btn-kartu-disabled">
                    <i class="bi bi-lock-fill"></i> Belum Bisa Absen
                </span>
            </div>

        @else
            {{-- Waktu sudah lewat, belum ada record (edge case) --}}
            <span class="status-badge status-tidak">
                <i class="bi bi-x-circle-fill" style="font-size:0.7rem;"></i>
                Tidak Hadir
            </span>
        @endif

    </div>
    @endforeach
@endif
