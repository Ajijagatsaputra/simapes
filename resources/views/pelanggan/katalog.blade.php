@extends('layouts.pelanggan')
@section('title', 'Katalog Seragam - SIMAPES')

@push('styles')
    <style>
        .catalog-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-info h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1a2b4a;
        }

        .page-info p {
            font-size: .85rem;
            color: #6b7e9f;
            margin-top: 4px;
        }

        /* ── Filter Bar ── */
        .filter-bar {
            background: #fff;
            padding: 16px 20px;
            border-radius: 16px;
            border: 1px solid #e2e8f4;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            box-shadow: 0 4px 16px rgba(26, 43, 74, .03);
        }

        .search-box {
            display: flex;
            align-items: center;
            background: #f5f8ff;
            border: 1px solid #dde8f8;
            border-radius: 10px;
            padding: 8px 14px;
            gap: 10px;
            flex: 1;
            min-width: 250px;
        }

        .search-box input {
            border: none;
            background: transparent;
            outline: none;
            font-size: .85rem;
            color: #1a2b4a;
            width: 100%;
        }

        .search-box svg {
            color: #8ca0bf;
        }

        .filter-options {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-btn {
            background: #f0f4fb;
            color: #5a7090;
            border: 1px solid #dde8f8;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: #4A90D9;
            color: #fff;
            border-color: #4A90D9;
        }

        /* ── Grid Katalog ── */
        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 24px;
        }

        .product-card {
            background: #fff;
            border: 1px solid #e2e8f4;
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 16px rgba(26, 43, 74, .02);
            transition: transform 0.22s, box-shadow 0.22s;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(26, 43, 74, .08);
        }

        /* PERBAIKAN: Gambar Utuh (Contain, tidak terpotong!) */
        .product-thumb {
            height: 220px;
            background: #f4f7fc;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            color: #4A90D9;
            padding: 8px;
            cursor: pointer;
        }

        .product-thumb img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-thumb img {
            transform: scale(1.03);
        }

        .product-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .3px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
            z-index: 2;
        }

        /* Badge warna berdasarkan jenis seragam */
        .badge-sd {
            background: #fee2e2;
            color: #ef4444;
        }

        .badge-smp {
            background: #dbeafe;
            color: #2563eb;
        }

        .badge-sma {
            background: #f3f4f6;
            color: #4b5563;
        }

        .badge-umum {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-tk {
            background: #ecfdf5;
            color: #059669;
        }

        .badge-atribut {
            background: #f3e8ff;
            color: #7c3aed;
        }

        .product-info {
            padding: 20px;
            display: flex;
            flex-direction: column;
            flex: 1;
            gap: 8px;
        }

        .product-name {
            font-size: 1rem;
            font-weight: 700;
            color: #1a2b4a;
            line-height: 1.35;
            cursor: pointer;
            transition: color 0.15s;
        }

        .product-name:hover {
            color: #4A90D9;
        }

        .product-desc {
            font-size: .8rem;
            color: #6b7e9f;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: auto;
        }

        .product-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px dashed #e2e8f4;
        }

        .product-price {
            font-size: 1.15rem;
            font-weight: 800;
            color: #4A90D9;
        }

        .product-stock {
            font-size: .75rem;
            font-weight: 600;
        }

        .stock-available {
            color: #10b981;
        }

        .stock-empty {
            color: #ef4444;
        }

        .card-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }

        .btn-detail {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: #eef4ff;
            color: #1A56DB;
            border: 1px solid #c5d8f5;
            padding: 10px;
            border-radius: 10px;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
        }

        .btn-detail:hover {
            background: #dbe7ff;
            color: #1648c4;
        }

        .btn-order {
            flex: 1.2;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #4A90D9;
            color: #fff;
            text-decoration: none;
            padding: 10px;
            border-radius: 10px;
            font-size: .82rem;
            font-weight: 600;
            transition: background 0.15s;
            border: none;
            cursor: pointer;
        }

        .btn-order:hover {
            background: #3a7bc8;
        }

        .btn-order:disabled {
            background: #cbd5e1;
            color: #94a3b8;
            cursor: not-allowed;
        }

        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            background: #fff;
            border-radius: 16px;
            border: 1px dashed #c5d8f5;
            padding: 48px 24px;
        }

        /* ── MODAL DETAIL PRODUK ── */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.97);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-box {
            background: #ffffff;
            width: 100%;
            max-width: 900px;
            max-height: 90vh;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.25);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e2e8f4;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fafcff;
        }

        .modal-header h2 {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1a2b4a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-close-modal {
            background: #f0f4fb;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            font-size: 1.4rem;
            color: #5a7494;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .btn-close-modal:hover {
            background: #e2e8f4;
            color: #1a2b4a;
        }

        .modal-body {
            padding: 24px;
            overflow-y: auto;
            display: grid;
            grid-template-columns: 340px 1fr;
            gap: 28px;
        }

        @media (max-width: 768px) {
            .modal-body {
                grid-template-columns: 1fr;
            }
        }

        .modal-img-container {
            background: #f4f7fc;
            border-radius: 14px;
            border: 1px solid #e2e8f4;
            padding: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 280px;
            position: relative;
        }

        .modal-img-container img {
            max-width: 100%;
            max-height: 320px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .modal-info {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .modal-price-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
            padding: 14px 18px;
            border-radius: 12px;
            border: 1px solid #e2e8f4;
        }

        .modal-price {
            font-size: 1.4rem;
            font-weight: 800;
            color: #1A56DB;
        }

        /* ── Tabs inside Modal ── */
        .modal-tabs {
            display: flex;
            gap: 6px;
            border-bottom: 2px solid #e2e8f4;
            padding-bottom: 2px;
        }

        .tab-btn {
            background: none;
            border: none;
            padding: 10px 14px;
            font-size: .83rem;
            font-weight: 700;
            color: #5a7494;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.2s;
        }

        .tab-btn:hover {
            color: #1A56DB;
        }

        .tab-btn.active {
            color: #1A56DB;
            border-bottom-color: #1A56DB;
        }

        .tab-panel {
            display: none;
            font-size: 0.88rem;
            color: #334155;
            line-height: 1.6;
            padding-top: 8px;
        }

        .tab-panel.active {
            display: block;
        }

        /* Detail Tables */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 0.82rem;
        }

        .detail-table th,
        .detail-table td {
            padding: 10px 12px;
            border: 1px solid #e2e8f4;
            text-align: center;
        }

        .detail-table th {
            background: #f1f5f9;
            color: #1e293b;
            font-weight: 700;
        }

        .detail-table tr:nth-child(even) {
            background: #f8fafc;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #e2e8f4;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background: #fafcff;
        }

        .btn-modal-order {
            background: #1A56DB;
            color: #fff;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9rem;
            transition: background 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-modal-order:hover {
            background: #1648c4;
        }
    </style>
@endpush

@section('content')
    <div class="catalog-container">
        {{-- Header --}}
        <div class="page-header">
            <div class="page-info">
                <h1>Katalog Seragam Sekolah</h1>
                <p>Pilih dan pesan seragam sekolah dengan kualitas jahitan terbaik</p>
            </div>
        </div>

        {{-- Filter & Search --}}
        <div class="filter-bar">
            <div class="search-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input type="text" id="searchInput" placeholder="Cari nama seragam..." oninput="filterKatalog()">
            </div>
            <div class="filter-options">
                <button class="filter-btn active" onclick="filterCategory('all', this)">Semua</button>
                <button class="filter-btn" onclick="filterCategory('TK', this)">TK/PAUD</button>
                <button class="filter-btn" onclick="filterCategory('SD', this)">SD</button>
                <button class="filter-btn" onclick="filterCategory('SMP', this)">SMP</button>
                <button class="filter-btn" onclick="filterCategory('SMA', this)">SMA/SMK</button>
                <button class="filter-btn" onclick="filterCategory('Umum', this)">Umum</button>
                <button class="filter-btn" onclick="filterCategory('Atribut', this)">Atribut</button>
            </div>
        </div>

        {{-- Grid Produk --}}
        <div class="catalog-grid" id="catalogGrid">
            @forelse($produk as $p)
                @php
                    $jenisLower = strtolower($p->jenis_seragam);
                    $badgeClass = 'badge-umum';
                    if ($jenisLower === 'tk') {
                        $badgeClass = 'badge-tk';
                    } elseif (str_contains($jenisLower, 'sd')) {
                        $badgeClass = 'badge-sd';
                    } elseif (str_contains($jenisLower, 'smp')) {
                        $badgeClass = 'badge-smp';
                    } elseif (str_contains($jenisLower, 'sma') || str_contains($jenisLower, 'smk')) {
                        $badgeClass = 'badge-sma';
                    } elseif ($jenisLower === 'atribut') {
                        $badgeClass = 'badge-atribut';
                    }

                    $sizeChartJson = json_encode($p->size_chart_data);
                    $estimasiJson = json_encode($p->estimasi_bb_tb_data);
                @endphp
                <div class="product-card" data-name="{{ strtolower($p->nama_produk) }}" data-category="{{ $p->jenis_seragam }}">
                    {{-- Thumbnail Gambar Utuh --}}
                    <div class="product-thumb" onclick="openDetailModal({{ $p->id }})">
                        <span class="product-badge {{ $badgeClass }}">
                            {{ $p->jenis_seragam === 'TK' ? 'TK/PAUD' : $p->jenis_seragam }}
                        </span>
                        @if($p->gambar)
                            <img src="{{ asset($p->gambar) }}" alt="{{ $p->nama_produk }}">
                        @else
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                                <path
                                    d="M20.38 3.46L16 6.5V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3.5L3.62 3.46a1 1 0 0 0-1.46.9l1.5 14.5a2 2 0 0 0 2 1.8h12.68a2 2 0 0 0 2-1.8l1.5-14.5a1 1 0 0 0-1.46-.9z" />
                                <path d="M12 2v7M8 9h8" />
                            </svg>
                        @endif
                    </div>

                    <div class="product-info">
                        <h3 class="product-name" onclick="openDetailModal({{ $p->id }})">{{ $p->nama_produk }}</h3>
                        <p class="product-desc">
                            {{ $p->deskripsi ?? 'Bahan berkualitas premium, jahitan rapi, nyaman digunakan sehari-hari.' }}
                        </p>

                        <div class="product-meta">
                            <span class="product-price">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                            <span class="product-stock {{ $p->stok > 0 ? 'stock-available' : 'stock-empty' }}">
                                {{ $p->stok > 0 ? 'Tersedia (' . $p->stok . ')' : 'Stok Habis' }}
                            </span>
                        </div>

                        <div class="card-actions">
                            <button type="button" class="btn-detail" onclick="openDetailModal({{ $p->id }})">
                                🔍 Detail
                            </button>

                            @if($p->stok > 0)
                                <a href="{{ route('pelanggan.pesanan.create', ['produk_id' => $p->id]) }}" class="btn-order">
                                    Pesan Sekarang
                                </a>
                            @else
                                <button class="btn-order" disabled>Stok Habis</button>
                            @endif
                        </div>
                    </div>

                    {{-- Data Tersembunyi untuk Modal Detail --}}
                    <div id="product-data-{{ $p->id }}" style="display:none;" data-name="{{ $p->nama_produk }}"
                        data-category="{{ $p->jenis_seragam === 'TK' ? 'TK/PAUD' : $p->jenis_seragam }}"
                        data-badgeclass="{{ $badgeClass }}" data-price="Rp {{ number_format($p->harga, 0, ',', '.') }}"
                        data-stock="{{ $p->stok > 0 ? 'Tersedia (' . $p->stok . ')' : 'Stok Habis' }}"
                        data-stockclass="{{ $p->stok > 0 ? 'stock-available' : 'stock-empty' }}"
                        data-image="{{ $p->gambar ? asset($p->gambar) : '' }}"
                        data-desc="{{ $p->deskripsi ?? 'Seragam sekolah dibuat dari bahan berkualitas tinggi, jahitan rapi ganda, serta nyaman dipakai sepanjang hari.' }}"
                        data-bahan="{{ $p->spesifikasi_bahan_formatted }}"
                        data-sizechartcustom="{{ $p->size_chart_custom ?? '' }}" data-sizechart='{{ $sizeChartJson }}'
                        data-estimasicustom="{{ $p->estimasi_bb_tb_custom ?? '' }}" data-estimasi='{{ $estimasiJson }}'
                        data-orderurl="{{ route('pelanggan.pesanan.create', ['produk_id' => $p->id]) }}"
                        data-hasstock="{{ $p->stok > 0 ? '1' : '0' }}">
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="8" y1="12" x2="16" y2="12" />
                    </svg>
                    <h3>Katalog Kosong</h3>
                    <p>Belum ada produk seragam yang terdaftar di katalog saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- MODAL DETAIL PRODUK --}}
    <div class="modal-overlay" id="modalDetailOverlay" onclick="closeDetailModalOnBackdrop(event)">
        <div class="modal-box">
            <div class="modal-header">
                <h2>
                    <span id="mCategoryBadge" class="product-badge"></span>
                    <span id="mTitle">Detail Produk</span>
                </h2>
                <button type="button" class="btn-close-modal" onclick="closeDetailModal()">&times;</button>
            </div>

            <div class="modal-body">
                {{-- Kolom Kiri: Gambar Utuh --}}
                <div class="modal-img-container" id="mImgBox">
                    <img id="mImage" src="" alt="Gambar Produk">
                </div>

                {{-- Kolom Kanan: Detail & Tabs --}}
                <div class="modal-info">
                    <div class="modal-price-row">
                        <div>
                            <div style="font-size: 0.78rem; color: #6b7e9f; font-weight: 600;">HARGA SATUAN</div>
                            <div class="modal-price" id="mPrice">Rp 0</div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 0.78rem; color: #6b7e9f; font-weight: 600;">STATUS STOK</div>
                            <div id="mStock" class="product-stock" style="font-size: 0.9rem;"></div>
                        </div>
                    </div>

                    {{-- Tabs --}}
                    <div class="modal-tabs">
                        <button type="button" class="tab-btn active" onclick="switchTab('tabBahan', this)">🧵 Spesifikasi
                            Bahan</button>
                        <button type="button" class="tab-btn" onclick="switchTab('tabSizeChart', this)">📏 Size
                            Chart</button>
                        <button type="button" class="tab-btn" onclick="switchTab('tabEstimasi', this)">⚖️ Estimasi
                            BB/TB</button>
                        <button type="button" class="tab-btn" onclick="switchTab('tabDesc', this)">📝 Deskripsi</button>
                    </div>

                    {{-- Content Panels --}}
                    <div class="tab-panel active" id="tabBahan">
                        <div id="mBahan" style="white-space: pre-line;"></div>
                    </div>

                    <div class="tab-panel" id="tabSizeChart">
                        <p style="font-size: 0.8rem; color: #64748b; margin-bottom: 6px;">
                            *Toleransi ukuran jahitan &plusmn; 1-2 cm.
                        </p>
                        <div id="mSizeChartText"
                            style="display:none; white-space: pre-line; background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f4; margin-bottom: 8px;">
                        </div>
                        <table class="detail-table" id="mSizeChartTable">
                            <thead>
                                <tr>
                                    <th>Ukuran (Size)</th>
                                    <th>Lebar Dada (LD)</th>
                                    <th>Panjang Baju (PB)</th>
                                    <th>Lingkar Pinggang</th>
                                    <th>Panjang Celana/Rok</th>
                                </tr>
                            </thead>
                            <tbody id="mSizeChartBody"></tbody>
                        </table>
                    </div>

                    <div class="tab-panel" id="tabEstimasi">
                        <p style="font-size: 0.8rem; color: #64748b; margin-bottom: 6px;">
                            *Panduan rekomendasi ukuran berdasarkan Berat Badan (BB) & Tinggi Badan (TB).
                        </p>
                        <div id="mEstimasiText"
                            style="display:none; white-space: pre-line; background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f4; margin-bottom: 8px;">
                        </div>
                        <table class="detail-table" id="mEstimasiTable">
                            <thead>
                                <tr>
                                    <th>Ukuran (Size)</th>
                                    <th>Estimasi BB</th>
                                    <th>Estimasi TB</th>
                                </tr>
                            </thead>
                            <tbody id="mEstimasiBody"></tbody>
                        </table>
                    </div>

                    <div class="tab-panel" id="tabDesc">
                        <p id="mDesc" style="white-space: pre-line;"></p>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="filter-btn" onclick="closeDetailModal()">Tutup</button>
                <a id="mOrderBtn" href="#" class="btn-modal-order">
                    🛒 Pesan Sekarang
                </a>
            </div>
        </div>
    </div>

    <script>
        let currentCategory = 'all';

        function filterCategory(category, button) {
            currentCategory = category;
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            filterKatalog();
        }

        function filterKatalog() {
            const query = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.product-card');

            cards.forEach(card => {
                const name = card.getAttribute('data-name');
                const category = card.getAttribute('data-category');

                let matchesCategory = false;
                if (currentCategory === 'all') {
                    matchesCategory = true;
                } else if (currentCategory === 'SMA') {
                    matchesCategory = category.toLowerCase().includes('sma') || category.toLowerCase().includes('smk');
                } else if (currentCategory === 'Atribut') {
                    matchesCategory = category === 'Atribut';
                } else {
                    matchesCategory = category.toLowerCase() === currentCategory.toLowerCase();
                }

                const matchesSearch = name.includes(query);
                card.style.display = (matchesSearch && matchesCategory) ? 'flex' : 'none';
            });
        }

        // Modal Functions
        function openDetailModal(id) {
            const dataEl = document.getElementById(`product-data-${id}`);
            if (!dataEl) return;

            document.getElementById('mTitle').textContent = dataEl.dataset.name;
            document.getElementById('mCategoryBadge').textContent = dataEl.dataset.category;
            document.getElementById('mCategoryBadge').className = `product-badge ${dataEl.dataset.badgeclass}`;
            document.getElementById('mPrice').textContent = dataEl.dataset.price;

            const stockEl = document.getElementById('mStock');
            stockEl.textContent = dataEl.dataset.stock;
            stockEl.className = `product-stock ${dataEl.dataset.stockclass}`;

            // Image
            const imgBox = document.getElementById('mImgBox');
            if (dataEl.dataset.image) {
                imgBox.innerHTML = `<img src="${dataEl.dataset.image}" alt="${dataEl.dataset.name}">`;
            } else {
                imgBox.innerHTML = `
                                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#4A90D9" stroke-width="1.2">
                                    <path d="M20.38 3.46L16 6.5V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3.5L3.62 3.46a1 1 0 0 0-1.46.9l1.5 14.5a2 2 0 0 0 2 1.8h12.68a2 2 0 0 0 2-1.8l1.5-14.5a1 1 0 0 0-1.46-.9z"/>
                                    <path d="M12 2v7M8 9h8"/>
                                </svg>`;
            }

            // Bahan & Desc
            document.getElementById('mBahan').textContent = dataEl.dataset.bahan;
            document.getElementById('mDesc').textContent = dataEl.dataset.desc;

            // Size Chart: Custom Text vs Default Table
            if (dataEl.dataset.sizechartcustom && dataEl.dataset.sizechartcustom.trim() !== '') {
                document.getElementById('mSizeChartText').textContent = dataEl.dataset.sizechartcustom;
                document.getElementById('mSizeChartText').style.display = 'block';
                document.getElementById('mSizeChartTable').style.display = 'none';
            } else {
                document.getElementById('mSizeChartText').style.display = 'none';
                document.getElementById('mSizeChartTable').style.display = 'table';
                const sizeChart = JSON.parse(dataEl.dataset.sizechart || '[]');
                const scTbody = document.getElementById('mSizeChartBody');
                scTbody.innerHTML = '';
                sizeChart.forEach(row => {
                    scTbody.innerHTML += `
                                    <tr>
                                        <td><strong>${row.size}</strong></td>
                                        <td>${row.baju_ld || '-'}</td>
                                        <td>${row.baju_pb || '-'}</td>
                                        <td>${row.bawahan_lp || '-'}</td>
                                        <td>${row.bawahan_pc || '-'}</td>
                                    </tr>`;
                });
            }

            // Estimasi BB/TB: Custom Text vs Default Table
            if (dataEl.dataset.estimasicustom && dataEl.dataset.estimasicustom.trim() !== '') {
                document.getElementById('mEstimasiText').textContent = dataEl.dataset.estimasicustom;
                document.getElementById('mEstimasiText').style.display = 'block';
                document.getElementById('mEstimasiTable').style.display = 'none';
            } else {
                document.getElementById('mEstimasiText').style.display = 'none';
                document.getElementById('mEstimasiTable').style.display = 'table';
                const estimasi = JSON.parse(dataEl.dataset.estimasi || '[]');
                const estTbody = document.getElementById('mEstimasiBody');
                estTbody.innerHTML = '';
                estimasi.forEach(row => {
                    estTbody.innerHTML += `
                                    <tr>
                                        <td><strong>${row.size}</strong></td>
                                        <td>${row.bb || '-'}</td>
                                        <td>${row.tb || '-'}</td>
                                    </tr>`;
                });
            }

            // Order Button
            const orderBtn = document.getElementById('mOrderBtn');
            if (dataEl.dataset.hasstock === '1') {
                orderBtn.href = dataEl.dataset.orderurl;
                orderBtn.style.display = 'inline-flex';
            } else {
                orderBtn.style.display = 'none';
            }

            // Reset tab to default
            switchTab('tabBahan', document.querySelector('.modal-tabs .tab-btn'));

            document.getElementById('modalDetailOverlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeDetailModal() {
            document.getElementById('modalDetailOverlay').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function closeDetailModalOnBackdrop(e) {
            if (e.target.id === 'modalDetailOverlay') {
                closeDetailModal();
            }
        }

        function switchTab(tabId, btn) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));

            if (btn) btn.classList.add('active');
            const targetPanel = document.getElementById(tabId);
            if (targetPanel) targetPanel.classList.add('active');
        }
    </script>
@endsection