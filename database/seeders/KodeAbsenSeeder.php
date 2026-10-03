<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KodeAbsenSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Kode absen dibuat per hari selama 7 hari terakhir.
         * Format: YYYYMMDD-XX (tanggal + nomor urut).
         */
        $kodeList = [];

        for ($d = 6; $d >= 0; $d--) {
            $tanggal = Carbon::today()->subDays($d)->format('Ymd');

            /* Satu kode per hari untuk masing-masing kelas (4 kelas) */
            $kelasList = ['RPL1', 'RPL2', 'TKJ1', 'AK1'];

            foreach ($kelasList as $kelas) {
                $kodeList[] = [
                    'kode'       => $tanggal . '-' . $kelas,
                    'created_at' => Carbon::today()->subDays($d)->setTime(6, 30),
                    'updated_at' => Carbon::today()->subDays($d)->setTime(6, 30),
                ];
            }
        }

        DB::table('kode_absen')->insert($kodeList);
    }
}
