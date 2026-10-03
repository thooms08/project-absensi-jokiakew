<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('verifikasi', function (Blueprint $table) {
            $table->enum('role', ['guru', 'murid'])->default('guru')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('verifikasi', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
