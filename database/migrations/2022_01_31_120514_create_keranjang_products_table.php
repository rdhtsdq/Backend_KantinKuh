<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateKeranjangProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keranjang_products', function (Blueprint $table) {
            $table->foreignUuid('kode_keranjang')->references('kode_keranjang')->on('keranjangs')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('kode')->references('kode')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->integer("jumlah");
            $table->text('keterangan')->nullable(true);
            $table->timestamps();
        });

        DB::statement("
            create view laporan as
            select transactions.kode_keranjang as id,
            keranjang_products.jumlah,
            products.nama,products.harga,
            (keranjang_products.jumlah * products.harga) as total,
            NOW() as waktu
            from keranjang_products inner join products on keranjang_products.kode = products.kode
            inner JOIN keranjangs on keranjang_products.kode_keranjang = keranjangs.kode_keranjang
            inner JOIN transactions on keranjangs.kode_keranjang = transactions.kode_keranjang
            where transactions.status = 'lunas'
            ;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW laporan");
        Schema::dropIfExists('keranjang_products');
    }
}
