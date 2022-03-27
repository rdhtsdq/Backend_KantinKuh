<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLogUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_users', function (Blueprint $table) {
            $table->string("username");
            $table->string("username_baru");
            $table->string("password");
            $table->string("password_baru");
            $table->string("keterangan");
            $table->time("waktu");$table->id();
            $table->timestamps();
        });

        DB::statement("
        CREATE TRIGGER log_user_updated
        AFTER UPDATE 
        ON users 
        FOR EACH ROW
        BEGIN
            INSERT INTO log_users
            set username = old.username,
            username_baru = IFNULL(new.username,old.username),
            password = old.password,
            password_baru = IFNULL(new.password,old.password),
            keterangan = 'updated',
            waktu = NOW();
        END
        ");
        DB::statement("
        CREATE TRIGGER log_user_deleted
        AFTER DELETE 
        ON users 
        FOR EACH ROW
        BEGIN
            INSERT INTO log_users
            set username = old.username,
            username_baru = old.username,
            password = old.password,
            password_baru = old.password,
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
        DB::statement("DROP TRIGGER IF EXISTS `log_user_updated`");
        DB::statement("DROP TRIGGER IF EXISTS `log_user_deleted`");
        
        Schema::dropIfExists('log_users');
    }
}
