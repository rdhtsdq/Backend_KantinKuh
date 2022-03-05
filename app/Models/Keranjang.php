<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory,HasUuid;
    protected $primaryKey = 'kode_keranjang';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['kode_keranjang','jumlah'];

    public function product()
    {
        return $this->belongsToMany(Product::class,'keranjang_products','kode_keranjang','kode')->withPivot("jumlah","keterangan");
    }
    public function transaction()
    {
        return $this->belongsTo(Transaction::class,"kode_transaksi","kode_transaksi");
    }

}
