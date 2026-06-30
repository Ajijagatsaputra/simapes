<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Register SIMAPES - Sistem Informasi Manajemen Pesanan Seragam Sekolah">
    <title>Daftar Pelanggan — SIMAPES</title>
    <link rel="icon" type="image/png" href="{{ asset('logoauth/logo2.png') }}">
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
            --primary: #1A4FAB;
            --primary-btn: #1A56DB;
            --primary-hover: #1648c4;
            --text-dark: #1a2b4a;
            --text-muted: #4a6080;
            --text-sub: #5a7494;
            --border-card: #bad3f5;
            --input-bg: #f5f9ff;
            --input-border: #c5d8f5;
            --bg-page: #f0f4fb;
            --white: #ffffff;
            --r-card: 16px;
            --r-input: 10px;
            --font: 'Inter', sans-serif;
            --ease: 0.2s ease;
        }

        html,
        body {
            height: 100%;
            font-family: var(--font);
            background: var(--bg-page);
        }

        /* ── Wrapper ── */
        .register-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 12px;
        }

        /* ── Kartu utama ── */
        .register-card {
            display: flex;
            width: 100%;
            max-width: 960px;
            background: var(--white);
            border-radius: var(--r-card);
            box-shadow: 0 8px 40px rgba(26, 79, 171, .12);
            overflow: hidden;
        }

        /* ── Panel Kiri (Branding) ── */
        .panel-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 48px 36px;
            gap: 10px;
            background: var(--white);
        }

        .brand-logo {
            width: 88px;
            height: 88px;
            object-fit: contain;
            margin-bottom: 6px;
        }

        .brand-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: .5px;
        }

        .brand-subtitle {
            font-size: .93rem;
            font-weight: 600;
            color: var(--primary);
            line-height: 1.55;
            max-width: 230px;
        }

        .brand-desc {
            font-size: .8rem;
            color: var(--text-sub);
            line-height: 1.65;
            max-width: 230px;
            margin-top: 2px;
        }

        .brand-illus {
            width: 100%;
            max-width: 250px;
            object-fit: contain;
            margin-top: 14px;
        }

        /* ── Garis Pembatas ── */
        .divider {
            width: 1px;
            background: linear-gradient(to bottom, transparent, var(--border-card) 25%, var(--border-card) 75%, transparent);
            align-self: stretch;
        }

        /* ── Panel Kanan (Form) ── */
        .panel-right {
            flex: 1.3;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
            background: var(--white);
        }

        .form-box {
            width: 100%;
            border: 1.5px solid var(--border-card);
            border-radius: var(--r-card);
            padding: 30px;
        }

        .form-title {
            font-size: 1.45rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .form-sub {
            font-size: .8rem;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 20px;
        }

        /* ── Form Grid ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 16px;
            margin-bottom: 20px;
        }

        @media (max-width: 580px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ── Field ── */
        .field {
            margin-bottom: 0;
        }

        .field.full-width {
            grid-column: span 2;
        }

        @media (max-width: 580px) {
            .field.full-width {
                grid-column: span 1;
            }
        }

        .field-label {
            display: block;
            font-size: .78rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-sub);
            display: flex;
            pointer-events: none;
        }

        .field-input,
        .field-textarea {
            width: 100%;
            padding: 9.5px 12px 9.5px 36px;
            font-family: var(--font);
            font-size: .85rem;
            color: var(--text-dark);
            background: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: var(--r-input);
            outline: none;
            transition: border-color var(--ease), box-shadow var(--ease), background var(--ease);
        }

        .field-textarea {
            padding: 9.5px 12px;
            resize: vertical;
            min-height: 52px;
        }

        .field-input:focus,
        .field-textarea:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 79, 171, .11);
            background: var(--white);
        }

        .field-input::placeholder,
        .field-textarea::placeholder {
            color: #b0bec5;
        }

        .field-error {
            font-size: .72rem;
            color: #e53935;
            margin-top: 4px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-sub);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
            border-radius: 4px;
            transition: color var(--ease), background var(--ease);
        }

        .toggle-password:hover {
            color: var(--primary);
            background: rgba(26, 79, 171, .05);
        }

        .field-input.has-error,
        .field-textarea.has-error {
            border-color: #e53935 !important;
            background: #fff8f8 !important;
        }

        /* ── Tombol ── */
        .btn-register {
            width: 100%;
            padding: 12px;
            background: var(--primary-btn);
            color: var(--white);
            font-family: var(--font);
            font-size: .9rem;
            font-weight: 600;
            border: none;
            border-radius: var(--r-input);
            cursor: pointer;
            letter-spacing: .3px;
            transition: background var(--ease), transform var(--ease), box-shadow var(--ease);
        }

        .btn-register:hover {
            background: var(--primary-hover);
            box-shadow: 0 4px 16px rgba(26, 79, 171, .28);
            transform: translateY(-1px);
        }

        /* ── Responsive ── */
        @media (max-width: 820px) {
            .register-card {
                flex-direction: column;
            }

            .divider {
                width: 100%;
                height: 1px;
                background: linear-gradient(to right, transparent, var(--border-card) 25%, var(--border-card) 75%, transparent);
            }

            .panel-left {
                padding: 32px 24px 16px;
            }

            .panel-right {
                padding: 16px;
            }

            .form-box {
                padding: 24px 20px;
                border: none;
            }
        }
    </style>
</head>

