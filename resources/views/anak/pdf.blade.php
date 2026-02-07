<!DOCTYPE html>
<html>
<head>
    <title>Data Anak Asuh</title>
    <style>
        @page {
            margin: 15mm;
            size: A4 landscape;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 9px;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 3px solid #0D7C66;
            padding-bottom: 8px;
        }
        .header h2 {
            margin: 3px 0;
            color: #064635;
            font-size: 16px;
        }
        .header p {
            margin: 2px 0;
            color: #666;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #333;
            padding: 4px 3px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #0D7C66;
            color: white;
            font-weight: bold;
            text-align: center;
            font-size: 8px;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            font-size: 8px;
            text-align: right;
            color: #666;
            margin-top: 10px;
            padding-top: 5px;
            border-top: 1px solid #ddd;
        }
        .badge {
            padding: 2px 4px;
            border-radius: 2px;
            font-size: 7px;
            font-weight: bold;
        }
        .badge-aktif {
            background: #d4edda;
            color: #155724;
        }
        .badge-keluar {
            background: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>DATA ANAK ASUH</h2>
        <h3 style="margin: 3px 0;">PANTI ASUHAN ASSHOLIHIN</h3>
        <p>Candimulyo, Jombang</p>
        <p style="margin-top: 5px; font-weight: bold;">
            Dicetak: {{ date('d F Y, H:i') }} WIB
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="7%">No. Induk</th>
                <th width="10%">NIK</th>
                <th width="12%">Nama Lengkap</th>
                <th width="4%">JK</th>
                <th width="10%">Tempat, Tgl Lahir</th>
                <th width="10%">Nama Wali</th>
                <th width="8%">Hubungan</th>
                <th width="8%">No. HP</th>
                <th width="13%">Alamat Wali</th>
                <th width="6%">Status</th>
                <th width="7%">Tgl Masuk</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach($anak as $item)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="text-center">{{ $item->nomor_induk ?? '-' }}</td>
                <td>{{ $item->nik ?? '-' }}</td>
                <td><strong>{{ $item->nama ?? '-' }}</strong></td>
                <td class="text-center">{{ $item->jenis_kelamin ?? '-' }}</td>
                <td>
                    {{ $item->tempat_lahir ?? '-' }}, 
                    {{ $item->tanggal_lahir ? $item->tanggal_lahir->format('d/m/Y') : '-' }}
                    @if($item->tanggal_lahir)
                        <br><small>({{ $item->tanggal_lahir->age }} th)</small>
                    @endif
                </td>
                <td>{{ $item->nama_wali ?? '-' }}</td>
                <td>{{ $item->hubungan_wali ?? '-' }}</td>
                <td>{{ $item->no_hp_wali ?? '-' }}</td>
                <td>{{ $item->alamat_wali ?? '-' }}</td>
                <td class="text-center">
                    <span class="badge {{ $item->status_anak == 'AKTIF' ? 'badge-aktif' : 'badge-keluar' }}">
                        {{ $item->status_anak ?? '-' }}
                    </span>
                </td>
                <td class="text-center">
                    {{ $item->tanggal_masuk ? $item->tanggal_masuk->format('d/m/Y') : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 10px; font-size: 9px;">
        <strong>Total Anak Asuh: {{ $anak->count() }} orang</strong>
        <span style="margin-left: 20px;">
            Aktif: {{ $anak->where('status_anak', 'AKTIF')->count() }} | 
            Keluar: {{ $anak->where('status_anak', 'KELUAR')->count() }}
        </span>
    </div>

    <div class="footer">
        <p>Sistem Informasi Panti Asuhan Assholihin</p>
    </div>
</body>
</html>
