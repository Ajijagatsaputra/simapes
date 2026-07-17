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

        .product-thumb {
            height: 180px;
            background: linear-gradient(135deg, #e8f0fd 0%, #c5d8f5 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            color: #4A90D9;
        }

        .product-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .3px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
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

        .btn-order {
            display: block;
            width: 100%;
            text-align: center;
            background: #4A90D9;
            color: #fff;
            text-decoration: none;
            padding: 11px;
            border-radius: 10px;
            font-size: .85rem;
            font-weight: 600;
            transition: background 0.15s;
            margin-top: 12px;
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

        .empty-state svg {
            color: #8ca0bf;
            margin-bottom: 12px;
        }

        .empty-state h3 {
            font-size: 1.1rem;
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

        {{-- Grid --}}
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
                @endphp
                <div class="product-card" data-name="{{ strtolower($p->nama_produk) }}" data-category="{{ $p->jenis_seragam }}">
                    <div class="product-thumb">
                        <span
                            class="product-badge {{ $badgeClass }}">{{ $p->jenis_seragam === 'TK' ? 'TK/PAUD' : $p->jenis_seragam }}</span>
                        @if($p->gambar)
                            <img src="{{ asset($p->gambar) }}" alt="{{ $p->nama_produk }}"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <!-- Uniform SVG Icon -->
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                                <path
                                    d="M20.38 3.46L16 6.5V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3.5L3.62 3.46a1 1 0 0 0-1.46.9l1.5 14.5a2 2 0 0 0 2 1.8h12.68a2 2 0 0 0 2-1.8l1.5-14.5a1 1 0 0 0-1.46-.9z" />
                                <path d="M12 2v7M8 9h8" />
                            </svg>
                        @endif
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">{{ $p->nama_produk }}</h3>
                        <p class="product-desc">
                            {{ $p->deskripsi ?? 'Bahan berkualitas premium, jahitan rapi, nyaman digunakan sehari-hari.' }}
                        </p>

                        <div class="product-meta">
                            <span class="product-price">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                            <span class="product-stock {{ $p->stok > 0 ? 'stock-available' : 'stock-empty' }}">
                                {{ $p->stok > 0 ? 'Tersedia (' . $p->stok . ')' : 'Stok Habis' }}
                            </span>
                        </div>

                        @if($p->stok > 0)
                            <a href="{{ route('pelanggan.pesanan.create', ['produk_id' => $p->id]) }}" class="btn-order">
                                Pesan Sekarang
                            </a>
                        @else
                            <button class="btn-order" disabled>Stok Habis</button>
                        @endif
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

    <script>
        let currentCategory = 'all';

        function filterCategory(category, button) {
            currentCategory = category;

            // Toggle active class on buttons
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
                    // Match SMA atau SMK
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
    </script>
@endsection