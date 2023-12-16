<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenawaran extends Model
{
    use HasFactory;

    public function penawaran()
    {
        return $this->hasOne(Penawaran::class);
    }

    public function barang()
    {
        return $this->hasMany(Barang::class);
    }
}
