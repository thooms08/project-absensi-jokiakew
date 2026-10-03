<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /* ----------------------------------------------------------------
           ADMIN  (1 akun)
        ---------------------------------------------------------------- */
        User::create([
            'username' => 'admin',
            'name'     => 'Administrator',
            'email'    => 'admin@smksibnusina.sch.id',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        /* ----------------------------------------------------------------
           GURU  (8 akun)
        ---------------------------------------------------------------- */
        $guruList = [
            ['budi.santoso',     'Budi Santoso',       'budi@smksibnusina.sch.id'],
            ['sari.dewi',        'Sari Dewi',          'sari@smksibnusina.sch.id'],
            ['ahmad.fauzi',      'Ahmad Fauzi',        'ahmad@smksibnusina.sch.id'],
            ['rina.marlina',     'Rina Marlina',       'rina@smksibnusina.sch.id'],
            ['hendra.kusuma',    'Hendra Kusuma',      'hendra@smksibnusina.sch.id'],
            ['dewi.anggraini',   'Dewi Anggraini',     'dewi@smksibnusina.sch.id'],
            ['rudi.hartono',     'Rudi Hartono',       'rudi@smksibnusina.sch.id'],
            ['fitri.handayani',  'Fitri Handayani',    'fitri@smksibnusina.sch.id'],
        ];

        foreach ($guruList as [$username, $name, $email]) {
            User::create([
                'username' => $username,
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make('guru123'),
                'role'     => 'guru',
            ]);
        }

        /* ----------------------------------------------------------------
           MURID  (30 akun)
        ---------------------------------------------------------------- */
        $muridList = [
            ['aldi.pratama',     'Aldi Pratama',      'aldi@siswa.smksibnusina.sch.id'],
            ['bunga.lestari',    'Bunga Lestari',     'bunga@siswa.smksibnusina.sch.id'],
            ['candra.wijaya',    'Candra Wijaya',     'candra@siswa.smksibnusina.sch.id'],
            ['dian.safitri',     'Dian Safitri',      'dian@siswa.smksibnusina.sch.id'],
            ['eka.putra',        'Eka Putra',         'eka@siswa.smksibnusina.sch.id'],
            ['fajar.nugroho',    'Fajar Nugroho',     'fajar@siswa.smksibnusina.sch.id'],
            ['gita.permata',     'Gita Permata',      'gita@siswa.smksibnusina.sch.id'],
            ['hadi.susanto',     'Hadi Susanto',      'hadi@siswa.smksibnusina.sch.id'],
            ['indah.kurnia',     'Indah Kurnia',      'indah@siswa.smksibnusina.sch.id'],
            ['joko.prabowo',     'Joko Prabowo',      'joko@siswa.smksibnusina.sch.id'],
            ['kartika.sari',     'Kartika Sari',      'kartika@siswa.smksibnusina.sch.id'],
            ['luthfi.hakim',     'Luthfi Hakim',      'luthfi@siswa.smksibnusina.sch.id'],
            ['maya.putri',       'Maya Putri',        'maya@siswa.smksibnusina.sch.id'],
            ['nova.andriani',    'Nova Andriani',     'nova@siswa.smksibnusina.sch.id'],
            ['oscar.firmansyah', 'Oscar Firmansyah',  'oscar@siswa.smksibnusina.sch.id'],
            ['putri.rahayu',     'Putri Rahayu',      'putri@siswa.smksibnusina.sch.id'],
            ['qori.ramadhan',    'Qori Ramadhan',     'qori@siswa.smksibnusina.sch.id'],
            ['rizky.amalia',     'Rizky Amalia',      'rizky@siswa.smksibnusina.sch.id'],
            ['sinta.wulandari',  'Sinta Wulandari',   'sinta@siswa.smksibnusina.sch.id'],
            ['taufik.hidayat',   'Taufik Hidayat',    'taufik@siswa.smksibnusina.sch.id'],
            ['umar.bakri',       'Umar Bakri',        'umar@siswa.smksibnusina.sch.id'],
            ['vina.melinda',     'Vina Melinda',      'vina@siswa.smksibnusina.sch.id'],
            ['wahyu.setiawan',   'Wahyu Setiawan',    'wahyu@siswa.smksibnusina.sch.id'],
            ['xena.olivia',      'Xena Olivia',       'xena@siswa.smksibnusina.sch.id'],
            ['yogi.prasetyo',    'Yogi Prasetyo',     'yogi@siswa.smksibnusina.sch.id'],
            ['zahra.nabilah',    'Zahra Nabilah',     'zahra@siswa.smksibnusina.sch.id'],
            ['arif.rahman',      'Arif Rahman',       'arif@siswa.smksibnusina.sch.id'],
            ['bella.oktavia',    'Bella Oktavia',     'bella@siswa.smksibnusina.sch.id'],
            ['ciko.putranto',    'Ciko Putranto',     'ciko@siswa.smksibnusina.sch.id'],
            ['devi.kusumawati',  'Devi Kusumawati',   'devi@siswa.smksibnusina.sch.id'],
        ];

        foreach ($muridList as [$username, $name, $email]) {
            User::create([
                'username' => $username,
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make('murid123'),
                'role'     => 'murid',
            ]);
        }
    }
}
