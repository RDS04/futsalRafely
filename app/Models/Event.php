<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    protected $fillable = [
     'judul',
     'tanggal_mulai',
     'tanggal_selesai',
     'status',
     'deskripsi',
     'gambar',
    ];
}
