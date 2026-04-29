@extends('layouts.dashboard')
@section('title', 'History Aktivitas')

@section('extra-styles')
<style>
    .history-item {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 9px 12px;
        border-radius: 10px;
        background: var(--row-bg, #f8fbff);
        margin-bottom: 6px;
        transition: background 0.3s;
    }

    .history-desc {
        font-weight: 700;
        font-size: 0.83rem;
        color: var(--text-main, #0a2540);
    }

    .history-aksi {
        font-size: 0.7rem;
        color: var(--text-muted, #aaa);
        margin-top: 2px;
    }

    .history-time {
        font-size: 0.72rem;
        color: var(--text-muted, #ccc);
        flex-shrink: 0;
        margin-left: 10px;
        text-align: right;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 20px;">
    <h1 style="font-family: var(--fd); font-size: 1.5rem; font-weight: 800; color: var(--ocean-deep);">📜 History Aktivitas</h1>
</div>

<div class="d-card">
    <div class="d-card-title">🕐 Semua Aktivitasmu</div>
    @forelse($log as $item)
    <div class="history-item">
        <div>
            <div class="history-desc">{{ $item->deskripsi }}</div>
            <div class="history-aksi">🏷️ {{ str_replace('_',' ', $item->aksi) }}</div>
        </div>
        <div class="history-time">
            {{ $item->created_at->format('d M Y') }}<br>
            {{ $item->created_at->format('H:i') }} WIB
        </div>
    </div>
    @empty
    <p style="font-size:0.82rem; color:#aaa; text-align:center; padding: 30px 0;">Belum ada aktivitas tercatat</p>
    @endforelse

    {{-- Pagination --}}
    <div style="margin-top: 16px;">
        {{ $log->links() }}
    </div>
</div>
@endsection