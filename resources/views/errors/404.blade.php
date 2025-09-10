<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Halaman Tidak Ditemukan | {{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/Sekolahan-v2.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;
            background-color: #f8fafc; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333; 
        }

        .container {
            text-align: center;
            max-width: 500px;
            padding: 40px 20px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }

        .error-code {
            font-size: 7rem;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 10px;
            color: #2563eb; 
        }

        .error-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #1e3a8a; 
        }

        .error-message {
            font-size: 1.1rem;
            margin-bottom: 30px;
            color: #64748b;
            line-height: 1.6;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 12px 28px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .btn-primary {
            background-color: #2563eb; 
            color: white;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
        }

        .btn-primary:hover {
            background-color: #1d4ed8; 
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
        }

        .btn-secondary {
            background-color: #e2e8f0;
            color: #334155;
            border: 1px solid #cbd5e1;
        }

        .btn-secondary:hover {
            background-color: #d1d5db;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .error-code {
                font-size: 5rem;
            }
            .error-title {
                font-size: 1.75rem;
            }
            .error-message {
                font-size: 1rem;
            }
            .button-group {
                flex-direction: column;
                align-items: center;
            }
            .btn {
                width: 100%;
                max-width: 280px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">404</div>
        <h1 class="error-title">Halaman Tidak Ditemukan</h1>
        <p class="error-message">
            {{ $message ?? 'Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin telah dihapus atau URL yang Anda masukkan salah.' }}
        </p>

        <div class="button-group">
            <a href="{{ url('/') }}" class="btn btn-primary">
                Kembali ke Beranda
            </a>
            <a href="javascript:history.back()" class="btn btn-secondary">
                Halaman Sebelumnya
            </a>
        </div>
    </div>
</body>
</html>
