<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donatur extends Model
{
    use HasFactory;

    protected $table = 'donatur';
    protected $primaryKey = 'id_donatur';
    
    protected $fillable = [
        'user_id', 'nama', 'email', 'no_hp', 'alamat', 'deskripsi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function donasi()
    {
        return $this->hasMany(Donasi::class, 'id_donatur');
    }
}
