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
         * Format: YYYYMMDD-<KELAS>
         *
         * SMK — kelas RPL1, RPL2, TKJ1, AK1
         * SMP — kelas VIIA, VIIB, VIIIA, IXA
         */
        $kelasSmk = ['RPL1', 'RPL2', 'TKJ1', 'AK1'];
        $kelasSmp = ['VIIA', 'VIIB', 'VIIIA', 'IXA'];

        $kodeList = [];

        for ($d = 6; $d >= 0; $d--) {
            $tanggal = Carbon::today()->subDays($d)->format('Ymd');
            $waktu   = Carbon::today()->subDays($d)->setTime(6, 30);

            foreach (array_merge($kelasSmk, $kelasSmp) as $kelas) {
                $kodeList[] = [
                    'kode'       => $tanggal . '-' . $kelas,
                    'created_at' => $waktu,
                    'updated_at' => $waktu,
                ];
            }
        }

        DB::table('kode_absen')->insert($kodeList);
    }
}
