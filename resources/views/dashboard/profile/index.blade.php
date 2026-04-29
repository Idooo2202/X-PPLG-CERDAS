@extends('layouts.dashboard')
@section('title', 'Profil Saya')

@section('extra-styles')
<style>
    .prof-header {
        background: linear-gradient(135deg, var(--ocean-deep), #003070);
        border-radius: var(--r);
        padding: 28px;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .prof-avatar-big {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--turq), var(--ocean));
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--fd);
        font-weight: 800;
        font-size: 1.8rem;
        color: #fff;
        flex-shrink: 0;
        border: 3px solid rgba(255, 255, 255, 0.4);
        overflow: hidden;
    }

    .prof-avatar-big img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .prof-info-name {
        font-family: var(--fd);
        font-weight: 800;
        font-size: 1.3rem;
    }

    .prof-info-role {
        font-size: 0.78rem;
        opacity: 0.72;
        margin-top: 2px;
        text-transform: capitalize;
    }

    .prof-stats {
        display: flex;
        gap: 16px;
        margin-top: 10px;
        flex-wrap: wrap;
    }

    .ps {
        text-align: center;
    }

    .ps-v {
        font-family: var(--fd);
        font-weight: 800;
        font-size: 1.2rem;
    }

    .ps-l {
        font-size: 0.7rem;
        opacity: 0.72;
        text-transform: uppercase;
    }

    .prof-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    @media (max-width: 800px) {
        .prof-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        margin-bottom: 12px;
    }

    .form-group label {
        display: block;
        font-weight: 700;
        font-size: 0.78rem;
        color: var(--text-main, #0a2540);
        margin-bottom: 4px;
    }

    .form-group input {
        width: 100%;
        padding: 9px 12px;
        border: 2px solid var(--input-border, #eee);
        border-radius: 10px;
        font-family: var(--fb);
        outline: none;
        transition: border 0.3s, background 0.3s, color 0.3s;
        background: var(--input-bg, #fff);
        color: var(--text-main, #0a2540);
    }

    .form-group input:focus {
        border-color: var(--ocean);
    }

    .logout-btn {
        width: 100%;
        padding: 13px;
        background: linear-gradient(135deg, #c0392b, #e74c3c);
        color: #fff;
        border: none;
        border-radius: var(--r);
        font-family: var(--fd);
        font-weight: 700;
        font-size: 0.95rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        box-shadow: 0 4px 16px rgba(192,57,43,0.3);
        transition: all 0.25s;
    }

    .logout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(192,57,43,0.45);
    }

    body.dark-mode .logout-btn {
        background: linear-gradient(135deg, rgba(239,68,68,0.8), rgba(220,38,38,0.9));
        box-shadow: 0 4px 16px rgba(239,68,68,0.25);
    }

    body.dark-mode .logout-btn:hover {
        box-shadow: 0 8px 24px rgba(239,68,68,0.4);
    }

    /* Hide logout on desktop — sidebar already has it */
    @media (min-width: 1025px) {
        .profile-logout-wrap { display: none; }
    }
</style>
@endsection

@section('content')
{{-- Header Profil --}}
<div class="prof-header">
    <div class="prof-avatar-big">
        @if($user->foto_profil)
        <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="Foto">
        @else
        {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
        @endif
    </div>
    <div style="flex:1">
        <div class="prof-info-name">{{ $user->nama_lengkap }}</div>
        <div class="prof-info-role">{{ str_replace('_',' ', $user->role) }} · {{ $user->username }}</div>
        @if($user->no_absen)
        <div style="font-size:0.76rem; opacity:0.65; margin-top:2px;">No. Absen: {{ $user->no_absen }}</div>
        @endif
        <div class="prof-stats">
            @if($user->role === 'wali_kelas')
            <div class="ps">
                <div class="ps-v">—</div>
                <div class="ps-l">Tidak ikut leaderboard</div>
            </div>
            <div class="ps">
                <div class="ps-v">—</div>
                <div class="ps-l">Tidak ikut absensi siswa</div>
            </div>
            <div class="ps">
                <div class="ps-v">—</div>
                <div class="ps-l">Tidak ikut bayar kas</div>
            </div>
            @else
            <div class="ps">
                <div class="ps-v">{{ $user->leaderboard->poin ?? 0 }} ⭐</div>
                <div class="ps-l">Total Poin</div>
            </div>
            <div class="ps">
                <div class="ps-v">{{ $user->leaderboard->streak_hadir ?? 0 }} 🔥</div>
                <div class="ps-l">Streak Hadir</div>
            </div>
            <div class="ps">
                <div class="ps-v">{{ $user->leaderboard->total_hadir ?? 0 }}</div>
                <div class="ps-l">Total Hadir</div>
            </div>
            <div class="ps">
                <div class="ps-v">{{ $user->leaderboard->total_kas_bayar ?? 0 }}</div>
                <div class="ps-l">Bayar Kas</div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="prof-grid">
    {{-- Form Edit Profil --}}
    <div class="d-card">
        <div class="d-card-title">✏️ Edit Profil</div>
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>👤 Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ $user->nama_lengkap }}" required>
            </div>
            <div class="form-group">
                <label>📧 Email</label>
                <input type="email" name="email" value="{{ $user->email }}" placeholder="email@contoh.com">
            </div>
            <div class="form-group">
                <label>📱 No. HP</label>
                <input type="text" name="no_hp" value="{{ $user->no_hp }}" placeholder="08xxxxxxxxxx">
            </div>
            <div class="form-group">
                <label>🖼️ Foto Profil</label>
                <input type="file" name="foto" accept="image/*">
            </div>
            <hr style="margin: 14px 0; border: none; border-top: 1px solid #eee;">
            <div style="font-size:0.78rem; font-weight:700; color:#aaa; margin-bottom:8px;">🔒 Ganti Password (opsional)</div>
            <div class="form-group">
                <label>Password Lama</label>
                <input type="password" name="password_lama" placeholder="Masukkan password lama">
            </div>
            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password_baru" placeholder="Min 4 karakter">
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_baru_confirmation" placeholder="Ulangi password baru">
            </div>
            <button type="submit" style="width:100%; padding:11px; background:linear-gradient(90deg,var(--ocean),var(--turq-dk)); color:#fff; border:none; border-radius:11px; font-family:var(--fd); font-weight:700; cursor:pointer; font-size:0.95rem;">
                💾 Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- History Aktivitas Singkat --}}
    <div class="d-card">
        <div class="d-card-title">📜 Aktivitas Terakhir</div>
        @php
        $logs = \App\Models\ActivityLog::where('user_id', $user->id)
        ->orderByDesc('created_at')->take(10)->get();
        @endphp
        @forelse($logs as $log)
        <div style="padding: 8px 11px; border-radius: 10px; background: #f8fbff; margin-bottom: 5px; font-size: 0.81rem;">
            <div style="font-weight: 700;">{{ $log->deskripsi }}</div>
            <div style="font-size: 0.7rem; color: #aaa; margin-top: 2px;">{{ $log->created_at->diffForHumans() }}</div>
        </div>
        @empty
        <p style="font-size:0.82rem; color:#aaa; text-align:center; padding: 20px 0;">Belum ada aktivitas</p>
        @endforelse
        <a href="{{ route('history.index') }}" style="display:block; text-align:center; margin-top:10px; font-family:var(--fd); font-weight:700; font-size:0.82rem; color:var(--ocean); text-decoration:none;">
            Lihat Semua →
        </a>
    </div>
</div>

{{-- Logout Button — only visible on mobile/tablet (desktop uses sidebar) --}}
<div class="profile-logout-wrap" style="margin-top: 22px;">
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="logout-btn">
            🚪 Logout dari Akun
        </button>
    </form>
</div>
@endsection