<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat {{ $certificate->user->name }}</title>
    <style>
        @font-face {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 400;
            src: url('https://fonts.gstatic.com/s/poppins/v20/pxiEyp8kv8JHgFVrJJfecg.woff2') format('woff2');
        }
        @font-face {
            font-family: 'Poppins';
            font-style: normal;
            font-weight: 700;
            src: url('https://fonts.gstatic.com/s/poppins/v20/pxiByp8kv8JHgFVrLCz7V1s.woff2') format('woff2');
        }

        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html, body {
            font-family: 'Poppins', 'Helvetica', 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        /* --- PERBAIKAN UTAMA --- */
        .page {
            width: 29.7cm;
            /* Hapus height: 21cm; Biarkan tinggi konten yang menentukan */
            padding: 40px 80px; /* Kurangi sedikit padding atas-bawah */
            position: relative;
            overflow: hidden;
            page-break-after: always; /* Tetap buat halaman baru setelah elemen ini */
            page-break-inside: avoid !important; /* PERINTAH KUNCI: Jangan pernah memotong elemen ini di tengah halaman! */
        }

        .page:last-of-type {
            page-break-after: avoid;
        }

        /* --- Gaya Halaman Depan --- */
        .certificate-wrapper .corner-top-left {
            position: absolute; top: 0; left: 0; width: 0; height: 0;
            border-top: 180px solid #2563eb;
            border-right: 180px solid transparent; z-index: 1;
        }
        .certificate-wrapper .corner-bottom-right {
            position: absolute; bottom: 0; right: 0; width: 0; height: 0;
            border-bottom: 180px solid #2563eb;
            border-left: 180px solid transparent; z-index: 1;
        }
        .header {
            display: table; width: 100%; margin-bottom: 20px;
            position: relative; z-index: 2;
        }
        .logo-container { display: table-cell; vertical-align: middle; width: 80px; }
        .logo { width: 70px; height: auto; }
        .header-text { display: table-cell; vertical-align: middle; padding-left: 15px; }
        .header-text p { margin: 0; line-height: 1.4; }
        .lpk-name { font-size: 16px; font-weight: bold; color: #1e3a8a; text-transform: uppercase; }
        .lpk-name-small { font-size: 14px; color: #555; }
        .content { text-align: center; position: relative; z-index: 2; padding-top: 15px; } /* padding dikurangi */
        h1 {
            font-size: 48px; /* font dikecilkan */
            margin: 15px 0 10px;
            color: #1e3a8a; font-weight: 700;
        }
        .certificate-number {
            display: inline-block; background-color: #3b82f6; color: #fff;
            padding: 8px 25px; border-radius: 20px; font-size: 14px;
            margin-bottom: 25px; /* margin dikurangi */
            font-weight: bold;
        }
        .intro-text { font-size: 16px; margin-bottom: 10px; }
        .participant-name {
            font-size: 56px; /* font dikecilkan */
            font-weight: 700;
            color: #1e3a8a; margin: 15px 0 5px;
        }
        .name-underline {
            width: 150px; height: 5px; background-color: #60a5fa;
            margin: 0 auto 25px; /* margin dikurangi */
            border-radius: 5px;
        }
        .telah-mengikuti {
            font-size: 18px; text-transform: uppercase;
            letter-spacing: 2px; margin-bottom: 15px; font-weight: bold;
        }
        .program-details {
            font-size: 16px; font-weight: bold; margin-bottom: 25px;
            text-transform: uppercase; line-height: 1.6;
        }
        .status {
            font-size: 36px; font-weight: 700; letter-spacing: 2px;
            color: #16a34a; margin-top: 10px;
        }

        /* --- Gaya Halaman Belakang (Lampiran) --- */
        .competency-header {
            text-align: center; border-bottom: 2px solid #1e3a8a;
            padding-bottom: 10px; margin-bottom: 30px;
        }
        .competency-title {
            text-align: center; font-size: 22px; font-weight: bold;
            color: #1e3a8a; margin-bottom: 20px; text-transform: uppercase;
        }
        .competency-table {
            width: 100%; border-collapse: collapse; margin-top: 15px;
        }
        .competency-table th, .competency-table td {
            border: 1px solid #ccc; padding: 10px;
            font-size: 12px; text-align: left;
        }
        .competency-table th {
            background-color: #e0e7ff; font-weight: bold; color: #1e3a8a;
        }
        .competency-table td.no { text-align: center; width: 40px; }
        
        .competency-footer {
            position: absolute;
            bottom: 40px; /* Disesuaikan dengan padding .page */
            left: 80px;
            right: 80px;
            width: auto;
            display: table;
        }
        .issue-date-section {
            display: table-cell; text-align: left;
            font-size: 14px; vertical-align: bottom;
        }
        .signature-section {
            display: table-cell; text-align: center;
            font-size: 14px; width: 300px; vertical-align: bottom;
        }
    </style>
</head>
<body>
    {{-- Halaman 1: Depan Sertifikat --}}
    <div class="page certificate-wrapper">
        <div class="corner-top-left"></div>
        <div class="corner-bottom-right"></div>
        
        <div class="header">
            <div class="logo-container">
                {{-- REKOMENDASI: Gunakan gambar Base64 agar selalu termuat tanpa koneksi internet --}}
                {{-- <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgA..." class="logo"> --}}
            </div>
            <div class="header-text">
                <p class="lpk-name-small">LPK Sekolahan Indonesia Digital</p>
                <p class="lpk-name">LPK SEKOLAHAN INDONESIA DIGITAL</p>
            </div>
        </div>

        <div class="content">
            <h1>Sertifikat</h1>
            <div class="certificate-number">
                NOMOR: {{ $certificate->certificate_number }}
            </div>

            <p class="intro-text">Lembaga Pelatihan Kerja Sekolah Indonesia Digital, menyatakan bahwa:</p>
            
            <h2 class="participant-name">{{ $certificate->user->name }}</h2>
            <div class="name-underline"></div>

            <p class="telah-mengikuti">Telah Mengikuti</p>
            
            <p class="program-details">
                PELATIHAN {{ $certificate->program->name }} 
                <br>
                PADA TANGGAL {{ strtoupper(\Carbon\Carbon::parse($certificate->program->start_date)->isoFormat('D MMMM Y')) }} S.D. {{ strtoupper(\Carbon\Carbon::parse($certificate->program->end_date)->isoFormat('D MMMM Y')) }}
            </p>

            <p class="intro-text">dan dinyatakan:</p>
            <p class="status">{{ strtoupper($certificate->status) }}</p>
        </div>
    </div>

    {{-- Halaman 2: Belakang (Unit Kompetensi) --}}
    @if($certificate->competencyUnits->isNotEmpty())
        <div class="page competency-wrapper">
            <div class="competency-header">
                <p style="font-size: 18px; font-weight: bold; color: #1e3a8a; margin:0; text-transform:uppercase;">Lampiran Sertifikat Kompetensi</p>
                <p style="margin: 5px 0 0; font-size: 14px;">Nomor: {{ $certificate->certificate_number }}</p>
            </div>
    
            <div class="competency-main-content">
                <h3 class="competency-title">Daftar Unit Kompetensi</h3>
                <p style="text-align:center; margin-top:-15px; font-size: 14px;">Diberikan kepada: <strong>{{ $certificate->user->name }}</strong></p>
    
                <table class="competency-table">
                    <thead>
                        <tr>
                            <th class="no">No</th>
                            <th style="width: 150px;">Kode Unit</th>
                            <th>Judul Unit Kompetensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($certificate->competencyUnits as $index => $unit)
                        <tr>
                            <td class="no">{{ $index + 1 }}</td>
                            <td>{{ $unit->unit_code }}</td>
                            <td>{{ $unit->title }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="competency-footer">
                <div class="issue-date-section">
                    <p>Diterbitkan di: Bandung <br> Pada tanggal: {{ \Carbon\Carbon::parse($certificate->issue_date)->isoFormat('D MMMM Y') }}</p>
                </div>
                <div class="signature-section">
                    <p>Direktur LPK<br>Sekolahan Indonesia Digital</p>
                    <div style="height: 80px;"></div> {{-- Memberi ruang untuk TTD --}}
                    <p style="border-bottom: 1px solid #333; padding-bottom: 5px; margin:0;"><strong>(Nama Direktur)</strong></p>
                    <p style="margin-top: 5px;">NIP/ID: ...........................</p>
                </div>
            </div>
        </div>
    @endif
</body>
</html>

