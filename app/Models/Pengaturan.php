<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    // Tambahkan baris ini agar sistem mengizinkan jam disimpan
    protected $fillable = [
        'mulai_hadir',
        'batas_hadir',
        'batas_terlambat',
        'batas_alpa'
    ];
}
