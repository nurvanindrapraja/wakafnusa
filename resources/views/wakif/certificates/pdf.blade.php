<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sertifikat Wakaf — {{ $certificate->certificate_number }}</title>
    <style>
        @page { 
            size: A4 landscape;
            margin: 20mm; /* Increased to 20mm for all sides to ensure balance */
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #1a1a1a; 
            margin: 0; 
            padding: 0; 
            line-height: 1.2; 
            background-color: #fff;
        }
        .container {
            width: 100%;
            height: 140mm; /* Reduced to ensure strict 1-page with 20mm margins */
            box-sizing: border-box;
            border: 6px double #004000;
            padding: 20px;
            position: relative;
        }
        .header { 
            text-align: center; 
            margin-bottom: 15px; 
            border-bottom: 2px solid #004000; 
            padding-bottom: 10px; 
        }
        .logo-text { font-size: 20px; font-weight: 900; color: #004000; letter-spacing: 3px; }
        .subtitle { font-size: 10px; font-weight: bold; color: #666; text-transform: uppercase; letter-spacing: 1px; }
        
        .cert-title { text-align: center; margin: 10px 0; }
        .cert-title h2 { font-size: 22px; color: #004000; margin: 0; text-transform: uppercase; letter-spacing: 3px; font-weight: 900; }
        .cert-title .number { font-size: 12px; color: #b48c00; margin-top: 5px; font-weight: 900; }
        
        .main-content {
            margin-top: 15px;
            width: 100%;
        }

        .data-table { width: 100%; font-size: 13px; border-collapse: collapse; margin-bottom: 10px; }
        .data-table td { padding: 3px 0; vertical-align: top; }
        .data-table td.label { width: 160px; font-weight: bold; color: #666; text-transform: uppercase; font-size: 9px; letter-spacing: 1px; }
        .data-table td.value { font-weight: 900; color: #1a1a1a; }

        .legal-text {
            font-size: 13px;
            text-align: center;
            color: #1a1a1a;
            line-height: 1.4;
            margin: 10px auto;
            padding: 10px 20px;
            background-color: #f9fdf9;
            border-radius: 12px;
            border: 1px solid #eef7ee;
            max-width: 90%;
            font-weight: 500;
        }

        .footer-section {
            margin-top: 10px;
            width: 100%;
            display: table;
        }
        .footer-col {
            display: table-cell;
            vertical-align: bottom;
        }
        .signature-block {
            text-align: right;
            padding-right: 40px;
        }
        .issued-date {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        .signature-title {
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 35px;
        }
        .signature-name {
            font-size: 14px;
            font-weight: 900;
            color: #004000;
            text-decoration: underline;
        }
        .signature-position {
            font-size: 10px;
            color: #666;
            margin-top: 2px;
        }
        .signature-img {
            height: 50px;
            margin: -35px 0 -5px auto;
            display: block;
        }

        .qr-block {
            text-align: left;
            padding-left: 10px;
        }
        .qr-img {
            width: 70px;
            height: 70px;
            margin-bottom: 3px;
        }
        .qr-text {
            font-size: 8px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .footer-note {
            position: absolute;
            bottom: 15px;
            left: 40px;
            right: 40px;
            text-align: center;
            font-size: 8px;
            color: #999;
            font-style: italic;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo-text" style="font-size: 22px; line-height: 1.2;">LEMBAGA WAKAF DAN PERTANAHAN</div>
            <div class="logo-text" style="font-size: 22px; line-height: 1.2; margin-bottom: 5px;">NAHDLATUL ULAMA (LWPNU)</div>
            <div class="subtitle" style="font-size: 18px; margin-top: 5px; color: #333;">PENGURUS BESAR NAHDLATUL ULAMA</div>
        </div>

        <div class="cert-title">
            <h2>Sertifikat Wakaf Uang / Melalui Uang</h2>
            <div class="number">NOMOR: {{ $certificate->certificate_number }}</div>
        </div>

        <div class="main-content">
            <table class="data-table">
                <tr>
                    <td class="label">Nama Wakif</td>
                    <td class="value">: {{ $certificate->donation->donor_display_name }}</td>
                </tr>
                <tr>
                    <td class="label">Nominal Wakaf</td>
                    <td class="value">: Rp {{ number_format($certificate->donation->amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="label">Program Wakaf</td>
                    <td class="value">: {{ $certificate->donation->program->title }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal Bayar</td>
                    <td class="value">: {{ $certificate->donation->paid_at?->translatedFormat('d F Y, H:i') }} WIB</td>
                </tr>
            </table>

            <div class="legal-text">
                "Telah menunaikan wakaf uang melalui platform WAKAF NUSA dan dicatat sebagai wakaf yang sah sesuai dengan ketentuan dalam Peraturan Perkumpulan Nahdlatul Ulama Nomor 14 Tahun 2023 tentang Pengelolaan dan Pengembangan Harta Benda Wakaf Nahdlatul Ulama."
            </div>
        </div>

        <div class="footer-section">
            <div class="footer-col qr-block">
                <img src="data:image/svg+xml;base64,{{ $qrcode }}" class="qr-img">
                <div class="qr-text">Scan untuk Verifikasi Keaslian</div>
            </div>
            <div class="footer-col signature-block">
                <div class="issued-date">
                    Diterbitkan di Jakarta, {{ $certificate->issued_at?->translatedFormat('d F Y') }}
                </div>
                <div class="signature-title">NAZHIR NU DIWAKILI OLEH</div>
                @if(isset($settings['chairman_signature']) && $settings['chairman_signature'])
                    <img src="{{ public_path('storage/' . $settings['chairman_signature']) }}" class="signature-img">
                @else
                    <div style="height: 60px;"></div>
                @endif
                <div class="signature-name">{{ $settings['chairman_name'] ?? 'Drs. H. Ahmad Muzakki, M.Ag' }}</div>
                <div class="signature-position">{{ $settings['chairman_title'] ?? 'Ketua LWP PBNU' }}</div>
            </div>
        </div>

        <div class="footer-note">
            Sertifikat ini diterbitkan secara elektronik oleh WAKAF NUSA dan sah secara hukum sesuai UU No. 41 Tahun 2004 tentang Wakaf.
        </div>
    </div>
</body>
</html>
