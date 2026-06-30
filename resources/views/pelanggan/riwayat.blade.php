@extends('layouts.pelanggan')
@section('title', 'Riwayat Pesanan - SIMAPES')

@push('styles')
    <style>
        .riwayat-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f4;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(26, 43, 74, .03);
        }

        .table-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .table-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a2b4a;
        }

        .search-wrap {
            display: flex;
            align-items: center;
            background: #f5f8ff;
            border: 1px solid #dde8f8;
            border-radius: 10px;
            padding: 6px 12px;
            gap: 8px;
        }

        .search-wrap input {
            border: none;
            background: transparent;
            outline: none;
            font-size: .8rem;
            color: #1a2b4a;
            width: 180px;
        }

        /* ── Table Styling ── */
        .order-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .82rem;
        }

        .order-table th {
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

        .order-table th:first-child {
            border-radius: 8px 0 0 8px;
        }

        .order-table th:last-child {
            border-radius: 0 8px 8px 0;
            text-align: center;
        }

        .order-table td {
            padding: 14px 12px;
            color: #2d4060;
            border-bottom: 1px solid #f6f9fd;
            vertical-align: middle;
        }

        .order-table tr:hover td {
            background: #fafcff;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .btn-detail {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #e8f0fd;
            color: #4A90D9;
            border: none;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: .78rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-detail:hover {
            background: #4A90D9;
            color: #fff;
        }

        .empty-state {
            text-align: center;
            padding: 48px 24px;
        }

        .empty-state svg {
            color: #8ca0bf;
            margin-bottom: 12px;
        }

        .empty-state h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1a2b4a;
        }

        .empty-state p {
            font-size: .82rem;
            color: #6b7e9f;
            margin-top: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="riwayat-container">
        {{-- Header --}}
        <div>
            <h1 style="font-size: 1.6rem; font-weight: 800; color: #1a2b4a;">Riwayat Pemesanan</h1>
            <p style="font-size: .85rem; color: #6b7e9f; margin-top: 4px;">Daftar seluruh pesanan Anda yang telah selesai
                dikerjakan</p>
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="table-toolbar">
                <span class="table-title">Pesanan Selesai</span>
                <div class="search-wrap">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari nomor pesanan..." oninput="filterOrders()">
                </div>
            </div>

            <div style="overflow-x: auto; width: 100%;">
                <table class="order-table" id="orderTable">
                    <thead>
                        <tr>
                            <th style="width: 140px;">No. Pesanan</th>
                            <th style="width: 140px;">Tanggal</th>
                            <th>Item Seragam</th>
                            <th style="width: 160px; text-align: right;">Total Harga</th>
                            <th style="width: 150px; text-align: center;">Status</th>
                            <th style="width: 100px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="orderBody">
                        @forelse($riwayat as $p)
                            <tr data-search="{{ strtolower($p->no_pesanan) }}">
                                <td style="font-weight: 700; color: #1a2b4a;">{{ $p->no_pesanan }}</td>
                                <td>{{ \Carbon\Carbon::parse($p->tanggal_pesanan)->isoFormat('DD MMMM YYYY') }}</td>
                                <td>
                                    <div style="display: flex; flex-direction: column; gap: 2px;">
                                        @foreach($p->details as $d)
                                            <span style="font-size: .78rem; color: #5a7090;">
                                                • {{ $d->produk->nama_produk ?? 'Seragam' }} (Size: {{ $d->ukuran }},
                                                {{ $d->total_item }} pcs)
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td style="text-align: right; font-weight: 700; color: #4A90D9;">
                                    Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                                </td>
                                <td style="text-align: center;">
                                    <span class="status-badge">Selesai</span>
                                </td>
                                <td style="text-align: center;">
                                    <a href="{{ route('pelanggan.pesanan.show', $p->id) }}" class="btn-detail">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <polyline points="9 11 12 14 22 4"></polyline>
                                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2 2V5a2 2 0 0 1 2-2h11"></path>
                                        </svg>
                                        <h3>Belum Ada Riwayat</h3>
                                        <p>Anda belum memiliki transaksi pesanan seragam yang selesai dikerjakan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($riwayat->hasPages())
                <div style="margin-top: 20px; display: flex; justify-content: center;">
                    {{ $riwayat->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        function filterOrders() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#orderBody tr:not(:has(.empty-state))');

            rows.forEach(row => {
                const searchVal = row.getAttribute('data-search');
                if (searchVal.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
@endsection