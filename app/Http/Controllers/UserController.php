<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Leaderboard;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

    // Daftar semua user
    public function index() {
        $users = User::orderBy('role')->orderBy('nama_lengkap')->get();
        return view('dashboard.users.index', compact('users'));
    }

    // Tambah user baru
    public function store(Request $request) {
        $request->validate([
            'username'     => 'required|unique:users|max:50|alpha_dash',
            'password'     => 'required|min:4|max:30',
            'nama_lengkap' => 'required|max:100',
            'role'         => 'required|in:wali_kelas,bendahara,sekretaris,siswa',
            'no_absen'     => 'nullable|max:5',
            'email'        => 'nullable|email|max:100',
        ]);

        $user = User::create([
            'username'     => $request->username,
            'password'     => Hash::make($request->password),
            'nama_lengkap' => $request->nama_lengkap,
            'role'         => $request->role,
            'no_absen'     => $request->no_absen,
            'email'        => $request->email,
            'is_active'    => true,
        ]);

        // Buat entry leaderboard otomatis hanya untuk non wali_kelas
        if ($user->role !== 'wali_kelas') {
            Leaderboard::create(['user_id' => $user->id]);
        }

        return back()->with('success', "User {$user->username} berhasil dibuat!");
    }

    // Edit user
    public function update(Request $request, User $user) {
        $request->validate([
            'nama_lengkap' => 'required|max:100',
            'role'         => 'required|in:wali_kelas,bendahara,sekretaris,siswa',
            'password'     => 'nullable|min:4|max:30',
            'no_absen'     => 'nullable|max:5',
            'email'        => 'nullable|email|max:100',
        ]);

        $data = $request->only(['nama_lengkap', 'role', 'no_absen', 'email', 'no_hp']);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return back()->with('success', "User {$user->username} berhasil diupdate!");
    }

    // Nonaktifkan / Aktifkan user
    public function toggleActive(User $user) {
        // Jangan nonaktifkan diri sendiri
        if ($user->id === session('user_id')) {
            return back()->with('error', 'Tidak bisa menonaktifkan akun sendiri!');
        }
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "User {$user->username} berhasil {$status}!");
    }

    // Hapus user
    public function destroy(User $user) {
        if ($user->id === session('user_id')) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }
        $nama = $user->username;
        $user->delete();
        return back()->with('success', "User {$nama} berhasil dihapus!");
    }
}