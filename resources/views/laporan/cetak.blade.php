<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Pemesanan Konveksi</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 30px;
            font-size: 13px;
            line-height: 1.5;
        }

        .invoice-box {
            max-width: 900px;
            margin: auto;
        }

        .header {
            text-align: center;
            border-bottom: 3px double #1a2b4a;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .company-name {
            font-size: 24px;
            font-weight: 800;
            color: #1a2b4a;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .company-sub {
            font-size: 11px;
            color: #555;
            margin-top: 5px;
        }

        .title {
            font-size: 16px;
            font-weight: 700;
            color: #1a2b4a;
            margin: 15px 0 5px;
            text-transform: uppercase;
        }

        .period {
            font-size: 12px;
            color: #555;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            margin-bottom: 30px;
        }

        th {
            background: #f2f5fa;
            color: #1a2b4a;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            padding: 10px 8px;
            border: 1px solid #ddd;
        }

        td {
            padding: 10px 8px;
            border: 1px solid #ddd;
            color: #2d4060;
        }

        .summary-box {
            display: flex;
            justify-content: flex-end;
            gap: 40px;
            font-size: 14px;
            font-weight: 700;
            color: #1a2b4a;
            margin-top: 10px;
            border: 1.5px solid #1a2b4a;
            padding: 15px 20px;
            background: #fbfdff;
        }

        .footer {
            text-align: right;
            margin-top: 60px;
            font-size: 12px;
        }

        .btn-print-floating {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #4A90D9;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(74, 144, 217, 0.3);
            transition: transform 0.2s;
        }

        .btn-print-floating:hover {
            transform: scale(1.1);
        }

        @media print {
            .btn-print-floating {
                display: none;
            }

            body {
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="header">
            <span class="company-name">SIMAPES KONVEKSI</span>
            <div class="company-sub">Sistem Informasi Manajemen Pemesanan Konveksi & Prediksi</div>
            <div class="company-sub">Alamat: Kantor SIMAPES Pusat, Telp: 08123456789</div>
        </div>

        <center>
            <h1 class="title">Laporan Transaksi Pemesanan</h1>
            <div class="period">
                Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('DD MMMM YYYY') }} s/d
                {{ \Carbon\Carbon::parse($endDate)->isoFormat('DD MMMM YYYY') }}
                &nbsp;|&nbsp; Status: {{ ucfirst($status) }}
            </div>
        </center>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%; text-align: center;">No</th>
                    <th>No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Nama Sekolah / Instansi</th>
                    <th>Tanggal Pesanan</th>
                    <th style="text-align: center;">Status</th>
                    <th style="text-align: right;">Total Harga</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanan as $index => $p)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="font-weight: 700;">{{ $p->no_pesanan }}</td>
                        <td style="font-weight: 600;">{{ $p->user->name }}</td>
                        <td>{{ $p->user->nama_sekolah ?? '-' }}</td>
                        <td>{{ $p->tanggal_pesanan ? $p->tanggal_pesanan->isoFormat('DD MMMM YYYY') : '-' }}</td>
                        <td style="text-align: center; font-weight: 600;">{{ strtoupper($p->status) }}</td>
                        <td style="text-align: right; font-weight: 700;">Rp
                            {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #8ca0bf; padding: 20px;">
                            Tidak ada transaksi pesanan dalam periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="summary-box">
            <div>Total Transaksi: <span style="color: #4A90D9; font-size: 16px;">{{ $pesanan->count() }}</span></div>
            <div>Total Pendapatan: <span style="color: #34c472; font-size: 16px;">Rp
                    {{ number_format($totalPendapatan, 0, ',', '.') }}</span></div>
        </div>

        <div class="footer">
            <p>Bandung, {{ \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY') }}</p>
            <br><br><br>
            <p style="font-weight: 700; text-decoration: underline;">Admin SIMAPES</p>
        </div>
    </div>

    <button class="btn-print-floating" onclick="window.print()" title="Cetak Nota">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 6 2 18 2 18 9" />
            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
            <rect x="6" y="14" width="12" height="8" />
        </svg>
    </button>

    <script>
        window.onload = function () {
            window.print();
        }
    </script>
</body>

</html>