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

        /* ── Modal Pembayaran ── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(10, 20, 50, .55);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            opacity: 0;
            visibility: hidden;
            transition: opacity .25s, visibility .25s;
        }

        .modal-overlay.open {
            opacity: 1;
            visibility: visible;
        }

        .modal-box {
            background: #fff;
            border-radius: 20px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 24px 64px rgba(26, 43, 74, .18);
            transform: translateY(24px);
            transition: transform .25s;
            overflow: hidden;
        }

        .modal-overlay.open .modal-box {
            transform: translateY(0);
        }

        .modal-header {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            color: #fff;
            font-size: 1rem;
            font-weight: 800;
            margin: 0;
        }

        .modal-header span {
            color: #93c5fd;
            font-size: .78rem;
            margin-top: 3px;
            display: block;
        }

        .modal-close {
            background: rgba(255, 255, 255, .15);
            border: none;
            color: #fff;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .15s;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, .3);
        }

        .modal-body {
            padding: 20px 24px;
        }

        .modal-footer {
            padding: 0 24px 20px;
            display: flex;
            gap: 10px;
        }

        .pay-method-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 16px;
        }

        .pay-method-btn {
            border: 2px solid #e2e8f4;
            border-radius: 12px;
            padding: 14px 12px;
            cursor: pointer;
            text-align: center;
            transition: border-color .15s, background .15s;
            background: #fff;
        }

        .pay-method-btn:hover {
            border-color: #4A90D9;
            background: #f5f8ff;
        }

        .pay-method-btn.selected {
            border-color: #4A90D9;
            background: #eaf3fc;
        }

        .pay-method-btn .icon {
            font-size: 1.4rem;
            margin-bottom: 4px;
        }

        .pay-method-btn .label {
            font-size: .8rem;
            font-weight: 700;
            color: #1a2b4a;
        }

        .pay-method-btn .sub {
            font-size: .68rem;
            color: #8ca0bf;
            margin-top: 2px;
        }

        .form-lbl {
            display: block;
            font-size: .75rem;
            font-weight: 600;
            color: #5a7090;
            margin-bottom: 5px;
        }

        .form-ctrl {
            width: 100%;
            border: 1.5px solid #dde8f8;
            border-radius: 9px;
            padding: 9px 12px;
            font-size: .83rem;
            font-family: inherit;
            color: #1a2b4a;
            background: #fafdff;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
            box-sizing: border-box;
        }

        .form-ctrl:focus {
            border-color: #4A90D9;
            box-shadow: 0 0 0 3px rgba(74, 144, 217, .12);
            background: #fff;
        }

        .upload-zone {
            border: 2px dashed #c5d8f5;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color .15s, background .15s;
            margin-bottom: 16px;
        }

        .upload-zone:hover {
            border-color: #4A90D9;
            background: #f5f8ff;
        }

        .upload-zone.has-file {
            border-color: #10b981;
            background: #ecfdf5;
        }

        .upload-zone .uz-icon {
            font-size: 1.6rem;
            margin-bottom: 6px;
        }

        .upload-zone .uz-text {
            font-size: .78rem;
            color: #6b7e9f;
        }

        .upload-zone .uz-file {
            font-size: .78rem;
            font-weight: 700;
            color: #059669;
            margin-top: 4px;
        }

        .btn-submit-bayar {
            flex: 1;
            background: #4A90D9;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 11px;
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: background .2s;
        }

        .btn-submit-bayar:hover {
            background: #3a7bc8;
        }

        .btn-cancel-modal {
            background: #f0f4fb;
            color: #5a7090;
            border: 1px solid #dde8f8;
            border-radius: 10px;
            padding: 11px 18px;
            font-size: .85rem;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: background .15s;
        }

        .btn-cancel-modal:hover {
            background: #e2e8f4;
        }

        .nominal-highlight {
            background: #fff3e6;
            border: 1px solid #fde68a;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nominal-highlight .nh-label {
            font-size: .78rem;
            color: #d97706;
            font-weight: 600;
        }

        .nominal-highlight .nh-value {
            font-size: 1.1rem;
            font-weight: 800;
            color: #b45309;
        }

        .btn-bayar-termin {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: linear-gradient(135deg, #1e3c72, #3a7bc8);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 18px;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: opacity .15s, transform .15s;
            text-decoration: none;
        }

        .btn-bayar-termin:hover {
            opacity: .9;
            transform: translateY(-1px);
        }

        .btn-bayar-termin:disabled {
            background: #cbd5e1;
            color: #94a3b8;
            cursor: not-allowed;
            transform: none;
        }

        .btn-bayar-lunas {
            background: linear-gradient(135deg, #059669, #10b981);
        }

        .badge-pending-pay {
            background: #fff3e6;
            color: #d97706;
            font-size: .65rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 10px;
            margin-left: 8px;
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

            {{-- Progres Produksi Timeline --}}
            @if(in_array($pesanan->status, ['dikerjakan', 'selesai']))
                <div class="info-section" style="margin-bottom: 28px;">
                    <h3>Progres Produksi Seragam</h3>

                    @if($pesanan->progresProduksis->isEmpty())
                        <div
                            style="background: #fafbfc; border: 1px dashed #dde8f8; border-radius: 12px; padding: 24px; text-align: center; color: #8ca0bf; font-size: 0.82rem;">
                            ⏳ Menunggu tim produksi memulai proses pengerjaan.
                        </div>
                    @else
                        @php
                            $latestProgress = $pesanan->progresProduksis->sortByDesc('updated_at')->first();
                            $totalPcs = $pesanan->details->sum('total_item');
                        @endphp
                        @if($latestProgress)
                                <div
                                    style="background: #f4f8fd; border: 1px solid #e1ecfa; border-radius: 12px; padding: 16px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(30, 60, 114, 0.03);">
                                    <div
                                        style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 8px; border-bottom: 1px solid #e1ecfa; padding-bottom: 8px;">
                                        <span
                                            style="font-size: 0.75rem; font-weight: 700; color: #1e3c72; text-transform: uppercase; letter-spacing: 0.5px;">Status
                                            Terakhir Proses Produksi</span>
                                        <span style="font-size: 0.72rem; color: #8ca0bf; font-weight: 600;">Terakhir Diperbarui:
                                            {{ $latestProgress->updated_at->diffForHumans() }}</span>
                                    </div>
                                    <div
                                        style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                                        <div>
                                            <strong style="font-size: 1rem; color: #1a2b4a;">{{ $latestProgress->tahapan }}</strong>
                                        </div>
                                        <div style="display: flex; gap: 6px; align-items: center;">
                                            <span
                                                style="background: #e8f0fd; color: #4A90D9; font-weight: 800; font-size: 0.72rem; padding: 4px 8px; border-radius: 6px;">{{ $latestProgress->jumlah_pcs }}
                                                Pcs</span>
                              @php
                                $latestPct = $totalPcs > 0 ? round(($latestProgress->jumlah_pcs / $totalPcs) * 100, 1) : 0;
                            @endphp
                            <span
                                                style="background: #e6fffa; color: #00a389; font-weight: 800; font-size: 0.72rem; padding: 4px 8px; border-radius: 6px;">{{ $latestPct }}%</span>
                                        </div>
                                    </div>
                                    @if($latestProgress->catatan)
                                        <div
                                            style="font-size: 0.78rem; color: #5a7090; font-style: italic; margin-top: 8px; background: #fff; padding: 8px 12px; border-radius: 6px; border: 1px solid #eef2f6;">
                                            "{{ $latestProgress->catatan }}"
                                        </div>
                                    @endif
                                </div>
                        @endif

                        {{-- Visualisasi Progress Bar per Tahap --}}
                        <div style="background: #fff; border: 1px solid #e1ecfa; border-radius: 12px; padding: 18px; margin-bottom: 24px; box-shadow: 0 2px 8px rgba(30, 60, 114, 0.02);">
                            <div style="font-size: 0.78rem; font-weight: 700; color: #1e3c72; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 14px; border-bottom: 1px dashed #e1ecfa; padding-bottom: 8px;">
                                Visualisasi Distribusi Produksi (Target: {{ $totalPcs }} Pcs)
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 14px;">
                                @foreach($pesanan->progresProduksis as $prog)
                                    @php
                                        $stagePct = $totalPcs > 0 ? round(($prog->jumlah_pcs / $totalPcs) * 100, 1) : 0;
                                        $barColor = '#4A90D9';
                                        if ($prog->tahapan === 'Selesai Produksi') {
                                            $barColor = '#10b981';
                                        } elseif (str_contains(strtolower($prog->tahapan), 'qc') || str_contains(strtolower($prog->tahapan), 'packing')) {
                                            $barColor = '#3b82f6';
                                        } elseif (str_contains(strtolower($prog->tahapan), 'kancing') || str_contains(strtolower($prog->tahapan), 'jahit')) {
                                            $barColor = '#f5a54a';
                                        } elseif (str_contains(strtolower($prog->tahapan), 'potong')) {
                                            $barColor = '#8b5cf6';
                                        }
                                    @endphp
                                    <div>
                                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.76rem; margin-bottom: 5px;">
                                            <span style="font-weight: 600; color: #2d4060;">{{ $prog->tahapan }}</span>
                                            <span style="font-weight: 700; color: #1a2b4a;">{{ $prog->jumlah_pcs }} / {{ $totalPcs }} Pcs ({{ $stagePct }}%)</span>
                                        </div>
                                        <div style="width: 100%; height: 8px; background: #e8eef8; border-radius: 999px; overflow: hidden;">
                                            <div style="width: {{ $stagePct }}%; height: 100%; background: {{ $barColor }}; border-radius: 999px; transition: width 0.3s ease;"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div style="position: relative; padding-left: 28px; margin-top: 14px;">
                            <div style="position: absolute; left: 9px; top: 4px; bottom: 4px; width: 2px; background: #e8eef8;">
                            </div>

                            @foreach($pesanan->progresProduksis as $prog)
                                    <div style="position: relative; margin-bottom: 24px;">
                                        <div
                                            style="position: absolute; left: -24px; top: 4px; width: 12px; height: 12px; border-radius: 50%; border: 2.5px solid #fff; background: {{ $prog->tahapan === 'Selesai Produksi' ? '#10b981' : '#4A90D9' }}; box-shadow: 0 0 0 3px {{ $prog->tahapan === 'Selesai Produksi' ? 'rgba(16,185,129,0.15)' : 'rgba(74,144,217,0.15)' }};">
                                        </div>

                                        <div
                                            style="background: #fdfeff; border: 1px solid #dde8f8; border-radius: 12px; padding: 16px; box-shadow: 0 2px 8px rgba(74, 144, 217, 0.02);">
                                            <div
                                                style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 6px;">
                                                <span
                                                    style="font-weight: 700; color: #1a2b4a; font-size: 0.88rem;">{{ $prog->tahapan }}</span>
                                                <div style="display: flex; gap: 6px; align-items: center;">
                                                    <span
                                                        style="background: #e8f0fd; color: #4A90D9; font-weight: 800; font-size: 0.72rem; padding: 3px 8px; border-radius: 6px;">{{ $prog->jumlah_pcs }}
                                                        Pcs</span>
                                @php
                                    $stagePct = $totalPcs > 0 ? round(($prog->jumlah_pcs / $totalPcs) * 100, 1) : 0;
                                @endphp
                                                    <span
                                                        style="background: #e6fffa; color: #00a389; font-weight: 800; font-size: 0.72rem; padding: 3px 8px; border-radius: 6px;">{{ $stagePct }}%</span>
                                                </div>
                                            </div>

                                            {{-- Progress Bar --}}
                                            @php
                                                $barColor = '#4A90D9';
                                                if ($prog->tahapan === 'Selesai Produksi') {
                                                    $barColor = '#10b981';
                                                } elseif (str_contains(strtolower($prog->tahapan), 'qc') || str_contains(strtolower($prog->tahapan), 'packing')) {
                                                    $barColor = '#3b82f6';
                                                } elseif (str_contains(strtolower($prog->tahapan), 'kancing') || str_contains(strtolower($prog->tahapan), 'jahit')) {
                                                    $barColor = '#f5a54a';
                                                } elseif (str_contains(strtolower($prog->tahapan), 'potong')) {
                                                    $barColor = '#8b5cf6';
                                                }
                                            @endphp
                                            <div style="width: 100%; height: 6px; background: #e8eef8; border-radius: 999px; overflow: hidden; margin-bottom: 10px;">
                                                <div style="width: {{ $stagePct }}%; height: 100%; background: {{ $barColor }}; border-radius: 999px;"></div>
                                            </div>

                                            @if($prog->catatan)
                                                <div style="font-size: 0.78rem; color: #5a7090; margin-bottom: 10px; font-style: italic;">
                                                    "{{ $prog->catatan }}"
                                                </div>
                                            @endif

                                            @if($prog->dokumentasi)
                                                <div style="margin-top: 10px; margin-bottom: 6px;">
                                                    <a href="{{ asset('storage/' . $prog->dokumentasi) }}" target="_blank"
                                                        style="display: inline-block;">
                                                        <img src="{{ asset('storage/' . $prog->dokumentasi) }}"
                                                            style="max-width: 100%; max-height: 250px; border-radius: 8px; border: 1px solid #e2e8f4; object-fit: contain;"
                                                            alt="Dokumentasi">
                                                    </a>
                                                    <div style="font-size: 0.65rem; color: #8ca0bf; margin-top: 4px;">🔍 Klik gambar untuk
                                                        memperbesar</div>
                                                </div>
                                            @endif

                                            <div style="font-size: 0.68rem; color: #8ca0bf; text-align: right; margin-top: 6px;">
                                                Terakhir diupdate: {{ $prog->updated_at->isoFormat('DD MMMM YYYY, HH:mm') }} WIB
                                                ({{ $prog->updated_at->diffForHumans() }})
                                            </div>
                                        </div>
                                    </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

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

            {{-- Invoice Section — Interaktif dengan Tombol Bayar --}}
            @php
                $dp50 = $pesanan->total_harga / 2;
                $lunas50 = $pesanan->total_harga / 2;
                $sp = $pesanan->status_pembayaran ?? 'belum_bayar';
                $termin1 = $pesanan->pembayarans->where('termin_ke', 1)->first();
                $termin2 = $pesanan->pembayarans->where('termin_ke', 2)->first();
                $t1Verified = $termin1 && $termin1->status === 'verified';
                $t1Pending = $termin1 && $termin1->status === 'pending';
                $t2Verified = $termin2 && $termin2->status === 'verified';
                $t2Pending = $termin2 && $termin2->status === 'pending';
                $bisaBayarT1 = !$termin1;
                $bisaBayarT2 = $t1Verified && !$termin2;
            @endphp
            <div class="info-section" style="margin-bottom:24px;">
                <h3>Invoice &amp; Pembayaran Termin</h3>
                @if(session('success'))
                    <div
                        style="background:#ecfdf5;border:1px solid #a7f3d0;border-radius:10px;padding:12px 16px;font-size:.83rem;color:#065f46;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                        <span>✓</span> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div
                        style="background:#fef2f2;border:1px solid #fca5a5;border-radius:10px;padding:12px 16px;font-size:.83rem;color:#991b1b;font-weight:600;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                        <span>✗</span> {{ session('error') }}
                    </div>
                @endif

                <div style="background:#f5f8ff;border:1px solid #dde8f8;border-radius:14px;overflow:hidden;">
                    {{-- Header --}}
                    <div
                        style="background:linear-gradient(135deg,#1e3c72,#2a5298);padding:16px 20px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
                        <div>
                            <div style="color:#fff;font-weight:800;font-size:.95rem;">Invoice #{{ $pesanan->no_pesanan }}
                            </div>
                            <div style="color:#93c5fd;font-size:.75rem;margin-top:2px;">
                                {{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->isoFormat('DD MMMM YYYY') }}
                            </div>
                        </div>
                        <span class="pay-badge-sm pb-{{ $sp }}" style="font-size:.78rem;padding:5px 14px;">
                            @if($sp === 'belum_bayar') Belum Bayar
                            @elseif($sp === 'dp') DP Dibayar
                            @else Lunas @endif
                        </span>
                    </div>

                    <div style="padding:0 20px;">
                        {{-- Total --}}
                        <div
                            style="display:flex;justify-content:space-between;align-items:center;padding:14px 0;border-bottom:1px solid #e2e8f4;">
                            <div>
                                <div style="font-weight:700;color:#1a2b4a;font-size:.85rem;">Total Tagihan</div>
                                <div style="font-size:.72rem;color:#8ca0bf;">{{ $pesanan->details->sum('total_item') }} Pcs
                                    · Alokasi otomatis per item</div>
                            </div>
                            <div style="font-weight:800;font-size:1.05rem;color:#1a2b4a;">Rp
                                {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                            </div>
                        </div>

                        {{-- Termin 1 --}}
                        <div
                            style="display:flex;justify-content:space-between;align-items:center;padding:14px 0;border-bottom:1px solid #e2e8f4;gap:12px;flex-wrap:wrap;">
                            <div style="flex:1;min-width:180px;">
                                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                    <span style="font-weight:700;color:#1a2b4a;font-size:.85rem;">Termin 1 — DP (50%)</span>
                                    @if($t1Verified)
                                        <span
                                            style="background:#ecfdf5;color:#059669;font-size:.65rem;font-weight:700;padding:2px 7px;border-radius:10px;">✓
                                            Terbayar</span>
                                    @elseif($t1Pending)
                                        <span style="background:#fff3e6;color:#d97706;font-size:.65rem;font-weight:700;padding:2px 7px;border-radius:10px;">⏳ Menunggu Pembayaran</span>
                                    @else
                                        <span
                                            style="background:#fef2f2;color:#dc2626;font-size:.65rem;font-weight:700;padding:2px 7px;border-radius:10px;">Belum
                                            Bayar</span>
                                    @endif
                                </div>
                                <div style="font-size:.72rem;color:#8ca0bf;margin-top:3px;">
                                    @if($t1Verified) Dibayar {{ $termin1->tanggal_bayar->isoFormat('DD MMM YYYY') }} ·
                                        {{ ucfirst($termin1->metode_pembayaran) }}
                                    @elseif($t1Pending) Silakan selesaikan pembayaran via Xendit
                                    @else Bayar untuk memulai produksi @endif
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div style="font-weight:800;font-size:.95rem;color:#d97706;">Rp
                                    {{ number_format($dp50, 0, ',', '.') }}
                                </div>
                                @if($bisaBayarT1)
                                    <button onclick="openModalBayar(1, {{ $dp50 }})" class="btn-bayar-termin">💳 Bayar
                                        DP</button>
                                @elseif($t1Pending && $termin1->xendit_invoice_url)
                                    <a href="{{ $termin1->xendit_invoice_url }}" target="_blank" class="btn-bayar-termin" style="background:#2563eb;color:#fff;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">💳 Bayar Sekarang</a>
                                @endif
                            </div>
                        </div>

                        {{-- Termin 2 --}}
                        <div
                            style="display:flex;justify-content:space-between;align-items:center;padding:14px 0;border-bottom:1px solid #e2e8f4;gap:12px;flex-wrap:wrap;">
                            <div style="flex:1;min-width:180px;">
                                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                                    <span style="font-weight:700;color:#1a2b4a;font-size:.85rem;">Termin 2 — Pelunasan
                                        (50%)</span>
                                    @if($t2Verified)
                                        <span
                                            style="background:#ecfdf5;color:#059669;font-size:.65rem;font-weight:700;padding:2px 7px;border-radius:10px;">✓
                                            Terbayar</span>
                                    @elseif($t2Pending)
                                        <span style="background:#fff3e6;color:#d97706;font-size:.65rem;font-weight:700;padding:2px 7px;border-radius:10px;">⏳ Menunggu Pembayaran</span>
                                    @elseif($t1Verified)
                                        <span
                                            style="background:#fff3e6;color:#d97706;font-size:.65rem;font-weight:700;padding:2px 7px;border-radius:10px;">Siap
                                            Dibayar</span>
                                    @else
                                        <span
                                            style="background:#f3f4f6;color:#6b7280;font-size:.65rem;font-weight:700;padding:2px 7px;border-radius:10px;">Terkunci</span>
                                    @endif
                                </div>
                                <div style="font-size:.72rem;color:#8ca0bf;margin-top:3px;">
                                    @if($t2Verified) Dibayar {{ $termin2->tanggal_bayar->isoFormat('DD MMM YYYY') }} ·
                                        {{ ucfirst($termin2->metode_pembayaran) }}
                                    @elseif($t2Pending) Silakan selesaikan pembayaran via Xendit
                                    @elseif($t1Verified) Termin 1 sudah lunas — silakan lanjut pelunasan
                                    @else Selesaikan Termin 1 terlebih dahulu @endif
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:12px;">
                                <div
                                    style="font-weight:800;font-size:.95rem;color:{{ $t2Verified ? '#059669' : '#94a3b8' }};">
                                    Rp {{ number_format($lunas50, 0, ',', '.') }}</div>
                                @if($bisaBayarT2)
                                    <button onclick="openModalBayar(2, {{ $lunas50 }})"
                                        class="btn-bayar-termin btn-bayar-lunas">💳 Bayar Lunas</button>
                                @elseif($t2Pending && $termin2->xendit_invoice_url)
                                    <a href="{{ $termin2->xendit_invoice_url }}" target="_blank" class="btn-bayar-termin btn-bayar-lunas" style="background:#2563eb;color:#fff;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">💳 Bayar Sekarang</a>
                                @endif
                            </div>
                        </div>

                        {{-- Sisa --}}
                        <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 0;">
                            <div style="font-weight:700;color:#dc2626;font-size:.85rem;">Sisa Tagihan</div>
                            <div style="font-weight:800;font-size:1.05rem;color:#dc2626;">Rp
                                {{ number_format($pesanan->sisa_tagihan, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    {{-- Simulasi Alokasi Proporsional --}}
                    <div style="background:#fff;border-top:1px solid #e2e8f4;padding:14px 20px;">
                        <div
                            style="font-size:.72rem;font-weight:700;color:#8ca0bf;text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;">
                            Simulasi Alokasi DP per Item</div>
                        <div style="display:flex;flex-direction:column;gap:6px;">
                            @foreach($pesanan->details as $d)
                                @php
                                    $proporsi = $pesanan->total_harga > 0 ? $d->subtotal / $pesanan->total_harga : 0;
                                    $dpItem = $d->harga_satuan > 0 ? (int) floor($dp50 * $proporsi / $d->harga_satuan) : 0;
                                @endphp
                                <div style="display:flex;justify-content:space-between;align-items:center;font-size:.75rem;">
                                    <span style="color:#1a2b4a;font-weight:600;">{{ $d->produk->nama_produk ?? '-' }} <span
                                            style="background:#e8f0fd;color:#4A90D9;padding:1px 5px;border-radius:4px;font-size:.65rem;font-weight:700;">{{ $d->ukuran }}</span></span>
                                    <span style="color:#6b7e9f;">DP → <strong style="color:#d97706;">{{ $dpItem }} pcs</strong>
                                        / {{ $d->total_item }} pcs terjamin produksi</span>
                                </div>
                            @endforeach
                        </div>
                        <div
                            style="margin-top:10px;font-size:.72rem;color:#8ca0bf;border-top:1px dashed #e2e8f4;padding-top:10px;">
                            💬 Setelah pembayaran dikonfirmasi admin, alokasi pcs di atas otomatis diperbarui.
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODAL Pembayaran --}}
            <div id="modalBayar" class="modal-overlay" onclick="closeModalOnOverlay(event)">
                <div class="modal-box">
                    <div class="modal-header">
                        <div>
                            <h3 id="modalTitle">Bayar Termin 1 — DP</h3>
                            <span id="modalSubtitle">Gateway Pembayaran Otomatis Xendit</span>
                        </div>
                        <button type="button" class="modal-close" onclick="closeModal()">✕</button>
                    </div>
                    <form method="POST" action="{{ route('pelanggan.pesanan.bayar', $pesanan->id) }}">
                        @csrf
                        <input type="hidden" name="termin_ke" id="inputTerminKe" value="1">
                        <div class="modal-body">
                            <div class="nominal-highlight">
                                <span class="nh-label">Nominal yang harus dibayar</span>
                                <span class="nh-value" id="modalNominal">Rp 0</span>
                            </div>
                            
                            <div style="background:#f0f4fb;border-radius:10px;padding:16px;margin-bottom:14px;font-size:.82rem;color:#1a2b4a;line-height:1.5;border:1px solid #dde8f8;">
                                💳 <strong>Pembayaran Instan via Xendit</strong><br>
                                <span style="color:#5a7090;margin-top:6px;display:block;">
                                    Anda akan dialihkan ke halaman pembayaran aman Xendit untuk menyelesaikan transaksi menggunakan Virtual Account (Transfer Bank), QRIS, E-Wallet, atau metode pembayaran lainnya.
                                </span>
                            </div>

                            <div>
                                <label class="form-lbl" for="catatanPelanggan">Catatan (opsional)</label>
                                <textarea class="form-ctrl" id="catatanPelanggan" name="catatan_pelanggan" rows="2"
                                    placeholder="Ada catatan untuk pembayaran ini?"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn-cancel-modal" onclick="closeModal()">Batal</button>
                            <button type="submit" class="btn-submit-bayar" style="background:#2563eb;color:white;border:none;">Bayar Sekarang via Xendit</button>
                        </div>
                    </form>
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

@push('scripts')
    <script>
        function openModalBayar(termin, nominal) {
            const modal = document.getElementById('modalBayar');
            const title = document.getElementById('modalTitle');
            const subtitle = document.getElementById('modalSubtitle');
            const inputTermin = document.getElementById('inputTerminKe');
            const modalNominal = document.getElementById('modalNominal');

            // Format Currency
            const formatted = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(nominal);

            inputTermin.value = termin;
            modalNominal.innerText = formatted;

            if (termin === 1) {
                title.innerText = 'Bayar Termin 1 — DP';
                subtitle.innerText = 'Bayar DP 50% melalui Xendit untuk memulai produksi';
            } else {
                title.innerText = 'Bayar Termin 2 — Pelunasan';
                subtitle.innerText = 'Bayar Pelunasan 50% melalui Xendit untuk pengiriman produk';
            }

            modal.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('modalBayar');
            modal.classList.remove('open');
            document.body.style.overflow = '';

            // Reset Form
            document.getElementById('catatanPelanggan').value = '';
        }

        function closeModalOnOverlay(e) {
            if (e.target.id === 'modalBayar') {
                closeModal();
            }
        }
    </script>
@endpush