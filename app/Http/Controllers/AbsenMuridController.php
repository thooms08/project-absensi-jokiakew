<?php

namespace App\Http\Controllers;

use App\Models\AbsenMurid;
use App\Models\KodeAbsen;
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
       MURID — Halaman & logika absensi dari sisi murid
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

    /* ----------------------------------------------------------------
       KARTU  –  Partial AJAX: kartu jadwal hari ini untuk murid
    ---------------------------------------------------------------- */
    public function kartu()
    {
        $muridId    = auth()->id();
        $hariIni    = self::DOW_TO_HARI[date('N')] ?? null;
        $sekarang   = Carbon::now();
        $tglHariIni = $sekarang->toDateString();

        /* Ambil kelas murid ini */
        $kelasIds = PesertaKelas::where('user_id', $muridId)->pluck('kelas_id');

        /* Jadwal semua guru di kelas murid ini, hari ini */
        $jadwals = JadwalMengajar::with(['guru', 'mapel', 'kelas'])
            ->whereIn('kelas_id', $kelasIds)
            ->where('hari', $hariIni)
            ->orderBy('waktu_mulai')
            ->get();

        /* Absen murid hari ini indexed by jadwal_id */
        $absenHariIni = AbsenMurid::where('user_id', $muridId)
            ->where('tanggal_absen', $tglHariIni)
            ->whereNotNull('jadwal_mengajar_id')
            ->get()
            ->keyBy('jadwal_mengajar_id');

        $nowHi = $sekarang->format('H:i');

        $kartuList = $jadwals->map(function ($jadwal) use ($absenHariIni, $sekarang, $muridId, $tglHariIni, $nowHi) {
            $absen      = $absenHariIni->get($jadwal->id);
            $jamMulaiHi = substr($jadwal->waktu_mulai, 0, 5);
            $jamAkhirHi = substr($jadwal->waktu_berakhir, 0, 5);

            $sudahMulai   = $nowHi >= $jamMulaiHi;
            $sudahLewat   = $nowHi >  $jamAkhirHi;
            $dalamJendela = $sudahMulai && !$sudahLewat;

            /* Auto tidak jika sudah lewat & belum absen */
            if (!$absen && $sudahLewat) {
                $absen = AbsenMurid::create([
                    'user_id'            => $muridId,
                    'jadwal_mengajar_id' => $jadwal->id,
                    'tanggal_absen'      => $tglHariIni,
                    'kode_absen'         => '-',
                    'status'             => 'tidak',
                    'alasan'             => 'Tidak hadir (melewati batas waktu)',
                ]);
            }

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
       STORE  –  Simpan absen murid (hadir / izin)
    ---------------------------------------------------------------- */
    public function store(Request $request)
    {
        $muridId    = auth()->id();
        $tglHariIni = Carbon::today()->toDateString();
        $sekarang   = Carbon::now();

        $request->validate([
            'jadwal_id'  => 'required|exists:jadwal_mengajar,id',
            'status'     => 'required|in:hadir,izin',
            'kode_absen' => 'required_if:status,hadir|nullable|string|size:6',
            'foto_selfie'=> 'required_if:status,hadir|nullable|string',
            'alasan'     => 'required_if:status,izin|nullable|string|max:500',
            'foto_izin'  => 'nullable|string',
        ], [
            'kode_absen.required_if'  => 'Kode absen wajib diisi.',
            'foto_selfie.required_if' => 'Foto selfie wajib diambil.',
            'alasan.required_if'      => 'Keterangan izin wajib diisi.',
        ]);

        $jadwal = JadwalMengajar::findOrFail($request->jadwal_id);

        /* Validasi jendela waktu */
        $nowHi      = $sekarang->format('H:i');
        $jamMulaiHi = substr($jadwal->waktu_mulai, 0, 5);
        $jamAkhirHi = substr($jadwal->waktu_berakhir, 0, 5);

        if ($nowHi < $jamMulaiHi) {
            return response()->json(['success'=>false, 'message'=>'Absen belum dimulai. Jadwal mulai pukul '.$jamMulaiHi.'.'], 422);
        }
        if ($nowHi > $jamAkhirHi) {
            return response()->json(['success'=>false, 'message'=>'Waktu absen sudah berakhir pukul '.$jamAkhirHi.'.'], 422);
        }

        /* Cegah duplikat */
        if (AbsenMurid::where('user_id',$muridId)->where('jadwal_mengajar_id',$jadwal->id)->where('tanggal_absen',$tglHariIni)->exists()) {
            return response()->json(['success'=>false,'message'=>'Anda sudah absen untuk jadwal ini.'], 422);
        }

        /* Validasi kode absen */
        if ($request->status === 'hadir') {
            $kode = KodeAbsen::where('kode', strtoupper($request->kode_absen))
                             ->where('jadwal_mengajar_id', $jadwal->id)
                             ->where('is_active', true)
                             ->first();

            if (!$kode || !$kode->masihBerlaku()) {
                return response()->json(['success'=>false,'message'=>'Kode absen tidak valid atau sudah kedaluwarsa.'], 422);
            }
        }

        /* Simpan foto */
        $fotoSelfie = null;
        $fotoIzin   = null;

        if ($request->status === 'hadir' && $request->filled('foto_selfie')) {
            $fotoSelfie = $this->simpanFoto($request->foto_selfie, 'absen_murid');
        }
        if ($request->filled('foto_izin')) {
            $fotoIzin = $this->simpanFoto($request->foto_izin, 'izin_murid');
        }

        AbsenMurid::create([
            'user_id'            => $muridId,
            'jadwal_mengajar_id' => $jadwal->id,
            'tanggal_absen'      => $tglHariIni,
            'kode_absen'         => $request->status === 'hadir' ? strtoupper($request->kode_absen) : '-',
            'status'             => $request->status,
            'alasan'             => $request->status === 'izin' ? $request->alasan : null,
            'foto_selfie'        => $fotoSelfie,
            'foto_izin'          => $fotoIzin,
        ]);

        return response()->json(['success'=>true,'message'=> $request->status === 'hadir' ? 'Absen hadir berhasil dicatat.' : 'Izin berhasil dicatat.']);
    }

    /* ----------------------------------------------------------------
       FOTO SERVE  –  Stream foto murid dari storage private
    ---------------------------------------------------------------- */
    public function fotoServe($tipe, $filename)
    {
        /* tipe: absen_murid | izin_murid */
        $allowed = ['absen_murid','izin_murid'];
        if (!in_array($tipe, $allowed)) abort(404);

        $path = storage_path('app/private/'.$tipe.'/'.$filename);
        if (!file_exists($path)) abort(404);

        $mime = mime_content_type($path) ?: 'image/jpeg';
        return response()->file($path, ['Content-Type' => $mime]);
    }

    /* ================================================================
       GURU — Notifikasi absen murid
    ================================================================ */

    /* ----------------------------------------------------------------
       NOTIF COUNT  –  Jumlah absen murid belum dikonfirmasi hari ini
    ---------------------------------------------------------------- */
    public function guruNotifCount()
    {
        $guruId  = auth()->id();
        $tgl     = Carbon::today()->toDateString();

        /* Jadwal guru hari ini */
        $jadwalIds = JadwalMengajar::where('user_id', $guruId)->pluck('id');

        $count = AbsenMurid::whereIn('jadwal_mengajar_id', $jadwalIds)
                           ->where('tanggal_absen', $tgl)
                           ->whereIn('status', ['hadir','izin'])
                           ->whereNull('dikonfirmasi_at')
                           ->count();

        return response()->json(['count' => $count]);
    }

    /* ----------------------------------------------------------------
       NOTIF LIST  –  Daftar absen murid hari ini untuk lonceng guru
    ---------------------------------------------------------------- */
    public function guruNotifList()
    {
        $guruId  = auth()->id();
        $tgl     = Carbon::today()->toDateString();

        $jadwalIds = JadwalMengajar::where('user_id', $guruId)->pluck('id');

        $list = AbsenMurid::with(['murid','jadwal.kelas','jadwal.mapel'])
            ->whereIn('jadwal_mengajar_id', $jadwalIds)
            ->where('tanggal_absen', $tgl)
            ->whereIn('status', ['hadir','izin'])
            ->whereNull('dikonfirmasi_at')
            ->latest()
            ->take(30)
            ->get()
            ->map(function ($a) {
                return [
                    'id'       => $a->id,
                    'nama'     => $a->murid?->name ?? '-',
                    'username' => $a->murid?->username ?? '-',
                    'avatar'   => strtoupper(mb_substr($a->murid?->name ?? 'M', 0, 1)),
                    'kelas'    => $a->jadwal?->kelas?->nama_kelas ?? '-',
                    'mapel'    => $a->jadwal?->mapel?->nama_mapel ?? '-',
                    'jam_mulai'=> $a->jadwal ? substr($a->jadwal->waktu_mulai, 0, 5) : '-',
                    'status'   => $a->status,
                    'alasan'   => $a->alasan ?? '',
                    'waktu'    => $a->created_at ? $a->created_at->locale('id')->diffForHumans() : '-',
                    'foto_url' => $a->status === 'hadir' && $a->foto_selfie
                                  ? route('murid.foto_serve', ['tipe'=>'absen_murid','filename'=>basename($a->foto_selfie)])
                                  : null,
                    'foto_izin_url' => $a->foto_izin
                                  ? route('murid.foto_serve', ['tipe'=>'izin_murid','filename'=>basename($a->foto_izin)])
                                  : null,
                ];
            });

        return response()->json(['list' => $list]);
    }

    /* ----------------------------------------------------------------
       KONFIRMASI NOTIF  –  Guru konfirmasi absen murid
    ---------------------------------------------------------------- */
    public function guruKonfirmasi($id)
    {
        $absen = AbsenMurid::findOrFail($id);
        $absen->update(['dikonfirmasi_at' => now()]);
        return response()->json(['success' => true]);
    }

    /* ----------------------------------------------------------------
       ABSEN MURID PAGE  –  Halaman absensi murid untuk guru
    ---------------------------------------------------------------- */
    public function guruPage()
    {
        return view('guru.absen_murid');
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

        try {
            $awal  = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth()->toDateString();
            $akhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth()->toDateString();
        } catch (\Exception $e) {
            $awal  = Carbon::now()->startOfMonth()->toDateString();
            $akhir = Carbon::now()->endOfMonth()->toDateString();
        }

        $query = \App\Models\User::where('role', 'murid');
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
                'murid'        => $m,
                'total_hadir'  => $rows->where('status', 'hadir')->count(),
                'total_izin'   => $rows->where('status', 'izin')->count(),
                'total_tidak'  => $rows->where('status', 'tidak')->count(),
            ];
        });

        return view('admin.partials.list_absen_murid', compact('rekap', 'bulan', 'awal', 'akhir'));
    }

    public function adminDetailMurid(Request $request, $userId)
    {
        $bulan = $request->get('bulan', date('Y-m'));
        try {
            $awal  = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth()->toDateString();
            $akhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth()->toDateString();
        } catch (\Exception $e) {
            $awal  = Carbon::now()->startOfMonth()->toDateString();
            $akhir = Carbon::now()->endOfMonth()->toDateString();
        }

        $murid = \App\Models\User::where('role', 'murid')->findOrFail($userId);

        $absens = AbsenMurid::with(['jadwal.mapel', 'jadwal.kelas'])
            ->where('user_id', $userId)
            ->whereBetween('tanggal_absen', [$awal, $akhir])
            ->whereNotNull('jadwal_mengajar_id')
            ->orderBy('tanggal_absen', 'desc')
            ->get()
            ->map(function ($a) {
                return [
                    'tanggal'        => $a->tanggal_absen?->translatedFormat('d F Y') ?? '-',
                    'hari'           => $a->jadwal?->hari ?? '-',
                    'kelas'          => $a->jadwal?->kelas?->nama_kelas ?? '-',
                    'mapel'          => $a->jadwal?->mapel?->nama_mapel ?? '-',
                    'jam_mulai'      => $a->jadwal ? substr($a->jadwal->waktu_mulai, 0, 5) : '-',
                    'jam_berakhir'   => $a->jadwal ? substr($a->jadwal->waktu_berakhir, 0, 5) : '-',
                    'status'         => $a->status,
                    'alasan'         => $a->alasan ?? '-',
                    'foto_selfie_url'=> $a->foto_selfie
                                       ? route('murid.foto_serve', ['tipe'=>'absen_murid','filename'=>basename($a->foto_selfie)])
                                       : null,
                    'foto_izin_url'  => $a->foto_izin
                                       ? route('murid.foto_serve', ['tipe'=>'izin_murid','filename'=>basename($a->foto_izin)])
                                       : null,
                ];
            });

        return response()->json([
            'murid'  => ['id' => $murid->id, 'name' => $murid->name],
            'absens' => $absens,
        ]);
    }

    /* ----------------------------------------------------------------
       SIMPAN FOTO BASE64
    ---------------------------------------------------------------- */
    private function simpanFoto(string $base64, string $folder): ?string
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64, $match)) return null;
        $ext     = strtolower($match[1]);
        $allowed = ['jpeg','jpg','png','webp'];
        if (!in_array($ext, $allowed)) return null;

        $data = base64_decode(substr($base64, strpos($base64, ',') + 1));
        if (!$data) return null;

        $dir = storage_path('app/private/'.$folder);
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $filename = $folder.'_'.auth()->id().'_'.time().'_'.uniqid().'.'.$ext;
        file_put_contents($dir.'/'.$filename, $data);
        return $filename;
    }
}
