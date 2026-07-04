<?php

namespace App\Http\Controllers;

use App\Models\SanksiEdukatif;
use Illuminate\Http\Request;

class SanksiEdukatifController extends Controller
{
    // Menampilkan halaman sanksi
    public function index()
    {
        // Mengurutkan sanksi dari yang terbaru ditambahkan
        $sanksis = SanksiEdukatif::latest()->get();
        return view('admin.sanksi', compact('sanksis'));
    }

    // Menyimpan sanksi baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_sanksi' => 'required'
        ]);

        SanksiEdukatif::create([
            'nama_sanksi' => $request->nama_sanksi
        ]);

        return back()->with('success', 'Sanksi Edukatif berhasil ditambahkan!');
    }

    // Menghapus sanksi
    public function destroy($id)
    {
        $sanksi = SanksiEdukatif::find($id);
        if ($sanksi) {
            $sanksi->delete();
            return back()->with('success', 'Sanksi Edukatif berhasil dihapus!');
        }
        return back()->with('error', 'Data gagal dihapus.');
    }
}