<body>
    <div class="register-wrapper">
        <div class="register-card">

            {{-- Panel Kiri: Branding --}}
            <div class="panel-left">
                <img src="{{ asset('logoauth/logo2.png') }}" alt="Logo SIMAPES" class="brand-logo">
                <h1 class="brand-title">SIMAPES</h1>
                <p class="brand-subtitle">Sistem Informasi Manajemen<br>Pesanan Seragam Sekolah</p>
                <p class="brand-desc">Daftar sebagai pelanggan untuk melakukan pemesanan seragam sekolah dengan mudah
                </p>
                <img src="{{ asset('auth/logo.png') }}" alt="Ilustrasi Seragam Sekolah" class="brand-illus">
            </div>

            <div class="divider" aria-hidden="true"></div>

            {{-- Panel Kanan: Form --}}
            <div class="panel-right">
                <div class="form-box">
                    <h2 class="form-title">Daftar Akun Baru</h2>
                    <p class="form-sub">Lengkapi data diri untuk pendaftaran akun pelanggan</p>

                    <form method="POST" action="{{ route('register') }}" novalidate>
                        @csrf

                        <div class="form-grid">
                            {{-- Nama Lengkap --}}
                            <div class="field">
                                <label class="field-label" for="name">Nama Lengkap</label>
                                <div class="field-wrap">
                                    <span class="field-icon" aria-hidden="true">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                            <circle cx="12" cy="7" r="4" />
                                        </svg>
                                    </span>
                                    <input id="name" class="field-input" type="text" name="name"
                                        value="{{ old('name') }}" placeholder="Contoh: Lutfa Nur" required autofocus>
                                </div>
                                @error('name')
                                    <p class="field-error" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="field">
                                <label class="field-label" for="email">Email</label>
                                <div class="field-wrap">
                                    <span class="field-icon" aria-hidden="true">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path
                                                d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                            <polyline points="22,6 12,13 2,6" />
                                        </svg>
                                    </span>
                                    <input id="email" class="field-input" type="email" name="email"
                                        value="{{ old('email') }}" placeholder="contoh@gmail.com" required>
                                </div>
                                @error('email')
                                    <p class="field-error" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- No WhatsApp --}}
                            <div class="field">
                                <label class="field-label" for="no_whatsapp">No. WhatsApp</label>
                                <div class="field-wrap">
                                    <span class="field-icon" aria-hidden="true">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path
                                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                        </svg>
                                    </span>
                                    <input id="no_whatsapp" class="field-input" type="text" name="no_whatsapp"
                                        value="{{ old('no_whatsapp') }}" placeholder="08xxxxxxxxxx">
                                </div>
                                @error('no_whatsapp')
                                    <p class="field-error" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nama Sekolah --}}
                            <div class="field">
                                <label class="field-label" for="nama_sekolah">Sekolah / Instansi</label>
                                <div class="field-wrap">
                                    <span class="field-icon" aria-hidden="true">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M22 10v6M2 10l10-5 10 5-10 5z" />
                                            <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5" />
                                        </svg>
                                    </span>
                                    <input id="nama_sekolah" class="field-input" type="text" name="nama_sekolah"
                                        value="{{ old('nama_sekolah') }}" placeholder="Contoh: SMAN 1 Bandung">
                                </div>
                                @error('nama_sekolah')
                                    <p class="field-error" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Alamat --}}
                            <div class="field full-width">
                                <label class="field-label" for="alamat">Alamat Lengkap</label>
                                <textarea id="alamat" class="field-textarea" name="alamat"
                                    placeholder="Masukkan alamat pengiriman lengkap anda...">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                    <p class="field-error" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="field">
                                <label class="field-label" for="password">Password</label>
                                <div class="field-wrap">
                                    <span class="field-icon" aria-hidden="true">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                        </svg>
                                    </span>
                                    <input id="password" class="field-input" type="password" name="password"
                                        placeholder="Min. 8 karakter" required autocomplete="new-password"
                                        style="padding-right: 42px;">
                                    <button type="button" class="toggle-password" data-target="password"
                                        aria-label="Tampilkan password">
                                        <svg class="eye-icon" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="field-error" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div class="field">
                                <label class="field-label" for="password_confirmation">Konfirmasi Password</label>
                                <div class="field-wrap">
                                    <span class="field-icon" aria-hidden="true">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                        </svg>
                                    </span>
                                    <input id="password_confirmation" class="field-input" type="password"
                                        name="password_confirmation" placeholder="Ulangi password" required
                                        autocomplete="new-password" style="padding-right: 42px;">
                                    <button type="button" class="toggle-password" data-target="password_confirmation"
                                        aria-label="Tampilkan password">
                                        <svg class="eye-icon" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                            <circle cx="12" cy="12" r="3" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <p class="field-error" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Tombol Register --}}
                        <button type="submit" class="btn-register">Daftar Akun</button>
                    </form>

                    {{-- Link Login --}}
                    <div style="text-align: center; margin-top: 20px; font-size: 0.82rem; color: var(--text-muted);">
                        Sudah memiliki akun? <a href="{{ route('login') }}"
                            style="color: var(--primary-btn); text-decoration: none; font-weight: 700;">Login di
                            sini</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Toggle Password Visibility
            document.querySelectorAll('.toggle-password').forEach(btn => {
                btn.addEventListener('click', () => {
                    const targetId = btn.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const isPassword = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isPassword ? 'text' : 'password');
                    btn.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');

                    const icon = btn.querySelector('.eye-icon');
                    if (isPassword) {
                        icon.innerHTML = `
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                            <line x1="1" y1="1" x2="23" y2="23" />
                        `;
                    } else {
                        icon.innerHTML = `
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        `;
                    }
                });
            });
        });
    </script>
</body>

</html>