<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        .title {
            font-family: Arial, sans-serif;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }
        .subtitle {
            font-family: Arial, sans-serif;
            font-size: 12px;
            text-align: center;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th {
            border: 1px solid #000;
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            padding: 8px;
        }
        td {
            border: 1px solid #000;
            font-family: Arial, sans-serif;
            font-size: 11px;
            padding: 8px;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .summary-row {
            font-weight: bold;
            background-color: #eaf3fc;
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="10" class="title">LAPORAN TRANSAKSI PEMESANAN KONVEKSI</td>
        </tr>
        <tr>
            <td colspan="10" class="subtitle">SIMAPES KONVEKSI</td>
        </tr>
        <tr>
            <td colspan="10" class="subtitle">
                Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('DD MMMM YYYY') }} s/d {{ \Carbon\Carbon::parse($endDate)->isoFormat('DD MMMM YYYY') }}
            </td>
        </tr>
        <tr>
            <td colspan="10" class="subtitle">
                Filter Sekolah: {{ $sekolahSelected === 'semua' ? 'Semua Sekolah' : $sekolahSelected }} |
                Progress: {{ $progressSelected === 'semua' ? 'Semua Status' : ucfirst(str_replace('_', ' ', $progressSelected)) }} |
                Keuangan: {{ $keuanganSelected === 'semua' ? 'Semua Status Keuangan' : ($keuanganSelected === 'belum_lunas' ? 'Memiliki Sisa Tagihan (Belum Lunas)' : 'Lunas') }}
            </td>
        </tr>
        <tr>
            <td colspan="10"></td>
        </tr>
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th>No. Pesanan</th>
                <th>Nama Pelanggan</th>
                <th>Nama Sekolah / Instansi</th>
                <th style="text-align: center;">Tanggal Pesanan</th>
                <th style="text-align: center;">Status Pesanan</th>
                <th style="text-align: right;">Total Tagihan</th>
                <th style="text-align: right;">Total Terbayar</th>
                <th style="text-align: right;">Sisa Tagihan</th>
                <th style="text-align: center;">Status Keuangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotalHarga = 0;
                $grandTotalTerbayar = 0;
                $grandTotalSisa = 0;
            @endphp
            @forelse($pesanan as $index => $p)
                @php
                    $grandTotalHarga += $p->total_harga;
                    $grandTotalTerbayar += $p->total_terbayar ?? 0;
                    $grandTotalSisa += $p->sisa_tagihan ?? 0;
                    
                    // Determine financial status text
                    $statusKeuangan = 'Belum Bayar';
                    if (($p->total_terbayar ?? 0) > 0) {
                        if (($p->sisa_tagihan ?? 0) <= 0) {
                            $statusKeuangan = 'Lunas';
                        } else {
                            $statusKeuangan = 'DP (Belum Lunas)';
                        }
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td style="font-weight: bold;">{{ $p->no_pesanan }}</td>
                    <td>{{ $p->user->name }}</td>
                    <td>{{ $p->user->nama_sekolah ?? '-' }}</td>
                    <td class="text-center">{{ $p->tanggal_pesanan ? $p->tanggal_pesanan->format('d-m-Y') : '-' }}</td>
                    <td class="text-center">{{ strtoupper($p->status) }}</td>
                    <td class="text-right">{{ $p->total_harga }}</td>
                    <td class="text-right">{{ $p->total_terbayar ?? 0 }}</td>
                    <td class="text-right">{{ $p->sisa_tagihan ?? 0 }}</td>
                    <td class="text-center">{{ $statusKeuangan }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="color: #8ca0bf;">
                        Tidak ada transaksi pesanan yang sesuai dengan filter.
                    </td>
                </tr>
            @endforelse
            @if($pesanan->count() > 0)
                <tr class="summary-row">
                    <td colspan="6" class="text-right">TOTAL</td>
                    <td class="text-right">{{ $grandTotalHarga }}</td>
                    <td class="text-right">{{ $grandTotalTerbayar }}</td>
                    <td class="text-right">{{ $grandTotalSisa }}</td>
                    <td class="text-center"></td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
