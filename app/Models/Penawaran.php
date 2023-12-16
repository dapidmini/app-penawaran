<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penawaran extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function detail_penawaran()
    {
        return $this->belongsToMany(DetailPenawaran::class, 'detail_penawaran_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
