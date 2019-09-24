<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('codigo')->unique();
            $table->bigInteger('subcategoria_id')->unsigned()->nullable();
            $table->bigInteger('almacene_id')->unsigned();
            $table->bigInteger('minimo');
            $table->bigInteger('maximo');
            $table->bigInteger('stock');
            $table->bigInteger('alerta');
            $table->timestamps();

            $table->foreign('subcategoria_id')->references('id')->on('subcategorias');
            $table->foreign('almacene_id')->references('id')->on('almacenes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
