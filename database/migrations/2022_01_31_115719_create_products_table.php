<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid("kode")->primary();
            $table->string("nama");
            $table->integer("harga");
            $table->string("gambar");
            $table->enum('status',['ada','habis']);
            $table->enum('kategori',['snack','food','drink']);
            $table->timestamps();
        });

        // DB::statement("
        // DELIMITER $$
        // CREATE TRIGGER log_produk_updated
        // AFTER UPDATE 
        // ON products 
        // FOR EACH ROW
        // BEGIN
        //     INSERT INTO log_product
        //     set nama = old.nama,
        //     nama_baru = IFNULL(new.nama,old.nama),
        //     harga = old.harga,
        //     harga_baru = IFNULL(new.harga,old.harga),
        //     gambar = old.gambar,
        //     gambar_baru = IFNULL(new.gambar,old.gambar),
        //     keterangan = 'updated',
        //     waktu = NOW();
        // END$$
        // DELIMITER ;
        // ");

        // DB::statement("
        // DELIMITER $$
        // CREATE TRIGGER log_produk_deleted
        // AFTER delete
        // ON products 
        // FOR EACH ROW
        // BEGIN
        //     INSERT INTO log_product
        //     set nama = old.nama,
        //     nama_baru = old.nama,
        //     harga = old.harga,
        //     harga_baru = old.harga,
        //     gambar = old.gambar,
        //     gambar_baru = old.gambar,
        //     keterangan = 'deleted',
        //     waktu = NOW();
        // END$$
        // DELIMITER ;        
        // ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // DB::statement("DROP TRIGGER IF EXISTS `log_produk_updated`");
        // DB::statement("DROP TRIGGER IF EXISTS `log_produk_deleted`");
        Schema::dropIfExists('products');
    }
}
