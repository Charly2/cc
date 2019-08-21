<?php


use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacturasCargadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas_cargadas', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('id_agente');
            $table->integer('id_usuario');
            $table->integer('id_expediente')->nullable();
            $table->string('formaDePago');
            $table->string('tipo_factura'); //bandera para identificar si es un comprobante o una factura del agente

            $table->string('emisor_rfc');
            $table->string('emisor_nombre');
            $table->string('receptor_rfc');
            $table->string('receptor_nombre');
            $table->float('total');
            $table->string('fecha');
            $table->string('poliza');
            $table->string('folio');
            $table->mediumText('xml_file');
            $table->mediumText('json_cfdi');
            $table->string('status_factura')->nullable();
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
        Schema::drop('facturas_cargadas');
    }
}
