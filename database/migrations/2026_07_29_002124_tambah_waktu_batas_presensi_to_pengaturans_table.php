<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pengaturans', function (Blueprint $table) {
            $table->time('mulai_hadir')->default('06:00:00')->nullable();
            $table->time('batas_hadir')->default('07:30:00')->nullable();
            $table->time('batas_terlambat')->default('08:00:00')->nullable();
            $table->time('batas_alpa')->default('15:00:00')->nullable();
        });
    }

    public function down()
    {
        Schema::table('pengaturans', function (Blueprint $table) {
            $table->dropColumn(['mulai_hadir', 'batas_hadir', 'batas_terlambat', 'batas_alpa']);
        });
    }
};
