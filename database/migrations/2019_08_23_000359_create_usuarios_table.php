<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre_usuario')->unique();
            $table->string('nombre');
            $table->string('contrasena');
            $table->integer('tipo');
            $table->timestamps();
        });

        DB::table('usuarios')->insert(
            array(
                'nombre' => 'User',
                'nombre_usuario' => 'user',
                'contrasena' => '123',
                'tipo' => 0
            )
        );

        DB::table('usuarios')->insert(
            array(
                'nombre' => 'Admin',
                'nombre_usuario' => 'admin',
                'contrasena' => '123',
                'tipo' => 1
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
        Schema::dropIfExists('usuarios');
    }
}
