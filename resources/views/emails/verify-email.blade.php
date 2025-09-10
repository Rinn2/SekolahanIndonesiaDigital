<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Alamat Email Anda</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f7; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .header { background-color: #4A90E2; color: white; padding: 40px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px 40px; color: #555; line-height: 1.7; }
        .content p { margin: 0 0 20px; }
        .button-container { text-align: center; margin: 30px 0; }
        .button { background-color: #4A90E2; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { background-color: #f4f4f7; padding: 20px 40px; font-size: 12px; color: #888; text-align: center; }
        .link-expire { margin-top: 20px; font-size: 14px; text-align: center; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verifikasi Alamat Email Anda</h1>
        </div>
        <div class="content">
            <p>Halo {{ $user->name }},</p>
            <p>Terima kasih telah mendaftar di <strong>{{ config('app.name') }}</strong>. Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda dan mengaktifkan akun Anda.</p>
            <div class="button-container">
                <a href="{{ $verificationUrl }}" class="button">Verifikasi Email</a>
            </div>
            <p>Jika Anda mengalami masalah dengan tombol di atas, salin dan tempel URL berikut ke browser web Anda:</p>
            <p style="word-break: break-all; font-size: 12px;">{{ $verificationUrl }}</p>
            <p class="link-expire">Tautan verifikasi ini akan kedaluwarsa dalam {{ config('auth.verification.expire', 60) }} menit.</p>
            <p>Jika Anda tidak membuat akun ini, Anda tidak perlu melakukan tindakan lebih lanjut.</p>
            <p>Terima kasih,<br>Tim {{ config('app.name') }}</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Semua Hak Cipta Dilindungi.</p>
        </div>
    </div>
</body>
</html>