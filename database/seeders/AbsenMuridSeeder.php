<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AbsenMuridSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Ambil semua murid SMK dan SMP secara terpisah
         * agar kode absen yang dipakai sesuai kelas masing-masing.
         */
        $muridSmkIds = User::where('role', 'murid_smk')
                           ->orderBy('id')
                           ->pluck('id')
                           ->toArray();

        $muridSmpIds = User::where('role', 'murid_smp')
                           ->orderBy('id')
                           ->pluck('id')
                           ->toArray();

        /*
         * Pola kehadiran deterministik (tidak random setiap seed).
         * Dipakai bergantian berdasarkan (indeks_murid + hari_ke) % panjang_pola.
         *   85% hadir, 8% izin, 7% tidak hadir
         */
        $pola = [
            'hadir','hadir','hadir','hadir','hadir','hadir','hadir',
            'hadir','hadir','hadir','hadir','hadir','izin', 'hadir',
            'hadir','hadir','hadir','hadir','hadir','hadir','tidak',
            'hadir','hadir','hadir','hadir','hadir','hadir','hadir',
            'hadir','izin',
        ];

        $rows = [];

        /* ---- Murid SMK — kode absen pakai kelas RPL1 ---- */
        $kodeSmk = [];
        for ($d = 6; $d >= 0; $d--) {
            $kodeSmk[$d] = Carbon::today()->subDays($d)->format('Ymd') . '-RPL1';
        }

        foreach ($muridSmkIds as $mIdx => $muridId) {
            for ($d = 6; $d >= 0; $d--) {
                $hariKe  = 6 - $d;
                $tanggal = Carbon::today()->subDays($d);
                $status  = $pola[($mIdx + $hariKe) % count($pola)];

                $waktu = ($status === 'hadir')
                    ? $tanggal->copy()->setTime(rand(6, 7), rand(5, 55))
                    : $tanggal->copy()->setTime(0, 0);

                $rows[] = [
                    'uuid'       => (string) Str::uuid(),
                    'user_id'    => $muridId,
                    'kode_absen' => $kodeSmk[$d],
                    'status'     => $status,
                    'alasan'     => match ($status) {
                        'izin'  => 'Sakit',
                        'tidak' => 'Tanpa Keterangan',
                        default => null,
                    },
                    'created_at' => $waktu,
                    'updated_at' => $waktu,
                ];
            }
        }

        /* ---- Murid SMP — kode absen pakai kelas VII-A ---- */
        $kodeSmp = [];
        for ($d = 6; $d >= 0; $d--) {
            $kodeSmp[$d] = Carbon::today()->subDays($d)->format('Ymd') . '-VIIA';
        }

        foreach ($muridSmpIds as $mIdx => $muridId) {
            for ($d = 6; $d >= 0; $d--) {
                $hariKe  = 6 - $d;
                $tanggal = Carbon::today()->subDays($d);
                $status  = $pola[($mIdx + $hariKe + 7) % count($pola)]; /* offset +7 agar beda pola dari SMK */

                $waktu = ($status === 'hadir')
                    ? $tanggal->copy()->setTime(rand(6, 7), rand(5, 55))
                    : $tanggal->copy()->setTime(0, 0);

                $rows[] = [
                    'uuid'       => (string) Str::uuid(),
                    'user_id'    => $muridId,
                    'kode_absen' => $kodeSmp[$d],
                    'status'     => $status,
                    'alasan'     => match ($status) {
                        'izin'  => 'Sakit',
                        'tidak' => 'Tanpa Keterangan',
                        default => null,
                    },
                    'created_at' => $waktu,
                    'updated_at' => $waktu,
                ];
            }
        }

        DB::table('absen_murid')->insert($rows);
    }
}
