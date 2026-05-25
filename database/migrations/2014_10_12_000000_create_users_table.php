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
    Schema::create('users', function (Blueprint $table) {
        $table->id(); // Ini id_user
        $table->string('name'); // Nama Lengkap
        $table->string('email')->unique(); // Kita pakai email buat login (lebih standar)
        $table->string('password');
        // Role: Admin, Piket, BK
        $table->enum('role', ['admin', 'piket', 'bk'])->default('piket');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
