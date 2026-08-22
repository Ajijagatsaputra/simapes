@extends('layouts.main')

@section('title', 'Data Pesanan — SIMAPES')

@push('styles')
<style>
    /* ── Page Header ── */
    .page-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 24px; }
    .page-title { font-size: 1.75rem; font-weight: 800; color: #1a2b4a; line-height: 1.2; }
    .breadcrumb { display: flex; align-items: center; gap: 6px; font-size: .8rem; color: #8ca0bf; margin-top: 4px; }
    .breadcrumb a { color: #8ca0bf; text-decoration: none; transition: color .15s; }
    .breadcrumb a:hover { color: #4A90D9; }
    .breadcrumb-sep { font-size: .7rem; opacity: .5; }
    .breadcrumb-current { color: #4A90D9; font-weight: 600; }
    .page-date { display: flex; align-items: center; gap: 8px; font-size: .85rem; color: #6b7e9f; background: #fff; border: 1px solid #e2e8f4; border-radius: 10px; padding: 8px 14px; }

    /* ── Stat Bar & Grid ── */
    .stats-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 16px; margin-bottom: 22px; }
    .stat-bar { background: #fff; border: 1px solid #e8eef8; border-radius: 16px; padding: 18px 22px; display: flex; align-items: center; gap: 16px; margin-bottom: 0; box-shadow: 0 2px 8px rgba(74,144,217,.06); }
    .stat-bar-icon { width: 50px; height: 50px; background: #eaf3fc; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #4A90D9; flex-shrink: 0; }
    .stat-bar-label { font-size: .75rem; color: #8ca0bf; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
    .stat-bar-value { font-size: 1.7rem; font-weight: 800; color: #1a2b4a; line-height: 1.1; }
    .stat-bar-desc  { font-size: .72rem; color: #a0aec0; }
    .stat-bar-link { text-decoration: none; display: block; transition: transform 0.2s, box-shadow 0.2s; }
    .stat-bar-link:hover { transform: translateY(-3px); }
    .stat-bar-link:hover .stat-bar { box-shadow: 0 6px 16px rgba(74, 144, 217, 0.12); border-color: #bcd4f5; }
    @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 992px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .stats-grid { grid-template-columns: 1fr; } }

    /* ── Card ── */
    .card { background: #fff; border-radius: 16px; border: 1px solid #e8eef8; padding: 22px 24px; box-shadow: 0 2px 8px rgba(74,144,217,.06); }

    /* ── Toolbar ── */
    .table-toolbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 10px; }
    .table-toolbar-left { font-size: .95rem; font-weight: 700; color: #1a2b4a; }
    .toolbar-right { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    
    .search-wrap { display: flex; align-items: center; background: #f5f8ff; border: 1px solid #dde8f8; border-radius: 10px; padding: 6px 12px; gap: 8px; }
    .search-wrap input { border: none; background: transparent; outline: none; font-size: .8rem; color: #1a2b4a; width: 140px; font-family: inherit; }
    .search-wrap input::placeholder { color: #aab9d0; }
    .search-wrap svg { color: #8ca0bf; flex-shrink: 0; }

    .filter-select { border: 1px solid #dde8f8; background: #f5f8ff; border-radius: 10px; padding: 6px 10px; font-size: .8rem; color: #5a7090; outline: none; font-family: inherit; cursor: pointer; }

    /* ── Table ── */
    .data-table { width: 100%; border-collapse: collapse; font-size: .8rem; }
    .data-table thead th { background: #f5f8ff; color: #8ca0bf; font-weight: 600; font-size: .72rem; text-transform: uppercase; letter-spacing: .4px; padding: 10px 12px; text-align: left; border-bottom: 1px solid #e8eef8; white-space: nowrap; }
    .data-table thead th:first-child { border-radius: 8px 0 0 8px; }
    .data-table thead th:last-child  { border-radius: 0 8px 8px 0; text-align: center; }
    .data-table tbody td { padding: 12px 12px; color: #2d4060; border-bottom: 1px solid #f6f9fd; vertical-align: middle; }
    .data-table tbody tr:last-child td { border-bottom: none; }
    .data-table tbody tr:hover td { background: #fafcff; }
    .data-table td.center { text-align: center; }
    .row-number { color: #8ca0bf; font-weight: 600; }
    
    /* ── Status Inline Select ── */
    .status-select { border-radius: 20px; font-size: .72rem; font-weight: 700; padding: 4px 8px; border: none; outline: none; cursor: pointer; font-family: inherit; appearance: none; -webkit-appearance: none; text-align: center; transition: opacity .15s; }
    .status-select:hover { opacity: .85; }
    .status-select.status-pending { background: #f3f4f6; color: #4b5563; }
    .status-select.status-diproses { background: #fff3e6; color: #f5a54a; }
    .status-select.status-dikerjakan { background: #e8f0fd; color: #4A90D9; }
    .status-select.status-selesai { background: #e8f8ee; color: #34c472; }
    .status-select.status-batal { background: #fee2e2; color: #dc2626; }
    .status-select.loading { opacity: .5; cursor: wait; }

    /* ── Product List Item (Table) ── */
    .item-produk-row { font-size: 0.76rem; margin-bottom: 4px; padding-bottom: 4px; border-bottom: 1px dashed #e8eef8; }
    .item-produk-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .item-badge-ukuran { background: #f0f4fb; border-radius: 4px; padding: 1px 4px; font-weight: 700; font-size: 0.65rem; color: #5a7090; }

    /* ── Aksi ── */
    .aksi-wrap { display: flex; gap: 6px; justify-content: center; }
    .btn-edit, .btn-batal-aksi, .btn-status, .btn-print, .btn-bayar, .btn-progres { width: 30px; height: 30px; border: none; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: opacity .15s, transform .15s; text-decoration: none; }
    .btn-status { background: #e8f0fd; color: #4A90D9; }
    .btn-edit  { background: #4A90D9; color: #fff; }
    .btn-batal-aksi { background: #ef4444; color: #fff; }
    .btn-print { background: #8a63d2; color: #fff; text-decoration: none; }
    .btn-bayar { background: #10b981; color: #fff; text-decoration: none; }
    .btn-progres { background: #eab308; color: #fff; }
    .btn-progres.disabled { background: #e2e8f4; color: #a0aec0; cursor: not-allowed; pointer-events: none; }
    .btn-batal-aksi:hover, .btn-status:hover, .btn-print:hover, .btn-bayar:hover, .btn-progres:hover { opacity: .85; transform: scale(1.08); }

    /* Payment Status */
    .pay-status { display:inline-flex; padding:3px 7px; border-radius:12px; font-size:.65rem; font-weight:700; }
    .ps-belum_bayar { background:#fef2f2; color:#dc2626; }
    .ps-dp { background:#fff3e6; color:#d97706; }
    .ps-lunas { background:#ecfdf5; color:#059669; }

    /* ── Empty State ── */
    .empty-state { text-align: center; padding: 40px 20px; color: #a0aec0; }
    .empty-state svg { margin-bottom: 12px; opacity: .4; }
    .empty-state p { font-size: .85rem; }

    /* ── Pagination ── */
    .pagination-wrap { display: flex; align-items: center; justify-content: center; gap: 6px; margin-top: 18px; }
    .page-btn { width: 32px; height: 32px; border: 1px solid #dde8f8; border-radius: 8px; background: #fff; color: #4A90D9; font-size: .8rem; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; font-family: inherit; transition: background .15s, color .15s; text-decoration: none; }
    .page-btn:hover { background: #4A90D9; color: #fff; border-color: #4A90D9; }
    .page-btn.active { background: #4A90D9; color: #fff; border-color: #4A90D9; }
    .page-btn:disabled { opacity: .4; cursor: not-allowed; }
    .page-dots { color: #8ca0bf; font-size: .8rem; font-weight: 600; padding: 0 4px; }
</style>
@endpush

@section('content')

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Data Pesanan</h1>
            <nav class="breadcrumb" aria-label="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="breadcrumb-sep">›</span>
                <span class="breadcrumb-current">Pesanan</span>
            </nav>
        </div>
        <div class="page-date">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8"  y1="2" x2="8"  y2="6"/>
                <line x1="3"  y1="10" x2="21" y2="10"/>
            </svg>
            {{ \Carbon\Carbon::now()->isoFormat('DD MMM YYYY') }}
        </div>
    </div>

    {{-- ── Stats Grid ── --}}
    <div class="stats-grid">
        {{-- Card 1: Total Transaksi --}}
        <a href="{{ route('admin.pesanan.index', ['status' => 'semua']) }}" class="stat-bar-link">
            <div class="stat-bar">
                <div class="stat-bar-icon" style="background: #e8f0fd; color: #4A90D9;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                </div>
                <div>
                    <div class="stat-bar-label">Total Pesanan</div>
                    <div class="stat-bar-value">{{ $totalPesanan }}</div>
                    <div class="stat-bar-desc">Semua status pesanan</div>
                </div>
            </div>
        </a>

        {{-- Card 2: Menunggu Persetujuan --}}
        <a href="{{ route('admin.pesanan.index', ['status' => 'pending']) }}" class="stat-bar-link">
            <div class="stat-bar">
                <div class="stat-bar-icon" style="background: #f3f4f6; color: #4b5563;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
                <div>
                    <div class="stat-bar-label">Menunggu Persetujuan</div>
                    <div class="stat-bar-value">{{ $totalPending }}</div>
                    <div class="stat-bar-desc">Perlu verifikasi</div>
                </div>
            </div>
        </a>

        {{-- Card 3: Diproses --}}
        <a href="{{ route('admin.pesanan.index', ['status' => 'diproses']) }}" class="stat-bar-link">
            <div class="stat-bar">
                <div class="stat-bar-icon" style="background: #fff3e6; color: #f5a54a;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
                <div>
                    <div class="stat-bar-label">Diproses</div>
                    <div class="stat-bar-value">{{ $totalDiproses }}</div>
                    <div class="stat-bar-desc">Pesanan baru masuk</div>
                </div>
            </div>
        </a>

        {{-- Card 4: Dikerjakan --}}
        <a href="{{ route('admin.pesanan.index', ['status' => 'dikerjakan']) }}" class="stat-bar-link">
            <div class="stat-bar">
                <div class="stat-bar-icon" style="background: #eaf3fc; color: #4A90D9;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z"/>
                    </svg>
                </div>
                <div>
                    <div class="stat-bar-label">Dikerjakan</div>
                    <div class="stat-bar-value">{{ $totalDikerjakan }}</div>
                    <div class="stat-bar-desc">Sedang diproduksi</div>
                </div>
            </div>
        </a>

        {{-- Card 5: Selesai --}}
        <a href="{{ route('admin.pesanan.index', ['status' => 'selesai']) }}" class="stat-bar-link">
            <div class="stat-bar">
                <div class="stat-bar-icon" style="background: #e8f8ee; color: #34c472;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                </div>
                <div>
                    <div class="stat-bar-label">Selesai</div>
                    <div class="stat-bar-value">{{ $totalSelesai }}</div>
                    <div class="stat-bar-desc">Rampung & diambil</div>
                </div>
            </div>
        </a>
    </div>

    {{-- ── Main Layout (full-width) ── --}}
    <div>

        {{-- ── Tabel Pesanan ── --}}
        <div class="card">
            <div class="table-toolbar">
                <span class="table-toolbar-left">Daftar Transaksi Pesanan</span>
                <div class="toolbar-right">
                    <form method="GET" action="{{ route('admin.pesanan.index') }}" style="display:flex; gap:10px; align-items:center;">
                        <div class="search-wrap">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Pesanan">
                        </div>
                        
                        <select name="status" class="filter-select" onchange="this.form.submit()">
                            <option value="semua" {{ request('status') == 'semua' ? 'selected' : '' }}>Status Semua</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="dikerjakan" {{ request('status') == 'dikerjakan' ? 'selected' : '' }}>Dikerjakan</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal / Ditolak</option>
                        </select>

                        <input type="date" name="tanggal" class="filter-select" value="{{ request('tanggal') }}" onchange="this.form.submit()">
                        
                        @if(request()->anyFilled(['search', 'status', 'tanggal']))
                            <a href="{{ route('admin.pesanan.index') }}" class="btn-batal" style="padding: 6px 12px; font-size: 0.8rem; text-decoration: none; border-radius: 10px;">Reset</a>
                        @endif
                    </form>

                </div>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:48px">No.</th>
                        <th>No Pesanan</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Daftar Item Produk</th>
                        <th>Total Harga</th>
                        <th style="width:120px" class="center">Status</th>
                        <th style="width:80px" class="center">Bayar</th>
                        <th style="width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pesanan as $index => $p)
                    <tr>
                        <td class="row-number">{{ $pesanan->firstItem() + $index }}</td>
                        <td style="font-weight: 700; color: #4A90D9;">{{ $p->no_pesanan }}</td>
                        <td>{{ $p->tanggal_pesanan ? $p->tanggal_pesanan->isoFormat('DD MMM YYYY') : '-' }}</td>
                        <td>
                            <div>
                                <div style="font-weight:600;">{{ $p->user->name ?? 'Pelanggan Terhapus' }}</div>
                                <div style="font-size:0.75rem; color:#8ca0bf;">{{ $p->user->no_whatsapp ?? '-' }}</div>
                            </div>
                        </td>
                        <td>
                            @foreach($p->details as $d)
                                <div class="item-produk-row" style="display: flex; gap: 8px; align-items: start; margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px dashed #e8eef8;">
                                    @if($d->path_gambar)
                                        <div style="flex-shrink: 0; cursor: pointer;" onclick="openLightbox('{{ asset('storage/' . $d->path_gambar) }}')">
                                            <img src="{{ asset('storage/' . $d->path_gambar) }}" alt="Preview" style="width: 42px; height: 42px; object-fit: cover; border-radius: 6px; border: 1px solid #c5d8f5; box-shadow: 0 1px 4px rgba(0,0,0,0.05); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                        </div>
                                    @endif
                                    <div style="flex-grow: 1;">
                                        <div style="display: flex; justify-content: space-between; align-items: start;">
                                            <div>
                                                <strong>{{ $d->produk->nama_produk ?? 'Produk Terhapus' }}</strong>
                                                <span class="item-badge-ukuran" style="margin-left: 4px;">{{ $d->ukuran }}</span>
                                                <span style="color: #6b7e9f; margin-left: 4px;">x{{ $d->total_item }}</span>
                                            </div>
                                            <span style="color:#8ca0bf; font-weight: 600; margin-left: 10px;">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
                                        </div>
                                        @if($d->catatan)
                                            <div style="font-size: 0.72rem; color: #d97706; background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 2px 6px; margin-top: 4px; display: inline-flex; align-items: center; gap: 4px; max-width: 100%; word-break: break-word;">
                                                <span>📝</span>
                                                <span>{{ $d->catatan }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </td>
                        <td class="harga-cell" style="font-weight:800; color: #1a2b4a;">
                            Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="center">
                            <select
                                class="status-select status-{{ $p->status }}"
                                data-id="{{ $p->id }}"
                                data-current="{{ $p->status }}"
                                data-url="{{ route('admin.pesanan.updateStatus', $p->id) }}"
                                onchange="updateStatusAjax(this)">
                                <option value="pending"    {{ $p->status === 'pending'    ? 'selected' : '' }}>Pending</option>
                                <option value="diproses"   {{ $p->status === 'diproses'   ? 'selected' : '' }}>Diproses</option>
                                <option value="dikerjakan" {{ $p->status === 'dikerjakan' ? 'selected' : '' }}>Dikerjakan</option>
                                <option value="selesai"    {{ $p->status === 'selesai'    ? 'selected' : '' }}>Selesai</option>
                                <option value="batal"      {{ $p->status === 'batal'      ? 'selected' : '' }}>Batal</option>
                            </select>
                        </td>
                        <td class="center">
                            <span class="pay-status ps-{{ $p->status_pembayaran ?? 'belum_bayar' }}">
                                {{ ($p->status_pembayaran ?? 'belum_bayar') === 'belum_bayar' ? 'Belum' : (($p->status_pembayaran ?? '') === 'dp' ? 'DP' : 'Lunas') }}
                            </span>
                        </td>
                        <td>
                            <div class="aksi-wrap">
                                <a href="{{ route('admin.pesanan.nota', $p->id) }}" target="_blank" class="btn-print" title="Cetak Nota">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 6 2 18 2 18 9"/>
                                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                        <rect x="6" y="14" width="12" height="8"/>
                                    </svg>
                                </a>

                                @if($p->status !== 'batal')
                                    <button type="button" class="btn-batal-aksi" title="Batal / Tolak Pesanan"
                                        data-nama="{{ $p->no_pesanan }}"
                                        data-url="{{ route('admin.pesanan.updateStatus', $p->id) }}"
                                        onclick="confirmBatalPesanan(this)">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="18" y1="6" x2="6" y2="18"/>
                                            <line x1="6" y1="6" x2="18" y2="18"/>
                                        </svg>
                                    </button>
                                @endif

                                <a href="{{ route('admin.pesanan.pembayaran', $p->id) }}" class="btn-bayar" title="Kelola Pembayaran">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                                        <line x1="1" y1="10" x2="23" y2="10"/>
                                    </svg>
                                </a>

                                 @if(in_array($p->status, ['dikerjakan', 'selesai']))
                                     <a href="{{ route('admin.pesanan.progres', $p->id) }}" class="btn-progres" title="Kelola Progres Produksi">
                                         <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                             <circle cx="12" cy="12" r="3"></circle>
                                             <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                         </svg>
                                     </a>
                                 @else
                                     <button class="btn-progres disabled" disabled title="Progres hanya dapat dikelola saat status Dikerjakan atau Selesai">
                                         <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                             <circle cx="12" cy="12" r="3"></circle>
                                             <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                         </svg>
                                     </button>
                                 @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                </svg>
                                <p>Belum ada data pesanan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

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

    {{-- Lightbox Modal --}}
    <div id="lightboxModal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.75); z-index: 99999; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.25s ease;" onclick="closeLightbox()">
        <div style="position: relative; max-width: 90%; max-height: 90%;" onclick="event.stopPropagation()">
            <button onclick="closeLightbox()" style="position: absolute; top: -40px; right: 0; background: none; border: none; color: #fff; font-size: 30px; font-weight: bold; cursor: pointer;">&times;</button>
            <img id="lightboxImage" src="" style="max-width: 100%; max-height: 80vh; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.3); border: 2px solid #fff;">
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // ── Inline Status Dropdown AJAX ──────────────────────────────────
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function updateStatusAjax(selectEl) {
        const newStatus = selectEl.value;
        const previousStatus = selectEl.dataset.current || 'pending';
        const url = selectEl.dataset.url;

        // Update visual class immediately
        selectEl.className = 'status-select loading';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-HTTP-Method-Override': 'PATCH',
            },
            body: JSON.stringify({ status: newStatus, _method: 'PATCH' }),
        })
        .then(async res => {
            const data = await res.json();
            if (res.ok && data.success) {
                selectEl.className = 'status-select status-' + newStatus;
                selectEl.dataset.current = newStatus;
                showToast(data.message, 'success');
            } else {
                showToast(data.message || 'Gagal mengubah status pesanan.', 'error');
                selectEl.value = previousStatus;
                selectEl.className = 'status-select status-' + previousStatus;
            }
        })
        .catch(() => {
            showToast('Terjadi kesalahan saat menghubungi server.', 'error');
            selectEl.value = previousStatus;
            selectEl.className = 'status-select status-' + previousStatus;
        });
    }

    function confirmBatalPesanan(btn) {
        const noPesanan = btn.dataset.nama;
        const url = btn.dataset.url;

        confirmBatalAksi(noPesanan, function () {
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'PATCH',
                },
                body: JSON.stringify({ status: 'batal', _method: 'PATCH' }),
            })
            .then(async res => {
                const data = await res.json();
                if (res.ok && data.success) {
                    showToast(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    showToast(data.message || 'Gagal membatalkan pesanan.', 'error');
                }
            })
            .catch(() => {
                showToast('Terjadi kesalahan saat menghubungi server.', 'error');
            });
        });
    }
    function openLightbox(src) {
        const modal = document.getElementById('lightboxModal');
        const img = document.getElementById('lightboxImage');
        img.src = src;
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.style.opacity = '1';
        }, 10);
    }

    function closeLightbox() {
        const modal = document.getElementById('lightboxModal');
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.style.display = 'none';
        }, 250);
    }
</script>
@endpush
