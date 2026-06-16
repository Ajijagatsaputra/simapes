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
                    <th style="text-align: center;">Lead Time (Kirim)</th>
                    <th style="text-align: right;">Safety Stock</th>
                    <th style="text-align: right;">Reorder Point (ROP)</th>
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
                        <td style="text-align: center; font-weight: 600; color: #8a63d2;">{{ $val['lead_time'] }} Hari</td>
                        <td style="text-align: right; font-weight: 700; color: #34c472;">{{ number_format($val['safety_stock'], 0, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: 700; color: #ef4444;">{{ number_format($val['rop'], 0, ',', '.') }}</td>
                        <td style="color: #8ca0bf; font-size: 0.78rem;">{{ $val['keterangan'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 18px; background: #fafcff; border: 1px dashed #cedbe9; border-radius: 10px; padding: 12px; font-size: 0.75rem; color: #5a7090; line-height: 1.5;">
        <strong>💡 Catatan Teori & Formula Manajemen Rantai Pasok (SCM):</strong>
        <ul style="margin: 6px 0 0 16px; padding: 0;">
            <li><strong>Safety Stock (Stok Pengaman):</strong> Jumlah stok yang disimpan untuk mengantisipasi ketidakpastian permintaan atau keterlambatan pengiriman. Rumus: <code style="font-family: monospace; background: #e8f0fd; padding: 2px 4px; border-radius: 4px;">Safety Stock = 0.5 * (Rata-rata Kebutuhan Harian * Lead Time)</code>.</li>
            <li><strong>Reorder Point / ROP (Titik Pemesanan Kembali):</strong> Ambang batas stok bahan baku di gudang di mana pemesanan baru harus dilakukan agar bahan baku baru datang sebelum stok aman habis. Rumus: <code style="font-family: monospace; background: #e8f0fd; padding: 2px 4px; border-radius: 4px;">ROP = (Rata-rata Kebutuhan Harian * Lead Time) + Safety Stock</code>.</li>
        </ul>
    </div>
</div>
