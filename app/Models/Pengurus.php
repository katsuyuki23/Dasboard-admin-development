<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengurus extends Model
{
    use HasFactory;

    protected $table = 'pengurus';
    protected $primaryKey = 'id_pengurus';

    protected $fillable = [
        'nik',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'mulai_bekerja',
        'jabatan',
        'status_kepegawaian',
        'pendidikan_terakhir',
        'pelatihan'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'mulai_bekerja' => 'date'
    ];
}
