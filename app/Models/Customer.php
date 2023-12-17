<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Customer extends Model
{
    use HasFactory, Sluggable;

    protected $guarded = ['id'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'nama'
            ]
        ];
    }

    public function penawarans()
    {
        return $this->hasMany(Penawaran::class, 'customer_id');
    }

    public function latestPenawaranByCust()
    {
        return $this->hasOne(Penawaran::class, 'customer_id')->latest('updated_at');
    }
}
