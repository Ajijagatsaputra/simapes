@extends('layouts.main')

@section('title', 'Data Supplier — SIMAPES')

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

        /* ── Stat Card ── */
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
            background: #e8f0fd;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4A90D9;
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

        /* ── Main Panel Layout ── */
        .supplier-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 20px;
            align-items: start;
        }

        @media (max-width: 992px) {
            .supplier-layout {
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

        /* ── Table Toolbar ── */
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
            background: #4A90D9;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 8px 16px;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: background .2s, transform .15s;
        }

        .btn-tambah:hover {
            background: #3a7bc8;
            transform: translateY(-1px);
        }

        /* ── Data Table ── */
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

        .data-table td:last-child {
            text-align: center;
        }

        /* ── Badges Kategori ── */
        .badge-kategori {
            display: inline-block;
            font-size: .65rem;
            font-weight: 700;
            padding: 2.5px 7px;
            border-radius: 6px;
            margin-right: 3px;
            margin-bottom: 3px;
            text-transform: capitalize;
        }

        .badge-kategori.kain {
            background: #e0f2fe;
            color: #0369a1;
        }

        .badge-kategori.kancing {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-kategori.benang {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .badge-kategori.resleting {
            background: #dcfce7;
            color: #15803d;
        }

        /* ── Aksi Buttons ── */
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

        /* ── Nomor baris ── */
        .row-number {
            color: #8ca0bf;
            font-weight: 600;
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

        /* ── Form Panel ── */
        .form-panel .card-title {
            font-size: .95rem;
            font-weight: 700;
            color: #1a2b4a;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f4fb;
        }

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
        .form-textarea:focus {
            border-color: #4A90D9;
            box-shadow: 0 0 0 3px rgba(74, 144, 217, .12);
            background: #fff;
        }

        .form-textarea {
            resize: vertical;
            min-height: 72px;
        }

        /* ── Checkbox Kategori ── */
        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 6px;
        }

        .checkbox-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            color: #2d4060;
            cursor: pointer;
            font-weight: 500;
        }

        .checkbox-label input[type="checkbox"] {
            width: 16px;
            height: 16px;
            border: 1.5px solid #dde8f8;
            border-radius: 4px;
            outline: none;
            cursor: pointer;
            accent-color: #4A90D9;
        }

        .form-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid #f0f4fb;
        }

        .btn-batal {
            padding: 8px 18px;
            background: #f0f4fb;
            border: 1px solid #dde8f8;
            border-radius: 9px;
            color: #5a7090;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: background .15s;
        }

        .btn-batal:hover {
            background: #e2e8f4;
        }

        .btn-simpan {
            padding: 8px 20px;
            background: #4A90D9;
            border: none;
            border-radius: 9px;
            color: #fff;
            font-size: .82rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: background .2s;
        }

        .btn-simpan:hover {
            background: #3a7bc8;
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
    </style>
@endpush

@section('content')

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Data Supplier</h1>
            <nav class="breadcrumb" aria-label="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="breadcrumb-sep">›</span>
                <span class="breadcrumb-current">Supplier</span>
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
                <line x1="16.5" y1="9.4" x2="7.5" y2="4.21" />
                <polygon points="12 22.08 12 12 3 6.92 3 17.08 12 22.08" />
                <polygon points="12 22.08 12 12 21 6.92 21 17.08 12 22.08" />
                <polygon points="12 12 3 6.92 12 1.84 21 6.92 12 12" />
            </svg>
        </div>
        <div>
            <div class="stat-bar-label">Total Supplier</div>
            <div class="stat-bar-value">{{ $totalSupplier }}</div>
            <div class="stat-bar-desc">Mitra Supplier Terdaftar</div>
        </div>
    </div>

    {{-- ── Main Layout: Tabel + Form ── --}}
    <div class="supplier-layout">

        {{-- ── Tabel Supplier ── --}}
        <div class="card">
            <div class="table-toolbar">
                <span class="table-toolbar-left">Daftar Supplier Mitra</span>
                <div class="toolbar-right">
                    <div class="search-wrap">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                        <input type="text" id="searchInput" placeholder="Cari Supplier" oninput="filterTable()">
                    </div>
                    <button class="btn-tambah" id="btn-open-form" onclick="openForm()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Tambah Supplier
                    </button>
                </div>
            </div>

            <table class="data-table" id="tableSupplier">
                <thead>
                    <tr>
                        <th style="width:48px">No.</th>
                        <th>Nama Supplier</th>
                        <th>Kategori Bahan</th>
                        <th>No. WhatsApp</th>
                        <th>Alamat</th>
                        <th>Keterangan</th>
                        <th style="width:80px">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($supplier as $index => $s)
                        @php
                            $searchTags = strtolower($s->nama_supplier . ' ' . ($s->no_whatsapp ?? '') . ' ' . ($s->alamat ?? '') . ' ' . implode(' ', $s->kategori_bahan ?? []));
                        @endphp
                        <tr data-search="{{ $searchTags }}">
                            <td class="row-number">{{ $supplier->firstItem() + $index }}</td>
                            <td style="font-weight: 700; color: #1a2b4a;">{{ $s->nama_supplier }}</td>
                            <td>
                                @foreach($s->kategori_bahan ?? [] as $kat)
                                    <span class="badge-kategori {{ $kat }}">{{ $kat }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($s->no_whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $s->no_whatsapp) }}" target="_blank"
                                        style="color: #4A90D9; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path
                                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                        </svg>
                                        {{ $s->no_whatsapp }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $s->alamat ?? '-' }}</td>
                            <td style="font-size: 0.76rem; color: #6b7e9f;">{{ $s->deskripsi ?? '-' }}</td>
                            <td>
                                <div class="aksi-wrap">
                                    <button class="btn-edit" title="Edit"
                                        onclick="editSupplier({{ $s->id }}, '{{ addslashes($s->nama_supplier) }}', '{{ addslashes($s->no_whatsapp ?? '') }}', '{{ addslashes($s->alamat ?? '') }}', {{ json_encode($s->kategori_bahan ?? []) }}, '{{ addslashes($s->deskripsi ?? '') }}')">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                    </button>
                                    <form method="POST" action="{{ route('admin.supplier.destroy', $s->id) }}"
                                        style="display:inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-hapus" title="Hapus"
                                            data-nama="{{ $s->nama_supplier }}"
                                            onclick="confirmHapus(this.closest('form'), this.dataset.nama)">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                                <path d="M10 11v6M14 11v6" />
                                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="7">
                                <div class="empty-state">
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.5">
                                        <line x1="16.5" y1="9.4" x2="7.5" y2="4.21" />
                                        <polygon points="12 22.08 12 12 3 6.92 3 17.08 12 22.08" />
                                        <polygon points="12 22.08 12 12 21 6.92 21 17.08 12 22.08" />
                                    </svg>
                                    <p>Belum ada data supplier.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            @if($supplier->hasPages())
                <div class="pagination-wrap">
                    @if($supplier->onFirstPage())
                        <button class="page-btn" disabled>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6" />
                            </svg>
                        </button>
                    @else
                        <a href="{{ $supplier->previousPageUrl() }}" class="page-btn">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="15 18 9 12 15 6" />
                            </svg>
                        </a>
                    @endif

                    @php
                        $start = max(1, $supplier->currentPage() - 2);
                        $end = min($supplier->lastPage(), $supplier->currentPage() + 2);
                    @endphp

                    @if($start > 1)
                        <a href="{{ $supplier->url(1) }}" class="page-btn">1</a>
                        @if($start > 2)
                            <span class="page-dots">...</span>
                        @endif
                    @endif

                    @for($page = $start; $page <= $end; $page++)
                        <a href="{{ $supplier->url($page) }}"
                            class="page-btn {{ $page == $supplier->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @endfor

                    @if($end < $supplier->lastPage())
                        @if($end < $supplier->lastPage() - 1)
                            <span class="page-dots">...</span>
                        @endif
                        <a href="{{ $supplier->url($supplier->lastPage()) }}" class="page-btn">{{ $supplier->lastPage() }}</a>
                    @endif

                    @if($supplier->hasMorePages())
                        <a href="{{ $supplier->nextPageUrl() }}" class="page-btn">
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

        {{-- ── Form Panel ── --}}
        <div class="card form-panel" id="formPanel">
            <div class="card-title" id="formTitle">Tambah Supplier</div>

            <form method="POST" id="formSupplier" action="{{ route('admin.supplier.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="supplier_id" id="supplierId" value="">

                <div class="form-group">
                    <label class="form-label" for="namaSupplier">Nama Supplier</label>
                    <input class="form-input" type="text" id="namaSupplier" name="nama_supplier"
                        placeholder="Masukkan nama toko/PT" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Kategori Bahan Yang Disuplai</label>
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="kategori_bahan[]" value="kain" id="chk-kain">
                            Kain (Drill, Katun, dll)
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="kategori_bahan[]" value="kancing" id="chk-kancing">
                            Kancing
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="kategori_bahan[]" value="benang" id="chk-benang">
                            Benang Jahit
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="kategori_bahan[]" value="resleting" id="chk-resleting">
                            Resleting Celana
                        </label>
                    </div>
                    @error('kategori_bahan')
                        <span style="font-size:0.7rem; color:#e05a5a; font-weight:600; display:block; margin-top:5px;">
                            Pilih minimal satu kategori bahan.
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="noWhatsapp">No WhatsApp / Telepon</label>
                    <input class="form-input" type="text" id="noWhatsapp" name="no_whatsapp" placeholder="08xxxxxxxxxx">
                </div>

                <div class="form-group">
                    <label class="form-label" for="alamat">Alamat Toko / Kantor</label>
                    <textarea class="form-textarea" id="alamat" name="alamat"
                        placeholder="Jl. Raya Contoh No. 1..."></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="deskripsi">Keterangan Spesifik Barang</label>
                    <textarea class="form-textarea" id="deskripsi" name="deskripsi"
                        placeholder="Tulis barang khusus yang disuplai..."></textarea>
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
        // ── Search Filter ────────────────────────────────────────────────
        function filterTable() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#tableBody tr[data-search]');
            rows.forEach(row => {
                row.style.display = row.dataset.search.includes(q) ? '' : 'none';
            });
        }

        // ── Form: Mode Tambah ────────────────────────────────────────────
        function openForm() {
            document.getElementById('formTitle').textContent = 'Tambah Supplier';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('formSupplier').action = '{{ route("admin.supplier.store") }}';
            document.getElementById('supplierId').value = '';
            document.getElementById('namaSupplier').value = '';
            document.getElementById('noWhatsapp').value = '';
            document.getElementById('alamat').value = '';
            document.getElementById('deskripsi').value = '';

            // Uncheck all categories
            document.getElementById('chk-kain').checked = false;
            document.getElementById('chk-kancing').checked = false;
            document.getElementById('chk-benang').checked = false;
            document.getElementById('chk-resleting').checked = false;

            document.getElementById('btnSimpan').textContent = 'Simpan';
            document.getElementById('namaSupplier').focus();
        }

        // ── Form: Mode Edit ──────────────────────────────────────────────
        function editSupplier(id, nama, wa, alamat, kategori, deskripsi) {
            document.getElementById('formTitle').textContent = 'Edit Supplier';
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('formSupplier').action = '/admin/supplier/' + id;
            document.getElementById('supplierId').value = id;
            document.getElementById('namaSupplier').value = nama;
            document.getElementById('noWhatsapp').value = wa;
            document.getElementById('alamat').value = alamat;
            document.getElementById('deskripsi').value = deskripsi;

            // Check selected categories
            document.getElementById('chk-kain').checked = kategori.includes('kain');
            document.getElementById('chk-kancing').checked = kategori.includes('kancing');
            document.getElementById('chk-benang').checked = kategori.includes('benang');
            document.getElementById('chk-resleting').checked = kategori.includes('resleting');

            document.getElementById('btnSimpan').textContent = 'Update';
            document.getElementById('namaSupplier').focus();
            document.getElementById('formPanel').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // ── Reset Form ───────────────────────────────────────────────────
        function resetForm() {
            document.getElementById('formTitle').textContent = 'Tambah Supplier';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('formSupplier').reset();
            document.getElementById('formSupplier').action = '{{ route("admin.supplier.store") }}';
            document.getElementById('btnSimpan').textContent = 'Simpan';
        }
    </script>
@endpush