@extends('layouts.main')

@section('title', 'Dashboard — SIMAPES')

{{-- ══════════════════════════════════════════
Scoped Styles
══════════════════════════════════════════ --}}
@push('styles')
    <style>
        /* ── Page Header ── */
        .dash-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .dash-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1a2b4a;
            line-height: 1.2;
        }

        .dash-subtitle {
            font-size: .9rem;
            color: #6b7e9f;
            margin-top: 4px;
        }

        .dash-date {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .85rem;
            color: #6b7e9f;
            background: #fff;
            border: 1px solid #e2e8f4;
            border-radius: 10px;
            padding: 8px 14px;
        }

        .dash-date svg {
            opacity: .6;
        }

        /* ── Stat Cards Row ── */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 18px 16px;
            border: 1px solid #e8eef8;
            display: flex;
            flex-direction: column;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(74, 144, 217, .06);
            transition: box-shadow .2s, transform .2s;
        }

        .stat-card:hover {
            box-shadow: 0 6px 20px rgba(74, 144, 217, .14);
            transform: translateY(-2px);
        }

        .stat-top {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon.blue {
            background: #e8f0fd;
            color: #4A90D9;
        }

        .stat-icon.teal {
            background: #e6f7f4;
            color: #2dbe9f;
        }

        .stat-icon.green {
            background: #e8f8ee;
            color: #34c472;
        }

        .stat-icon.orange {
            background: #fff3e6;
            color: #f5a54a;
        }

        .stat-icon.red {
            background: #fdeaea;
            color: #e05a5a;
        }

        .stat-label {
            font-size: .72rem;
            color: #8ca0bf;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .stat-value {
            font-size: 1.9rem;
            font-weight: 800;
            color: #1a2b4a;
            line-height: 1;
        }

        .stat-desc {
            font-size: .72rem;
            color: #a0aec0;
        }

        /* ── Charts Row ── */
        .charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e8eef8;
            padding: 22px 24px;
            box-shadow: 0 2px 8px rgba(74, 144, 217, .06);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 4px;
        }

        .card-title {
            font-size: .95rem;
            font-weight: 700;
            color: #1a2b4a;
        }

        .card-subtitle {
            font-size: .72rem;
            color: #8ca0bf;
            margin-bottom: 16px;
        }

        .period-select {
            font-size: .75rem;
            color: #4A90D9;
            background: #f0f6ff;
            border: 1px solid #d0e3fa;
            border-radius: 8px;
            padding: 5px 10px;
            cursor: pointer;
            font-weight: 600;
        }

        /* ── Bottom Row ── */
        .bottom-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* ── Table ── */
        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .btn-lihat-semua {
            font-size: .75rem;
            color: #4A90D9;
            background: #f0f6ff;
            border: 1px solid #d0e3fa;
            border-radius: 8px;
            padding: 5px 12px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: background .15s;
        }

        .btn-lihat-semua:hover {
            background: #daeaff;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .78rem;
        }

        .orders-table th {
            text-align: left;
            color: #8ca0bf;
            font-weight: 600;
            padding: 0 8px 10px;
            border-bottom: 1px solid #f0f4fb;
            white-space: nowrap;
        }

        .orders-table td {
            padding: 9px 8px;
            color: #2d4060;
            border-bottom: 1px solid #f6f9fd;
        }

        .orders-table tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            font-size: .68rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .badge-diproses {
            background: #fff3e6;
            color: #f5a54a;
        }

        .badge-dikerjakan {
            background: #e8f0fd;
            color: #4A90D9;
        }

        .badge-selesai {
            background: #e8f8ee;
            color: #34c472;
        }

        /* ── Prediksi Info Card ── */
        .prediksi-info {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            background: #f5f8ff;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .prediksi-info-icon {
            width: 46px;
            height: 46px;
            background: #e8f0fd;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4A90D9;
            flex-shrink: 0;
        }

        .prediksi-info-title {
            font-size: .82rem;
            font-weight: 700;
            color: #1a2b4a;
            margin-bottom: 3px;
        }

        .prediksi-info-period {
            font-size: .78rem;
            font-weight: 600;
            color: #4A90D9;
            margin-bottom: 4px;
        }

        .prediksi-info-desc {
            font-size: .72rem;
            color: #8ca0bf;
            line-height: 1.5;
        }

        .btn-detail-prediksi {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #4A90D9;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: background .2s, transform .2s;
            margin-top: auto;
            width: fit-content;
        }

        .btn-detail-prediksi:hover {
            background: #3a7bc8;
            transform: translateX(2px);
        }

        .prediksi-card-inner {
            display: flex;
            flex-direction: column;
            height: 100%;
            min-height: 220px;
        }

        /* ── Activity Logs ── */
        .activity-card {
            margin-top: 24px;
        }

        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .activity-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border-bottom: 1px solid #f0f4fb;
            font-size: .8rem;
            color: #2d4060;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-user {
            font-weight: 700;
            color: #4A90D9;
        }

        .activity-text {
            flex: 1;
            margin-left: 10px;
        }

        .activity-time {
            font-size: .72rem;
            color: #8ca0bf;
            margin-left: 14px;
        }

        .activity-ip {
            font-size: .68rem;
            background: #f0f6ff;
            color: #4A90D9;
            padding: 2px 6px;
            border-radius: 6px;
            font-family: monospace;
            margin-left: 10px;
        }
    </style>
