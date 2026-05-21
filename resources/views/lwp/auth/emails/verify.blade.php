<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email Akun Pengurus LWP</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #006400; /* Deep NU Green */
            color: #ffffff;
            text-align: center;
            padding: 30px 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .content {
            padding: 40px;
            line-height: 1.6;
        }
        .content h2 {
            margin-top: 0;
            color: #0f172a;
            font-size: 20px;
        }
        .btn-container {
            text-align: center;
            margin: 35px 0;
        }
        .btn {
            background-color: #22c55e;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 14px;
            display: inline-block;
            box-shadow: 0 10px 15px -3px rgba(34, 197, 94, 0.2);
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #15803d;
        }
        .footer {
            background-color: #f1f5f9;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
        }
        .footer a {
            color: #006400;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>WAKAF NUSA</h1>
            <div style="font-size: 12px; font-weight: bold; margin-top: 5px; opacity: 0.9;">LEMBAGA WAKAF DAN PERTANAHAN NAHDLATUL ULAMA</div>
        </div>
        <div class="content">
            <h2>Assalamualaikum Wr. Wb., {{ $nama }}</h2>
            <p>Terima kasih telah mendaftar sebagai Pengurus LWP di portal WAKAF NUSA. Untuk mengaktifkan akun Anda dan memulai pendataan tanah wakaf, silakan lakukan verifikasi alamat email Anda terlebih dahulu.</p>
            <p>Klik tombol di bawah ini untuk memverifikasi akun Anda:</p>
            
            <div class="btn-container">
                <a href="{{ $url }}" class="btn">Verifikasi Email Sekarang</a>
            </div>
            
            <p>Jika tombol di atas tidak berfungsi, Anda dapat menyalin dan menempelkan tautan berikut ke browser Anda:</p>
            <p style="word-break: break-all; font-size: 12px; background-color: #f1f5f9; padding: 12px; border-radius: 8px; color: #475569;">
                <a href="{{ $url }}" style="color: #006400; text-decoration: none;">{{ $url }}</a>
            </p>
            <p>Tautan verifikasi ini hanya berlaku untuk pendaftaran akun Anda.</p>
            <p>Wassalamu'alaikum Wr. Wb.</p>
        </div>
        <div class="footer">
            &copy; 2026 WAKAF NUSA • LWPNU Jatim<br>
            Pesan ini dikirim secara otomatis oleh sistem. Harap tidak membalas email ini.
        </div>
    </div>
</body>
</html>
