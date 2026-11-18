<?php

namespace Database\Seeders;

use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* Kelas untuk SMK */
        $kelasSMK = [
            'X-A', 'X-B', 'X-C',
            'XI-RPL', 'XI-TKJ', 'XI-MM',
            'XII-RPL', 'XII-TKJ', 'XII-MM',
        ];

        foreach ($kelasSMK as $nama) {
            Kelas::firstOrCreate(
                ['nama_kelas' => $nama, 'jenis_sekolah' => 'smk'],
                ['jenis_sekolah' => 'smk']
            );
        }

        /* Kelas untuk SMP */
        $kelasSMP = [
            '7-A', '7-B', '7-C', '7-D',
            '8-A', '8-B', '8-C', '8-D',
            '9-A', '9-B', '9-C', '9-D',
        ];

        foreach ($kelasSMP as $nama) {
            Kelas::firstOrCreate(
                ['nama_kelas' => $nama, 'jenis_sekolah' => 'smp'],
                ['jenis_sekolah' => 'smp']
            );
        }
    }
}
