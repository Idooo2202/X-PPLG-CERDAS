<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kehadiran;
use App\Models\KasPayment;
use App\Models\Pesan;
use App\Models\Pelanggaran;

class DashboardController extends Controller {

    public function index() {
        $userId = session('user_id');
        $user   = User::with('leaderboard')->findOrFail($userId);

        // Jadwal hari ini & besok dihandle di frontend (JS) karena sudah ada di homescreen

        // Pesan masuk yang belum dibaca
        $pesanBelumDibaca = Pesan::where('ke_user_id', $userId)
            ->where('is_read', false)
            ->orWhere(function($q) use ($userId) {
                $q->where('is_broadcast', true)->where('dari_user_id', '!=', $userId);
            })
            ->count();

        // Kehadiran bulan ini
        $kehadiranBulanIni = Kehadiran::where('user_id', $userId)
            ->whereMonth('tanggal', now()->month)
            ->get();

        $siswaAktif = User::where('role', 'siswa')->where('is_active', true)->count();
        $pelanggaranPending = Pelanggaran::where('status', 'pending')->count();

        return view('dashboard.index', compact('user', 'pesanBelumDibaca', 'kehadiranBulanIni', 'siswaAktif', 'pelanggaranPending'));
    }
}