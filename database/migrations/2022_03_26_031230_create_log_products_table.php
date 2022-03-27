<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLogProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_products', function (Blueprint $table) {
            $table->id("log_id");
            $table->string("nama");
            $table->string("nama_baru");
            $table->string("harga");
            $table->string("harga_baru");
            $table->string("gambar");
            $table->string("gambar_baru");
            $table->string("keterangan");
            $table->time("waktu");
        });

        DB::statement("
        CREATE TRIGGER log_produk_updated
        AFTER UPDATE 
        ON products 
        FOR EACH ROW
        BEGIN
            INSERT INTO log_products
            set nama = old.nama,
            nama_baru = IFNULL(new.nama,old.nama),
            harga = old.harga,
            harga_baru = IFNULL(new.harga,old.harga),
            gambar = old.gambar,
            gambar_baru = IFNULL(new.gambar,old.gambar),
            keterangan = 'updated',
            waktu = NOW();
        END
        ");
        DB::statement("
        CREATE TRIGGER log_produk_deleted
        AFTER delete
        ON products 
        FOR EACH ROW
        BEGIN
            INSERT INTO log_products
            set nama = old.nama,
            nama_baru = old.nama,
            harga = old.harga,
            harga_baru = old.harga,
            gambar = old.gambar,
            gambar_baru = old.gambar,
            keterangan = 'deleted',
            waktu = NOW();
        END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP TRIGGER IF EXISTS `log_produk_deleted`");
        DB::statement("DROP TRIGGER IF EXISTS `log_produk_updated`");

        Schema::dropIfExists('log_products');
    }
}
