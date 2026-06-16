{{--
    ┌─────────────────────────────────────────────────────┐
    │  Sidebar Component — SIMAPES                        │
    │                                                     │
    │  Usage:  <x-sidebar.index />                        │
    │                                                     │
    │  Active route dideteksi otomatis via request()->    │
    │  routeIs() sehingga tidak perlu prop tambahan.      │
    └─────────────────────────────────────────────────────┘
--}}

@php
    /**
     * Daftar item navigasi.
     * Tambah atau hapus item di sini tanpa menyentuh markup.
     *
     * @var array $navItems
     */
    $navItems = [
        [
            'label'  => 'Dashboard',
            'route'  => 'dashboard',
            'match'  => 'dashboard',          // pola untuk routeIs()
            'icon'   => 'dashboard',
        ],
        [
            'label'  => 'Data Pelanggan',
            'route'  => 'pelanggan.index',
            'match'  => 'pelanggan.*',
            'icon'   => 'pelanggan',
        ],
        [
            'label'  => 'Data Produk',
            'route'  => 'produk.index',
            'match'  => 'produk.*',
            'icon'   => 'produk',
        ],
        [
            'label'  => 'Data Supplier',
            'route'  => 'supplier.index',
            'match'  => 'supplier.*',
            'icon'   => 'supplier',
        ],
        [
            'label'  => 'Data Pesanan',
            'route'  => 'pesanan.index',
            'match'  => 'pesanan.*',
            'icon'   => 'pesanan',
        ],
        [
            'label'  => 'Prediksi',
            'route'  => 'prediksi.index',
            'match'  => 'prediksi.*',
            'icon'   => 'prediksi',
        ],
        [
            'label'  => 'Laporan',
            'route'  => 'laporan.index',
            'match'  => 'laporan.*',
            'icon'   => 'laporan',
        ],
    ];
@endphp

{{-- ─────────────────────────── Sidebar ─────────────────────────── --}}
<aside class="simapes-sidebar" id="sidebar" role="navigation" aria-label="Navigasi Utama">

    {{-- ── Logo & Brand ── --}}
    <div class="sidebar-brand">
        <div class="brand-icon-wrap" aria-hidden="true">
            <img src="{{ asset('logoauth/logo2.png') }}" alt="Logo SIMAPES" class="brand-icon-img">
        </div>
        <span class="brand-name">SIMAPES</span>
    </div>

    {{-- ── Navigasi ── --}}
    <nav class="sidebar-nav" role="menubar">
        @foreach ($navItems as $item)
            @php
                $isActive = request()->routeIs($item['match']);
            @endphp

            {{-- Cek apakah route terdaftar, fallback ke '#' jika belum --}}
            @php
                $href = Route::has($item['route']) ? route($item['route']) : '#';
            @endphp

            <a
                href="{{ $href }}"
                class="nav-item {{ $isActive ? 'nav-item--active' : '' }}"
                role="menuitem"
                aria-current="{{ $isActive ? 'page' : 'false' }}"
            >
                {{-- Icon SVG inline per jenis --}}
                <span class="nav-icon" aria-hidden="true">
                    @switch($item['icon'])

                        {{-- Dashboard: rumah --}}
                        @case('dashboard')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9L12 2l9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                <polyline points="9 22 9 12 15 12 15 22"/>
                            </svg>
                        @break

                        {{-- Pelanggan: orang --}}
                        @case('pelanggan')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                        @break

                        {{-- Produk: baju/seragam --}}
                        @case('produk')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.38 3.46L16 2a4 4 0 0 1-8 0L3.62 3.46a2 2 0 0 0-1.34 2.23l.58 3.57a1 1 0 0 0 .99.84H6v10c0 1.1.9 2 2 2h8a2 2 0 0 0 2-2V10h2.15a1 1 0 0 0 .99-.84l.58-3.57a2 2 0 0 0-1.34-2.23z"/>
                            </svg>
                        @break

                        {{-- Supplier: paket/pengiriman --}}
                        @case('supplier')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/>
                                <polygon points="12 22.08 12 12 3 6.92 3 17.08 12 22.08"/>
                                <polygon points="12 22.08 12 12 21 6.92 21 17.08 12 22.08"/>
                                <polygon points="12 12 3 6.92 12 1.84 21 6.92 12 12"/>
                            </svg>
                        @break

                        {{-- Pesanan: daftar/dokumen --}}
                        @case('pesanan')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                                <rect x="9" y="3" width="6" height="4" rx="1"/>
                                <line x1="9" y1="12" x2="15" y2="12"/>
                                <line x1="9" y1="16" x2="13" y2="16"/>
                            </svg>
                        @break

                        {{-- Prediksi: grafik batang --}}
                        @case('prediksi')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="20" x2="18" y2="10"/>
                                <line x1="12" y1="20" x2="12" y2="4"/>
                                <line x1="6"  y1="20" x2="6"  y2="14"/>
                                <line x1="2"  y1="20" x2="22" y2="20"/>
                            </svg>
                        @break

                        {{-- Laporan: file/printer --}}
                        @case('laporan')
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10 9 9 9 8 9"/>
                            </svg>
                        @break

                    @endswitch
                </span>

                <span class="nav-label">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- ── Spacer ── --}}
    <div class="sidebar-spacer" aria-hidden="true"></div>

    {{-- ── Tombol Logout ── --}}
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item nav-item--logout" id="btn-logout">
                <span class="nav-icon" aria-hidden="true">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </span>
                <span class="nav-label">Logout</span>
            </button>
        </form>
    </div>

