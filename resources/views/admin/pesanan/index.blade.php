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
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px; }
    .stat-bar { background: #fff; border: 1px solid #e8eef8; border-radius: 16px; padding: 18px 22px; display: flex; align-items: center; gap: 16px; margin-bottom: 0; box-shadow: 0 2px 8px rgba(74,144,217,.06); }
    .stat-bar-icon { width: 50px; height: 50px; background: #eaf3fc; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #4A90D9; flex-shrink: 0; }
    .stat-bar-label { font-size: .75rem; color: #8ca0bf; font-weight: 600; text-transform: uppercase; letter-spacing: .5px; }
    .stat-bar-value { font-size: 1.7rem; font-weight: 800; color: #1a2b4a; line-height: 1.1; }
    .stat-bar-desc  { font-size: .72rem; color: #a0aec0; }
    @media (max-width: 992px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .stats-grid { grid-template-columns: 1fr; } }

    /* ── Layout ── */
    .pesanan-layout { display: grid; grid-template-columns: 1fr 340px; gap: 20px; align-items: start; }
    @media (max-width: 1200px) { .pesanan-layout { grid-template-columns: 1fr; } }

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

    .btn-tambah { display: inline-flex; align-items: center; gap: 6px; background: #4A90D9; color: #fff; border: none; border-radius: 10px; padding: 8px 16px; font-size: .82rem; font-weight: 700; cursor: pointer; font-family: inherit; transition: background .2s, transform .15s; }
    .btn-tambah:hover { background: #3a7bc8; transform: translateY(-1px); }

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
    
    /* ── Status Badges ── */
    .status-badge { display: inline-flex; align-items: center; justify-content: center; padding: 4px 10px; border-radius: 20px; font-size: .72rem; font-weight: 700; text-transform: capitalize; }
    .status-diproses { background: #fff3e6; color: #f5a54a; }
    .status-dikerjakan { background: #e8f0fd; color: #4A90D9; }
    .status-selesai { background: #e8f8ee; color: #34c472; }

    /* ── Product List Item (Table) ── */
    .item-produk-row { font-size: 0.76rem; margin-bottom: 4px; padding-bottom: 4px; border-bottom: 1px dashed #e8eef8; }
    .item-produk-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .item-badge-ukuran { background: #f0f4fb; border-radius: 4px; padding: 1px 4px; font-weight: 700; font-size: 0.65rem; color: #5a7090; }

    /* ── Aksi ── */
    .aksi-wrap { display: flex; gap: 6px; justify-content: center; }
    .btn-edit, .btn-hapus, .btn-status, .btn-print, .btn-bayar { width: 30px; height: 30px; border: none; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: opacity .15s, transform .15s; }
    .btn-status { background: #e8f0fd; color: #4A90D9; }
    .btn-edit  { background: #4A90D9; color: #fff; }
    .btn-hapus { background: #e05a5a; color: #fff; }
    .btn-print { background: #8a63d2; color: #fff; text-decoration: none; }
    .btn-bayar { background: #10b981; color: #fff; text-decoration: none; }
    .btn-edit:hover, .btn-hapus:hover, .btn-status:hover, .btn-print:hover, .btn-bayar:hover { opacity: .85; transform: scale(1.08); }

    /* Payment Status */
    .pay-status { display:inline-flex; padding:3px 7px; border-radius:12px; font-size:.65rem; font-weight:700; }
    .ps-belum_bayar { background:#fef2f2; color:#dc2626; }
    .ps-dp { background:#fff3e6; color:#d97706; }
    .ps-lunas { background:#ecfdf5; color:#059669; }

    /* ── Form Panel ── */
    .form-panel .panel-title { font-size: .95rem; font-weight: 700; color: #1a2b4a; margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid #f0f4fb; }
    .form-group { margin-bottom: 14px; }
    .form-label { display: block; font-size: .75rem; font-weight: 600; color: #5a7090; margin-bottom: 5px; }
    .form-input, .form-textarea, .form-select { width: 100%; border: 1.5px solid #dde8f8; border-radius: 9px; padding: 8px 11px; font-size: .82rem; font-family: inherit; color: #1a2b4a; background: #fafdff; outline: none; transition: border-color .15s, box-shadow .15s; }
    .form-input:focus, .form-textarea:focus, .form-select:focus { border-color: #4A90D9; box-shadow: 0 0 0 3px rgba(74,144,217,.12); background: #fff; }
    
    /* ── Dynamic Product Items in Form ── */
    .items-container { border: 1.5px solid #e8f0fc; border-radius: 12px; padding: 12px; background: #fafdff; margin-bottom: 16px; }
    .item-form-row { display: grid; grid-template-columns: 1.2fr 0.6fr 0.6fr auto; gap: 8px; align-items: center; margin-bottom: 10px; padding-bottom: 10px; border-bottom: 1px solid #eef3fb; }
    .item-form-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .btn-remove-item { background: #fee2e2; color: #ef4444; border: none; width: 28px; height: 28px; border-radius: 6px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: background .15s; }
    .btn-remove-item:hover { background: #fca5a5; }
    .btn-add-item { background: #eaf3fc; color: #4A90D9; border: 1px dashed #4A90D9; border-radius: 8px; width: 100%; padding: 8px; font-size: 0.78rem; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px; transition: background .15s; margin-top: 8px; }
    .btn-add-item:hover { background: #ddeefc; }

    .form-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 18px; padding-top: 14px; border-top: 1px solid #f0f4fb; }
    .btn-batal { padding: 8px 18px; background: #f0f4fb; border: 1px solid #dde8f8; border-radius: 9px; color: #5a7090; font-size: .82rem; font-weight: 600; cursor: pointer; font-family: inherit; transition: background .15s; }
    .btn-batal:hover { background: #e2e8f4; }
    .btn-simpan { padding: 8px 20px; background: #4A90D9; border: none; border-radius: 9px; color: #fff; font-size: .82rem; font-weight: 700; cursor: pointer; font-family: inherit; transition: background .2s; }
    .btn-simpan:hover { background: #3a7bc8; }

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

    @media (max-width: 576px) {
        .item-form-row {
            grid-template-columns: 1fr !important;
            gap: 10px !important;
            position: relative;
            padding-bottom: 16px !important;
            border-bottom: 1.5px dashed #dde8f8 !important;
        }
        .item-form-row:last-child {
            border-bottom: none !important;
            padding-bottom: 0 !important;
        }
        .btn-remove-item {
            position: absolute;
            top: 0;
            right: 0;
            z-index: 5;
        }
    }
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

        {{-- Card 2: Diproses --}}
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

        {{-- Card 3: Dikerjakan --}}
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

        {{-- Card 4: Selesai --}}
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
    </div>

    {{-- ── Main Layout ── --}}
    <div class="pesanan-layout">

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
                            <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="dikerjakan" {{ request('status') == 'dikerjakan' ? 'selected' : '' }}>Dikerjakan</option>
                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>

                        <input type="date" name="tanggal" class="filter-select" value="{{ request('tanggal') }}" onchange="this.form.submit()">
                        
                        @if(request()->anyFilled(['search', 'status', 'tanggal']))
                            <a href="{{ route('admin.pesanan.index') }}" class="btn-batal" style="padding: 6px 12px; font-size: 0.8rem; text-decoration: none; border-radius: 10px;">Reset</a>
                        @endif
                    </form>

                    <button class="btn-tambah" onclick="openForm()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5"  y1="12" x2="19" y2="12"/>
                        </svg>
                        Tambah Pesanan
                    </button>
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
                        <th style="width:90px" class="center">Status</th>
                        <th style="width:80px" class="center">Bayar</th>
                        <th style="width:140px">Aksi</th>
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
                                <div class="item-produk-row">
                                    <strong>{{ $d->produk->nama_produk ?? 'Produk Terhapus' }}</strong> 
                                    <span class="item-badge-ukuran">{{ $d->ukuran }}</span> 
                                    <span style="color: #6b7e9f;">x {{ $d->total_item }}</span>
                                    <span style="float: right; color:#8ca0bf;">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </td>
                        <td class="harga-cell" style="font-weight:800; color: #1a2b4a;">
                            Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="center">
                            <span class="status-badge status-{{ $p->status }}">{{ $p->status }}</span>
                        </td>
                        <td class="center">
                            <span class="pay-status ps-{{ $p->status_pembayaran ?? 'belum_bayar' }}">
                                {{ ($p->status_pembayaran ?? 'belum_bayar') === 'belum_bayar' ? 'Belum' : (($p->status_pembayaran ?? '') === 'dp' ? 'DP' : 'Lunas') }}
                            </span>
                        </td>
                        <td>
                            <div class="aksi-wrap">
                                <form method="POST" action="{{ route('admin.pesanan.updateStatus', $p->id) }}" style="display:inline;" id="status-form-{{ $p->id }}">
                                    @csrf @method('PATCH')
                                    @php
                                        $nextStatus = $p->status == 'diproses' ? 'dikerjakan' : ($p->status == 'dikerjakan' ? 'selesai' : 'diproses');
                                    @endphp
                                    <input type="hidden" name="status" value="{{ $nextStatus }}">
                                    <button type="submit" class="btn-status" title="Ubah Status ke {{ ucfirst($nextStatus) }}">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/>
                                        </svg>
                                    </button>
                                </form>
                                
                                <a href="{{ route('admin.pesanan.nota', $p->id) }}" target="_blank" class="btn-print" title="Cetak Nota">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 6 2 18 2 18 9"/>
                                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                                        <rect x="6" y="14" width="12" height="8"/>
                                    </svg>
                                </a>

                                <button class="btn-edit" title="Edit"
                                    onclick="editPesanan(
                                        {{ $p->id }},
                                        {{ $p->user_id }},
                                        '{{ $p->tanggal_pesanan ? $p->tanggal_pesanan->format('Y-m-d') : '' }}',
                                        '{{ $p->status }}',
                                        {{ json_encode($p->details) }}
                                    )">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>

                                <form method="POST" action="{{ route('admin.pesanan.destroy', $p->id) }}" class="form-hapus" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-hapus" title="Hapus"
                                        data-nama="{{ $p->no_pesanan }}"
                                        onclick="confirmHapus(this.closest('form'), this.dataset.nama)">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                            <path d="M10 11v6M14 11v6"/>
                                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                        </svg>
                                    </button>
                                </form>

                                <a href="{{ route('admin.pesanan.pembayaran', $p->id) }}" class="btn-bayar" title="Kelola Pembayaran">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"/>
                                        <line x1="1" y1="10" x2="23" y2="10"/>
                                    </svg>
                                </a>

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

        {{-- ── Form Panel ── --}}
        <div class="card form-panel" id="formPanel">
            <div class="panel-title" id="formTitle">Tambah Pesanan</div>

            <form method="POST" id="formPesanan" action="{{ route('admin.pesanan.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="form-group">
                    <label class="form-label" for="userId">Pelanggan</label>
                    <select class="form-select" id="userId" name="user_id" required>
                        <option value="">Pilih Pelanggan</option>
                        @foreach($pelanggan as $pl)
                            <option value="{{ $pl->id }}">{{ $pl->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:8px">
                    <label class="form-label">Daftar Item Produk Seragam</label>
                </div>

                {{-- Container item-item dinamis --}}
                <div class="items-container" id="itemsContainer">
                    <!-- Baris produk akan disisipkan via JS -->
                </div>
                
                <button type="button" class="btn-add-item" onclick="addItemRow()">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5"  y1="12" x2="19" y2="12"/>
                    </svg>
                    Tambah Item Produk
                </button>

                <div class="form-row" style="margin-top:16px">
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label" for="tanggalPesanan">Tanggal</label>
                        <input class="form-input" type="date" id="tanggalPesanan" name="tanggal_pesanan" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group" style="margin-bottom:0">
                        <label class="form-label" for="status">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="diproses" selected>Diproses</option>
                            <option value="dikerjakan">Dikerjakan</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-batal" onclick="resetForm()">Batal</button>
                    <button type="submit" class="btn-simpan" id="btnSimpan">Simpan</button>
                </div>
            </form>
        </div>

    </div>

@endsection

@push('scripts')
<script>
    const produksData = @json($produks);
    let itemIndex = 0;

    // Helper untuk merender 1 baris item produk baru di form
    function addItemRow(produkId = '', ukuran = 'M', totalItem = 1) {
        const container = document.getElementById('itemsContainer');
        const row = document.createElement('div');
        row.className = 'item-form-row';
        row.id = `item-row-${itemIndex}`;

        // Options list produk
        let options = '<option value="">Pilih Produk</option>';
        produksData.forEach(p => {
            const selected = p.id == produkId ? 'selected' : '';
            options += `<option value="${p.id}" ${selected}>${p.nama_produk} (Rp ${new Intl.NumberFormat('id-ID').format(p.harga)})</option>`;
        });

        row.innerHTML = `
            <div>
                <select class="form-select" name="items[${itemIndex}][produk_id]" required style="padding:6px 8px; font-size:0.75rem">
                    ${options}
                </select>
            </div>
            <div>
                <select class="form-select" name="items[${itemIndex}][ukuran]" required style="padding:6px 8px; font-size:0.75rem">
                    <option value="S" ${ukuran === 'S' ? 'selected' : ''}>S</option>
                    <option value="M" ${ukuran === 'M' ? 'selected' : ''}>M</option>
                    <option value="L" ${ukuran === 'L' ? 'selected' : ''}>L</option>
                    <option value="XL" ${ukuran === 'XL' ? 'selected' : ''}>XL</option>
                    <option value="XXL" ${ukuran === 'XXL' ? 'selected' : ''}>XXL</option>
                </select>
            </div>
            <div>
                <input class="form-input" type="number" name="items[${itemIndex}][total_item]" value="${totalItem}" min="1" required style="padding:6px 8px; font-size:0.75rem">
            </div>
            <div>
                <button type="button" class="btn-remove-item" onclick="removeItemRow('${row.id}')" title="Hapus Item">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
        `;

        container.appendChild(row);
        itemIndex++;
    }

    function removeItemRow(rowId) {
        const row = document.getElementById(rowId);
        if (row) {
            row.remove();
        }
    }

    // ── Open Form (Tambah Mode) ───────────────────────────────────────
    function openForm() {
        document.getElementById('formTitle').textContent = 'Tambah Pesanan';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('formPesanan').action = '{{ route("admin.pesanan.store") }}';
        document.getElementById('formPesanan').reset();
        document.getElementById('tanggalPesanan').value = '{{ date("Y-m-d") }}';
        document.getElementById('status').value = 'diproses';
        document.getElementById('btnSimpan').textContent = 'Simpan';
        
        // Bersihkan container item dan sisipkan 1 baris default
        document.getElementById('itemsContainer').innerHTML = '';
        itemIndex = 0;
        addItemRow(); 

        document.getElementById('userId').focus();
    }

    // ── Edit Mode ────────────────────────────────────────────────────
    function editPesanan(id, userId, tanggal, status, details) {
        document.getElementById('formTitle').textContent = 'Edit Pesanan';
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('formPesanan').action = '/admin/pesanan/' + id;
        document.getElementById('userId').value = userId;
        document.getElementById('tanggalPesanan').value = tanggal;
        document.getElementById('status').value = status;
        document.getElementById('btnSimpan').textContent = 'Update';

        // Bersihkan container item dan render detail pesanan yang ada
        const container = document.getElementById('itemsContainer');
        container.innerHTML = '';
        itemIndex = 0;

        details.forEach(d => {
            addItemRow(d.produk_id, d.ukuran, d.total_item);
        });

        document.getElementById('formPanel').scrollIntoView({ behavior: 'smooth', block: 'start' });
        document.getElementById('userId').focus();
    }

    // ── Reset Form ───────────────────────────────────────────────────
    function resetForm() {
        openForm();
    }

    // Inisialisasi awal form dengan 1 baris input produk kosong
    document.addEventListener('DOMContentLoaded', function() {
        openForm();
    });
</script>
@endpush
