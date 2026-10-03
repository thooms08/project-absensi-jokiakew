<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfileSekolahSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('profile_sekolah')->insert([
            'nama_sekolah' => 'SMKS Ibnu Sina',
            'deskripsi'    => 'SMKS Ibnu Sina adalah sekolah menengah kejuruan yang berkomitmen mencetak generasi terampil, berakhlak mulia, dan siap bersaing di dunia industri modern berbasis teknologi.',
            'logo'         => 'logo.png',
            'no_hp'        => '+62852-1234-5678',
            'email'        => 'info@smksibnusina.sch.id',
            'alamat'       => 'Jl. Raya Ibnu Sina No. 1, Kec. Contoh, Kab. Contoh, Provinsi, 12345',
            'maps_link'    => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.0!2d106.8!3d-6.2!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMDAuMCJTIDEwNsKwNDgnMDAuMCJF!5e0!3m2!1sid!2sid!4v1234567890',
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }
}