</aside>

{{-- ─────────────────────── Scoped CSS ─────────────────────────── --}}
<style>
    /* ── Tokens ── */
    :root {
        --sb-width:         160px;
        --sb-bg:            #4A90D9;
        --sb-bg-dark:       #3a7bc8;
        --sb-active-bg:     #ffffff;
        --sb-active-text:   #1A4FAB;
        --sb-text:          #ffffff;
        --sb-text-muted:    rgba(255,255,255,.75);
        --sb-icon-size:     22px;
        --sb-item-radius:   12px;
        --sb-font:          'Inter', sans-serif;
        --sb-transition:    0.18s ease;
    }

    /* ── Sidebar Shell ── */
    .simapes-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: var(--sb-width);
        height: 100vh;
        background: var(--sb-bg);
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 28px 0 24px;
        z-index: 100;
        font-family: var(--sb-font);
        box-shadow: 4px 0 20px rgba(26,79,171,.15);
        overflow: hidden;
    }

    /* ── Brand / Logo ── */
    .sidebar-brand {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        margin-bottom: 32px;
        padding: 0 12px;
        width: 100%;
    }

    .brand-icon-wrap {
        width: 60px;
        height: 60px;
        background: #ffffff;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 4px;
    }

    .brand-icon-img {
        width: 48px;
        height: 48px;
        object-fit: contain;
    }

    .brand-name {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--sb-text);
        letter-spacing: .8px;
        text-transform: uppercase;
    }

    /* ── Nav Container ── */
    .sidebar-nav {
        display: flex;
        flex-direction: column;
        gap: 4px;
        width: 100%;
        padding: 0 10px;
    }

    /* ── Nav Item (link & button share same class) ── */
    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        padding: 12px 8px;
        border-radius: var(--sb-item-radius);
        color: var(--sb-text);
        text-decoration: none;
        background: transparent;
        border: none;
        cursor: pointer;
        font-family: var(--sb-font);
        transition: background var(--sb-transition), color var(--sb-transition), transform var(--sb-transition);
        text-align: center;
    }

    .nav-item:hover:not(.nav-item--active) {
        background: rgba(255,255,255,.15);
        transform: translateY(-1px);
    }

    /* ── Active State ── */
    .nav-item--active {
        background: var(--sb-active-bg);
        color: var(--sb-active-text);
        box-shadow: 0 4px 12px rgba(0,0,0,.1);
    }

    /* ── Icon & Label ── */
    .nav-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: var(--sb-icon-size);
        height: var(--sb-icon-size);
        flex-shrink: 0;
    }

    .nav-label {
        font-size: .72rem;
        font-weight: 600;
        line-height: 1.2;
        word-break: break-word;
    }

    /* ── Spacer dorong logout ke bawah ── */
    .sidebar-spacer { flex: 1; }

    /* ── Footer / Logout ── */
    .sidebar-footer {
        width: 100%;
        padding: 0 10px;
    }

    .nav-item--logout {
        color: var(--sb-text);
        opacity: .85;
    }

    .nav-item--logout:hover {
        background: rgba(255,255,255,.15);
        opacity: 1;
        transform: translateY(-1px);
    }
</style>
