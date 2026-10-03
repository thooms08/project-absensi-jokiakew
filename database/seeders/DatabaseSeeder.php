<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Urutan penting:
     *  1. ProfileSekolahSeeder  - tidak ada dependensi
     *  2. UserSeeder            - mengisi tabel users (FK dipakai seeder lain)
     *  3. KodeAbsenSeeder       - mengisi tabel kode_absen (FK dipakai AbsenMuridSeeder)
     *  4. WaliMuridSeeder       - butuh user_id murid
     *  5. AbsenGuruSeeder       - butuh user_id guru
     *  6. AbsenMuridSeeder      - butuh user_id murid + kode_absen
     */
    public function run(): void
    {
        $this->call([
            ProfileSekolahSeeder::class,
            UserSeeder::class,
            KodeAbsenSeeder::class,
            WaliMuridSeeder::class,
            AbsenGuruSeeder::class,
            AbsenMuridSeeder::class,
        ]);
    }
}
