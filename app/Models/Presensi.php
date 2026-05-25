<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    // TAMBAHKAN KODE INI:
    protected $fillable = [
        'siswa_id',
        'tanggal',
        'jam_masuk',
        'status'
    ];

    // Opsional: Relasi biar gampang manggil nama siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
