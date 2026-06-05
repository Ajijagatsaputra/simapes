@extends('layouts.main')

@section('title', 'Prediksi Pemesanan — SIMAPES')

@push('styles')
    <style>
        /* ── Page Header ── */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1a2b4a;
            line-height: 1.2;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .8rem;
            color: #8ca0bf;
            margin-top: 4px;
        }

        .breadcrumb a {
            color: #8ca0bf;
            text-decoration: none;
            transition: color .15s;
        }

        .breadcrumb a:hover {
            color: #4A90D9;
        }

        .breadcrumb-sep {
            font-size: .7rem;
            opacity: .5;
        }

        .breadcrumb-current {
            color: #4A90D9;
            font-weight: 600;
        }

        .page-date {
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

        /* ── Form Parameter Prediksi ── */
        .form-prediksi {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e8eef8;
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(74, 144, 217, .04);
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            align-items: flex-end;
        }

        @media (max-width: 992px) {
            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-size: .8rem;
            font-weight: 600;
            color: #4a5a7a;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-group input {
            padding: 10px 14px;
            border: 1px solid #cedbe9;
            border-radius: 10px;
            font-size: .85rem;
            color: #1a2b4a;
            font-family: inherit;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }

        .form-group input:focus {
            border-color: #4A90D9;
            box-shadow: 0 0 0 3px rgba(74, 144, 217, 0.15);
        }

        .btn-hitung {
            background: #4A90D9;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: background .2s, transform .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 40px;
        }

        .btn-hitung:hover {
            background: #357abd;
            transform: translateY(-1px);
        }

        .btn-hitung:active {
            transform: translateY(0);
        }

        /* ── Warning Box ── */
        .warning-box {
            background: #fff3e6;
            border-left: 5px solid #f5a54a;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
            box-shadow: 0 4px 12px rgba(245, 165, 74, 0.08);
        }

        .warning-icon {
            color: #f5a54a;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .warning-title {
            font-size: .95rem;
            font-weight: 700;
            color: #7c5212;
            margin-bottom: 4px;
        }

        .warning-desc {
            font-size: .82rem;
            color: #8f6629;
            line-height: 1.55;
        }

        .seeder-hint {
            background: rgba(245, 165, 74, 0.12);
            padding: 10px 14px;
            border-radius: 8px;
            font-family: 'Courier New', Courier, monospace;
            font-size: .78rem;
            display: inline-block;
            margin-top: 12px;
            border: 1px dashed rgba(245, 165, 74, 0.35);
            color: #7c5212;
            font-weight: 600;
        }

        /* ── Stat Cards Grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px 18px;
            border: 1px solid #e8eef8;
            display: flex;
            flex-direction: column;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(74, 144, 217, .04);
            transition: box-shadow .2s, transform .2s;
        }

        .stat-card:hover {
            box-shadow: 0 6px 20px rgba(74, 144, 217, .12);
            transform: translateY(-2px);
        }

        .stat-header {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon.green {
            background: #e8f8ee;
            color: #34c472;
        }

        .stat-icon.purple {
            background: #f3ebfc;
            color: #8a63d2;
        }

        .stat-icon.blue {
            background: #e8f0fd;
            color: #4A90D9;
        }

        .stat-icon.orange {
            background: #fff3e6;
            color: #f5a54a;
        }

        .stat-label {
            font-size: .72rem;
            color: #8ca0bf;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .stat-value {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1a2b4a;
            line-height: 1.1;
        }

        .stat-desc {
            font-size: .72rem;
            color: #a0aec0;
        }

        /* ── Main Layout ── */
        .prediksi-layout {
            display: grid;
            grid-template-columns: 1.6fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
            align-items: start;
        }

        @media (max-width: 1200px) {
            .prediksi-layout {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e8eef8;
            padding: 22px 24px;
            box-shadow: 0 2px 8px rgba(74, 144, 217, .04);
        }

        .card-title-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f4fb;
        }

        .card-title {
            font-size: .95rem;
            font-weight: 700;
            color: #1a2b4a;
        }

        /* ── Table Prediksi ── */
        .pred-table-wrap {
            max-height: 380px;
            overflow-y: auto;
        }

        .pred-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .8rem;
        }

        .pred-table th {
            background: #f5f8ff;
            color: #8ca0bf;
            font-weight: 600;
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            padding: 10px 12px;
            text-align: left;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .pred-table td {
            padding: 11px 12px;
            color: #2d4060;
            border-bottom: 1px solid #f6f9fd;
            vertical-align: middle;
        }

        .pred-table tbody tr:hover td {
            background: #fafcff;
        }

        .badge-status {
            display: inline-block;
            font-size: .65rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
        }

        .badge-status.tinggi {
            background: #fee2e2;
            color: #ef4444;
        }

        .badge-status.sedang {
            background: #fff3e6;
            color: #f5a54a;
        }

        .badge-status.rendah {
            background: #e8f8ee;
            color: #34c472;
        }

        /* ── Teori & Rumus Section ── */
        .theory-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e8eef8;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(74, 144, 217, .04);
            margin-bottom: 24px;
        }

        .formula-box {
            background: #f5f8ff;
            border-left: 4px solid #4A90D9;
            padding: 14px 18px;
            border-radius: 0 12px 12px 0;
            margin: 14px 0;
            font-family: 'Courier New', Courier, monospace;
            font-size: .85rem;
            color: #1a2b4a;
            line-height: 1.5;
            overflow-x: auto;
        }

        .parameter-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-top: 14px;
        }

        @media (max-width: 768px) {
            .parameter-grid {
                grid-template-columns: 1fr;
            }
        }

        .param-card {
            background: #fafcff;
            border: 1px solid #e8eef8;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
        }

        .param-symbol {
            font-size: 1.3rem;
            font-weight: 700;
            color: #4A90D9;
            margin-bottom: 4px;
        }

        .param-value {
            font-size: .85rem;
            font-weight: 600;
            color: #1a2b4a;
            margin-bottom: 2px;
        }

        .param-desc {
            font-size: .7rem;
            color: #8ca0bf;
        }

        .step-list {
            margin-left: 20px;
            font-size: .82rem;
            color: #4a5a7a;
            line-height: 1.6;
        }

        .step-list li {
            margin-bottom: 8px;
        }
    </style>
