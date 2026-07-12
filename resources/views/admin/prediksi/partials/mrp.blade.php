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
        <span class="mrp-title-badge"
            style="font-size: 0.72rem; color: #8ca0bf; font-weight: 600; background: #e8f0fd; padding: 4px 10px; border-radius: 20px;">
            Est. untuk {{ $totalPrediksiTahunDepan }} Pesanan ({{ $totalPrediksiTahunDepan * 20 }} Pcs)
        </span>
    </div>
    <p style="font-size: .8rem; color: #6b7e9f; line-height: 1.5; margin-bottom: 18px;">
        Berikut adalah kalkulasi kebutuhan bahan baku mentah yang diproyeksikan untuk menyelesaikan total volume
        pesanan ramalan selama 12 bulan ke depan. Estimasi didasarkan pada rata-rata porsi item per pesanan (20
        pcs/order) dan rasio penggunaan bahan standar konveksi.
    </p>

    {{-- ── Panel Simulasi What-If ── --}}
    <div
        style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 15px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div
                style="background: #e0f2fe; color: #0284c7; padding: 8px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="18" y1="20" x2="18" y2="10" />
                    <line x1="12" y1="20" x2="12" y2="4" />
                    <line x1="6" y1="20" x2="6" y2="14" />
                </svg>
            </div>
            <div>
                <strong style="color: #0f172a; font-size: 0.85rem; display: block;">Simulasi Skenario "What-If"
                    Permintaan</strong>
                <span style="color: #64748b; font-size: 0.75rem;">Simulasikan lonjakan/penurunan pasar untuk melihat
                    dampaknya langsung ke rantai pasok.</span>
            </div>
        </div>
        <div>
            <select id="whatIfSelect" onchange="runWhatIfSimulation(this.value)"
                style="padding: 8px 12px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.8rem; font-weight: 600; color: #334155; background: white; cursor: pointer; min-width: 220px; outline: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                <option value="1.0">Normal (Sesuai Prediksi)</option>
                <option value="1.15">Kenaikan Permintaan (+15%)</option>
                <option value="1.30">Lonjakan Permintaan (+30%)</option>
                <option value="1.50">Puncak Musim Baru (+50%)</option>
                <option value="0.85">Penurunan Ringan (-15%)</option>
                <option value="0.70">Krisis Permintaan (-30%)</option>
            </select>
        </div>
    </div>

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
                    <tr data-mrp-key="{{ $key }}" data-base-jumlah="{{ $val['jumlah'] }}"
                        data-lead-time="{{ $val['lead_time'] }}">
                        <td style="font-weight: 700; color: #1a2b4a;">{{ $val['nama'] }}</td>
                        <td class="mrp-jumlah"
                            style="text-align: right; font-weight: 800; color: #4A90D9; font-size: 0.95rem;">
                            {{ number_format($val['jumlah'], 0, ',', '.') }}
                        </td>
                        <td style="font-weight: 600; color: #5a7090;">{{ $val['satuan'] }}</td>
                        <td style="text-align: center; font-weight: 600; color: #8a63d2;">{{ $val['lead_time'] }} Hari</td>
                        <td class="mrp-safety-stock" style="text-align: right; font-weight: 700; color: #34c472;">
                            {{ number_format($val['safety_stock'], 0, ',', '.') }}</td>
                        <td class="mrp-rop" style="text-align: right; font-weight: 700; color: #ef4444;">
                            {{ number_format($val['rop'], 0, ',', '.') }}</td>
                        <td style="color: #8ca0bf; font-size: 0.78rem;">{{ $val['keterangan'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div
        style="margin-top: 18px; background: #fafcff; border: 1px dashed #cedbe9; border-radius: 10px; padding: 12px; font-size: 0.75rem; color: #5a7090; line-height: 1.5;">
        <strong>💡 Catatan Teori & Formula Manajemen Rantai Pasok (SCM):</strong>
        <ul style="margin: 6px 0 0 16px; padding: 0;">
            <li><strong>Safety Stock (Stok Pengaman):</strong> Jumlah stok yang disimpan untuk mengantisipasi
                ketidakpastian permintaan atau keterlambatan pengiriman. Rumus: <code
                    style="font-family: monospace; background: #e8f0fd; padding: 2px 4px; border-radius: 4px;">Safety Stock = 0.5 * (Rata-rata Kebutuhan Harian * Lead Time)</code>.
            </li>
            <li><strong>Reorder Point / ROP (Titik Pemesanan Kembali):</strong> Ambang batas stok bahan baku di gudang
                di mana pemesanan baru harus dilakukan agar bahan baku baru datang sebelum stok aman habis. Rumus: <code
                    style="font-family: monospace; background: #e8f0fd; padding: 2px 4px; border-radius: 4px;">ROP = (Rata-rata Kebutuhan Harian * Lead Time) + Safety Stock</code>.
            </li>
        </ul>
    </div>
</div>