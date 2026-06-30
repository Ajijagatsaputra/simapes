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
    @endif

@endsection

@push('scripts')
    @include('admin.prediksi.partials.scripts')
@endpush