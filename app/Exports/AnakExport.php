<?php

namespace App\Exports;

use App\Models\Anak;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class AnakExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle, WithColumnWidths
{
    protected $anak;

    public function __construct($anak = null)
    {
        $this->anak = $anak;
    }

    public function collection()
    {
        if ($this->anak) {
            return collect([$this->anak]);
        }
        
        return Anak::with(['riwayatKesehatan', 'riwayatPendidikan'])
            ->orderBy('nomor_induk', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            ['DATA ANAK ASUH - PANTI ASUHAN ASSHOLIHIN'],
            ['Dicetak: ' . date('d F Y, H:i') . ' WIB'],
            [],
            [
                // Data Identitas
                'No. Induk',
                'NIK',
                'NISN',
                'Nama Lengkap',
                'Jenis Kelamin',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Umur',
                
                // Data Keluarga
                'Nama Ayah',
                'Nama Ibu',
                'Nama Wali',
                'Hubungan Wali',
                'No. HP Wali',
                'No. HP Keluarga',
                
                // Alamat
                'Alamat Wali',
                'Alamat Asal',
                
                // Status & Tanggal
                'Status',
                'Alasan Masuk',
                'Tanggal Masuk',
                'Tanggal Keluar',
                
                // Riwayat Kesehatan
                'Riwayat Kesehatan',
                
                // Riwayat Pendidikan
                'Pendidikan Terakhir',
            ]
        ];
    }

    public function map($anak): array
    {
        // Calculate age
        $umur = '';
        if ($anak->tanggal_lahir) {
            $umur = $anak->tanggal_lahir->age . ' tahun';
        }
        
        // Get health history
        $kesehatan = $anak->riwayatKesehatan->map(function($item) {
            return $item->kategori . ': ' . $item->keterangan;
        })->implode('; ');
        
        // Get education history
        $pendidikan = $anak->riwayatPendidikan->map(function($item) {
            return $item->jenjang . ' - ' . $item->nama_sekolah;
        })->implode('; ');
        
        return [
            // Data Identitas
            $anak->nomor_induk ?? '-',
            $anak->nik ?? '-',
            $anak->nisn ?? '-',
            $anak->nama ?? '-',
            $anak->jenis_kelamin ?? '-',
            $anak->tempat_lahir ?? '-',
            $anak->tanggal_lahir ? $anak->tanggal_lahir->format('d/m/Y') : '-',
            $umur ?: '-',
            
            // Data Keluarga
            $anak->nama_ayah ?? '-',
            $anak->nama_ibu ?? '-',
            $anak->nama_wali ?? '-',
            $anak->hubungan_wali ?? '-',
            $anak->no_hp_wali ?? '-',
            $anak->no_hp_keluarga ?? '-',
            
            // Alamat
            $anak->alamat_wali ?? '-',
            $anak->alamat_asal ?? '-',
            
            // Status & Tanggal
            $anak->status_anak ?? '-',
            $anak->alasan_masuk ?? '-',
            $anak->tanggal_masuk ? $anak->tanggal_masuk->format('d/m/Y') : '-',
            $anak->tanggal_keluar ? $anak->tanggal_keluar->format('d/m/Y') : '-',
            
            // Riwayat
            $kesehatan ?: '-',
            $pendidikan ?: '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        
        // Title styling
        $sheet->mergeCells('A1:V1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('0D7C66');
        $sheet->getStyle('A1')->getFont()->getColor()->setRGB('FFFFFF');
        
        // Date styling
        $sheet->mergeCells('A2:V2');
        $sheet->getStyle('A2')->getFont()->setBold(true);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Header styling
        $sheet->getStyle('A4:V4')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '064635']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);
        
        // Set row height for header
        $sheet->getRowDimension(4)->setRowHeight(30);
        
        // Data rows styling
        for ($row = 5; $row <= $lastRow; $row++) {
            $sheet->getStyle("A{$row}:V{$row}")->applyFromArray([
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_TOP,
                    'wrapText' => true
                ]
            ]);
            
            // Alternate row colors
            if ($row % 2 == 0) {
                $sheet->getStyle("A{$row}:V{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F8F9FA');
            }
        }
        
        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,  // No. Induk
            'B' => 16,  // NIK
            'C' => 14,  // NISN
            'D' => 25,  // Nama
            'E' => 10,  // JK
            'F' => 15,  // Tempat Lahir
            'G' => 12,  // Tgl Lahir
            'H' => 10,  // Umur
            'I' => 20,  // Nama Ayah
            'J' => 20,  // Nama Ibu
            'K' => 20,  // Nama Wali
            'L' => 15,  // Hubungan Wali
            'M' => 14,  // No HP Wali
            'N' => 14,  // No HP Keluarga
            'O' => 30,  // Alamat Wali
            'P' => 30,  // Alamat Asal
            'Q' => 10,  // Status
            'R' => 25,  // Alasan Masuk
            'S' => 12,  // Tgl Masuk
            'T' => 12,  // Tgl Keluar
            'U' => 35,  // Riwayat Kesehatan
            'V' => 35,  // Pendidikan
        ];
    }

    public function title(): string
    {
        return 'Data Anak Asuh';
    }
}
