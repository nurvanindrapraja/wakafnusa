<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #334155; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 18px; border-bottom: 3px double #006400; padding-bottom: 10px; }
        .header h1 { font-size: 17px; color: #006400; margin: 0; text-transform: uppercase; letter-spacing: 0.5px; font-weight: bold; }
        .header h2 { font-size: 12px; color: #b8860b; margin: 4px 0 0; text-transform: uppercase; font-weight: bold; letter-spacing: 0.5px; }

        .meta-box { margin-bottom: 16px; border: 1px solid #cbd5e1; border-radius: 4px; padding: 8px 12px; background-color: #f8fafc; }
        .meta-table { width: 100%; border-collapse: collapse; }
        .meta-table td { padding: 2px 4px; border: none; font-size: 10.5px; }
        .meta-label { width: 140px; font-weight: bold; color: #475569; text-transform: uppercase; font-size: 10px; }
        .meta-sep { width: 12px; text-align: center; font-weight: bold; color: #475569; }
        .meta-val { font-weight: bold; color: #0f172a; font-size: 10.5px; }

        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th { background-color: #006400; color: #ffffff; padding: 9px 8px; text-align: left; text-transform: uppercase; font-size: 9.5px; letter-spacing: 0.5px; }
        table.data-table td { padding: 7px 8px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }
        table.data-table tr:nth-child(even) { background-color: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { background-color: #f1f5f9 !important; font-weight: bold; border-top: 2px solid #006400; border-bottom: 2px solid #006400; }
        .total-row td { font-weight: bold; color: #006400; font-size: 10.5px; }
        .footer { margin-top: 25px; text-align: right; font-size: 9px; color: #64748b; font-style: italic; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Lembaga Wakaf dan Pertanahan Nahdlatul Ulama</h1>
        <h2>{{ $title }}</h2>
    </div>
    
    <div class="meta-box">
        <table class="meta-table">
            @if(!empty($selectedLokasi['provinsi']))
            <tr>
                <td class="meta-label">Provinsi</td>
                <td class="meta-sep">:</td>
                <td class="meta-val">{{ strtoupper($selectedLokasi['provinsi']['nama']) }}</td>
            </tr>
            @endif
            @if(!empty($selectedLokasi['kabupaten']))
            <tr>
                <td class="meta-label">Kabupaten / Kota</td>
                <td class="meta-sep">:</td>
                <td class="meta-val">{{ strtoupper($selectedLokasi['kabupaten']['nama']) }}</td>
            </tr>
            @endif
            @if(!empty($selectedLokasi['kecamatan']))
            <tr>
                <td class="meta-label">Kecamatan</td>
                <td class="meta-sep">:</td>
                <td class="meta-val">{{ strtoupper($selectedLokasi['kecamatan']['nama']) }}</td>
            </tr>
            @endif
            @if(!empty($selectedLokasi['kelurahan']))
            <tr>
                <td class="meta-label">Kelurahan / Desa</td>
                <td class="meta-sep">:</td>
                <td class="meta-val">{{ strtoupper($selectedLokasi['kelurahan']['nama']) }}</td>
            </tr>
            @endif
            <tr>
                <td class="meta-label">Tanggal Unduh</td>
                <td class="meta-sep">:</td>
                <td class="meta-val">{{ date('d F Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="18%">Kode Lokasi</th>
                <th width="47%">Nama Wilayah ({{ str_replace('_', '/', $levelLabel) }})</th>
                <th width="15%" class="text-center">Jumlah Aset</th>
                <th width="15%" class="text-right">Total Luas (m²)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalAset = 0; $totalLuas = 0; @endphp
            @foreach($data as $index => $row)
                @php $totalAset += $row->JML_ASET; $totalLuas += $row->JML_LUAS; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row->KODE_LOKASI }}</td>
                    <td>{{ strtoupper($row->NAMA_LOKASI) }}</td>
                    <td class="text-center">{{ number_format($row->JML_ASET, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($row->JML_LUAS, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3" class="text-right">TOTAL KESELURUHAN:</td>
                <td class="text-center">{{ number_format($totalAset, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalLuas, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara otomatis melalui Sistem Informasi Wakaf Nahdlatul Ulama (SIWAKNU)
    </div>
</body>
</html>
