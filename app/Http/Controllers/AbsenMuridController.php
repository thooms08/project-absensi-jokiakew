<?php

namespace App\Http\Controllers;

use App\Models\AbsenMurid;
use App\Models\JadwalMengajar;
use App\Models\PesertaKelas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsenMuridController extends Controller
{
    private const DOW_TO_HARI = [
        1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu',
    ];

    /* ================================================================
       MURID — Halaman & rekap (read-only)
    ================================================================ */

    public function index()
    {
        return view('murid.index');
    }

    public function absenPage()
    {
        return view('murid.absen');
    }

    /* ----------------------------------------------------------------
       KARTU  –  Partial AJAX: kartu jadwal hari ini untuk murid (read-only)
    ---------------------------------------------------------------- */
    public function kartu()
    {
        $muridId    = auth()->id();
        $hariIni    = self::DOW_TO_HARI[date('N')] ?? null;
        $sekarang   = Carbon::now();
        $tglHariIni = $sekarang->toDateString();

        $kelasIds = PesertaKelas::where('user_id', $muridId)->pluck('kelas_id');

        $jadwals = JadwalMengajar::with(['guru', 'mapel', 'kelas'])
            ->whereIn('kelas_id', $kelasIds)
            ->where('hari', $hariIni)
            ->orderBy('waktu_mulai')
            ->get();

        $absenHariIni = AbsenMurid::where('user_id', $muridId)
            ->where('tanggal_absen', $tglHariIni)
            ->whereNotNull('jadwal_mengajar_id')
            ->get()
            ->keyBy('jadwal_mengajar_id');

        $nowHi = $sekarang->format('H:i');

        $kartuList = $jadwals->map(function ($jadwal) use ($absenHariIni, $nowHi) {
            $absen      = $absenHariIni->get($jadwal->id);
            $jamMulaiHi = substr($jadwal->waktu_mulai, 0, 5);
            $jamAkhirHi = substr($jadwal->waktu_berakhir, 0, 5);

            $sudahMulai   = $nowHi >= $jamMulaiHi;
            $sudahLewat   = $nowHi >  $jamAkhirHi;
            $dalamJendela = $sudahMulai && !$sudahLewat;

            return [
                'jadwal'        => $jadwal,
                'absen'         => $absen,
                'dalam_jendela' => $dalamJendela,
                'belum_mulai'   => !$sudahMulai,
                'sudah_lewat'   => $sudahLewat,
            ];
        });

        return view('murid.partials.card_absen', compact('kartuList', 'tglHariIni'));
    }

    /* ----------------------------------------------------------------
       SUMMARY  –  Rekap absen murid bulan ini (untuk dashboard)
    ---------------------------------------------------------------- */
    public function summary()
    {
        $muridId = auth()->id();
        $awal    = Carbon::now()->startOfMonth()->toDateString();
        $akhir   = Carbon::now()->endOfMonth()->toDateString();

        $rows = AbsenMurid::where('user_id', $muridId)
            ->whereBetween('tanggal_absen', [$awal, $akhir])
            ->whereNotNull('jadwal_mengajar_id')
            ->get();

        return response()->json([
            'hadir' => $rows->where('status', 'hadir')->count(),
            'izin'  => $rows->where('status', 'izin')->count(),
            'tidak' => $rows->where('status', 'tidak')->count(),
            'total' => $rows->count(),
        ]);
    }

    /* ================================================================
       GURU — Halaman & scan QR
    ================================================================ */

    public function guruPage()
    {
        return view('guru.absen_murid');
    }

    /* ----------------------------------------------------------------
       GURU HARI INI LIST  –  AJAX: daftar absen murid hari ini
    ---------------------------------------------------------------- */
    public function guruHariIniList(Request $request)
    {
        $guruId    = auth()->id();
        $tgl       = Carbon::today()->toDateString();
        $jadwalIds = JadwalMengajar::with(['kelas','mapel'])
                        ->where('user_id', $guruId)
                        ->get()
                        ->keyBy('id');

        if ($jadwalIds->isEmpty()) {
            return response()->json(['rows' => [], 'tanggal' => $tgl]);
        }

        $absens = AbsenMurid::with(['murid', 'jadwal.kelas', 'jadwal.mapel'])
            ->whereIn('jadwal_mengajar_id', $jadwalIds->keys())
            ->where('tanggal_absen', $tgl)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($a) {
                return [
                    'id'           => $a->id,
                    'nama'         => $a->murid?->name ?? '-',
                    'username'     => $a->murid?->username ?? '-',
                    'avatar'       => strtoupper(mb_substr($a->murid?->name ?? 'M', 0, 1)),
                    'kelas'        => $a->jadwal?->kelas?->nama_kelas ?? '-',
                    'mapel'        => $a->jadwal?->mapel?->nama_mapel ?? '-',
                    'jam_mulai'    => $a->jadwal ? substr($a->jadwal->waktu_mulai, 0, 5) : '-',
                    'jam_berakhir' => $a->jadwal ? substr($a->jadwal->waktu_berakhir, 0, 5) : '-',
                    'status'       => $a->status,
                    'alasan'       => $a->alasan ?? '',
                    'waktu'        => $a->created_at ? $a->created_at->format('H:i') : '-',
                ];
            });

        return response()->json(['rows' => $absens, 'tanggal' => $tgl]);
    }

    /* ----------------------------------------------------------------
       GURU SCAN PAGE  –  Halaman scan QR murid
    ---------------------------------------------------------------- */
    public function guruScanPage()
    {
        return view('guru.scan');
    }

    /* ----------------------------------------------------------------
       GURU SCAN QR  –  Proses QR scan dari kamera guru
    ---------------------------------------------------------------- */
    public function guruScanQr(Request $request)
    {
        $guruId = auth()->id();
        $request->validate([
            'user_id'   => 'required|integer|exists:users,id',
            'jadwal_id' => 'required|integer|exists:jadwal_mengajar,id',
        ]);

        $murid = \App\Models\User::where('id', $request->user_id)
                                  ->whereIn('role', ['murid_smk', 'murid_smp'])
                                  ->first();

        if (!$murid) {
            return response()->json([
                'success' => false,
                'message' => 'Murid tidak ditemukan. Pastikan QR yang di-scan adalah QR absen murid.',
            ], 404);
        }

        $jadwal = JadwalMengajar::where('id', $request->jadwal_id)
                                 ->where('user_id', $guruId)
                                 ->firstOrFail();

        $tglHariIni = Carbon::today()->toDateString();

        $isAnggota = \App\Models\PesertaKelas::where('user_id', $murid->id)
                                              ->where('kelas_id', $jadwal->kelas_id)
                                              ->exists();
        if (!$isAnggota) {
            return response()->json([
                'success' => false,
                'nama'    => $murid->name,
                'message' => $murid->name . ' bukan peserta kelas ' . ($jadwal->kelas?->nama_kelas ?? '') . '.',
            ], 422);
        }

        $sudahAbsen = AbsenMurid::where('user_id', $murid->id)
                                 ->where('jadwal_mengajar_id', $jadwal->id)
                                 ->where('tanggal_absen', $tglHariIni)
                                 ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success'     => false,
                'sudah_absen' => true,
                'nama'        => $murid->name,
                'kelas'       => $jadwal->kelas?->nama_kelas ?? '-',
                'mapel'       => $jadwal->mapel?->nama_mapel ?? '-',
                'message'     => $murid->name . ' sudah tercatat absen untuk jadwal ini.',
            ], 422);
        }

        AbsenMurid::create([
            'user_id'            => $murid->id,
            'jadwal_mengajar_id' => $jadwal->id,
            'tanggal_absen'      => $tglHariIni,
            'status'             => 'hadir',
            'alasan'             => null,
            'dikonfirmasi_at'    => now(),
        ]);

        return response()->json([
            'success' => true,
            'nama'    => $murid->name,
            'kelas'   => $jadwal->kelas?->nama_kelas ?? '-',
            'mapel'   => $jadwal->mapel?->nama_mapel ?? '-',
            'message' => $murid->name . ' berhasil dicatat hadir.',
        ]);
    }

    /* ----------------------------------------------------------------
       JADWAL HARI INI  –  Daftar jadwal guru hari ini untuk dropdown scan QR
    ---------------------------------------------------------------- */
    public function guruJadwalHariIni()
    {
        $guruId  = auth()->id();
        $hariIni = self::DOW_TO_HARI[date('N')] ?? null;

        if (!$hariIni) {
            return response()->json(['jadwals' => []]);
        }

        $jadwals = JadwalMengajar::with(['mapel', 'kelas'])
            ->where('user_id', $guruId)
            ->where('hari', $hariIni)
            ->orderBy('waktu_mulai')
            ->get()
            ->map(fn($j) => [
                'id'             => $j->id,
                'kelas'          => $j->kelas?->nama_kelas ?? '-',
                'mapel'          => $j->mapel?->nama_mapel ?? '-',
                'waktu_mulai'    => substr($j->waktu_mulai, 0, 5),
                'waktu_berakhir' => substr($j->waktu_berakhir, 0, 5),
            ]);

        return response()->json(['jadwals' => $jadwals]);
    }

    /* ================================================================
       ADMIN — rekap absensi semua murid (read-only)
    ================================================================ */

    public function adminIndex()
    {
        return view('admin.absen_murid');
    }

    public function adminList(Request $request)
    {
        $search = trim($request->get('search', ''));
        $bulan  = $request->get('bulan', date('Y-m'));
        $jenis  = session('admin_jenis_sekolah', 'smk');
        $role   = 'murid_' . $jenis;

        try {
            $awal  = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth()->toDateString();
            $akhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth()->toDateString();
        } catch (\Exception $e) {
            $awal  = Carbon::now()->startOfMonth()->toDateString();
            $akhir = Carbon::now()->endOfMonth()->toDateString();
        }

        $query = \App\Models\User::where('role', $role);
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $murid = $query->orderBy('name')->get();

        $rekap = $murid->map(function ($m) use ($awal, $akhir) {
            $rows = AbsenMurid::where('user_id', $m->id)
                ->whereBetween('tanggal_absen', [$awal, $akhir])
                ->whereNotNull('jadwal_mengajar_id')
                ->get();
            return [
                'murid'       => $m,
                'total_hadir' => $rows->where('status', 'hadir')->count(),
                'total_izin'  => $rows->where('status', 'izin')->count(),
                'total_tidak' => $rows->where('status', 'tidak')->count(),
            ];
        });

        return view('admin.partials.list_absen_murid', compact('rekap', 'bulan', 'awal', 'akhir'));
    }

    public function adminDetailMurid(Request $request, $userId)
    {
        $bulan = $request->get('bulan', date('Y-m'));
        $jenis = session('admin_jenis_sekolah', 'smk');
        $role  = 'murid_' . $jenis;

        try {
            $awal  = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth()->toDateString();
            $akhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth()->toDateString();
        } catch (\Exception $e) {
            $awal  = Carbon::now()->startOfMonth()->toDateString();
            $akhir = Carbon::now()->endOfMonth()->toDateString();
        }

        $murid = \App\Models\User::where('role', $role)->findOrFail($userId);

        $absens = AbsenMurid::with(['jadwal.mapel', 'jadwal.kelas'])
            ->where('user_id', $userId)
            ->whereBetween('tanggal_absen', [$awal, $akhir])
            ->whereNotNull('jadwal_mengajar_id')
            ->orderBy('tanggal_absen', 'desc')
            ->get()
            ->map(function ($a) {
                return [
                    'tanggal'      => $a->tanggal_absen?->translatedFormat('d F Y') ?? '-',
                    'hari'         => $a->jadwal?->hari ?? '-',
                    'kelas'        => $a->jadwal?->kelas?->nama_kelas ?? '-',
                    'mapel'        => $a->jadwal?->mapel?->nama_mapel ?? '-',
                    'jam_mulai'    => $a->jadwal ? substr($a->jadwal->waktu_mulai, 0, 5) : '-',
                    'jam_berakhir' => $a->jadwal ? substr($a->jadwal->waktu_berakhir, 0, 5) : '-',
                    'status'       => $a->status,
                    'alasan'       => $a->alasan ?? '-',
                ];
            });

        return response()->json([
            'murid'  => ['id' => $murid->id, 'name' => $murid->name],
            'absens' => $absens,
        ]);
    }
}
