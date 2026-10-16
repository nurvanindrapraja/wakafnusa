<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'Daftar Aset Wakaf Tanah' }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 15mm 15mm 15mm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9.5px;
            color: #1e293b;
            line-height: 1.35;
        }
        .header {
            text-align: center;
            margin-bottom: 14px;
            border-bottom: 3px double #006400;
            padding-bottom: 8px;
        }
        .header h1 {
            font-size: 16px;
            color: #006400;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: bold;
        }
        .header h2 {
            font-size: 11px;
            color: #b8860b;
            margin: 3px 0 0;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header-source {
            font-size: 9px;
            color: #475569;
            font-style: italic;
            margin-top: 4px;
            letter-spacing: 0.3px;
        }

        .meta-box {
            margin-bottom: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 6px 10px;
            background-color: #f8fafc;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 2px 4px;
            border: none;
            font-size: 9px;
            vertical-align: top;
        }
        .meta-label {
            width: 130px;
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
        }
        .meta-sep {
            width: 10px;
            text-align: center;
            font-weight: bold;
            color: #475569;
        }
        .meta-val {
            font-weight: bold;
            color: #0f172a;
        }

        table.data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            table-layout: fixed;
            margin-top: 6px;
        }
        table.data-table th {
            background-color: #006400;
            color: #ffffff;
            padding: 5px 4px;
            text-align: left;
            text-transform: uppercase;
            font-size: 8px;
            letter-spacing: 0.3px;
            border-top: 1px solid #005000;
            border-bottom: 1px solid #005000;
            border-right: 1px solid #005000;
            vertical-align: middle;
        }
        table.data-table th:first-child {
            border-left: 1px solid #005000;
        }
        table.data-table td {
            padding: 4px 4px;
            border-bottom: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
            font-size: 8px;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        table.data-table td:first-child {
            border-left: 1px solid #cbd5e1;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            background-color: #f1f5f9 !important;
            font-weight: bold;
            border-top: 2px solid #006400;
            border-bottom: 2px solid #006400;
        }
        .total-row td {
            font-weight: bold;
            color: #006400;
            font-size: 9px;
        }
        .footer {
            margin-top: 15px;
            text-align: right;
            font-size: 8px;
            color: #64748b;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Lembaga Wakaf dan Pertanahan Nahdlatul Ulama</h1>
        <h2>{{ $title ?? 'DAFTAR ASET WAKAF TANAH NAHDLATUL ULAMA' }}</h2>
        <div class="header-source">Diunduh dari Sistem Informasi Wakaf Nahdlatul Ulama (SIWAKNU)</div>
    </div>
    
    <div class="meta-box">
        <table class="meta-table">
            <tr>
                <td width="50%">
                    <table style="width: 100%; border-collapse: collapse;">
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
                    </table>
                </td>
                <td width="50%">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td class="meta-label">Sumber Data</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-val">SIWAKNU (Sistem Informasi Wakaf NU)</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Tanggal Unduh</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-val">{{ date('d F Y H:i') }} WIB</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Total Aset Wakaf</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-val">{{ number_format(count($items), 0, ',', '.') }} Bidang Tanah</td>
                        </tr>
                        @if(!empty($filterInfo))
                        <tr>
                            <td class="meta-label">Filter Tambahan</td>
                            <td class="meta-sep">:</td>
                            <td class="meta-val">{{ $filterInfo }}</td>
                        </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="3%" class="text-center">No</th>
                <th width="19%">Alamat</th>
                <th width="10%">Kelurahan</th>
                <th width="10%">Kecamatan</th>
                <th width="12%">Wakif</th>
                <th width="12%">Nazhir</th>
                <th width="11%">Penggunaan</th>
                <th width="7%" class="text-right">Luas (m²)</th>
                <th width="8%" class="text-center">No. AIW</th>
                <th width="8%" class="text-center">No. Sertifikat</th>
            </tr>
        </thead>
        <tbody>
            @php $totalLuas = 0; @endphp
            @forelse($items as $index => $row)
                @php 
                    $luasVal = (float) ($row->LUAS ?? 0);
                    $totalLuas += $luasVal; 
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row->ALAMAT ?: '-' }}</td>
                    <td>{{ $row->KEL ?: '-' }}</td>
                    <td>{{ $row->KEC ?: '-' }}</td>
                    <td>{{ $row->WAKIF ?: '-' }}</td>
                    <td>{{ $row->NADZIR ?: '-' }}</td>
                    <td>{{ $row->PENGGUNAAN ?: '-' }}</td>
                    <td class="text-right">{{ number_format($luasVal, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $row->NO_AIW ?: '-' }}</td>
                    <td class="text-center">{{ $row->NO_SERT ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 15px; color: #64748b; font-style: italic;">
                        Tidak ada data aset wakaf yang sesuai dengan kriteria filter.
                    </td>
                </tr>
            @endforelse
            @if(count($items) > 0)
                <tr class="total-row">
                    <td colspan="7" class="text-right">TOTAL LUAS:</td>
                    <td class="text-right">{{ number_format($totalLuas, 0, ',', '.') }}</td>
                    <td colspan="2" class="text-center">-</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara otomatis melalui Sistem Informasi Wakaf Nahdlatul Ulama (SIWAKNU) pada {{ date('d-m-Y H:i:s') }}
    </div>
</body>
</html>
