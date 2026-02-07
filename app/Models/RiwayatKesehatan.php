<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKesehatan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_kesehatan';
    protected $primaryKey = 'id_kesehatan';
    
    protected $fillable = [
        'id_anak', 'kategori', 'keterangan'
    ];

    public function anak()
    {
        return $this->belongsTo(Anak::class, 'id_anak');
    }
}
