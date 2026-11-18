<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AbsenGuruSeeder extends Seeder
{
    public function run(): void
    {
        /*
         * Ambil semua guru (SMK + SMP) urut berdasarkan id.
         * Urutan sesuai UserSeeder: 8 guru_smk dulu, lalu 6 guru_smp.
         */
        $guruIds = User::whereIn('role', ['guru_smk', 'guru_smp'])
                       ->orderBy('id')
                       ->pluck('id')
                       ->toArray();

        /*
         * Distribusi status per guru selama 7 hari terakhir.
         * hari[0] = 6 hari lalu … hari[6] = hari ini.
         * Guru ke-0..7 = SMK, guru ke-8..13 = SMP.
         */
        $distribusi = [
            /* SMK */
            /* Budi Santoso     */ ['hadir','hadir','hadir','hadir','hadir','hadir','hadir'],
            /* Sari Dewi        */ ['hadir','hadir','hadir','izin', 'hadir','hadir','hadir'],
            /* Ahmad Fauzi      */ ['hadir','hadir','hadir','hadir','hadir','hadir','hadir'],
            /* Rina Marlina     */ ['hadir','izin', 'hadir','hadir','tidak','hadir','tidak'],
            /* Hendra Kusuma    */ ['hadir','hadir','hadir','hadir','hadir','hadir','hadir'],
            /* Dewi Anggraini   */ ['hadir','hadir','hadir','hadir','hadir','hadir','hadir'],
            /* Rudi Hartono     */ ['hadir','hadir','tidak','hadir','hadir','izin', 'izin' ],
            /* Fitri Handayani  */ ['hadir','hadir','hadir','hadir','hadir','hadir','hadir'],
            /* SMP */
            /* Ali Mustofa      */ ['hadir','hadir','hadir','hadir','hadir','hadir','hadir'],
            /* Nining Rahayu    */ ['hadir','hadir','izin', 'hadir','hadir','hadir','hadir'],
            /* Teguh Wibowo     */ ['hadir','hadir','hadir','hadir','tidak','hadir','hadir'],
            /* Sri Wahyuni      */ ['hadir','hadir','hadir','hadir','hadir','hadir','hadir'],
            /* Doni Prasetyo    */ ['hadir','izin', 'hadir','hadir','hadir','hadir','hadir'],
            /* Lilis Suryani    */ ['hadir','hadir','hadir','hadir','hadir','hadir','hadir'],
        ];

        $rows = [];

        foreach ($guruIds as $gIdx => $guruId) {
            $statusPerHari = $distribusi[$gIdx] ?? array_fill(0, 7, 'hadir');

            for ($d = 6; $d >= 0; $d--) {
                $hariKe  = 6 - $d;
                $tanggal = Carbon::today()->subDays($d);
                $status  = $statusPerHari[$hariKe];

                $waktuAbsen = ($status === 'hadir')
                    ? $tanggal->copy()->setTime(rand(6, 7), rand(0, 59))
                    : $tanggal->copy()->setTime(0, 0);

                $rows[] = [
                    'uuid'       => (string) Str::uuid(),
                    'user_id'    => $guruId,
                    'foto_bukti' => $status === 'hadir'
                        ? 'dummy/bukti_' . $guruId . '_' . $tanggal->format('Ymd') . '.jpg'
                        : null,
                    'status'     => $status,
                    'alasan'     => match ($status) {
                        'izin'  => 'Keperluan keluarga',
                        'tidak' => 'Tanpa Keterangan',
                        default => null,
                    },
                    'created_at' => $waktuAbsen,
                    'updated_at' => $waktuAbsen,
                ];
            }
        }

        DB::table('absen_guru')->insert($rows);
    }
}
