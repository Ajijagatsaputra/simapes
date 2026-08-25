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
            @php
                $t1Admin = $pesanan->pembayarans->where('termin_ke', 1)->first();
                $t2Admin = $pesanan->pembayarans->where('termin_ke', 2)->first();
                $dpNominal = $pesanan->total_harga / 2;
            @endphp
            <div class="card">
                <div class="card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                    Riwayat Pembayaran ({{ $pesanan->pembayarans->count() }} Termin)
                </div>

                {{-- Progress Steps Termin --}}
                <div style="display:flex; gap:0; margin-bottom:20px; position:relative;">
                    {{-- Connector line --}}
                    <div style="position:absolute; top:20px; left:50%; transform:translateX(-50%); width:calc(100% - 80px); height:2px; background:#e2e8f4; z-index:0;"></div>

                    @php
                        $steps = [
                            1 => ['label' => 'DP (50%)', 'nominal' => $dpNominal],
                            2 => ['label' => 'Pelunasan', 'nominal' => $pesanan->total_harga - $dpNominal],
                        ];
                    @endphp
                    @foreach($steps as $stepNum => $step)
                        @php
                            $tAdmin = $pesanan->pembayarans->where('termin_ke', $stepNum)->first();
                            $isDone    = $tAdmin && $tAdmin->status === 'verified';
                            $isPending = $tAdmin && $tAdmin->status === 'pending';
                            $dotBg  = $isDone ? '#10b981' : ($isPending ? '#f59e0b' : '#e2e8f4');
                            $dotClr = $isDone || $isPending ? '#fff' : '#8ca0bf';
                            $txtClr = $isDone ? '#059669' : ($isPending ? '#d97706' : '#94a3b8');
                        @endphp
                        <div style="flex:1; display:flex; flex-direction:column; align-items:center; position:relative; z-index:1;">
                            <div style="width:40px; height:40px; border-radius:50%; background:{{ $dotBg }}; color:{{ $dotClr }}; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:.85rem; box-shadow:0 2px 6px rgba(0,0,0,.1);">
                                @if($isDone) ✓ @elseif($isPending) ⏳ @else {{ $stepNum }} @endif
                            </div>
                            <div style="font-size:.72rem; font-weight:700; color:#1a2b4a; margin-top:6px;">Termin {{ $stepNum }}</div>
                            <div style="font-size:.68rem; color:{{ $txtClr }}; font-weight:600;">{{ $step['label'] }}</div>
                            <div style="font-size:.65rem; color:#8ca0bf; margin-top:2px;">
                                @if($isDone) ✓ Verified
                                @elseif($isPending) Menunggu Verifikasi
                                @else Belum Dibayar
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- END Progress Steps --}}
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
                                        <span class="tl-termin">
                                            Termin {{ $p->termin_ke }}
                                            — {{ $p->termin_ke === 1 ? 'DP' : 'Pelunasan' }}
                                            @if($p->status === 'pending')
                                                <span style="background:#fff3e6;color:#d97706;font-size:.65rem;font-weight:700;padding:2px 7px;border-radius:10px;margin-left:6px;">⏳ Menunggu Verifikasi</span>
                                            @else
                                                <span style="background:#ecfdf5;color:#059669;font-size:.65rem;font-weight:700;padding:2px 7px;border-radius:10px;margin-left:6px;">✓ Terverifikasi</span>
                                            @endif
                                        </span>
                                        <span class="tl-amount">Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="tl-meta">
                                        {{ $p->tanggal_bayar->isoFormat('DD MMM YYYY') }} · {{ ucfirst($p->metode_pembayaran) }}
                                        @if($p->catatan) · Catatan Admin: {{ $p->catatan }} @endif
                                        @if($p->catatan_pelanggan) · Catatan Pelanggan: <em style="color:#1a2b4a;">"{{ $p->catatan_pelanggan }}"</em> @endif
                                    </div>
                                    <div class="tl-items" style="margin-bottom:8px;">
                                        @foreach($p->details as $pd)
                                            <strong>{{ $pd->detailPesanan->produk->nama_produk ?? '-' }}
                                                ({{ $pd->detailPesanan->ukuran }})</strong>: {{ $pd->jumlah_cover }} pcs
                                            <span style="color:#8ca0bf;">= Rp
                                                {{ number_format($pd->nominal_cover, 0, ',', '.') }}</span>@if(!$loop->last), @endif
                                        @endforeach
                                    </div>
                                    
                                    @if($p->bukti_bayar)
                                        <div style="margin-bottom:8px;">
                                            <a href="{{ asset('storage/' . $p->bukti_bayar) }}" target="_blank" style="display:inline-flex;align-items:center;gap:6px;font-size:.72rem;color:#4A90D9;text-decoration:none;font-weight:700;background:#e8f0fd;padding:4px 10px;border-radius:6px;">
                                                📎 Lihat Bukti Pembayaran
                                            </a>
                                        </div>
                                    @endif

                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-top:8px;">
                                        <div>
                                            @if($p->status === 'pending')
                                                <form method="POST"
                                                    action="{{ route('admin.pesanan.pembayaran.verifikasi', [$pesanan->id, $p->id]) }}"
                                                    style="display:inline;">
                                                    @csrf
                                                    <button type="submit" style="background:#10b981;color:#fff;border:none;border-radius:6px;padding:5px 12px;font-size:.7rem;font-weight:700;cursor:pointer;transition:background .15s;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                                                        ✓ Verifikasi Pembayaran
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
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

        {{-- Right: Status Info Panel --}}
        <div>
            @php
                $dpNominal = $pesanan->total_harga / 2;
                $t1 = $pesanan->pembayarans->where('termin_ke', 1)->first();
                $t2 = $pesanan->pembayarans->where('termin_ke', 2)->first();
                $sp = $pesanan->status_pembayaran ?? 'belum_bayar';
            @endphp

            {{-- Status Badge Card --}}
            <div class="card" style="text-align:center; padding:28px 24px;">
                <div style="font-size:.72rem; font-weight:600; color:#8ca0bf; text-transform:uppercase; letter-spacing:.5px; margin-bottom:10px;">Status Pembayaran</div>
                @if($sp === 'lunas')
                    <div style="width:60px; height:60px; border-radius:50%; background:#ecfdf5; display:flex; align-items:center; justify-content:center; margin:0 auto 12px;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <div style="font-size:1.1rem; font-weight:800; color:#059669;">Lunas</div>
                    <div style="font-size:.75rem; color:#6b7e9f; margin-top:4px;">Seluruh tagihan telah terbayar</div>
                @elseif($sp === 'dp')
                    <div style="width:60px; height:60px; border-radius:50%; background:#fff3e6; display:flex; align-items:center; justify-content:center; margin:0 auto 12px;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </div>
                    <div style="font-size:1.1rem; font-weight:800; color:#d97706;">DP Terbayar</div>
                    <div style="font-size:.75rem; color:#6b7e9f; margin-top:4px;">Menunggu pelunasan dari pelanggan</div>
                @else
                    <div style="width:60px; height:60px; border-radius:50%; background:#fef2f2; display:flex; align-items:center; justify-content:center; margin:0 auto 12px;">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
                    </div>
                    <div style="font-size:1.1rem; font-weight:800; color:#dc2626;">Belum Bayar</div>
                    <div style="font-size:.75rem; color:#6b7e9f; margin-top:4px;">Pelanggan belum melakukan pembayaran</div>
                @endif
            </div>

            {{-- Termin 1: DP --}}
            <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; padding-bottom:12px; border-bottom:1px solid #f0f4fb;">
                    <div style="font-size:.88rem; font-weight:700; color:#1a2b4a; display:flex; align-items:center; gap:8px;">
                        <span style="width:28px; height:28px; border-radius:50%; background:{{ $t1 && $t1->status === 'verified' ? '#ecfdf5' : '#f5f8ff' }}; color:{{ $t1 && $t1->status === 'verified' ? '#059669' : '#8ca0bf' }}; display:inline-flex; align-items:center; justify-content:center; font-weight:800; font-size:.78rem;">1</span>
                        Termin 1 &mdash; DP
                    </div>
                    @if($t1)
                        @if($t1->status === 'verified')
                            <span style="background:#ecfdf5; color:#059669; font-size:.65rem; font-weight:700; padding:3px 8px; border-radius:10px;">✓ Verified</span>
                        @else
                            <span style="background:#fff3e6; color:#d97706; font-size:.65rem; font-weight:700; padding:3px 8px; border-radius:10px;">⏳ Pending</span>
                        @endif
                    @else
                        <span style="background:#f3f4f6; color:#6b7280; font-size:.65rem; font-weight:700; padding:3px 8px; border-radius:10px;">Belum Dibayar</span>
                    @endif
                </div>

                @if($t1)
                    <div style="display:flex; flex-direction:column; gap:8px; font-size:.8rem;">
                        <div style="display:flex; justify-content:space-between;">
                            <span style="color:#8ca0bf;">Nominal</span>
                            <span style="font-weight:700; color:#1a2b4a;">Rp {{ number_format($t1->jumlah_bayar, 0, ',', '.') }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span style="color:#8ca0bf;">Tanggal</span>
                            <span style="font-weight:600;">{{ $t1->tanggal_bayar?->isoFormat('DD MMM YYYY') ?? '-' }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span style="color:#8ca0bf;">Metode</span>
                            <span style="font-weight:600;">{{ ucfirst($t1->metode_pembayaran ?? '-') }}</span>
                        </div>
                        @if($t1->catatan_pelanggan)
                            <div style="background:#fff3e6; border:1px solid #fde68a; border-radius:8px; padding:8px 10px; font-size:.72rem; color:#92400e;">
                                💬 <em>"{{ $t1->catatan_pelanggan }}"</em>
                            </div>
                        @endif
                        @if($t1->bukti_bayar)
                            <a href="{{ asset('storage/' . $t1->bukti_bayar) }}" target="_blank"
                                style="display:inline-flex; align-items:center; gap:6px; font-size:.72rem; color:#4A90D9; text-decoration:none; font-weight:700; background:#e8f0fd; padding:6px 10px; border-radius:6px; width:fit-content;">
                                📎 Lihat Bukti Pembayaran
                            </a>
                        @endif
                        @if($t1->status === 'pending')
                            <form method="POST" action="{{ route('admin.pesanan.pembayaran.verifikasi', [$pesanan->id, $t1->id]) }}">
                                @csrf
                                <button type="submit"
                                    style="width:100%; background:#10b981; color:#fff; border:none; border-radius:8px; padding:9px; font-size:.8rem; font-weight:700; cursor:pointer; transition:background .15s;"
                                    onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                                    ✓ Verifikasi Pembayaran DP
                                </button>
                            </form>
                        @endif
                    </div>
                @else
                    <div style="text-align:center; padding:16px 0; color:#a0aec0; font-size:.8rem;">
                        Pelanggan belum melakukan pembayaran DP.
                    </div>
                @endif
            </div>

            {{-- Termin 2: Pelunasan --}}
            <div class="card">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; padding-bottom:12px; border-bottom:1px solid #f0f4fb;">
                    <div style="font-size:.88rem; font-weight:700; color:#1a2b4a; display:flex; align-items:center; gap:8px;">
                        <span style="width:28px; height:28px; border-radius:50%; background:{{ $t2 && $t2->status === 'verified' ? '#ecfdf5' : '#f5f8ff' }}; color:{{ $t2 && $t2->status === 'verified' ? '#059669' : '#8ca0bf' }}; display:inline-flex; align-items:center; justify-content:center; font-weight:800; font-size:.78rem;">2</span>
                        Termin 2 &mdash; Pelunasan
                    </div>
                    @if($t2)
                        @if($t2->status === 'verified')
                            <span style="background:#ecfdf5; color:#059669; font-size:.65rem; font-weight:700; padding:3px 8px; border-radius:10px;">✓ Verified</span>
                        @else
                            <span style="background:#fff3e6; color:#d97706; font-size:.65rem; font-weight:700; padding:3px 8px; border-radius:10px;">⏳ Pending</span>
                        @endif
                    @elseif($t1 && $t1->status === 'verified')
                        <span style="background:#eff6ff; color:#3b82f6; font-size:.65rem; font-weight:700; padding:3px 8px; border-radius:10px;">Siap Dibayar</span>
                    @else
                        <span style="background:#f3f4f6; color:#6b7280; font-size:.65rem; font-weight:700; padding:3px 8px; border-radius:10px;">Terkunci</span>
                    @endif
                </div>

                @if($t2)
                    <div style="display:flex; flex-direction:column; gap:8px; font-size:.8rem;">
                        <div style="display:flex; justify-content:space-between;">
                            <span style="color:#8ca0bf;">Nominal</span>
                            <span style="font-weight:700; color:#1a2b4a;">Rp {{ number_format($t2->jumlah_bayar, 0, ',', '.') }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span style="color:#8ca0bf;">Tanggal</span>
                            <span style="font-weight:600;">{{ $t2->tanggal_bayar?->isoFormat('DD MMM YYYY') ?? '-' }}</span>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span style="color:#8ca0bf;">Metode</span>
                            <span style="font-weight:600;">{{ ucfirst($t2->metode_pembayaran ?? '-') }}</span>
                        </div>
                        @if($t2->catatan_pelanggan)
                            <div style="background:#fff3e6; border:1px solid #fde68a; border-radius:8px; padding:8px 10px; font-size:.72rem; color:#92400e;">
                                💬 <em>"{{ $t2->catatan_pelanggan }}"</em>
                            </div>
                        @endif
                        @if($t2->bukti_bayar)
                            <a href="{{ asset('storage/' . $t2->bukti_bayar) }}" target="_blank"
                                style="display:inline-flex; align-items:center; gap:6px; font-size:.72rem; color:#4A90D9; text-decoration:none; font-weight:700; background:#e8f0fd; padding:6px 10px; border-radius:6px; width:fit-content;">
                                📎 Lihat Bukti Pembayaran
                            </a>
                        @endif
                        @if($t2->status === 'pending')
                            <form method="POST" action="{{ route('admin.pesanan.pembayaran.verifikasi', [$pesanan->id, $t2->id]) }}">
                                @csrf
                                <button type="submit"
                                    style="width:100%; background:#10b981; color:#fff; border:none; border-radius:8px; padding:9px; font-size:.8rem; font-weight:700; cursor:pointer; transition:background .15s;"
                                    onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                                    ✓ Verifikasi Pelunasan
                                </button>
                            </form>
                        @endif
                    </div>
                @elseif($t1 && $t1->status === 'verified')
                    <div style="text-align:center; padding:16px 0; color:#3b82f6; font-size:.8rem;">
                        DP sudah lunas. Menunggu pelanggan membayar pelunasan.
                    </div>
                @else
                    <div style="text-align:center; padding:16px 0; color:#a0aec0; font-size:.8rem;">
                        🔒 Terkunci — selesaikan Termin 1 terlebih dahulu.
                    </div>
                @endif
            </div>

            {{-- Info Note --}}
            <div style="background:#f0f9ff; border:1px solid #bae6fd; border-radius:12px; padding:14px 16px; font-size:.75rem; color:#0369a1; display:flex; gap:10px; align-items:flex-start;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:1px;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div>
                    <strong>Pembayaran dilakukan oleh pelanggan</strong> melalui gateway Xendit secara online.
                    Admin hanya melakukan <strong>verifikasi & monitoring</strong>.
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush