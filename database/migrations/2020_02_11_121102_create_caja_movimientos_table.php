<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCajaMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caja_movimientos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idcaja_conceptos')->unsigned();
            $table->foreign('idcaja_conceptos')->references('id')->on('caja_conceptos');
            $table->integer('idusuario')->unsigned();
            $table->foreign('idusuario')->references('id')->on('users');
            $table->decimal('importe_i', 11, 2);
			$table->decimal('importe_e', 11, 2);
            $table->dateTime('fecha_hora');
            $table->string('estado', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caja_movimientos');
    }
}
