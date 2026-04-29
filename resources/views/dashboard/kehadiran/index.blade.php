@extends('layouts.dashboard')
@section('title', 'Kehadiran')

@section('extra-styles')
<style>
    .kh-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    @media (max-width: 900px) {
        .kh-grid {
            grid-template-columns: 1fr;
        }
    }

    .kh-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 9px 13px;
        background: var(--row-bg, #f8fbff);
        border-radius: 11px;
        margin-bottom: 6px;
        transition: background 0.3s;
    }

    .kh-name {
        font-weight: 700;
        font-size: 0.83rem;
        color: var(--text-main, #0a2540);
    }

    .kh-stats {
        display: flex;
        gap: 6px;
        font-size: 0.7rem;
        margin-top: 2px;
    }

    .kh-st {
        padding: 2px 8px;
        border-radius: 50px;
        font-weight: 700;
    }

    .st-hadir {
        background: #d4edda;
        color: #1a7a4a;
    }

    .st-izin {
        background: #fff3cd;
        color: #856404;
    }

    .st-sakit {
        background: #d1ecf1;
        color: #0c5460;
    }

    .st-alpha {
        background: #f8d7da;
        color: #721c24;
    }

    .kh-sel-wrap {
        display: flex;
        gap: 6px;
    }

    .kh-sel-wrap select {
        padding: 5px 8px;
        border: 2px solid #eee;
        border-radius: 8px;
        font-family: var(--fb);
        font-size: 0.8rem;
        cursor: pointer;
    }

    .stat-mini-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-bottom: 18px;
    }

    .stat-mini {
        background: var(--card-bg, #fff);
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        box-shadow: var(--sh);
        border: 1px solid var(--card-border, rgba(0,119,190,0.08));
        transition: background 0.4s ease;
    }

    .stat-mini-val {
        font-family: var(--fd);
        font-size: 1.2rem;
        font-weight: 800;
    }

    .stat-mini-lbl {
        font-size: 0.68rem;
        color: #aaa;
        text-transform: uppercase;
        font-weight: 700;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 20px;">
    <h1 style="font-family: var(--fd); font-size: 1.5rem; font-weight: 800; color: var(--ocean-deep);">📋 Kehadiran</h1>
</div>

{{-- Filter tanggal --}}
<form method="GET" action="{{ route('kehadiran.index') }}" style="display:flex; gap:10px; margin-bottom:18px; flex-wrap:wrap;">
    <input type="date" name="tanggal" value="{{ $tanggal }}"
        style="padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none;">
    <button type="submit" style="padding:9px 18px; background:var(--ocean); color:#fff; border:none; border-radius:10px; font-family:var(--fd); font-weight:700; cursor:pointer;">🔍 Lihat</button>
</form>

{{-- Statistik bulan ini (untuk user yang login) --}}
@if(session('user_role') === 'siswa')
@php $myStat = $statistik[session('user_id')] ?? []; @endphp
<div class="stat-mini-row">
    <div class="stat-mini">
        <div class="stat-mini-val" style="color:#1a7a4a">{{ $myStat['hadir'] ?? 0 }}</div>
        <div class="stat-mini-lbl">✅ Hadir</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-val" style="color:#856404">{{ $myStat['izin'] ?? 0 }}</div>
        <div class="stat-mini-lbl">📝 Izin</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-val" style="color:#0c5460">{{ $myStat['sakit'] ?? 0 }}</div>
        <div class="stat-mini-lbl">🤒 Sakit</div>
    </div>
    <div class="stat-mini">
        <div class="stat-mini-val" style="color:#721c24">{{ $myStat['alpha'] ?? 0 }}</div>
        <div class="stat-mini-lbl">❌ Alpha</div>
    </div>
</div>
@endif

<div class="kh-grid">
    {{-- Daftar Kehadiran Hari Ini --}}
    <div class="d-card">
        <div class="d-card-title">📋 Kehadiran — {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</div>

        {{-- Form Absensi Massal (wali & sekretaris) --}}
        @if(in_array(session('user_role'), ['wali_kelas','sekretaris']))
        <form action="{{ route('kehadiran.massal') }}" method="POST">
            @csrf
            <input type="hidden" name="tanggal" value="{{ $tanggal }}">
            @foreach($siswaList as $siswa)
            @php $k = $kehadiranHariIni[$siswa->id] ?? null; @endphp
            <div class="kh-row">
                <div>
                    <div class="kh-name">{{ $siswa->no_absen ? "[{$siswa->no_absen}]" : '' }} {{ $siswa->nama_lengkap }}</div>
                </div>
                <div class="kh-sel-wrap">
                    <select name="kehadiran[{{ $siswa->id }}]">
                        @foreach(['hadir','izin','sakit','alpha'] as $s)
                        <option value="{{ $s }}" {{ ($k && $k->status === $s) ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endforeach
            <button type="submit" style="width:100%; margin-top:12px; padding:11px; background:linear-gradient(90deg,var(--ocean),var(--turq-dk)); color:#fff; border:none; border-radius:11px; font-family:var(--fd); font-weight:700; cursor:pointer; font-size:0.95rem;">
                💾 Simpan Semua Kehadiran
            </button>
        </form>

        @else
        {{-- Read-only untuk siswa --}}
        @foreach($siswaList as $siswa)
        @php $k = $kehadiranHariIni[$siswa->id] ?? null; @endphp
        <div class="kh-row">
            <div class="kh-name">{{ $siswa->no_absen ? "[{$siswa->no_absen}]" : '' }} {{ $siswa->nama_lengkap }}</div>
            @if($k)
            <span class="kh-st st-{{ $k->status }}">{{ ucfirst($k->status) }}</span>
            @else
            <span class="kh-st" style="background:#eee; color:#aaa;">Belum dicatat</span>
            @endif
        </div>
        @endforeach
        @endif
    </div>

    {{-- Rekap Bulan Ini --}}
    <div class="d-card">
        <div class="d-card-title">📊 Rekap — {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</div>
        @foreach($siswaList as $siswa)
        @php $s = $statistik[$siswa->id] ?? []; @endphp
        <div class="kh-row">
            <div>
                <div class="kh-name">{{ $siswa->nama_lengkap }}</div>
                <div class="kh-stats">
                    <span class="kh-st st-hadir">H: {{ $s['hadir'] ?? 0 }}</span>
                    <span class="kh-st st-izin">I: {{ $s['izin'] ?? 0 }}</span>
                    <span class="kh-st st-sakit">S: {{ $s['sakit'] ?? 0 }}</span>
                    <span class="kh-st st-alpha">A: {{ $s['alpha'] ?? 0 }}</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

@if(in_array(session('user_role'), ['wali_kelas','sekretaris']))
@php
    $pelanggaranTerbaru = \App\Models\Pelanggaran::with(['user', 'pelapor'])
    ->orderByDesc('created_at')
    ->take(8)
    ->get();
@endphp
<div class="d-card" style="margin-top:18px;">
    <div class="d-card-title">🚨 Laporan Pelanggaran Siswa</div>
    <form action="{{ route('pelanggaran.store') }}" method="POST" style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
        @csrf
        <select name="user_id" required style="padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none;">
            <option value="">Pilih siswa</option>
            @foreach($siswaList as $siswa)
            <option value="{{ $siswa->id }}">{{ $siswa->nama_lengkap }}</option>
            @endforeach
        </select>
        <input type="date" name="tanggal" value="{{ now()->toDateString() }}" required style="padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none;">
        <input type="text" name="jenis_pelanggaran" placeholder="Jenis pelanggaran" required style="grid-column:1 / -1; padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none;">
        <textarea name="deskripsi" rows="3" placeholder="Deskripsi kejadian..." required style="grid-column:1 / -1; padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none; resize:vertical;"></textarea>
        <button type="submit" style="grid-column:1 / -1; padding:11px; background:linear-gradient(90deg,var(--coral),#c0392b); color:#fff; border:none; border-radius:11px; font-family:var(--fd); font-weight:700; cursor:pointer; font-size:0.95rem;">
            📝 Simpan Laporan Pelanggaran
        </button>
    </form>
    <div style="margin-top:16px;">
        <div class="d-card-title" style="font-size:0.9rem; margin-bottom:8px;">📌 Riwayat Pelanggaran Terbaru</div>
        @forelse($pelanggaranTerbaru as $p)
        <div class="kh-row" style="align-items:flex-start;">
            <div>
                <div class="kh-name">{{ $p->user->nama_lengkap }} — {{ $p->jenis_pelanggaran }}</div>
                <div style="font-size:0.75rem; color:#666; margin-top:2px;">{{ $p->deskripsi }}</div>
                <div style="font-size:0.7rem; color:#aaa; margin-top:3px;">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }} · pelapor: {{ $p->pelapor->nama_lengkap ?? '-' }}</div>
            </div>
            <span class="kh-st" style="background:#fff3cd; color:#856404;">{{ ucfirst($p->status) }}</span>
        </div>
        @empty
        <p style="font-size:0.82rem; color:#aaa; text-align:center; padding:12px 0;">Belum ada laporan pelanggaran</p>
        @endforelse
    </div>
</div>
@endif
@endsection