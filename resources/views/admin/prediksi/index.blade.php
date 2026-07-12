@extends('layouts.main')

@section('title', 'Prediksi Pemesanan — SIMAPES')

@push('styles')
    @include('admin.prediksi.partials.styles')
@endpush

@section('content')

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Prediksi Jumlah Pesanan</h1>
            <nav class="breadcrumb" aria-label="breadcrumb">
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <span class="breadcrumb-sep">›</span>
                <span class="breadcrumb-current">Prediksi</span>
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

    {{-- ── Form Parameter Input ── --}}
    @include('admin.prediksi.partials.form')

    @if(!$hasData)
        {{-- ── Tampilan Peringatan Jika Data Tidak Cukup ── --}}
        @include('admin.prediksi.partials.warning')
    @else
        {{-- ── Stat Cards Grid (Tampil jika ada data) ── --}}
        @include('admin.prediksi.partials.stats')

        {{-- ── Main Layout: Chart + Tabel + MRP ── --}}
        <div class="prediksi-layout">
            @include('admin.prediksi.partials.chart_table')
            @include('admin.prediksi.partials.mrp')
        </div>

        {{-- ── Rekomendasi Supplier ── --}}
        @include('admin.prediksi.partials.supplier')

        {{-- ── Analisis & Rekomendasi AI ── --}}
        <div class="ai-card" id="aiCardSection">
            <div class="ai-header">
                <div class="ai-title-wrap">
                    <div class="ai-icon-pulse">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 16v-4"></path>
                            <path d="M12 8h.01"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="card-title" style="font-size: 1rem; margin: 0;">Analisis Cerdas &amp; Rekomendasi AI</h2>
                        <p style="font-size: 0.72rem; color: #8ca0bf; margin: 2px 0 0 0;">Gunakan kecerdasan buatan untuk
                            menginterpretasikan tren dan kebutuhan bahan baku</p>
                    </div>
                </div>
                <div class="ai-controls">
                    <select id="aiProviderSelect" class="ai-select">
                        <option value="gemini">Gemini API (Direct)</option>
                        <option value="openrouter">OpenRouter (Gemini 2.5 Flash)</option>
                    </select>
                    <button type="button" class="btn-ai" onclick="runAiAnalysis()">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                        </svg>
                        Analisis Data
                    </button>
                </div>
            </div>

            <div class="ai-content" id="aiContentArea">
                <div class="ai-placeholder-text">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                        style="margin-bottom: 8px; color: #cedbe9; display: block; margin-left: auto; margin-right: auto;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="16" x2="12" y2="12"></line>
                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                    </svg>
                    Silakan klik tombol "Analisis Data" untuk menghasilkan analisis tren penjualan, evaluasi peramalan
                    Holt-Winters, dan rekomendasi inventori (MRP) berbasis AI.
                </div>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
    @include('admin.prediksi.partials.scripts')
@endpush