@extends('layouts.main')
@section('title', 'Pembayaran ' . $pesanan->no_pesanan . ' — SIMAPES')

@push('styles')
    <style>
        .page-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #5a7090;
            text-decoration: none;
            font-size: .85rem;
            font-weight: 600;
            margin-bottom: 18px;
            transition: color .15s;
        }

        .page-back:hover {
            color: #4A90D9;
        }

        .payment-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 20px;
            align-items: start;
        }

        @media(max-width:1000px) {
            .payment-grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e8eef8;
            padding: 22px 24px;
            box-shadow: 0 2px 8px rgba(74, 144, 217, .06);
            margin-bottom: 20px;
        }

        .card-title {
            font-size: .95rem;
            font-weight: 700;
            color: #1a2b4a;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f4fb;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Summary Cards */
        .sum-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        @media(max-width:600px) {
            .sum-grid {
                grid-template-columns: 1fr;
            }
        }

        .sum-card {
            border-radius: 12px;
            padding: 16px;
            text-align: center;
        }

        .sum-card .label {
            font-size: .7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 4px;
        }

        .sum-card .value {
            font-size: 1.3rem;
            font-weight: 800;
        }

        .sum-tagihan {
            background: #fff3e6;
        }

        .sum-tagihan .label {
            color: #d97706;
        }

        .sum-tagihan .value {
            color: #b45309;
        }

        .sum-terbayar {
            background: #ecfdf5;
        }

        .sum-terbayar .label {
            color: #059669;
        }

        .sum-terbayar .value {
            color: #047857;
        }

        .sum-sisa {
            background: #fef2f2;
        }

        .sum-sisa .label {
            color: #dc2626;
        }

        .sum-sisa .value {
            color: #b91c1c;
        }

        /* Status Badge */
        .pay-badge {
            display: inline-flex;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .pay-belum_bayar {
            background: #fef2f2;
            color: #dc2626;
        }

        .pay-dp {
            background: #fff3e6;
            color: #d97706;
        }

        .pay-lunas {
            background: #ecfdf5;
            color: #059669;
        }

        /* Items Table */
        .item-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: .78rem;
        }

        .item-tbl th {
            background: #f5f8ff;
            color: #8ca0bf;
            font-weight: 600;
            font-size: .7rem;
            text-transform: uppercase;
            padding: 10px 8px;
            text-align: left;
            border-bottom: 1px solid #e8eef8;
        }

        .item-tbl td {
            padding: 10px 8px;
            border-bottom: 1px solid #f6f9fd;
            vertical-align: middle;
        }

        .item-tbl tr:last-child td {
            border-bottom: none;
        }

        .progress-bar-wrap {
            background: #f0f4fb;
            border-radius: 6px;
            height: 8px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            border-radius: 6px;
            transition: width .3s;
        }

        .cover-badge {
            font-size: .68rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .cover-lunas {
            background: #ecfdf5;
            color: #059669;
        }

        .cover-sebagian {
            background: #fff3e6;
            color: #d97706;
        }

        .cover-belum {
            background: #fef2f2;
            color: #dc2626;
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 24px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 8px;
            top: 4px;
            bottom: 4px;
            width: 2px;
            background: #e8eef8;
        }

        .tl-item {
            position: relative;
            margin-bottom: 20px;
        }

        .tl-item:last-child {
            margin-bottom: 0;
        }

        .tl-dot {
            position: absolute;
            left: -20px;
            top: 4px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .tl-dot-verified {
            background: #10b981;
        }

        .tl-dot-pending {
            background: #f59e0b;
        }

        .tl-dot-rejected {
            background: #ef4444;
        }

        .tl-content {
            background: #f5f8ff;
            border: 1px solid #e8eef8;
            border-radius: 10px;
            padding: 14px;
        }

        .tl-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            flex-wrap: wrap;
            gap: 6px;
        }

        .tl-termin {
            font-weight: 700;
            color: #1a2b4a;
            font-size: .85rem;
        }

        .tl-amount {
            font-weight: 800;
            color: #4A90D9;
            font-size: .9rem;
        }

        .tl-meta {
            font-size: .72rem;
            color: #8ca0bf;
            margin-bottom: 6px;
        }

        .tl-items {
            font-size: .72rem;
            color: #5a7090;
        }

        .tl-items strong {
            color: #1a2b4a;
        }

        /* Form */
        .form-group {
            margin-bottom: 14px;
        }

        .form-label {
            display: block;
            font-size: .75rem;
            font-weight: 600;
            color: #5a7090;
            margin-bottom: 5px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            border: 1.5px solid #dde8f8;
            border-radius: 9px;
            padding: 8px 11px;
            font-size: .82rem;
            font-family: inherit;
            color: #1a2b4a;
            background: #fafdff;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            border-color: #4A90D9;
            box-shadow: 0 0 0 3px rgba(74, 144, 217, .12);
            background: #fff;
        }

        .form-textarea {
            resize: vertical;
            min-height: 60px;
        }

        .alloc-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: .78rem;
            margin-bottom: 12px;
        }

        .alloc-tbl th {
            background: #f5f8ff;
            color: #8ca0bf;
            font-weight: 600;
            font-size: .7rem;
            padding: 8px 6px;
            text-align: left;
        }

        .alloc-tbl td {
            padding: 8px 6px;
            border-bottom: 1px solid #f6f9fd;
        }

        .alloc-input {
            width: 70px;
            padding: 5px 8px;
            border: 1.5px solid #dde8f8;
            border-radius: 7px;
            font-size: .78rem;
            text-align: center;
            font-family: inherit;
        }

        .alloc-input:focus {
            border-color: #4A90D9;
            outline: none;
        }

        .btn-submit {
            display: block;
            width: 100%;
            background: #10b981;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 11px;
            font-size: .88rem;
            font-weight: 700;
            cursor: pointer;
            transition: background .15s;
            margin-top: 8px;
        }

        .btn-submit:hover {
            background: #059669;
        }

        .btn-submit:disabled {
            background: #94d2bd;
            cursor: not-allowed;
        }

        .btn-hapus-sm {
            background: #fee2e2;
            color: #ef4444;
            border: none;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: .7rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-hapus-sm:hover {
            background: #fca5a5;
        }

        .calc-row {
            display: flex;
            justify-content: space-between;
            font-size: .82rem;
            margin-bottom: 6px;
        }

        .calc-total {
            font-weight: 800;
            color: #1a2b4a;
            border-top: 1px dashed #dde8f8;
            padding-top: 8px;
            margin-top: 8px;
        }
    </style>
@endpush

@section('content')
    <a href="{{ route('admin.pesanan.index') }}" class="page-back">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
            stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12" />
            <polyline points="12 19 5 12 12 5" />
        </svg>
        Kembali ke Data Pesanan
    </a>

    <h1 style="font-size:1.5rem; font-weight:800; color:#1a2b4a; margin-bottom:6px;">Pembayaran — {{ $pesanan->no_pesanan }}
    </h1>
    <p style="font-size:.82rem; color:#6b7e9f; margin-bottom:20px;">{{ $pesanan->user->name }} ·
        {{ $pesanan->user->nama_sekolah ?? '-' }}</p>

    {{-- Summary Cards --}}
    <div class="sum-grid">
        <div class="sum-card sum-tagihan">
            <div class="label">Total Tagihan</div>
            <div class="value">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
        </div>
        <div class="sum-card sum-terbayar">
            <div class="label">Total Terbayar</div>
            <div class="value">Rp {{ number_format($pesanan->total_terbayar, 0, ',', '.') }}</div>
        </div>
        <div class="sum-card sum-sisa">
            <div class="label">Sisa Tagihan</div>
            <div class="value">Rp {{ number_format($pesanan->sisa_tagihan, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="payment-grid">
        {{-- Left: Detail Items + Riwayat --}}
        <div>
            {{-- Detail Item & Coverage --}}
            <div class="card">
                <div class="card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path
                            d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z" />
                    </svg>
                    Progress Pembayaran Per Item
                    <span class="pay-badge pay-{{ $pesanan->status_pembayaran }}" style="margin-left:auto;">
                        {{ $pesanan->status_pembayaran === 'belum_bayar' ? 'Belum Bayar' : ($pesanan->status_pembayaran === 'dp' ? 'DP / Cicilan' : 'Lunas') }}
                    </span>
                </div>
                <div style="overflow-x:auto;">
                    <table class="item-tbl">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Ukuran</th>
                                <th style="text-align:center;">Total</th>
                                <th style="text-align:center;">Terbayar</th>
                                <th style="text-align:center;">Sisa</th>
                                <th style="width:120px;">Progress</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan->details as $d)
                                @php
                                    $pct = $d->total_item > 0 ? round(($d->jumlah_terbayar / $d->total_item) * 100) : 0;
                                    $statusItem = $d->status_item;
                                    $color = $statusItem === 'lunas' ? '#10b981' : ($statusItem === 'sebagian' ? '#f59e0b' : '#ef4444');
                                @endphp
                                <tr>
                                    <td style="font-weight:600;">{{ $d->produk->nama_produk ?? '-' }}</td>
                                    <td><span
                                            style="background:#e8f0fd;color:#4A90D9;padding:2px 6px;border-radius:4px;font-weight:700;font-size:.72rem;">{{ $d->ukuran }}</span>
                                    </td>
                                    <td style="text-align:center; font-weight:600;">{{ $d->total_item }} pcs</td>
                                    <td style="text-align:center; font-weight:700; color:#047857;">{{ $d->jumlah_terbayar }} pcs
                                    </td>
                                    <td style="text-align:center; font-weight:600; color:#b91c1c;">{{ $d->jumlah_belum_bayar }}
                                        pcs</td>
                                    <td>
                                        <div class="progress-bar-wrap">
                                            <div class="progress-bar-fill" style="width:{{ $pct }}%; background:{{ $color }};">
                                            </div>
                                        </div>
                                        <span style="font-size:.65rem; color:#8ca0bf;">{{ $pct }}%</span>
                                    </td>
                                    <td>
                                        <span class="cover-badge cover-{{ $statusItem }}">
                                            {{ $statusItem === 'lunas' ? 'Lunas' : ($statusItem === 'sebagian' ? 'Sebagian' : 'Belum') }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Riwayat Pembayaran --}}
            <div class="card">
                <div class="card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    Riwayat Pembayaran ({{ $pesanan->pembayarans->count() }} Termin)
                </div>
                @if($pesanan->pembayarans->isEmpty())
                    <p style="text-align:center; color:#a0aec0; font-size:.85rem; padding:20px 0;">Belum ada pembayaran
                        tercatat.</p>
                @else
                    <div class="timeline">
                        @foreach($pesanan->pembayarans as $p)
                            <div class="tl-item">
                                <div class="tl-dot tl-dot-{{ $p->status }}"></div>
                                <div class="tl-content">
                                    <div class="tl-header">
                                        <span class="tl-termin">Termin {{ $p->termin_ke }}</span>
                                        <span class="tl-amount">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="tl-meta">
                                        {{ $p->tanggal_bayar->isoFormat('DD MMM YYYY') }} · {{ ucfirst($p->metode_pembayaran) }}
                                        @if($p->catatan) · {{ $p->catatan }} @endif
                                    </div>
                                    <div class="tl-items">
                                        @foreach($p->details as $pd)
                                            <strong>{{ $pd->detailPesanan->produk->nama_produk ?? '-' }}
                                                ({{ $pd->detailPesanan->ukuran }})</strong>: {{ $pd->jumlah_cover }} pcs
                                            <span style="color:#8ca0bf;">= Rp
                                                {{ number_format($pd->nominal_cover, 0, ',', '.') }}</span>@if(!$loop->last), @endif
                                        @endforeach
                                    </div>
                                    <div style="margin-top:8px; text-align:right;">
                                        <form method="POST"
                                            action="{{ route('admin.pesanan.pembayaran.destroy', [$pesanan->id, $p->id]) }}"
                                            style="display:inline;"
                                            onsubmit="return confirm('Hapus pembayaran termin {{ $p->termin_ke }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-hapus-sm">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Right: Form Pembayaran --}}
        <div>
            @if($pesanan->sisa_tagihan > 0)
                <div class="card">
                    <div class="card-title">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2" />
                            <line x1="1" y1="10" x2="23" y2="10" />
                        </svg>
                        Catat Pembayaran Baru
                    </div>

                    <form method="POST" action="{{ route('admin.pesanan.pembayaran.store', $pesanan->id) }}" id="payForm">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Tanggal Bayar</label>
                            <input type="date" name="tanggal_bayar" class="form-input" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="form-select" required>
                                <option value="transfer">Transfer Bank</option>
                                <option value="tunai">Tunai</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Alokasi Per Item (berapa pcs yang dibayar)</label>
                            <table class="alloc-tbl">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Sisa</th>
                                        <th>Bayar (pcs)</th>
                                        <th style="text-align:right;">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pesanan->details as $d)
                                        <tr>
                                            <td style="font-weight:600; font-size:.75rem;">{{ $d->produk->nama_produk ?? '-' }}
                                                <span style="color:#4A90D9;">({{ $d->ukuran }})</span></td>
                                            <td style="font-size:.75rem;">{{ $d->jumlah_belum_bayar }} pcs</td>
                                            <td>
                                                <input type="number" name="alokasi[{{ $d->id }}]" class="alloc-input" value="0"
                                                    min="0" max="{{ $d->jumlah_belum_bayar }}" data-harga="{{ $d->harga_satuan }}"
                                                    data-max="{{ $d->jumlah_belum_bayar }}" oninput="calcAlloc()">
                                            </td>
                                            <td style="text-align:right; font-weight:600;" id="nom-{{ $d->id }}">Rp 0</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div style="background:#f5f8ff; border-radius:10px; padding:12px; margin-bottom:14px;">
                            <div class="calc-row"><span>Total Alokasi</span><span id="calcTotal"
                                    style="font-weight:700; color:#4A90D9;">Rp 0</span></div>
                            <div class="calc-row"><span>Sisa Tagihan</span><span style="font-weight:600; color:#b91c1c;">Rp
                                    {{ number_format($pesanan->sisa_tagihan, 0, ',', '.') }}</span></div>
                        </div>

                        <input type="hidden" name="jumlah_bayar" id="jumlahBayarHidden" value="0">

                        <div class="form-group">
                            <label class="form-label">Catatan (opsional)</label>
                            <textarea name="catatan" class="form-textarea" placeholder="Catatan pembayaran..."></textarea>
                        </div>

                        <button type="submit" class="btn-submit" id="btnSubmitPay" disabled>Catat Pembayaran</button>
                    </form>
                </div>
            @else
                <div class="card" style="text-align:center; padding:40px;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="1.5"
                        style="margin-bottom:12px;">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    <h3 style="color:#059669; font-weight:800; margin-bottom:4px;">Pembayaran Lunas</h3>
                    <p style="font-size:.82rem; color:#6b7e9f;">Seluruh tagihan untuk pesanan ini telah terbayar.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function calcAlloc() {
            let total = 0;
            document.querySelectorAll('.alloc-input').forEach(input => {
                const qty = parseInt(input.value) || 0;
                const harga = parseFloat(input.dataset.harga) || 0;
                const max = parseInt(input.dataset.max) || 0;
                if (qty > max) input.value = max;
                const nom = Math.min(qty, max) * harga;
                total += nom;
                const detailId = input.name.match(/\[(\d+)\]/)[1];
                document.getElementById('nom-' + detailId).textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(nom);
            });
            document.getElementById('calcTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
            document.getElementById('jumlahBayarHidden').value = total;
            document.getElementById('btnSubmitPay').disabled = total <= 0;
        }
    </script>
@endpush