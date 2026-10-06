<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absen_murid', function (Blueprint $table) {
            /* Jadwal yang sedang diabsen */
            $table->foreignId('jadwal_mengajar_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('jadwal_mengajar')
                  ->nullOnDelete();

            /* Tanggal absen dilakukan */
            $table->date('tanggal_absen')->nullable()->after('jadwal_mengajar_id');

            /* Foto selfie untuk absen hadir */
            $table->string('foto_selfie')->nullable()->after('alasan');

            /* Foto bukti untuk izin */
            $table->string('foto_izin')->nullable()->after('foto_selfie');

            /* Sudah dikonfirmasi guru */
            $table->timestamp('dikonfirmasi_at')->nullable()->after('foto_izin');
        });
    }

    public function down(): void
    {
        Schema::table('absen_murid', function (Blueprint $table) {
            $table->dropForeign(['jadwal_mengajar_id']);
            $table->dropColumn(['jadwal_mengajar_id', 'tanggal_absen', 'foto_selfie', 'foto_izin', 'dikonfirmasi_at']);
        });
    }
};
