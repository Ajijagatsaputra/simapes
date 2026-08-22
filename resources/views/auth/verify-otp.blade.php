<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Verifikasi OTP SIMAPES - Sistem Informasi Manajemen Pesanan Seragam Sekolah">
    <title>Verifikasi OTP — SIMAPES</title>
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
            --primary-light: #eef4ff;
            --text-dark: #1a2b4a;
            --text-muted: #4a6080;
            --text-sub: #5a7494;
            --border-card: #bad3f5;
            --input-bg: #f5f9ff;
            --input-border: #c5d8f5;
            --bg-page: #f0f4fb;
            --white: #ffffff;
            --success: #03543f;
            --success-bg: #def7ec;
            --success-border: #84e1bc;
            --danger: #9b1c1c;
            --danger-bg: #fde8e8;
            --danger-border: #f8b4b4;
            --r-card: 20px;
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

        /* ── Animated background ── */
        body {
            background:
                radial-gradient(ellipse at 20% 80%, rgba(26, 79, 171, 0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(26, 86, 219, 0.06) 0%, transparent 50%),
                #f0f4fb;
        }

        .wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .card {
            width: 100%;
            max-width: 500px;
            background: var(--white);
            border-radius: var(--r-card);
            box-shadow: 0 16px 60px rgba(26, 79, 171, .14), 0 4px 16px rgba(26, 79, 171, .06);
            border: 1px solid var(--border-card);
            overflow: hidden;
            animation: slideUp 0.4s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Step Progress Bar ── */
        .step-bar {
            background: var(--primary-light);
            padding: 14px 24px;
            border-bottom: 1px solid var(--border-card);
            display: flex;
            align-items: center;
            gap: 0;
            justify-content: center;
        }

        .step-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.75rem;
            color: var(--text-sub);
            font-weight: 500;
        }

        .step-item.active {
            color: var(--primary);
            font-weight: 700;
        }

        .step-item.done {
            color: var(--success);
        }

        .step-dot {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--input-border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-sub);
            flex-shrink: 0;
        }

        .step-item.active .step-dot {
            background: var(--primary-btn);
            color: white;
            box-shadow: 0 0 0 4px rgba(26, 86, 219, 0.15);
        }

        .step-item.done .step-dot {
            background: var(--success);
            color: white;
        }

        .step-divider {
            width: 40px;
            height: 2px;
            background: var(--input-border);
            margin: 0 8px;
            flex-shrink: 0;
        }

        .step-divider.done {
            background: var(--success);
        }

        /* ── Card Body ── */
        .card-body {
            padding: 36px 40px 40px;
            text-align: center;
        }

        @media (max-width: 480px) {
            .card-body {
                padding: 28px 24px 32px;
            }
        }

        /* ── Email Icon ── */
        .email-icon-wrapper {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, var(--primary-light), #d8e9ff);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            border: 2px solid var(--border-card);
        }

        .email-icon-wrapper svg {
            color: var(--primary);
        }

        .brand-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 0.88rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .email-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-bottom: 28px;
            border: 1px solid var(--border-card);
            word-break: break-all;
        }

        /* ── Alert Messages ── */
        .alert {
            padding: 12px 16px;
            border-radius: var(--r-input);
            font-size: 0.84rem;
            margin-bottom: 20px;
            text-align: left;
            line-height: 1.5;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .alert-icon {
            flex-shrink: 0;
            margin-top: 1px;
        }

        .alert-danger {
            background-color: var(--danger-bg);
            color: var(--danger);
            border: 1px solid var(--danger-border);
        }

        .alert-success {
            background-color: var(--success-bg);
            color: var(--success);
            border: 1px solid var(--success-border);
        }

        /* ── OTP Input Container ── */
        .otp-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 8px;
        }

        .otp-digit {
            width: 54px;
            height: 62px;
            text-align: center;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--primary);
            background: var(--input-bg);
            border: 2px solid var(--input-border);
            border-radius: var(--r-input);
            outline: none;
            transition: all var(--ease);
            caret-color: transparent;
        }

        .otp-digit:focus {
            border-color: var(--primary-btn);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(26, 86, 219, 0.14);
            transform: translateY(-2px);
        }

        .otp-digit.filled {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .otp-digit.error {
            border-color: #e53935;
            background: #fff5f5;
            animation: shake 0.3s ease;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-4px);
            }

            75% {
                transform: translateX(4px);
            }
        }

        .otp-hint {
            font-size: 0.75rem;
            color: var(--text-sub);
            margin-bottom: 24px;
        }

        /* ── Submit Button ── */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary-btn), #1648c4);
            color: var(--white);
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            border-radius: var(--r-input);
            cursor: pointer;
            transition: all var(--ease);
            margin-bottom: 20px;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #1648c4, #1340b0);
            box-shadow: 0 6px 20px rgba(26, 86, 219, 0.35);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .btn-submit:disabled {
            background: #adb5bd;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* ── Resend Section ── */
        .resend-box {
            font-size: 0.85rem;
            color: var(--text-sub);
            padding: 16px;
            background: var(--input-bg);
            border-radius: var(--r-input);
            border: 1px solid var(--input-border);
            margin-bottom: 16px;
        }

        .countdown-text {
            display: block;
            font-size: 0.8rem;
            color: var(--text-sub);
            margin-top: 4px;
        }

        .timer-number {
            font-weight: 700;
            color: var(--primary);
        }

        .btn-resend {
            background: none;
            border: none;
            color: var(--primary-btn);
            font-weight: 700;
            cursor: pointer;
            text-decoration: underline;
            padding: 0;
            font-size: 0.85rem;
            font-family: var(--font);
            transition: color var(--ease);
        }

        .btn-resend:hover:not(:disabled) {
            color: var(--primary);
        }

        .btn-resend:disabled {
            color: var(--text-sub);
            cursor: not-allowed;
            text-decoration: none;
        }

        /* ── Back Link ── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.82rem;
            color: var(--text-sub);
            text-decoration: none;
            transition: color var(--ease);
        }

        .back-link:hover {
            color: var(--primary);
        }

        /* ── Success Overlay ── */
        .success-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(3, 84, 63, 0.08);
            backdrop-filter: blur(4px);
            z-index: 999;
            align-items: center;
            justify-content: center;
        }

        .success-overlay.show {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .success-card {
            background: white;
            border-radius: 20px;
            padding: 40px 36px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            max-width: 320px;
            animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(0.85);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .success-check {
            width: 72px;
            height: 72px;
            background: var(--success-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            border: 2px solid var(--success-border);
        }

        .success-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .success-sub {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* ── Expiry indicator ── */
        .expiry-bar {
            height: 3px;
            background: var(--input-border);
            border-radius: 2px;
            margin-bottom: 24px;
            overflow: hidden;
        }

        .expiry-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--success), #22c55e);
            border-radius: 2px;
            transition: width 1s linear, background 1s linear;
            width: 100%;
        }
    </style>
</head>

<body>
    <!-- Success Overlay -->
    <div class="success-overlay" id="successOverlay">
        <div class="success-card">
            <div class="success-check">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#03543f" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
            </div>
            <div class="success-title">Email Terverifikasi!</div>
            <p class="success-sub">Akun Anda berhasil diaktifkan. Mengalihkan ke dashboard...</p>
        </div>
    </div>

    <div class="wrapper">
        <div class="card">

            <!-- Step Progress Bar -->
            <div class="step-bar">
                <div class="step-item done">
                    <div class="step-dot">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="3">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </div>
                    <span>Pendaftaran</span>
                </div>
                <div class="step-divider done"></div>
                <div class="step-item active">
                    <div class="step-dot">2</div>
                    <span>Verifikasi Email</span>
                </div>
                <div class="step-divider"></div>
                <div class="step-item">
                    <div class="step-dot">3</div>
                    <span>Selesai</span>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <!-- Icon -->
                <div class="email-icon-wrapper">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg>
                </div>

                <h1 class="brand-title">Cek Email Anda</h1>
                <p class="subtitle">
                    Kami telah mengirimkan kode OTP 6 digit ke:
                </p>
                <div class="email-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3" />
                        <path d="M12 1a11 11 0 1 0 4.07 21.19" />
                    </svg>
                    {{ $user->email }}
                </div>

                @if (session('status'))
                    <div class="alert alert-success">
                        <div class="alert-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                        </div>
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger" id="errorAlert">
                        <div class="alert-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                        </div>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <!-- OTP Form -->
                <form action="{{ route('otp.verify.submit') }}" method="POST" id="otpForm">
                    @csrf
                    <input type="hidden" name="code" id="fullOtpCode">

                    <div class="otp-label">Masukkan Kode OTP</div>

                    <div class="otp-inputs" id="otpInputs">
                        <input type="text" maxlength="1" class="otp-digit {{ $errors->has('code') ? 'error' : '' }}"
                            inputmode="numeric" pattern="[0-9]*" autofocus autocomplete="one-time-code" id="otp-0">
                        <input type="text" maxlength="1" class="otp-digit {{ $errors->has('code') ? 'error' : '' }}"
                            inputmode="numeric" pattern="[0-9]*" id="otp-1">
                        <input type="text" maxlength="1" class="otp-digit {{ $errors->has('code') ? 'error' : '' }}"
                            inputmode="numeric" pattern="[0-9]*" id="otp-2">
                        <input type="text" maxlength="1" class="otp-digit {{ $errors->has('code') ? 'error' : '' }}"
                            inputmode="numeric" pattern="[0-9]*" id="otp-3">
                        <input type="text" maxlength="1" class="otp-digit {{ $errors->has('code') ? 'error' : '' }}"
                            inputmode="numeric" pattern="[0-9]*" id="otp-4">
                        <input type="text" maxlength="1" class="otp-digit {{ $errors->has('code') ? 'error' : '' }}"
                            inputmode="numeric" pattern="[0-9]*" id="otp-5">
                    </div>

                    <div class="otp-hint">Kode berlaku selama 10 menit. Periksa folder spam jika tidak ditemukan.</div>

                    <!-- Expiry bar (visual 10 menit) -->
                    <div class="expiry-bar">
                        <div class="expiry-fill" id="expiryFill"></div>
                    </div>

                    <button type="submit" class="btn-submit" id="btnSubmit">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        Verifikasi Akun
                    </button>
                </form>

                <!-- Resend Section -->
                <div class="resend-box">
                    <div>Belum menerima kode?</div>
                    <form action="{{ route('otp.resend') }}" method="POST" style="display: inline;" id="resendForm">
                        @csrf
                        <button type="submit" class="btn-resend" id="btnResend" disabled>
                            Kirim Ulang OTP
                        </button>
                    </form>
                    <span class="countdown-text" id="countdownText">
                        Dapat dikirim ulang dalam <span class="timer-number"
                            id="timer">{{ $resendCooldown > 0 ? $resendCooldown : 60 }}</span> detik
                    </span>
                </div>

                <!-- Back Link -->
                <div>
                    <a href="{{ route('login') }}" class="back-link">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polyline points="15 18 9 12 15 6" />
                        </svg>
                        Kembali ke halaman Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('.otp-digit');
            const fullOtpInput = document.getElementById('fullOtpCode');
            const otpForm = document.getElementById('otpForm');
            const btnResend = document.getElementById('btnResend');
            const timerSpan = document.getElementById('timer');
            const countdownText = document.getElementById('countdownText');
            const expiryFill = document.getElementById('expiryFill');
            const btnSubmit = document.getElementById('btnSubmit');

            // ── OTP Input Handling ─────────────────────────────────────────
            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    // Hanya izinkan angka
                    e.target.value = e.target.value.replace(/\D/g, '').slice(-1);

                    if (e.target.value && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }

                    e.target.classList.toggle('filled', !!e.target.value);
                    e.target.classList.remove('error');
                    updateFullCode();
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace') {
                        if (!input.value && index > 0) {
                            inputs[index - 1].value = '';
                            inputs[index - 1].classList.remove('filled');
                            inputs[index - 1].focus();
                        }
                        input.classList.remove('filled');
                        updateFullCode();
                    }
                    // Arrow navigation
                    if (e.key === 'ArrowLeft' && index > 0) inputs[index - 1].focus();
                    if (e.key === 'ArrowRight' && index < inputs.length - 1) inputs[index + 1].focus();
                });

                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pasteData = e.clipboardData.getData('text').trim().replace(/\D/g, '');
                    if (pasteData.length >= 6) {
                        pasteData.slice(0, 6).split('').forEach((char, i) => {
                            if (inputs[i]) {
                                inputs[i].value = char;
                                inputs[i].classList.add('filled');
                                inputs[i].classList.remove('error');
                            }
                        });
                        inputs[Math.min(5, pasteData.length - 1)].focus();
                        updateFullCode();
                    }
                });

                // Click to focus
                input.addEventListener('click', () => input.select());
            });

            function updateFullCode() {
                let code = '';
                inputs.forEach(input => code += input.value);
                fullOtpInput.value = code;
            }

            // ── Form Submit ─────────────────────────────────────────────────
            otpForm.addEventListener('submit', function (e) {
                updateFullCode();
                if (fullOtpInput.value.length < 6) {
                    e.preventDefault();
                    inputs.forEach(inp => inp.classList.add('error'));
                    inputs[0].focus();
                    return;
                }
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = `
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="spin">
                        <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                    </svg>
                    Memverifikasi...
                `;
            });

            // ── Resend Countdown ────────────────────────────────────────────
            // Gunakan cooldown dari server (detik tersisa)
            let seconds = parseInt('{{ $resendCooldown > 0 ? $resendCooldown : 60 }}');

            function startCountdown() {
                if (seconds <= 0) {
                    btnResend.disabled = false;
                    countdownText.style.display = 'none';
                    return;
                }

                const countdown = setInterval(() => {
                    seconds--;
                    timerSpan.textContent = seconds;

                    if (seconds <= 0) {
                        clearInterval(countdown);
                        btnResend.disabled = false;
                        countdownText.style.display = 'none';
                    }
                }, 1000);
            }

            startCountdown();

            // ── Expiry Bar (10 menit = 600 detik) ──────────────────────────
            // Ini hanya visual, tidak memblokir submit
            let expirySeconds = 600;
            const expiryInterval = setInterval(() => {
                expirySeconds--;
                const pct = Math.max(0, (expirySeconds / 600) * 100);
                expiryFill.style.width = pct + '%';

                if (pct < 30) {
                    expiryFill.style.background = 'linear-gradient(90deg, #e53935, #ef5350)';
                } else if (pct < 60) {
                    expiryFill.style.background = 'linear-gradient(90deg, #f59e0b, #fbbf24)';
                }

                if (expirySeconds <= 0) clearInterval(expiryInterval);
            }, 1000);

            // ── Spinner CSS ─────────────────────────────────────────────────
            const style = document.createElement('style');
            style.textContent = `.spin { animation: spin 0.8s linear infinite; } @keyframes spin { to { transform: rotate(360deg); } }`;
            document.head.appendChild(style);
        });
    </script>
</body>

</html>