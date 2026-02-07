<table>
    <thead>
        <tr>
            <th colspan="8" style="text-align: center; font-weight: bold; font-size: 14px;">REKAPITULASI KEUANGAN TAHUNAN {{ $data['year'] }}</th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center; font-weight: bold;">PANTI ASUHAN ASSHOLIHIN</th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center; font-weight: bold;">CANDIMULYO JOMBANG</th>
        </tr>
        <tr><th colspan="8"></th></tr>
    </thead>
    <tbody>
        <!-- PEMASUKAN PANTI -->
        <tr>
            <th colspan="8" style="background-color: #d9ead3; font-weight: bold;">PEMASUKAN PANTI</th>
        </tr>
        <tr style="background-color: #f3f3f3; font-weight: bold;">
            <th>No</th>
            <th>Bulan</th>
            <th>Donatur</th>
            <th>Non Donatur</th>
            <th>Bantuan</th>
            <th>UEP</th>
            <th>Kotak Amal</th>
            <th>Jumlah</th>
        </tr>
        @php
            $monthNames = ['JANUARI', 'FEBRUARI', 'MARET', 'APRIL', 'MEI', 'JUNI', 'JULI', 'AGUSTUS', 'SEPTEMBER', 'OKTOBER', 'NOPEMBER', 'DESEMBER'];
            $totalPemasukan = ['DONATUR_TETAP' => 0, 'NON_DONATUR' => 0, 'BANTUAN' => 0, 'PROGRAM_UEP' => 0, 'KOTAK_AMAL' => 0];
        @endphp
        @for($m = 1; $m <= 12; $m++)
            @php
                $row = $data['pemasukan'][$m];
                $jumlah = array_sum($row);
                foreach($row as $key => $val) {
                    $totalPemasukan[$key] += $val;
                }
            @endphp
            <tr>
                <td>{{ $m }}</td>
                <td>{{ $monthNames[$m-1] }}</td>
                <td style="text-align: right;">{{ number_format($row['DONATUR_TETAP'], 0, ',', '.') }}</td>
                <td style="text-align: right;">{{ number_format($row['NON_DONATUR'], 0, ',', '.') }}</td>
                <td style="text-align: right;">{{ number_format($row['BANTUAN'], 0, ',', '.') }}</td>
                <td style="text-align: right;">{{ number_format($row['PROGRAM_UEP'], 0, ',', '.') }}</td>
                <td style="text-align: right;">{{ number_format($row['KOTAK_AMAL'], 0, ',', '.') }}</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($jumlah, 0, ',', '.') }}</td>
            </tr>
        @endfor
        <tr style="background-color: #fff2cc; font-weight: bold;">
            <td colspan="2">JUMLAH</td>
            <td style="text-align: right;">{{ number_format($totalPemasukan['DONATUR_TETAP'], 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totalPemasukan['NON_DONATUR'], 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totalPemasukan['BANTUAN'], 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totalPemasukan['PROGRAM_UEP'], 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totalPemasukan['KOTAK_AMAL'], 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format(array_sum($totalPemasukan), 0, ',', '.') }}</td>
        </tr>
        <tr style="font-weight: bold;">
            <td colspan="2">Rata-rata/bulan</td>
            <td style="text-align: right;">{{ number_format($totalPemasukan['DONATUR_TETAP']/12, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totalPemasukan['NON_DONATUR']/12, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totalPemasukan['BANTUAN']/12, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totalPemasukan['PROGRAM_UEP']/12, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totalPemasukan['KOTAK_AMAL']/12, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format(array_sum($totalPemasukan)/12, 0, ',', '.') }}</td>
        </tr>

        <tr><td colspan="8"></td></tr>

        <!-- PENGELUARAN PANTI -->
        <tr>
            <th colspan="8" style="background-color: #f4cccc; font-weight: bold;">PENGELUARAN PANTI</th>
        </tr>
        <tr style="background-color: #f3f3f3; font-weight: bold;">
            <th>No</th>
            <th>Bulan</th>
            <th>Permakanan</th>
            <th>Operasional</th>
            <th>Pendidikan</th>
            <th></th>
            <th>Srn Prasarana</th>
            <th>Jumlah</th>
        </tr>
        @php
            $totalPengeluaran = ['PERMAKANAN' => 0, 'OPERASIONAL' => 0, 'PENDIDIKAN' => 0, 'SARANA_PRASARANA' => 0];
        @endphp
        @for($m = 1; $m <= 12; $m++)
            @php
                $row = $data['pengeluaran'][$m];
                $jumlah = array_sum($row);
                foreach($row as $key => $val) {
                    $totalPengeluaran[$key] += $val;
                }
            @endphp
            <tr>
                <td>{{ $m }}</td>
                <td>{{ $monthNames[$m-1] }}</td>
                <td style="text-align: right;">{{ number_format($row['PERMAKANAN'], 0, ',', '.') }}</td>
                <td style="text-align: right;">{{ number_format($row['OPERASIONAL'], 0, ',', '.') }}</td>
                <td style="text-align: right;">{{ number_format($row['PENDIDIKAN'], 0, ',', '.') }}</td>
                <td></td>
                <td style="text-align: right;">{{ number_format($row['SARANA_PRASARANA'], 0, ',', '.') }}</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($jumlah, 0, ',', '.') }}</td>
            </tr>
        @endfor
        <tr style="background-color: #fff2cc; font-weight: bold;">
            <td colspan="2">JUMLAH</td>
            <td style="text-align: right;">{{ number_format($totalPengeluaran['PERMAKANAN'], 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totalPengeluaran['OPERASIONAL'], 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totalPengeluaran['PENDIDIKAN'], 0, ',', '.') }}</td>
            <td></td>
            <td style="text-align: right;">{{ number_format($totalPengeluaran['SARANA_PRASARANA'], 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format(array_sum($totalPengeluaran), 0, ',', '.') }}</td>
        </tr>
        <tr style="font-weight: bold;">
            <td colspan="2">Rata-rata/bulan</td>
            <td style="text-align: right;">{{ number_format($totalPengeluaran['PERMAKANAN']/12, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totalPengeluaran['OPERASIONAL']/12, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totalPengeluaran['PENDIDIKAN']/12, 0, ',', '.') }}</td>
            <td>0</td>
            <td style="text-align: right;">{{ number_format($totalPengeluaran['SARANA_PRASARANA']/12, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format(array_sum($totalPengeluaran)/12, 0, ',', '.') }}</td>
        </tr>
        <tr style="font-weight: bold;">
            <td colspan="2">Rata-rata/hari</td>
            <td style="text-align: right;">{{ number_format($totalPengeluaran['PERMAKANAN']/365, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totalPengeluaran['OPERASIONAL']/365, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format($totalPengeluaran['PENDIDIKAN']/365, 0, ',', '.') }}</td>
            <td>0</td>
            <td style="text-align: right;">{{ number_format($totalPengeluaran['SARANA_PRASARANA']/365, 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format(array_sum($totalPengeluaran)/365, 0, ',', '.') }}</td>
        </tr>

        <tr><td colspan="8"></td></tr>

        <!-- REKAPITULASI KEUANGAN PANTI -->
        <tr>
            <th colspan="8" style="background-color: #cfe2f3; font-weight: bold;">REKAPITULASI KEUANGAN PANTI</th>
        </tr>
        <tr style="background-color: #f3f3f3; font-weight: bold;">
            <th>No</th>
            <th>Bulan</th>
            <th></th>
            <th>Pemasukan</th>
            <th>Pengeluaran</th>
            <th>Saldo</th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <td>1</td>
            <td>SALDO {{ $data['year'] - 1 }}</td>
            <td></td>
            <td style="text-align: right;">{{ number_format($data['saldoAwal'], 0, ',', '.') }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @for($m = 1; $m <= 12; $m++)
            <tr>
                <td>{{ $m + 1 }}</td>
                <td>{{ $monthNames[$m-1] }}</td>
                <td></td>
                <td style="text-align: right;">{{ number_format($data['rekap'][$m]['pemasukan'], 0, ',', '.') }}</td>
                <td style="text-align: right;">{{ number_format($data['rekap'][$m]['pengeluaran'], 0, ',', '.') }}</td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($data['rekap'][$m]['saldo'], 0, ',', '.') }}</td>
                <td></td>
                <td></td>
            </tr>
        @endfor
        <tr style="background-color: #fff2cc; font-weight: bold;">
            <td colspan="3">JUMLAH</td>
            <td style="text-align: right;">{{ number_format(array_sum($totalPemasukan), 0, ',', '.') }}</td>
            <td style="text-align: right;">{{ number_format(array_sum($totalPengeluaran), 0, ',', '.') }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr><td colspan="8"></td></tr>
        <tr><td colspan="8"></td></tr>

        <!-- Signature -->
        <tr>
            <td colspan="3" style="text-align: center;">Mengetahui,</td>
            <td colspan="2"></td>
            <td colspan="3" style="text-align: center;">Jombang, {{ date('d F Y') }}</td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center;">Ketua</td>
            <td colspan="2"></td>
            <td colspan="3" style="text-align: center;">Bendahara</td>
        </tr>
        <tr><td colspan="8"></td></tr>
        <tr><td colspan="8"></td></tr>
        <tr><td colspan="8"></td></tr>
        <tr>
            <td colspan="3" style="text-align: center; font-weight: bold;">Drs. H. SUYOTO, M.Si.</td>
            <td colspan="2"></td>
            <td colspan="3" style="text-align: center; font-weight: bold;">M. CHABIBULLOH</td>
        </tr>
    </tbody>
</table>
