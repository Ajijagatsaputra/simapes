<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'SIMAPES'))</title>
    <link rel="icon" type="image/png" href="{{ asset('logoauth/logo2.png') }}">

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --sb-width: 160px;
            --font: 'Inter', sans-serif;
            --bg-main: #f0f4fb;
            --text: #1a2b4a;
        }

        html,
        body {
            height: 100%;
            font-family: var(--font);
            background: var(--bg-main);
            color: var(--text);
        }

        /* ── App Shell ── */
        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        .app-content {
            margin-left: var(--sb-width);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .app-page {
            flex: 1;
            padding: 32px 32px 40px;
        }

        /* ══════════════════════════════════════════
           TOAST NOTIFICATION SYSTEM
        ══════════════════════════════════════════ */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            pointer-events: none;
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
            box-shadow: 0 8px 32px rgba(26, 43, 74, .18), 0 2px 8px rgba(26, 43, 74, .08);
            pointer-events: all;
            transform: translateX(120%);
            opacity: 0;
            transition: transform 0.38s cubic-bezier(.34, 1.56, .64, 1), opacity 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }

        .toast.hide {
            transform: translateX(120%);
            opacity: 0;
        }

        /* Progress bar */
        .toast::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 100%;
            border-radius: 0 0 14px 14px;
            animation: toastProgress 3.5s linear forwards;
        }

        @keyframes toastProgress {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }

        /* Variants */
        .toast-success {
            border-left: 4px solid #34c472;
        }

        .toast-success .toast-icon {
            background: #e8f8ee;
            color: #34c472;
        }

        .toast-success::after {
            background: #34c472;
        }

        .toast-error {
            border-left: 4px solid #e05a5a;
        }

        .toast-error .toast-icon {
            background: #fdeaea;
            color: #e05a5a;
        }

        .toast-error::after {
            background: #e05a5a;
        }

        .toast-warning {
            border-left: 4px solid #f5a54a;
        }

        .toast-warning .toast-icon {
            background: #fff3e6;
            color: #f5a54a;
        }

        .toast-warning::after {
            background: #f5a54a;
        }

        .toast-info {
            border-left: 4px solid #4A90D9;
        }

        .toast-info .toast-icon {
            background: #e8f0fd;
            color: #4A90D9;
        }

        .toast-info::after {
            background: #4A90D9;
        }

        .toast-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .toast-body {
            flex: 1;
        }

        .toast-title {
            font-size: .82rem;
            font-weight: 700;
            color: #1a2b4a;
            margin-bottom: 2px;
        }

        .toast-message {
            font-size: .75rem;
            color: #6b7e9f;
            line-height: 1.4;
        }

        .toast-close {
            background: none;
            border: none;
            cursor: pointer;
            color: #a0aec0;
            padding: 2px;
            display: flex;
            align-items: center;
            border-radius: 6px;
            transition: color .15s, background .15s;
            flex-shrink: 0;
        }

        .toast-close:hover {
            color: #5a7090;
            background: #f0f4fb;
        }

        /* ══════════════════════════════════════════
           CONFIRM MODAL
        ══════════════════════════════════════════ */
        .confirm-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 25, 50, 0.45);
            backdrop-filter: blur(4px);
            z-index: 8888;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity .25s ease;
        }

        .confirm-overlay.show {
            opacity: 1;
            pointer-events: all;
        }

        .confirm-modal {
            background: #fff;
            border-radius: 20px;
            padding: 32px 28px 24px;
            width: 360px;
            max-width: 90vw;
            box-shadow: 0 20px 60px rgba(26, 43, 74, .25);
            transform: scale(.9) translateY(10px);
            transition: transform .3s cubic-bezier(.34, 1.56, .64, 1);
            text-align: center;
        }

        .confirm-overlay.show .confirm-modal {
            transform: scale(1) translateY(0);
        }

        .confirm-icon-wrap {
            width: 64px;
            height: 64px;
            background: #fdeaea;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            color: #e05a5a;
        }

        .confirm-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1a2b4a;
            margin-bottom: 8px;
        }

        .confirm-desc {
            font-size: .83rem;
            color: #6b7e9f;
            line-height: 1.55;
            margin-bottom: 24px;
        }

        .confirm-desc strong {
            color: #1a2b4a;
            font-weight: 700;
        }

        .confirm-actions {
            display: flex;
            gap: 10px;
        }

        .confirm-btn-batal {
            flex: 1;
            padding: 11px;
            background: #f0f4fb;
            border: 1px solid #dde8f8;
            border-radius: 12px;
            color: #5a7090;
            font-size: .85rem;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: background .15s;
        }

        .confirm-btn-batal:hover {
            background: #e2e8f4;
        }

        .confirm-btn-hapus {
            flex: 1;
            padding: 11px;
            background: #e05a5a;
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: background .2s, transform .15s;
        }

        .confirm-btn-hapus:hover {
            background: #c94848;
            transform: translateY(-1px);
        }

        /* ── Mobile Top Bar ── */
        .mobile-header {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: #4A90D9;
            color: #fff;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            z-index: 90;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        }

        .btn-menu-toggle {
            background: none;
            border: none;
            color: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            border-radius: 8px;
            transition: background 0.15s;
        }

        .btn-menu-toggle:hover {
            background: rgba(255, 255, 255, .1);
        }

        .mobile-brand {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .mobile-brand-img {
            width: 32px;
            height: 32px;
            object-fit: contain;
            background: #fff;
            border-radius: 8px;
            padding: 2px;
        }

        .mobile-brand-name {
            font-size: 1rem;
            font-weight: 800;
            letter-spacing: .5px;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 25, 50, 0.4);
            backdrop-filter: blur(2px);
            z-index: 95;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        /* ── Global Responsiveness Rules ── */
        .card {
            overflow-x: auto;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
        }

        /* ── Tablet / iPad Viewports (<= 1100px) ── */
        @media (max-width: 1100px) {

            .pelanggan-layout,
            .produk-layout,
            .supplier-layout,
            .pesanan-layout,
            .prediksi-layout,
            .laporan-layout {
                grid-template-columns: 1fr !important;
                gap: 20px !important;
            }

            .stats-row {
                grid-template-columns: repeat(3, 1fr) !important;
            }

            .charts-row,
            .bottom-row {
                grid-template-columns: 1fr !important;
                gap: 20px !important;
            }
        }

        /* ── Small Tablets / Mobile Viewports (<= 768px) ── */
        @media (max-width: 768px) {
            body, html {
                max-width: 100vw;
                overflow-x: hidden;
            }

            .app-layout {
                max-width: 100vw;
                overflow-x: hidden;
            }

            .mobile-header {
                display: flex;
            }

            .app-content {
                margin-left: 0 !important;
                padding-top: 60px;
                max-width: 100vw;
                overflow-x: hidden;
                min-width: 0;
            }

            .app-page {
                padding: 16px 16px 30px !important;
                min-width: 0;
            }

            /* Modify Sidebar behavior on mobile */
            .simapes-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 100;
            }

            .simapes-sidebar.open {
                transform: translateX(0);
            }

            /* Card padding optimization for mobile screens */
            .card {
                padding: 16px 14px !important;
            }

            /* Responsive stats grids on dashboard */
            .stats-row {
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 12px !important;
            }

            .stat-card {
                padding: 14px 12px !important;
            }

            /* Stack page-header or dash-header elements */
            .dash-header,
            .page-header {
                flex-direction: column !important;
                gap: 12px !important;
                align-items: stretch !important;
            }

            .page-date,
            .dash-date {
                align-self: flex-start !important;
            }

            /* Table toolbar stacked nicely */
            .table-toolbar {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 12px !important;
            }

            .toolbar-right {
                flex-direction: row !important;
                justify-content: space-between !important;
                width: 100% !important;
                gap: 8px !important;
            }

            .search-wrap {
                flex: 1 !important;
            }

            .search-wrap input {
                width: 100% !important;
            }

            /* Form panels stacked and clean */
            .form-panel {
                width: 100% !important;
                position: relative !important;
                top: 0 !important;
            }
        }

        /* ── Extra Small Mobile Viewports (<= 480px) ── */
        @media (max-width: 480px) {
            .stats-row {
                grid-template-columns: 1fr !important;
            }

            .toolbar-right {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 8px !important;
            }

            .btn-tambah {
                justify-content: center !important;
            }
        }
    </style>

    {{-- Slot CSS tambahan per halaman --}}
    @stack('styles')
</head>

<body>

    <div class="app-layout">

        {{-- Mobile Top Bar --}}
        <header class="mobile-header">
            <button type="button" class="btn-menu-toggle" id="menuToggleBtn" aria-label="Buka Menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
            <div class="mobile-brand">
                <img src="{{ asset('logoauth/logo2.png') }}" alt="Logo SIMAPES" class="mobile-brand-img">
                <span class="mobile-brand-name">SIMAPES</span>
            </div>
            <div style="width: 24px;"></div>
        </header>

        {{-- Sidebar --}}
        <x-sidebar.index />

        {{-- Mobile Sidebar Overlay --}}
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        {{-- Konten utama --}}
        <div class="app-content">
            <main class="app-page" id="main-content">
                @yield('content')
            </main>
        </div>

    </div>

    {{-- ══════════════════════════════════════════
    TOAST CONTAINER
    ══════════════════════════════════════════ --}}
    <div class="toast-container" id="toastContainer" aria-live="polite"></div>

    {{-- ══════════════════════════════════════════
    CONFIRM MODAL (reusable)
    ══════════════════════════════════════════ --}}
    <div class="confirm-overlay" id="confirmOverlay" role="dialog" aria-modal="true">
        <div class="confirm-modal">
            <div class="confirm-icon-wrap">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6" />
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                    <path d="M10 11v6M14 11v6" />
                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                </svg>
            </div>
            <div class="confirm-title">Hapus Data?</div>
            <div class="confirm-desc" id="confirmDesc">
                Data ini akan dihapus secara permanen dan tidak dapat dikembalikan.
            </div>
            <div class="confirm-actions">
                <button class="confirm-btn-batal" onclick="closeConfirm()">Batal</button>
                <button class="confirm-btn-hapus" id="confirmOkBtn">Ya, Hapus</button>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
    GLOBAL SCRIPTS
    ══════════════════════════════════════════ --}}
    <script>
        /* ── Toast System ─────────────────────────────────────────────── */
        const icons = {
            success: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`,
            error: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`,
            warning: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`,
            info: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`,
        };

        const titles = { success: 'Berhasil!', error: 'Terjadi Kesalahan', warning: 'Peringatan', info: 'Informasi' };

        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
            <div class="toast-icon">${icons[type]}</div>
            <div class="toast-body">
                <div class="toast-title">${titles[type]}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="dismissToast(this.closest('.toast'))">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>`;
            container.appendChild(toast);

            // Trigger animation
            requestAnimationFrame(() => {
                requestAnimationFrame(() => toast.classList.add('show'));
            });

            // Auto dismiss
            setTimeout(() => dismissToast(toast), 3500);
        }

        function dismissToast(toast) {
            if (!toast) return;
            toast.classList.add('hide');
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400);
        }

        /* ── Confirm Modal ────────────────────────────────────────────── */
        let _pendingForm = null;

        function confirmHapus(formEl, namaItem) {
            _pendingForm = formEl;
            const desc = namaItem
                ? `Data <strong>"${namaItem}"</strong> akan dihapus secara permanen dan tidak dapat dikembalikan.`
                : 'Data ini akan dihapus secara permanen dan tidak dapat dikembalikan.';
            document.getElementById('confirmDesc').innerHTML = desc;
            document.getElementById('confirmOverlay').classList.add('show');

            document.getElementById('confirmOkBtn').onclick = function () {
                const form = _pendingForm;
                closeConfirm();
                if (form) form.submit();
            };
        }

        function closeConfirm() {
            document.getElementById('confirmOverlay').classList.remove('show');
            _pendingForm = null;
        }

        // Tutup modal jika klik overlay
        document.getElementById('confirmOverlay').addEventListener('click', function (e) {
            if (e.target === this) closeConfirm();
        });

        // Tutup dengan ESC
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeConfirm();
        });

        /* ── Auto-show toast dari session Laravel ─────────────────────── */
        @if(session('success'))
            showToast(@json(session('success')), 'success');
        @endif
        @if(session('error'))
            showToast(@json(session('error')), 'error');
        @endif
        @if(session('warning'))
            showToast(@json(session('warning')), 'warning');
        @endif
        @if(session('info'))
            showToast(@json(session('info')), 'info');
        @endif

        // Toggle Mobile Sidebar
        document.addEventListener('DOMContentLoaded', () => {
            const menuToggleBtn = document.getElementById('menuToggleBtn');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (menuToggleBtn && sidebar && sidebarOverlay) {
                menuToggleBtn.addEventListener('click', () => {
                    sidebar.classList.add('open');
                    sidebarOverlay.classList.add('show');
                });

                sidebarOverlay.addEventListener('click', () => {
                    sidebar.classList.remove('open');
                    sidebarOverlay.classList.remove('show');
                });

                document.querySelectorAll('.nav-item').forEach(item => {
                    item.addEventListener('click', () => {
                        sidebar.classList.remove('open');
                        sidebarOverlay.classList.remove('show');
                    });
                });
            }
        });
    </script>

    {{-- Slot script tambahan per halaman --}}
    @stack('scripts')

</body>

</html>