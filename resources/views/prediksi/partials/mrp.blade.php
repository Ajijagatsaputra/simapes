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
