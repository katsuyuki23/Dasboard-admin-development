<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriTransaksi;

class KategoriTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = ['DONASI', 'OPERASIONAL', 'PENDIDIKAN', 'KESEHATAN'];

        foreach ($kategori as $nama) {
            KategoriTransaksi::create(['nama_kategori' => $nama]);
        }
    }
}
