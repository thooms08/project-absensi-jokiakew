<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Str;

class WaliMuridSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Ambil semua murid (SMK + SMP) urut berdasarkan id.
         * Jumlah total: 20 murid SMK + 15 murid SMP = 35 murid.
         */
        $muridIds = User::whereIn('role', ['murid_smk', 'murid_smp'])
                        ->orderBy('id')
                        ->pluck('id')
                        ->toArray();

        /* Nama wali untuk 35 murid */
        $waliList = [
            /* SMK (20) */
            ['Bapak Agus Pratama',      '08111000001'],
            ['Ibu Sri Lestari',         '08111000002'],
            ['Bapak Dodi Wijaya',       '08111000003'],
            ['Ibu Yuni Safitri',        '08111000004'],
            ['Bapak Surya Putra',       '08111000005'],
            ['Ibu Wati Nugroho',        '08111000006'],
            ['Bapak Hari Permata',      '08111000007'],
            ['Ibu Endang Susanto',      '08111000008'],
            ['Bapak Bambang Kurnia',    '08111000009'],
            ['Ibu Siti Prabowo',        '08111000010'],
            ['Bapak Danu Sari',         '08111000011'],
            ['Ibu Halimah Hakim',       '08111000012'],
            ['Bapak Eko Mulyono',       '08111000013'],
            ['Ibu Ratna Andriani',      '08111000014'],
            ['Bapak Roni Firmansyah',   '08111000015'],
            ['Ibu Lastri Rahayu',       '08111000016'],
            ['Bapak Zulkifli Ramadhan', '08111000017'],
            ['Ibu Suci Amalia',         '08111000018'],
            ['Bapak Gatot Santoso',     '08111000019'],
            ['Ibu Nurul Hidayat',       '08111000020'],
            /* SMP (15) */
            ['Bapak Salim Bakri',       '08111000021'],
            ['Ibu Tini Melinda',        '08111000022'],
            ['Bapak Haris Setiawan',    '08111000023'],
            ['Ibu Farida Olivia',       '08111000024'],
            ['Bapak Nanang Prasetyo',   '08111000025'],
            ['Ibu Aminah Nabilah',      '08111000026'],
            ['Bapak Jefri Rahman',      '08111000027'],
            ['Ibu Maryam Oktavia',      '08111000028'],
            ['Bapak Tejo Putranto',     '08111000029'],
            ['Ibu Sumarni Kusumawati',  '08111000030'],
            ['Bapak Heru Santoso',      '08111000031'],
            ['Ibu Dewi Azzahra',        '08111000032'],
            ['Bapak Sutrisno Wicaksono','08111000033'],
            ['Ibu Ani Pertiwi',         '08111000034'],
            ['Bapak Rohman Maulana',    '08111000035'],
        ];

        $rows = [];
        foreach ($muridIds as $idx => $userId) {
            [$nama, $noWa] = $waliList[$idx];
            $rows[] = [
                'uuid'       => (string) Str::uuid(),
                'user_id'    => $userId,
                'nama'       => $nama,
                'no_wa'      => $noWa,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('wali_murid')->insert($rows);
    }
}
