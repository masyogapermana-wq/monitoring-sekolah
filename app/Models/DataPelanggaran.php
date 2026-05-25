<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPelanggaran extends Model
{
    use HasFactory;

    // TAMBAHKAN KODE INI:
    protected $fillable = [
        'siswa_id',
        'jenis_pelanggaran_id',
        'user_id',
        'catatan',
        'tanggal_kejadian',
        'sanksi'
    ];
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function jenisPelanggaran()
    {
        return $this->belongsTo(JenisPelanggaran::class, 'jenis_pelanggaran_id');
    }

}
