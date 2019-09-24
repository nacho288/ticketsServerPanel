<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlmaceneOficinaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('almacene_oficina', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('almacene_id')->unsigned();
            $table->bigInteger('oficina_id')->unsigned();
            $table->timestamps();

            $table->foreign('almacene_id')->references('id')->on('almacenes');
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
        Schema::dropIfExists('almacene_oficina');
    }
}
