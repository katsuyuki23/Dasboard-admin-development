<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoKegiatan extends Model
{
    use HasFactory;

    protected $table = 'foto_kegiatan';
    protected $primaryKey = 'id_foto';

    protected $fillable = [
        'id_anak',
        'judul',
        'deskripsi',
        'path_foto',
        'tanggal_kegiatan'
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date'
    ];

    public function anak()
    {
        return $this->belongsTo(Anak::class, 'id_anak', 'id_anak');
    }
}
