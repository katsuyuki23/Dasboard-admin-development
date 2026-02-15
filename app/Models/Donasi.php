<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donasi extends Model
{
    use HasFactory;

    protected $table = 'donasi';
    protected $primaryKey = 'id_donasi';
    
    protected $fillable = [
        'type_donasi', 'id_donatur', 'sumber_non_donatur',
        'bulan', 'tahun', 'jumlah', 'tanggal_catat',
        'snap_token', 'status_pembayaran',
        // DOKU Columns
        'order_id', 'payment_url', 'payment_method', 'payment_channel', 
        'va_number', 'qr_string', 'expired_at', 'payment_info'
    ];

    protected $casts = [
        'tanggal_catat' => 'date',
        'payment_info' => 'array',
    ];

    public function donatur()
    {
        return $this->belongsTo(Donatur::class, 'id_donatur');
    }

    public function transaksiKas()
    {
        return $this->hasOne(TransaksiKas::class, 'id_donasi');
    }
}
