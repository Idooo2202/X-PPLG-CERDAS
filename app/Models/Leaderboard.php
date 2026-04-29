<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model {
    protected $fillable = [
        'user_id', 'poin', 'streak_hadir', 'streak_kas',
        'total_hadir', 'total_kas_bayar', 'tier'
    ];
    public function user() { return $this->belongsTo(User::class); }

    // Hitung tier otomatis berdasarkan poin
    public static function hitungTier(int $poin): string {
        if ($poin >= 500) return 'sultan';
        if ($poin >= 200) return 'kaya';
        if ($poin >= 50)  return 'normal';
        return 'kelas_bawah';
    }
}