<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipo',20)->nullable();
            $table->integer('idTipo');

            $table->float('monto_factura')->nullable();  //monto real de la factura
            $table->float('monto_pagado')->nullable();   //monto pagado
            $table->float('monto_anterior')->nullable(); //monto inicial
            $table->string('rfc')->nullable();
            $table->integer('idExpediente');
            $table->date('fechaPago');
            $table->string('uidPago');

            $table->integer('id_agencia')->unsigned();
            $table->integer('id_empresa')->unsigned();
            $table->foreign('id_agencia')->references('id')->on('agencias');
            $table->foreign('id_empresa')->references('id')->on('empresas');

            $table->timestamps();
            $table->string('transaccionCP')->nullable();
            $table->string('polizaContable')->nullable();
            $table->integer('id_facturacargada')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('movimientos');
    }
}
