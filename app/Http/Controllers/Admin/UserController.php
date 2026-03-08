<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 📋 Tampilkan daftar petugas
    public function index()
    {
        $petugas = User::where('role', 'petugas')->get();
        return view('admin.users.index', compact('petugas'));
    }

    // ➕ Form tambah petugas
    public function create()
    {
        return view('admin.users.create');
    }

    // 💾 Simpan petugas baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'petugas',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Akun petugas berhasil dibuat');
    }

    // ✏️ Form edit
    public function edit($id)
    {
        $petugas = User::findOrFail($id);
        return view('admin.users.edit', compact('petugas'));
    }

    // 🔄 Update data
    public function update(Request $request, $id)
    {
        $petugas = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $petugas->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data petugas berhasil diperbarui');
    }

    // 🗑️ Hapus petugas
    public function destroy($id)
    {
        $petugas = User::findOrFail($id);

        // 🔒 Jangan izinkan hapus admin
        if ($petugas->role === 'admin') {
            return back()->with('success', 'Admin tidak bisa dihapus');
        }

        $petugas->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Petugas berhasil dihapus');
    }
}
