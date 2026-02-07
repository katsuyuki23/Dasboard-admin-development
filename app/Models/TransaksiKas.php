<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiKas extends Model
{
    use HasFactory;

    protected $table = 'transaksi_kas';
    protected $primaryKey = 'id_transaksi';
    
    protected $fillable = [
        'id_kas', 'id_kategori', 'id_donasi',
        'jenis_transaksi', 'nominal', 'tanggal', 'keterangan'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function kas()
    {
        return $this->belongsTo(Kas::class, 'id_kas');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriTransaksi::class, 'id_kategori');
    }

    public function donasi()
    {
        return $this->belongsTo(Donasi::class, 'id_donasi');
    }
}
