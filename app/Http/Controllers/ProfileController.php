<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller {

    public function index() {
        $user = User::with('leaderboard', 'kehadiran', 'kasPayments')->findOrFail(session('user_id'));
        // #region agent log
        @file_put_contents(
            base_path('debug-f13595.log'),
            json_encode([
                'sessionId' => 'f13595',
                'runId' => 'audit-7-issues',
                'hypothesisId' => 'H17',
                'location' => 'app/Http/Controllers/ProfileController.php:index',
                'message' => 'Profile payload snapshot for username rendering',
                'data' => [
                    'userId' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role,
                ],
                'timestamp' => (int) round(microtime(true) * 1000),
            ], JSON_UNESCAPED_SLASHES) . PHP_EOL,
            FILE_APPEND
        );
        // #endregion
        return view('dashboard.profile.index', compact('user'));
    }

    public function update(Request $request) {
        $user = User::findOrFail(session('user_id'));

        $request->validate([
            'nama_lengkap' => 'required|max:100',
            'email'        => 'nullable|email|max:100',
            'no_hp'        => 'nullable|max:20',
            'password_baru' => 'nullable|min:4|confirmed',
        ]);

        $data = $request->only(['nama_lengkap', 'email', 'no_hp']);
        if ($request->password_baru) {
            if (!Hash::check($request->password_lama, $user->password)) {
                return back()->with('error', 'Password lama salah!');
            }
            $data['password'] = Hash::make($request->password_baru);
        }

        // Upload foto profil
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('profil', 'public');
            $data['foto_profil'] = $path;
        }

        $user->update($data);
        session(['user_name' => $user->nama_lengkap]);

        return back()->with('success', 'Profil berhasil diupdate!');
    }
}