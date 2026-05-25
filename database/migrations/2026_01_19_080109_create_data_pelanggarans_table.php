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
    Schema::create('data_pelanggarans', function (Blueprint $table) {
        $table->id();
        // Relasi ke siswa
        $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
        // Relasi ke jenis pelanggaran
        $table->foreignId('jenis_pelanggaran_id')->constrained('jenis_pelanggarans');
        // Relasi ke user (guru yang input)
        $table->foreignId('user_id')->constrained('users');
        $table->text('catatan')->nullable(); // Keterangan tambahan
        $table->date('tanggal_kejadian'); // Kapan melanggarnya
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pelanggarans');
    }
};
