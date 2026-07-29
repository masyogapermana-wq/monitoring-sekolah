<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    // Pastikan keempat kolom baru ini dimasukkan ke dalam array $fillable
    // (Jika ada kolom lama seperti 'jam_masuk' biarkan saja, cukup tambahkan yang baru)
    protected $fillable = [
        'jam_masuk', // Kolom lama lu
        'mulai_hadir',
        'batas_hadir',
        'batas_terlambat',
        'batas_alpa'
    ];
}
