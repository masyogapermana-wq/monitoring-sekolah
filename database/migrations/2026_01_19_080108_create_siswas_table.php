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
    Schema::create('siswas', function (Blueprint $table) {
        $table->id(); // UniqueID
        $table->string('nis')->unique(); // NIS wajib unik buat QR Code
        $table->string('nama_siswa');
        $table->string('kelas'); // Misal: XII
        $table->string('jurusan'); // Misal: TKJ 1
        $table->string('foto')->nullable(); // Foto boleh kosong dulu
        $table->integer('total_poin')->default(0); // Awal masuk poin 0
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
