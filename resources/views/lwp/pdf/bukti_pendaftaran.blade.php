<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Pendaftaran Wakaf</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
            line-height: 1.5;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .header {
            margin-bottom: 20px;
        }
        .header img {
            width: 400px;
            display: block;
            margin: 0 auto -180px 280px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 0px;
            line-height: 1.2;
        }
        .subtitle {
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-left: 20px;
        }
        td {
            vertical-align: top;
            padding: 4px 0;
        }
        .col-label {
            width: 30%;
        }
        .col-colon {
            width: 2%;
        }
        .col-value {
            width: 68%;
        }
        .mt-40 {
            margin-top: 40px;
            margin-left: 20px;
        }
        .mb-5 {
            margin-bottom: 5px;
        }
        .qr-code {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header text-center">
        @php
            $logoPath = public_path('images/logo-nu.svg');
            $logoData = '';
            if (file_exists($logoPath)) {
                // If dompdf has issues with svg, it might just display nothing. But let's try reading it.
                $logoData = 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($logoPath));
            }
        @endphp
        <img src="{{ $logoData }}" alt="Logo NU">
        <div class="title">LEMBAGA WAKAF DAN PERTANAHAN</div>
        <div class="title">PENGURUS BESAR NAHDLATUL ULAMA</div>
        <div class="subtitle">NOMOR IDENTITAS WAKAF : {{ $item->NIA }}</div>
    </div>

    <table>
        <tr>
            <td class="col-label">Nama Aset Wakaf</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->NAMA ?? '-') }}</td>
        </tr>
        <tr>
            <td class="col-label">No AIW / Tgl AIW</td>
            <td class="col-colon">:</td>
            <td class="col-value">
                {{ strtoupper($item->NO_AIW ?? '-') }} / 
                {{ !empty($item->TGL_AIW ?? null) ? date('d-m-Y', strtotime($item->TGL_AIW)) : '-' }}
            </td>
        </tr>
        <tr>
            <td class="col-label">No Sertifikat / Tgl Sert</td>
            <td class="col-colon">:</td>
            <td class="col-value">
                {{ strtoupper($item->NO_SERT ?? '-') }} / 
                {{ !empty($item->TGL_SERT ?? null) ? date('d-m-Y', strtotime($item->TGL_SERT)) : '-' }}
            </td>
        </tr>
        <tr>
            <td class="col-label">Wakif</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->WAKIF ?? '-') }}</td>
        </tr>
        <tr>
            <td class="col-label">Nadzir</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->NADZIR ?? '-') }}</td>
        </tr>
        <tr>
            <td class="col-label">Penggunaan</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->PENGGUNAAN ?? '-') }}</td>
        </tr>
        <tr>
            <td class="col-label">Alamat</td>
            <td class="col-colon">:</td>
            <td class="col-value">
                {{ strtoupper($item->ALAMAT ?? '-') }}<br>
                @if(!empty($item->KEC ?? null)) KEL. {{ strtoupper($item->KEL ?? '') }} KEC. {{ strtoupper($item->KEC) }}<br> @endif
                @if(!empty($item->KAB ?? null)) KAB. {{ strtoupper($item->KAB) }}<br> @endif
                @if(!empty($item->PROV ?? null)) {{ strtoupper($item->PROV) }} @endif
            </td>
        </tr>
        <tr>
            <td class="col-label">Luas</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ $item->LUAS ?? 0 }} M2</td>
        </tr>
        <tr>
            <td class="col-label">Status Tanah</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->JENIS_SW ?? '-') }}</td>
        </tr>
        <tr>
            <td class="col-label">Status Produktif</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->JENIS_SP ?? '-') }}</td>
        </tr>
        <tr>
            <td class="col-label">Kondisi</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->JENIS_ST ?? '-') }}</td>
        </tr>
        <tr>
            <td class="col-label">Kriteria</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->KRITERIA ?? '-') }}</td>
        </tr>
        <tr>
            <td class="col-label">Dientri tgl</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ $item->TGINS ?? '-' }}</td>
        </tr>
    </table>

    <div class="mt-40">
        <div class="mb-5">Diunduh pada tanggal<br>{{ date('d-m-Y H:i:s') }}</div>
        <div>Dokumen ini bisa dicek keabsahannya dengan memindai QRcode berikut ini:</div>
        
        @php
            $qrUrl = route('land-waqf.verify', ['nia' => $item->NIA]);
            // We use simple-qrcode format svg and base64 encode it for dompdf since imagick is not available for png
            $qrCode = base64_encode(QrCode::format('svg')->size(120)->generate($qrUrl));
        @endphp
        <div class="qr-code">
            <img src="data:image/svg+xml;base64,{!! $qrCode !!}" alt="QR Code">
        </div>
    </div>
</body>
</html>
