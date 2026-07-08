@extends('layouts.main')

@section('title', 'Laporan Pemesanan — SIMAPES')

@push('styles')
    <style>
        /* ── Page Header ── */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1a2b4a;
            line-height: 1.2;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .8rem;
            color: #8ca0bf;
            margin-top: 4px;
        }

        .breadcrumb a {
            color: #8ca0bf;
            text-decoration: none;
            transition: color .15s;
        }

        .breadcrumb a:hover {
            color: #4A90D9;
        }

        .breadcrumb-sep {
            font-size: .7rem;
            opacity: .5;
        }

        .breadcrumb-current {
            color: #4A90D9;
            font-weight: 600;
        }

        /* ── Layout ── */
        .laporan-layout {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 20px;
            align-items: start;
        }

        @media (max-width: 992px) {
            .laporan-layout {
                grid-template-columns: 1fr;
            }
        }

        /* ── Card ── */
        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e8eef8;
            padding: 22px 24px;
            box-shadow: 0 2px 8px rgba(74, 144, 217, .06);
        }

        .card-title {
            font-size: .95rem;
            font-weight: 700;
            color: #1a2b4a;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f4fb;
        }

        /* ── Form Inputs ── */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: .75rem;
            font-weight: 600;
            color: #5a7090;
            margin-bottom: 6px;
        }

        .form-input,
        .form-select {
            width: 100%;
            border: 1.5px solid #dde8f8;
            border-radius: 9px;
            padding: 9px 12px;
            font-size: .82rem;
            font-family: inherit;
            color: #1a2b4a;
            background: #fafdff;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: #4A90D9;
            box-shadow: 0 0 0 3px rgba(74, 144, 217, .12);
            background: #fff;
        }

        .btn-apply {
            background: #4A90D9;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            transition: background .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 10px;
        }

        .btn-apply:hover {
            background: #357abd;
        }

        .btn-print-rep {
            background: #8a63d2;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            transition: background .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-print-rep:hover {
            background: #764ebb;
        }

        /* ── Table ── */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-top: 10px;
        }

        .laporan-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .laporan-table th {
            background: #f5f8ff;
            color: #8ca0bf;
            font-weight: 600;
            font-size: .75rem;
            text-transform: uppercase;
            letter-spacing: .5px;
            padding: 12px 14px;
            border-bottom: 1.5px solid #dde8f8;
        }

        .laporan-table td {
            padding: 14px;
            border-bottom: 1px solid #f0f4fb;
            font-size: .82rem;
            color: #2d4060;
            vertical-align: middle;
        }

        .laporan-table tbody tr:hover td {
            background: #fbfdff;
        }

        .badge-status {
            display: inline-block;
            font-size: .68rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 8px;
            text-transform: uppercase;
        }

        .badge-status.pending {
            background: #f3f4f6;
            color: #4b5563;
        }

        .badge-status.diproses {
            background: #fff3e6;
            color: #f5a54a;
        }

        .badge-status.dikerjakan {
            background: #eaf3fc;
            color: #4A90D9;
        }

        .badge-status.selesai {
            background: #e8f8ee;
            color: #34c472;
        }

        .badge-status.batal {
            background: #fdf2f2;
            color: #ef4444;
        }

        /* ── Summary Cards ── */
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }

        .sum-card {
            background: #fcfdfe;
            border: 1px solid #eef3fb;
            border-radius: 12px;
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sum-label {
            font-size: .7rem;
            font-weight: 600;
            color: #8ca0bf;
            text-transform: uppercase;
        }

        .sum-value {
            font-size: 1.35rem;
            font-weight: 800;
            color: #1a2b4a;
        }

        /* ── Pagination ── */
        .pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 18px;
        }

        .page-btn {
            width: 32px;
            height: 32px;
            border: 1px solid #dde8f8;
            border-radius: 8px;
            background: #fff;
            color: #4A90D9;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: inherit;
            transition: background .15s, color .15s;
            text-decoration: none;
        }

        .page-btn:hover {
            background: #4A90D9;
            color: #fff;
            border-color: #4A90D9;
        }

        .page-btn.active {
            background: #4A90D9;
            color: #fff;
            border-color: #4A90D9;
        }

        .page-btn:disabled {
            opacity: .4;
            cursor: not-allowed;
        }

        .page-dots {
            color: #8ca0bf;
            font-size: .8rem;
            font-weight: 600;
            padding: 0 4px;
        }
    </style>
@endpush

