<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('pengaturans', function (Blueprint $table) {
            $table->dropColumn(['mulai_hadir', 'batas_hadir', 'batas_terlambat', 'batas_alpa']);
        });
    }
};
