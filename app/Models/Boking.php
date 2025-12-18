<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boking extends Model
{
    protected $table = 'bokings';

    protected $fillable = [
        'nama',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'lapangan',
        'catatan',
    ];
    
}
