<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanksiEdukatif extends Model
{
    use HasFactory;

    // Membuka izin agar form web bisa menyimpan data ke kolom ini
    protected $fillable = [
        'nama_sanksi',
        'poin_minimal'
    ];
}
