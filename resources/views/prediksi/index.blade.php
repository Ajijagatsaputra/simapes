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

        .preset-select {
            padding: 10px 14px;
            border: 1px solid #cedbe9;
            border-radius: 10px;
            font-size: .85rem;
            color: #1a2b4a;
            font-family: inherit;
            outline: none;
            background: #fafdff;
            cursor: pointer;
            transition: border-color .15s;
            width: 100%;
        }

        .preset-select:focus {
            border-color: #4A90D9;
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
        <form action="{{ route('prediksi.index') }}" method="GET" id="formPrediksi">
            <div style="display: flex; flex-direction: column; gap: 16px;">
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 16px; align-items: flex-end;">
                    <div class="form-group">
                        <label for="preset-select" style="font-weight: 700; color: #1a2b4a;">
                            Mode Sensitivitas Prediksi
                        </label>
                        <select id="preset-select" class="preset-select">
                            <option value="otomatis">Rekomendasi Sistem (Stabil & Optimal)</option>
                            <option value="tren">Sensitif terhadap Tren Penjualan Terbaru (Responsif)</option>
                            <option value="musiman">Fokus pada Siklus Musiman Tahunan (Tahun Ajaran Baru)</option>
                            <option value="kustom">Kustom Parameter Lanjutan (Keperluan Uji Sidang / Uji Akurasi)</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn-hitung" style="width: 100%;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            Hitung Prediksi
                        </button>
                    </div>
                </div>

                {{-- Row Parameter Lanjutan (hanya muncul saat Kustom dipilih) ── --}}
                <div id="custom-params-wrapper" class="form-grid"
                    style="display: none; border-top: 1px dashed #e2e8f4; padding-top: 16px;">
                    <div class="form-group">
                        <label for="alpha">
                            &alpha; (Alpha) - Pemulus Level
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" style="color:#8ca0bf; cursor:pointer;"
                                title="Bobot pemulusan untuk Level dasar penjualan. Nilai: 0 - 1.">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                        </label>
                        <input type="number" step="0.0001" min="0" max="1" name="alpha" id="alpha" value="{{ $alpha }}">
                    </div>
                    <div class="form-group">
                        <label for="beta">
                            &beta; (Beta) - Pemulus Tren
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" style="color:#8ca0bf; cursor:pointer;"
                                title="Bobot pemulusan untuk perubahan tren kenaikan/penurunan. Nilai: 0 - 1.">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                        </label>
                        <input type="number" step="0.0001" min="0" max="1" name="beta" id="beta" value="{{ $beta }}">
                    </div>
                    <div class="form-group">
                        <label for="gamma">
                            &gamma; (Gamma) - Pemulus Musiman
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" style="color:#8ca0bf; cursor:pointer;"
                                title="Bobot pemulusan untuk pola musiman tahunan. Nilai: 0 - 1.">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="16" x2="12" y2="12" />
                                <line x1="12" y1="8" x2="12.01" y2="8" />
                            </svg>
                        </label>
                        <input type="number" step="0.0001" min="0" max="1" name="gamma" id="gamma" value="{{ $gamma }}">
                    </div>
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
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                    <line x1="12" y1="9" x2="12" y2="13" />
                    <line x1="12" y1="17" x2="12.01" y2="17" />
                </svg>
            </div>
            <div>
                <h3 class="warning-title">Data Historis Tidak Mencukupi</h3>
                <p class="warning-desc">{{ $message }}</p>
                <div class="warning-desc" style="margin-top: 8px;">
                    Untuk melakukan simulasi data 3 tahun penuh secara otomatis di database, silakan jalankan command artisan
                    seeder di terminal proyek Anda:
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
                <div class="stat-value" style="font-size: 1.35rem; font-weight: 800; padding: 2px 0;">{{ $puncakPrediksi }}
                </div>
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

            {{-- ── Material Requirement Planning (MRP) ── --}}
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-title-wrap">
                    <span class="card-title" style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" style="color: #4A90D9;">
                            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" />
                            <line x1="7" y1="7" x2="7.01" y2="7" />
                        </svg>
                        Perencanaan Kebutuhan Bahan Baku (Material Requirement Planning - MRP)
                    </span>
                    <span
                        style="font-size: 0.72rem; color: #8ca0bf; font-weight: 600; background: #e8f0fd; padding: 4px 10px; border-radius: 20px;">
                        Est. untuk {{ $totalPrediksiTahunDepan }} Pesanan ({{ $totalPrediksiTahunDepan * 20 }} Pcs)
                    </span>
                </div>
                <p style="font-size: .8rem; color: #6b7e9f; line-height: 1.5; margin-bottom: 18px;">
                    Berikut adalah kalkulasi kebutuhan bahan baku mentah yang diproyeksikan untuk menyelesaikan total volume
                    pesanan ramalan selama 12 bulan ke depan. Estimasi didasarkan pada rata-rata porsi item per pesanan (20
                    pcs/order) dan rasio penggunaan bahan standar konveksi.
                </p>

                <div class="pred-table-wrap" style="max-height: none; overflow: visible;">
                    <table class="pred-table">
                        <thead>
                            <tr>
                                <th>Nama Bahan Baku</th>
                                <th style="text-align: right;">Total Kebutuhan</th>
                                <th>Satuan</th>
                                <th>Keterangan / Alokasi Penggunaan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mrp as $key => $val)
                                <tr>
                                    <td style="font-weight: 700; color: #1a2b4a;">{{ $val['nama'] }}</td>
                                    <td style="text-align: right; font-weight: 800; color: #4A90D9; font-size: 0.95rem;">
                                        {{ number_format($val['jumlah'], 0, ',', '.') }}
                                    </td>
                                    <td style="font-weight: 600; color: #5a7090;">{{ $val['satuan'] }}</td>
                                    <td style="color: #8ca0bf; font-size: 0.78rem;">{{ $val['keterangan'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        {{-- ── Rekomendasi Supplier ── --}}
        <div class="card" style="margin-bottom: 24px;">
            <div class="card-title-wrap">
                <span class="card-title" style="display: flex; align-items: center; gap: 8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" style="color: #8a63d2;">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                        <line x1="9" y1="3" x2="9" y2="21" />
                    </svg>
                    Rekomendasi Supplier Pengadaan Bahan Baku
                </span>
            </div>
            <p style="font-size: .8rem; color: #6b7e9f; line-height: 1.5; margin-bottom: 18px;">
                Berikut adalah supplier terdaftar yang direkomendasikan berdasarkan kecocokan kategori bahan baku hasil
                kalkulasi MRP di atas.
            </p>

            <div style="display: flex; flex-direction: column; gap: 16px;">
                @foreach($mrp as $key => $val)
                    <div style="background: #fafcff; border: 1px solid #e8eef8; border-radius: 12px; padding: 16px;">
                        <div
                            style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px dashed #e2e8f4; padding-bottom: 10px; margin-bottom: 12px;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span class="badge-status sedang"
                                    style="text-transform: uppercase; font-size: 0.65rem;">{{ $val['nama'] }}</span>
                                <span style="font-size: 0.8rem; font-weight: 700; color: #1a2b4a;">Dibutuhkan:
                                    {{ number_format($val['jumlah'], 0, ',', '.') }} {{ $val['satuan'] }}</span>
                            </div>
                        </div>

                        @if($rekomendasiSupplier[$key]->isEmpty())
                            <div style="font-size: 0.8rem; color: #a0aec0; padding: 6px 0;">
                                Belum ada supplier terdaftar untuk kategori ini.
                            </div>
                        @else
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 12px;">
                                @foreach($rekomendasiSupplier[$key] as $sup)
                                    <div
                                        style="background: #fff; border: 1px solid #e8eef8; border-radius: 10px; padding: 12px; display: flex; flex-direction: column; justify-content: space-between; gap: 10px;">
                                        <div>
                                            <div style="font-size: 0.82rem; font-weight: 700; color: #1a2b4a;">{{ $sup->nama_supplier }}
                                            </div>
                                            <div
                                                style="font-size: 0.72rem; color: #8ca0bf; margin-top: 4px; display: flex; align-items: flex-start; gap: 4px;">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" style="flex-shrink: 0; margin-top: 2px;">
                                                    <path d="M12 2a8 8 0 0 0-8 8c0 5.25 8 12 8 12s8-6.75 8-12a8 8 0 0 0-8-8z" />
                                                    <circle cx="12" cy="10" r="3" />
                                                </svg>
                                                {{ $sup->alamat ?? '-' }}
                                            </div>
                                            <div style="font-size: 0.74rem; color: #5a7090; margin-top: 6px; line-height: 1.4;">
                                                {{ $sup->deskripsi ?? '-' }}
                                            </div>
                                        </div>

                                        @if($sup->no_whatsapp)
                                            <div>
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $sup->no_whatsapp) }}?text=Halo%20{{ urlencode($sup->nama_supplier) }},%20kami%20tertarik%20untuk%20memesan%20bahan%20baku%20{{ urlencode(strtolower($val['nama'])) }}%20sebanyak%20{{ $val['jumlah'] }}%20{{ $val['satuan'] }}."
                                                    target="_blank"
                                                    style="display: inline-flex; align-items: center; gap: 6px; background: #e8f8ee; color: #2e7d32; border: 1px solid #a5d6a7; padding: 6px 12px; border-radius: 8px; font-size: 0.72rem; font-weight: 700; text-decoration: none; transition: background 0.2s;">
                                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2.5">
                                                        <path
                                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                                    </svg>
                                                    Hubungi WhatsApp
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    @if($hasData)
        {{-- Mengimpor Chart.js dari CDN (Jika belum dimuat di layout utama) --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
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
                                pointRadius: function (context) {
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const presetSelect = document.getElementById('preset-select');
            const customWrapper = document.getElementById('custom-params-wrapper');
            const alphaInput = document.getElementById('alpha');
            const betaInput = document.getElementById('beta');
            const gammaInput = document.getElementById('gamma');

            const presets = {
                otomatis: { alpha: 0.2, beta: 0.1, gamma: 0.3 },
                tren: { alpha: 0.5, beta: 0.4, gamma: 0.3 },
                musiman: { alpha: 0.2, beta: 0.1, gamma: 0.6 }
            };

            function updateParams() {
                const val = presetSelect.value;
                if (val === 'kustom') {
                    customWrapper.style.display = 'grid';
                } else {
                    customWrapper.style.display = 'none';
                    if (presets[val]) {
                        alphaInput.value = presets[val].alpha;
                        betaInput.value = presets[val].beta;
                        gammaInput.value = presets[val].gamma;
                    }
                }
            }

            // Tentukan state awal select berdasarkan input saat ini
            const currentAlpha = parseFloat(alphaInput.value);
            const currentBeta = parseFloat(betaInput.value);
            const currentGamma = parseFloat(gammaInput.value);

            let matchedPreset = 'kustom';
            for (const [key, p] of Object.entries(presets)) {
                if (Math.abs(p.alpha - currentAlpha) < 0.0001 &&
                    Math.abs(p.beta - currentBeta) < 0.0001 &&
                    Math.abs(p.gamma - currentGamma) < 0.0001) {
                    matchedPreset = key;
                    break;
                }
            }

            presetSelect.value = matchedPreset;
            updateParams();

            presetSelect.addEventListener('change', updateParams);
        });
    </script>
@endpush