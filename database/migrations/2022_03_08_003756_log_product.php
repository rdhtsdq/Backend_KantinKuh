<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LogProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_product',function (Blueprint $table){
            $table->id("log_id");
            $table->string("nama");
            $table->string("nama_baru");
            $table->string("harga");
            $table->string("harga_baru");
            $table->string("gambar");
            $table->string("gambar_baru");
            $table->time("waktu");
        }
    );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_produc');
    }
}
