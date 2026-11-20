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
     *  3. WaliMuridSeeder       - butuh user_id murid
     *  4. AbsenGuruSeeder       - butuh user_id guru
     *  5. AbsenMuridSeeder      - butuh user_id murid
     */
    public function run(): void
    {
        $this->call([
            ProfileSekolahSeeder::class,
            UserSeeder::class,
            MapelSeeder::class,
            KelasSeeder::class,
            WaliMuridSeeder::class,
            AbsenGuruSeeder::class,
            AbsenMuridSeeder::class,
        ]);
    }
}
