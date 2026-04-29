<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PesanController;

// ═══════════════════════════════════
//  PUBLIC — Home & Auth
// ═══════════════════════════════════
Route::get('/',      [AuthController::class, 'showLogin'])->name('home');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ═══════════════════════════════════
//  PROTECTED — Semua Role
// ═══════════════════════════════════
Route::middleware(['auth.custom'])->group(function () {

    // Beranda dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kas — semua bisa lihat
    Route::get('/kas', [KasController::class, 'index'])->name('kas.index');

    // Kas — hanya wali & bendahara bisa input
    Route::middleware(['role:wali_kelas,bendahara'])->group(function () {
        Route::post('/kas',                      [KasController::class, 'store'])->name('kas.store');
        Route::post('/kas/payment/{user}/toggle', [KasController::class, 'togglePayment'])->name('kas.payment.toggle');
    });

    // Kehadiran — semua bisa lihat
    Route::get('/kehadiran', [KehadiranController::class, 'index'])->name('kehadiran.index');

    // Kehadiran — hanya wali & sekretaris bisa input
    Route::middleware(['role:wali_kelas,sekretaris'])->group(function () {
        Route::post('/kehadiran/simpan',        [KehadiranController::class, 'simpan'])->name('kehadiran.simpan');
        Route::post('/kehadiran/massal',        [KehadiranController::class, 'simpanMassal'])->name('kehadiran.massal');
    });

    // Leaderboard — semua bisa lihat
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');

    // Profile — semua bisa akses & edit
    Route::get('/profile',          [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update',  [ProfileController::class, 'update'])->name('profile.update');

    // Pesan
    Route::get('/pesan', [PesanController::class, 'index'])->name('pesan.index');
    // Pesan (lanjutan)
    Route::post('/pesan/kirim',  [PesanController::class, 'kirim'])->name('pesan.kirim');
    Route::post('/pesan/balas',  [PesanController::class, 'balas'])->name('pesan.balas');
    Route::post('/pesan/{pesan}/read', [PesanController::class, 'markRead'])->name('pesan.read');

    // History aktivitas
    Route::get('/history', function () {
        $user = \App\Models\User::findOrFail(session('user_id'));
        $log  = \App\Models\ActivityLog::where('user_id', $user->id)
            ->orderByDesc('created_at')->paginate(30);
        return view('dashboard.history.index', compact('user', 'log'));
    })->name('history.index');

    // ─── KHUSUS WALI KELAS ─────────────────────────────────────
    Route::middleware(['role:wali_kelas'])->group(function () {
        // Manajemen user
        Route::get('/users',           [UserController::class, 'index'])->name('users.index');
        Route::post('/users',          [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}',    [UserController::class, 'update'])->name('users.update');
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // ─── KHUSUS SEKRETARIS ─────────────────────────────────────
    Route::middleware(['role:sekretaris,wali_kelas'])->group(function () {
        Route::post('/pelanggaran',    function (\Illuminate\Http\Request $req) {
            $req->validate([
                'user_id'          => 'required|exists:users,id',
                'jenis_pelanggaran' => 'required|max:100',
                'deskripsi'        => 'required|max:500',
                'tanggal'          => 'required|date',
            ]);
            \App\Models\Pelanggaran::create([
                'user_id'           => $req->user_id,
                'dilaporkan_oleh'   => session('user_id'),
                'jenis_pelanggaran' => $req->jenis_pelanggaran,
                'deskripsi'         => $req->deskripsi,
                'tanggal'           => $req->tanggal,
                'status'            => 'pending',
            ]);
            return back()->with('success', 'Pelanggaran berhasil dilaporkan!');
        })->name('pelanggaran.store');
    });
});