<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory,HasUuid;
    protected $primaryKey = "id_pengeluaran";
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ["id_pengeluaran","jumlah","keterangan"];

}
