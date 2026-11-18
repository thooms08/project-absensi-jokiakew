<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /* Tambah kolom jenis_sekolah ke mapel */
        Schema::table('mapel', function (Blueprint $table) {
            $table->enum('jenis_sekolah', ['smk', 'smp'])->default('smk')->after('nama_mapel');
        });

        /* Tambah kolom jenis_sekolah ke kelas */
        Schema::table('kelas', function (Blueprint $table) {
            $table->enum('jenis_sekolah', ['smk', 'smp'])->default('smk')->after('nama_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mapel', function (Blueprint $table) {
            $table->dropColumn('jenis_sekolah');
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('jenis_sekolah');
        });
    }
};
