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
        $guruIds = User::where('role', 'guru')
                       ->orderBy('id')
                       ->pluck('id')
                       ->toArray();

        /*
         * Distribusi status per guru (index 0-7) selama 7 hari.
         * Format per guru: array 7 elemen, tiap elemen = status hari tsb.
         * hari[0] = 6 hari lalu, hari[6] = hari ini.
         */
        $distribusi = [
            /* Budi Santoso     */ ['hadir','hadir','hadir','hadir','hadir','hadir','hadir'],
            /* Sari Dewi        */ ['hadir','hadir','hadir','izin', 'hadir','hadir','hadir'],
            /* Ahmad Fauzi      */ ['hadir','hadir','hadir','hadir','hadir','hadir','hadir'],
            /* Rina Marlina     */ ['hadir','izin', 'hadir','hadir','tidak','hadir','tidak'],
            /* Hendra Kusuma    */ ['hadir','hadir','hadir','hadir','hadir','hadir','hadir'],
            /* Dewi Anggraini   */ ['hadir','hadir','hadir','hadir','hadir','hadir','hadir'],
            /* Rudi Hartono     */ ['hadir','hadir','tidak','hadir','hadir','izin', 'izin' ],
            /* Fitri Handayani  */ ['hadir','hadir','hadir','hadir','hadir','hadir','hadir'],
        ];

        $rows = [];

        foreach ($guruIds as $gIdx => $guruId) {
            $statusPerHari = $distribusi[$gIdx] ?? array_fill(0, 7, 'hadir');

            for ($d = 6; $d >= 0; $d--) {
                $hariKe  = 6 - $d;   /* 0 = 6 hari lalu, 6 = hari ini */
                $tanggal = Carbon::today()->subDays($d);
                $status  = $statusPerHari[$hariKe];

                $waktuAbsen = ($status === 'hadir')
                    ? $tanggal->copy()->setTime(rand(6, 7), rand(0, 59))
                    : $tanggal->copy()->setTime(0, 0);

                $rows[] = [
                    'uuid'       => (string) Str::uuid(),
                    'user_id'    => $guruId,
                    'foto_bukti' => $status === 'hadir' ? 'dummy/bukti_' . $guruId . '_' . $tanggal->format('Ymd') . '.jpg' : null,
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
