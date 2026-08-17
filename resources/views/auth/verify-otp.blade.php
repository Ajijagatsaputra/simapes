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

        .wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .card {
            width: 100%;
            max-width: 480px;
            background: var(--white);
            border-radius: var(--r-card);
            box-shadow: 0 12px 40px rgba(26, 79, 171, .14);
            padding: 40px 32px;
            text-align: center;
            border: 1px solid var(--border-card);
        }

        .brand-logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
            margin-bottom: 12px;
        }

        .brand-title {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 6px;
        }

        .subtitle {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 24px;
        }

        .email-badge {
            display: inline-block;
            background: #eef4ff;
            color: var(--primary);
            font-weight: 600;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.85rem;
            margin-bottom: 24px;
            border: 1px solid var(--border-card);
        }

        /* ── Alert Messages ── */
        .alert {
            padding: 12px 16px;
            border-radius: var(--r-input);
            font-size: 0.85rem;
            margin-bottom: 20px;
            text-align: left;
            line-height: 1.4;
        }

        .alert-danger {
            background-color: #fde8e8;
            color: #9b1c1c;
            border: 1px solid #f8b4b4;
        }

        .alert-success {
            background-color: #def7ec;
            color: #03543f;
            border: 1px solid #84e1bc;
        }

        /* ── OTP Inputs Container ── */
        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 28px;
        }

        .otp-digit {
            width: 52px;
            height: 58px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            background: var(--input-bg);
            border: 2px solid var(--input-border);
            border-radius: var(--r-input);
            outline: none;
            transition: all var(--ease);
        }

        .otp-digit:focus {
            border-color: var(--primary-btn);
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(26, 86, 219, 0.18);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--primary-btn);
            color: var(--white);
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            border-radius: var(--r-input);
            cursor: pointer;
            transition: background var(--ease), transform var(--ease);
            margin-bottom: 20px;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        .resend-box {
            font-size: 0.85rem;
            color: var(--text-sub);
        }

        .btn-resend {
            background: none;
            border: none;
            color: var(--primary-btn);
            font-weight: 600;
            cursor: pointer;
            text-decoration: underline;
            padding: 0;
            font-size: 0.85rem;
        }

        .btn-resend:disabled {
            color: var(--text-sub);
            cursor: not-allowed;
            text-decoration: none;
        }

        .back-link {
            display: inline-block;
            margin-top: 16px;
            font-size: 0.82rem;
            color: var(--text-sub);
            text-decoration: none;
        }

        .back-link:hover {
            color: var(--primary);
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="card">
            <img src="{{ asset('logoauth/logo2.png') }}" alt="Logo SIMAPES" class="brand-logo">
            <h1 class="brand-title">Verifikasi Email</h1>
            <p class="subtitle">
                Kami telah mengirimkan 6 digit kode OTP ke alamat email:
            </p>
            <div class="email-badge">{{ $user->email }}</div>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('otp.verify.submit') }}" method="POST" id="otpForm">
                @csrf
                <input type="hidden" name="code" id="fullOtpCode">

                <div class="otp-inputs" id="otpInputs">
                    <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]*" autofocus>
                    <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" maxlength="1" class="otp-digit" inputmode="numeric" pattern="[0-9]*">
                </div>

                <button type="submit" class="btn-submit">Verifikasi Akun</button>
            </form>

            <div class="resend-box">
                Belum menerima kode?
                <form action="{{ route('otp.resend') }}" method="POST" style="display: inline;" id="resendForm">
                    @csrf
                    <button type="submit" class="btn-resend" id="btnResend" disabled>
                        Kirim Ulang (<span id="timer">60</span>s)
                    </button>
                </form>
            </div>

            <div>
                <a href="{{ route('login') }}" class="back-link">← Kembali ke halaman Login</a>
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

            // Handle Input & Auto-focus
            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    const value = e.target.value;
                    if (value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                    updateFullCode();
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace') {
                        if (!input.value && index > 0) {
                            inputs[index - 1].focus();
                        }
                    }
                });

                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pasteData = e.clipboardData.getData('text').trim();
                    if (/^\d{6}$/.test(pasteData)) {
                        pasteData.split('').forEach((char, i) => {
                            if (inputs[i]) inputs[i].value = char;
                        });
                        inputs[5].focus();
                        updateFullCode();
                    }
                });
            });

            function updateFullCode() {
                let code = '';
                inputs.forEach(input => code += input.value);
                fullOtpInput.value = code;
            }

            otpForm.addEventListener('submit', function (e) {
                updateFullCode();
            });

            // Resend Timer Logic
            let seconds = 60;
            const countdown = setInterval(() => {
                seconds--;
                if (seconds > 0) {
                    timerSpan.textContent = seconds;
                } else {
                    clearInterval(countdown);
                    btnResend.disabled = false;
                    btnResend.innerHTML = 'Kirim Ulang OTP';
                }
            }, 1000);
        });
    </script>
</body>

</html>