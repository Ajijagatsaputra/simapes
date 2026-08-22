@extends('layouts.main')

@section('title', 'Data Produk — SIMAPES')

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

        .page-date {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .85rem;
            color: #6b7e9f;
            background: #fff;
            border: 1px solid #e2e8f4;
            border-radius: 10px;
            padding: 8px 14px;
        }

        /* ── Stat Bar ── */
        .stat-bar {
            background: #fff;
            border: 1px solid #e8eef8;
            border-radius: 16px;
            padding: 18px 22px;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 22px;
            box-shadow: 0 2px 8px rgba(74, 144, 217, .06);
        }

        .stat-bar-icon {
            width: 50px;
            height: 50px;
            background: #e6f7f4;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2dbe9f;
            flex-shrink: 0;
        }

        .stat-bar-label {
            font-size: .75rem;
            color: #8ca0bf;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .stat-bar-value {
            font-size: 1.7rem;
            font-weight: 800;
            color: #1a2b4a;
            line-height: 1.1;
        }

        .stat-bar-desc {
            font-size: .72rem;
            color: #a0aec0;
        }

        /* ── Card ── */
        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e8eef8;
            padding: 22px 24px;
            box-shadow: 0 2px 8px rgba(74, 144, 217, .06);
        }

        /* ── Toolbar ── */
        .table-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .table-toolbar-left {
            font-size: .95rem;
            font-weight: 700;
            color: #1a2b4a;
        }

        .toolbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
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
            width: 160px;
            font-family: inherit;
        }

        .search-wrap input::placeholder {
            color: #aab9d0;
        }

        .search-wrap svg {
            color: #8ca0bf;
            flex-shrink: 0;
        }

        .btn-tambah {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #4A90D9, #3a7bc8);
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 8px 18px;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: all .2s;
            box-shadow: 0 4px 12px rgba(74, 144, 217, .3);
        }

        .btn-tambah:hover {
            background: linear-gradient(135deg, #3a7bc8, #2d6ab5);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(74, 144, 217, .4);
        }

        /* ── Table ── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .8rem;
        }

        .data-table thead th {
            background: #f5f8ff;
            color: #8ca0bf;
            font-weight: 600;
            font-size: .72rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #e8eef8;
            white-space: nowrap;
        }

        .data-table thead th:first-child {
            border-radius: 8px 0 0 8px;
        }

        .data-table thead th:last-child {
            border-radius: 0 8px 8px 0;
            text-align: center;
        }

        .data-table tbody td {
            padding: 11px 12px;
            color: #2d4060;
            border-bottom: 1px solid #f6f9fd;
            vertical-align: middle;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .data-table tbody tr:hover td {
            background: #fafcff;
        }

        .data-table td.center { text-align: center; }
        .row-number { color: #8ca0bf; font-weight: 600; }
        .harga-cell { font-weight: 600; color: #2d4060; white-space: nowrap; }

        /* ── Expand Button & Detail Row ── */
        .btn-expand {
            width: 22px; height: 22px;
            border-radius: 6px;
            background: #eef3fb;
            border: 1px solid #dde8f8;
            display: inline-flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: #4A90D9;
            transition: background .15s, transform .2s;
            flex-shrink: 0;
        }
        .btn-expand:hover { background: #ddeaf8; }
        .btn-expand.open { background: #4A90D9; color: #fff; border-color: #4A90D9; transform: rotate(45deg); }

        .detail-row td { padding: 0 !important; border-bottom: 1px solid #eef3fb !important; background: #f8fbff; }
        .detail-inner {
            overflow: hidden;
            max-height: 0;
            transition: max-height .35s cubic-bezier(.4,0,.2,1), padding .3s;
            padding: 0 16px;
        }
        .detail-inner.open { max-height: 500px; padding: 14px 16px; }
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }
        @media (max-width: 900px) { .detail-grid { grid-template-columns: 1fr 1fr; } }
        @media (max-width: 600px) { .detail-grid { grid-template-columns: 1fr; } }
        .detail-item-label {
            font-size: .67rem; font-weight: 700; color: #4A90D9;
            text-transform: uppercase; letter-spacing: .5px;
            margin-bottom: 4px;
            display: flex; align-items: center; gap: 5px;
        }
        .detail-item-value {
            font-size: .75rem; color: #2d4060; line-height: 1.5;
            background: #fff; border: 1px solid #e8eef8;
            border-radius: 8px; padding: 8px 10px;
            white-space: pre-wrap; word-break: break-word;
            min-height: 38px;
        }
        .detail-item-value.empty { color: #c0cce0; font-style: italic; }

        /* Badge indikator di kolom nama produk */
        .detail-badge {
            display: inline-flex; align-items: center; gap: 3px;
            font-size: .62rem; font-weight: 700;
            background: #eef3fb; color: #4A90D9;
            border-radius: 4px; padding: 1px 5px;
            margin-left: 4px;
        }
        .detail-badge.has { background: #eaf7f1; color: #27a870; }

        .stok-cell {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
        }

        .stok-ok {
            background: #e8f8ee;
            color: #34c472;
        }

        .stok-low {
            background: #fff3e6;
            color: #f5a54a;
        }

        .stok-empty {
            background: #fdeaea;
            color: #e05a5a;
        }

        /* ── Aksi ── */
        .aksi-wrap {
            display: flex;
            gap: 6px;
            justify-content: center;
        }

        .btn-edit,
        .btn-hapus {
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: opacity .15s, transform .15s;
        }

        .btn-edit {
            background: #4A90D9;
            color: #fff;
        }

        .btn-hapus {
            background: #e05a5a;
            color: #fff;
        }

        .btn-edit:hover,
        .btn-hapus:hover {
            opacity: .85;
            transform: scale(1.08);
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
            text-decoration: none;
            transition: background .15s, color .15s;
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

        /* ── Empty State ── */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #a0aec0;
        }

        .empty-state svg {
            margin-bottom: 12px;
            opacity: .4;
        }

        .empty-state p {
            font-size: .85rem;
        }

        /* ══════════════════════════════════════════════
           MODAL STYLES
        ══════════════════════════════════════════════ */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 25, 50, 0.55);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: 9000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            visibility: hidden;
            transition: opacity .3s ease, visibility .3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-box {
            background: #fff;
            border-radius: 20px;
            width: 100%;
            max-width: 720px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 60px rgba(15, 25, 50, .22), 0 0 0 1px rgba(255, 255, 255, .1);
            transform: translateY(28px) scale(0.97);
            transition: transform .35s cubic-bezier(.34, 1.56, .64, 1), opacity .3s ease;
            opacity: 0;
            scrollbar-width: thin;
            scrollbar-color: #dde8f8 transparent;
        }

        .modal-overlay.active .modal-box {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        /* Modal Header */
        .modal-header {
            background: linear-gradient(135deg, #1e3a6e 0%, #2e5fa3 50%, #4A90D9 100%);
            padding: 22px 28px;
            border-radius: 20px 20px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .modal-header::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .07);
        }

        .modal-header::after {
            content: '';
            position: absolute;
            bottom: -30px;
            left: 20px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
        }

        .modal-header-left {
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            z-index: 1;
        }

        .modal-header-icon {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, .15);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, .2);
        }

        .modal-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #fff;
        }

        .modal-subtitle {
            font-size: .75rem;
            color: rgba(255, 255, 255, .7);
            margin-top: 1px;
        }

        .modal-close {
            width: 34px;
            height: 34px;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #fff;
            transition: background .2s;
            position: relative;
            z-index: 1;
        }

        .modal-close:hover {
            background: rgba(255, 255, 255, .28);
        }

        /* Modal Body */
        .modal-body {
            padding: 24px 28px;
        }

        /* Form inside modal */
        .modal-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .modal-form-grid .full-width {
            grid-column: 1 / -1;
        }

        .form-section-title {
            font-size: .72rem;
            font-weight: 700;
            color: #4A90D9;
            text-transform: uppercase;
            letter-spacing: .8px;
            margin: 16px 0 10px;
            padding-bottom: 6px;
            border-bottom: 1px solid #eef3fb;
            grid-column: 1 / -1;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: .75rem;
            font-weight: 600;
            color: #5a7090;
            margin-bottom: 5px;
        }

        .form-input,
        .form-textarea,
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
        .form-textarea:focus,
        .form-select:focus {
            border-color: #4A90D9;
            box-shadow: 0 0 0 3px rgba(74, 144, 217, .12);
            background: #fff;
        }

        .form-textarea {
            resize: vertical;
            min-height: 72px;
        }

        /* Image upload area */
        .img-upload-area {
            border: 2px dashed #c5d8f5;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            background: #f8fbff;
        }

        .img-upload-area:hover {
            border-color: #4A90D9;
            background: #eef5fd;
        }

        .img-upload-area input[type="file"] {
            display: none;
        }

        .img-preview-wrap {
            display: none;
            align-items: center;
            gap: 12px;
            padding: 10px;
            background: #f0f6ff;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .img-preview-wrap.show {
            display: flex;
        }

        .img-preview-wrap img {
            width: 52px;
            height: 52px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #c5d8f5;
        }

        .img-preview-info {
            flex: 1;
            text-align: left;
        }

        .img-preview-info span {
            font-size: .75rem;
            font-weight: 600;
            color: #1a2b4a;
            display: block;
        }

        .img-preview-info small {
            font-size: .68rem;
            color: #8ca0bf;
        }

        /* Modal Footer */
        .modal-footer {
            padding: 16px 28px 22px;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            border-top: 1px solid #f0f4fb;
        }

        .btn-modal-batal {
            padding: 9px 22px;
            background: #f0f4fb;
            border: 1.5px solid #dde8f8;
            border-radius: 10px;
            color: #5a7090;
            font-size: .83rem;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: background .15s;
        }

        .btn-modal-batal:hover {
            background: #e2e8f4;
        }

        .btn-modal-simpan {
            padding: 9px 26px;
            background: linear-gradient(135deg, #4A90D9, #3a7bc8);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-size: .83rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: all .2s;
            box-shadow: 0 4px 12px rgba(74, 144, 217, .3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-modal-simpan:hover {
            background: linear-gradient(135deg, #3a7bc8, #2d6ab5);
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(74, 144, 217, .4);
        }

        @media (max-width: 600px) {
            .modal-form-grid {
                grid-template-columns: 1fr;
            }

            .modal-form-grid .full-width {
                grid-column: 1;
            }

            .modal-body {
                padding: 18px 16px;
            }

            .modal-footer {
                padding: 14px 16px 18px;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Data Produk</h1>
            <nav class="breadcrumb" aria-label="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="breadcrumb-sep">›</span>
                <span class="breadcrumb-current">Produk Seragam</span>
            </nav>
        </div>
        <div class="page-date">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
            {{ \Carbon\Carbon::now()->isoFormat('DD MMM YYYY') }}
        </div>
    </div>

    {{-- ── Stat Bar ── --}}
    <div class="stat-bar">
        <div class="stat-bar-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round">
                <path
                    d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z" />
            </svg>
        </div>
        <div>
            <div class="stat-bar-label">Total Produk</div>
            <div class="stat-bar-value">{{ $totalProduk }}</div>
            <div class="stat-bar-desc">Produk</div>
        </div>
    </div>

    {{-- ── Tabel Produk (Full Width) ── --}}
    <div class="card">
        <div class="table-toolbar">
            <span class="table-toolbar-left">Daftar Produk</span>
            <div class="toolbar-right">
                <div class="search-wrap">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input type="text" id="searchInput" placeholder="Cari Produk" oninput="filterTable()">
                </div>
                <button class="btn-tambah" onclick="openModal()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Tambah Produk
                </button>
            </div>
        </div>

        <table class="data-table" id="tableProduk">
            <thead>
                <tr>
                    <th style="width:40px"></th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th style="width:60px" class="center">Stok</th>
                    <th style="width:130px">Spesifikasi Bahan</th>
                    <th style="width:110px">Size Chart</th>
                    <th style="width:110px">Estimasi BB/TB</th>
                    <th style="width:80px" class="center">Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($produk as $index => $p)
                    @php
                        $hasBahan    = !empty($p->spesifikasi_bahan);
                        $hasSizeChart = !empty($p->size_chart);
                        $hasEstimasi = !empty($p->estimasi_bb_tb);
                        $rowId       = 'detail-' . $p->id;
                    @endphp
                    {{-- ── Baris Utama ── --}}
                    <tr data-search="{{ strtolower($p->nama_produk . ' ' . $p->jenis_seragam) }}" class="main-row">
                        {{-- Tombol Expand --}}
                        <td class="center">
                            <button class="btn-expand" title="Lihat Detail" onclick="toggleDetail('{{ $rowId }}', this)">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                            </button>
                        </td>
                        {{-- Nama Produk --}}
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                @if($p->gambar)
                                    <img src="{{ asset($p->gambar) }}" alt="{{ $p->nama_produk }}"
                                        style="width: 36px; height: 36px; border-radius: 8px; object-fit: cover; border: 1px solid #dde8f8; flex-shrink:0;">
                                @else
                                    <div style="width: 36px; height: 36px; border-radius: 8px; background: #f0f4fb; display: flex; align-items: center; justify-content: center; color: #8ca0bf; border: 1px solid #dde8f8; flex-shrink:0;">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight:600;">{{ $p->nama_produk }}</div>
                                    @if($p->deskripsi)
                                        <div style="font-size:.72rem; color:#8ca0bf; margin-top:2px;">{{ Str::limit($p->deskripsi, 50) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        {{-- Kategori --}}
                        <td>{{ $p->jenis_seragam }}</td>
                        {{-- Harga --}}
                        <td class="harga-cell">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                        {{-- Stok --}}
                        <td class="center">
                            @php $stokClass = $p->stok === 0 ? 'stok-empty' : ($p->stok <= 10 ? 'stok-low' : 'stok-ok'); @endphp
                            <span class="stok-cell {{ $stokClass }}">{{ $p->stok }}</span>
                        </td>
                        {{-- Spesifikasi Bahan --}}
                        <td style="max-width:130px; font-size:.75rem; color: {{ $hasBahan ? '#2d4060' : '#c0cce0' }};">
                            {{ $hasBahan ? Str::limit($p->spesifikasi_bahan, 40) : '—' }}
                        </td>
                        {{-- Size Chart --}}
                        <td style="max-width:110px; font-size:.75rem; color: {{ $hasSizeChart ? '#2d4060' : '#c0cce0' }};">
                            {{ $hasSizeChart ? Str::limit($p->size_chart, 30) : '—' }}
                        </td>
                        {{-- Estimasi BB/TB --}}
                        <td style="max-width:110px; font-size:.75rem; color: {{ $hasEstimasi ? '#2d4060' : '#c0cce0' }};">
                            {{ $hasEstimasi ? Str::limit($p->estimasi_bb_tb, 30) : '—' }}
                        </td>
                        {{-- Aksi --}}
                        <td>
                            <div class="aksi-wrap">
                                <button class="btn-edit" title="Edit" data-id="{{ $p->id }}" data-nama="{{ $p->nama_produk }}"
                                    data-jenis="{{ $p->jenis_seragam }}" data-harga="{{ (int) $p->harga }}"
                                    data-deskripsi="{{ $p->deskripsi ?? '' }}" data-stok="{{ $p->stok }}"
                                    data-gambar="{{ $p->gambar ?? '' }}"
                                    data-spesifikasi_bahan="{{ $p->spesifikasi_bahan ?? '' }}"
                                    data-size_chart="{{ $p->size_chart ?? '' }}"
                                    data-estimasi_bb_tb="{{ $p->estimasi_bb_tb ?? '' }}" onclick="editProdukFromBtn(this)">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <form method="POST" action="{{ route('admin.produk.destroy', $p->id) }}" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-hapus" title="Hapus" data-nama="{{ $p->nama_produk }}"
                                        onclick="confirmHapus(this.closest('form'), this.dataset.nama)">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                            <path d="M10 11v6M14 11v6"/>
                                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- ── Detail Row (expandable) ── --}}
                    <tr class="detail-row" id="{{ $rowId }}">
                        <td colspan="9">
                            <div class="detail-inner" id="{{ $rowId }}-inner">
                                <div class="detail-grid">
                                    {{-- Spesifikasi Bahan --}}
                                    <div>
                                        <div class="detail-item-label">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                                            Spesifikasi Bahan
                                        </div>
                                        <div class="detail-item-value {{ $hasBahan ? '' : 'empty' }}">{{ $hasBahan ? $p->spesifikasi_bahan : 'Belum diisi' }}</div>
                                    </div>
                                    {{-- Size Chart --}}
                                    <div>
                                        <div class="detail-item-label">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="4 7 4 4 20 4 20 7"/><line x1="9" y1="20" x2="15" y2="20"/><line x1="12" y1="4" x2="12" y2="20"/></svg>
                                            Tabel Ukuran / Size Chart
                                        </div>
                                        <div class="detail-item-value {{ $hasSizeChart ? '' : 'empty' }}">{{ $hasSizeChart ? $p->size_chart : 'Belum diisi' }}</div>
                                    </div>
                                    {{-- Estimasi BB/TB --}}
                                    <div>
                                        <div class="detail-item-label">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                            Estimasi BB / TB
                                        </div>
                                        <div class="detail-item-value {{ $hasEstimasi ? '' : 'empty' }}">{{ $hasEstimasi ? $p->estimasi_bb_tb : 'Belum diisi' }}</div>
                                    </div>
                                    {{-- Deskripsi --}}
                                    <div style="grid-column: 1 / -1;">
                                        <div class="detail-item-label">
                                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                            Deskripsi Produk
                                        </div>
                                        <div class="detail-item-value {{ $p->deskripsi ? '' : 'empty' }}">{{ $p->deskripsi ?: 'Belum diisi' }}</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z"/>
                                </svg>
                                <p>Belum ada data produk.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($produk->hasPages())
            <div class="pagination-wrap">
                @if($produk->onFirstPage())
                    <button class="page-btn" disabled>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6" />
                        </svg>
                    </button>
                @else
                    <a href="{{ $produk->previousPageUrl() }}" class="page-btn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6" />
                        </svg>
                    </a>
                @endif

                @foreach($produk->getUrlRange(1, $produk->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="page-btn {{ $page == $produk->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach

                @if($produk->hasMorePages())
                    <a href="{{ $produk->nextPageUrl() }}" class="page-btn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </a>
                @else
                    <button class="page-btn" disabled>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6" />
                        </svg>
                    </button>
                @endif
            </div>
        @endif
    </div>

    {{-- ══════════════════════════════════════════
    MODAL TAMBAH / EDIT PRODUK
    ══════════════════════════════════════════ --}}
    <div class="modal-overlay" id="modalOverlay" onclick="handleOverlayClick(event)">
        <div class="modal-box" id="modalBox">

            {{-- Header --}}
            <div class="modal-header">
                <div class="modal-header-left">
                    <div class="modal-header-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z" />
                        </svg>
                    </div>
                    <div>
                        <div class="modal-title" id="modalTitle">Tambah Produk</div>
                        <div class="modal-subtitle" id="modalSubtitle">Isi detail produk seragam baru</div>
                    </div>
                </div>
                <button class="modal-close" onclick="closeModal()" title="Tutup">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                <form method="POST" id="formProduk" action="{{ route('admin.produk.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="modal-form-grid">

                        {{-- Seksi: Informasi Dasar --}}
                        <div class="form-section-title">📦 Informasi Dasar</div>

                        {{-- Nama Produk --}}
                        <div class="form-group full-width">
                            <label class="form-label" for="namaProduk">Nama Produk <span
                                    style="color:#e05a5a">*</span></label>
                            <input class="form-input" type="text" id="namaProduk" name="nama_produk"
                                placeholder="Cth: Baju Seragam OSIS SMA" required>
                        </div>

                        {{-- Kategori --}}
                        <div class="form-group">
                            <label class="form-label" for="jenisSeragam">Kategori <span
                                    style="color:#e05a5a">*</span></label>
                            <select class="form-select" id="jenisSeragam" name="jenis_seragam" required>
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                <option value="TK">TK/PAUD</option>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA/SMK">SMA/SMK</option>
                                <option value="Umum">Umum</option>
                                <option value="Atribut">Atribut</option>
                            </select>
                        </div>

                        {{-- Harga & Stok --}}
                        <div class="form-group" style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                            <div>
                                <label class="form-label" for="harga">Harga (Rp) <span
                                        style="color:#e05a5a">*</span></label>
                                <input class="form-input" type="text" id="harga" name="harga" placeholder="75.000" required>
                            </div>
                            <div>
                                <label class="form-label" for="stok">Stok <span style="color:#e05a5a">*</span></label>
                                <input class="form-input" type="number" id="stok" name="stok" placeholder="50" min="0"
                                    required>
                            </div>
                        </div>

                        {{-- Seksi: Detail Teknis --}}
                        <div class="form-section-title">🧵 Detail Teknis (Opsional)</div>

                        {{-- Spesifikasi Bahan --}}
                        <div class="form-group">
                            <label class="form-label" for="spesifikasiBahan">Spesifikasi Bahan</label>
                            <textarea class="form-textarea" id="spesifikasiBahan" name="spesifikasi_bahan"
                                placeholder="Cth: Atasan Katun Deluxe | Bawahan Drill Famatex..."></textarea>
                        </div>

                        {{-- Size Chart --}}
                        <div class="form-group">
                            <label class="form-label" for="sizeChart">Tabel Ukuran / Size Chart</label>
                            <textarea class="form-textarea" id="sizeChart" name="size_chart"
                                placeholder="Cth: S (LD: 88cm, PB: 65cm) | M (LD: 94cm, PB: 68cm)..."></textarea>
                        </div>

                        {{-- Estimasi BB/TB --}}
                        <div class="form-group">
                            <label class="form-label" for="estimasiBbTb">Estimasi BB / TB</label>
                            <textarea class="form-textarea" id="estimasiBbTb" name="estimasi_bb_tb"
                                placeholder="Cth: S (BB: 35-45kg, TB: 145-155cm)..."></textarea>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="form-group">
                            <label class="form-label" for="deskripsi">Deskripsi Produk</label>
                            <textarea class="form-textarea" id="deskripsi" name="deskripsi"
                                placeholder="Deskripsi singkat produk..."></textarea>
                        </div>

                        {{-- Seksi: Foto --}}
                        <div class="form-section-title">🖼️ Foto Produk</div>

                        {{-- Image Upload --}}
                        <div class="form-group full-width">
                            {{-- Preview gambar existing (saat edit) --}}
                            <div class="img-preview-wrap" id="currentImageContainer">
                                <img id="currentImagePreview" src="" alt="Preview">
                                <div class="img-preview-info">
                                    <span id="currentImageName">Gambar saat ini</span>
                                    <small>Unggah gambar baru untuk mengganti</small>
                                </div>
                            </div>
                            {{-- Upload area --}}
                            <div class="img-upload-area" id="uploadArea"
                                onclick="document.getElementById('gambarInput').click()">
                                <div id="uploadPlaceholder">
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#8ca0bf"
                                        stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                        style="margin-bottom:8px">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" />
                                    </svg>
                                    <p style="font-size:.78rem; color:#8ca0bf; margin:0">Klik untuk pilih gambar</p>
                                    <p style="font-size:.68rem; color:#aab9d0; margin:4px 0 0">JPG, PNG, WEBP — Maks. 2MB
                                    </p>
                                </div>
                                <div id="newImgPreview"
                                    style="display:none; align-items:center; gap:10px; justify-content:center">
                                    <img id="newImgPreviewImg" src="" alt="Preview"
                                        style="width:52px;height:52px;border-radius:8px;object-fit:cover;border:2px solid #c5d8f5;">
                                    <div style="text-align:left">
                                        <p id="newImgName" style="font-size:.78rem;font-weight:600;color:#1a2b4a;margin:0">
                                        </p>
                                        <p style="font-size:.7rem;color:#8ca0bf;margin:2px 0 0">Klik area ini untuk ganti
                                            gambar</p>
                                    </div>
                                </div>
                            </div>
                            <input type="file" id="gambarInput" name="gambar" accept="image/*"
                                onchange="previewImage(event)">
                        </div>

                    </div>{{-- end modal-form-grid --}}
                </form>
            </div>{{-- end modal-body --}}

            {{-- Footer --}}
            <div class="modal-footer">
                <button type="button" class="btn-modal-batal" onclick="closeModal()">Batal</button>
                <button type="button" class="btn-modal-simpan" id="btnSimpan"
                    onclick="document.getElementById('formProduk').submit()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    <span id="btnSimpanText">Simpan Produk</span>
                </button>
            </div>

        </div>{{-- end modal-box --}}
    </div>{{-- end modal-overlay --}}

@endsection

@push('scripts')
    <script>
        // ── Search ──────────────────────────────────────────────────────
        function filterTable() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            document.querySelectorAll('#tableBody tr.main-row').forEach(row => {
                const match = row.dataset.search.includes(q);
                row.style.display = match ? '' : 'none';
                // Sembunyikan detail row pasangannya juga
                const detailRow = row.nextElementSibling;
                if (detailRow && detailRow.classList.contains('detail-row')) {
                    detailRow.style.display = match ? '' : 'none';
                }
            });
        }

        // ── Toggle Detail Row ────────────────────────────────────────────
        function toggleDetail(rowId, btn) {
            const inner = document.getElementById(rowId + '-inner');
            const isOpen = inner.classList.contains('open');

            // Tutup semua yang terbuka dulu (opsional — hapus block ini jika ingin multi-expand)
            document.querySelectorAll('.detail-inner.open').forEach(el => {
                el.classList.remove('open');
            });
            document.querySelectorAll('.btn-expand.open').forEach(el => {
                el.classList.remove('open');
            });

            // Toggle yang diklik
            if (!isOpen) {
                inner.classList.add('open');
                btn.classList.add('open');
            }
        }

        // ── Modal Controls ───────────────────────────────────────────────
        function openModal() {
            resetForm();
            document.getElementById('modalOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
            setTimeout(() => document.getElementById('namaProduk').focus(), 350);
        }

        function closeModal() {
            document.getElementById('modalOverlay').classList.remove('active');
            document.body.style.overflow = '';
        }

        function handleOverlayClick(e) {
            if (e.target === document.getElementById('modalOverlay')) closeModal();
        }

        // Tutup modal dengan Escape
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal();
        });

        // ── Reset Form ───────────────────────────────────────────────────
        function resetForm() {
            document.getElementById('modalTitle').textContent = 'Tambah Produk';
            document.getElementById('modalSubtitle').textContent = 'Isi detail produk seragam baru';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('formProduk').action = '{{ route("admin.produk.store") }}';
            document.getElementById('formProduk').reset();
            document.getElementById('btnSimpanText').textContent = 'Simpan Produk';

            // Reset image
            document.getElementById('currentImageContainer').classList.remove('show');
            document.getElementById('currentImagePreview').src = '';
            document.getElementById('uploadPlaceholder').style.display = 'block';
            document.getElementById('newImgPreview').style.display = 'none';
            document.getElementById('newImgPreviewImg').src = '';
        }

        // ── Edit ─────────────────────────────────────────────────────────
        function formatRibuan(value) {
            let numberString = value.toString().replace(/[^0-9]/g, '');
            if (numberString === '') return '';
            return parseInt(numberString, 10).toLocaleString('id-ID');
        }

        function editProdukFromBtn(btn) {
            const ds = btn.dataset;
            editProduk(ds.id, ds.nama, ds.jenis, ds.harga, ds.deskripsi, ds.stok, ds.gambar, ds.spesifikasi_bahan, ds.size_chart, ds.estimasi_bb_tb);
        }

        function editProduk(id, nama, jenis, harga, deskripsi, stok, gambarUrl, spesifikasiBahan, sizeChart, estimasiBbTb) {
            document.getElementById('modalTitle').textContent = 'Edit Produk';
            document.getElementById('modalSubtitle').textContent = 'Perbarui informasi produk seragam';
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('formProduk').action = '/admin/produk/' + id;
            document.getElementById('namaProduk').value = nama;
            document.getElementById('jenisSeragam').value = jenis;
            document.getElementById('harga').value = formatRibuan(harga);
            document.getElementById('stok').value = stok;
            document.getElementById('deskripsi').value = deskripsi || '';
            document.getElementById('spesifikasiBahan').value = spesifikasiBahan || '';
            document.getElementById('sizeChart').value = sizeChart || '';
            document.getElementById('estimasiBbTb').value = estimasiBbTb || '';
            document.getElementById('btnSimpanText').textContent = 'Update Produk';

            // Tampilkan gambar lama jika ada
            const currentImgContainer = document.getElementById('currentImageContainer');
            const currentImgPreview = document.getElementById('currentImagePreview');
            if (gambarUrl && gambarUrl !== '') {
                currentImgPreview.src = '/' + gambarUrl;
                currentImgContainer.classList.add('show');
            } else {
                currentImgPreview.src = '';
                currentImgContainer.classList.remove('show');
            }

            // Reset preview gambar baru
            document.getElementById('uploadPlaceholder').style.display = 'block';
            document.getElementById('newImgPreview').style.display = 'none';

            document.getElementById('modalOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
            setTimeout(() => document.getElementById('namaProduk').focus(), 350);
        }

        // ── Image Preview ────────────────────────────────────────────────
        function previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('newImgPreviewImg').src = e.target.result;
                document.getElementById('newImgName').textContent = file.name;
                document.getElementById('uploadPlaceholder').style.display = 'none';
                document.getElementById('newImgPreview').style.display = 'flex';
            };
            reader.readAsDataURL(file);
        }

        // ── Format harga ─────────────────────────────────────────────────
        const hargaInput = document.getElementById('harga');
        if (hargaInput) {
            hargaInput.addEventListener('input', function () {
                let cursorPosition = this.selectionStart;
                let originalLength = this.value.length;
                let formatted = formatRibuan(this.value);
                this.value = formatted;
                cursorPosition = cursorPosition + (formatted.length - originalLength);
                this.setSelectionRange(cursorPosition, cursorPosition);
            });
        }

        // Strip titik ribuan sebelum submit
        const formProduk = document.getElementById('formProduk');
        if (formProduk) {
            formProduk.addEventListener('submit', function () {
                if (hargaInput) hargaInput.value = hargaInput.value.replace(/\./g, '');
            });
        }
    </script>
@endpush