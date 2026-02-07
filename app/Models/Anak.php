<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anak extends Model
{
    use HasFactory;

    protected $table = 'anak';
    protected $primaryKey = 'id_anak';
    
    protected $fillable = [
        'nomor_induk', 'nik', 'nisn', 'nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
        'status_anak', 'nama_ayah', 'nama_ibu', 'nama_wali', 'hubungan_wali',
        'no_hp_wali', 'no_hp_keluarga', 'alamat_wali', 'alamat_asal', 'alasan_masuk',
        'tanggal_masuk', 'tanggal_keluar', 'foto'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'tanggal_keluar' => 'date',
    ];

    public function riwayatKesehatan()
    {
        return $this->hasMany(RiwayatKesehatan::class, 'id_anak');
    }

    public function riwayatPendidikan()
    {
        return $this->hasMany(RiwayatPendidikan::class, 'id_anak');
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenAnak::class, 'id_anak', 'id_anak');
    }

    public function fotoKegiatan()
    {
        return $this->hasMany(FotoKegiatan::class, 'id_anak', 'id_anak');
    }
}
