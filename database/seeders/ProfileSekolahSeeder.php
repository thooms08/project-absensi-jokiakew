<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileSekolahSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Satu record profil sekolah (singleton).
         * Nama sekolah dibuat generik karena sistem kini mencakup SMK & SMP.
         * Admin dapat memperbarui nama dan logo masing-masing dari halaman Profile.
         */
        DB::table('profile_sekolah')->insert([
            'nama_sekolah' => 'Ibnu Sina (SMK & SMP)',
            'deskripsi'    => 'Yayasan Ibnu Sina mengelola SMK dan SMP yang berkomitmen mencetak generasi terampil, berakhlak mulia, dan siap bersaing di era modern.',
            'logo'         => null,
            'no_hp'        => '+62852-1234-5678',
            'email'        => 'info@ibnusina.sch.id',
            'alamat'       => 'Jl. Raya Ibnu Sina No. 1, Kec. Contoh, Kab. Contoh, 12345',
            'maps_link'    => null,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }
}