@endpush

@section('content')

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Prediksi Jumlah Pesanan</h1>
            <nav class="breadcrumb" aria-label="breadcrumb">
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <span class="breadcrumb-sep">›</span>
                <span class="breadcrumb-current">Prediksi</span>
            </nav>
        </div>
        <div class="page-date">
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

    {{-- ── Form Parameter Input ── --}}
    <div class="form-prediksi">
        <form action="{{ route('prediksi.index') }}" method="GET">
            <div class="form-grid">
                <div class="form-group">
                    <label for="alpha">
                        &alpha; (Alpha)
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#8ca0bf; cursor:pointer;" title="Parameter pemulusan untuk Level data aktual. Nilai: 0 - 1.">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                    </label>
                    <input type="number" step="0.0001" min="0" max="1" name="alpha" id="alpha" value="{{ $alpha }}">
                </div>
                <div class="form-group">
                    <label for="beta">
                        &beta; (Beta)
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#8ca0bf; cursor:pointer;" title="Parameter pemulusan untuk Tren. Nilai: 0 - 1.">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                    </label>
                    <input type="number" step="0.0001" min="0" max="1" name="beta" id="beta" value="{{ $beta }}">
                </div>
                <div class="form-group">
                    <label for="gamma">
                        &gamma; (Gamma)
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color:#8ca0bf; cursor:pointer;" title="Parameter pemulusan untuk Seasonal (Pola Musiman). Nilai: 0 - 1.">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                    </label>
                    <input type="number" step="0.0001" min="0" max="1" name="gamma" id="gamma" value="{{ $gamma }}">
                </div>
                <div>
                    <button type="submit" class="btn-hitung">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        Hitung Prediksi
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if(!$hasData)
        {{-- ── Tampilan Peringatan Jika Data Tidak Cukup ── --}}
        <div class="warning-box">
            <div class="warning-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
            <div>
                <h3 class="warning-title">Data Historis Tidak Mencukupi</h3>
                <p class="warning-desc">{{ $message }}</p>
                <div class="warning-desc" style="margin-top: 8px;">
                    Untuk melakukan simulasi data 3 tahun penuh secara otomatis di database, silakan jalankan command artisan seeder di terminal proyek Anda:
                </div>
                <div class="seeder-hint">
                    php artisan db:seed --class=PesananHistorisSeeder
                </div>
            </div>
        </div>
    @else
        {{-- ── Stat Cards Grid (Tampil jika ada data) ── --}}
        <div class="stats-grid">
            {{-- Card 1: MAPE / Akurasi --}}
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon green">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                    </div>
                    <span class="stat-label">Akurasi Prediksi</span>
                </div>
                <div class="stat-value">{{ number_format(100 - $mape, 2) }}%</div>
                <div class="stat-desc">MAPE Error: {{ number_format($mape, 2) }}%</div>
            </div>

            {{-- Card 2: Bulan Puncak --}}
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon purple">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10" />
                            <line x1="12" y1="20" x2="12" y2="4" />
                            <line x1="6" y1="20" x2="6" y2="14" />
                            <line x1="2" y1="20" x2="22" y2="20" />
                        </svg>
                </div>
                <span class="stat-label">Bulan Puncak Prediksi</span>
            </div>
            <div class="stat-value" style="font-size: 1.35rem; font-weight: 800; padding: 2px 0;">{{ $puncakPrediksi }}</div>
            <div class="stat-desc">Est. Permintaan Tertinggi</div>
        </div>

        {{-- Card 3: Total Prediksi Pesanan --}}
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon blue">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg>
                </div>
                <span class="stat-label">Total Est. Pesanan</span>
            </div>
            <div class="stat-value">{{ $totalPrediksiTahunDepan }}</div>
            <div class="stat-desc">12 Bulan ke Depan</div>
        </div>

        {{-- Card 4: Rata-Rata Bulanan --}}
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon orange">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
                <span class="stat-label">Rata-Rata Bulanan</span>
            </div>
            <div class="stat-value">{{ $rataRataPesananPrediksi }} / bln</div>
            <div class="stat-desc">Berdasarkan hasil proyeksi</div>
        </div>
    </div>

    {{-- ── Main Layout: Chart + Tabel ── --}}
    <div class="prediksi-layout">
        {{-- Chart Visualisasi --}}
        <div class="card">
            <div class="card-title-wrap">
                <span class="card-title">Grafik Proyeksi Permintaan Pesanan (Historis vs Prediksi)</span>
            </div>
            <div style="position: relative; height: 350px;">
                <canvas id="chartPrediksiTahunan"></canvas>
            </div>
        </div>

        {{-- Tabel Data Prediksi --}}
        <div class="card">
            <div class="card-title-wrap">
                <span class="card-title">Tabel Hasil Prediksi (12 Bulan Ke Depan)</span>
            </div>
            <div class="pred-table-wrap">
                <table class="pred-table">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th style="text-align: right;">Jumlah Prediksi</th>
                            <th style="text-align: center;">Tingkat Volume</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($prediksi as $p)
                            <tr>
                                <td style="font-weight: 600;">{{ $p['label'] }}</td>
                                <td style="text-align: right; font-weight: 700; color: #1a2b4a;">
                                    {{ $p['count'] }} Pesanan
                                </td>
                                <td style="text-align: center;">
                                    @if($p['count'] >= 18)
                                        <span class="badge-status tinggi">Volume Tinggi</span>
                                    @elseif($p['count'] >= 8)
                                        <span class="badge-status sedang">Volume Sedang</span>
                                    @else
                                        <span class="badge-status rendah">Volume Rendah</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ── Teori & Metode Perhitungan (Kebutuhan Sidang TA) ── --}}
    <div class="theory-card">
        <h3 class="card-title" style="margin-bottom: 14px; font-size: 1.05rem;">
            Metodologi Prediksi: Holt-Winters Triple Exponential Smoothing (Multiplicative)
        </h3>
        
        <p style="font-size: .83rem; color: #4a5a7a; line-height: 1.6; margin-bottom: 12px;">
            Aplikasi ini menggunakan metode <strong>Holt-Winters (Triple Exponential Smoothing)</strong> tipe Multiplikatif. Metode ini sangat ideal untuk memproyeksikan data pesanan konveksi jasa pembuatan seragam karena mampu secara simultan menangani tiga komponen utama time-series: <strong>Level</strong> (Nilai Rata-rata), <strong>Trend</strong> (Peningkatan/Penurunan secara jangka panjang), dan <strong>Seasonality</strong> (Musiman bulanan seperti tahun ajaran baru sekolah).
        </p>

        <h4 style="font-size: .83rem; font-weight: 700; color: #1a2b4a; margin-top: 18px; margin-bottom: 6px;">
            Parameter Penghalusan (Smoothing Parameters) yang Digunakan:
        </h4>
        <div class="parameter-grid">
            <div class="param-card">
                <div class="param-symbol">&alpha; (Alpha)</div>
                <div class="param-value">{{ $parameters['alpha'] }}</div>
                <div class="param-desc">Faktor penghalus untuk Level dasar data aktual</div>
            </div>
            <div class="param-card">
                <div class="param-symbol">&beta; (Beta)</div>
                <div class="param-value">{{ $parameters['beta'] }}</div>
                <div class="param-desc">Faktor penghalus untuk memperbarui Tren pertumbuhan</div>
            </div>
            <div class="param-card">
                <div class="param-symbol">&gamma; (Gamma)</div>
                <div class="param-value">{{ $parameters['gamma'] }}</div>
                <div class="param-desc">Faktor penghalus indeks Musiman (Seasonal) bulanan</div>
            </div>
        </div>

        <h4 style="font-size: .83rem; font-weight: 700; color: #1a2b4a; margin-top: 20px; margin-bottom: 8px;">
            Persamaan Matematika Holt-Winters Multiplikatif:
        </h4>
        <div class="formula-box">
            1. Persamaan Level (L_t)     : L_t = &alpha; * (Y_t / S_{t-p}) + (1 - &alpha;) * (L_{t-1} + T_{t-1})<br>
            2. Persamaan Tren (T_t)      : T_t = &beta; * (L_t - L_{t-1}) + (1 - &beta;) * T_{t-1}<br>
            3. Persamaan Musiman (S_t)   : S_t = &gamma; * (Y_t / L_t) + (1 - &gamma;) * S_{t-p}<br>
            4. Rumus Prediksi (F_{t+m})  : F_{t+m} = (L_t + m * T_t) * S_{t-p+m}
        </div>

        <h4 style="font-size: .83rem; font-weight: 700; color: #1a2b4a; margin-top: 18px; margin-bottom: 8px;">
            Langkah-Langkah Perhitungan Sistem:
        </h4>
        <ul class="step-list">
            <li>
                <strong>Inisialisasi Data:</strong> Menghitung nilai dasar rata-rata (*Level*) awal, nilai peningkatan awal (*Trend*), serta menghitung 12 indeks musiman awal (*Seasonal Indices*) berdasarkan data 2 tahun pertama.
            </li>
            <li>
                <strong>Iterasi Evaluasi:</strong> Melakukan perulangan (*looping*) baris demi baris data dari bulan pertama hingga bulan terakhir (bulan ke-{{ count($historis) }}) untuk memperbarui nilai parameter $L_t$, $T_t$, dan $S_t$ agar menyesuaikan fluktuasi riil.
            </li>
            <li>
                <strong>Proyeksi Prediksi:</strong> Menggunakan nilai akhir Level dan Trend terakhir dikalikan dengan bobot musiman bulan yang bersangkutan untuk meramalkan volume pesanan di 12 bulan ke depan secara presisi.
            </li>
        </ul>
    </div>
    @endif

