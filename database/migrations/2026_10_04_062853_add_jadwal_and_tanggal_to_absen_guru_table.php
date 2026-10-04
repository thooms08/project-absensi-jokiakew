<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom jadwal_mengajar_id dan tanggal_absen ke tabel absen_guru.
     * jadwal_mengajar_id  → menghubungkan absen ke jadwal spesifik
     * tanggal_absen       → tanggal aktual absen dilakukan (Y-m-d)
     */
    public function up(): void
    {
        Schema::table('absen_guru', function (Blueprint $table) {
            $table->foreignId('jadwal_mengajar_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('jadwal_mengajar')
                  ->nullOnDelete();

            $table->date('tanggal_absen')
                  ->nullable()
                  ->after('jadwal_mengajar_id');
        });
    }

    public function down(): void
    {
        Schema::table('absen_guru', function (Blueprint $table) {
            $table->dropForeign(['jadwal_mengajar_id']);
            $table->dropColumn(['jadwal_mengajar_id', 'tanggal_absen']);
        });
    }
};
