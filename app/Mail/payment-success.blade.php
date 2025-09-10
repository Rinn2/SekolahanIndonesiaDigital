<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .success-icon {
            background-color: rgba(255,255,255,0.2);
            border-radius: 50%;
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-icon svg {
            width: 40px;
            height: 40px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .message {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .details-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin: 30px 0;
            border-left: 4px solid #28a745;
        }
        .details-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        .detail-label {
            color: #666;
            font-weight: 500;
        }
        .detail-value {
            color: #333;
            font-weight: 600;
        }
        .program-info {
            background-color: #ffffff;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .program-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        .program-description {
            color: #666;
            margin-bottom: 15px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .status-accepted {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .next-steps {
            background-color: #fff3cd;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            border-left: 4px solid #ffc107;
        }
        .next-steps h3 {
            color: #856404;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .next-steps ul {
            color: #856404;
            margin: 0;
            padding-left: 20px;
        }
        .next-steps li {
            margin-bottom: 8px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .contact-info {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .contact-links {
            margin-top: 15px;
        }
        .contact-links a {
            color: #667eea;
            text-decoration: none;
            margin: 0 15px;
            font-size: 14px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }
        @media (max-width: 600px) {
            .container {
                margin: 0;
                box-shadow: none;
            }
            .header, .content, .footer {
                padding: 20px;
            }
            .detail-row {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="success-icon">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h1>Pembayaran Berhasil!</h1>
            <p style="margin: 10px 0 0 0; font-size: 16px; opacity: 0.9;">
                Terima kasih atas pembayaran Anda
            </p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Halo {{ $user->name }},
            </div>

            <div class="message">
                Selamat! Pembayaran Anda untuk program <strong>{{ $program->title }}</strong> telah berhasil diproses. 
                Berikut adalah detail pembayaran dan pendaftaran Anda:
            </div>

            <!-- Payment Details -->
            <div class="details-card">
                <div class="details-title">Detail Pembayaran</div>
                
                <div class="detail-row">
                    <span class="detail-label">Order ID</span>
                    <span class="detail-value">{{ $enrollment->order_id }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Tanggal Pembayaran</span>
                    <span class="detail-value">
                        {{ $enrollment->payment_date ? $enrollment->payment_date->format('d F Y, H:i') : now()->format('d F Y, H:i') }} WIB
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Jumlah Pembayaran</span>
                    <span class="detail-value">Rp {{ number_format($program->price, 0, ',', '.') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status Pembayaran</span>
                    <span class="detail-value">
                        <span class="status-badge status-paid">Lunas</span>
                    </span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status Pendaftaran</span>
                    <span class="detail-value">
                        <span class="status-badge {{ $enrollment->status === 'diterima' ? 'status-accepted' : 'status-paid' }}">
                            {{ ucfirst($enrollment->status) }}
                        </span>
                    </span>
                </div>
            </div>

            <!-- Program Information -->
            <div class="program-info">
                <div class="program-title">{{ $program->title }}</div>
                <div class="program-description">{{ $program->description }}</div>
                
                @if($enrollment->schedule)
                <div style="margin-top: 15px;">
                    <strong>Jadwal:</strong> {{ $enrollment->schedule->name }}
                </div>
                @endif
            </div>

            <!-- Next Steps -->
            <div class="next-steps">
                <h3>Langkah Selanjutnya</h3>
                <ul>
                    @if($enrollment->status === 'diterima')
                        <li>Anda telah resmi terdaftar dalam program ini</li>
                        <li>Informasi lebih lanjut akan dikirimkan melalui email atau WhatsApp</li>
                        <li>Simpan email ini sebagai bukti pembayaran dan pendaftaran</li>
                    @else
                        <li>Pendaftaran Anda sedang diproses oleh tim kami</li>
                        <li>Anda akan menerima konfirmasi dalam 1x24 jam</li>
                        <li>Pantau status pendaftaran melalui dashboard akun Anda</li>
                    @endif
                    <li>Hubungi customer service jika ada pertanyaan</li>
                </ul>
            </div>

            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef;">
                <p style="color: #666; font-size: 14px; margin: 0;">
                    Email ini dikirim secara otomatis, mohon jangan membalas email ini. 
                    Jika Anda memiliki pertanyaan, silakan hubungi customer service kami.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="logo">SekolahID</div>
            <div class="contact-info">
                Butuh bantuan? Hubungi kami
            </div>
            <div class="contact-links">
                <a href="mailto:support@sekolahid.com">support@sekolahid.com</a>
                <a href="tel:+6281234567890">+62 812-3456-7890</a>
            </div>
            <div style="margin-top: 20px; font-size: 12px; color: #999;">
                © {{ date('Y') }} SekolahID. All rights reserved.
            </div>
        </div>
    </div>
</body>
</html>