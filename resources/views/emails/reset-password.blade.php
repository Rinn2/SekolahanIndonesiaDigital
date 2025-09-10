{{-- resources/views/emails/.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SIPEL</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #3b82f6, #10b981);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .content h2 {
            color: #1f2937;
            margin-bottom: 20px;
            font-size: 20px;
        }
        .content p {
            margin-bottom: 16px;
            color: #4b5563;
        }
        .button {
            display: inline-block;
            background: #3b82f6;
            color: white !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 0;
            text-align: center;
        }
        .button:hover {
            background: #2563eb;
        }
        .info-box {
            background: #f3f4f6;
            border-left: 4px solid #3b82f6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 0 4px 4px 0;
        }
        .footer {
            background: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #3b82f6;
            text-decoration: none;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 4px;
            }
            .header, .content, .footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔑 Reset Password</h1>
            <p style="margin: 10px 0 0 0; opacity: 0.9;">SIPEL - Sistem Informasi Pelatihan</p>
        </div>
        
        <div class="content">
            <h2>Halo, {{ $name }}!</h2>
            
            <p>Kami menerima permintaan untuk mengatur ulang password akun Anda di SIPEL. Jika Anda yang membuat permintaan ini, silakan klik tombol di bawah untuk melanjutkan:</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $resetUrl }}" class="button">Reset Password Saya</a>
            </div>
            
            <div class="info-box">
                <p><strong>⏰ Penting:</strong></p>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Link ini akan kadaluarsa dalam <strong>60 menit</strong></li>
                    <li>Jika Anda tidak meminta reset password, abaikan email ini</li>
                    <li>Password Anda akan tetap aman sampai Anda membuat yang baru</li>
                </ul>
            </div>
            
            <p>Jika tombol di atas tidak berfungsi, Anda dapat menyalin dan menempel URL berikut ke browser Anda:</p>
            <p style="word-break: break-all; color: #3b82f6; font-family: monospace; font-size: 14px; background: #f3f4f6; padding: 10px; border-radius: 4px;">
                {{ $resetUrl }}
            </p>
            
            <p style="margin-top: 30px;">Jika Anda mengalami kesulitan atau memiliki pertanyaan, jangan ragu untuk menghubungi tim support kami.</p>
            
            <p>Salam hangat,<br>
            <strong>Tim SIPEL</strong></p>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem SIPEL.</p>
            <p>Jangan membalas email ini karena kotak masuk tidak dipantau.</p>
            <p style="margin-top: 10px;">
                © {{ date('Y') }} SIPEL. Semua hak dilindungi.
            </p>
        </div>
    </div>
</body>
</html>