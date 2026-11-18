<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AbsenGuru;
use App\Models\AbsenMurid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminSekolahController extends Controller
{
    /* ================================================================
       GET jenis sekolah aktif dari session
    ================================================================ */
    public function getJenis()
    {
        $jenis = session('admin_jenis_sekolah', 'smk');
        return response()->json(['jenis' => $jenis]);
    }

    /* ================================================================
       SET jenis sekolah aktif ke session
    ================================================================ */
    public function setJenis(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:smk,smp',
        ]);

        $jenis = $request->input('jenis');
        session(['admin_jenis_sekolah' => $jenis]);

        return response()->json([
            'success'            => true,
            'jenis'              => $jenis,
            'label'              => strtoupper($jenis),
            'register_murid_url' => route('register.murid.jenis', ['jenis' => $jenis]),
            'register_guru_url'  => route('register.guru.jenis',  ['jenis' => $jenis]),
        ]);
    }

    /* ================================================================
       DASHBOARD DATA  –  semua angka untuk index.blade.php
       Query difilter berdasarkan jenis sekolah aktif di session.
    ================================================================ */
    public function dashboardData(Request $request)
    {
        \Carbon\Carbon::setLocale('id');

        $jenis     = session('admin_jenis_sekolah', 'smk');
        $roleGuru  = 'guru_'  . $jenis;
        $roleMurid = 'murid_' . $jenis;
        $today     = Carbon::today();
        $labelJenis = strtoupper($jenis);

        /* ----------------------------------------------------------
           1. STAT CARDS
        ---------------------------------------------------------- */
        $totalGuru  = User::where('role', $roleGuru)->count();
        $totalMurid = User::where('role', $roleMurid)->count();

        /* Ambil ID semua guru & murid jenis ini */
        $guruIds  = User::where('role', $roleGuru)->pluck('id');
        $muridIds = User::where('role', $roleMurid)->pluck('id');

        /* Absen guru hari ini */
        $absenGuruHariIni = AbsenGuru::whereIn('user_id', $guruIds)
            ->whereDate('created_at', $today)
            ->get();

        $guruHadir = $absenGuruHariIni->where('status', 'hadir')->count();
        $guruTidak = $absenGuruHariIni->where('status', 'tidak')->count();
        $guruIzin  = $absenGuruHariIni->where('status', 'izin')->count();

        /* Absen murid hari ini */
        $absenMuridHariIni = AbsenMurid::whereIn('user_id', $muridIds)
            ->whereDate('created_at', $today)
            ->get();

        $muridHadir = $absenMuridHariIni->where('status', 'hadir')->count();
        $muridTidak = $absenMuridHariIni->where('status', 'tidak')->count();
        $muridIzin  = $absenMuridHariIni->where('status', 'izin')->count();

        $totalHadir = $guruHadir + $muridHadir;
        $totalTidak = $guruTidak + $muridTidak;
        $totalIzin  = $guruIzin  + $muridIzin;

        $totalAnggota = $totalGuru + $totalMurid;
        $pctHadir = $totalAnggota > 0
            ? round(($totalHadir / $totalAnggota) * 100, 1)
            : 0;

        /* ----------------------------------------------------------
           2. GRAFIK GARIS: 7 hari terakhir
        ---------------------------------------------------------- */
        $labels7     = [];
        $muridHadir7 = [];
        $guruHadir7  = [];

        $hariId = ['Min','Sen','Sel','Rab','Kam','Jum','Sab'];
        $bulanId = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        for ($d = 6; $d >= 0; $d--) {
            $tgl = $today->copy()->subDays($d);

            $labels7[] = $hariId[$tgl->dayOfWeek] . ', ' . $tgl->day . ' ' . $bulanId[$tgl->month];

            $muridHadir7[] = AbsenMurid::whereIn('user_id', $muridIds)
                ->whereDate('created_at', $tgl)
                ->where('status', 'hadir')
                ->count();

            $guruHadir7[] = AbsenGuru::whereIn('user_id', $guruIds)
                ->whereDate('created_at', $tgl)
                ->where('status', 'hadir')
                ->count();
        }

        /* ----------------------------------------------------------
           3. BAR CHART: per bulan tahun berjalan
        ---------------------------------------------------------- */
        $tahun     = $today->year;
        $bulanSkrg = $today->month;

        $labelsBulan    = [];
        $hadirBulanan   = [];
        $tidakBulanan   = [];
        $izinBulanan    = [];

        $namaBulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

        for ($b = 1; $b <= $bulanSkrg; $b++) {
            $labelsBulan[] = $namaBulan[$b - 1];

            $hadirBulanan[] = AbsenMurid::whereIn('user_id', $muridIds)
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $b)
                ->where('status', 'hadir')
                ->count();

            $tidakBulanan[] = AbsenMurid::whereIn('user_id', $muridIds)
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $b)
                ->where('status', 'tidak')
                ->count();

            $izinBulanan[] = AbsenMurid::whereIn('user_id', $muridIds)
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $b)
                ->where('status', 'izin')
                ->count();
        }

        /* ----------------------------------------------------------
           4. TABEL ABSEN GURU HARI INI
        ---------------------------------------------------------- */
        $tabelGuru = User::where('role', $roleGuru)
            ->with(['mapel'])
            ->get()
            ->map(function ($guru) use ($today) {
                $absen = AbsenGuru::where('user_id', $guru->id)
                    ->whereDate('created_at', $today)
                    ->latest()
                    ->first();

                $mapelNama = $guru->mapel->pluck('nama_mapel')->implode(', ') ?: '-';

                return [
                    'nama'   => $guru->name,
                    'mapel'  => $mapelNama,
                    'waktu'  => $absen ? Carbon::parse($absen->created_at)->format('H:i') : '--',
                    'status' => $absen ? $absen->status : 'tidak',
                    'avatar' => strtoupper(mb_substr($guru->name, 0, 1)),
                ];
            })
            ->values();

        /* ----------------------------------------------------------
           5. AKTIVITAS TERBARU (gabungan absen guru + murid, 8 item)
        ---------------------------------------------------------- */
        $aktGuru = AbsenGuru::with('guru')
            ->whereIn('user_id', $guruIds)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($a) {
                return [
                    'type'   => 'guru',
                    'nama'   => $a->guru->name ?? '-',
                    'status' => $a->status,
                    'waktu'  => Carbon::parse($a->created_at)->diffForHumans(),
                    'dot'    => $a->status === 'hadir' ? '#4ade80'
                                : ($a->status === 'izin' ? '#fbbf24' : '#f87171'),
                ];
            });

        $aktMurid = AbsenMurid::with('murid')
            ->whereIn('user_id', $muridIds)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($a) {
                return [
                    'type'   => 'murid',
                    'nama'   => $a->murid->name ?? '-',
                    'status' => $a->status,
                    'waktu'  => Carbon::parse($a->created_at)->diffForHumans(),
                    'dot'    => $a->status === 'hadir' ? '#22d3ee'
                                : ($a->status === 'izin' ? '#fbbf24' : '#f87171'),
                ];
            });

        $aktivitas = $aktGuru->concat($aktMurid)
            ->sortByDesc(fn($a) => $a['waktu'])
            ->take(8)
            ->values();

        /* ----------------------------------------------------------
           Kembalikan semua data sekaligus
        ---------------------------------------------------------- */
        return response()->json([
            'jenis'       => $jenis,
            'label'       => $labelJenis,
            'stats'       => [
                'total_guru'  => $totalGuru,
                'total_murid' => $totalMurid,
                'hadir'       => $totalHadir,
                'tidak'       => $totalTidak,
                'izin'        => $totalIzin,
                'pct_hadir'   => $pctHadir,
            ],
            'line'        => [
                'labels'      => $labels7,
                'murid_hadir' => $muridHadir7,
                'guru_hadir'  => $guruHadir7,
            ],
            'bar'         => [
                'labels'  => $labelsBulan,
                'hadir'   => $hadirBulanan,
                'tidak'   => $tidakBulanan,
                'izin'    => $izinBulanan,
            ],
            'tabel_guru'  => $tabelGuru,
            'aktivitas'   => $aktivitas,
        ]);
    }
}
