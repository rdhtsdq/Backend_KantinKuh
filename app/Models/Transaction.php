<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory,HasUuid;
    protected $primaryKey = 'kode_transaksi';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['kode_transaksi','kode_keranjang','nama','telepon','meja','harga','status'];

    public function keranjang()
    {
        return $this->hasMany(Keranjang::class,'kode_keranjang','kode_keranjang');
    }
    public function product()
    {
        return $this->hasManyThrough(Product::class,Keranjang::class,"kode_keranjang","kode","kode","kode_keranjang");
    }
}
