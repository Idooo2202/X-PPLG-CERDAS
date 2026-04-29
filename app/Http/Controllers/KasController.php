<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kas;
use App\Models\KasPayment;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\Leaderboard;
use Carbon\Carbon;

class KasController extends Controller {

    public function index() {
        $user   = User::findOrFail(session('user_id'));
        $bulan  = request('bulan', now()->month);
        $tahun  = request('tahun', now()->year);
        $viewMode = request('view_mode', 'monthly');

        $totalPemasukan  = Kas::where('jenis', 'pemasukan')
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->sum('jumlah');
        $totalPengeluaran = Kas::where('jenis', 'pengeluaran')
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)->sum('jumlah');
        $saldo = $totalPemasukan - $totalPengeluaran;

        $transaksi = Kas::with('user')
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->orderByDesc('tanggal')->get();

        // Payment checklist — semua siswa
        $siswaList    = User::where('role', 'siswa')->where('is_active', true)->orderBy('nama_lengkap')->get();
        $hariIni      = now()->toDateString();
        $payments     = KasPayment::where('tanggal_bayar', $hariIni)->get()->keyBy('user_id');

        // Build chart data based on view mode
        $chartData = $this->buildChartData($viewMode, $bulan, $tahun);

        return view('dashboard.kas.index', compact(
            'user', 'totalPemasukan', 'totalPengeluaran', 'saldo',
            'transaksi', 'siswaList', 'payments', 'bulan', 'tahun',
            'viewMode', 'chartData'
        ));
    }

    private function buildChartData(string $viewMode, int $bulan, int $tahun): array {
        $labels = [];
        $pemasukan = [];
        $pengeluaran = [];

        if ($viewMode === 'weekly') {
            // Last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->format('d M');
                $pemasukan[] = (float) Kas::where('jenis', 'pemasukan')
                    ->whereDate('tanggal', $date->toDateString())->sum('jumlah');
                $pengeluaran[] = (float) Kas::where('jenis', 'pengeluaran')
                    ->whereDate('tanggal', $date->toDateString())->sum('jumlah');
            }
        } elseif ($viewMode === 'monthly') {
            // All months in the selected year
            for ($m = 1; $m <= 12; $m++) {
                $labels[] = Carbon::createFromDate($tahun, $m, 1)->format('M');
                $pemasukan[] = (float) Kas::where('jenis', 'pemasukan')
                    ->whereMonth('tanggal', $m)->whereYear('tanggal', $tahun)->sum('jumlah');
                $pengeluaran[] = (float) Kas::where('jenis', 'pengeluaran')
                    ->whereMonth('tanggal', $m)->whereYear('tanggal', $tahun)->sum('jumlah');
            }
        } elseif ($viewMode === 'yearly') {
            // Last 5 years
            $currentYear = now()->year;
            for ($y = $currentYear - 4; $y <= $currentYear; $y++) {
                $labels[] = (string) $y;
                $pemasukan[] = (float) Kas::where('jenis', 'pemasukan')
                    ->whereYear('tanggal', $y)->sum('jumlah');
                $pengeluaran[] = (float) Kas::where('jenis', 'pengeluaran')
                    ->whereYear('tanggal', $y)->sum('jumlah');
            }
        }

        return compact('labels', 'pemasukan', 'pengeluaran');
    }

    // Tambah transaksi kas (pemasukan/pengeluaran)
    public function store(Request $request) {
        $request->validate([
            'jenis'      => 'required|in:pemasukan,pengeluaran',
            'jumlah'     => 'required|numeric|min:100',
            'keterangan' => 'required|max:255',
            'tanggal'    => 'required|date',
        ]);

        Kas::create([
            'jenis'      => $request->jenis,
            'jumlah'     => $request->jumlah,
            'keterangan' => $request->keterangan,
            'tanggal'    => $request->tanggal,
            'user_id'    => session('user_id'),
        ]);

        ActivityLog::create([
            'user_id'     => session('user_id'),
            'aksi'        => 'input_' . $request->jenis,
            'deskripsi'   => "Input {$request->jenis} Rp{$request->jumlah}: {$request->keterangan}",
        ]);

        return back()->with('success', 'Transaksi berhasil disimpan!');
    }

    // Checklist pembayaran kas harian siswa
    public function togglePayment(Request $request, User $user) {
        // #region agent log
        @file_put_contents(
            base_path('debug-f13595.log'),
            json_encode([
                'sessionId' => 'f13595',
                'runId' => 'post-fix',
                'hypothesisId' => 'H12',
                'location' => 'app/Http/Controllers/KasController.php:togglePayment',
                'message' => 'togglePayment route binding state',
                'data' => [
                    'routeUserParam' => $request->route('user'),
                    'boundUserId' => $user->id,
                ],
                'timestamp' => (int) round(microtime(true) * 1000),
            ], JSON_UNESCAPED_SLASHES) . PHP_EOL,
            FILE_APPEND
        );
        // #endregion
        $hariIni = now()->toDateString();
        $payment = KasPayment::firstOrCreate(
            ['user_id' => $user->id, 'tanggal_bayar' => $hariIni],
            ['jumlah' => 2000, 'is_paid' => false]
        );

        $payment->update([
            'is_paid'     => !$payment->is_paid,
            'verified_by' => $payment->is_paid ? null : session('user_id'),
        ]);

        // Update poin leaderboard jika baru bayar
        if ($payment->is_paid) {
            $this->updateLeaderboardKas($user->id);
            ActivityLog::create([
                'user_id'   => $user->id,
                'aksi'      => 'bayar_kas',
                'deskripsi' => "Membayar kas Rp2000 tanggal {$hariIni}",
            ]);
        }

        return back()->with('success', "Status pembayaran {$user->nama_lengkap} diupdate!");
    }

    private function updateLeaderboardKas(int $userId): void {
        $lb = Leaderboard::firstOrCreate(['user_id' => $userId]);
        // Cek apakah kemarin bayar (streak)
        $kemarin = Carbon::yesterday()->toDateString();
        $kemarin_bayar = KasPayment::where('user_id', $userId)
            ->where('tanggal_bayar', $kemarin)->where('is_paid', true)->exists();

        $streak = $kemarin_bayar ? $lb->streak_kas + 1 : 1;
        // Bonus poin jika streak
        $bonus = $streak >= 5 ? 5 : ($streak >= 3 ? 2 : 0);
        $poin  = 3 + $bonus; // Base 3 poin per bayar

        $lb->update([
            'poin'          => $lb->poin + $poin,
            'streak_kas'    => $streak,
            'total_kas_bayar' => $lb->total_kas_bayar + 1,
            'tier'          => Leaderboard::hitungTier($lb->poin + $poin),
        ]);
    }
}