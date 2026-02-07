<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenAnak extends Model
{
    use HasFactory;

    protected $table = 'dokumen_anak';
    protected $primaryKey = 'id_dokumen';

    protected $fillable = [
        'id_anak',
        'jenis_dokumen',
        'nama_file',
        'path_file',
        'keterangan'
    ];

    public function anak()
    {
        return $this->belongsTo(Anak::class, 'id_anak', 'id_anak');
    }
}
