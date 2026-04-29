<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Leaderboard;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run(): void {
        // Buat Wali Kelas
        $wali = User::create([
            'username'     => 'patahyasin',
            'password'     => Hash::make('wali123'),
            'nama_lengkap' => 'Bp. Patah Yasin',
            'role'         => 'wali_kelas',
            'is_active'    => true,
        ]);
        Leaderboard::create(['user_id' => $wali->id]);

        // Buat Bendahara
        $bendahara = User::create([
            'username'     => 'nabila',
            'password'     => Hash::make('bendahara123'),
            'nama_lengkap' => 'Nabila',
            'role'         => 'bendahara',
            'no_absen'     => '20',
            'is_active'    => true,
        ]);
        Leaderboard::create(['user_id' => $bendahara->id]);

        // Buat Sekretaris
        $sek = User::create([
            'username'     => 'regita',
            'password'     => Hash::make('sek123'),
            'nama_lengkap' => 'Regita',
            'role'         => 'sekretaris',
            'no_absen'     => '22',
            'is_active'    => true,
        ]);
        Leaderboard::create(['user_id' => $sek->id]);

        // Buat beberapa siswa contoh
        $siswaList = [
            ['username' => 'zein',   'nama_lengkap' => 'Zein',   'no_absen' => '34'],
            ['username' => 'alfino', 'nama_lengkap' => 'Alfino', 'no_absen' => '03'],
            ['username' => 'rido',   'nama_lengkap' => 'Rido',   'no_absen' => '26'],
        ];

        foreach ($siswaList as $s) {
            $siswa = User::create([
                'username'     => $s['username'],
                'password'     => Hash::make('siswa123'),
                'nama_lengkap' => $s['nama_lengkap'],
                'role'         => 'siswa',
                'no_absen'     => $s['no_absen'],
                'is_active'    => true,
            ]);
            Leaderboard::create(['user_id' => $siswa->id]);
        }
    }
}