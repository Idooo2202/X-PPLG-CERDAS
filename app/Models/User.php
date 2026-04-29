<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;

    protected $fillable = [
        'username', 'password', 'nama_lengkap', 'role',
        'no_absen', 'foto_profil', 'email', 'no_hp', 'is_active'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['password' => 'hashed', 'is_active' => 'boolean'];

    // Relasi
    public function kehadiran() { return $this->hasMany(Kehadiran::class); }
    public function kasPayments() { return $this->hasMany(KasPayment::class); }
    public function leaderboard() { return $this->hasOne(Leaderboard::class); }
    public function pelanggaran() { return $this->hasMany(Pelanggaran::class); }
    public function activityLog() { return $this->hasMany(ActivityLog::class); }

    // Helper cek role
    public function isWaliKelas(): bool { return $this->role === 'wali_kelas'; }
    public function isBendahara(): bool { return $this->role === 'bendahara'; }
    public function isSekretaris(): bool { return $this->role === 'sekretaris'; }
    public function isSiswa(): bool { return $this->role === 'siswa'; }

    // Bisa manage kas?
    public function canManageKas(): bool {
        return in_array($this->role, ['wali_kelas', 'bendahara']);
    }

    // Bisa manage kehadiran?
    public function canManageKehadiran(): bool {
        return in_array($this->role, ['wali_kelas', 'sekretaris']);
    }
}