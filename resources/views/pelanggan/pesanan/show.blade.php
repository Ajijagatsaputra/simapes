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
            transition: color 0.15s;
        }

        .btn-back:hover {
            color: #4A90D9;
        }

        /* ── Header Detail ── */
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

        /* Status Badge */
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

        /* ── Info Grid ── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        @media (max-width: 600px) {
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

        /* ── Table Items ── */
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
            width: 300px;
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

        @media (max-width: 600px) {
            .total-summary-card {
                justify-content: center;
            }

            .total-box {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="detail-container">
        {{-- Back Button --}}
        <div>
            <a href="{{ route('pelanggan.pesanan.index') }}" class="btn-back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali ke Status Pesanan
            </a>
        </div>

        {{-- Detail Card --}}
        <div class="card">
            {{-- Header --}}
            <div class="detail-header">
                <div class="order-meta">
                    <h1>No. Pesanan: {{ $pesanan->no_pesanan }}</h1>
                    <span>Dibuat pada:
                        {{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->isoFormat('DD MMMM YYYY, HH:mm') }} WIB</span>
                </div>
                <div>
                    @if($pesanan->status === 'diproses')
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
                {{-- Pemesan Info --}}
                <div class="info-section">
                    <h3>Detail Pemesan</h3>
                    <div class="info-box">
                        <div class="info-row">
                            <span class="info-label">Nama</span>
                            <span class="info-value">: {{ $pesanan->user->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">No. WhatsApp</span>
                            <span class="info-value">: {{ $pesanan->user->no_whatsapp ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Sekolah/Instansi</span>
                            <span class="info-value">: {{ $pesanan->user->nama_sekolah ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Delivery Info --}}
                <div class="info-section">
                    <h3>Alamat Pengiriman</h3>
                    <div class="info-box" style="min-height: 110px;">
                        <span
                            style="font-size: .82rem; color: #2d4060; font-weight: 500; display: block; line-height: 1.6;">
                            {{ $pesanan->user->alamat ?? 'Alamat pengiriman belum diisi oleh pelanggan.' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Items Table --}}
            <div class="info-section">
                <h3>Daftar Item Seragam</h3>
                <div style="overflow-x: auto; width: 100%;">
                    <table class="detail-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No.</th>
                                <th>Nama Seragam</th>
                                <th style="width: 120px; text-align: center;">Ukuran</th>
                                <th style="width: 150px; text-align: right;">Harga Satuan</th>
                                <th style="width: 100px; text-align: center;">Jumlah</th>
                                <th style="width: 160px; text-align: right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan->details as $index => $d)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td style="font-weight: 700; color: #1a2b4a;">
                                        {{ $d->produk->nama_produk ?? 'Seragam' }}
                                    </td>
                                    <td style="text-align: center; font-weight: 600;">
                                        <span
                                            style="background: #e8f0fd; color: #4A90D9; padding: 3px 8px; border-radius: 6px;">{{ $d->ukuran }}</span>
                                    </td>
                                    <td style="text-align: right;">
                                        Rp {{ number_format($d->harga_satuan, 0, ',', '.') }}
                                    </td>
                                    <td style="text-align: center; font-weight: 600;">
                                        {{ $d->total_item }} Pcs
                                    </td>
                                    <td style="text-align: right; font-weight: 700; color: #2d4060;">
                                        Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Summary Total --}}
            <div class="total-summary-card">
                <div class="total-box">
                    <div class="total-row">
                        <span>Total Items</span>
                        <span style="font-weight: 600; color: #1a2b4a;">{{ $pesanan->details->sum('total_item') }}
                            Pcs</span>
                    </div>
                    <div class="total-row">
                        <span>Pengiriman</span>
                        <span style="font-weight: 600; color: #10b981;">Gratis</span>
                    </div>
                    <div class="total-grand">
                        <span>Total Bayar</span>
                        <span style="color: #4A90D9;">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection