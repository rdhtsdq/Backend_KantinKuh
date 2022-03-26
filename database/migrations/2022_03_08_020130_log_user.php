<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LogUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("log_user",function (Blueprint $table){
            $table->string("username");
            $table->string("username_baru");
            $table->string("password");
            $table->string("password_baru");
            $table->string("keterangan");
            $table->time("waktu");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
