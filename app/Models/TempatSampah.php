<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempatSampah extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_tempat',
        'lokasi',
        'kapasitas_cm',
        'status',
    ];
}
