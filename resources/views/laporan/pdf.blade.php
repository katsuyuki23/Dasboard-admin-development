<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan</title>
    <style>
        @page {
            margin: 15mm;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #0D7C66;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
            color: #064635;
            font-size: 18px;
        }
        .header p {
            margin: 3px 0;
            color: #666;
        }
        .info-box {
            background: #f8f9fa;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 4px solid #0D7C66;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px 6px;
            text-align: left;
        }
        th {
            background-color: #0D7C66;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .text-end {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .success {
            color: #27AE60;
            font-weight: bold;
        }
        .danger {
            color: #E74C3C;
            font-weight: bold;
        }
        .total-row {
            background-color: #FFF2CC;
            font-weight: bold;
        }
        .summary-row {
            background-color: #E8F5E9;
            font-weight: bold;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            font-size: 9px;
            text-align: right;
            color: #666;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        .category-label {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KEUANGAN</h2>
        <h3 style="margin: 5px 0;">PANTI ASUHAN ASSHOLIHIN</h3>
        <p>Candimulyo, Jombang</p>
        <p style="margin-top: 10px; font-weight: bold;">
            Periode: {{ \Carbon\Carbon::parse($request->start_date)->format('d F Y') }} 
            s/d {{ \Carbon\Carbon::parse($request->end_date)->format('d F Y') }}
        </p>
    </div>

    <div class="info-box">
        <strong>Ringkasan:</strong> 
        Total Pemasukan: <span class="success">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</span> | 
        Total Pengeluaran: <span class="danger">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</span> | 
        Surplus/Defisit: <span style="color: {{ $totalMasuk - $totalKeluar >= 0 ? '#27AE60' : '#E74C3C' }}">
            Rp {{ number_format($totalMasuk - $totalKeluar, 0, ',', '.') }}
        </span>
    </div>

    <table>
        <thead>
            <tr>
                <th width="8%">Tanggal</th>
                <th width="12%">Kas</th>
                <th width="15%">Kategori</th>
                <th width="20%">Keterangan</th>
                <th width="8%">Jenis</th>
                <th width="15%">Pemasukan</th>
                <th width="15%">Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach($data as $row)
            <tr>
                <td class="text-center">{{ $row->tanggal->format('d/m/Y') }}</td>
                <td>{{ $row->kas->nama_kas ?? '-' }}</td>
                <td>
                    <span class="category-label">
                        {{ ucwords(strtolower(str_replace('_', ' ', $row->kategori?->nama_kategori ?? 'Tidak Berkategori'))) }}
                    </span>
                </td>
                <td>{{ $row->keterangan ?: '-' }}</td>
                <td class="text-center">
                    <span style="background: {{ $row->jenis_transaksi == 'MASUK' ? '#d4edda' : '#f8d7da' }}; 
                                 padding: 2px 6px; border-radius: 3px; font-size: 9px;">
                        {{ $row->jenis_transaksi }}
                    </span>
                </td>
                <td class="text-end">
                    @if($row->jenis_transaksi == 'MASUK')
                        <span class="success">Rp {{ number_format($row->nominal, 0, ',', '.') }}</span>
                    @else
                        -
                    @endif
                </td>
                <td class="text-end">
                    @if($row->jenis_transaksi == 'KELUAR')
                        <span class="danger">Rp {{ number_format($row->nominal, 0, ',', '.') }}</span>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
            
            <!-- Total Row -->
            <tr class="total-row">
                <td colspan="5" class="text-end"><strong>TOTAL</strong></td>
                <td class="text-end success">Rp {{ number_format($totalMasuk, 0, ',', '.') }}</td>
                <td class="text-end danger">Rp {{ number_format($totalKeluar, 0, ',', '.') }}</td>
            </tr>
            
            <!-- Summary Row -->
            <tr class="summary-row">
                <td colspan="5" class="text-end"><strong>SURPLUS / DEFISIT</strong></td>
                <td colspan="2" class="text-end" style="color: {{ $totalMasuk - $totalKeluar >= 0 ? '#27AE60' : '#E74C3C' }}">
                    <strong>Rp {{ number_format($totalMasuk - $totalKeluar, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
        <p>Sistem Informasi Panti Asuhan Assholihin</p>
    </div>
</body>
</html>
