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
            'email'    => 'admin@sekolah.sch.id',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        /* ----------------------------------------------------------------
           GURU SMK  (8 akun)
        ---------------------------------------------------------------- */
        $guruSmk = [
            ['budi.santoso',    'Budi Santoso',    'budi@smk.sch.id'],
            ['sari.dewi',       'Sari Dewi',       'sari@smk.sch.id'],
            ['ahmad.fauzi',     'Ahmad Fauzi',     'ahmad@smk.sch.id'],
            ['rina.marlina',    'Rina Marlina',    'rina@smk.sch.id'],
            ['hendra.kusuma',   'Hendra Kusuma',   'hendra@smk.sch.id'],
            ['dewi.anggraini',  'Dewi Anggraini',  'dewi@smk.sch.id'],
            ['rudi.hartono',    'Rudi Hartono',    'rudi@smk.sch.id'],
            ['fitri.handayani', 'Fitri Handayani', 'fitri@smk.sch.id'],
        ];

        foreach ($guruSmk as [$username, $name, $email]) {
            User::create([
                'username' => $username,
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make('guru123'),
                'role'     => 'guru_smk',
            ]);
        }

        /* ----------------------------------------------------------------
           GURU SMP  (6 akun)
        ---------------------------------------------------------------- */
        $guruSmp = [
            ['ali.mustofa',     'Ali Mustofa',     'ali@smp.sch.id'],
            ['nining.rahayu',   'Nining Rahayu',   'nining@smp.sch.id'],
            ['teguh.wibowo',    'Teguh Wibowo',    'teguh@smp.sch.id'],
            ['sri.wahyuni',     'Sri Wahyuni',     'sri@smp.sch.id'],
            ['doni.prasetyo',   'Doni Prasetyo',   'doni@smp.sch.id'],
            ['lilis.suryani',   'Lilis Suryani',   'lilis@smp.sch.id'],
        ];

        foreach ($guruSmp as [$username, $name, $email]) {
            User::create([
                'username' => $username,
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make('guru123'),
                'role'     => 'guru_smp',
            ]);
        }

        /* ----------------------------------------------------------------
           MURID SMK  (20 akun)
        ---------------------------------------------------------------- */
        $muridSmk = [
            ['aldi.pratama',    'Aldi Pratama',    'aldi@siswa.smk.sch.id'],
            ['bunga.lestari',   'Bunga Lestari',   'bunga@siswa.smk.sch.id'],
            ['candra.wijaya',   'Candra Wijaya',   'candra@siswa.smk.sch.id'],
            ['dian.safitri',    'Dian Safitri',    'dian@siswa.smk.sch.id'],
            ['eka.putra',       'Eka Putra',       'eka@siswa.smk.sch.id'],
            ['fajar.nugroho',   'Fajar Nugroho',   'fajar@siswa.smk.sch.id'],
            ['gita.permata',    'Gita Permata',    'gita@siswa.smk.sch.id'],
            ['hadi.susanto',    'Hadi Susanto',    'hadi@siswa.smk.sch.id'],
            ['indah.kurnia',    'Indah Kurnia',    'indah@siswa.smk.sch.id'],
            ['joko.prabowo',    'Joko Prabowo',    'joko@siswa.smk.sch.id'],
            ['kartika.sari',    'Kartika Sari',    'kartika@siswa.smk.sch.id'],
            ['luthfi.hakim',    'Luthfi Hakim',    'luthfi@siswa.smk.sch.id'],
            ['maya.putri',      'Maya Putri',      'maya@siswa.smk.sch.id'],
            ['nova.andriani',   'Nova Andriani',   'nova@siswa.smk.sch.id'],
            ['oscar.f',         'Oscar Firmansyah','oscar@siswa.smk.sch.id'],
            ['putri.rahayu',    'Putri Rahayu',    'putri@siswa.smk.sch.id'],
            ['qori.ramadhan',   'Qori Ramadhan',   'qori@siswa.smk.sch.id'],
            ['rizky.amalia',    'Rizky Amalia',    'rizky@siswa.smk.sch.id'],
            ['sinta.w',         'Sinta Wulandari', 'sinta@siswa.smk.sch.id'],
            ['taufik.hidayat',  'Taufik Hidayat',  'taufik@siswa.smk.sch.id'],
        ];

        foreach ($muridSmk as [$username, $name, $email]) {
            User::create([
                'username' => $username,
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make('murid123'),
                'role'     => 'murid_smk',
            ]);
        }

        /* ----------------------------------------------------------------
           MURID SMP  (15 akun)
        ---------------------------------------------------------------- */
        $muridSmp = [
            ['umar.bakri',      'Umar Bakri',      'umar@siswa.smp.sch.id'],
            ['vina.melinda',    'Vina Melinda',    'vina@siswa.smp.sch.id'],
            ['wahyu.setiawan',  'Wahyu Setiawan',  'wahyu@siswa.smp.sch.id'],
            ['xena.olivia',     'Xena Olivia',     'xena@siswa.smp.sch.id'],
            ['yogi.prasetyo',   'Yogi Prasetyo',   'yogi@siswa.smp.sch.id'],
            ['zahra.nabilah',   'Zahra Nabilah',   'zahra@siswa.smp.sch.id'],
            ['arif.rahman',     'Arif Rahman',     'arif@siswa.smp.sch.id'],
            ['bella.oktavia',   'Bella Oktavia',   'bella@siswa.smp.sch.id'],
            ['ciko.putranto',   'Ciko Putranto',   'ciko@siswa.smp.sch.id'],
            ['devi.k',          'Devi Kusumawati', 'devi@siswa.smp.sch.id'],
            ['evan.santoso',    'Evan Santoso',    'evan@siswa.smp.sch.id'],
            ['fira.azzahra',    'Fira Azzahra',    'fira@siswa.smp.sch.id'],
            ['galih.wicaksono', 'Galih Wicaksono', 'galih@siswa.smp.sch.id'],
            ['hana.pertiwi',    'Hana Pertiwi',    'hana@siswa.smp.sch.id'],
            ['ilham.maulana',   'Ilham Maulana',   'ilham@siswa.smp.sch.id'],
        ];

        foreach ($muridSmp as [$username, $name, $email]) {
            User::create([
                'username' => $username,
                'name'     => $name,
                'email'    => $email,
                'password' => Hash::make('murid123'),
                'role'     => 'murid_smp',
            ]);
        }
    }
}