@section('content')
    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Laporan Pemesanan</h1>
            <nav class="breadcrumb" aria-label="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="breadcrumb-sep">›</span>
                <span class="breadcrumb-current">Laporan</span>
            </nav>
        </div>
    </div>

    {{-- ── Main Layout ── --}}
    <div class="laporan-layout">

        {{-- ── Sidebar Filter ── --}}
        <div class="card">
            <div class="card-title">Filter Laporan</div>
            <form method="GET" action="{{ route('admin.laporan.index') }}">
                <div class="form-group">
                    <label class="form-label" for="start_date">Tanggal Mulai</label>
                    <input class="form-input" type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                        required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="end_date">Tanggal Selesai</label>
                    <input class="form-input" type="date" name="end_date" id="end_date" value="{{ $endDate }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="sekolah">Per Sekolah / Instansi</label>
                    <select class="form-select" name="sekolah" id="sekolah">
                        <option value="semua" {{ $sekolahSelected == 'semua' ? 'selected' : '' }}>Semua Sekolah</option>
                        @foreach($listSekolah as $sch)
                            <option value="{{ $sch }}" {{ $sekolahSelected == $sch ? 'selected' : '' }}>{{ $sch }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="progress">Progress Kerja</label>
                    <select class="form-select" name="progress" id="progress">
                        <option value="semua" {{ $progressSelected == 'semua' ? 'selected' : '' }}>Semua Progress</option>
                        <option value="sedang_berjalan" {{ $progressSelected == 'sedang_berjalan' ? 'selected' : '' }}>Sedang Berjalan / Proses</option>
                        <option value="selesai" {{ $progressSelected == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="batal" {{ $progressSelected == 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="keuangan">Status Keuangan</label>
                    <select class="form-select" name="keuangan" id="keuangan">
                        <option value="semua" {{ $keuanganSelected == 'semua' ? 'selected' : '' }}>Semua Keuangan</option>
                        <option value="belum_lunas" {{ $keuanganSelected == 'belum_lunas' ? 'selected' : '' }}>Sisa Tagihan (Belum Lunas)</option>
                        <option value="lunas" {{ $keuanganSelected == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>

                <button type="submit" class="btn-apply">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                    </svg>
                    Terapkan Filter
                </button>
            </form>

            <a href="{{ route('admin.laporan.cetak', ['start_date' => $startDate, 'end_date' => $endDate, 'sekolah' => $sekolahSelected, 'progress' => $progressSelected, 'keuangan' => $keuanganSelected]) }}"
                target="_blank" class="btn-print-rep" style="margin-bottom: 10px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9" />
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                    <rect x="6" y="14" width="12" height="8" />
                </svg>
                Cetak Laporan PDF
            </a>

            <a href="{{ route('admin.laporan.excel', ['start_date' => $startDate, 'end_date' => $endDate, 'sekolah' => $sekolahSelected, 'progress' => $progressSelected, 'keuangan' => $keuanganSelected]) }}"
                class="btn-print-rep" style="background: #10b981;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Ekspor Laporan Excel
            </a>
        </div>

        {{-- ── Data Table & Summary ── --}}
        <div class="card">
            <div class="card-title">Daftar Transaksi</div>

            <div class="summary-grid">
                <div class="sum-card">
                    <span class="sum-label">Total Pesanan</span>
                    <span class="sum-value">{{ $pesanan->total() }} Transaksi</span>
                </div>
                <div class="sum-card">
                    <span class="sum-label">Total Pendapatan</span>
                    <span class="sum-value" style="color: #34c472;">Rp
                        {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="laporan-table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th style="text-align: center;">Status</th>
                            <th style="text-align: right;">Total Tagihan</th>
                            <th style="text-align: right;">Terbayar</th>
                            <th style="text-align: right;">Sisa Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pesanan as $p)
                            <tr>
                                <td style="font-weight: 700; color: #4A90D9;">{{ $p->no_pesanan }}</td>
                                <td>
                                    <div style="font-weight: 600;">{{ $p->user->name }}</div>
                                    <div style="font-size: 0.72rem; color: #8ca0bf;">{{ $p->user->nama_sekolah ?? '-' }}</div>
                                </td>
                                <td>{{ $p->tanggal_pesanan ? $p->tanggal_pesanan->isoFormat('DD MMM YYYY') : '-' }}</td>
                                <td style="text-align: center;">
                                    <span class="badge-status {{ $p->status }}">{{ $p->status }}</span>
                                </td>
                                <td style="text-align: right; font-weight: 700;">Rp
                                    {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                                <td style="text-align: right; font-weight: 600; color: #10b981;">Rp
                                    {{ number_format($p->total_terbayar ?? 0, 0, ',', '.') }}</td>
                                <td style="text-align: right; font-weight: 700; color: {{ ($p->sisa_tagihan ?? 0) > 0 ? '#ef4444' : '#10b981' }};">Rp
                                    {{ number_format($p->sisa_tagihan ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; color: #8ca0bf; padding: 30px;">
                                    Tidak ada transaksi pesanan dalam periode dan filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($pesanan->hasPages())
            <div class="pagination-wrap">
                @if($pesanan->onFirstPage())
                    <button class="page-btn" disabled>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </button>
                @else
                    <a href="{{ $pesanan->previousPageUrl() }}" class="page-btn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                    </a>
                @endif

                {{-- Page Numbers (Responsive Sliding Window) --}}
                @php
                    $start = max(1, $pesanan->currentPage() - 2);
                    $end = min($pesanan->lastPage(), $pesanan->currentPage() + 2);
                @endphp

                @if($start > 1)
                    <a href="{{ $pesanan->url(1) }}" class="page-btn">1</a>
                    @if($start > 2)
                        <span class="page-dots">...</span>
                    @endif
                @endif

                @for($page = $start; $page <= $end; $page++)
                    <a href="{{ $pesanan->url($page) }}" class="page-btn {{ $page == $pesanan->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endfor

                @if($end < $pesanan->lastPage())
                    @if($end < $pesanan->lastPage() - 1)
                        <span class="page-dots">...</span>
                    @endif
                    <a href="{{ $pesanan->url($pesanan->lastPage()) }}" class="page-btn">{{ $pesanan->lastPage() }}</a>
                @endif

                @if($pesanan->hasMorePages())
                    <a href="{{ $pesanan->nextPageUrl() }}" class="page-btn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </a>
                @else
                    <button class="page-btn" disabled>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                    </button>
                @endif
            </div>
            @endif
        </div>

    </div>
@endsection