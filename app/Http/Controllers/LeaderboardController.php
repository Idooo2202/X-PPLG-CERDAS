<?php
namespace App\Http\Controllers;

use App\Models\Leaderboard;
use App\Models\User;

class LeaderboardController extends Controller {
    public function index() {
        $user = User::findOrFail(session('user_id'));
        // #region agent log
        @file_put_contents(
            base_path('debug-f13595.log'),
            json_encode([
                'sessionId' => 'f13595',
                'runId' => 'audit-7-issues',
                'hypothesisId' => 'H19',
                'location' => 'app/Http/Controllers/LeaderboardController.php:index',
                'message' => 'Leaderboard role inclusion snapshot',
                'data' => [
                    'currentUserRole' => $user->role,
                    'activeWaliCount' => User::where('role', 'wali_kelas')->where('is_active', true)->count(),
                    'activeSiswaCount' => User::where('role', 'siswa')->where('is_active', true)->count(),
                ],
                'timestamp' => (int) round(microtime(true) * 1000),
            ], JSON_UNESCAPED_SLASHES) . PHP_EOL,
            FILE_APPEND
        );
        // #endregion

        $rankings = Leaderboard::with('user')
            ->whereHas('user', fn($q) => $q->where('is_active', true)->where('role', '!=', 'wali_kelas'))
            ->orderByDesc('poin')
            ->get()
            ->map(function ($lb, $idx) {
                $lb->rank = $idx + 1;
                return $lb;
            });

        // Posisi user sendiri
        $myRank = $rankings->firstWhere('user_id', $user->id);

        return view('dashboard.leaderboard.index', compact('user', 'rankings', 'myRank'));
    }
}