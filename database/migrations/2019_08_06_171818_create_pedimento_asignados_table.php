<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedimentoAsignadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedimentos_asignados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_pedimento')->unsigned();
            $table->integer('id_expediente')->unsigned();
            $table->timestamps();
            $table->foreign('id_pedimento')->references('id')->on('pedimentos');
            $table->foreign('id_expediente')->references('id')->on('expedientes');
            $table->index('id','idx_pedimAsignados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pedimentos_asignados');
    }
}
