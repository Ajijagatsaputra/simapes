@extends('layouts.pelanggan')
@section('title', 'Dashboard Pelanggan - SIMAPES')

@push('styles')
    <style>
        .dashboard-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* ── Hero / Greeting Banner ── */
        .hero-banner {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border-radius: 18px;
            padding: 32px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(30, 60, 114, 0.15);
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-content h1 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .hero-content p {
            font-size: .9rem;
            color: #cbd5e1;
            max-width: 600px;
            line-height: 1.5;
        }

        /* ── Alert Bar ── */
        .alert-banner {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 14px;
            color: #b45309;
            font-size: .82rem;
        }

        .alert-banner svg {
            flex-shrink: 0;
            color: #d97706;
        }

        .alert-banner a {
            color: #d97706;
            font-weight: 700;
            text-decoration: underline;
        }

        /* ── Stats Grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: #fff;
            border: 1px solid #e2e8f4;
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 4px 16px rgba(26, 43, 74, .02);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .icon-blue {
            background: #e8f0fd;
            color: #4A90D9;
        }

        .icon-orange {
            background: #fffbeb;
            color: #d97706;
        }

        .icon-green {
            background: #ecfdf5;
            color: #10b981;
        }

        .icon-indigo {
            background: #eef2ff;
            color: #6366f1;
        }

        .stat-info {
            display: flex;
            flex-direction: column;
        }

        .stat-value {
            font-size: 1.4rem;
            font-weight: 800;
            color: #1a2b4a;
            line-height: 1.2;
        }

        .stat-label {
            font-size: .78rem;
            color: #6b7e9f;
            font-weight: 500;
            margin-top: 2px;
        }

        /* ── Main Layout ── */
        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            align-items: start;
        }

        @media (max-width: 900px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #e2e8f4;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(26, 43, 74, .03);
        }

        .card-header-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            border-bottom: 1px dashed #e2e8f4;
            padding-bottom: 12px;
        }

        .card-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1a2b4a;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-link {
            font-size: .8rem;
            font-weight: 600;
            color: #4A90D9;
            text-decoration: none;
            transition: color 0.15s;
        }

        .card-link:hover {
            color: #3a7bc8;
        }

        /* ── Recent Orders Table ── */
        .order-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .8rem;
        }

        .order-table th {
            color: #8ca0bf;
            font-weight: 600;
            font-size: .7rem;
            text-transform: uppercase;
            letter-spacing: .4px;
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #e2e8f4;
        }

        .order-table td {
            padding: 12px;
            color: #2d4060;
            border-bottom: 1px solid #f6f9fd;
            vertical-align: middle;
        }

        .order-table tr:hover td {
            background: #fafcff;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: .7rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-diproses {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fde68a;
        }

        .badge-dikerjakan {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .badge-selesai {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }

        .badge-batal {
            background: #fdf2f2;
            color: #e05a5a;
            border: 1px solid #fde8e8;
        }

        .btn-view {
            width: 28px;
            height: 28px;
            background: #f0f4fb;
            color: #4A90D9;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-view:hover {
            background: #4A90D9;
            color: #fff;
        }

        /* ── Quick Actions ── */
        .action-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .action-button {
            display: flex;
            align-items: center;
            gap: 14px;
            background: #f5f8ff;
            border: 1px solid #dde8f8;
            padding: 14px;
            border-radius: 12px;
            text-decoration: none;
            color: #1a2b4a;
            transition: all 0.2s;
        }

        .action-button:hover {
            transform: translateX(4px);
            background: #fff;
            border-color: #4A90D9;
            box-shadow: 0 4px 12px rgba(74, 144, 217, 0.08);
        }

        .action-btn-icon {
            width: 38px;
            height: 38px;
            background: #fff;
            border: 1px solid #dde8f8;
            color: #4A90D9;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .action-button:hover .action-btn-icon {
            background: #4A90D9;
            color: #fff;
            border-color: #4A90D9;
        }

        .action-details {
            display: flex;
            flex-direction: column;
        }

        .action-title {
            font-size: .85rem;
            font-weight: 700;
        }

        .action-desc {
            font-size: .72rem;
            color: #6b7e9f;
            margin-top: 2px;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        {{-- Greeting Banner --}}
        <div class="hero-banner">
            <div class="hero-content">
                <h1>Selamat Datang Kembali, {{ auth()->user()->name }}! 👋</h1>
                <p>
                    @if(auth()->user()->nama_sekolah)
                        Perwakilan dari <strong>{{ auth()->user()->nama_sekolah }}</strong>.
                    @endif
                    Di portal SIMAPES, Anda dapat memesan seragam sekolah berkualitas dengan mudah, melacak status
                    pembuatannya secara langsung, dan mengelola histori pemesanan.
                </p>
            </div>
        </div>

        {{-- Profile Completeness Warning --}}
        @if(!auth()->user()->no_whatsapp || !auth()->user()->alamat)
            <div class="alert-banner">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div>
                    <strong>Lengkapi Profil Anda:</strong> Anda belum mengisi nomor WhatsApp atau alamat pengiriman dengan
                    lengkap. Silakan lengkapi di halaman <a href="{{ route('pelanggan.profil.edit') }}">Profil Akun</a> untuk
                    mempercepat koordinasi pesanan dan proses pengiriman seragam.
                </div>
            </div>
        @endif

        {{-- Stats Grid --}}
        <div class="stats-grid">
            {{-- Stat 1: Total Orders --}}
            <div class="stat-card">
                <div class="stat-icon icon-blue">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $totalPesanan }}</span>
                    <span class="stat-label">Total Pesanan</span>
                </div>
            </div>

            {{-- Stat 2: Active Orders --}}
            <div class="stat-card">
                <div class="stat-icon icon-orange">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $pesananDiproses + $pesananDikerjakan }}</span>
                    <span class="stat-label">Pesanan Berjalan</span>
                </div>
            </div>

            {{-- Stat 3: Completed Orders --}}
            <div class="stat-card">
                <div class="stat-icon icon-green">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $pesananSelesai }}</span>
                    <span class="stat-label">Selesai Dikerjakan</span>
                </div>
            </div>

            {{-- Stat 4: Catalog products --}}
            <div class="stat-card">
                <div class="stat-icon icon-indigo">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="9" y1="3" x2="9" y2="21"></line>
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $totalProduk }}</span>
                    <span class="stat-label">Pilihan Seragam</span>
                </div>
            </div>
        </div>

        {{-- Main Content Grid --}}
        <div class="main-grid">
            {{-- Left Side: Latest Orders --}}
            <div class="card">
                <div class="card-header-flex">
                    <span class="card-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <line x1="8" y1="6" x2="21" y2="6"></line>
                            <line x1="8" y1="12" x2="21" y2="12"></line>
                            <line x1="8" y1="18" x2="21" y2="18"></line>
                            <line x1="3" y1="6" x2="3.01" y2="6"></line>
                            <line x1="3" y1="12" x2="3.01" y2="12"></line>
                            <line x1="3" y1="18" x2="3.01" y2="18"></line>
                        </svg>
                        Pesanan Terakhir Anda
                    </span>
                    <a href="{{ route('pelanggan.pesanan.index') }}" class="card-link">Lihat Semua</a>
                </div>

                <div style="overflow-x: auto; width: 100%;">
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>No. Pesanan</th>
                                <th>Tanggal</th>
                                <th style="text-align: right;">Total Bayar</th>
                                <th style="text-align: center;">Status</th>
                                <th style="width: 40px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesananTerbaru as $p)
                                <tr>
                                    <td style="font-weight: 700; color: #1a2b4a;">{{ $p->no_pesanan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pesanan)->isoFormat('DD MMM YYYY') }}</td>
                                    <td style="text-align: right; font-weight: 700; color: #4A90D9;">
                                        Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td style="text-align: center;">
                                        @if($p->status === 'diproses')
                                            <span class="status-badge badge-diproses">Diproses</span>
                                        @elseif($p->status === 'dikerjakan')
                                            <span class="status-badge badge-dikerjakan">Dikerjakan</span>
                                        @elseif($p->status === 'selesai')
                                            <span class="status-badge badge-selesai">Selesai</span>
                                        @else
                                            <span class="status-badge badge-batal">Batal</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('pelanggan.pesanan.show', $p->id) }}" class="btn-view"
                                            title="Detail Pesanan">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="9 18 15 12 9 6"></polyline>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 24px; color: #8ca0bf;">
                                        Belum ada transaksi pemesanan seragam.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Right Side: Quick Actions --}}
            <div class="card">
                <div class="card-header-flex">
                    <span class="card-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                        Akses Cepat
                    </span>
                </div>

                <div class="action-list">
                    {{-- Action 1: Buat Pesanan --}}
                    <a href="{{ route('pelanggan.pesanan.create') }}" class="action-button">
                        <div class="action-btn-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                        </div>
                        <div class="action-details">
                            <span class="action-title">Buat Pesanan Baru</span>
                            <span class="action-desc">Form pemesanan seragam sekolah</span>
                        </div>
                    </a>

                    {{-- Action 2: Katalog --}}
                    <a href="{{ route('pelanggan.katalog') }}" class="action-button">
                        <div class="action-btn-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="9" y1="3" x2="9" y2="21"></line>
                            </svg>
                        </div>
                        <div class="action-details">
                            <span class="action-title">Katalog Seragam</span>
                            <span class="action-desc">Lihat model & harga terbaru</span>
                        </div>
                    </a>

                    {{-- Action 3: Profil --}}
                    <a href="{{ route('pelanggan.profil.edit') }}" class="action-button">
                        <div class="action-btn-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <div class="action-details">
                            <span class="action-title">Pengaturan Profil</span>
                            <span class="action-desc">Perbarui data diri & alamat kirim</span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection