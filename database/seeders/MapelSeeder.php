<?php

namespace Database\Seeders;

use App\Models\Mapel;
use Illuminate\Database\Seeder;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* Mata pelajaran untuk SMK dan SMP */
        $mapelSMK = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Fisika',
            'Kimia',
            'Biologi',
            'Sejarah',
            'Geografi',
            'Ekonomi',
            'Pemrograman',
        ];

        $mapelSMP = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'IPA (Fisika)',
            'IPA (Kimia)',
            'IPA (Biologi)',
            'IPS (Sejarah)',
            'IPS (Geografi)',
            'IPS (Ekonomi)',
            'Seni Budaya',
        ];

        foreach ($mapelSMK as $nama) {
            Mapel::firstOrCreate(
                ['nama_mapel' => $nama, 'jenis_sekolah' => 'smk']
            );
        }

        foreach ($mapelSMP as $nama) {
            Mapel::firstOrCreate(
                ['nama_mapel' => $nama, 'jenis_sekolah' => 'smp']
            );
        }
    }
}
