@extends('layouts.dashboard')
@section('title', 'Kas Kelas')

@section('extra-styles')
<style>
    .kas-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 22px;
    }

    @media (max-width: 600px) {
        .kas-summary {
            grid-template-columns: 1fr;
        }
    }

    .ks-card {
        background: var(--card-bg, #fff);
        border-radius: var(--r);
        padding: 18px;
        box-shadow: var(--sh);
        text-align: center;
        border: 1px solid var(--card-border, rgba(0,119,190,0.08));
        transition: background 0.4s ease;
    }

    .ks-val {
        font-family: var(--fd);
        font-size: 1.5rem;
        font-weight: 800;
    }

    .ks-lbl {
        font-size: 0.72rem;
        color: #aaa;
        text-transform: uppercase;
        font-weight: 700;
        margin-top: 3px;
    }

    .kas-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    @media (max-width: 900px) {
        .kas-grid {
            grid-template-columns: 1fr;
        }
    }

    .trx-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 9px 12px;
        border-radius: 10px;
        background: var(--row-bg, #f8fbff);
        margin-bottom: 6px;
        font-size: 0.83rem;
        transition: background 0.3s;
    }

    .trx-item.pengeluaran {
        background: rgba(255,123,107,0.08);
    }

    body.dark-mode .trx-item {
        background: rgba(255,255,255,0.05);
    }

    body.dark-mode .trx-item.pengeluaran {
        background: rgba(255,123,107,0.12);
    }

    .trx-badge {
        font-family: var(--fd);
        font-weight: 700;
        font-size: 0.85rem;
    }

    .trx-badge.masuk {
        color: #1a7a4a;
    }

    .trx-badge.keluar {
        color: var(--coral);
    }

    .pay-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 12px;
        border-radius: 10px;
        background: var(--row-bg, #f8fbff);
        margin-bottom: 5px;
        transition: background 0.3s;
    }

    .pay-row .pname {
        font-weight: 600;
        font-size: 0.83rem;
    }

    .pay-toggle {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 700;
        transition: all 0.2s;
    }

    .pay-toggle.paid {
        background: #d4edda;
        color: #1a7a4a;
    }

    .pay-toggle.unpaid {
        background: #f8d7da;
        color: var(--coral);
    }

    /* Chart Styles */
    .chart-container {
        background: var(--card-bg, #fff);
        border-radius: var(--r);
        padding: 22px;
        box-shadow: var(--sh);
        margin-bottom: 22px;
        border: 1px solid var(--card-border, rgba(0,119,190,0.08));
        transition: background 0.4s ease;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .chart-title {
        font-family: var(--fd);
        font-weight: 700;
        font-size: 1rem;
        color: var(--ocean);
    }

    body.dark-mode .chart-title {
        color: var(--turq);
    }

    .chart-mode-selector {
        display: flex;
        gap: 5px;
        background: rgba(0,119,190,0.07);
        padding: 4px;
        border-radius: 12px;
    }

    body.dark-mode .chart-mode-selector {
        background: rgba(255,255,255,0.07);
    }

    .chart-mode-btn {
        padding: 7px 16px;
        border: none;
        background: transparent;
        border-radius: 9px;
        font-family: var(--fd);
        font-weight: 700;
        font-size: 0.75rem;
        color: #888;
        cursor: pointer;
        transition: all 0.2s;
    }

    body.dark-mode .chart-mode-btn {
        color: rgba(255,255,255,0.5);
    }

    .chart-mode-btn.active {
        background: linear-gradient(135deg, var(--ocean), var(--turq-dk));
        color: #fff;
        box-shadow: 0 4px 14px rgba(0,119,190,0.3);
    }

    .chart-mode-btn:hover:not(.active) {
        background: rgba(0,119,190,0.1);
        color: var(--ocean);
    }

    body.dark-mode .chart-mode-btn:hover:not(.active) {
        background: rgba(64,224,208,0.1);
        color: var(--turq);
    }

    .chart-wrapper {
        position: relative;
        height: 300px;
        width: 100%;
    }

    .chart-legend {
        display: flex;
        gap: 18px;
        margin-top: 14px;
        flex-wrap: wrap;
    }

    .chart-legend-item {
        display: flex;
        align-items: center;
        gap: 7px;
        font-family: var(--fd);
        font-size: 0.78rem;
        font-weight: 700;
        color: var(--text-main, #0a2540);
    }

    .chart-legend-dot {
        width: 12px;
        height: 12px;
        border-radius: 4px;
        flex-shrink: 0;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 20px;">
    <h1 style="font-family: var(--fd); font-size: 1.5rem; font-weight: 800; color: var(--ocean-deep);">💰 Kas Kelas</h1>
    <p style="font-size:0.82rem; color:#aaa; margin-top:2px;">Bulan {{ DateTime::createFromFormat('!m', $bulan)->format('F') }} {{ $tahun }}</p>
</div>

{{-- Grafik --}}
<div class="chart-container">
    <div class="chart-header">
        <div class="chart-title">📊 Grafik Arus Kas</div>
        <div class="chart-mode-selector">
            <button class="chart-mode-btn {{ $viewMode === 'weekly' ? 'active' : '' }}" onclick="changeChartMode('weekly')">📅 Mingguan</button>
            <button class="chart-mode-btn {{ $viewMode === 'monthly' ? 'active' : '' }}" onclick="changeChartMode('monthly')">📆 Bulanan</button>
            <button class="chart-mode-btn {{ $viewMode === 'yearly' ? 'active' : '' }}" onclick="changeChartMode('yearly')">🗓️ Tahunan</button>
        </div>
    </div>
    <div class="chart-wrapper">
        <canvas id="kasChart"></canvas>
    </div>
    <div class="chart-legend">
        <div class="chart-legend-item">
            <div class="chart-legend-dot" style="background:rgba(26,122,74,0.85);"></div>
            📥 Pemasukan
        </div>
        <div class="chart-legend-item">
            <div class="chart-legend-dot" style="background:rgba(255,123,107,0.85);"></div>
            📤 Pengeluaran
        </div>
    </div>
</div>

{{-- Ringkasan --}}
<div class="kas-summary">
    <div class="ks-card">
        <div class="ks-val" style="color:#1a7a4a">Rp{{ number_format($totalPemasukan,0,',','.') }}</div>
        <div class="ks-lbl">📥 Pemasukan</div>
    </div>
    <div class="ks-card">
        <div class="ks-val" style="color:var(--coral)">Rp{{ number_format($totalPengeluaran,0,',','.') }}</div>
        <div class="ks-lbl">📤 Pengeluaran</div>
    </div>
    <div class="ks-card">
        <div class="ks-val" {!! $saldo >= 0 ? 'style="color: var(--ocean)"' : 'style="color: var(--coral)"' !!}>Rp{{ number_format($saldo,0,',','.') }}</div>
        <div class="ks-lbl">💼 Saldo</div>
    </div>
</div>

<div class="kas-grid">

    {{-- Riwayat Transaksi --}}
    <div class="d-card">
        <div class="d-card-title">📋 Riwayat Transaksi</div>
        @forelse($transaksi as $trx)
        <div class="trx-item {{ $trx->jenis }}">
            <div>
                <div style="font-weight:600; font-size:0.84rem;">{{ $trx->keterangan }}</div>
                <div style="font-size:0.72rem; color:#aaa;">{{ $trx->tanggal->format('d M Y') }} — {{ $trx->user->nama_lengkap }}</div>
            </div>
            <div class="trx-badge {{ $trx->jenis === 'pemasukan' ? 'masuk' : 'keluar' }}">
                {{ $trx->jenis === 'pemasukan' ? '+' : '-' }}Rp{{ number_format($trx->jumlah,0,',','.') }}
            </div>
        </div>
        @empty
        <p style="font-size:0.82rem; color:#aaa; text-align:center; padding:20px 0;">Belum ada transaksi bulan ini</p>
        @endforelse

        {{-- Input Transaksi — hanya wali & bendahara --}}
        @if(in_array(session('user_role'), ['wali_kelas','bendahara']))
        <hr style="margin: 16px 0; border: none; border-top: 1px solid #eee;">
        <div class="d-card-title" style="font-size:0.88rem; margin-bottom:12px;">➕ Tambah Transaksi</div>
        <form action="{{ route('kas.store') }}" method="POST" style="display:grid; gap:10px;">
            @csrf
            <select name="jenis" style="padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none; width:100%;">
                <option value="pemasukan">📥 Pemasukan</option>
                <option value="pengeluaran">📤 Pengeluaran</option>
            </select>
            <input type="number" name="jumlah" placeholder="Jumlah (Rp)" min="100" required
                style="padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none;">
            <input type="text" name="keterangan" placeholder="Keterangan..." required
                style="padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none;">
            <input type="date" name="tanggal" value="{{ now()->toDateString() }}" required
                style="padding:9px 12px; border:2px solid #eee; border-radius:10px; font-family:var(--fb); outline:none;">
            <button type="submit" style="padding:10px; background:linear-gradient(90deg,var(--ocean),var(--turq-dk)); color:#fff; border:none; border-radius:10px; font-family:var(--fd); font-weight:700; cursor:pointer;">💾 Simpan</button>
        </form>
        @endif
    </div>

    {{-- Checklist Kas Harian --}}
    @if(in_array(session('user_role'), ['wali_kelas','bendahara']))
    <div class="d-card">
        <div class="d-card-title">✅ Iuran Kas Hari Ini — {{ now()->format('d M Y') }}</div>
        @foreach($siswaList as $siswa)
        @php $p = $payments[$siswa->id] ?? null; $paid = $p && $p->is_paid; @endphp
        <div class="pay-row">
            <div>
                <div class="pname">{{ $siswa->no_absen ? "[{$siswa->no_absen}]" : '' }} {{ $siswa->nama_lengkap }}</div>
                @if($paid)
                <div style="font-size:0.68rem; color:#1a7a4a;">✅ Sudah bayar Rp{{ number_format($p->jumlah,0,',','.') }}</div>
                @else
                <div style="font-size:0.68rem; color:#aaa;">Belum bayar</div>
                @endif
            </div>
            <form action="{{ route('kas.payment.toggle', $siswa) }}" method="POST">
                @csrf
                <button type="submit" class="pay-toggle {{ $paid ? 'paid' : 'unpaid' }}">
                    {{ $paid ? '✅' : '❌' }}
                </button>
            </form>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Data dari controller
    const chartData = @json($chartData);
    const currentViewMode = '{{ $viewMode }}';
    const bulan = {{ $bulan }};
    const tahun = {{ $tahun }};

    let kasChart = null;

    function getChartColors() {
        const isDark = document.body.classList.contains('dark-mode');
        return {
            gridColor: isDark ? 'rgba(255,255,255,0.04)' : 'rgba(0,119,190,0.07)',
            tickColor: isDark ? '#475569' : '#888',
            tooltipBg: isDark ? 'rgba(13,17,23,0.97)' : 'rgba(0,31,63,0.92)',
            tooltipBorder: isDark ? 'rgba(76,161,175,0.3)' : 'rgba(0,119,190,0.3)',
            isDark: isDark,
        };
    }

    function initChart() {
        const ctx = document.getElementById('kasChart').getContext('2d');
        const colors = getChartColors();
        const isDark = colors.isDark;

        // Premium gradient fills — glow effect in dark mode
        const gradGreen = ctx.createLinearGradient(0, 0, 0, 300);
        if (isDark) {
            gradGreen.addColorStop(0, 'rgba(74,222,128,0.8)');
            gradGreen.addColorStop(0.5, 'rgba(74,222,128,0.35)');
            gradGreen.addColorStop(1, 'rgba(74,222,128,0.04)');
        } else {
            gradGreen.addColorStop(0, 'rgba(26,122,74,0.85)');
            gradGreen.addColorStop(1, 'rgba(26,122,74,0.12)');
        }

        const gradCoral = ctx.createLinearGradient(0, 0, 0, 300);
        if (isDark) {
            gradCoral.addColorStop(0, 'rgba(248,113,113,0.8)');
            gradCoral.addColorStop(0.5, 'rgba(248,113,113,0.35)');
            gradCoral.addColorStop(1, 'rgba(248,113,113,0.04)');
        } else {
            gradCoral.addColorStop(0, 'rgba(255,123,107,0.85)');
            gradCoral.addColorStop(1, 'rgba(255,123,107,0.12)');
        }

        kasChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: chartData.pemasukan,
                        backgroundColor: gradGreen,
                        borderColor: isDark ? 'rgba(74,222,128,0.7)' : 'rgba(26,122,74,0.9)',
                        borderWidth: isDark ? 1 : 0,
                        borderRadius: 8,
                        borderSkipped: false,
                        hoverBackgroundColor: isDark ? 'rgba(74,222,128,0.95)' : 'rgba(26,122,74,0.95)',
                    },
                    {
                        label: 'Pengeluaran',
                        data: chartData.pengeluaran,
                        backgroundColor: gradCoral,
                        borderColor: isDark ? 'rgba(248,113,113,0.7)' : 'rgba(255,123,107,0.9)',
                        borderWidth: isDark ? 1 : 0,
                        borderRadius: 8,
                        borderSkipped: false,
                        hoverBackgroundColor: isDark ? 'rgba(248,113,113,0.95)' : 'rgba(255,123,107,0.95)',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: colors.tooltipBg,
                        titleFont: { family: "'Baloo 2', cursive", size: 13, weight: '700' },
                        bodyFont: { family: "'Nunito', sans-serif", size: 12 },
                        padding: 14,
                        cornerRadius: 12,
                        borderColor: colors.tooltipBorder,
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                const icon = context.datasetIndex === 0 ? '📥' : '📤';
                                return ` ${icon} ${context.dataset.label}: Rp${new Intl.NumberFormat('id-ID').format(context.raw)}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: colors.gridColor, drawBorder: false },
                        border: { display: false },
                        ticks: {
                            font: { family: "'Nunito', sans-serif", size: 11 },
                            color: colors.tickColor,
                            padding: 8,
                            callback: function(value) {
                                if (value === 0) return 'Rp0';
                                return 'Rp' + new Intl.NumberFormat('id-ID', { notation: 'compact', maximumFractionDigits: 1 }).format(value);
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            font: { family: "'Nunito', sans-serif", size: 11 },
                            color: colors.tickColor,
                            padding: 6,
                        }
                    }
                },
                animation: { duration: 800, easing: 'easeInOutQuart' }
            }
        });
    }

    function changeChartMode(mode) {
        document.querySelectorAll('.chart-mode-btn').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
        const url = new URL(window.location.href);
        url.searchParams.set('view_mode', mode);
        window.location.href = url.toString();
    }

    document.addEventListener('DOMContentLoaded', function() {
        initChart();

        // Re-init chart colors when theme changes
        const observer = new MutationObserver(function() {
            if (kasChart) {
                kasChart.destroy();
                initChart();
            }
        });
        observer.observe(document.body, { attributes: true, attributeFilter: ['class'] });
    });
</script>
@endsection