@endpush

{{-- ══════════════════════════════════════════
Page Content
══════════════════════════════════════════ --}}
@section('content')

    {{-- ── Page Header ── --}}
    <div class="dash-header">
        <div>
            <h1 class="dash-title">Dashboard</h1>
            <p class="dash-subtitle">Selamat Datang, {{ Auth::user()->name ?? 'Admin' }}! 👋</p>
        </div>
        <div class="dash-date">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
            {{ \Carbon\Carbon::now()->isoFormat('DD MMM YYYY') }}
        </div>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="stats-row">

        {{-- Total Pelanggan --}}
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon blue">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <span class="stat-label">Total Pelanggan</span>
            </div>
            <div class="stat-value">{{ $totalPelanggan ?? 0 }}</div>
            <div class="stat-desc">Pelanggan terdaftar</div>
        </div>

        {{-- Total Produk --}}
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon teal">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z" />
                    </svg>
                </div>
                <span class="stat-label">Total Produk</span>
            </div>
            <div class="stat-value">{{ $totalProduk ?? 0 }}</div>
            <div class="stat-desc">Produk</div>
        </div>

        {{-- Total Pesanan --}}
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon green">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                        <rect x="9" y="3" width="6" height="4" rx="1" />
                        <line x1="9" y1="12" x2="15" y2="12" />
                        <line x1="9" y1="16" x2="13" y2="16" />
                    </svg>
                </div>
                <span class="stat-label">Total Pesanan</span>
            </div>
            <div class="stat-value">{{ $totalPesanan ?? 0 }}</div>
            <div class="stat-desc">Semua Pesanan</div>
        </div>

        {{-- Pesanan Diproses --}}
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon orange">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
                <span class="stat-label">Pesanan Diproses</span>
            </div>
            <div class="stat-value">{{ $pesananDiproses ?? 0 }}</div>
            <div class="stat-desc">Sedang Dikerjakan</div>
        </div>

        {{-- Pesanan Selesai --}}
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon red">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                </div>
                <span class="stat-label">Pesanan Selesai</span>
            </div>
            <div class="stat-value">{{ $pesananSelesai ?? 0 }}</div>
            <div class="stat-desc">Selesai</div>
        </div>

    </div>

    {{-- ── Charts Row ── --}}
    <div class="charts-row">

        {{-- Grafik Jumlah Pesanan --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Grafik Jumlah Pesanan</span>
                <select class="period-select" id="periodePesanan" onchange="updateChartPesanan(this.value)">
                    <option value="12">12 Bulan Terakhir</option>
                    <option value="6">6 Bulan Terakhir</option>
                    <option value="3">3 Bulan Terakhir</option>
                </select>
            </div>
            <p class="card-subtitle">Jumlah Pesanan</p>
            <canvas id="chartPesanan" height="160"></canvas>
        </div>

        {{-- Prediksi Jumlah Pesanan --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Prediksi Jumlah Pesanan (Periode Berikutnya)</span>
                <select class="period-select" id="periodePrediksi" onchange="updateChartPrediksi(this.value)">
                    <option value="6">6 Bulan Terakhir</option>
                    <option value="12">12 Bulan Terakhir</option>
                </select>
            </div>
            <p class="card-subtitle">Jumlah Prediksi Pesanan</p>
            <canvas id="chartPrediksi" height="160"></canvas>
        </div>

    </div>

    {{-- ── Bottom Row: Tabel + Info Prediksi ── --}}
    <div class="bottom-row">

        {{-- Pesanan Terbaru --}}
        <div class="card">
            <div class="table-header">
                <span class="card-title">Pesanan Terbaru</span>
                @if(Route::has('pesanan.index'))
                    <a href="{{ route('admin.pesanan.index') }}" class="btn-lihat-semua">Lihat Semua</a>
                @endif
            </div>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesananTerbaru ?? [] as $pesanan)
                        <tr>
                            <td>{{ $pesanan->no_pesanan ?? 'PSN-' . str_pad($pesanan->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $pesanan->user->name ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($pesanan->created_at)->isoFormat('DD MMM YYYY') }}</td>
                            <td>Rp {{ number_format($pesanan->total_harga ?? 0, 0, ',', '.') }}</td>
                            <td>
                                @php $s = strtolower($pesanan->status ?? ''); @endphp
                                <span class="badge
                                                @if($s === 'diproses') badge-diproses
                                                @elseif($s === 'dikerjakan') badge-dikerjakan
                                                @else badge-selesai @endif">
                                    {{ ucfirst($pesanan->status ?? 'selesai') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;color:#a0aec0;padding:24px 0;">
                                Belum ada data pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Informasi Prediksi --}}
        <div class="card">
            <div class="prediksi-card-inner">
                <div class="card-header" style="margin-bottom:16px;">
                    <span class="card-title">Informasi Prediksi</span>
                </div>

                <div class="prediksi-info">
                    <div class="prediksi-info-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10" />
                            <line x1="12" y1="20" x2="12" y2="4" />
                            <line x1="6" y1="20" x2="6" y2="14" />
                            <line x1="2" y1="20" x2="22" y2="20" />
                        </svg>
                    </div>
                    <div>
                        <div class="prediksi-info-title">Prediksi Jumlah Pesanan Periode Berikutnya</div>
                        <div class="prediksi-info-period">
                            {{ \Carbon\Carbon::now()->addMonth()->isoFormat('MMMM') }} –
                            {{ \Carbon\Carbon::now()->addMonths(6)->isoFormat('MMMM YYYY') }}
                        </div>
                        <div class="prediksi-info-desc">
                            Prediksi ini dihasilkan berdasarkan historis data pesanan sebelumnya
                        </div>
                    </div>
                </div>

                <div style="flex:1;"></div>

                @if(Route::has('prediksi.index'))
                    <a href="{{ route('admin.prediksi.index') }}" class="btn-detail-prediksi">
                        Lihat Detail Prediksi
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </a>
                @else
                    <a href="#" class="btn-detail-prediksi">
                        Lihat Detail Prediksi
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12" />
                            <polyline points="12 5 19 12 12 19" />
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Activity Log (Audit Trail) ── --}}
    <div class="card activity-card">
                <div class="card-header" style="margin-bottom: 16px;">
                    <span class="card-title" style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" style="color: #8a63d2;">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        Log Aktivitas Sistem Terbaru (Audit Trail)
                    </span>
                </div>
                <ul class="activity-list">
                    @forelse($activityLogs ?? [] as $log)
                        <li class="activity-item">
                            <div style="display: flex; align-items: center; flex: 1;">
                                <span class="activity-user">{{ $log->user->name ?? 'System' }}</span>
                                <span class="activity-text">{{ $log->activity }}</span>
                                @if($log->ip_address)
                                    <span class="activity-ip">{{ $log->ip_address }}</span>
                                @endif
                            </div>
                            <span class="activity-time">{{ $log->created_at->diffForHumans() }}</span>
                        </li>
                    @empty
                        <li style="text-align: center; color: #8ca0bf; padding: 20px; font-size: 0.8rem;">
                            Belum ada aktivitas yang dicatat.
                        </li>
                    @endforelse
                </ul>
            </div>

@endsection

        {{-- ══════════════════════════════════════════
        Scripts — Chart.js
        ══════════════════════════════════════════ --}}
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
            <script>
                // ── Data dari server ─────────────────────────────────────────────────
                const allDataPesanan = @json($chartPesanan ?? []);
                const allDataPrediksi = @json($chartPrediksi ?? []);

                function sliceLast(arr, n) { return arr.slice(-n); }

                // ── Chart Pesanan ────────────────────────────────────────────────────
                const ctxP = document.getElementById('chartPesanan').getContext('2d');
                let chartPesananObj;

                function buildChartPesanan(n) {
                    const slice = sliceLast(allDataPesanan, n);
                    const labels = slice.map(d => d.label);
                    const data = slice.map(d => d.count);

                    const gradient = ctxP.createLinearGradient(0, 0, 0, 200);
                    gradient.addColorStop(0, 'rgba(74,144,217,.25)');
                    gradient.addColorStop(1, 'rgba(74,144,217,0)');

                    return new Chart(ctxP, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [{
                                data,
                                borderColor: '#4A90D9',
                                backgroundColor: gradient,
                                borderWidth: 2.5,
                                pointBackgroundColor: '#4A90D9',
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                tension: 0.35,
                                fill: true,
                            }]
                        },
                        options: {
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#8ca0bf' } },
                                y: { grid: { color: '#f0f4fb' }, ticks: { font: { size: 10 }, color: '#8ca0bf' } }
                            },
                            interaction: { mode: 'index', intersect: false },
                        }
                    });
                }
                chartPesananObj = buildChartPesanan(12);
                function updateChartPesanan(n) {
                    chartPesananObj.destroy();
                    chartPesananObj = buildChartPesanan(parseInt(n));
                }

                // ── Chart Prediksi ───────────────────────────────────────────────────
                const ctxPr = document.getElementById('chartPrediksi').getContext('2d');
                let chartPrediksiObj;

                function buildChartPrediksi(n) {
                    const slice = sliceLast(allDataPrediksi, n);
                    const labels = slice.map(d => d.label);
                    const data = slice.map(d => d.count);

                    const gradient = ctxPr.createLinearGradient(0, 0, 0, 200);
                    gradient.addColorStop(0, 'rgba(138,99,210,.25)');
                    gradient.addColorStop(1, 'rgba(138,99,210,0)');

                    return new Chart(ctxPr, {
                        type: 'line',
                        data: {
                            labels,
                            datasets: [{
                                data,
                                borderColor: '#8a63d2',
                                backgroundColor: gradient,
                                borderWidth: 2.5,
                                pointBackgroundColor: '#8a63d2',
                                pointRadius: 4,
                                pointHoverRadius: 6,
                                tension: 0.35,
                                fill: true,
                            }]
                        },
                        options: {
                            plugins: { legend: { display: false } },
                            scales: {
                                x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#8ca0bf' } },
                                y: { grid: { color: '#f0f4fb' }, ticks: { font: { size: 10 }, color: '#8ca0bf' } }
                            },
                            interaction: { mode: 'index', intersect: false },
                        }
                    });
                }
                chartPrediksiObj = buildChartPrediksi(6);
                function updateChartPrediksi(n) {
                    chartPrediksiObj.destroy();
                    chartPrediksiObj = buildChartPrediksi(parseInt(n));
                }
            </script>
        @endpush