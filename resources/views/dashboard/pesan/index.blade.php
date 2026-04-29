@extends('layouts.dashboard')
@section('title', 'Pesan')

@section('extra-styles')
<style>
    .pesan-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    @media (max-width: 900px) {
        .pesan-grid {
            grid-template-columns: 1fr;
        }
    }

    .p-item {
        padding: 11px 14px;
        border-radius: 12px;
        background: var(--row-bg, #f8fbff);
        margin-bottom: 7px;
        cursor: pointer;
        transition: all 0.2s;
        border-left: 3px solid transparent;
    }

    .p-item:hover {
        background: var(--row-hover, #eaf4ff);
        transform: translateX(2px);
    }

    .p-item.unread {
        border-left-color: var(--ocean);
        background: #eaf4ff;
    }

    .p-item.broadcast {
        border-left-color: var(--coral);
    }

    .p-judul {
        font-family: var(--fd);
        font-weight: 700;
        font-size: 0.86rem;
        color: var(--text-main, #0a2540);
    }

    .p-meta {
        font-size: 0.7rem;
        color: var(--text-muted, #aaa);
        margin-top: 2px;
    }

    .p-isi {
        font-size: 0.78rem;
        color: var(--text-muted, #555);
        margin-top: 5px;
        line-height: 1.5;
    }

    .p-badge {
        display: inline-block;
        padding: 1px 8px;
        border-radius: 50px;
        font-size: 0.65rem;
        font-weight: 700;
        margin-left: 5px;
    }

    .pb-bc {
        background: #fff3cd;
        color: #856404;
    }

    .pb-unr {
        background: var(--ocean);
        color: #fff;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 20px;">
    <h1 style="font-family: var(--fd); font-size: 1.5rem; font-weight: 800; color: var(--ocean-deep);">💬 Pesan</h1>
</div>

<div class="pesan-grid">

    {{-- Kotak Masuk --}}
    <div class="d-card">
        <div class="d-card-title">📥 Kotak Masuk</div>
        @php
        $userId = session('user_id');
        $pesanMasuk = \App\Models\Pesan::where(function($q) use ($userId) {
        $q->where('ke_user_id', $userId)
        ->orWhere(function($q2) use ($userId) {
        $q2->where('is_broadcast', true)->where('dari_user_id', '!=', $userId);
        });
        })->with('pengirim')->orderByDesc('created_at')->take(30)->get();
        @endphp
        @forelse($pesanMasuk as $p)
        <div class="p-item {{ !$p->is_read ? 'unread' : '' }} {{ $p->is_broadcast ? 'broadcast' : '' }}">
            <div class="p-judul">
                {{ $p->judul }}
                @if($p->is_broadcast) <span class="p-badge pb-bc">📢 Broadcast</span> @endif
                @if(!$p->is_read) <span class="p-badge pb-unr">Baru</span> @endif
            </div>
            <div class="p-meta">
                dari {{ $p->pengirim->nama_lengkap }} · {{ $p->created_at->diffForHumans() }}
            </div>
            <div class="p-isi">{{ Str::limit($p->isi, 120) }}</div>
            @if(!$p->is_read)
            <form action="{{ route('pesan.read', $p) }}" method="POST" style="margin-top:6px;">
                @csrf
                <button type="submit" style="font-size:0.72rem; background:#e3f2fd; color:#1565c0; border:none; padding:3px 10px; border-radius:8px; font-family:var(--fd); font-weight:700; cursor:pointer;">
                    ✅ Tandai Dibaca
                </button>
            </form>
            @endif
        </div>
        @empty
        <p style="font-size:0.82rem; color:#aaa; text-align:center; padding: 20px 0;">Tidak ada pesan masuk</p>
        @endforelse
    </div>

    {{-- Kirim Pesan --}}
    <div class="d-card">
        <div class="d-card-title">✉️ Kirim Pesan</div>
        <form action="{{ route('pesan.kirim') }}" method="POST" style="display:grid; gap:10px;">
            @csrf
            @php
            $semuaUser = \App\Models\User::where('id', '!=', session('user_id'))
            ->where('is_active', true)->orderBy('nama_lengkap')->get();
            @endphp

            <div>
                <label style="font-size:0.78rem; font-weight:700; display:block; margin-bottom:4px;">📨 Kirim ke</label>
                <select name="ke_user_id" id="pTujuan" style="width:100%; padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none;">
                    @if(in_array(session('user_role'), ['wali_kelas','sekretaris','bendahara']))
                    <option value="">📢 Broadcast ke Semua</option>
                    @endif
                    @foreach($semuaUser as $u)
                    <option value="{{ $u->id }}">{{ $u->nama_lengkap }} ({{ str_replace('_',' ', $u->role) }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="font-size:0.78rem; font-weight:700; display:block; margin-bottom:4px;">📌 Judul</label>
                <input type="text" name="judul" placeholder="Judul pesan..." required
                    style="width:100%; padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none;">
            </div>

            <div>
                <label style="font-size:0.78rem; font-weight:700; display:block; margin-bottom:4px;">💬 Isi Pesan</label>
                <textarea name="isi" rows="4" placeholder="Tulis pesanmu di sini..." required
                    style="width:100%; padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none; resize:vertical;"></textarea>
            </div>

            <button type="submit" style="padding:11px; background:linear-gradient(90deg,var(--ocean),var(--turq-dk)); color:#fff; border:none; border-radius:11px; font-family:var(--fd); font-weight:700; cursor:pointer; font-size:0.95rem;">
                🚀 Kirim Pesan
            </button>
        </form>

        {{-- Pesan Terkirim --}}
        <div style="margin-top: 20px;">
            <div class="d-card-title" style="font-size:0.88rem; margin-bottom:10px;">📤 Terkirim</div>
            @php
            $terkirim = \App\Models\Pesan::where('dari_user_id', session('user_id'))
            ->with('penerima')->orderByDesc('created_at')->take(10)->get();
            @endphp
            @forelse($terkirim as $p)
            <div class="p-item">
                <div class="p-judul">{{ $p->judul }} @if($p->is_broadcast) <span class="p-badge pb-bc">📢</span> @endif</div>
                <div class="p-meta">ke {{ $p->is_broadcast ? 'Semua' : ($p->penerima->nama_lengkap ?? '—') }} · {{ $p->created_at->diffForHumans() }}</div>
            </div>
            @empty
            <p style="font-size:0.8rem; color:#aaa; text-align:center;">Belum ada pesan terkirim</p>
            @endforelse
        </div>
    </div>

</div>
@endsection