<?php

namespace App\Http\Controllers;

use App\Models\KodeAbsen;
use App\Models\JadwalMengajar;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class KodeAbsenController extends Controller
{
    private const DOW_TO_HARI = [
        1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu',
    ];

    /* ----------------------------------------------------------------
       BUAT KODE  –  Guru membuat kode absen 6 digit (valid 5 menit)
    ---------------------------------------------------------------- */
    public function buat(Request $request)
    {
        $guruId = auth()->id();

        $request->validate([
            'jadwal_mengajar_id' => 'required|exists:jadwal_mengajar,id',
        ]);

        /* Pastikan jadwal milik guru ini */
        $jadwal = JadwalMengajar::where('id', $request->jadwal_mengajar_id)
                                ->where('user_id', $guruId)
                                ->firstOrFail();

        /* Non-aktifkan kode lama untuk jadwal yang sama */
        KodeAbsen::where('user_id', $guruId)
                 ->where('jadwal_mengajar_id', $jadwal->id)
                 ->where('is_active', true)
                 ->update(['is_active' => false]);

        /* Buat kode unik 6 karakter (huruf besar + angka) */
        do {
            $kode = strtoupper(Str::random(3) . random_int(100, 999));
        } while (KodeAbsen::where('kode', $kode)->where('is_active', true)->exists());

        $kodeAbsen = KodeAbsen::create([
            'user_id'            => $guruId,
            'jadwal_mengajar_id' => $jadwal->id,
            'kode'               => $kode,
            'expired_at'         => Carbon::now()->addMinutes(5),
            'is_active'          => true,
        ]);

        return response()->json([
            'success'    => true,
            'kode'       => $kode,
            'expired_at' => $kodeAbsen->expired_at->format('H:i:s'),
            'sisa_detik' => 300,    /* 5 menit */
        ]);
    }

    /* ----------------------------------------------------------------
       STATUS KODE  –  Cek apakah kode masih aktif (polling dari frontend)
    ---------------------------------------------------------------- */
    public function status(Request $request)
    {
        $request->validate(['jadwal_mengajar_id' => 'required|exists:jadwal_mengajar,id']);

        $kode = KodeAbsen::where('user_id', auth()->id())
                         ->where('jadwal_mengajar_id', $request->jadwal_mengajar_id)
                         ->where('is_active', true)
                         ->latest()
                         ->first();

        if (!$kode || !$kode->masihBerlaku()) {
            return response()->json(['aktif' => false]);
        }

        return response()->json([
            'aktif'      => true,
            'kode'       => $kode->kode,
            'sisa_detik' => max(0, Carbon::now()->diffInSeconds($kode->expired_at, false) * -1 + 300),
        ]);
    }

    /* ----------------------------------------------------------------
       JADWAL GURU HARI INI  –  Daftar jadwal guru untuk dropdown modal kode
    ---------------------------------------------------------------- */
    public function jadwalHariIni()
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
}
