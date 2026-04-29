<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller {

    // Tampilkan form login (modal di homepage)
    public function showLogin() {
        // #region agent log
        @file_put_contents(
            base_path('debug-f13595.log'),
            json_encode([
                'sessionId' => 'f13595',
                'runId' => 'audit-7-issues',
                'hypothesisId' => 'H15',
                'location' => 'app/Http/Controllers/AuthController.php:showLogin',
                'message' => 'Session persistence config snapshot',
                'data' => [
                    'path' => request()->path(),
                    'sessionDriver' => config('session.driver'),
                    'sessionLifetime' => config('session.lifetime'),
                    'expireOnClose' => config('session.expire_on_close'),
                    'hasSessionUserId' => (bool) session('user_id'),
                ],
                'timestamp' => (int) round(microtime(true) * 1000),
            ], JSON_UNESCAPED_SLASHES) . PHP_EOL,
            FILE_APPEND
        );
        \Illuminate\Support\Facades\Log::info('debug-f13595 H15 session snapshot', [
            'sessionId' => 'f13595',
            'runId' => 'audit-7-issues',
            'hypothesisId' => 'H15',
            'location' => 'app/Http/Controllers/AuthController.php:showLogin',
            'path' => request()->path(),
            'sessionDriver' => config('session.driver'),
            'sessionLifetime' => config('session.lifetime'),
            'expireOnClose' => config('session.expire_on_close'),
            'hasSessionUserId' => (bool) session('user_id'),
        ]);
        // #endregion
        if (session('user_id')) {
            return redirect()->route('dashboard');
        }
        return view('home'); // homepage dengan modal login
    }

    // Proses login
    public function login(Request $request) {
        $request->validate([
            'username' => 'required|string|max:50',
            'password' => 'required|string|min:4',
        ]);

        // Rate limiting — max 5 percobaan per 1 menit
        $key = 'login.' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('error', "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.");
        }

        $user = User::where('username', $request->username)
                    ->where('is_active', true)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            RateLimiter::hit($key, 60);
            return back()->with('error', 'Username atau password salah!')->withInput(['username' => $request->username]);
        }

        RateLimiter::clear($key);

        // Simpan ke session
        session([
            'user_id'   => $user->id,
            'user_role' => $user->role,
            'user_name' => $user->nama_lengkap,
        ]);

        return redirect()->route('dashboard');
    }

    // Logout
    public function logout() {
        session()->flush();
        return redirect()->route('home')->with('success', 'Berhasil logout!');
    }
}