<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login SIMAPES - Sistem Informasi Manajemen Pesanan Seragam Sekolah">
    <title>Login — SIMAPES</title>
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
        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        /* ── Kartu utama ── */
        .login-card {
            display: flex;
            width: 100%;
            max-width: 860px;
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
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            background: var(--white);
        }

        .form-box {
            width: 100%;
            max-width: 360px;
            border: 1.5px solid var(--border-card);
            border-radius: var(--r-card);
            padding: 40px 34px;
        }

        .form-title {
            font-size: 1.55rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .form-sub {
            font-size: .83rem;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 28px;
        }

        /* ── Alert Status ── */
        .alert-ok {
            background: #e8f5e9;
            color: #2e7d32;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: .82rem;
            margin-bottom: 16px;
        }

        /* ── Field ── */
        .field {
            margin-bottom: 20px;
        }

        .field-label {
            display: block;
            font-size: .8rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-sub);
            display: flex;
            pointer-events: none;
        }

        .field-input {
            width: 100%;
            padding: 11px 14px 11px 40px;
            font-family: var(--font);
            font-size: .88rem;
            color: var(--text-dark);
            background: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: var(--r-input);
            outline: none;
            transition: border-color var(--ease), box-shadow var(--ease), background var(--ease);
        }

        .field-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 79, 171, .11);
            background: var(--white);
        }

        .field-input::placeholder {
            color: #b0bec5;
        }

        .field-error {
            font-size: .76rem;
            color: #e53935;
            margin-top: 5px;
        }

        /* ── Eye Toggle & Validation Styles ── */
        .toggle-password {
            position: absolute;
            right: 13px;
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

        .field-input.has-error {
            border-color: #e53935 !important;
            background: #fff8f8 !important;
        }

        .field-input.has-error:focus {
            box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.15) !important;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            20%,
            60% {
                transform: translateX(-6px);
            }

            40%,
            80% {
                transform: translateX(6px);
            }
        }

        .shake {
            animation: shake 0.4s ease;
        }

        /* ── Tombol ── */
        .btn-login {
            width: 100%;
            padding: 13px;
            margin-top: 8px;
            background: var(--primary-btn);
            color: var(--white);
            font-family: var(--font);
            font-size: .93rem;
            font-weight: 600;
            border: none;
            border-radius: var(--r-input);
            cursor: pointer;
            letter-spacing: .3px;
            transition: background var(--ease), transform var(--ease), box-shadow var(--ease);
        }

        .btn-login:hover {
            background: var(--primary-hover);
            box-shadow: 0 4px 16px rgba(26, 79, 171, .28);
            transform: translateY(-1px);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: none;
        }

        /* ── Responsive ── */
        @media (max-width: 660px) {
            .login-card {
                flex-direction: column;
            }

            .divider {
                width: 100%;
                height: 1px;
                background: linear-gradient(to right, transparent, var(--border-card) 25%, var(--border-card) 75%, transparent);
            }

            .panel-left {
                padding: 36px 24px 20px;
            }

            .panel-right {
                padding: 24px 20px 36px;
            }

            .form-box {
                padding: 28px 18px;
                border: none;
            }
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="login-card">

            {{-- Panel Kiri: Branding --}}
            <div class="panel-left">
                <img src="{{ asset('logoauth/logo2.png') }}" alt="Logo SIMAPES" class="brand-logo">
                <h1 class="brand-title">SIMAPES</h1>
                <p class="brand-subtitle">Sistem Informasi Manajemen<br>Pesanan Seragam Sekolah</p>
                <p class="brand-desc">Kelola data pelanggan, produk, pesanan,<br>dan prediksi pesanan dengan
                    mudah<br>dan terstruktur</p>
                <img src="{{ asset('auth/logo.png') }}" alt="Ilustrasi Seragam Sekolah" class="brand-illus">
            </div>

            <div class="divider" aria-hidden="true"></div>
            <div class="panel-right">
                <div class="form-box">

                    @if (session('status'))
                        <div class="alert-ok" role="alert">{{ session('status') }}</div>
                    @endif

                    <h2 class="form-title">Selamat Datang!</h2>
                    <p class="form-sub">Silahkan login untuk melanjutkan ke sistem</p>

                    <form method="POST" action="{{ route('login') }}" novalidate>
                        @csrf

                        {{-- Username --}}
                        <div class="field">
                            <label class="field-label" for="email">Username</label>
                            <div class="field-wrap">
                                <span class="field-icon" aria-hidden="true">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                </span>
                                <input id="email" class="field-input" type="email" name="email"
                                    value="{{ old('email') }}" placeholder="Masukkan email anda" required autofocus
                                    autocomplete="username">
                            </div>
                            @error('email')
                                <p class="field-error" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="field">
                            <label class="field-label" for="password">Password</label>
                            <div class="field-wrap">
                                <span class="field-icon" aria-hidden="true">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                    </svg>
                                </span>
                                <input id="password" class="field-input" type="password" name="password"
                                    placeholder="Masukkan password anda" required autocomplete="current-password"
                                    style="padding-right: 42px;">
                                <button type="button" id="toggle-password" class="toggle-password"
                                    aria-label="Tampilkan password">
                                    <svg id="eye-icon" class="eye-icon" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="field-error" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- Tombol Login --}}
                        <button id="btn-login" type="submit" class="btn-login">Login</button>
                    </form>

                    {{-- Link Registrasi --}}
                    <div style="text-align: center; margin-top: 20px; font-size: 0.82rem; color: var(--text-muted);">
                        Belum punya akun? <a href="{{ route('register') }}"
                            style="color: var(--primary-btn); text-decoration: none; font-weight: 700; transition: color var(--ease);">Daftar
                            Pelanggan Baru</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const togglePasswordBtn = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');

            // Toggle Password Visibility
            togglePasswordBtn.addEventListener('click', () => {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                togglePasswordBtn.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');

                if (isPassword) {
                    // Eye off icon
                    eyeIcon.innerHTML = `
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24" />
                        <line x1="1" y1="1" x2="23" y2="23" />
                    `;
                } else {
                    // Eye icon
                    eyeIcon.innerHTML = `
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    `;
                }
            });

            // Validation logic
            const form = document.querySelector('form');
            const emailInput = document.getElementById('email');

            form.addEventListener('submit', (e) => {
                let isValid = true;

                // Clear previous client-side errors
                document.querySelectorAll('.client-error').forEach(el => el.remove());
                emailInput.classList.remove('has-error');
                passwordInput.classList.remove('has-error');

                // Validate Email
                if (!emailInput.value.trim()) {
                    showError(emailInput, 'Username / Email wajib diisi!');
                    isValid = false;
                } else if (!validateEmail(emailInput.value.trim())) {
                    showError(emailInput, 'Format email tidak valid!');
                    isValid = false;
                }

                // Validate Password
                if (!passwordInput.value.trim()) {
                    showError(passwordInput, 'Password wajib diisi!');
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();

                    // Shake the form box card for nice premium feedback
                    const card = document.querySelector('.form-box');
                    card.classList.remove('shake');
                    // Trigger reflow
                    void card.offsetWidth;
                    card.classList.add('shake');
                }
            });

            // Real-time input validation cleanup
            [emailInput, passwordInput].forEach(input => {
                input.addEventListener('input', () => {
                    input.classList.remove('has-error');
                    const errorMsg = input.closest('.field').querySelector('.client-error');
                    if (errorMsg) errorMsg.remove();
                });
            });

            function showError(inputElement, message) {
                inputElement.classList.add('has-error');
                const field = inputElement.closest('.field');

                // Remove server-side validation error if present
                const serverError = field.querySelector('.field-error');
                if (serverError) serverError.remove();

                const errorP = document.createElement('p');
                errorP.className = 'field-error client-error';
                errorP.setAttribute('role', 'alert');
                errorP.innerText = message;
                errorP.style.opacity = '0';
                errorP.style.transform = 'translateY(-5px)';
                errorP.style.transition = 'all 0.3s ease';
                field.appendChild(errorP);

                // Animate fade-in
                setTimeout(() => {
                    errorP.style.opacity = '1';
                    errorP.style.transform = 'translateY(0)';
                }, 10);
            }

            function validateEmail(email) {
                // simple email regex
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return re.test(email);
            }
        });
    </script>
</body>

</html>