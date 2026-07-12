<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Analisis Prediksi SIMAPES</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm 1.5cm 1.5cm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2b3a4a;
            font-size: 10pt;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        .header {
            border-bottom: 2px solid #4A90D9;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-title {
            font-size: 20pt;
            font-weight: bold;
            color: #1a2b4a;
            margin: 0;
        }

        .logo-subtitle {
            font-size: 9pt;
            color: #6b7e9f;
            margin: 2px 0 0 0;
        }

        .doc-info {
            text-align: right;
            font-size: 8.5pt;
            color: #6b7e9f;
        }

        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #1a2b4a;
            border-bottom: 1px solid #e1e9f5;
            padding-bottom: 4px;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .parameter-table,
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .parameter-table th,
        .parameter-table td,
        .data-table th,
        .data-table td {
            border: 1px solid #e2e8f0;
            padding: 6px 8px;
            text-align: left;
            font-size: 9pt;
        }

        .data-table th {
            background-color: #f7fafc;
            color: #1a2b4a;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8pt;
            font-weight: bold;
            border-radius: 4px;
            background-color: #ebf8ff;
            color: #2b6cb0;
        }

        .badge-success {
            background-color: #f0fff4;
            color: #38a169;
        }

        .ai-analysis-container {
            background-color: #f7fafc;
            border-left: 4px solid #8a63d2;
            padding: 12px 15px;
            margin-top: 10px;
            border-radius: 4px;
        }

        /* Style markdown tags converted to HTML */
        .ai-analysis-container h3 {
            font-size: 11pt;
            color: #8a63d2;
            margin-top: 12px;
            margin-bottom: 6px;
            border-bottom: 1px dashed #e2e8f0;
            padding-bottom: 3px;
        }

        .ai-analysis-container h4 {
            font-size: 10pt;
            color: #2b3a4a;
            margin-top: 10px;
            margin-bottom: 4px;
        }

        .ai-analysis-container p {
            margin: 0 0 8px 0;
            font-size: 9pt;
            text-align: justify;
        }

        .ai-analysis-container ul {
            margin: 0 0 8px 0;
            padding-left: 20px;
        }

        .ai-analysis-container li {
            margin-bottom: 3px;
            font-size: 9pt;
        }

        .footer {
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 20px;
            text-align: center;
            font-size: 8pt;
            color: #a0aec0;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
        }

        .page-break {
            page-break-after: always;
        }

        .pagenum:before {
            content: counter(page);
        }
    </style>
</head>

<body>
    <div class="footer">
        SIMAPES (Sistem Informasi Manajemen Pemesanan & Prediksi Konveksi Seragam) — Halaman <span
            class="pagenum"></span>
    </div>

    {{-- HEADER --}}
    <div class="header">
        <table>
            <tr>
                <td>
                    <h1 class="logo-title">SIMAPES</h1>
                    <p class="logo-subtitle">Sistem Informasi Manajemen Pemesanan & Prediksi Konveksi Seragam</p>
                </td>
                <td class="doc-info">
                    <strong>LAPORAN ANALISIS PREDIKSI & MRP</strong><br>
                    Tanggal Cetak: {{ \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY') }}<br>
                    Pembuat: Admin SIMAPES
                </td>
            </tr>
        </table>
    </div>

    {{-- METODE & PARAMETER --}}
    <div class="section-title">1. Parameter Model & Evaluasi Peramalan</div>
    <table class="parameter-table">
        <tr>
            <th style="width: 25%; background: #f7fafc;">Metode Peramalan</th>
            <td style="width: 25%;">Holt-Winters (Triple Exponential Smoothing)</td>
            <th style="width: 25%; background: #f7fafc;">Mean Absolute Percentage Error (MAPE)</th>
            <td
                style="width: 25%; font-weight: bold; color: {{ $mape <= 10 ? '#38a169' : ($mape <= 20 ? '#d69e2e' : '#e53e3e') }}">
                {{ number_format($mape, 2) }}%
                <span class="badge {{ $mape <= 10 ? 'badge-success' : '' }}">
                    ({{ $mape <= 10 ? 'Sangat Akurat' : ($mape <= 20 ? 'Baik' : 'Kurang Akurat') }})
                </span>
            </td>
        </tr>
        <tr>
            <th style="background: #f7fafc;">Parameter α (Alpha)</th>
            <td>{{ $alpha }}</td>
            <th style="background: #f7fafc;">Mean Absolute Deviation (MAD)</th>
            <td>{{ number_format($mad, 2) }} pesanan</td>
        </tr>
        <tr>
            <th style="background: #f7fafc;">Parameter β (Beta)</th>
            <td>{{ $beta }}</td>
            <th style="background: #f7fafc;">Dataset Historis</th>
            <td>
                {{ count($historis) }} Bulan
                @if(session()->has('uploaded_prediction_filename'))
                    <span style="font-size: 8pt; color: #718096;">({{ session('uploaded_prediction_filename') }})</span>
                @else
                    <span style="font-size: 8pt; color: #718096;">(Database Sistem)</span>
                @endif
            </td>
        </tr>
        <tr>
            <th style="background: #f7fafc;">Parameter γ (Gamma)</th>
            <td>{{ $gamma }}</td>
            <th style="background: #f7fafc;">Periode Proyeksi</th>
            <td>12 Bulan Ke Depan</td>
        </tr>
    </table>

    {{-- DATA PROYEKSI --}}
    <div class="section-title">2. Proyeksi Pemesanan 12 Bulan Ke Depan</div>
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 10%;">No.</th>
                <th>Bulan Proyeksi</th>
                <th class="text-right" style="width: 40%;">Prediksi Jumlah Pesanan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prediksi as $idx => $p)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $p['label'] }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ $p['count'] }} Pesanan</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    {{-- DETAIL KEBUTUHAN BAHAN (MRP) --}}
    <div class="section-title">3. Rencana Kebutuhan Bahan Baku (MRP)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Nama Bahan Baku</th>
                <th class="text-right">Total Kebutuhan (1 Thn)</th>
                <th class="text-center">Lead Time</th>
                <th class="text-right">Safety Stock</th>
                <th class="text-right">Reorder Point (ROP)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mrp as $key => $val)
                <tr>
                    <td style="font-weight: bold;">{{ $val['nama'] }}</td>
                    <td class="text-right">{{ number_format($val['jumlah']) }} {{ $val['satuan'] }}</td>
                    <td class="text-center">{{ $val['lead_time'] }} Hari</td>
                    <td class="text-right">{{ number_format($val['safety_stock']) }} {{ $val['satuan'] }}</td>
                    <td class="text-right" style="font-weight: bold; color: #2b6cb0;">{{ number_format($val['rop']) }}
                        {{ $val['satuan'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p style="font-size: 8pt; color: #718096; margin-top: -5px; margin-bottom: 20px;">
        *Catatan: <strong>Reorder Point (ROP)</strong> adalah titik batas di mana stok bahan baku harus segera dipesan
        ulang ke supplier agar tidak terjadi stockout saat proses produksi berjalan.
    </p>

    {{-- ANALISIS AI --}}
    <div class="section-title">4. Analisis Strategis &amp; Rekomendasi AI</div>
    <div class="ai-analysis-container">
        {!! $analysisHtml !!}
    </div>

</body>

</html>