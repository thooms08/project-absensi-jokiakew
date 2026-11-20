<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Hapus fitur kode absen & selfie murid:
     * 1. Drop kolom kode_absen dan foto_selfie dari tabel absen_murid
     * 2. Drop tabel kode_absen beserta foreign key-nya
     */
    public function up(): void
    {
        /* ── 1. Bersihkan kolom di absen_murid ── */
        Schema::table('absen_murid', function (Blueprint $table) {
            $table->dropColumn(['kode_absen', 'foto_selfie']);
        });

        /* ── 2. Drop tabel kode_absen ── */
        Schema::dropIfExists('kode_absen');
    }

    public function down(): void
    {
        /* ── Kembalikan kolom di absen_murid ── */
        Schema::table('absen_murid', function (Blueprint $table) {
            $table->string('kode_absen')->nullable()->after('tanggal_absen');
            $table->string('foto_selfie')->nullable()->after('alasan');
        });

        /* ── Buat ulang tabel kode_absen ── */
        Schema::create('kode_absen', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();
            $table->foreignId('jadwal_mengajar_id')
                  ->nullable()
                  ->constrained('jadwal_mengajar')
                  ->nullOnDelete();
            $table->timestamp('expired_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};
