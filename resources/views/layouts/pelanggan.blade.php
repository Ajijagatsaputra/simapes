<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMAPES - Portal Pelanggan')</title>
    <link rel="icon" type="image/png" href="{{ asset('logoauth/logo2.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f4fb;
            color: #1a2b4a;
            min-height: 100vh;
        }

        /* ── Navbar ── */
        .navbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f4;
            padding: 0 32px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 2px 12px rgba(26, 43, 74, .07);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .navbar-brand img {
            width: 36px;
            height: 36px;
            object-fit: contain;
        }

        .navbar-brand span {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1a2b4a;
        }

        .navbar-brand small {
            font-size: .7rem;
            color: #4A90D9;
            font-weight: 600;
            display: block;
            line-height: 1;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
        }

        .nav-link {
            padding: 8px 14px;
            border-radius: 10px;
            text-decoration: none;
            font-size: .85rem;
            font-weight: 500;
            color: #5a7090;
            transition: background .15s, color .15s;
        }

        .nav-link:hover,
        .nav-link.active {
            background: #e8f0fd;
            color: #4A90D9;
        }

        .nav-link.active {
            font-weight: 700;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-dropdown {
            position: relative;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f0f4fb;
            border-radius: 10px;
            padding: 6px 12px;
            font-size: .82rem;
            cursor: pointer;
            user-select: none;
            transition: background 0.15s;
            border: 1px solid transparent;
        }

        .user-chip:hover,
        .user-chip.active {
            background: #e2ecfa;
            border-color: #c5d8f5;
        }

        .user-chip-avatar {
            width: 28px;
            height: 28px;
            background: #4A90D9;
            color: #fff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .75rem;
        }

        .user-chip-name {
            font-weight: 600;
            color: #1a2b4a;
        }

        .user-chip-role {
            font-size: .7rem;
            color: #4A90D9;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            background: #fff;
            border: 1px solid #e2e8f4;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(26, 43, 74, .12);
            width: 200px;
            display: none;
            flex-direction: column;
            padding: 6px;
            z-index: 100;
            transform-origin: top right;
            animation: dropdownFade .18s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dropdown-menu.show {
            display: flex;
        }

        @keyframes dropdownFade {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-5px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            color: #5a7090;
            text-decoration: none;
            font-size: .82rem;
            font-weight: 500;
            border-radius: 8px;
            cursor: pointer;
            transition: background .15s, color .15s;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            font-family: inherit;
        }

        .dropdown-item:hover {
            background: #f0f4fb;
            color: #4A90D9;
        }

        .dropdown-item svg {
            color: #8ca0bf;
            transition: color .15s;
        }

        .dropdown-item:hover svg {
            color: #4A90D9;
        }

        .dropdown-divider {
            border: 0;
            border-top: 1px solid #e2e8f4;
            margin: 6px 0;
        }

        .logout-item {
            color: #e05a5a;
        }

        .logout-item:hover {
            background: #fdeaea;
            color: #e05a5a;
        }

        .logout-item:hover svg {
            color: #e05a5a;
        }

        /* ── Content ── */
        .page-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 32px 24px 60px;
        }

        /* ── Footer ── */
        .site-footer {
            background: linear-gradient(135deg, #0f1f3d 0%, #1a2b4a 50%, #1e3a5f 100%);
            color: #c8d9f0;
            margin-top: auto;
            position: relative;
            overflow: hidden;
        }

        .site-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #4A90D9 0%, #6fb3f5 50%, #4A90D9 100%);
        }

        .site-footer::after {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(74, 144, 217, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .footer-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 48px 24px 36px;
            display: grid;
            grid-template-columns: 1.8fr 1fr 1fr 1.4fr;
            gap: 40px;
            position: relative;
            z-index: 1;
        }

        /* Brand Column */
        .footer-brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            text-decoration: none;
        }

        .footer-brand-logo img {
            width: 36px;
            height: 36px;
            object-fit: contain;
            filter: brightness(0) invert(1);
            opacity: 0.9;
        }

        .footer-brand-name {
            font-size: 1.2rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.3px;
        }

        .footer-brand-name small {
            display: block;
            font-size: .68rem;
            font-weight: 500;
            color: #4A90D9;
            letter-spacing: .5px;
            line-height: 1;
        }

        .footer-tagline {
            font-size: .82rem;
            line-height: 1.7;
            color: #8ca0bf;
            margin-bottom: 20px;
            max-width: 260px;
        }

        .footer-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(74, 144, 217, 0.15);
            border: 1px solid rgba(74, 144, 217, 0.3);
            border-radius: 20px;
            padding: 6px 14px;
            font-size: .72rem;
            font-weight: 600;
            color: #4A90D9;
        }

        /* Footer Columns */
        .footer-col-title {
            font-size: .78rem;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 18px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-col-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 24px;
            height: 2px;
            background: #4A90D9;
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-links a {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .82rem;
            color: #8ca0bf;
            text-decoration: none;
            transition: color .2s, gap .2s;
        }

        .footer-links a::before {
            content: '';
            width: 5px;
            height: 5px;
            background: #4A90D9;
            border-radius: 50%;
            flex-shrink: 0;
            opacity: 0.5;
            transition: opacity .2s, transform .2s;
        }

        .footer-links a:hover {
            color: #6fb3f5;
            gap: 12px;
        }

        .footer-links a:hover::before {
            opacity: 1;
            transform: scale(1.4);
        }

        /* Contact Items */
        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 14px;
        }

        .footer-contact-icon {
            width: 32px;
            height: 32px;
            background: rgba(74, 144, 217, 0.12);
            border: 1px solid rgba(74, 144, 217, 0.2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #4A90D9;
            transition: background .2s;
        }

        .footer-contact-item:hover .footer-contact-icon {
            background: rgba(74, 144, 217, 0.25);
        }

        .footer-contact-text {
            font-size: .8rem;
            color: #8ca0bf;
            line-height: 1.5;
        }

        .footer-contact-text strong {
            display: block;
            color: #c8d9f0;
            font-size: .75rem;
            font-weight: 600;
            margin-bottom: 2px;
        }

        /* Divider */
        .footer-divider {
            border: 0;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
        }

        /* Bottom Bar */
        .footer-bottom {
            max-width: 1200px;
            margin: 0 auto;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .footer-copyright {
            font-size: .75rem;
            color: #5a7090;
        }

        .footer-copyright a {
            color: #4A90D9;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-bottom-links {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .footer-bottom-link {
            font-size: .72rem;
            color: #5a7090;
            text-decoration: none;
            padding: 4px 10px;
            border-radius: 6px;
            transition: color .15s, background .15s;
        }

        .footer-bottom-link:hover {
            color: #4A90D9;
            background: rgba(74, 144, 217, 0.08);
        }

        .footer-bottom-sep {
            color: #2d4060;
            font-size: .7rem;
        }

        /* Responsive Footer */
        @media (max-width: 1024px) {
            .footer-main {
                grid-template-columns: 1.5fr 1fr 1fr;
                gap: 32px;
            }

            .footer-brand-col {
                grid-column: 1 / -1;
                display: flex;
                flex-direction: row;
                align-items: flex-start;
                gap: 24px;
            }

            .footer-tagline {
                max-width: none;
                margin-bottom: 0;
            }
        }

        @media (max-width: 768px) {
            .footer-main {
                grid-template-columns: 1fr 1fr;
                gap: 28px;
                padding: 36px 16px 28px;
            }

            .footer-brand-col {
                grid-column: 1 / -1;
                flex-direction: column;
            }

            .footer-bottom {
                padding: 16px;
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
        }

        @media (max-width: 480px) {
            .footer-main {
                grid-template-columns: 1fr;
                gap: 24px;
                padding: 28px 16px 24px;
            }

            .footer-col-title {
                margin-bottom: 14px;
            }
        }

        /* ── Toast ── */
        .toast-container {
            position: fixed;
            top: 80px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #fff;
            border-radius: 14px;
            padding: 14px 18px;
            min-width: 300px;
            max-width: 380px;
            box-shadow: 0 8px 32px rgba(26, 43, 74, .18);
            transform: translateX(120%);
            opacity: 0;
            transition: transform 0.38s cubic-bezier(.34, 1.56, .64, 1), opacity 0.3s ease;
        }

        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }

        .toast-success {
            border-left: 4px solid #34c472;
        }

        .toast-error {
            border-left: 4px solid #e05a5a;
        }

        .toast-info {
            border-left: 4px solid #4A90D9;
        }

        /* ── Hamburger Button ── */
        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            color: #1a2b4a;
            padding: 8px;
            z-index: 60;
            outline: none;
        }

        .dropdown-user-header {
            display: none;
            padding: 10px 14px;
            border-bottom: 1px solid #f0f4fb;
            margin-bottom: 6px;
        }

        /* Responsive Media Queries */
        @media (max-width: 768px) {
            .navbar {
                padding: 0 16px;
            }

            .hamburger-btn {
                display: block;
            }

            .navbar-nav {
                position: fixed;
                top: 64px;
                left: 0;
                right: 0;
                background: #fff;
                flex-direction: column;
                padding: 16px;
                gap: 12px;
                border-bottom: 1px solid #e2e8f4;
                box-shadow: 0 8px 16px rgba(26, 43, 74, .08);
                display: none;
                z-index: 45;
            }

            .navbar-nav.show {
                display: flex;
            }

            .nav-link {
                width: 100%;
                text-align: center;
                padding: 12px;
            }

            .navbar-right {
                gap: 8px;
            }

            .user-chip-name,
            .user-chip-role {
                display: none;
            }

            .user-chip {
                padding: 8px;
            }

            .dropdown-user-header {
                display: block;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <a href="{{ route('pelanggan.dashboard') }}" class="navbar-brand">
            <img src="{{ asset('logoauth/logo2.png') }}" alt="SIMAPES">
            <div>
                <span>SIMAPES</span>
                <small>Portal Pelanggan</small>
            </div>
        </a>

        <button class="hamburger-btn" onclick="toggleMobileMenu(event)" aria-label="Toggle menu">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>

        <ul class="navbar-nav">
            <li><a href="{{ route('pelanggan.dashboard') }}"
                    class="nav-link {{ request()->routeIs('pelanggan.dashboard') ? 'active' : '' }}">Dashboard</a></li>
            <li><a href="{{ route('pelanggan.katalog') }}"
                    class="nav-link {{ request()->routeIs('pelanggan.katalog') ? 'active' : '' }}">Katalog</a></li>
            <li><a href="{{ route('pelanggan.pesanan.create') }}"
                    class="nav-link {{ request()->routeIs('pelanggan.pesanan.create') ? 'active' : '' }}">Buat
                    Pesanan</a></li>
            <li><a href="{{ route('pelanggan.pesanan.index') }}"
                    class="nav-link {{ request()->routeIs('pelanggan.pesanan.index') || request()->routeIs('pelanggan.pesanan.show') ? 'active' : '' }}">Status
                    Pesanan</a>
            </li>
        </ul>

        <div class="navbar-right">
            <div class="user-dropdown">
                <div class="user-chip" id="userDropdownTrigger" onclick="toggleDropdown(event)">
                    <div class="user-chip-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div>
                        <div class="user-chip-name">{{ auth()->user()->name }}</div>
                        <div class="user-chip-role">{{ auth()->user()->nama_sekolah ?? 'Pelanggan' }}</div>
                    </div>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        style="margin-left: 4px; transition: transform .2s;" id="dropdownChevron">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>

                <div class="dropdown-menu" id="userDropdownMenu">
                    <div class="dropdown-user-header">
                        <div style="font-weight: 700; color: #1a2b4a; font-size: .85rem;">{{ auth()->user()->name }}
                        </div>
                        <div style="font-size: .72rem; color: #8ca0bf; margin-top: 2px;">
                            {{ auth()->user()->nama_sekolah ?? 'Pelanggan' }}
                        </div>
                    </div>
                    <a href="{{ route('pelanggan.profil.edit') }}" class="dropdown-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Profil Akun
                    </a>
                    <a href="{{ route('pelanggan.riwayat') }}" class="dropdown-item">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 11 12 14 22 4"></polyline>
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                        </svg>
                        Riwayat Pesanan
                    </a>
                    <hr class="dropdown-divider">
                    <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                        @csrf
                        <button type="submit" class="dropdown-item logout-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="page-wrapper">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-main">
            <!-- Brand Column -->
            <div class="footer-brand-col">
                <div>
                    <a href="{{ route('pelanggan.dashboard') }}" class="footer-brand-logo">
                        <img src="{{ asset('logoauth/logo2.png') }}" alt="SIMAPES Logo">
                        <div class="footer-brand-name">
                            SIMAPES
                            <small>Portal Pelanggan</small>
                        </div>
                    </a>
                    <p class="footer-tagline">
                        Sistem Informasi Manajemen Pemesanan Seragam yang memudahkan proses pemesanan, tracking
                        produksi, hingga pengiriman seragam sekolah Anda.
                    </p>
                    <span class="footer-badge">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                        Terpercaya & Terverifikasi
                    </span>
                </div>
            </div>

            <!-- Navigasi Column -->
            <div>
                <h3 class="footer-col-title">Navigasi</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('pelanggan.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('pelanggan.katalog') }}">Katalog Seragam</a></li>
                    <li><a href="{{ route('pelanggan.pesanan.create') }}">Buat Pesanan</a></li>
                    <li><a href="{{ route('pelanggan.pesanan.index') }}">Status Pesanan</a></li>
                    <li><a href="{{ route('pelanggan.riwayat') }}">Riwayat Pesanan</a></li>
                </ul>
            </div>

            <!-- Layanan Column -->
            <div>
                <h3 class="footer-col-title">Layanan</h3>
                <ul class="footer-links">
                    <li><a href="{{ route('pelanggan.profil.edit') }}">Profil Akun</a></li>
                    <li><a href="{{ route('pelanggan.pesanan.create') }}">Pemesanan Online</a></li>
                    <li><a href="{{ route('pelanggan.katalog') }}">Cek Harga Seragam</a></li>
                    <li><a href="{{ route('pelanggan.pesanan.index') }}">Tracking Produksi</a></li>
                    <li><a href="{{ route('pelanggan.riwayat') }}">Riwayat Transaksi</a></li>
                </ul>
            </div>

            <!-- Kontak Column -->
            <div>
                <h3 class="footer-col-title">Hubungi Kami</h3>

                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                    </div>
                    <div class="footer-contact-text">
                        <strong>Alamat Toko</strong>
                        Jl. Raya Seragam No. 1,<br>Kota Anda, Jawa Tengah
                    </div>
                </div>

                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.38 2 2 0 0 1 3.6 1.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.77a16 16 0 0 0 6.29 6.29l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                    </div>
                    <div class="footer-contact-text">
                        <strong>WhatsApp</strong>
                        +62 812-3456-7890
                    </div>
                </div>

                <div class="footer-contact-item">
                    <div class="footer-contact-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <div class="footer-contact-text">
                        <strong>Jam Operasional</strong>
                        Senin – Sabtu: 08.00 – 17.00 WIB
                    </div>
                </div>
            </div>
        </div>

        <hr class="footer-divider">

        <!-- Footer Bottom Bar -->
        <div class="footer-bottom">
            <p class="footer-copyright">
                &copy; {{ date('Y') }} <a href="{{ route('pelanggan.dashboard') }}">SIMAPES</a>. Semua hak dilindungi
                undang-undang.
            </p>
            <div class="footer-bottom-links">
                <a href="#" class="footer-bottom-link">Kebijakan Privasi</a>
                <span class="footer-bottom-sep">&bull;</span>
                <a href="#" class="footer-bottom-link">Syarat &amp; Ketentuan</a>
                <span class="footer-bottom-sep">&bull;</span>
                <a href="#" class="footer-bottom-link">Bantuan</a>
            </div>
        </div>
    </footer>

    <!-- Toast -->
    <div class="toast-container" id="toastContainer"></div>
    <script>
        function toggleDropdown(event) {
            event.stopPropagation();
            const menu = document.getElementById('userDropdownMenu');
            const trigger = document.getElementById('userDropdownTrigger');
            const chevron = document.getElementById('dropdownChevron');
            const show = menu.classList.contains('show');

            if (show) {
                menu.classList.remove('show');
                trigger.classList.remove('active');
                chevron.style.transform = 'rotate(0deg)';
            } else {
                menu.classList.add('show');
                trigger.classList.add('active');
                chevron.style.transform = 'rotate(180deg)';
            }
        }

        document.addEventListener('click', function (event) {
            const menu = document.getElementById('userDropdownMenu');
            const trigger = document.getElementById('userDropdownTrigger');
            const chevron = document.getElementById('dropdownChevron');

            if (menu && menu.classList.contains('show')) {
                if (!trigger.contains(event.target) && !menu.contains(event.target)) {
                    menu.classList.remove('show');
                    trigger.classList.remove('active');
                    chevron.style.transform = 'rotate(0deg)';
                }
            }
        });

        function toggleMobileMenu(event) {
            event.stopPropagation();
            const nav = document.querySelector('.navbar-nav');
            nav.classList.toggle('show');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function (event) {
            const nav = document.querySelector('.navbar-nav');
            const hamburger = document.querySelector('.hamburger-btn');
            if (nav && nav.classList.contains('show')) {
                if (!nav.contains(event.target) && !hamburger.contains(event.target)) {
                    nav.classList.remove('show');
                }
            }
        });

        function showToast(msg, type = 'success') {
            const t = document.createElement('div');
            t.className = `toast toast-${type}`;
            t.textContent = msg;
            document.getElementById('toastContainer').appendChild(t);
            requestAnimationFrame(() => requestAnimationFrame(() => t.classList.add('show')));
            setTimeout(() => t.remove(), 4000);
        }
        @if(session('success')) showToast(@json(session('success')), 'success'); @endif
        @if(session('error'))   showToast(@json(session('error')), 'error'); @endif
        @if(session('info'))    showToast(@json(session('info')), 'info'); @endif
    </script>
    @stack('scripts')
</body>

</html>