<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker; // Memanggil fitur pembuat data palsu

class SiswaSeeder extends Seeder
{
    /**
     * Menjalankan mesin pembuat data.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // 1. Membuat HANYA 22 Siswa untuk Kelas X TKJ 1
        for($i = 1; $i <= 22; $i++){
            DB::table('siswas')->insert([
                'nis' => $faker->unique()->numerify('#####'),
                'nama_siswa' => $faker->name,
                'kelas' => 'X TKJ 1', // Di-set pasti, tidak diacak
                'jurusan' => 'Teknik Komputer Jaringan',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Membuat 3 Siswa dari Kelas/Jurusan Berbeda (Contoh: X RPL 2)
        for($i = 1; $i <= 3; $i++){
            DB::table('siswas')->insert([
                'nis' => $faker->unique()->numerify('#####'),
                'nama_siswa' => $faker->name,
                'kelas' => 'X RPL 2', // Di-set pasti untuk 3 orang ini
                'jurusan' => 'Rekayasa Perangkat Lunak',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
