<?php

namespace App\Http\Controllers;

use App\Models\JadwalMengajar;
use App\Models\User;
use App\Models\Mapel;
use App\Models\Kelas;
use Illuminate\Http\Request;

class JadwalMengajarController extends Controller
{
    /** Daftar hari yang valid (urutan ISO Mon-Sat) */
    private const HARI_VALID = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

    /** Petakan dayOfWeek PHP (0=Min … 6=Sab) ke nama hari */
    private const DOW_TO_HARI = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
    ];

    /* ----------------------------------------------------------------
       INDEX  –  Halaman utama Jadwal Mengajar (admin)
    ---------------------------------------------------------------- */
    public function index()
    {
        return view('admin.jadwal_mengajar');
    }

    /* ----------------------------------------------------------------
       LIST  –  Partial AJAX: daftar jadwal (admin)
       Query params:
         - hari   : nama hari (Senin … Sabtu), default hari ini
         - search : string
    ---------------------------------------------------------------- */
    public function list(Request $request)
    {
        $hariParam = $request->get('hari', '');
        $search    = trim($request->get('search', ''));

        /* Tentukan hari aktif */
        $hariAktif = in_array($hariParam, self::HARI_VALID)
            ? $hariParam
            : (self::DOW_TO_HARI[date('N')] ?? 'Senin');   /* date('N') = 1=Sen … 7=Min */

        $query = JadwalMengajar::with(['guru', 'mapel', 'kelas'])
            ->where('hari', $hariAktif);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('guru', fn($g) => $g->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('mapel', fn($m) => $m->where('nama_mapel', 'like', "%{$search}%"))
                  ->orWhereHas('kelas', fn($k) => $k->where('nama_kelas', 'like', "%{$search}%"));
            });
        }

        $semua = $query->orderBy('waktu_mulai')->orderBy('user_id')->get();

        /* Kelompokkan per guru */
        $grouped = $semua->groupBy('user_id')->map(function ($rows) {
            $first = $rows->first();
            return [
                'guru'      => $first->guru,
                'hari'      => $first->hari,
                'mapel'     => $rows->pluck('mapel')->unique('id')->values(),
                'kelas'     => $rows->pluck('kelas')->unique('id')->values(),
                'jam_range' => $rows->map(fn($r) => [
                    'id'             => $r->id,
                    'kelas'          => $r->kelas,
                    'mapel'          => $r->mapel,
                    'waktu_mulai'    => $r->waktu_mulai,
                    'waktu_berakhir' => $r->waktu_berakhir,
                ])->values(),
            ];
        })->values();

        return view('admin.partials.list_jadwal_mengajar', compact('grouped', 'hariAktif'));
    }

    /* ----------------------------------------------------------------
       STORE  –  Simpan jadwal baru
    ---------------------------------------------------------------- */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'mapel_id'       => 'required|exists:mapel,id',
            'kelas_id'       => 'required|exists:kelas,id',
            'hari'           => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'waktu_mulai'    => 'required|date_format:H:i',
            'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
        ], [
            'hari.in'                  => 'Hari tidak valid.',
            'waktu_berakhir.after'     => 'Waktu berakhir harus setelah waktu mulai.',
        ]);

        /* Cegah duplikat: guru + kelas + mapel + hari */
        $exists = JadwalMengajar::where('user_id',  $request->user_id)
                                ->where('kelas_id', $request->kelas_id)
                                ->where('mapel_id', $request->mapel_id)
                                ->where('hari',     $request->hari)
                                ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal untuk guru, kelas, dan mapel ini pada hari tersebut sudah ada.',
            ], 422);
        }

        JadwalMengajar::create([
            'user_id'        => $request->user_id,
            'mapel_id'       => $request->mapel_id,
            'kelas_id'       => $request->kelas_id,
            'hari'           => $request->hari,
            'waktu_mulai'    => $request->waktu_mulai,
            'waktu_berakhir' => $request->waktu_berakhir,
        ]);

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil ditambahkan.']);
    }

    /* ----------------------------------------------------------------
       EDIT GURU  –  Ambil semua jadwal 1 guru di 1 hari (untuk modal kelola sesi)
    ---------------------------------------------------------------- */
    public function editGuru(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'hari'    => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
        ]);

        $jadwals = JadwalMengajar::with(['mapel', 'kelas'])
            ->where('user_id', $request->user_id)
            ->where('hari',    $request->hari)
            ->orderBy('waktu_mulai')
            ->get()
            ->map(fn($j) => [
                'id'             => $j->id,
                'mapel_id'       => $j->mapel_id,
                'mapel_nama'     => $j->mapel->nama_mapel ?? '-',
                'kelas_id'       => $j->kelas_id,
                'kelas_nama'     => $j->kelas->nama_kelas ?? '-',
                'waktu_mulai'    => substr($j->waktu_mulai, 0, 5),
                'waktu_berakhir' => substr($j->waktu_berakhir, 0, 5),
            ]);

        return response()->json([
            'user_id' => $request->user_id,
            'hari'    => $request->hari,
            'jadwals' => $jadwals,
        ]);
    }

    /* ----------------------------------------------------------------
       DESTROY BULK  –  Hapus semua jadwal berdasarkan array ID
    ---------------------------------------------------------------- */
    public function destroyBulk(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'exists:jadwal_mengajar,id',
        ]);

        $deleted = JadwalMengajar::whereIn('id', $request->ids)->delete();

        return response()->json([
            'success' => true,
            'message' => $deleted . ' jadwal berhasil dihapus.',
        ]);
    }

    /* ----------------------------------------------------------------
       STORE MULTI  –  Simpan 1 atau banyak sesi sekaligus
       Body: { user_id, hari, sesi: [{mapel_id, kelas_id, waktu_mulai, waktu_berakhir}, ...] }
    ---------------------------------------------------------------- */
    public function storeMulti(Request $request)
    {
        $request->validate([
            'user_id'              => 'required|exists:users,id',
            'hari'                 => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'sesi'                 => 'required|array|min:1',
            'sesi.*.mapel_id'      => 'required|exists:mapel,id',
            'sesi.*.kelas_id'      => 'required|exists:kelas,id',
            'sesi.*.waktu_mulai'   => 'required|date_format:H:i',
            'sesi.*.waktu_berakhir'=> 'required|date_format:H:i',
        ], [
            'sesi.*.waktu_berakhir.date_format' => 'Format jam berakhir tidak valid.',
        ]);

        $errors  = [];
        $created = 0;

        foreach ($request->sesi as $idx => $s) {
            /* Cek waktu berakhir > mulai */
            if ($s['waktu_berakhir'] <= $s['waktu_mulai']) {
                $errors[] = 'Sesi ' . ($idx + 1) . ': Jam berakhir harus setelah jam mulai.';
                continue;
            }

            /* Cek duplikat */
            $dup = JadwalMengajar::where('user_id',  $request->user_id)
                                 ->where('mapel_id', $s['mapel_id'])
                                 ->where('kelas_id', $s['kelas_id'])
                                 ->where('hari',     $request->hari)
                                 ->exists();

            if ($dup) {
                $errors[] = 'Sesi ' . ($idx + 1) . ': Kombinasi guru, mapel, kelas, dan hari sudah ada.';
                continue;
            }

            JadwalMengajar::create([
                'user_id'        => $request->user_id,
                'mapel_id'       => $s['mapel_id'],
                'kelas_id'       => $s['kelas_id'],
                'hari'           => $request->hari,
                'waktu_mulai'    => $s['waktu_mulai'],
                'waktu_berakhir' => $s['waktu_berakhir'],
            ]);
            $created++;
        }

        if ($created === 0 && !empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => implode(' | ', $errors),
            ], 422);
        }

        $msg = $created . ' jadwal berhasil ditambahkan.';
        if (!empty($errors)) {
            $msg .= ' ' . count($errors) . ' sesi dilewati: ' . implode('; ', $errors);
        }

        return response()->json(['success' => true, 'message' => $msg]);
    }

    /* ----------------------------------------------------------------
       UPDATE SESI  –  Update 1 sesi (dipakai dari modal kelola sesi)
       Dipanggil dari JS satu per satu; cek duplikat hanya exclude ID ini.
    ---------------------------------------------------------------- */
    public function updateSesi(Request $request, $id)
    {
        $jadwal = JadwalMengajar::findOrFail($id);

        $request->validate([
            'mapel_id'       => 'required|exists:mapel,id',
            'kelas_id'       => 'required|exists:kelas,id',
            'waktu_mulai'    => 'required|date_format:H:i',
            'waktu_berakhir' => 'required|date_format:H:i',
        ]);

        if ($request->waktu_berakhir <= $request->waktu_mulai) {
            return response()->json([
                'success' => false,
                'message' => 'Jam berakhir harus setelah jam mulai.',
            ], 422);
        }

        /* Cek duplikat exclude diri sendiri */
        $dup = JadwalMengajar::where('user_id',  $jadwal->user_id)
                             ->where('mapel_id', $request->mapel_id)
                             ->where('kelas_id', $request->kelas_id)
                             ->where('hari',     $jadwal->hari)
                             ->where('id', '!=', $id)
                             ->exists();

        if ($dup) {
            return response()->json([
                'success' => false,
                'message' => 'Kombinasi guru, mapel, kelas, dan hari ini sudah ada pada jadwal lain.',
            ], 422);
        }

        $jadwal->update([
            'mapel_id'       => $request->mapel_id,
            'kelas_id'       => $request->kelas_id,
            'waktu_mulai'    => $request->waktu_mulai,
            'waktu_berakhir' => $request->waktu_berakhir,
        ]);

        return response()->json(['success' => true, 'message' => 'Sesi berhasil diperbarui.']);
    }

    /* ----------------------------------------------------------------
       EDIT  –  Ambil data jadwal untuk form edit (lama, tetap ada)
    ---------------------------------------------------------------- */
    public function edit($id)
    {
        $jadwal = JadwalMengajar::with(['guru', 'mapel', 'kelas'])->findOrFail($id);

        return response()->json([
            'id'             => $jadwal->id,
            'user_id'        => $jadwal->user_id,
            'guru_nama'      => $jadwal->guru->name ?? '-',
            'mapel_id'       => $jadwal->mapel_id,
            'mapel_nama'     => $jadwal->mapel->nama_mapel ?? '-',
            'kelas_id'       => $jadwal->kelas_id,
            'kelas_nama'     => $jadwal->kelas->nama_kelas ?? '-',
            'hari'           => $jadwal->hari,
            'waktu_mulai'    => substr($jadwal->waktu_mulai, 0, 5),
            'waktu_berakhir' => substr($jadwal->waktu_berakhir, 0, 5),
        ]);
    }

    /* ----------------------------------------------------------------
       UPDATE  –  Simpan perubahan jadwal
    ---------------------------------------------------------------- */
    public function update(Request $request, $id)
    {
        $jadwal = JadwalMengajar::findOrFail($id);

        $request->validate([
            'user_id'        => 'required|exists:users,id',
            'mapel_id'       => 'required|exists:mapel,id',
            'kelas_id'       => 'required|exists:kelas,id',
            'hari'           => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'waktu_mulai'    => 'required|date_format:H:i',
            'waktu_berakhir' => 'required|date_format:H:i|after:waktu_mulai',
        ], [
            'hari.in'              => 'Hari tidak valid.',
            'waktu_berakhir.after' => 'Waktu berakhir harus setelah waktu mulai.',
        ]);

        /* Cegah duplikat (kecuali record ini sendiri) */
        $exists = JadwalMengajar::where('user_id',  $request->user_id)
                                ->where('kelas_id', $request->kelas_id)
                                ->where('mapel_id', $request->mapel_id)
                                ->where('hari',     $request->hari)
                                ->where('id', '!=', $id)
                                ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal untuk guru, kelas, dan mapel ini pada hari tersebut sudah ada.',
            ], 422);
        }

        $jadwal->update([
            'user_id'        => $request->user_id,
            'mapel_id'       => $request->mapel_id,
            'kelas_id'       => $request->kelas_id,
            'hari'           => $request->hari,
            'waktu_mulai'    => $request->waktu_mulai,
            'waktu_berakhir' => $request->waktu_berakhir,
        ]);

        return response()->json(['success' => true, 'message' => 'Jadwal berhasil diperbarui.']);
    }

    /* ----------------------------------------------------------------
       DESTROY  –  Hapus jadwal
    ---------------------------------------------------------------- */
    public function destroy($id)
    {
        JadwalMengajar::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus.']);
    }

    /* ----------------------------------------------------------------
       DROPDOWN DATA  –  Guru, Mapel, Kelas untuk select modal
    ---------------------------------------------------------------- */
    public function dropdownData()
    {
        $guru  = User::where('role', 'guru')->orderBy('name')->get(['id', 'name', 'username']);
        $mapel = Mapel::orderBy('nama_mapel')->get(['id', 'nama_mapel']);
        $kelas = Kelas::orderBy('nama_kelas')->get(['id', 'nama_kelas']);

        return response()->json([
            'guru'  => $guru,
            'mapel' => $mapel,
            'kelas' => $kelas,
        ]);
    }

    /* ================================================================
       GURU — hanya melihat jadwal dirinya sendiri
    ================================================================ */

    /* ----------------------------------------------------------------
       GURU INDEX  –  Halaman Jadwal Mengajar milik guru yang login
    ---------------------------------------------------------------- */
    public function guruIndex()
    {
        return view('guru.jadwal_mengajar');
    }

    /* ----------------------------------------------------------------
       GURU LIST  –  Partial AJAX: jadwal guru yang sedang login
    ---------------------------------------------------------------- */
    public function guruList(Request $request)
    {
        $guruId    = auth()->id();
        $hariParam = $request->get('hari', '');
        $search    = trim($request->get('search', ''));

        $hariAktif = in_array($hariParam, self::HARI_VALID)
            ? $hariParam
            : (self::DOW_TO_HARI[date('N')] ?? 'Senin');

        $rows = JadwalMengajar::with(['guru', 'mapel', 'kelas'])
            ->where('user_id', $guruId)
            ->where('hari', $hariAktif)
            ->orderBy('waktu_mulai')
            ->get();

        /* Kelompokkan per kelas */
        $grouped = $rows->groupBy('kelas_id')->map(function ($items) {
            $first = $items->first();
            return [
                'kelas'     => $first->kelas,
                'hari'      => $first->hari,
                'mapel'     => $items->pluck('mapel')->unique('id')->values(),
                'jam_range' => $items->map(fn($r) => [
                    'id'             => $r->id,
                    'mapel'          => $r->mapel,
                    'waktu_mulai'    => $r->waktu_mulai,
                    'waktu_berakhir' => $r->waktu_berakhir,
                ])->values(),
            ];
        })->values();

        return view('guru.partials.list_jadwal_mengajar', compact('grouped', 'hariAktif'));
    }
}
