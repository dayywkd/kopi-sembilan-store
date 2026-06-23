<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password | Toko Kopi Sembilan</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #FFFFFF;
            color: #121212;
            margin: 0;
            padding: 40px 20px;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            border: 1px solid #E5E7EB;
            padding: 40px;
            background-color: #FFFFFF;
        }
        .header {
            text-align: center;
            border-bottom: 1px solid #F3F4F6;
            padding-bottom: 24px;
            margin-bottom: 32px;
        }
        .logo {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #121212;
            text-decoration: none;
        }
        .title {
            font-size: 20px;
            font-weight: 600;
            letter-spacing: -0.02em;
            margin-bottom: 16px;
            color: #121212;
        }
        .content {
            font-size: 14px;
            line-height: 1.6;
            color: #4B5563;
            margin-bottom: 32px;
        }
        .btn-container {
            text-align: center;
            margin-bottom: 32px;
        }
        .btn {
            display: inline-block;
            background-color: #121212;
            color: #FFFFFF !important;
            text-decoration: none;
            padding: 14px 32px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            border-radius: 9999px;
            transition: background-color 0.2s ease;
        }
        .btn:hover {
            background-color: #374151;
        }
        .footer {
            font-size: 11px;
            line-height: 1.5;
            color: #9CA3AF;
            border-top: 1px solid #F3F4F6;
            padding-top: 24px;
            text-align: center;
        }
        .footer-link {
            color: #121212;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <a href="{{ route('home') }}" class="logo">Toko Kopi Sembilan</a>
    </div>

    <div class="title">Reset Kata Sandi</div>

    <div class="content">
        Halo {{ $name ?? 'Pelanggan' }},<br><br>
        Kami menerima permintaan untuk menyetel ulang kata sandi akun Toko Kopi Sembilan Anda. Silakan klik tombol di bawah ini untuk memperbarui kata sandi Anda:
    </div>

    <div class="btn-container">
        <a href="{{ route('password.reset', ['token' => $token, 'email' => $email]) }}" class="btn">Perbarui Kata Sandi</a>
    </div>

    <div class="content">
        Tautan ini berlaku selama 60 menit. Jika Anda tidak meminta pengaturan ulang kata sandi, abaikan email ini dengan aman.
    </div>

    <div class="footer">
        Hubungi kami jika Anda mengalami kesulitan atau kunjungi roastery kami di Tuban.<br>
        &copy; 2026 Kopi Sembilan. All rights reserved.
    </div>
</div>

</body>
</html>
