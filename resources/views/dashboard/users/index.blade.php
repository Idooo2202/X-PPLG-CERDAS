@extends('layouts.dashboard')
@section('title', 'Kelola User')

@section('extra-styles')
<style>
    .user-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    @media (max-width: 900px) {
        .user-grid {
            grid-template-columns: 1fr;
        }
    }

    .u-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 11px;
        background: var(--row-bg, #f8fbff);
        margin-bottom: 6px;
        transition: background 0.3s;
    }

    .u-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--ocean), var(--turq));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-family: var(--fd);
        font-weight: 800;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .u-info {
        flex: 1;
    }

    .u-name {
        font-weight: 700;
        font-size: 0.84rem;
        color: var(--text-main, #0a2540);
    }

    .u-sub {
        font-size: 0.7rem;
        color: var(--text-muted, #aaa);
    }

    .u-actions {
        display: flex;
        gap: 5px;
    }

    .u-btn {
        padding: 4px 10px;
        border-radius: 8px;
        border: none;
        font-family: var(--fd);
        font-weight: 700;
        font-size: 0.72rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .u-btn:hover {
        transform: scale(1.05);
    }

    .role-badge {
        display: inline-block;
        padding: 2px 9px;
        border-radius: 50px;
        font-size: 0.68rem;
        font-weight: 700;
    }

    .rb-wali_kelas {
        background: #e3f2fd;
        color: #1565c0;
    }

    .rb-bendahara {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .rb-sekretaris {
        background: #fce4ec;
        color: #c62828;
    }

    .rb-siswa {
        background: #f3e5f5;
        color: #6a1b9a;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 20px;">
    <h1 style="font-family: var(--fd); font-size: 1.5rem; font-weight: 800; color: var(--ocean-deep);">👥 Kelola User</h1>
</div>

<div class="user-grid">
    {{-- Daftar User --}}
    <div class="d-card">
        <div class="d-card-title">📋 Semua Pengguna ({{ $users->count() }})</div>
        @foreach($users as $u)
        <div class="u-row" {!! !$u->is_active ? 'style="opacity:0.45;"' : '' !!}>
            <div class="u-avatar">{{ strtoupper(substr($u->nama_lengkap, 0, 1)) }}</div>
            <div class="u-info">
                <div class="u-name">
                    {{ $u->nama_lengkap }}
                    <span class="role-badge rb-{{ $u->role }}">{{ str_replace('_',' ', $u->role) }}</span>
                </div>
                <div class="u-sub">{{ $u->username }} {{ $u->no_absen ? '· No.' . $u->no_absen : '' }}</div>
            </div>
            <div class="u-actions">
                {{-- Toggle aktif --}}
                @if($u->id !== session('user_id'))
                <form action="{{ route('users.toggle', $u) }}" method="POST">
                    @csrf @method('PATCH')
                    <button class="u-btn" {!! $u->is_active ? 'style="background:#fff3cd; color:#856404;"' : 'style="background:#d4edda; color:#1a7a4a;"' !!}
                        title="{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                        {{ $u->is_active ? '⏸' : '▶️' }}
                    </button>
                </form>
                {{-- Hapus --}}
                <form action="{{ route('users.destroy', $u) }}" method="POST"
                    onsubmit="return confirm('Yakin hapus user {{ $u->username }}?')">
                    @csrf @method('DELETE')
                    <button class="u-btn" style="background:#f8d7da; color:#721c24;">🗑</button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    {{-- Form Tambah User --}}
    <div class="d-card">
        <div class="d-card-title">➕ Tambah User Baru</div>
        <form action="{{ route('users.store') }}" method="POST" style="display:grid; gap:10px;">
            @csrf
            <input type="text" name="username" placeholder="Username (a-z, 0-9, _)" required
                style="padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none;">
            <input type="password" name="password" placeholder="Password (min 4 karakter)" required
                style="padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none;">
            <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required
                style="padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none;">
            <select name="role" style="padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none;">
                <option value="siswa">🎓 Siswa</option>
                <option value="bendahara">💰 Bendahara</option>
                <option value="sekretaris">📝 Sekretaris</option>
                <option value="wali_kelas">👨‍🏫 Wali Kelas</option>
            </select>
            <input type="text" name="no_absen" placeholder="No. Absen (opsional)"
                style="padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none;">
            <input type="email" name="email" placeholder="Email (opsional)"
                style="padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none;">
            <button type="submit" style="padding:11px; background:linear-gradient(90deg,var(--ocean),var(--turq-dk)); color:#fff; border:none; border-radius:11px; font-family:var(--fd); font-weight:700; cursor:pointer;">
                👤 Buat User Baru
            </button>
        </form>
    </div>
</div>
@endsection