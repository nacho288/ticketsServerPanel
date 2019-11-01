<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('almacene_id')->unsigned();
            $table->bigInteger('oficina_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('evaluado_por')->unsigned()->nullable();
            $table->bigInteger('entregado_por')->unsigned()->nullable();
            $table->bigInteger('retirado_por')->unsigned()->nullable();
            $table->integer('estado');
            $table->text('comentario_usuario')->nullable();
            $table->text('comentario_administrador')->nullable();
            $table->date('fecha')->nullable();
            $table->bigInteger('preparacion')->nullable();
            $table->timestamps();

            $table->foreign('almacene_id')->references('id')->on('almacenes');
            $table->foreign('oficina_id')->references('id')->on('oficinas');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('evaluado_por')->references('id')->on('users');
            $table->foreign('entregado_por')->references('id')->on('users');
            $table->foreign('retirado_por')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
