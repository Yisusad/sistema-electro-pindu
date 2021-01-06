<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCajaConceptosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caja_conceptos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tipo_movimientos')->unsigned();
            $table->foreign('tipo_movimientos')->references('id')->on('caja_tipo_movimientos');
            $table->string('descripcion', 256);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caja_conceptos');
    }
}
