<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi — SIMAPES</title>
    <style>
        /* Reset */
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            border: 0;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f0f4fb;
            color: #1a2b4a;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .email-wrapper {
            max-width: 580px;
            margin: 32px auto;
            padding: 0 16px;
        }

        .container {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(26, 79, 171, 0.1);
            overflow: hidden;
            border: 1px solid #bad3f5;
        }

        /* ── Header ── */
        .header {
            background: linear-gradient(135deg, #1A4FAB 0%, #1A56DB 100%);
            padding: 36px 28px;
            text-align: center;
        }

        .header-logo {
            font-size: 28px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 1px;
            margin: 0 0 6px 0;
        }

        .header-tagline {
            font-size: 12px;
            color: #c2d8ff;
            margin: 0;
            letter-spacing: 0.5px;
        }

        /* ── Body ── */
        .body {
            padding: 40px 32px;
            text-align: center;
        }

        /* ── Step badge ── */
        .step-badge {
            display: inline-block;
            background: #eef4ff;
            color: #1A56DB;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 20px;
            border: 1px solid #bad3f5;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 20px;
        }

        .greeting {
            font-size: 18px;
            color: #1a2b4a;
            font-weight: 700;
            margin: 0 0 12px 0;
        }

        .instruction {
            font-size: 14px;
            color: #4a6080;
            line-height: 1.7;
            margin: 0 0 32px 0;
            max-width: 420px;
            margin-left: auto;
            margin-right: auto;
        }

        /* ── OTP Box ── */
        .otp-box {
            background: linear-gradient(135deg, #f5f9ff, #eef4ff);
            border: 2px dashed #1A56DB;
            border-radius: 16px;
            padding: 28px 20px;
            margin: 0 auto 32px;
            max-width: 320px;
        }

        .otp-label {
            font-size: 11px;
            font-weight: 600;
            color: #5a7494;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 10px 0;
        }

        .otp-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 42px;
            font-weight: 800;
            color: #1A4FAB;
            letter-spacing: 12px;
            margin: 0;
            line-height: 1.1;
        }

        .otp-expiry {
            font-size: 12px;
            color: #5a7494;
            margin: 12px 0 0 0;
        }

        /* ── Steps ── */
        .steps-section {
            background: #f8faff;
            border-radius: 12px;
            padding: 20px 20px;
            margin-bottom: 28px;
            text-align: left;
        }

        .steps-title {
            font-size: 12px;
            font-weight: 700;
            color: #1a2b4a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 12px 0;
        }

        .step-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 13px;
            color: #4a6080;
            line-height: 1.5;
        }

        .step-row:last-child {
            margin-bottom: 0;
        }

        .step-num {
            width: 20px;
            height: 20px;
            background: #1A56DB;
            color: white;
            border-radius: 50%;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* ── Warning ── */
        .warning {
            background: #fff8e6;
            border: 1px solid #fcd34d;
            border-left: 4px solid #f59e0b;
            border-radius: 10px;
            padding: 14px 16px;
            text-align: left;
            font-size: 13px;
            color: #78350f;
            line-height: 1.6;
            margin-bottom: 0;
        }

        /* ── Footer ── */
        .footer {
            background: #f5f8ff;
            border-top: 1px solid #e5edf9;
            padding: 24px 28px;
            text-align: center;
        }

        .footer-text {
            font-size: 12px;
            color: #7a90b0;
            line-height: 1.6;
            margin: 0 0 8px 0;
        }

        .footer-copy {
            font-size: 11px;
            color: #9db0c8;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="container">

            <!-- Header -->
            <div class="header">
                <div class="header-logo">SIMAPES</div>
                <p class="header-tagline">Sistem Informasi Manajemen Pesanan Seragam Sekolah</p>
            </div>

            <!-- Body -->
            <div class="body">
                <div class="step-badge">🔐 Verifikasi Akun</div>

                <h1 class="greeting">Halo, {{ $user->name }}!</h1>

                <p class="instruction">
                    Terima kasih telah mendaftar di <strong>SIMAPES</strong>. Gunakan kode OTP di bawah ini
                    untuk memverifikasi email Anda dan mengaktifkan akun Anda.
                </p>

                <!-- OTP Code Box -->
                <div class="otp-box">
                    <p class="otp-label">Kode OTP Anda</p>
                    <p class="otp-code">{{ $otpCode }}</p>
                    <p class="otp-expiry">⏳ Berlaku selama <strong>10 menit</strong></p>
                </div>

                <!-- Steps Guide -->
                <div class="steps-section">
                    <div class="steps-title">Cara menggunakan kode ini:</div>
                    <div class="step-row">
                        <div class="step-num">1</div>
                        <div>Buka halaman verifikasi OTP yang telah dibuka di browser Anda</div>
                    </div>
                    <div class="step-row">
                        <div class="step-num">2</div>
                        <div>Masukkan 6 digit kode OTP di atas ke dalam kotak yang tersedia</div>
                    </div>
                    <div class="step-row">
                        <div class="step-num">3</div>
                        <div>Klik <strong>"Verifikasi Akun"</strong> — akun Anda akan langsung aktif</div>
                    </div>
                </div>

                <!-- Security Warning -->
                <div class="warning">
                    ⚠️ <strong>Peringatan Keamanan:</strong><br>
                    Jangan berikan kode OTP ini kepada siapa pun, termasuk pihak SIMAPES.
                    Jika Anda tidak merasa melakukan pendaftaran, abaikan email ini.
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p class="footer-text">
                    Email ini dikirim secara otomatis ke <strong>{{ $user->email }}</strong>.<br>
                    Jika ada pertanyaan, hubungi kami melalui halaman kontak SIMAPES.
                </p>
                <p class="footer-copy">&copy; {{ date('Y') }} SIMAPES. Seluruh hak cipta dilindungi.</p>
            </div>

        </div>
    </div>
</body>

</html>