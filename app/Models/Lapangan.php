<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    protected $table = "lapangans";
    protected $fillable = [
        'namaLapangan',
        'jenisLapangan',
        'harga',
        'status',
        'deskripsi',
        'gambar',
    ];
}
