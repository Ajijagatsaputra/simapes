<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi — SIMAPES</title>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f0f4fb;
            color: #1a2b4a;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .container {
            max-width: 560px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(26, 79, 171, 0.08);
            overflow: hidden;
            border: 1px solid #bad3f5;
        }

        .header {
            background-color: #1A4FAB;
            padding: 32px 24px;
            text-align: center;
            color: #ffffff;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .header p {
            margin: 6px 0 0 0;
            font-size: 13px;
            color: #d0e1fd;
        }

        .body {
            padding: 36px 28px;
            text-align: center;
        }

        .greeting {
            font-size: 16px;
            color: #1a2b4a;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .instruction {
            font-size: 14px;
            color: #4a6080;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        .otp-box {
            background-color: #f5f9ff;
            border: 2px dashed #1A56DB;
            border-radius: 12px;
            padding: 20px 16px;
            display: inline-block;
            margin-bottom: 28px;
        }

        .otp-code {
            font-family: 'Courier New', Courier, monospace;
            font-size: 36px;
            font-weight: 800;
            color: #1A4FAB;
            letter-spacing: 10px;
            margin: 0;
        }

        .warning {
            font-size: 12px;
            color: #5a7494;
            background-color: #f0f4fb;
            padding: 12px 16px;
            border-radius: 8px;
            line-height: 1.5;
        }

        .footer {
            background-color: #fafcff;
            border-top: 1px solid #e5edf9;
            padding: 20px 24px;
            text-align: center;
            font-size: 12px;
            color: #7a90b0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>SIMAPES</h1>
            <p>Sistem Informasi Manajemen Pesanan Seragam Sekolah</p>
        </div>
        <div class="body">
            <div class="greeting">Halo, {{ $user->name }}!</div>
            <p class="instruction">
                Terima kasih telah mendaftar di <strong>SIMAPES</strong>. Gunakan kode One-Time Password (OTP) di bawah
                ini untuk memverifikasi email Anda dan mengaktifkan akun Anda:
            </p>
            <div class="otp-box">
                <p class="otp-code">{{ $otpCode }}</p>
            </div>
            <div class="warning">
                ⏳ Kode OTP ini berlaku selama <strong>10 menit</strong>.<br>
                Jangan berikan kode ini kepada siapa pun, termasuk pihak SIMAPES.
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} SIMAPES. Seluruh hak cipta dilindungi.
        </div>
    </div>
</body>

</html>