<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-AIW - {{ $donation->donation_code }}</title>
    <style>
        @page { 
            size: A4 portrait;
            margin: 15mm; 
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #1a1a1a; 
            line-height: 1.4; 
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        .container {
            width: 100%;
            height: 100%;
            box-sizing: border-box;
        }
        .border-double {
            border: 4px double #004000;
            padding: 25px;
            height: 235mm; /* Reduced to ensure it fits on one page with DomPDF rendering */
            position: relative;
            box-sizing: border-box;
        }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #004000; padding-bottom: 20px; }
        .logo { font-size: 22px; font-weight: 900; color: #004000; letter-spacing: 3px; margin-bottom: 5px; }
        .title { font-size: 20px; font-weight: 900; color: #004000; margin-bottom: 2px; }
        .subtitle { font-size: 11px; font-weight: bold; color: #666; text-transform: uppercase; letter-spacing: 1px; }
        
        .section { margin-top: 25px; }
        .section-label { 
            font-size: 10px; 
            font-weight: 900; 
            color: #004000; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            margin-bottom: 12px;
            border-left: 4px solid #004000;
            padding-left: 10px;
        }
        
        .data-row { margin-bottom: 10px; font-size: 13px; }
        .label { display: inline-block; width: 140px; color: #666; font-weight: bold; }
        .value { display: inline-block; color: #1a1a1a; font-weight: 900; }

        .ikrar-box {
            background-color: #f9fdf9;
            border: 1px solid #eef7ee;
            padding: 20px;
            border-radius: 12px;
            font-size: 13px;
            line-height: 1.6;
            color: #1a1a1a;
            margin-top: 15px;
            font-weight: 500;
            text-align: justify;
        }

        .footer-signatures {
            margin-top: 60px;
            width: 100%;
        }
        .signature-table {
            width: 100%;
            border: none;
        }
        .signature-cell {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding-top: 20px;
        }
        .signature-title {
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 50px; /* Reduced from 60px */
        }
        .signature-name {
            font-size: 14px;
            font-weight: 900;
            color: #004000;
            text-decoration: underline;
        }
        .signature-position {
            font-size: 11px;
            color: #666;
            margin-top: 4px;
        }
        .signature-img {
            height: 70px;
            margin: -50px auto -10px;
            display: block;
        }
        .document-note {
            position: absolute;
            bottom: 30px; /* Moved up from 40px */
            left: 40px;
            right: 40px;
            text-align: center;
            font-size: 9px;
            color: #999;
            font-style: italic;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="border-double">
            <div class="header">
                <div class="logo">WAKAF NUSA</div>
                <div class="title">AKTA IKRAR WAKAF ELEKTRONIK (e-AIW)</div>
                <div class="subtitle">Nomor: {{ $donation->donation_code }}/AIW/{{ date('Y') }}</div>
            </div>

            <div class="section">
                <div class="section-label">Identitas Wakif</div>
                <div class="data-row"><span class="label">Nama Lengkap</span><span class="value">: {{ $donation->aiw_data['wakif_name'] }}</span></div>
                <div class="data-row"><span class="label">NIK / Paspor</span><span class="value">: {{ $donation->aiw_data['wakif_nik'] ?? '-' }}</span></div>
                <div class="data-row"><span class="label">Alamat</span><span class="value">: {{ $donation->aiw_data['wakif_address'] }}</span></div>
            </div>

            <div class="section">
                <div class="section-label">Detail Wakaf</div>
                <div class="data-row"><span class="label">Jenis Wakaf</span><span class="value">: Wakaf Uang / Melalui Uang</span></div>
                <div class="data-row"><span class="label">Nilai Nominal</span><span class="value">: Rp {{ number_format($donation->amount, 0, ',', '.') }}</span></div>
                <div class="data-row"><span class="label">Peruntukan</span><span class="value">: {{ $donation->aiw_data['program_title'] }}</span></div>
            </div>

            <div class="section">
                <div class="section-label">Ikrar Wakaf</div>
                <div class="ikrar-box">
                    "Dengan ini saya menyatakan ikrar wakaf kepada Lembaga Wakaf dan Pertanahan Nahdlatul Ulama (LWPNU) untuk dikelola sesuai ketentuan syariah dan peraturan yang berlaku, demi kemaslahatan umat."
                </div>
            </div>

            <div style="margin-top: 30px; text-align: right; font-size: 13px; font-weight: bold; color: #1a1a1a;">
                Jakarta, {{ $donation->paid_at?->translatedFormat('d F Y') }}
            </div>

            <div class="footer-signatures">
                <table class="signature-table">
                    <tr>
                        <td class="signature-cell">
                            <div class="signature-title">WAKIF</div>
                            <div style="height: 60px; position: relative;">
                                <div style="position: absolute; bottom: 5px; width: 100%; font-size: 10px; color: #999;">ttd</div>
                            </div>
                            <div class="signature-name">{{ $donation->aiw_data['wakif_name'] }}</div>
                        </td>
                        <td class="signature-cell">
                            <div class="signature-title">NAZHIR NU DIWAKILI OLEH</div>
                            <div style="height: 60px; position: relative;">
                                @if(isset($settings['chairman_signature']) && $settings['chairman_signature'])
                                    <img src="{{ public_path('storage/' . $settings['chairman_signature']) }}" style="height: 60px; width: auto; margin: 0 auto; display: block;">
                                @endif
                            </div>
                            <div class="signature-name">{{ $settings['chairman_name'] ?? 'Drs. H. Ahmad Muzakki, M.Ag' }}</div>
                            <div class="signature-position">{{ $settings['chairman_title'] ?? 'Ketua LWP PBNU' }}</div>
                        </td>
                    </tr>
                </table>
            </div>
            
            <div class="document-note">
                <table style="width: 100%; border-top: 1px solid #eee; padding-top: 10px; margin-top: 5px;">
                    <tr>
                        <td style="width: 60px; vertical-align: middle;">
                            <img src="data:image/svg+xml;base64,{{ $qrcode }}" style="width: 55px; height: 55px;">
                        </td>
                        <td style="vertical-align: middle; padding-left: 10px; text-align: left;">
                            <div style="font-size: 8px; color: #999; font-style: italic; line-height: 1.3;">
                                Dokumen ini adalah Akta Ikrar Wakaf Elektronik (e-AIW) yang sah sesuai UU No. 41 Tahun 2004.<br>
                                Keaslian dokumen dapat diverifikasi melalui pemindaian kode QR di samping<br>
                                atau mengunjungi sistem verifikasi resmi WAKAF NUSA.
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
