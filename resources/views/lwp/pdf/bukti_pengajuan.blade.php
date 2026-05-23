<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Pengajuan Pendaftaran Aset Wakaf</title>
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
            color: #0d5c3a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-left: 20px;
        }
        td {
            vertical-align: top;
            padding: 5px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .section-header {
            font-weight: bold;
            color: #0d5c3a;
            padding-top: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #0d5c3a;
            font-size: 13px;
        }
        .col-label {
            width: 35%;
            color: #475569;
        }
        .col-colon {
            width: 2%;
        }
        .col-value {
            width: 63%;
            font-weight: bold;
        }
        .mt-40 {
            margin-top: 40px;
            margin-left: 20px;
        }
        .mb-5 {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header text-center">
        @php
            $logoPath = public_path('images/logo-nu.svg');
            $logoData = '';
            if (file_exists($logoPath)) {
                $logoData = 'data:image/svg+xml;base64,' . base64_encode(file_get_contents($logoPath));
            }
        @endphp
        @if($logoData)
            <img src="{{ $logoData }}" alt="Logo NU">
        @endif
        <div class="title">LEMBAGA WAKAF DAN PERTANAHAN</div>
        <div class="title">PENGURUS BESAR NAHDLATUL ULAMA</div>
        <div class="subtitle">BUKTI PENDAFTARAN PENGAJUAN WAKAF</div>
    </div>

    <table>
        <tr>
            <td colspan="3" class="section-header">INFORMASI PENGAJUAN</td>
        </tr>
        <tr>
            <td class="col-label">Nomor Pengajuan</td>
            <td class="col-colon">:</td>
            <td class="col-value" style="font-size: 14px; color: #0d5c3a;">{{ $item->nomor_pengajuan }}</td>
        </tr>
        <tr>
            <td class="col-label">Tanggal Pengajuan</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ $item->created_at->format('d-m-Y H:i:s') }} WIB</td>
        </tr>
        <tr>
            <td class="col-label">Status Pengajuan</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ $item->status }}</td>
        </tr>

        <tr>
            <td colspan="3" class="section-header">LOKASI ASET WAKAF</td>
        </tr>
        <tr>
            <td class="col-label">Alamat Lengkap</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->alamat) }}</td>
        </tr>
        <tr>
            <td class="col-label">Kelurahan / Desa</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->kelurahan) }}</td>
        </tr>
        <tr>
            <td class="col-label">Kecamatan</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->kecamatan) }}</td>
        </tr>
        <tr>
            <td class="col-label">Kabupaten / Kota</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->kabupaten) }}</td>
        </tr>
        <tr>
            <td class="col-label">Provinsi</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->provinsi) }}</td>
        </tr>

        <tr>
            <td colspan="3" class="section-header">IDENTITAS ASET</td>
        </tr>
        <tr>
            <td class="col-label">Nama Aset (jika ada)</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->nama_aset ?? '-') }}</td>
        </tr>
        <tr>
            <td class="col-label">Luas Tanah</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ number_format($item->luas, 2, ',', '.') }} m²</td>
        </tr>
        <tr>
            <td class="col-label">Nama Wakif</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->nama_wakif) }}</td>
        </tr>
        <tr>
            <td class="col-label">Jenis Penggunaan</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->penggunaan) }}</td>
        </tr>

        <tr>
            <td colspan="3" class="section-header">LEGALITAS & IKRAR</td>
        </tr>
        <tr>
            <td class="col-label">Sudah Ikrar Wakaf?</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ $item->sudah_ikrar ? 'SUDAH' : 'BELUM' }}</td>
        </tr>
        @if($item->sudah_ikrar)
        <tr>
            <td class="col-label">Nomor Ikrar (AIW)</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->nomor_ikrar) }}</td>
        </tr>
        <tr>
            <td class="col-label">Tanggal Ikrar</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ $item->tanggal_ikrar ? $item->tanggal_ikrar->format('d-m-Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Nama Nazhir</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->nama_nazhir) }}</td>
        </tr>
        @endif

        <tr>
            <td class="col-label">Sudah Bersertifikat?</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ $item->sudah_sertifikat ? 'SUDAH' : 'BELUM' }}</td>
        </tr>
        @if($item->sudah_sertifikat)
        <tr>
            <td class="col-label">Nomor Sertifikat / NIB</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->nomor_sertifikat) }}</td>
        </tr>
        <tr>
            <td class="col-label">Tanggal Sertifikat</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ $item->tanggal_sertifikat ? $item->tanggal_sertifikat->format('d-m-Y') : '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">Atas Nama Sertifikat</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->atas_nama_sertifikat) }}</td>
        </tr>
        <tr>
            <td class="col-label">Jenis Sertifikat</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ strtoupper($item->jenis_sertifikat) }}</td>
        </tr>
        @endif

        <tr>
            <td colspan="3" class="section-header">KONTAK & TINDAKAN</td>
        </tr>
        <tr>
            <td class="col-label">Tindakan yang Diinginkan</td>
            <td class="col-colon">:</td>
            <td class="col-value">
                @if($item->tindakan_aset === 'WAKAF_NU') DIWAKAFKAN KE NU
                @elseif($item->tindakan_aset === 'ASET_NU') DIDAFTARKAN MENJADI ASET NU
                @elseif($item->tindakan_aset === 'GANTI_NAZHIR_NU') GANTI NAZHIR KE NU
                @else {{ strtoupper($item->tindakan_aset) }}
                @endif
            </td>
        </tr>
        <tr>
            <td class="col-label">Catatan Keterangan</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ $item->keterangan ?? '-' }}</td>
        </tr>
        <tr>
            <td class="col-label">No. HP / WhatsApp</td>
            <td class="col-colon">:</td>
            <td class="col-value">{{ $item->hp_wa }}</td>
        </tr>
    </table>

    <div class="mt-40">
        <div class="mb-5">Dicetak pada tanggal: {{ date('d-m-Y H:i:s') }} WIB</div>
        <div>Dokumen ini merupakan tanda bukti pengajuan resmi pendaftaran aset wakaf melalui platform Wakaf Nusa oleh Lembaga Wakaf dan Pertanahan NU (LWPNU). Pengurus LWP setempat akan segera melakukan tindak lanjut dan verifikasi lapangan.</div>
    </div>
</body>
</html>
