<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    // Tambahkan Kode Ini:
    protected $fillable = [
        'nis',
        'nama_siswa',
        'kelas',
        'jurusan',
        'foto',
        'total_poin'
    ];
}
