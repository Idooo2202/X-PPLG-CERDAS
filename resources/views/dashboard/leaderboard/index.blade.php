@extends('layouts.dashboard')
@section('title', 'Leaderboard')

@section('extra-styles')
<style>
    .lb-podium {
        display: flex;
        justify-content: center;
        align-items: flex-end;
        gap: 14px;
        margin-bottom: 28px;
        flex-wrap: wrap;
    }

    .lb-pod {
        text-align: center;
        transition: all 0.3s;
    }

    .lb-pod:hover {
        transform: translateY(-4px);
    }

    .lb-pod-crown {
        font-size: 1.8rem;
        margin-bottom: 4px;
    }

    .lb-pod-avatar {
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--fd);
        font-weight: 800;
        color: #fff;
        margin: 0 auto 6px;
    }

    .lb-pod-name {
        font-family: var(--fd);
        font-weight: 700;
        font-size: 0.8rem;
    }

    .lb-pod-poin {
        font-family: var(--fd);
        font-weight: 800;
        color: var(--ocean);
    }

    .lb-pod-stand {
        border-radius: 10px 10px 4px 4px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: var(--fd);
        font-weight: 800;
        color: #fff;
        font-size: 1.4rem;
    }

    .lb-table-row {
        display: grid;
        grid-template-columns: 40px 1fr auto;
        align-items: center;
        padding: 10px 14px;
        border-radius: 12px;
        margin-bottom: 6px;
        background: var(--row-bg, #f8fbff);
        gap: 12px;
        transition: all 0.2s;
    }

    .lb-table-row:hover {
        background: var(--row-hover, #eaf4ff);
        transform: translateX(3px);
    }

    .lb-table-row.me {
        background: linear-gradient(90deg, rgba(0, 119, 190, 0.12), rgba(64, 224, 208, 0.1));
        border: 2px solid rgba(0, 119, 190, 0.3);
    }

    .lb-rank {
        font-family: var(--fd);
        font-weight: 800;
        font-size: 1rem;
        color: #ccc;
        text-align: center;
    }

    .lb-rank.gold {
        color: #FFD700;
    }

    .lb-rank.silver {
        color: #C0C0C0;
    }

    .lb-rank.bronze {
        color: #CD7F32;
    }

    .lb-uname {
        font-family: var(--fd);
        font-weight: 700;
        font-size: 0.88rem;
        color: var(--text-main, #0a2540);
    }

    .lb-usub {
        font-size: 0.7rem;
        color: var(--text-muted, #aaa);
        margin-top: 1px;
    }

    .lb-right {
        text-align: right;
    }

    .lb-poin {
        font-family: var(--fd);
        font-weight: 800;
        color: var(--ocean);
        font-size: 0.95rem;
    }

    .lb-tier {
        font-size: 0.68rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 50px;
        display: inline-block;
        margin-top: 2px;
    }

    .t-sultan {
        background: linear-gradient(90deg, #FFD700, #FFA500);
        color: #fff;
    }

    .t-kaya {
        background: linear-gradient(90deg, #C0C0C0, #A0A0A0);
        color: #fff;
    }

    .t-normal {
        background: linear-gradient(90deg, var(--ocean), var(--turq));
        color: #fff;
    }

    .t-kelas_bawah {
        background: #eee;
        color: #888;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 20px;">
    <h1 style="font-family: var(--fd); font-size: 1.5rem; font-weight: 800; color: var(--ocean-deep);">🏆 Leaderboard X PPLG C</h1>
    <p style="font-size:0.82rem; color:#aaa; margin-top:2px;">Ranking berdasarkan poin kehadiran & iuran kas</p>
</div>

{{-- Posisi Saya --}}
@if($myRank)
<div class="d-card" style="margin-bottom: 18px; background: linear-gradient(90deg, var(--ocean-deep), #003070); color:#fff;">
    <div style="font-size:0.78rem; opacity:0.7; text-transform:uppercase; letter-spacing:1px; margin-bottom:4px;">📍 Posisimu</div>
    <div style="font-family:var(--fd); font-size:1.4rem; font-weight:800;">
        Rank #{{ $myRank->rank }} — {{ $myRank->poin }} Poin
    </div>
    <div style="font-size:0.8rem; opacity:0.75; margin-top:3px;">
        🔥 Streak Hadir: {{ $myRank->streak_hadir }} hari &nbsp;|&nbsp; 💰 Streak Kas: {{ $myRank->streak_kas }} hari
    </div>
</div>
@endif

{{-- Podium Top 3 --}}
@php $top3 = $rankings->take(3); @endphp
@if($top3->count() >= 1)
<div class="lb-podium">
    {{-- 2nd --}}
    @if($top3->count() >= 2)
    <div class="lb-pod">
        <div class="lb-pod-crown">🥈</div>
        <div class="lb-pod-avatar" style="width:50px;height:50px;background:linear-gradient(135deg,#C0C0C0,#A0A0A0);font-size:1.1rem;">
            {{ strtoupper(substr($top3->get(1)->user->nama_lengkap, 0, 1)) }}
        </div>
        <div class="lb-pod-name">{{ explode(' ', $top3->get(1)->user->nama_lengkap)[0] }}</div>
        <div class="lb-pod-poin">{{ $top3->get(1)->poin }} ⭐</div>
        <div class="lb-pod-stand" style="width:80px;height:60px;background:linear-gradient(180deg,#C0C0C0,#A0A0A0);">2</div>
    </div>
    @endif
    {{-- 1st --}}
    <div class="lb-pod">
        <div class="lb-pod-crown">👑</div>
        <div class="lb-pod-avatar" style="width:64px;height:64px;background:linear-gradient(135deg,#FFD700,#FFA500);font-size:1.3rem;">
            {{ strtoupper(substr($top3->get(0)->user->nama_lengkap, 0, 1)) }}
        </div>
        <div class="lb-pod-name" style="font-size:0.95rem;">{{ explode(' ', $top3->get(0)->user->nama_lengkap)[0] }}</div>
        <div class="lb-pod-poin" style="font-size:1.1rem;">{{ $top3->get(0)->poin }} ⭐</div>
        <div class="lb-pod-stand" style="width:80px;height:80px;background:linear-gradient(180deg,#FFD700,#FFA500);">1</div>
    </div>
    {{-- 3rd --}}
    @if($top3->count() >= 3)
    <div class="lb-pod">
        <div class="lb-pod-crown">🥉</div>
        <div class="lb-pod-avatar" style="width:44px;height:44px;background:linear-gradient(135deg,#CD7F32,#A0522D);font-size:1rem;">
            {{ strtoupper(substr($top3->get(2)->user->nama_lengkap, 0, 1)) }}
        </div>
        <div class="lb-pod-name">{{ explode(' ', $top3->get(2)->user->nama_lengkap)[0] }}</div>
        <div class="lb-pod-poin">{{ $top3->get(2)->poin }} ⭐</div>
        <div class="lb-pod-stand" style="width:80px;height:50px;background:linear-gradient(180deg,#CD7F32,#A0522D);">3</div>
    </div>
    @endif
</div>
@endif

{{-- Full Ranking --}}
<div class="d-card">
    <div class="d-card-title">📊 Ranking Lengkap</div>
    @foreach($rankings as $lb)
    @php
    $rankClass = $lb->rank === 1 ? 'gold' : ($lb->rank === 2 ? 'silver' : ($lb->rank === 3 ? 'bronze' : ''));
    $isMe = $lb->user_id === session('user_id');
    @endphp
    <div class="lb-table-row {{ $isMe ? 'me' : '' }}">
        <div class="lb-rank {{ $rankClass }}">
            @if($lb->rank === 1) 👑 @elseif($lb->rank === 2) 🥈 @elseif($lb->rank === 3) 🥉 @else #{{ $lb->rank }} @endif
        </div>
        <div>
            <div class="lb-uname">{{ $lb->user->nama_lengkap }} {{ $isMe ? '← Kamu' : '' }}</div>
            <div class="lb-usub">🔥 {{ $lb->streak_hadir }}d streak hadir &nbsp;|&nbsp; 💰 {{ $lb->streak_kas }}d streak kas</div>
        </div>
        <div class="lb-right">
            <div class="lb-poin">{{ $lb->poin }} ⭐</div>
            <div class="lb-tier t-{{ $lb->tier }}">{{ ucfirst(str_replace('_',' ', $lb->tier)) }}</div>
        </div>
    </div>
    @endforeach
</div>
@endsection