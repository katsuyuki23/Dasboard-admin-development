<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kas extends Model
{
    use HasFactory;

    protected $table = 'kas';
    protected $primaryKey = 'id_kas';
    
    protected $fillable = [
        'nama_kas', 'saldo'
    ];

    // Saldo should be protected from mass assignment if possible, but for simplicity here strictly following internal rules.
    // However, logic dictates we shouldn't edit it directly.
    
    public function transaksiKas()
    {
        return $this->hasMany(TransaksiKas::class, 'id_kas');
    }
}
