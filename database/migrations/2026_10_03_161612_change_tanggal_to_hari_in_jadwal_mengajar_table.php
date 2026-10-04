<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ganti kolom `tanggal` (date) menjadi `hari` (enum Senin-Sabtu).
     */
    public function up(): void
    {
        Schema::table('jadwal_mengajar', function (Blueprint $table) {
            $table->dropColumn('tanggal');
            $table->enum('hari', ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'])
                  ->after('kelas_id');
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_mengajar', function (Blueprint $table) {
            $table->dropColumn('hari');
            $table->date('tanggal')->after('kelas_id');
        });
    }
};
