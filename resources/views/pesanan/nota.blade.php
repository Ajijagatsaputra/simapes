<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pesanan - {{ $pesanan->no_pesanan }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 14px;
            line-height: 1.5;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            background: #fff;
            border-radius: 8px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #4A90D9;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-img {
            width: 45px;
            height: 45px;
            object-fit: contain;
        }

        .company-name {
            font-size: 20px;
            font-weight: 800;
            color: #1a2b4a;
            letter-spacing: 0.5px;
        }

        .title {
            font-size: 24px;
            font-weight: 800;
            color: #4A90D9;
            margin: 0;
            text-transform: uppercase;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 28px;
            gap: 20px;
        }

        .info-col {
            flex: 1;
        }

        .info-title {
            font-weight: 700;
            color: #8ca0bf;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .info-val {
            font-size: 14px;
            color: #1a2b4a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            margin-bottom: 30px;
        }

        th {
            background: #f5f8ff;
            color: #8ca0bf;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding: 10px 12px;
            border-bottom: 1px solid #dde8f8;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #f0f4fb;
            color: #2d4060;
        }

        .total-row {
            display: flex;
            justify-content: flex-end;
            font-size: 16px;
            font-weight: 800;
            color: #1a2b4a;
            margin-top: 20px;
        }

        .total-val {
            color: #4A90D9;
            border-bottom: 2px double #4A90D9;
            padding-bottom: 4px;
        }

        .footer {
            text-align: center;
            color: #8ca0bf;
            font-size: 11px;
            margin-top: 50px;
            border-top: 1px solid #eee;
            padding-top: 20px;
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

            .invoice-box {
                border: none;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="header">
            <div class="logo-section">
                <img src="{{ asset('logoauth/logo2.png') }}" class="logo-img" alt="Logo">
                <span class="company-name">SIMAPES KONVEKSI</span>
            </div>
            <h1 class="title">Nota Pesanan</h1>
        </div>

        <div class="info-row">
            <div class="info-col">
                <div class="info-title">No. Pesanan / Invoice</div>
                <div class="info-val" style="font-weight: 700; color: #4A90D9;">{{ $pesanan->no_pesanan }}</div>
                <div style="margin-top: 12px;" class="info-title">Tanggal Pesanan</div>
                <div class="info-val">{{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->isoFormat('DD MMMM YYYY') }}
                </div>
            </div>
            <div class="info-col">
                <div class="info-title">Detail Pelanggan</div>
                <div class="info-val" style="font-weight: 700;">{{ $pesanan->user->name }}</div>
                <div class="info-val">{{ $pesanan->user->nama_sekolah ?? '-' }}</div>
                <div class="info-val">{{ $pesanan->user->no_whatsapp ?? '-' }}</div>
                <div class="info-val" style="font-size: 12px; color: #555; margin-top: 4px;">
                    {{ $pesanan->user->alamat ?? '-' }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Produk</th>
                    <th>Ukuran</th>
                    <th>Harga Satuan</th>
                    <th>Jumlah</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesanan->details as $index => $detail)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="font-weight: 600;">{{ $detail->produk->nama_produk ?? 'Produk Dihapus' }}</td>
                        <td>{{ $detail->ukuran }}</td>
                        <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td>{{ $detail->total_item }} pcs</td>
                        <td style="text-align: right; font-weight: 600;">Rp
                            {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-row">
            <div>
                <span>Total Bayar: &nbsp;&nbsp;&nbsp;</span>
                <span class="total-val">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih telah mempercayakan kebutuhan konveksi Anda pada SIMAPES.</p>
            <p>Harap tunjukkan nota ini saat pengambilan pesanan.</p>
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