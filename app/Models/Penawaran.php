<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penawaran extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detail_penawaran()
    {
        return $this->belongsToMany(DetailPenawaran::class, 'detail_penawarans', 'penawaran_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function barangs()
    {
        return $this->belongsToMany(Barang::class)
            ->withPivot(['qty', 'hargaBarang', 'hargaJualSatuan'
            , 'diskonSatuanOri', 'diskonSatuanValue'
            , 'biayaSatuanOri', 'biayaSatuanValue'])
            ->withTimestamps();
    }
}
