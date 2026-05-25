<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use Illuminate\Http\Request;

class JenisPelanggaranController extends Controller
{
    public function index()
    {
        $pelanggarans = JenisPelanggaran::all();
        return view('admin.pelanggaran.index', compact('pelanggarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelanggaran' => 'required'
        ]);

        // Kita kirim diam-diam nilai poin = 0 biar database nggak error
        JenisPelanggaran::create([
            'nama_pelanggaran' => $request->nama_pelanggaran,
            'poin' => 0
        ]);

        return back()->with('success', 'Jenis pelanggaran berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        JenisPelanggaran::find($id)->delete();
        return back()->with('success', 'Jenis pelanggaran berhasil dihapus!');
    }
}
