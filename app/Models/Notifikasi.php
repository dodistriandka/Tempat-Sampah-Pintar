<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $fillable = [
        'tempat_sampah_id',
        'pesan',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'notifikasi_user')
                    ->withPivot('dibaca')
                    ->withTimestamps();
    }
}
