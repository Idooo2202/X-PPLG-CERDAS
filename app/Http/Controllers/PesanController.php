<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesan;
use App\Models\User;

class PesanController extends Controller {

    public function index() {
        $user = User::findOrFail(session('user_id'));
        // #region agent log
        @file_put_contents(
            base_path('debug-f13595.log'),
            json_encode([
                'sessionId' => 'f13595',
                'runId' => 'audit-7-issues',
                'hypothesisId' => 'H18',
                'location' => 'app/Http/Controllers/PesanController.php:index',
                'message' => 'Announcement/notification source snapshot',
                'data' => [
                    'userId' => $user->id,
                    'role' => $user->role,
                    'unreadInboxCount' => Pesan::where('ke_user_id', $user->id)->where('is_read', false)->count(),
                    'unreadBroadcastCount' => Pesan::where('is_broadcast', true)->where('dari_user_id', '!=', $user->id)->where('is_read', false)->count(),
                ],
                'timestamp' => (int) round(microtime(true) * 1000),
            ], JSON_UNESCAPED_SLASHES) . PHP_EOL,
            FILE_APPEND
        );
        // #endregion
        return view('dashboard.pesan.index', compact('user'));
    }

    public function kirim(Request $request) {
        $request->validate([
            'ke_user_id' => 'nullable|exists:users,id',
            'judul'      => 'required|max:150',
            'isi'        => 'required|max:2000',
        ]);

        $isBroadcast = empty($request->ke_user_id);

        // Hanya role tertentu yang boleh broadcast
        if ($isBroadcast && !in_array(session('user_role'), ['wali_kelas','sekretaris','bendahara'])) {
            return back()->with('error', 'Hanya pengurus yang bisa broadcast pesan!');
        }

        Pesan::create([
            'dari_user_id' => session('user_id'),
            'ke_user_id'   => $request->ke_user_id ?: null,
            'judul'        => $request->judul,
            'isi'          => $request->isi,
            'is_broadcast' => $isBroadcast,
            'is_read'      => false,
        ]);

        return back()->with('success', 'Pesan berhasil dikirim! 📨');
    }

    public function balas(Request $request) {
        $request->validate([
            'reply_to'   => 'required|exists:pesan,id',
            'isi'        => 'required|max:2000',
        ]);

        $original = Pesan::findOrFail($request->reply_to);

        Pesan::create([
            'dari_user_id' => session('user_id'),
            'ke_user_id'   => $original->dari_user_id,
            'judul'        => 'Re: ' . $original->judul,
            'isi'          => $request->isi,
            'is_broadcast' => false,
            'is_read'      => false,
            'reply_to'     => $original->id,
        ]);

        return back()->with('success', 'Balasan terkirim!');
    }

    public function markRead(Pesan $pesan) {
        // Pastikan hanya penerima yang bisa mark read
        if ($pesan->ke_user_id === session('user_id') || $pesan->is_broadcast) {
            $pesan->update(['is_read' => true]);
        }
        return back()->with('success', 'Pesan ditandai sudah dibaca.');
    }
}