<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    protected $table = 'pesans';
    protected $primaryKey = 'id_pesan';
    
    protected $fillable = ['nama', 'email', 'pesan'];
}
