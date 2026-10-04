<?php

namespace App\Http\Controllers;

use App\Models\AbsenGuru;
use App\Models\JadwalMengajar;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsenGuruController extends Controller
{
    /**
     * Nama hari PHP (date('N') = 1=Senin … 7=Minggu) ke enum jadwal.
     */
    private const DOW_TO_HARI = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
    ];

    /* ----------------------------------------------------------------
       INDEX  –  Halaman utama absensi guru
    ---------------------------------------------------------------- */
    public function index()
    {
        return view('guru.absen_guru');
    }

    /* ----------------------------------------------------------------
       KARTU  –  Partial AJAX: daftar kartu jadwal hari ini
    ---------------------------------------------------------------- */
    public function kartu(Request $request)
    {
        $guruId    = auth()->id();
        $hariIni   = self::DOW_TO_HARI[date('N')] ?? null;
        $sekarang  = Carbon::now();
        $tglHariIni = $sekarang->toDateString();          /* Y-m-d */

        /* Ambil jadwal guru hari ini */
        $jadwals = JadwalMengajar::with(['mapel', 'kelas'])
            ->where('user_id', $guruId)
            ->where('hari', $hariIni)
            ->orderBy('waktu_mulai')
            ->get();

        /* Ambil absen hari ini (indexed by jadwal_mengajar_id) */
        $absenHariIni = AbsenGuru::where('user_id', $guruId)
            ->where('tanggal_absen', $tglHariIni)
            ->whereNotNull('jadwal_mengajar_id')
            ->get()
            ->keyBy('jadwal_mengajar_id');

        /*
         * Proses setiap jadwal:
         *  - Jika sudah ada absen  → tampilkan status
         *  - Jika belum absen & jam sudah lewat → buat record 'tidak' otomatis
         *  - Jika belum absen & masih dalam jendela jam → tampilkan tombol Hadir/Izin
         *  - Jika belum absen & belum waktunya → tampilkan status "menunggu"
         */
        $kartuList = $jadwals->map(function ($jadwal) use ($absenHariIni, $sekarang, $guruId, $tglHariIni) {
            $absen = $absenHariIni->get($jadwal->id);

            /* Parse jam jadwal (format H:i:s atau H:i) */
            $jamMulai    = Carbon::createFromFormat('H:i:s', $jadwal->waktu_mulai);
            $jamBerakhir = Carbon::createFromFormat('H:i:s', $jadwal->waktu_berakhir);

            /* Bandingkan dengan jam sekarang (hari ini) */
            $nowTime     = Carbon::createFromFormat('H:i:s', $sekarang->format('H:i:s'));

            $sudahMulai  = $nowTime->gte($jamMulai);
            $sudahLewat  = $nowTime->gt($jamBerakhir);
            $dalamJendela = $sudahMulai && !$sudahLewat;

            /* Otomatis 'tidak' jika jam sudah lewat & belum absen */
            if (!$absen && $sudahLewat) {
                $absen = AbsenGuru::create([
                    'user_id'            => $guruId,
                    'jadwal_mengajar_id' => $jadwal->id,
                    'tanggal_absen'      => $tglHariIni,
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

        return view('guru.partials.kartu_absen_guru', compact('kartuList', 'hariIni', 'tglHariIni'));
    }

    /* ----------------------------------------------------------------
       STORE  –  Simpan absen (hadir dengan foto, atau izin)
    ---------------------------------------------------------------- */
    public function store(Request $request)
    {
        $guruId     = auth()->id();
        $tglHariIni = Carbon::today()->toDateString();
        $sekarang   = Carbon::now();

        $request->validate([
            'jadwal_id' => 'required|exists:jadwal_mengajar,id',
            'status'    => 'required|in:hadir,izin',
            'foto'      => 'required_if:status,hadir|nullable|string',   /* base64 */
            'alasan'    => 'required_if:status,izin|nullable|string|max:500',
        ], [
            'foto.required_if'   => 'Foto wajib diambil untuk absen hadir.',
            'alasan.required_if' => 'Keterangan wajib diisi untuk izin.',
        ]);

        $jadwal = JadwalMengajar::where('id', $request->jadwal_id)
                                ->where('user_id', $guruId)
                                ->firstOrFail();

        /* Validasi jendela waktu */
        $jamMulai    = Carbon::createFromFormat('H:i:s', $jadwal->waktu_mulai);
        $jamBerakhir = Carbon::createFromFormat('H:i:s', $jadwal->waktu_berakhir);
        $nowTime     = Carbon::createFromFormat('H:i:s', $sekarang->format('H:i:s'));

        if ($nowTime->lt($jamMulai)) {
            return response()->json([
                'success' => false,
                'message' => 'Absen belum bisa dilakukan. Jadwal dimulai pukul ' . substr($jadwal->waktu_mulai, 0, 5) . '.',
            ], 422);
        }

        if ($nowTime->gt($jamBerakhir)) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu absen sudah berakhir pada pukul ' . substr($jadwal->waktu_berakhir, 0, 5) . '.',
            ], 422);
        }

        /* Cegah duplikat */
        $sudahAbsen = AbsenGuru::where('user_id', $guruId)
                               ->where('jadwal_mengajar_id', $jadwal->id)
                               ->where('tanggal_absen', $tglHariIni)
                               ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absen untuk jadwal ini.',
            ], 422);
        }

        /* Simpan foto jika hadir */
        $fotoPath = null;
        if ($request->status === 'hadir' && $request->filled('foto')) {
            $fotoPath = $this->simpanFotoBase64($request->foto, $guruId);
            if (!$fotoPath) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyimpan foto. Pastikan format foto valid.',
                ], 422);
            }
        }

        AbsenGuru::create([
            'user_id'            => $guruId,
            'jadwal_mengajar_id' => $jadwal->id,
            'tanggal_absen'      => $tglHariIni,
            'foto_bukti'         => $fotoPath,
            'status'             => $request->status,
            'alasan'             => $request->status === 'izin'
                                    ? $request->alasan
                                    : null,
        ]);

        $pesan = $request->status === 'hadir'
                 ? 'Absen hadir berhasil dicatat.'
                 : 'Izin berhasil dicatat.';

        return response()->json(['success' => true, 'message' => $pesan]);
    }

    /* ----------------------------------------------------------------
       STATUS  –  Cek status absen satu jadwal (polling)
    ---------------------------------------------------------------- */
    public function status(Request $request)
    {
        $guruId     = auth()->id();
        $tglHariIni = Carbon::today()->toDateString();

        $request->validate(['jadwal_id' => 'required|exists:jadwal_mengajar,id']);

        $absen = AbsenGuru::where('user_id', $guruId)
                          ->where('jadwal_mengajar_id', $request->jadwal_id)
                          ->where('tanggal_absen', $tglHariIni)
                          ->first();

        return response()->json([
            'sudah_absen' => (bool) $absen,
            'status'      => $absen?->status,
        ]);
    }

    /* ================================================================
       ADMIN — lihat rekap absensi semua guru (read-only)
    ================================================================ */

    /* ----------------------------------------------------------------
       ADMIN INDEX  –  Halaman utama absensi guru (admin)
    ---------------------------------------------------------------- */
    public function adminIndex()
    {
        return view('admin.absen_guru');
    }

    /* ----------------------------------------------------------------
       ADMIN LIST  –  Partial AJAX: rekap per guru
       Menampilkan total hadir/izin/tidak masing-masing guru aktif.
    ---------------------------------------------------------------- */
    public function adminList(Request $request)
    {
        $search  = trim($request->get('search', ''));
        $bulan   = $request->get('bulan', date('Y-m'));   /* default bulan ini */

        /* Parse bulan ke range tanggal */
        try {
            $awal  = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth()->toDateString();
            $akhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth()->toDateString();
        } catch (\Exception $e) {
            $awal  = Carbon::now()->startOfMonth()->toDateString();
            $akhir = Carbon::now()->endOfMonth()->toDateString();
        }

        $query = \App\Models\User::where('role', 'guru');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name',     'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $guru = $query->orderBy('name')->get();

        /* Hitung total per guru untuk bulan yang dipilih */
        $rekap = $guru->map(function ($g) use ($awal, $akhir) {
            $absenBulan = AbsenGuru::where('user_id', $g->id)
                ->whereBetween('tanggal_absen', [$awal, $akhir])
                ->whereNotNull('jadwal_mengajar_id')
                ->get();

            return [
                'guru'         => $g,
                'total_hadir'  => $absenBulan->where('status', 'hadir')->count(),
                'total_izin'   => $absenBulan->where('status', 'izin')->count(),
                'total_tidak'  => $absenBulan->where('status', 'tidak')->count(),
            ];
        });

        return view('admin.partials.list_absen_guru', compact('rekap', 'bulan', 'awal', 'akhir'));
    }

    /* ----------------------------------------------------------------
       ADMIN DETAIL GURU  –  JSON: semua record absen 1 guru (untuk modal)
    ---------------------------------------------------------------- */
    public function adminDetailGuru(Request $request, $userId)
    {
        $bulan = $request->get('bulan', date('Y-m'));

        try {
            $awal  = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth()->toDateString();
            $akhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth()->toDateString();
        } catch (\Exception $e) {
            $awal  = Carbon::now()->startOfMonth()->toDateString();
            $akhir = Carbon::now()->endOfMonth()->toDateString();
        }

        $guru = \App\Models\User::where('role', 'guru')->findOrFail($userId);

        $absens = AbsenGuru::with(['jadwal.mapel', 'jadwal.kelas'])
            ->where('user_id', $userId)
            ->whereBetween('tanggal_absen', [$awal, $akhir])
            ->whereNotNull('jadwal_mengajar_id')
            ->orderBy('tanggal_absen', 'desc')
            ->orderBy('created_at',    'desc')
            ->get()
            ->map(function ($a) {
                return [
                    'id'           => $a->id,
                    'tanggal'      => $a->tanggal_absen?->translatedFormat('d F Y') ?? '-',
                    'hari'         => $a->jadwal?->hari ?? '-',
                    'kelas'        => $a->jadwal?->kelas?->nama_kelas ?? '-',
                    'mapel'        => $a->jadwal?->mapel?->nama_mapel ?? '-',
                    'jam_mulai'    => $a->jadwal ? substr($a->jadwal->waktu_mulai, 0, 5)    : '-',
                    'jam_berakhir' => $a->jadwal ? substr($a->jadwal->waktu_berakhir, 0, 5) : '-',
                    'status'       => $a->status,
                    'alasan'       => $a->alasan ?? '-',
                    'punya_foto'   => (bool) $a->foto_bukti,
                ];
            });

        return response()->json([
            'guru'   => ['id' => $guru->id, 'name' => $guru->name, 'username' => $guru->username],
            'absens' => $absens,
        ]);
    }

    /* ----------------------------------------------------------------
       ADMIN FOTO GURU  –  JSON: daftar foto bukti 1 guru (untuk grid dokumen)
    ---------------------------------------------------------------- */
    public function adminFotoGuru(Request $request, $userId)
    {
        $bulan = $request->get('bulan', date('Y-m'));

        try {
            $awal  = Carbon::createFromFormat('Y-m', $bulan)->startOfMonth()->toDateString();
            $akhir = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth()->toDateString();
        } catch (\Exception $e) {
            $awal  = Carbon::now()->startOfMonth()->toDateString();
            $akhir = Carbon::now()->endOfMonth()->toDateString();
        }

        $guru = \App\Models\User::where('role', 'guru')->findOrFail($userId);

        $fotos = AbsenGuru::with(['jadwal.kelas', 'jadwal.mapel'])
            ->where('user_id', $userId)
            ->where('status', 'hadir')
            ->whereNotNull('foto_bukti')
            ->whereBetween('tanggal_absen', [$awal, $akhir])
            ->orderBy('tanggal_absen', 'desc')
            ->get()
            ->map(function ($a) {
                return [
                    'id'       => $a->id,
                    'tanggal'  => $a->tanggal_absen?->translatedFormat('l, d F Y') ?? '-',
                    'hari'     => $a->jadwal?->hari ?? '-',
                    'kelas'    => $a->jadwal?->kelas?->nama_kelas ?? '-',
                    'mapel'    => $a->jadwal?->mapel?->nama_mapel ?? '-',
                    'foto_url' => route('admin.absen_guru.foto_serve', ['id' => $a->id]),
                ];
            });

        return response()->json([
            'guru'  => ['name' => $guru->name],
            'fotos' => $fotos,
        ]);
    }

    /* ----------------------------------------------------------------
       FOTO SERVE  –  Stream foto dari storage private ke browser
    ---------------------------------------------------------------- */
    public function fotoServe($id)
    {
        $absen = AbsenGuru::findOrFail($id);

        if (!$absen->foto_bukti) {
            abort(404);
        }

        $path = storage_path('app/private/' . $absen->foto_bukti);

        if (!file_exists($path)) {
            abort(404, 'Foto tidak ditemukan.');
        }

        $mime = mime_content_type($path) ?: 'image/jpeg';
        return response()->file($path, ['Content-Type' => $mime]);
    }

    /* ----------------------------------------------------------------
       ABSEN NOTIF COUNT  –  Jumlah absen baru hari ini (badge lonceng)
    ---------------------------------------------------------------- */
    public function absenNotifCount()
    {
        $tgl = Carbon::today()->toDateString();
        $count = AbsenGuru::where('tanggal_absen', $tgl)
                          ->whereNotNull('jadwal_mengajar_id')
                          ->whereIn('status', ['hadir', 'izin'])
                          ->count();

        return response()->json(['count' => $count]);
    }

    /* ----------------------------------------------------------------
       ABSEN NOTIF LIST  –  Daftar absen hari ini untuk dropdown notif
    ---------------------------------------------------------------- */
    public function absenNotifList()
    {
        $tgl = Carbon::today()->toDateString();

        $list = AbsenGuru::with(['guru', 'jadwal.kelas', 'jadwal.mapel'])
            ->where('tanggal_absen', $tgl)
            ->whereNotNull('jadwal_mengajar_id')
            ->whereIn('status', ['hadir', 'izin'])
            ->latest()
            ->take(30)
            ->get()
            ->map(function ($a) {
                return [
                    'id'       => $a->id,
                    'nama'     => $a->guru?->name         ?? '-',
                    'username' => $a->guru?->username     ?? '-',
                    'avatar'   => strtoupper(mb_substr($a->guru?->name ?? 'G', 0, 1)),
                    'kelas'    => $a->jadwal?->kelas?->nama_kelas ?? '-',
                    'mapel'    => $a->jadwal?->mapel?->nama_mapel ?? '-',
                    'jam_mulai'=> $a->jadwal ? substr($a->jadwal->waktu_mulai, 0, 5) : '-',
                    'status'   => $a->status,
                    'alasan'   => $a->alasan ?? '',
                    'waktu'    => $a->created_at?->diffForHumans() ?? '-',
                    'foto_url' => $a->status === 'hadir' && $a->foto_bukti
                                  ? route('admin.absen_guru.foto_serve', ['id' => $a->id])
                                  : null,
                ];
            });

        return response()->json(['list' => $list]);
    }
    private function simpanFotoBase64(string $base64, int $userId): ?string
    {
        /* Format: data:image/jpeg;base64,<data> */
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64, $match)) {
            return null;
        }

        $ext      = strtolower($match[1]);
        $allowed  = ['jpeg', 'jpg', 'png', 'webp'];
        if (!in_array($ext, $allowed)) {
            return null;
        }

        $imageData = substr($base64, strpos($base64, ',') + 1);
        $decoded   = base64_decode($imageData);
        if (!$decoded) {
            return null;
        }

        $folder   = storage_path('app/private/assets/guru_hadir');
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        $filename = 'guru_' . $userId . '_' . time() . '_' . uniqid() . '.' . $ext;
        $fullPath = $folder . '/' . $filename;

        if (file_put_contents($fullPath, $decoded) === false) {
            return null;
        }

        return 'assets/guru_hadir/' . $filename;   /* path relatif dari private */
    }
}
