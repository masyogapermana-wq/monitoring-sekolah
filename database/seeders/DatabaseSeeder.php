<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin (Tata Usaha)
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@smk.com',
            'password' => Hash::make('password'), // Passwordnya: password
            'role' => 'admin',
        ]);

        // 2. Akun Guru Piket
        User::create([
            'name' => 'Pak Guru Piket',
            'email' => 'piket@smk.com',
            'password' => Hash::make('password'),
            'role' => 'piket',
        ]);

        // 3. Akun Guru BK
        User::create([
            'name' => 'Bu Guru BK',
            'email' => 'bk@smk.com',
            'password' => Hash::make('password'),
            'role' => 'bk',
        ]);

        // Kita juga bisa isi jenis pelanggaran awal biar nggak kosong
        \App\Models\JenisPelanggaran::create(['nama_pelanggaran' => 'Terlambat < 15 Menit', 'poin' => 5]);
        \App\Models\JenisPelanggaran::create(['nama_pelanggaran' => 'Terlambat > 15 Menit', 'poin' => 10]);
        \App\Models\JenisPelanggaran::create(['nama_pelanggaran' => 'Seragam Tidak Lengkap', 'poin' => 5]);
        \App\Models\JenisPelanggaran::create(['nama_pelanggaran' => 'Membolos', 'poin' => 50]);
    }
}