@endsection

@push('scripts')
    @if($hasData)
    {{-- Mengimpor Chart.js dari CDN (Jika belum dimuat di layout utama) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Data Historis dari Controller
            const dataHistoris = @json($historis);
            // Data Prediksi dari Controller
            const dataPrediksi = @json($prediksi);

            // Olah label dan data point untuk Chart
            const labelsHistoris = dataHistoris.map(item => item.label);
            const countsHistoris = dataHistoris.map(item => item.count);

            const labelsPrediksi = dataPrediksi.map(item => item.label);
            const countsPrediksi = dataPrediksi.map(item => item.count);

            // Gabungkan label (historis + 12 bulan prediksi)
            const allLabels = [...labelsHistoris, ...labelsPrediksi];

            // Setup data point historis (data riil, null di akhir agar garis terputus)
            const datasetHistoris = [...countsHistoris, ...Array(labelsPrediksi.length).fill(null)];

            // Setup data point prediksi (null di awal + 1 titik pertemuan di index akhir historis agar garis menyambung + data prediksi)
            const titikPertemuan = countsHistoris[countsHistoris.length - 1];
            const datasetPrediksi = [...Array(countsHistoris.length - 1).fill(null), titikPertemuan, ...countsPrediksi];

            const ctx = document.getElementById('chartPrediksiTahunan').getContext('2d');

            // Setup gradient warna latar bawah kurva
            const gradientBlue = ctx.createLinearGradient(0, 0, 0, 300);
            gradientBlue.addColorStop(0, 'rgba(74, 144, 217, 0.2)');
            gradientBlue.addColorStop(1, 'rgba(74, 144, 217, 0)');

            const gradientPurple = ctx.createLinearGradient(0, 0, 0, 300);
            gradientPurple.addColorStop(0, 'rgba(138, 99, 210, 0.2)');
            gradientPurple.addColorStop(1, 'rgba(138, 99, 210, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: allLabels,
                    datasets: [
                        {
                            label: 'Jumlah Pesanan Aktual (Historis)',
                            data: datasetHistoris,
                            borderColor: '#4A90D9',
                            backgroundColor: gradientBlue,
                            borderWidth: 3,
                            pointBackgroundColor: '#4A90D9',
                            pointRadius: function(context) {
                                return context.dataIndex % 3 === 0 ? 3 : 0;
                            },
                            fill: true,
                            tension: 0.3
                        },
                        {
                            label: 'Proyeksi Prediksi (Holt-Winters)',
                            data: datasetPrediksi,
                            borderColor: '#8a63d2',
                            backgroundColor: gradientPurple,
                            borderWidth: 3,
                            borderDash: [5, 5], // Membuat garis putus-putus khusus untuk prediksi
                            pointBackgroundColor: '#8a63d2',
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: {
                                    family: 'Inter',
                                    size: 11,
                                    weight: '500'
                                },
                                color: '#1a2b4a'
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            bodyFont: {
                                family: 'Inter'
                            },
                            titleFont: {
                                family: 'Inter',
                                weight: 'bold'
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    family: 'Inter',
                                    size: 9
                                },
                                color: '#8ca0bf',
                                maxRotation: 45,
                                minRotation: 45,
                                autoSkip: true,
                                maxTicksLimit: 20
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f0f4fb'
                            },
                            ticks: {
                                font: {
                                    family: 'Inter',
                                    size: 10
                                },
                                color: '#8ca0bf'
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    }
                }
            });
        });
    </script>
    @endif
@endpush
