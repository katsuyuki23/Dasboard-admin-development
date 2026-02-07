<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanKeuanganExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $data;
    protected $startDate;
    protected $endDate;
    protected $totalMasuk;
    protected $totalKeluar;

    public function __construct($data, $startDate = null, $endDate = null)
    {
        $this->data = $data;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->totalMasuk = $data->where('jenis_transaksi', 'MASUK')->sum('nominal');
        $this->totalKeluar = $data->where('jenis_transaksi', 'KELUAR')->sum('nominal');
    }

    public function collection()
    {
        // Add summary rows at the end
        $collection = $this->data;
        
        // Add empty row
        $collection->push((object)[
            'tanggal' => null,
            'kas' => (object)['nama_kas' => ''],
            'kategori' => (object)['nama_kategori' => ''],
            'jenis_transaksi' => '',
            'nominal' => null,
            'keterangan' => ''
        ]);
        
        // Add total row
        $collection->push((object)[
            'tanggal' => null,
            'kas' => (object)['nama_kas' => ''],
            'kategori' => (object)['nama_kategori' => 'TOTAL'],
            'jenis_transaksi' => '',
            'nominal' => null,
            'keterangan' => ''
        ]);
        
        return $collection;
    }

    public function headings(): array
    {
        return [
            ['LAPORAN KEUANGAN PANTI ASUHAN ASSHOLIHIN'],
            ['Periode: ' . ($this->startDate ? date('d/m/Y', strtotime($this->startDate)) : '') . ' s/d ' . ($this->endDate ? date('d/m/Y', strtotime($this->endDate)) : '')],
            [],
            [
                'Tanggal',
                'Kas',
                'Kategori',
                'Jenis',
                'Pemasukan (Rp)',
                'Pengeluaran (Rp)',
                'Keterangan',
            ]
        ];
    }

    public function map($transaksi): array
    {
        // Check if this is the total row
        if ($transaksi->kategori && $transaksi->kategori->nama_kategori === 'TOTAL') {
            return [
                '',
                '',
                'TOTAL',
                '',
                $this->totalMasuk,
                $this->totalKeluar,
                'Surplus/Defisit: Rp ' . number_format($this->totalMasuk - $this->totalKeluar, 0, ',', '.')
            ];
        }
        
        // Check if this is empty row
        if (!$transaksi->tanggal) {
            return ['', '', '', '', '', '', ''];
        }
        
        return [
            $transaksi->tanggal->format('d/m/Y'),
            $transaksi->kas->nama_kas ?? '-',
            ucwords(strtolower(str_replace('_', ' ', $transaksi->kategori->nama_kategori ?? '-'))),
            $transaksi->jenis_transaksi,
            $transaksi->jenis_transaksi == 'MASUK' ? $transaksi->nominal : '',
            $transaksi->jenis_transaksi == 'KELUAR' ? $transaksi->nominal : '',
            $transaksi->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        // Title styling
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Period styling
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Header styling
        $sheet->getStyle('A4:G4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0D7C66']
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);
        
        // Data rows styling
        for ($row = 5; $row < $lastRow - 1; $row++) {
            $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ]
            ]);
        }
        
        // Total row styling
        $sheet->getStyle("A{$lastRow}:G{$lastRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFF2CC']
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);
        
        // Number formatting
        $sheet->getStyle("E5:F{$lastRow}")->getNumberFormat()
            ->setFormatCode('#,##0');
        
        // Column widths
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(30);
        
        return [];
    }

    public function title(): string
    {
        return 'Laporan Keuangan';
    }
}
