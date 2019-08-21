<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientosAgentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_agentes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipo', 20)->nullable();
            $table->integer('id_factura')->nullable();
            $table->float('totalPago');   //monto pagado
            $table->float('montoPagado');   //monto pagado
            $table->string('concepto')->nullable();   //monto pagado
            $table->string('uidPago')->nullable();;
            $table->integer('idExpediente');
            $table->string('fechaPago');
            $table->string('transaccionCP')->nullable();
            $table->string('polizaContable')->nullable();
            $table->string('fuente');
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
        Schema::drop('movimiento_agentes');
    }
}
