<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_pelanggarans', function (Blueprint $table) {
            // Menambahkan kolom sanksi setelah kolom catatan
            $table->string('sanksi')->nullable()->after('catatan');
        });
    }

    public function down(): void
    {
        Schema::table('data_pelanggarans', function (Blueprint $table) {
            $table->dropColumn('sanksi');
        });
    }
};