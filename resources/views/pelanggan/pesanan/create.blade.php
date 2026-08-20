@extends('layouts.pelanggan')
@section('title', 'Buat Pesanan Seragam - SIMAPES')

@push('styles')
    <style>
        .order-container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .order-container {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f4;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(26, 43, 74, .03);
            margin-bottom: 24px;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a2b4a;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px dashed #e2e8f4;
            padding-bottom: 12px;
        }

        .card-title-text {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── Product Card Block (Multi-Size) ── */
        .product-block {
            background: #ffffff;
            border: 1.5px solid #e2e8f4;
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 18px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .product-block:hover {
            border-color: #bad3f5;
            box-shadow: 0 6px 20px rgba(26, 79, 171, 0.05);
        }

        .product-block-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f4fb;
        }

        .product-block-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .product-block-thumb {
            width: 52px;
            height: 52px;
            border-radius: 10px;
            background: #f4f7fc;
            border: 1px solid #e2e8f4;
            display: flex;
            align-items: center;
            justify-content: center;
            object-fit: contain;
            flex-shrink: 0;
            overflow: hidden;
        }

        .product-block-thumb img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .product-block-title h4 {
            font-size: 0.98rem;
            font-weight: 700;
            color: #1a2b4a;
            margin-bottom: 2px;
        }

        .product-block-title .price-tag {
            font-size: 0.85rem;
            font-weight: 800;
            color: #1A56DB;
        }

        .btn-remove-block {
            background: #fde8e8;
            color: #e05a5a;
            border: none;
            border-radius: 8px;
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-remove-block:hover {
            background: #e05a5a;
            color: #fff;
        }

        /* ── Multi-Size Grid (FR-ORD-02) ── */
        .size-grid-label {
            font-size: 0.78rem;
            font-weight: 700;
            color: #5a7090;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .size-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(88px, 1fr));
            gap: 10px;
            margin-bottom: 14px;
        }

        .size-item-box {
            background: #f8fafc;
            border: 1px solid #e2e8f4;
            border-radius: 10px;
            padding: 8px;
            text-align: center;
            transition: all 0.15s;
        }

        .size-item-box:focus-within {
            border-color: #1A56DB;
            background: #ffffff;
            box-shadow: 0 0 0 2px rgba(26, 86, 219, 0.12);
        }

        .size-name {
            font-size: 0.75rem;
            font-weight: 800;
            color: #1a2b4a;
            margin-bottom: 4px;
        }

        .size-qty-input {
            width: 100%;
            text-align: center;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 6px 4px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            outline: none;
            background: #ffffff;
            color: #1a2b4a;
        }

        /* All Size Single Input (FR-ORD-03) */
        .all-size-box {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .all-size-info {
            font-size: 0.85rem;
            font-weight: 700;
            color: #1e40af;
        }

        .all-size-qty-input {
            width: 110px;
            text-align: center;
            font-weight: 800;
            font-size: 1rem;
            padding: 8px;
            border: 2px solid #3b82f6;
            border-radius: 8px;
            outline: none;
        }

        .block-extra-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #e2e8f4;
        }

        @media (max-width: 600px) {
            .block-extra-row {
                grid-template-columns: 1fr;
            }
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: 9px 12px;
            font-family: inherit;
            font-size: .82rem;
            color: #1a2b4a;
            background: #f5f8ff;
            border: 1.5px solid #c5d8f5;
            border-radius: 10px;
            outline: none;
            transition: all 0.2s;
        }

        .form-input:focus,
        .form-textarea:focus {
            border-color: #1A56DB;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.12);
        }

        /* ── Search & Select Product Button & Modal (FR-ORD-01) ── */
        .btn-open-search {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #1A56DB;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 18px;
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-open-search:hover {
            background: #1648c4;
        }

        .btn-csv-upload {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0f4fb;
            color: #1A4FAB;
            border: 1px solid #c5d8f5;
            border-radius: 10px;
            padding: 8px 14px;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-csv-upload:hover {
            background: #e2e8f4;
        }

        /* Search Modal Overlay */
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

        .modal-box {
            background: #ffffff;
            width: 100%;
            max-width: 760px;
            max-height: 85vh;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(15, 23, 42, 0.25);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .modal-header {
            padding: 18px 24px;
            border-bottom: 1px solid #e2e8f4;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fafcff;
        }

        .modal-header h3 {
            font-size: 1.15rem;
            font-weight: 800;
            color: #1a2b4a;
        }

        .btn-close-modal {
            background: #f0f4fb;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            font-size: 1.3rem;
            color: #5a7494;
            cursor: pointer;
        }

        .modal-search-bar {
            padding: 16px 24px;
            border-bottom: 1px solid #f0f4fb;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .modal-search-input {
            width: 100%;
            padding: 11px 16px;
            font-size: 0.9rem;
            border: 2px solid #c5d8f5;
            border-radius: 12px;
            outline: none;
            background: #f8fafc;
        }

        .modal-search-input:focus {
            border-color: #1A56DB;
            background: #ffffff;
        }

        .modal-pills {
            display: flex;
            gap: 6px;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .pill-btn {
            background: #f0f4fb;
            color: #5a7494;
            border: 1px solid #dde8f8;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
        }

        .pill-btn.active {
            background: #1A56DB;
            color: #ffffff;
            border-color: #1A56DB;
        }

        .modal-product-list {
            padding: 16px 24px;
            overflow-y: auto;
            max-height: 50vh;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .search-product-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            border: 1px solid #e2e8f4;
            border-radius: 12px;
            padding: 12px 16px;
            cursor: pointer;
            transition: all 0.15s;
        }

        .search-product-item:hover {
            border-color: #1A56DB;
            background: #f5f8ff;
            transform: translateX(3px);
        }

        .item-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .item-thumb {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: #f4f7fc;
            display: flex;
            align-items: center;
            justify-content: center;
            object-fit: contain;
            overflow: hidden;
            flex-shrink: 0;
        }

        .item-thumb img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .item-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #1a2b4a;
        }

        .item-price {
            font-size: 0.88rem;
            font-weight: 800;
            color: #1A56DB;
        }

        .badge-cat {
            font-size: 0.7rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 12px;
            background: #eef4ff;
            color: #1A56DB;
            display: inline-block;
            margin-top: 2px;
        }

        /* Summary Card */
        .summary-box {
            background: #fafcff;
            border: 1.5px solid #d0e1fd;
            border-radius: 14px;
            padding: 18px;
            margin-top: 16px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: .85rem;
            color: #5a7090;
            margin-bottom: 8px;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.15rem;
            font-weight: 800;
            color: #1a2b4a;
            border-top: 1px dashed #c5d8f5;
            padding-top: 10px;
            margin-top: 8px;
        }

        .btn-submit-order {
            width: 100%;
            background: #1A56DB;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-size: .95rem;
            font-weight: 800;
            cursor: pointer;
            transition: background 0.15s;
            margin-top: 16px;
        }

        .btn-submit-order:hover {
            background: #1648c4;
        }

        /* ===== PREMIUM TOAST NOTIFICATION (sp-prefixed to avoid layout conflict) ===== */
        #spt-container {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 99999;
            display: flex;
            flex-direction: column-reverse;
            gap: 12px;
            pointer-events: none;
            align-items: flex-end;
        }

        .spt {
            display: flex;
            align-items: center;
            gap: 14px;
            border-radius: 18px;
            padding: 0;
            min-width: 340px;
            max-width: 420px;
            pointer-events: all;
            position: relative;
            overflow: hidden;
            animation: sptBounceIn 0.5s cubic-bezier(.34, 1.56, .64, 1) both;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.18),
                0 8px 24px rgba(0, 0, 0, 0.10),
                inset 0 1px 0 rgba(255, 255, 255, 0.25);
        }

        .spt.spt-warning {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 50%, #fde68a22 100%);
            border: 1.5px solid rgba(245, 158, 11, 0.3);
        }

        .spt.spt-success {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 50%, #bbf7d022 100%);
            border: 1.5px solid rgba(16, 185, 129, 0.3);
        }

        .spt.spt-error {
            background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 50%, #fecdd322 100%);
            border: 1.5px solid rgba(239, 68, 68, 0.3);
        }

        .spt.spt-info {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 50%, #bfdbfe22 100%);
            border: 1.5px solid rgba(26, 86, 219, 0.25);
        }

        /* Left accent stripe */
        .spt::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 5px;
            border-radius: 18px 0 0 18px;
        }

        .spt-warning::before {
            background: linear-gradient(180deg, #fbbf24, #f59e0b);
        }

        .spt-success::before {
            background: linear-gradient(180deg, #34d399, #10b981);
        }

        .spt-error::before {
            background: linear-gradient(180deg, #f87171, #ef4444);
        }

        .spt-info::before {
            background: linear-gradient(180deg, #60a5fa, #1A56DB);
        }

        /* Shimmer sweep */
        .spt::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: sptShimmer 2.5s ease-in-out 0.4s 1;
            pointer-events: none;
        }

        .spt-inner {
            display: flex;
            align-items: center;
            gap: 14px;
            width: 100%;
            padding: 16px 18px 16px 20px;
        }

        .spt-icon {
            flex-shrink: 0;
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .spt-warning .spt-icon {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            box-shadow: 0 6px 20px rgba(245, 158, 11, 0.45);
        }

        .spt-success .spt-icon {
            background: linear-gradient(135deg, #34d399, #059669);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.45);
        }

        .spt-error .spt-icon {
            background: linear-gradient(135deg, #f87171, #dc2626);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.45);
        }

        .spt-info .spt-icon {
            background: linear-gradient(135deg, #60a5fa, #1A56DB);
            box-shadow: 0 6px 20px rgba(26, 86, 219, 0.40);
        }

        .spt-icon svg {
            width: 22px;
            height: 22px;
            stroke: #fff;
            fill: none;
        }

        .spt-body {
            flex: 1;
            min-width: 0;
        }

        .spt-title {
            font-size: 0.87rem;
            font-weight: 800;
            margin-bottom: 3px;
            letter-spacing: 0.01em;
            line-height: 1.2;
        }

        .spt-warning .spt-title {
            color: #78350f;
        }

        .spt-success .spt-title {
            color: #064e3b;
        }

        .spt-error .spt-title {
            color: #7f1d1d;
        }

        .spt-info .spt-title {
            color: #1e3a8a;
        }

        .spt-msg {
            font-size: 0.82rem;
            line-height: 1.5;
        }

        .spt-warning .spt-msg {
            color: #92400e;
        }

        .spt-success .spt-msg {
            color: #065f46;
        }

        .spt-error .spt-msg {
            color: #991b1b;
        }

        .spt-info .spt-msg {
            color: #1e40af;
        }

        .spt-close {
            flex-shrink: 0;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.07);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(0, 0, 0, 0.4);
            transition: background 0.2s, color 0.2s, transform 0.15s;
            margin-right: 4px;
        }

        .spt-close:hover {
            background: rgba(0, 0, 0, 0.14);
            color: rgba(0, 0, 0, 0.7);
            transform: rotate(90deg);
        }

        .spt-close svg {
            pointer-events: none;
        }

        .spt-bar {
            position: absolute;
            bottom: 0;
            left: 5px;
            height: 3px;
            animation: sptProgress 4.5s linear forwards;
        }

        .spt-warning .spt-bar {
            background: linear-gradient(90deg, #fbbf24, #f59e0b);
        }

        .spt-success .spt-bar {
            background: linear-gradient(90deg, #34d399, #10b981);
        }

        .spt-error .spt-bar {
            background: linear-gradient(90deg, #f87171, #ef4444);
        }

        .spt-info .spt-bar {
            background: linear-gradient(90deg, #60a5fa, #1A56DB);
        }

        .spt.spt-hide {
            animation: sptSlideOut 0.4s cubic-bezier(.55, 0, .1, 1) forwards;
        }

        @keyframes sptBounceIn {
            0% {
                opacity: 0;
                transform: translateX(100px) scale(0.7);
            }

            60% {
                opacity: 1;
                transform: translateX(-8px) scale(1.02);
            }

            80% {
                transform: translateX(4px) scale(0.99);
            }

            100% {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        @keyframes sptSlideOut {
            0% {
                opacity: 1;
                transform: translateX(0) scale(1);
                max-height: 100px;
                margin-bottom: 0;
            }

            100% {
                opacity: 0;
                transform: translateX(80px) scale(0.9);
                max-height: 0;
                margin-bottom: -14px;
                padding: 0;
            }
        }

        @keyframes sptProgress {
            from {
                width: calc(100% - 5px);
            }

            to {
                width: 0%;
            }
        }

        @keyframes sptShimmer {
            0% {
                left: -100%;
            }

            100% {
                left: 150%;
            }
        }
    </style>
@endpush

@section('content')
    {{-- PREMIUM TOAST CONTAINER --}}
    <div id="spt-container"></div>

    <form action="{{ route('pelanggan.pesanan.store') }}" method="POST" enctype="multipart/form-data" id="orderForm">
        @csrf
        <div class="order-container">
            {{-- Kolom Kiri: Daftar Produk yang Dipesan --}}
            <div class="left-col">
                <div class="card">
                    <div class="card-title">
                        <div class="card-title-text">
                            🛍️ Item Produk yang Dipesan
                        </div>
                        <div style="display: flex; gap: 8px;">
                            <button type="button" class="btn-csv-upload" onclick="openCsvModal()">
                                📄 Upload CSV / Excel
                            </button>
                            <button type="button" class="btn-open-search" onclick="openSearchModal()">
                                🔍 + Tambah Produk
                            </button>
                        </div>
                    </div>

                    @if(session('error'))
                        <div
                            style="background: #fde8e8; color: #9b1c1c; padding: 12px 16px; border-radius: 10px; font-size: 0.85rem; margin-bottom: 16px;">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Container Produk Terpilih --}}
                    <div id="selectedProductsContainer">
                        {{-- Diisi secara dinamis via JavaScript --}}
                    </div>

                    {{-- Empty State jika belum ada produk --}}
                    <div id="emptyOrderState"
                        style="text-align: center; padding: 40px 20px; border: 2px dashed #c5d8f5; border-radius: 14px; background: #f8fafc;">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#8ca0bf" stroke-width="1.5"
                            style="margin-bottom: 10px;">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                        <h4 style="font-size: 1rem; color: #1a2b4a; font-weight: 700; margin-bottom: 4px;">Belum Ada Produk
                            Dipilih</h4>
                        <p style="font-size: 0.83rem; color: #6b7e9f; margin-bottom: 16px;">Klik "+ Tambah Produk" untuk
                            mencari dan memilih seragam yang ingin dipesan.</p>
                        <button type="button" class="btn-open-search" onclick="openSearchModal()">
                            🔍 Cari & Pilih Produk
                        </button>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Ringkasan Pesanan & Target Tanggal --}}
            <div class="right-col">
                <div class="card">
                    <div class="card-title">
                        <div class="card-title-text">
                            📋 Ringkasan Transaksi
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 14px; margin-bottom: 16px;">
                        <label class="form-label" style="font-weight: 700; color: #1a2b4a; font-size: 0.85rem;">
                            📅 Target Tanggal Pengambilan <span style="color: #e05a5a;">*</span>
                        </label>
                        <input type="date" name="target_tanggal_pengambilan" id="targetTanggalPengambilan"
                            class="form-input" min="{{ date('Y-m-d') }}" value="{{ old('target_tanggal_pengambilan') }}"
                            required onchange="updateOrderSummary()">
                        <span
                            style="font-size: 0.72rem; color: #8ca0bf; margin-top: 4px; display: block; line-height: 1.4;">
                            Wajib menentukan tanggal rencana pengambilan pesanan seragam.
                        </span>
                    </div>

                    <div class="summary-box">
                        <div class="summary-row">
                            <span>Jumlah Produk Unik</span>
                            <span id="summaryUniqueCount" style="font-weight: 700;">0 Produk</span>
                        </div>
                        <div class="summary-row">
                            <span>Total Pcs (Item)</span>
                            <span id="summaryTotalPcs" style="font-weight: 700;">0 pcs</span>
                        </div>
                        <div class="summary-total">
                            <span>Total Harga</span>
                            <span id="summaryTotalPrice" style="color: #1A56DB;">Rp 0</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit-order" id="btnSubmitOrder" disabled>
                        Ajukan Pesanan Sekarang
                    </button>
                </div>
            </div>
    </form>

    {{-- MODAL SEARCH & SELECT PRODUCT (FR-ORD-01) --}}
    <div class="modal-overlay" id="searchModalOverlay" onclick="closeSearchModalOnBackdrop(event)">
        <div class="modal-box">
            <div class="modal-header">
                <h3>🔍 Pilih Produk Seragam</h3>
                <button type="button" class="btn-close-modal" onclick="closeSearchModal()">&times;</button>
            </div>
            <div class="modal-search-bar">
                <input type="text" id="modalSearchInput" class="modal-search-input"
                    placeholder="Cari nama seragam sekolah..." oninput="filterModalProducts()">
                <div class="modal-pills">
                    <button type="button" class="pill-btn active" onclick="filterModalCategory('all', this)">Semua</button>
                    <button type="button" class="pill-btn" onclick="filterModalCategory('TK', this)">TK/PAUD</button>
                    <button type="button" class="pill-btn" onclick="filterModalCategory('SD', this)">SD</button>
                    <button type="button" class="pill-btn" onclick="filterModalCategory('SMP', this)">SMP</button>
                    <button type="button" class="pill-btn" onclick="filterModalCategory('SMA', this)">SMA/SMK</button>
                    <button type="button" class="pill-btn" onclick="filterModalCategory('Umum', this)">Umum</button>
                    <button type="button" class="pill-btn" onclick="filterModalCategory('Atribut', this)">Atribut</button>
                </div>
            </div>

            <div class="modal-product-list" id="modalProductList">
                @foreach($produk as $p)
                    @php
                        $isAtribut = (strtolower($p->jenis_seragam) === 'atribut');
                    @endphp
                    <div class="search-product-item" data-name="{{ strtolower($p->nama_produk) }}"
                        data-category="{{ $p->jenis_seragam }}"
                        onclick="selectProductFromModal({{ $p->id }}, '{{ addslashes($p->nama_produk) }}', '{{ $p->jenis_seragam }}', {{ (int) $p->harga }}, '{{ $p->gambar ? asset($p->gambar) : '' }}', {{ $isAtribut ? 'true' : 'false' }})">
                        <div class="item-left">
                            <div class="item-thumb">
                                @if($p->gambar)
                                    <img src="{{ asset($p->gambar) }}" alt="{{ $p->nama_produk }}">
                                @else
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8ca0bf" stroke-width="1.5">
                                        <path
                                            d="M20.38 3.46L16 6.5V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3.5L3.62 3.46a1 1 0 0 0-1.46.9l1.5 14.5a2 2 0 0 0 2 1.8h12.68a2 2 0 0 0 2-1.8l1.5-14.5a1 1 0 0 0-1.46-.9z" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <div class="item-title">{{ $p->nama_produk }}</div>
                                <span class="badge-cat">{{ $p->jenis_seragam === 'TK' ? 'TK/PAUD' : $p->jenis_seragam }}</span>
                            </div>
                        </div>
                        <div class="item-price">
                            Rp {{ number_format($p->harga, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- MODAL UPLOAD CSV / EXCEL --}}
    <div class="modal-overlay" id="csvModalOverlay" onclick="closeCsvModalOnBackdrop(event)">
        <div class="modal-box" style="max-width: 520px;">
            <div class="modal-header">
                <h3>📄 Import Pesanan Massal (CSV)</h3>
                <button type="button" class="btn-close-modal" onclick="closeCsvModal()">&times;</button>
            </div>
            <div style="padding: 24px;">
                <p style="font-size: 0.85rem; color: #5a7090; margin-bottom: 16px;">
                    Upload file CSV sesuai format template SIMAPES untuk memasukkan pesanan massal secara otomatis.
                </p>
                <div style="margin-bottom: 16px;">
                    <a href="{{ route('pelanggan.pesanan.template') }}" class="btn-csv-upload"
                        style="display: inline-flex; width: 100%; justify-content: center;">
                        ⬇️ Download Template CSV
                    </a>
                </div>
                <div class="form-group">
                    <label class="form-label">Pilih File CSV</label>
                    <input type="file" id="csvFileInput" accept=".csv, .txt, .xlsx, .xls" class="form-input">
                </div>
                <button type="button" class="btn-submit-order" onclick="processCsvUpload()" style="margin-top: 10px;">
                    Proses Import Pesanan
                </button>
            </div>
        </div>
    </div>

    <script>
        const allProducts = @json($produk);
        let selectedProductBlocks = []; // Dataset produk yang ada di form
        let modalCategoryFilter = 'all';

        const standardSizes = ['S', 'M', 'L', 'XL', 'XXL', '3XL', '4XL', '5XL'];

        document.addEventListener('DOMContentLoaded', function () {
            // Auto add product if URL query param ?produk_id=XX exists
            const urlParams = new URLSearchParams(window.location.search);
            const initialProdukId = urlParams.get('produk_id');
            if (initialProdukId) {
                const found = allProducts.find(p => p.id == initialProdukId);
                if (found) {
                    const isAtribut = (found.jenis_seragam.toLowerCase() === 'atribut');
                    addProductBlock(found.id, found.nama_produk, found.jenis_seragam, parseInt(found.harga), found.gambar ? `/` + found.gambar : '', isAtribut);
                }
            }
        });

        // Open & Close Search Modal
        function openSearchModal() {
            document.getElementById('searchModalOverlay').classList.add('active');
            document.getElementById('modalSearchInput').focus();
        }

        function closeSearchModal() {
            document.getElementById('searchModalOverlay').classList.remove('active');
        }

        function closeSearchModalOnBackdrop(e) {
            if (e.target.id === 'searchModalOverlay') closeSearchModal();
        }

        // Filter Products inside Modal
        function filterModalCategory(cat, btn) {
            modalCategoryFilter = cat;
            document.querySelectorAll('.modal-pills .pill-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            filterModalProducts();
        }

        function filterModalProducts() {
            const query = document.getElementById('modalSearchInput').value.toLowerCase();
            const items = document.querySelectorAll('#modalProductList .search-product-item');

            items.forEach(item => {
                const name = item.dataset.name;
                const cat = item.dataset.category;

                let matchCat = (modalCategoryFilter === 'all') ||
                    (modalCategoryFilter === 'SMA' && (cat.toLowerCase().includes('sma') || cat.toLowerCase().includes('smk'))) ||
                    (cat.toLowerCase() === modalCategoryFilter.toLowerCase());

                let matchQuery = name.includes(query);
                item.style.display = (matchCat && matchQuery) ? 'flex' : 'none';
            });
        }

        // Select product from modal
        function selectProductFromModal(id, nama, jenis, harga, gambar, isAtribut) {
            closeSearchModal();
            addProductBlock(id, nama, jenis, harga, gambar, isAtribut);
        }

        // Add Product Card Block to Form (FR-ORD-01, FR-ORD-02, FR-ORD-03)
        function addProductBlock(id, nama, jenis, harga, gambar, isAtribut) {
            // Check if already added
            const existingIndex = selectedProductBlocks.findIndex(b => b.id === id);
            if (existingIndex !== -1) {
                showSptToast('warning', 'Produk Sudah Ada', `Produk "${nama}" sudah ada di dalam daftar pesanan Anda.`);
                return;
            }

            const blockIndex = selectedProductBlocks.length;
            const blockData = {
                index: blockIndex,
                id: id,
                nama: nama,
                jenis: jenis,
                harga: harga,
                gambar: gambar,
                isAtribut: isAtribut,
                sizes: {}
            };

            selectedProductBlocks.push(blockData);
            renderSelectedBlocks();
        }

        // Render Product Blocks
        function renderSelectedBlocks() {
            const container = document.getElementById('selectedProductsContainer');
            const emptyState = document.getElementById('emptyOrderState');

            if (selectedProductBlocks.length === 0) {
                container.innerHTML = '';
                emptyState.style.display = 'block';
                updateOrderSummary();
                return;
            }

            emptyState.style.display = 'none';
            container.innerHTML = '';

            selectedProductBlocks.forEach((block, idx) => {
                const formattedPrice = new Intl.NumberFormat('id-ID').format(block.harga);
                const badgeLabel = block.jenis === 'TK' ? 'TK/PAUD' : block.jenis;

                let sizesHtml = '';

                if (block.isAtribut) {
                    // FR-ORD-03: All Size Single Input
                    const qtyVal = block.sizes['All Size'] || '';
                    sizesHtml = `
                                                    <div class="all-size-box">
                                                        <div class="all-size-info">
                                                            🏷️ Kategori Atribut (All Size)
                                                        </div>
                                                        <div style="display: flex; align-items: center; gap: 8px;">
                                                            <label style="font-size: 0.8rem; font-weight: 700; color: #1e40af;">Jumlah (pcs):</label>
                                                            <input type="number" min="0" class="all-size-qty-input"
                                                                name="items[${idx}][sizes][All Size]"
                                                                value="${qtyVal}"
                                                                placeholder="0"
                                                                oninput="updateBlockSizeQty(${idx}, 'All Size', this.value)">
                                                        </div>
                                                    </div>`;
                } else {
                    // FR-ORD-02: Multi-Size Grid (S, M, L, XL, XXL, 3XL, 4XL, 5XL)
                    let gridItems = '';
                    standardSizes.forEach(sz => {
                        const qtyVal = block.sizes[sz] || '';
                        gridItems += `
                                                        <div class="size-item-box">
                                                            <div class="size-name">${sz}</div>
                                                            <input type="number" min="0" class="size-qty-input"
                                                                name="items[${idx}][sizes][${sz}]"
                                                                value="${qtyVal}"
                                                                placeholder="0"
                                                                oninput="updateBlockSizeQty(${idx}, '${sz}', this.value)">
                                                        </div>`;
                    });

                    sizesHtml = `
                                                    <div class="size-grid-label">Pilih Ukuran & Jumlah (Multi-Ukuran):</div>
                                                    <div class="size-grid">${gridItems}</div>`;
                }

                const blockHtml = `
                                                <div class="product-block" id="pBlock-${idx}">
                                                    <input type="hidden" name="items[${idx}][produk_id]" value="${block.id}">

                                                    <div class="product-block-header">
                                                        <div class="product-block-info">
                                                            <div class="product-block-thumb">
                                                                ${block.gambar ? `<img src="${block.gambar}" alt="${block.nama}">` : `
                                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8ca0bf" stroke-width="1.5">
                                                                        <path d="M20.38 3.46L16 6.5V3a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3.5L3.62 3.46a1 1 0 0 0-1.46.9l1.5 14.5a2 2 0 0 0 2 1.8h12.68a2 2 0 0 0 2-1.8l1.5-14.5a1 1 0 0 0-1.46-.9z"/>
                                                                    </svg>`}
                                                            </div>
                                                            <div class="product-block-title">
                                                                <h4>${block.nama}</h4>
                                                                <span class="badge-cat">${badgeLabel}</span>
                                                                <span class="price-tag" style="margin-left: 8px;">Rp ${formattedPrice} / pcs</span>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn-remove-block" onclick="removeProductBlock(${idx})" title="Hapus Produk Ini">
                                                            ✕
                                                        </button>
                                                    </div>

                                                    ${sizesHtml}

                                                    <div class="block-extra-row">
                                                        <div>
                                                            <label class="form-label">Catatan Khusus Produk (Opsional)</label>
                                                            <input type="text" class="form-input" name="items[${idx}][catatan]" placeholder="Cth: Bordir logo OSIS di lengan kanan...">
                                                        </div>
                                                        <div>
                                                            <label class="form-label">Gambar Acuan / Model (Opsional)</label>
                                                            <input type="file" class="form-input" name="items[${idx}][gambar]" accept="image/*">
                                                        </div>
                                                    </div>
                                                </div>`;

                container.innerHTML += blockHtml;
            });

            updateOrderSummary();
        }

        // Update quantity for a size in a block
        function updateBlockSizeQty(blockIdx, sizeName, value) {
            const valInt = parseInt(value) || 0;
            selectedProductBlocks[blockIdx].sizes[sizeName] = valInt > 0 ? valInt : 0;
            updateOrderSummary();
        }

        // Remove Product Block
        function removeProductBlock(idx) {
            selectedProductBlocks.splice(idx, 1);
            renderSelectedBlocks();
        }

        // Update Summary Totals
        function updateOrderSummary() {
            let uniqueCount = selectedProductBlocks.length;
            let totalPcs = 0;
            let totalPrice = 0;

            selectedProductBlocks.forEach(block => {
                Object.values(block.sizes).forEach(qty => {
                    const q = parseInt(qty) || 0;
                    totalPcs += q;
                    totalPrice += (q * block.harga);
                });
            });

            document.getElementById('summaryUniqueCount').textContent = `${uniqueCount} Produk`;
            document.getElementById('summaryTotalPcs').textContent = `${totalPcs} pcs`;
            document.getElementById('summaryTotalPrice').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(totalPrice)}`;

            const targetDateInput = document.getElementById('targetTanggalPengambilan');
            const hasValidDate = targetDateInput && targetDateInput.value.trim() !== '';

            const btnSubmit = document.getElementById('btnSubmitOrder');
            btnSubmit.disabled = !(totalPcs > 0 && hasValidDate);
        }

        // Modal CSV Upload Functions
        function openCsvModal() {
            document.getElementById('csvModalOverlay').classList.add('active');
        }

        function closeCsvModal() {
            document.getElementById('csvModalOverlay').classList.remove('active');
        }

        function closeCsvModalOnBackdrop(e) {
            if (e.target.id === 'csvModalOverlay') closeCsvModal();
        }

        function processCsvUpload() {
            const fileInput = document.getElementById('csvFileInput');
            if (!fileInput.files || fileInput.files.length === 0) {
                showSptToast('warning', 'File Belum Dipilih', 'Silakan pilih file CSV terlebih dahulu.');
                return;
            }

            const formData = new FormData();
            formData.append('file_excel', fileInput.files[0]);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('{{ route("pelanggan.pesanan.upload") }}', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.items) {
                        data.items.forEach(item => {
                            const prod = allProducts.find(p => p.id === item.produk_id);
                            if (prod) {
                                const isAtribut = (prod.jenis_seragam.toLowerCase() === 'atribut');
                                let block = selectedProductBlocks.find(b => b.id === prod.id);
                                if (!block) {
                                    addProductBlock(prod.id, prod.nama_produk, prod.jenis_seragam, parseInt(prod.harga), prod.gambar ? `/` + prod.gambar : '', isAtribut);
                                    block = selectedProductBlocks[selectedProductBlocks.length - 1];
                                }
                                block.sizes[item.ukuran] = (block.sizes[item.ukuran] || 0) + parseInt(item.jumlah);
                            }
                        });
                        renderSelectedBlocks();
                        closeCsvModal();
                        showSptToast('success', 'Import Berhasil', data.message);
                    } else {
                        showSptToast('error', 'Upload Gagal', 'Gagal mengunggah file CSV: ' + (data.message || 'Format tidak valid.'));
                    }
                })
                .catch(err => {
                    showSptToast('error', 'Terjadi Kesalahan', 'Terjadi kesalahan saat mengunggah file CSV.');
                });
        }
        function showSptToast(type, title, message, duration = 4500) {
            const container = document.getElementById('spt-container');

            const svgIcons = {
                warning: `<svg viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                                <line x1="12" y1="9" x2="12" y2="13"/>
                                <line x1="12" y1="17" x2="12.01" y2="17"/>
                            </svg>`,
                success: `<svg viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>`,
                error: `<svg viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="15" y1="9" x2="9" y2="15"/>
                                <line x1="9" y1="9" x2="15" y2="15"/>
                            </svg>`,
                info: `<svg viewBox="0 0 24 24" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="16" x2="12" y2="12"/>
                                <line x1="12" y1="8" x2="12.01" y2="8"/>
                            </svg>`
            };

            // Limit stacked toasts to 5
            const existing = container.querySelectorAll('.spt');
            if (existing.length >= 5) dismissSptToast(existing[existing.length - 1]);

            const spt = document.createElement('div');
            spt.className = `spt spt-${type}`;
            spt.innerHTML = `
                            <div class="spt-inner">
                                <div class="spt-icon">${svgIcons[type] || svgIcons.info}</div>
                                <div class="spt-body">
                                    <div class="spt-title">${title}</div>
                                    <div class="spt-msg">${message}</div>
                                </div>
                                <button class="spt-close" onclick="dismissSptToast(this.closest('.spt'))">
                                    <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round">
                                        <line x1="18" y1="6" x2="6" y2="18"/>
                                        <line x1="6" y1="6" x2="18" y2="18"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="spt-bar"></div>
                        `;

            container.appendChild(spt);
            void spt.offsetWidth; // force reflow for animation

            const timer = setTimeout(() => dismissSptToast(spt), duration);
            spt._timer = timer;

            // Pause progress on hover
            spt.addEventListener('mouseenter', () => {
                clearTimeout(spt._timer);
                spt.querySelectorAll('.spt-bar').forEach(p => p.style.animationPlayState = 'paused');
            });
            spt.addEventListener('mouseleave', () => {
                spt.querySelectorAll('.spt-bar').forEach(p => p.style.animationPlayState = 'running');
                spt._timer = setTimeout(() => dismissSptToast(spt), 1500);
            });
        }

        function dismissSptToast(spt) {
            if (!spt || spt.classList.contains('spt-hide')) return;
            clearTimeout(spt._timer);
            spt.classList.add('spt-hide');
            setTimeout(() => spt.remove(), 420);
        }
    </script>
@endsection