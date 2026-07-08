@extends('layouts.pelanggan')
@section('title', 'Detail Pesanan ' . $pesanan->no_pesanan . ' - SIMAPES')

@push('styles')
    <style>
        .detail-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f4;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(26, 43, 74, .03);
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #5a7090;
            text-decoration: none;
            font-size: .85rem;
            font-weight: 600;
            transition: color .15s;
        }

        .btn-back:hover {
            color: #4A90D9;
        }

        .detail-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 16px;
            border-bottom: 1px dashed #e2e8f4;
            padding-bottom: 18px;
            margin-bottom: 18px;
        }

        .order-meta h1 {
            font-size: 1.35rem;
            font-weight: 800;
            color: #1a2b4a;
        }

        .order-meta span {
            font-size: .8rem;
            color: #8ca0bf;
            margin-top: 4px;
            display: block;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: .75rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-pending {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
        }

        .badge-diproses {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fde68a;
        }

        .badge-dikerjakan {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .badge-selesai {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .badge-batal {
            background: #fdf2f2;
            color: #e05a5a;
            border: 1px solid #fde8e8;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        @media(max-width:600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        .info-section h3 {
            font-size: .9rem;
            font-weight: 700;
            color: #1a2b4a;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .info-box {
            background: #f5f8ff;
            border: 1px solid #dde8f8;
            border-radius: 12px;
            padding: 16px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
            font-size: .82rem;
            line-height: 1.5;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            width: 120px;
            color: #8ca0bf;
            font-weight: 500;
            flex-shrink: 0;
        }

        .info-value {
            color: #2d4060;
            font-weight: 600;
        }

        .detail-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .82rem;
            margin-bottom: 24px;
        }

        .detail-table th {
            background: #f5f8ff;
            color: #8ca0bf;
            font-weight: 600;
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f4;
        }

        .detail-table th:first-child {
            border-radius: 8px 0 0 8px;
        }

        .detail-table th:last-child {
            border-radius: 0 8px 8px 0;
            text-align: right;
        }

        .detail-table td {
            padding: 14px 12px;
            color: #2d4060;
            border-bottom: 1px solid #f6f9fd;
            vertical-align: middle;
        }

        .detail-table tr:last-child td {
            border-bottom: none;
        }

        .detail-table td:last-child {
            text-align: right;
        }

        .total-summary-card {
            display: flex;
            justify-content: flex-end;
            margin-top: 16px;
        }

        .total-box {
            width: 340px;
            background: #f5f8ff;
            border: 1px solid #dde8f8;
            border-radius: 12px;
            padding: 16px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-size: .85rem;
            margin-bottom: 10px;
        }

        .total-grand {
            display: flex;
            justify-content: space-between;
            font-size: 1.05rem;
            font-weight: 800;
            color: #1a2b4a;
            border-top: 1px dashed #c5d8f5;
            padding-top: 12px;
            margin-top: 12px;
        }

        @media(max-width:600px) {
            .total-summary-card {
                justify-content: center;
            }

            .total-box {
                width: 100%;
            }
        }

        /* Payment Section */
        .pay-sum-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        @media(max-width:600px) {
            .pay-sum-grid {
                grid-template-columns: 1fr;
            }
        }

        .pay-sum-card {
            border-radius: 12px;
            padding: 14px;
            text-align: center;
        }

        .pay-sum-card .label {
            font-size: .68rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .3px;
            margin-bottom: 3px;
        }

        .pay-sum-card .value {
            font-size: 1.15rem;
            font-weight: 800;
        }

        .psc-tagihan {
            background: #fff3e6;
        }

        .psc-tagihan .label {
            color: #d97706;
        }

        .psc-tagihan .value {
            color: #b45309;
        }

        .psc-terbayar {
            background: #ecfdf5;
        }

        .psc-terbayar .label {
            color: #059669;
        }

        .psc-terbayar .value {
            color: #047857;
        }

        .psc-sisa {
            background: #fef2f2;
        }

        .psc-sisa .label {
            color: #dc2626;
        }

        .psc-sisa .value {
            color: #b91c1c;
        }

        .pay-badge-sm {
            display: inline-flex;
            padding: 3px 8px;
            border-radius: 14px;
            font-size: .68rem;
            font-weight: 700;
        }

        .pb-belum_bayar {
            background: #fef2f2;
            color: #dc2626;
        }

        .pb-dp {
            background: #fff3e6;
            color: #d97706;
        }

        .pb-lunas {
            background: #ecfdf5;
            color: #059669;
        }

        .progress-bar-bg {
            background: #f0f4fb;
            border-radius: 6px;
            height: 6px;
            overflow: hidden;
            margin-top: 4px;
        }

        .progress-bar-fg {
            height: 100%;
            border-radius: 6px;
        }

        .cover-badge {
            font-size: .65rem;
            font-weight: 700;
            padding: 2px 5px;
            border-radius: 4px;
        }

        .cb-lunas {
            background: #ecfdf5;
            color: #059669;
        }

        .cb-sebagian {
            background: #fff3e6;
            color: #d97706;
        }

        .cb-belum_bayar {
            background: #fef2f2;
            color: #dc2626;
        }

        .termin-card {
            background: #f5f8ff;
            border: 1px solid #e8eef8;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 10px;
        }

        .termin-card:last-child {
            margin-bottom: 0;
        }

        .termin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .termin-title {
            font-weight: 700;
            font-size: .82rem;
            color: #1a2b4a;
        }

        .termin-amount {
            font-weight: 800;
            font-size: .88rem;
            color: #4A90D9;
        }

        .termin-meta {
            font-size: .72rem;
            color: #8ca0bf;
        }
    </style>
@endpush

@section('content')
    <div class="detail-container">
        <div>
            <a href="{{ route('pelanggan.pesanan.index') }}" class="btn-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12" />
                    <polyline points="12 19 5 12 12 5" />
                </svg>
                Kembali ke Status Pesanan
            </a>
        </div>

        {{-- Detail Card --}}
        <div class="card">
            <div class="detail-header">
                <div class="order-meta">
                    <h1>No. Pesanan: {{ $pesanan->no_pesanan }}</h1>
                    <span>Dibuat pada:
                        {{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->isoFormat('DD MMMM YYYY, HH:mm') }} WIB</span>
                </div>
                <div>
                    @if($pesanan->status === 'pending')
                        <span class="status-badge badge-pending">Menunggu Persetujuan</span>
                    @elseif($pesanan->status === 'diproses')
                        <span class="status-badge badge-diproses">Diproses</span>
                    @elseif($pesanan->status === 'dikerjakan')
                        <span class="status-badge badge-dikerjakan">Dikerjakan</span>
                    @elseif($pesanan->status === 'selesai')
                        <span class="status-badge badge-selesai">Selesai</span>
                    @else
                        <span class="status-badge badge-batal">Batal</span>
                    @endif
                </div>
            </div>

            {{-- Info Grid --}}
            <div class="info-grid">
                <div class="info-section">
                    <h3>Detail Pemesan</h3>
                    <div class="info-box">
                        <div class="info-row"><span class="info-label">Nama</span><span class="info-value">:
                                {{ $pesanan->user->name }}</span></div>
                        <div class="info-row"><span class="info-label">No. WhatsApp</span><span class="info-value">:
                                {{ $pesanan->user->no_whatsapp ?? '-' }}</span></div>
                        <div class="info-row"><span class="info-label">Sekolah/Instansi</span><span class="info-value">:
                                {{ $pesanan->user->nama_sekolah ?? '-' }}</span></div>
                    </div>
                </div>
                <div class="info-section">
                    <h3>Alamat Pengiriman</h3>
                    <div class="info-box" style="min-height:110px;">
                        <span style="font-size:.82rem; color:#2d4060; font-weight:500; display:block; line-height:1.6;">
                            {{ $pesanan->user->alamat ?? 'Alamat pengiriman belum diisi oleh pelanggan.' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Items Table with Coverage --}}
            <div class="info-section">
                <h3>Daftar Item Seragam & Progress Pembayaran</h3>
                <div style="overflow-x:auto; width:100%;">
                    <table class="detail-table">
                        <thead>
                            <tr>
                                <th style="width:50px;">No.</th>
                                <th>Nama Seragam</th>
                                <th style="width:80px; text-align:center;">Ukuran</th>
                                <th style="width:120px; text-align:right;">Harga Satuan</th>
                                <th style="width:80px; text-align:center;">Jumlah</th>
                                <th style="width:80px; text-align:center;">Terbayar</th>
                                <th style="width:100px;">Progress</th>
                                <th style="width:140px; text-align:right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan->details as $index => $d)
                                @php
                                    $pct = $d->total_item > 0 ? round(($d->jumlah_terbayar / $d->total_item) * 100) : 0;
                                    $st = $d->status_item;
                                    $clr = $st === 'lunas' ? '#10b981' : ($st === 'sebagian' ? '#f59e0b' : '#ef4444');
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td style="font-weight:700; color:#1a2b4a;">{{ $d->produk->nama_produk ?? 'Seragam' }}</td>
                                    <td style="text-align:center;"><span
                                            style="background:#e8f0fd;color:#4A90D9;padding:3px 8px;border-radius:6px;font-weight:600;">{{ $d->ukuran }}</span>
                                    </td>
                                    <td style="text-align:right;">Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                                    <td style="text-align:center; font-weight:600;">{{ $d->total_item }}</td>
                                    <td style="text-align:center; font-weight:700; color:#047857;">{{ $d->jumlah_terbayar }}
                                    </td>
                                    <td>
                                        <div class="progress-bar-bg">
                                            <div class="progress-bar-fg" style="width:{{ $pct }}%; background:{{ $clr }};">
                                            </div>
                                        </div>
                                        <span style="font-size:.62rem; color:#8ca0bf;">{{ $pct }}%</span>
                                        <span
                                            class="cover-badge cb-{{ $st }}">{{ $st === 'lunas' ? 'Lunas' : ($st === 'sebagian' ? 'Sebagian' : 'Belum') }}</span>
                                    </td>
                                    <td style="text-align:right; font-weight:700; color:#2d4060;">Rp
                                        {{ number_format($d->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Payment Summary --}}
            <div class="pay-sum-grid">
                <div class="pay-sum-card psc-tagihan">
                    <div class="label">Total Tagihan</div>
                    <div class="value">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
                </div>
                <div class="pay-sum-card psc-terbayar">
                    <div class="label">Total Terbayar</div>
                    <div class="value">Rp {{ number_format($pesanan->total_terbayar, 0, ',', '.') }}</div>
                </div>
                <div class="pay-sum-card psc-sisa">
                    <div class="label">Sisa Tagihan</div>
                    <div class="value">Rp {{ number_format($pesanan->sisa_tagihan, 0, ',', '.') }}</div>
                </div>
            </div>

            {{-- Riwayat Pembayaran --}}
            @if($pesanan->pembayarans->isNotEmpty())
                <div class="info-section">
                    <h3>Riwayat Pembayaran
                        <span class="pay-badge-sm pb-{{ $pesanan->status_pembayaran }}" style="margin-left:8px;">
                            {{ $pesanan->status_pembayaran === 'belum_bayar' ? 'Belum Bayar' : ($pesanan->status_pembayaran === 'dp' ? 'DP / Cicilan' : 'Lunas') }}
                        </span>
                    </h3>
                    @foreach($pesanan->pembayarans as $p)
                        <div class="termin-card">
                            <div class="termin-header">
                                <span class="termin-title">Termin {{ $p->termin_ke }}</span>
                                <span class="termin-amount">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</span>
                            </div>
                            <div class="termin-meta">{{ $p->tanggal_bayar->isoFormat('DD MMM YYYY') }} ·
                                {{ ucfirst($p->metode_pembayaran) }}@if($p->catatan) · {{ $p->catatan }}@endif
                            </div>
                            <div style="font-size:.72rem; color:#5a7090; margin-top:6px;">
                                @foreach($p->details as $pd)
                                    <strong>{{ $pd->detailPesanan->produk->nama_produk ?? '-' }}
                                        ({{ $pd->detailPesanan->ukuran }})</strong>: {{ $pd->jumlah_cover }} pcs
                                    @if(!$loop->last), @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Total Summary --}}
            <div class="total-summary-card">
                <div class="total-box">
                    <div class="total-row"><span>Total Items</span><span
                            style="font-weight:600; color:#1a2b4a;">{{ $pesanan->details->sum('total_item') }} Pcs</span>
                    </div>
                    <div class="total-row"><span>Pengiriman</span><span
                            style="font-weight:600; color:#10b981;">Gratis</span></div>
                    <div class="total-grand"><span>Total Tagihan</span><span style="color:#4A90D9;">Rp
                            {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span></div>
                </div>
            </div>
        </div>
    </div>
@endsection