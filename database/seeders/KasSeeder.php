<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kas;

class KasSeeder extends Seeder
{
    public function run(): void
    {
        Kas::updateOrCreate(
            ['id_kas' => 1],
            [
                'nama_kas' => 'Kas Panti',
                'saldo' => 0
            ]
        );
    }
}
