<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kode_absen', function (Blueprint $table) {
            /* Guru yang membuat kode */
            $table->foreignId('user_id')
                  ->nullable()
                  ->after('id')
                  ->constrained()
                  ->nullOnDelete();

            /* Jadwal yang dikaitkan */
            $table->foreignId('jadwal_mengajar_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('jadwal_mengajar')
                  ->nullOnDelete();

            /* Kode berlaku hingga (5 menit sejak dibuat) */
            $table->timestamp('expired_at')->nullable()->after('kode');

            /* Kode hanya aktif saat is_active=true; guru bisa nonaktifkan manual */
            $table->boolean('is_active')->default(true)->after('expired_at');
        });
    }

    public function down(): void
    {
        Schema::table('kode_absen', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['jadwal_mengajar_id']);
            $table->dropColumn(['user_id', 'jadwal_mengajar_id', 'expired_at', 'is_active']);
        });
    }
};
