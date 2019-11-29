<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExcepcionalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('excepcionales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('producto_id')->unsigned();
            $table->bigInteger('oficina_id')->unsigned();
            $table->bigInteger('cantidad')->unsigned()->nullable();
            $table->date('inicio');
            $table->date('final');
            $table->timestamps();

            $table->foreign('producto_id')->references('id')->on('productos');
            $table->foreign('oficina_id')->references('id')->on('oficinas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('excepcionales');
    }
}
