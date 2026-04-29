<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kehadiran;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Leaderboard;
use Carbon\Carbon;

class KehadiranController extends Controller {

    public function index() {
        $user      = User::findOrFail(session('user_id'));
        $tanggal   = request('tanggal', now()->toDateString());
        $bulan     = request('bulan', now()->month);
        $tahun     = request('tahun', now()->year);

        $siswaList = User::where('role', 'siswa')->where('is_active', true)
            ->orderBy('nama_lengkap')->get();

        // Kehadiran hari yang dipilih
        $kehadiranHariIni = Kehadiran::where('tanggal', $tanggal)
            ->with('user')->get()->keyBy('user_id');

        // Statistik bulan ini
        $statistik = [];
        foreach ($siswaList as $siswa) {
            $statistik[$siswa->id] = [
                'hadir' => Kehadiran::where('user_id', $siswa->id)
                    ->where('status', 'hadir')
                    ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->count(),
                'alpha' => Kehadiran::where('user_id', $siswa->id)
                    ->where('status', 'alpha')
                    ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->count(),
                'sakit' => Kehadiran::where('user_id', $siswa->id)
                    ->where('status', 'sakit')
                    ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->count(),
                'izin'  => Kehadiran::where('user_id', $siswa->id)
                    ->where('status', 'izin')
                    ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->count(),
            ];
        }

        return view('dashboard.kehadiran.index', compact(
            'user', 'siswaList', 'kehadiranHariIni', 'tanggal', 'statistik', 'bulan', 'tahun'
        ));
    }

    // Simpan / update kehadiran satu siswa
    public function simpan(Request $request) {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'tanggal'    => 'required|date',
            'status'     => 'required|in:hadir,izin,sakit,alpha',
            'keterangan' => 'nullable|max:255',
        ]);

        $kehadiran = Kehadiran::updateOrCreate(
            ['user_id' => $request->user_id, 'tanggal' => $request->tanggal],
            [
                'status'       => $request->status,
                'keterangan'   => $request->keterangan,
                'dicatat_oleh' => session('user_id'),
            ]
        );

        // Update leaderboard jika hadir
        if ($request->status === 'hadir') {
            $this->updateLeaderboardHadir($request->user_id);
        }

        ActivityLog::create([
            'user_id'   => $request->user_id,
            'aksi'      => 'kehadiran_' . $request->status,
            'deskripsi' => "Kehadiran dicatat: {$request->status} pada {$request->tanggal}",
        ]);

        return back()->with('success', 'Kehadiran berhasil disimpan!');
    }

    // Absensi massal (simpan semua sekaligus)
    public function simpanMassal(Request $request) {
        $request->validate([
            'tanggal'     => 'required|date',
            'kehadiran'   => 'required|array',
            'kehadiran.*' => 'in:hadir,izin,sakit,alpha',
        ]);

        foreach ($request->kehadiran as $userId => $status) {
            Kehadiran::updateOrCreate(
                ['user_id' => $userId, 'tanggal' => $request->tanggal],
                ['status' => $status, 'dicatat_oleh' => session('user_id')]
            );
            if ($status === 'hadir') $this->updateLeaderboardHadir($userId);
        }

        return back()->with('success', 'Semua kehadiran berhasil disimpan!');
    }

    private function updateLeaderboardHadir(int $userId): void {
        $lb = Leaderboard::firstOrCreate(['user_id' => $userId]);
        $kemarin = Carbon::yesterday()->toDateString();
        $kemarin_hadir = Kehadiran::where('user_id', $userId)
            ->where('tanggal', $kemarin)->where('status', 'hadir')->exists();

        $streak = $kemarin_hadir ? $lb->streak_hadir + 1 : 1;
        $bonus  = $streak >= 10 ? 10 : ($streak >= 5 ? 5 : ($streak >= 3 ? 2 : 0));
        $poin   = 5 + $bonus; // Base 5 poin per hadir

        $lb->update([
            'poin'         => $lb->poin + $poin,
            'streak_hadir' => $streak,
            'total_hadir'  => $lb->total_hadir + 1,
            'tier'         => Leaderboard::hitungTier($lb->poin + $poin),
        ]);
    }
}