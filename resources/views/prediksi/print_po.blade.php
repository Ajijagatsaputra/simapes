<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order — {{ $noPo }}</title>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #1a2b4a;
            margin: 0;
            padding: 40px;
            font-size: 14px;
            line-height: 1.5;
            background-color: #fff;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #1a2b4a;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo-section h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            color: #4A90D9;
        }
        .logo-section p {
            margin: 5px 0 0 0;
            color: #6b7e9f;
            font-size: 12px;
        }
        .title-section {
            text-align: right;
        }
        .title-section h2 {
            margin: 0;
            font-size: 20px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .title-section p {
            margin: 5px 0 0 0;
            font-weight: 600;
            color: #5a7090;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 30px;
        }
        .info-box h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            text-transform: uppercase;
            color: #8ca0bf;
            border-bottom: 1px solid #e2e8f4;
            padding-bottom: 5px;
        }
        .info-box p {
            margin: 4px 0;
            font-weight: 500;
        }
        .info-box strong {
            color: #1a2b4a;
        }
        .po-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .po-table th {
            background-color: #f5f8ff;
            color: #1a2b4a;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 12px;
            padding: 12px;
            border: 1px solid #cedbe9;
            text-align: left;
        }
        .po-table td {
            padding: 12px;
            border: 1px solid #cedbe9;
        }
        .notes {
            margin-top: 20px;
            font-size: 12px;
            color: #6b7e9f;
            border-left: 3px solid #4A90D9;
            padding-left: 15px;
        }
        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #1a2b4a;
            padding-top: 5px;
            font-weight: 700;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
        .btn-container {
            margin-bottom: 20px;
            text-align: right;
        }
        .btn-print {
            background-color: #4A90D9;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-print:hover {
            background-color: #357abd;
        }
    </style>
</head>
<body>

    <div class="btn-container no-print">
        <button onclick="window.print()" class="btn-print">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Cetak Purchase Order
        </button>
    </div>

    <div class="header">
        <div class="logo-section">
            <h1>SIMAPES CONFECTION</h1>
            <p>Sistem Informasi Manajemen Pemesanan & Pengadaan Bahan Baku</p>
        </div>
        <div class="title-section">
            <h2>Purchase Order</h2>
            <p>No: {{ $noPo }}</p>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-box">
            <h3>Dipesan Oleh (Pembeli)</h3>
            <p><strong>SIMAPES Confection</strong></p>
            <p>Bagian Logistik & Bahan Baku</p>
            <p>Kota Bandung, Jawa Barat</p>
            <p>Indonesia</p>
        </div>
        <div class="info-box">
            <h3>Dipesan Kepada (Supplier)</h3>
            <p><strong>{{ $supplier->nama_supplier }}</strong></p>
            <p>Alamat: {{ $supplier->alamat ?? '-' }}</p>
            <p>WhatsApp: {{ $supplier->no_whatsapp ?? '-' }}</p>
        </div>
    </div>

    <div class="info-grid" style="margin-bottom: 20px;">
        <div class="info-box" style="grid-column: span 2;">
            <h3>Detail Pengiriman & Tanggal</h3>
            <p>Tanggal PO: <strong>{{ \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY') }}</strong></p>
            <p>Metode Pembayaran: <strong>Transfer Bank / Tempo COD</strong></p>
        </div>
    </div>

    <table class="po-table">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th>Kategori / Nama Barang</th>
                <th style="text-align: right; width: 20%;">Jumlah</th>
                <th style="width: 15%;">Satuan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td style="font-weight: 600;">{{ $bahan }}</td>
                <td style="text-align: right; font-weight: 700; color: #4A90D9;">{{ number_format($jumlah, 0, ',', '.') }}</td>
                <td style="font-weight: 500;">{{ $satuan }}</td>
            </tr>
        </tbody>
    </table>

    <div class="notes">
        <p><strong>Catatan Penting:</strong></p>
        <ul>
            <li>Mohon konfirmasi ketersediaan stok barang segera setelah menerima Purchase Order ini.</li>
            <li>Barang harus dikirimkan dalam kondisi baik dan sesuai spesifikasi standar konveksi.</li>
            <li>Tagihan/Invoice pembayaran harap dikirimkan bersamaan dengan dokumen pengiriman barang.</li>
        </ul>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <p>Disetujui Oleh,</p>
            <div class="signature-line">
                Owner SIMAPES
            </div>
        </div>
        <div class="signature-box">
            <p>Dipesan Oleh,</p>
            <div class="signature-line">
                Bagian Logistik
            </div>
        </div>
    </div>

    <script>
        // Otomatis cetak saat halaman dibuka
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
