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

    protected $fillable = ['kode_transaksi','kode_keranjang','nama','telepon','meja'];

    public function keranjang()
    {
        return $this->hasMany(Keranjang::class,'kode_keranjang','kode_keranjang');
    }
}
