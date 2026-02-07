<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RekapTahunanExport implements FromView
{
    protected $year;
    protected $data;

    public function __construct($year, $data)
    {
        $this->year = $year;
        $this->data = $data;
    }

    public function view(): View
    {
        return view('laporan.rekap_excel', [
            'year' => $this->year,
            'data' => $this->data
        ]);
    }
}
