<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan migration.
     *
     * Ubah enum role users: [guru, murid, admin] → [guru_smk, guru_smp, murid_smk, murid_smp, admin]
     * Ubah enum role verifikasi: [guru, murid]   → [guru_smk, guru_smp, murid_smk, murid_smp]
     */
    public function up(): void
    {
        /*
         * Urutan penting di MySQL strict mode:
         * 1. Perluas ENUM agar menerima nilai baru DAN nilai lama sekaligus
         * 2. Migrasi data lama ke nilai baru
         * 3. Persempit ENUM ke hanya nilai baru
         */

        /* Step 1 — ENUM users sementara: semua nilai lama + baru */
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('guru','murid','admin','guru_smk','guru_smp','murid_smk','murid_smp') NOT NULL DEFAULT 'admin'");

        /* Step 1b — ENUM verifikasi sementara */
        DB::statement("ALTER TABLE verifikasi MODIFY COLUMN role ENUM('guru','murid','guru_smk','guru_smp','murid_smk','murid_smp') NOT NULL DEFAULT 'guru'");

        /* Step 2 — Migrasi data lama */
        DB::statement("UPDATE users     SET role = 'guru_smk'  WHERE role = 'guru'");
        DB::statement("UPDATE users     SET role = 'murid_smk' WHERE role = 'murid'");
        DB::statement("UPDATE verifikasi SET role = 'guru_smk'  WHERE role = 'guru'");
        DB::statement("UPDATE verifikasi SET role = 'murid_smk' WHERE role = 'murid'");

        /* Step 3 — Persempit ke enum final (tanpa nilai lama) */
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('guru_smk','guru_smp','murid_smk','murid_smp','admin') NOT NULL DEFAULT 'admin'");
        DB::statement("ALTER TABLE verifikasi MODIFY COLUMN role ENUM('guru_smk','guru_smp','murid_smk','murid_smp') NOT NULL DEFAULT 'guru_smk'");
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        /* Kembalikan data dulu */
        DB::statement("UPDATE users SET role = 'guru'  WHERE role IN ('guru_smk','guru_smp')");
        DB::statement("UPDATE users SET role = 'murid' WHERE role IN ('murid_smk','murid_smp')");

        DB::statement("UPDATE verifikasi SET role = 'guru'  WHERE role IN ('guru_smk','guru_smp')");
        DB::statement("UPDATE verifikasi SET role = 'murid' WHERE role IN ('murid_smk','murid_smp')");

        /* Kembalikan enum */
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('guru','murid','admin') NOT NULL DEFAULT 'admin'");
        DB::statement("ALTER TABLE verifikasi MODIFY COLUMN role ENUM('guru','murid') NOT NULL DEFAULT 'guru'");
    }
};
