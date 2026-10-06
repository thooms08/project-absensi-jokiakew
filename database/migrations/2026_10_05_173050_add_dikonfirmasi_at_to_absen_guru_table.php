<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom dikonfirmasi_at (timestamp nullable) ke tabel absen_guru.
     * NULL  = belum dikonfirmasi admin → tampil di notif lonceng
     * filled = sudah dikonfirmasi admin → tidak muncul lagi di notif
     */
    public function up(): void
    {
        Schema::table('absen_guru', function (Blueprint $table) {
            $table->timestamp('dikonfirmasi_at')->nullable()->after('alasan');
        });
    }

    public function down(): void
    {
        Schema::table('absen_guru', function (Blueprint $table) {
            $table->dropColumn('dikonfirmasi_at');
        });
    }
};
