<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('type');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert(
            array(
                'name'     => 'Super Usuario',
                'username'    => '99999999',
                'email'    => 'super@gmail.com',
                'password' => bcrypt('123123123'),
                'type' => 9,
            )
        );

        DB::table('users')->insert(
            array(
                'name'     => 'Usuario genérico',
                'username'    => '88888888',
                'email'    => 'generico@gmail.com',
                'password' => bcrypt('123123123'),
                'type' => 0,
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
