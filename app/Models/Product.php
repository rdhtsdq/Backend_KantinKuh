<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory,HasUuid;
    protected $primaryKey = 'kode';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['kode','nama','harga','gambar','status','kategori'];

    public function keranjang()
    {
        return $this->belongsToMany(Keranjang::class,'keranjang_products','kode','kode_keranjang');
    }
}
