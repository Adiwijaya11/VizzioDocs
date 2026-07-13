<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP - VizzioDocs</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333333;
            line-height: 1.6;
        }
        .wrapper {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .header p {
            color: rgba(255,255,255,0.85);
            font-size: 14px;
            margin-top: 6px;
        }
        .body {
            padding: 40px 36px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 16px;
        }
        .message {
            font-size: 15px;
            color: #555;
            margin-bottom: 30px;
        }
        .otp-box {
            background: linear-gradient(135deg, #f0f0ff 0%, #faf5ff 100%);
            border: 2px dashed #8b5cf6;
            border-radius: 12px;
            padding: 28px 20px;
            text-align: center;
            margin: 24px 0;
        }
        .otp-label {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #7c3aed;
            margin-bottom: 12px;
        }
        .otp-code {
            font-size: 46px;
            font-weight: 900;
            color: #6366f1;
            letter-spacing: 14px;
            font-family: 'Courier New', Courier, monospace;
        }
        .otp-expires {
            font-size: 13px;
            color: #888;
            margin-top: 12px;
        }
        .otp-expires strong {
            color: #ef4444;
        }
        .warning-box {
            background-color: #fff7ed;
            border-left: 4px solid #f97316;
            border-radius: 6px;
            padding: 14px 16px;
            margin: 20px 0;
        }
        .warning-box p {
            font-size: 13px;
            color: #92400e;
        }
        .footer {
            background-color: #f8f9fc;
            border-top: 1px solid #eaeaea;
            padding: 24px 36px;
            text-align: center;
        }
        .footer p {
            font-size: 12px;
            color: #aaa;
            margin: 4px 0;
        }
        .footer a {
            color: #8b5cf6;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="header">
            <h1>🔐 VizzioDocs</h1>
            <p>Atur Ulang Kata Sandi</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">Halo, {{ $recipientName }}!</p>

            <p class="message">
                Kami menerima permintaan untuk mengatur ulang kata sandi akun VizzioDocs Anda.
                Gunakan kode OTP berikut untuk melanjutkan proses:
            </p>

            <!-- OTP Box -->
            <div class="otp-box">
                <p class="otp-label">🔑 Kode OTP Anda</p>
                <div class="otp-code">{{ $otp }}</div>
                <p class="otp-expires">
                    Kode ini berlaku selama <strong>5 menit</strong>.
                </p>
            </div>

            <!-- Warning -->
            <div class="warning-box">
                <p>
                    ⚠️ <strong>Jangan bagikan kode ini</strong> kepada siapa pun, termasuk tim VizzioDocs.
                    Jika Anda tidak melakukan permintaan ini, abaikan email ini dan kata sandi Anda tidak akan berubah.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh sistem VizzioDocs.</p>
            <p>© {{ date('Y') }} VizzioDocs. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>
