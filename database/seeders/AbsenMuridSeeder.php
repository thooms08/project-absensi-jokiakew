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
        $muridIds = User::where('role', 'murid')
                        ->orderBy('id')
                        ->pluck('id')
                        ->toArray();

        /* Kode absen per hari: ambil kode RPL1 karena murid seeder diasumsikan 1 kelas */
        $kodePerHari = [];
        for ($d = 6; $d >= 0; $d--) {
            $tanggal           = Carbon::today()->subDays($d)->format('Ymd');
            $kodePerHari[$d]   = $tanggal . '-RPL1';
        }

        /*
         * Probabilitas kehadiran:
         *  - 85% hadir
         *  - 8%  izin
         *  - 7%  tidak hadir
         *
         * Supaya deterministik (tidak random setiap seed), gunakan
         * pola berulang berbasis indeks murid + hari.
         */
        $pola = [
            'hadir','hadir','hadir','hadir','hadir','hadir','hadir',
            'hadir','hadir','hadir','hadir','hadir','izin', 'hadir',
            'hadir','hadir','hadir','hadir','hadir','hadir','tidak',
            'hadir','hadir','hadir','hadir','hadir','hadir','hadir',
            'hadir','izin',
        ];

        $rows = [];

        foreach ($muridIds as $mIdx => $muridId) {
            for ($d = 6; $d >= 0; $d--) {
                $hariKe  = 6 - $d;
                $tanggal = Carbon::today()->subDays($d);

                /* Status diambil dari pola dengan offset berbeda tiap murid */
                $polaIdx = ($mIdx + $hariKe) % count($pola);
                $status  = $pola[$polaIdx];

                $waktuAbsen = ($status === 'hadir')
                    ? $tanggal->copy()->setTime(rand(6, 7), rand(5, 55))
                    : $tanggal->copy()->setTime(0, 0);

                $rows[] = [
                    'uuid'       => (string) Str::uuid(),
                    'user_id'    => $muridId,
                    'kode_absen' => $kodePerHari[$d],
                    'status'     => $status,
                    'alasan'     => match ($status) {
                        'izin'  => 'Sakit',
                        'tidak' => 'Tanpa Keterangan',
                        default => null,
                    },
                    'created_at' => $waktuAbsen,
                    'updated_at' => $waktuAbsen,
                ];
            }
        }

        DB::table('absen_murid')->insert($rows);
    }
}
