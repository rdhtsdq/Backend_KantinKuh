<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->foreignUuid('kode')->references('kode')->on('products')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignUuid('kode_keranjang')->references('kode_keranjang')->on('keranjangs')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keranjang_products');
    }
}
