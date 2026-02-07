<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriTransaksi extends Model
{
    use HasFactory;

    protected $table = 'kategori_transaksi';
    protected $primaryKey = 'id_kategori';
    
    // Schema doesn't have timestamps for this table
    public $timestamps = false;

    protected $fillable = [
        'nama_kategori'
    ];
}
