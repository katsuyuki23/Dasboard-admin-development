<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrowthMonitoring extends Model
{
    use HasFactory;

    protected $table = 'growth_monitoring';
    protected $primaryKey = 'id_monitoring';

    protected $fillable = [
        'id_anak',
        'tanggal_ukur',
        'usia_bulan',
        'berat_badan',
        'tinggi_badan',
        'lingkar_kepala',
        'z_score_berat',
        'z_score_tinggi',
        'status_gizi',
        'rekomendasi_ai',
        'catatan'
    ];

    protected $casts = [
        'tanggal_ukur' => 'date',
        'berat_badan' => 'decimal:2',
        'tinggi_badan' => 'decimal:2',
        'lingkar_kepala' => 'decimal:2',
        'z_score_berat' => 'decimal:2',
        'z_score_tinggi' => 'decimal:2',
    ];

    public function anak()
    {
        return $this->belongsTo(Anak::class, 'id_anak');
    }
}
