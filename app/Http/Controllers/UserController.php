<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan data guru/user
    public function index()
    {
        // Ambil semua user kecuali yang sedang login (biar admin gak gak sengaja hapus akunnya sendiri)
        $users = User::where('id', '!=', auth()->id())->get();
        return view('admin.user.index', compact('users'));
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password otomatis
            'role' => $request->role
        ]);

        return back()->with('success', 'Akun berhasil ditambahkan!');
    }

    // Hapus user
    public function destroy($id)
    {
        User::find($id)->delete();
        return back()->with('success', 'Akun berhasil dihapus!');
    }
}
