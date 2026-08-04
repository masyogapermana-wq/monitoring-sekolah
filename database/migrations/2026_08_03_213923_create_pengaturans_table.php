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
    Schema::create('pengaturans', function (Blueprint $table) {
        $table->id();
        // Pastikan baris-baris ini ada di dalamnya
        $table->time('mulai_hadir')->default('06:00:00');
        $table->time('batas_hadir')->default('07:30:00');
        $table->time('batas_terlambat')->default('08:00:00');
        $table->time('batas_alpa')->default('15:00:00');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};
