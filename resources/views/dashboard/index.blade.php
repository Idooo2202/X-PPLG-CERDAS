@extends('layouts.dashboard')
@section('title', 'Beranda')

@section('extra-styles')
<style>
    .beranda-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-top: 0;
    }

    @media (max-width: 768px) {
        .beranda-grid {
            grid-template-columns: 1fr;
        }
    }

    .stat-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }

    @media (max-width: 900px) {
        .stat-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 500px) {
        .stat-row {
            grid-template-columns: 1fr 1fr;
        }
    }

    .stat-card {
        background: var(--card-bg, #fff);
        border-radius: var(--r);
        padding: 16px;
        box-shadow: var(--sh);
        border-left: 4px solid var(--ocean);
        display: flex;
        flex-direction: column;
        gap: 4px;
        border-top: 1px solid var(--card-border, rgba(0, 119, 190, 0.08));
        border-right: 1px solid var(--card-border, rgba(0, 119, 190, 0.08));
        border-bottom: 1px solid var(--card-border, rgba(0, 119, 190, 0.08));
        transition: background 0.4s ease;
    }

    .stat-val {
        font-family: var(--fd);
        font-weight: 800;
        font-size: 1.6rem;
        color: var(--ocean);
    }

    .stat-lbl {
        font-size: 0.75rem;
        color: var(--text-muted, #888);
        font-weight: 600;
        text-transform: uppercase;
    }

    .live-clock {
        font-family: var(--fd);
        font-size: 2rem;
        font-weight: 800;
        color: var(--deep);
        letter-spacing: 2px;
    }

    .live-day {
        font-size: 0.82rem;
        color: #bbb;
        font-weight: 600;
        margin-top: 2px;
        margin-bottom: 10px;
    }

    .lbox {
        padding: 12px;
        border-radius: 12px;
        color: #fff;
        background: linear-gradient(135deg, var(--ocean), var(--turq-dk));
        margin-bottom: 8px;
    }

    .lbox.nxt {
        background: var(--seafoam);
        color: var(--deep);
    }

    .ll {
        font-size: 0.68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        opacity: 0.82;
    }

    .ln {
        font-family: var(--fd);
        font-weight: 700;
        font-size: 0.92rem;
        margin-top: 2px;
    }

    .lt {
        font-size: 0.76rem;
        opacity: 0.88;
        margin-top: 1px;
    }

    .si {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 7px 10px;
        border-radius: 9px;
        margin-bottom: 3px;
        font-size: 0.78rem;
        background: var(--row-bg, #f5f9ff);
        transition: background 0.3s;
    }

    .si.active {
        background: linear-gradient(90deg, rgba(0, 119, 190, 0.13), rgba(64, 224, 208, 0.13));
        border-left: 3px solid var(--ocean);
        font-weight: 700;
        color: var(--ocean);
    }

    .si.brk {
        background: #fff9f4;
        color: #c08060;
        font-style: italic;
    }

    .si-t {
        color: #ccc;
        font-size: 0.72rem;
        flex-shrink: 0;
        margin-left: 8px;
    }

    .piket-badge {
        display: inline-block;
        background: var(--ocean);
        color: #fff;
        font-family: var(--fd);
        font-weight: 700;
        font-size: 0.8rem;
        padding: 3px 13px;
        border-radius: 50px;
        margin-bottom: 11px;
    }

    .piket-list {
        list-style: none;
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .piket-list li {
        background: var(--sandy);
        color: var(--ocean-dk);
        font-family: var(--fd);
        font-weight: 600;
        font-size: 0.78rem;
        padding: 4px 11px;
        border-radius: 50px;
    }

    .piket-list li::before {
        content: '🐚 ';
    }

    .tier-badge {
        padding: 3px 12px;
        border-radius: 50px;
        font-family: var(--fd);
        font-weight: 700;
        font-size: 0.8rem;
    }

    .tier-sultan {
        background: linear-gradient(90deg, #FFD700, #FFA500);
        color: #fff;
    }

    .tier-kaya {
        background: linear-gradient(90deg, #C0C0C0, #A8A8A8);
        color: #fff;
    }

    .tier-normal {
        background: linear-gradient(90deg, var(--ocean), var(--turq));
        color: #fff;
    }

    .tier-kelas_bawah {
        background: #eee;
        color: #888;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 20px;">
    <h1 style="font-family: var(--fd); font-size: 1.6rem; font-weight: 800; color: var(--ocean-deep);">
        🏠 Beranda <span style="font-size:1rem; color:#aaa; font-weight:600;">— Halo, {{ session('user_name') }}! 👋</span>
    </h1>
</div>

{{-- Statistik singkat --}}
<div class="stat-row">
    @if($user->role === 'wali_kelas')
    <div class="stat-card">
        <div class="stat-val">{{ $siswaAktif }}</div>
        <div class="stat-lbl">👨‍🎓 Siswa Aktif</div>
    </div>
    <div class="stat-card" style="border-left-color: var(--coral)">
        <div class="stat-val" style="color:var(--coral)">{{ $pesanBelumDibaca }}</div>
        <div class="stat-lbl">🔔 Pesan Belum Dibaca</div>
    </div>
    <div class="stat-card" style="border-left-color: var(--turq)">
        <div class="stat-val" style="color:var(--turq)">{{ $pelanggaranPending }}</div>
        <div class="stat-lbl">⚠️ Pelanggaran Pending</div>
    </div>
    <div class="stat-card" style="border-left-color: #FFA500">
        <div class="stat-val" style="color:#FFA500; font-size:1.1rem; margin-top:4px;">
            <span class="tier-badge tier-kelas_bawah">Wali Kelas</span>
        </div>
        <div class="stat-lbl">👩‍🏫 Role</div>
    </div>
    @else
    <div class="stat-card">
        <div class="stat-val">{{ $kehadiranBulanIni->where('status','hadir')->count() }}</div>
        <div class="stat-lbl">✅ Hadir Bulan Ini</div>
    </div>
    <div class="stat-card" style="border-left-color: var(--coral)">
        <div class="stat-val" style="color:var(--coral)">{{ $kehadiranBulanIni->where('status','alpha')->count() }}</div>
        <div class="stat-lbl">❌ Alpha</div>
    </div>
    <div class="stat-card" style="border-left-color: var(--turq)">
        <div class="stat-val" style="color:var(--turq)">{{ $user->leaderboard->poin ?? 0 }}</div>
        <div class="stat-lbl">⭐ Total Poin</div>
    </div>
    <div class="stat-card" style="border-left-color: #FFA500">
        <div class="stat-val" style="color:#FFA500; font-size:1.1rem; margin-top:4px;">
            <span class="tier-badge tier-{{ $user->leaderboard->tier ?? 'kelas_bawah' }}">
                {{ ucfirst(str_replace('_', ' ', $user->leaderboard->tier ?? 'kelas bawah')) }}
            </span>
        </div>
        <div class="stat-lbl">🏅 Tier</div>
    </div>
    @endif
</div>

{{-- Beranda grid --}}
<div class="beranda-grid">

    {{-- Jadwal --}}
    <div class="d-card">
        <div class="d-card-title">⏰ Jadwal Sekarang</div>
        <div class="live-clock" id="liveClock">--:--:--</div>
        <div class="live-day" id="liveDay">---</div>
        <div class="lbox">
            <div class="ll">📚 Sekarang</div>
            <div class="ln" id="nowName">Memuat…</div>
            <div class="lt" id="nowTime"></div>
        </div>
        <div class="lbox nxt">
            <div class="ll">⏭ Selanjutnya</div>
            <div class="ln" id="nextName">Memuat…</div>
            <div class="lt" id="nextTime"></div>
        </div>
    </div>

    {{-- Piket & Jadwal Lengkap --}}
    <div class="d-card">
        <div class="d-card-title">🧹 Piket & Jadwal Penuh</div>
        <div class="piket-badge" id="piketDay">---</div>
        <ul class="piket-list" id="piketList"></ul>
        <div style="margin-top:16px">
            <div class="d-card-title" style="font-size:0.88rem; margin-bottom:8px;">📋 Jadwal Hari Ini</div>
            <div id="fullSch"></div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Data jadwal & piket (sama persis dengan homescreen)
    const DAYS = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const pad = n => String(n).padStart(2, '0');
    const t2m = t => {
        const [h, m] = t.split(':').map(Number);
        return h * 60 + m;
    };

    function getWIB() {
        const now = new Date();
        return new Date(now.getTime() + now.getTimezoneOffset() * 60000 + 7 * 3600000);
    }

    const PIKET = {
        1: ['Rido Ganteng', 'Evita', 'Asroh', 'Aisyah', 'Alfino', 'Risha', 'Yunisa', 'Nayla'],
        2: ['Arda', 'Amel', 'Rezza', 'Dea', 'Rafi', 'Cantika', 'Zaskya', 'Regita', 'Keyina'],
        3: ['Farhan', 'Kiran', 'Kustian', 'Nabila', 'Renita', 'Wulan', 'Windi', 'Meli'],
        4: ['EKOLOGI'],
        5: ['Ahyar', 'Zein', 'Faris', 'Early', 'Vina', 'Fitria', 'Mila', 'Livia', 'Fauzan'],
    };
    const LESSONS = {
        1: [{
            name: 'Upacara',
            start: '06:30',
            end: '07:15'
        }, {
            name: 'PJOK',
            start: '07:15',
            end: '09:30'
        }, {
            name: '☕ Istirahat I',
            start: '09:30',
            end: '09:45',
            isBreak: true
        }, {
            name: 'Matematika',
            start: '09:45',
            end: '10:30'
        }, {
            name: 'Mulok 1',
            start: '10:30',
            end: '12:00'
        }, {
            name: '☕ Istirahat II',
            start: '12:00',
            end: '12:45',
            isBreak: true
        }, {
            name: 'Mulok 1',
            start: '12:45',
            end: '13:30'
        }, {
            name: 'DPK(Pa Iip)',
            start: '13:30',
            end: '15:00'
        }],
        2: [{
            name: 'WK/BK/PB',
            start: '06:30',
            end: '07:15'
        }, {
            name: 'Bahasa Indonesia',
            start: '07:15',
            end: '08:45'
        }, {
            name: 'PAIBP',
            start: '08:45',
            end: '09:00'
        }, {
            name: '☕ Istirahat I',
            start: '09:30',
            end: '09:45',
            isBreak: true
        }, {
            name: 'PAIBP',
            start: '09:45',
            end: '10:30'
        }, {
            name: 'PIPAS',
            start: '10:30',
            end: '12:00'
        }, {
            name: '☕ Istirahat II',
            start: '12:00',
            end: '12:45',
            isBreak: true
        }, {
            name: 'PIPAS',
            start: '12:45',
            end: '13:30'
        }, {
            name: 'Bahasa Inggris',
            start: '13:30',
            end: '15:00'
        }],
        3: [{
            name: 'Matematika',
            start: '06:30',
            end: '08:00'
        }, {
            name: 'DPK (Pa Iip)',
            start: '08:00',
            end: '09:30'
        }, {
            name: '☕ Istirahat I',
            start: '09:30',
            end: '09:45',
            isBreak: true
        }, {
            name: 'DPK (Bu Yeni)',
            start: '09:45',
            end: '12:00'
        }, {
            name: '☕ Istirahat II',
            start: '12:00',
            end: '12:45',
            isBreak: true
        }, {
            name: 'DPK (Bu Yeni)',
            start: '12:45',
            end: '13:30'
        }, {
            name: 'Bahasa Inggris',
            start: '13:30',
            end: '15:00'
        }],
        4: [{
            name: 'DPK (Pa Aldhi)',
            start: '06:30',
            end: '09:30'
        }, {
            name: '☕ Istirahat I',
            start: '09:30',
            end: '09:45',
            isBreak: true
        }, {
            name: 'Seni Budaya',
            start: '09:45',
            end: '11:15'
        }, {
            name: 'Pendidikan Pancasila(PP)',
            start: '11:15',
            end: '12:00'
        }, {
            name: '☕ Istirahat II',
            start: '12:00',
            end: '12:45',
            isBreak: true
        }, {
            name: 'Pendidikan Pancasila(PP)',
            start: '12:45',
            end: '13:30'
        }, {
            name: 'Informatika',
            start: '13:30',
            end: '15:00'
        }],
        5: [{
            name: 'Duha',
            start: '06:30',
            end: '07:15'
        }, {
            name: 'Sejarah',
            start: '07:15',
            end: '08:45'
        }, {
            name: 'Bahasa Indonesia',
            start: '08:45',
            end: '09:30'
        }, {
            name: '☕ Istirahat I',
            start: '09:30',
            end: '09:45',
            isBreak: true
        }, {
            name: 'Mulok 2',
            start: '09:45',
            end: '11:15'
        }, {
            name: 'Matematika',
            start: '11:15',
            end: '12:00'
        }, {
            name: '☕ Istirahat II',
            start: '12:00',
            end: '12:45',
            isBreak: true
        }, {
            name: 'Mulok 2',
            start: '12:45',
            end: '13:30'
        }],
    };

    function updateClock() {
        const wib = getWIB();
        const h = pad(wib.getHours()),
            m = pad(wib.getMinutes()),
            s = pad(wib.getSeconds());
        const dayIdx = wib.getDay();
        document.getElementById('liveClock').textContent = `${h}:${m}:${s}`;
        document.getElementById('liveDay').textContent = `${DAYS[dayIdx]}, ${wib.getDate()}/${wib.getMonth()+1}/${wib.getFullYear()} WIB`;

        const nowMin = wib.getHours() * 60 + wib.getMinutes();
        const lessons = LESSONS[dayIdx] || [];
        let nowL = null,
            nextL = null;
        for (let i = 0; i < lessons.length; i++) {
            const st = t2m(lessons[i].start),
                en = t2m(lessons[i].end);
            if (nowMin >= st && nowMin < en) {
                nowL = lessons[i];
                nextL = lessons[i + 1] || null;
                break;
            }
            if (nowMin < st && !nextL) {
                nextL = lessons[i];
            }
        }
        document.getElementById('nowName').textContent = nowL ? nowL.name : 'Tidak ada kelas';
        document.getElementById('nowTime').textContent = nowL ? `${nowL.start} – ${nowL.end}` : '';
        document.getElementById('nextName').textContent = nextL ? nextL.name : 'Selesai untuk hari ini 🎉';
        document.getElementById('nextTime').textContent = nextL ? `${nextL.start} – ${nextL.end}` : '';

        // Piket
        const piket = PIKET[dayIdx] || [];
        document.getElementById('piketDay').textContent = piket.length ? `🧹 Piket ${DAYS[dayIdx]}` : `Tidak ada jadwal piket`;
        const pl = document.getElementById('piketList');
        pl.innerHTML = piket.map(n => `<li>${n}</li>`).join('');

        // Jadwal Penuh Hari Ini
        const fs = document.getElementById('fullSch');
        if (!lessons.length) {
            fs.innerHTML = '<div style="color:#aaa;font-size:0.8rem;">Libur / Tidak ada kelas</div>';
            return;
        }
        fs.innerHTML = lessons.map((l, i) => {
            const st = t2m(l.start),
                en = t2m(l.end);
            const isNow = nowMin >= st && nowMin < en;
            return `<div class="si ${isNow ? 'active' : ''} ${l.isBreak ? 'brk' : ''}">
        <span>${l.name}</span>
        <span class="si-t">${l.start}–${l.end}</span>
      </div>`;
        }).join('');
    }

    updateClock();
    setInterval(updateClock, 1000);
</script>
@endsection