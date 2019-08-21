<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimientosProveedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimientos_proveedores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('formaDePago');
            $table->string('emisor_rfc');
            $table->string('receptor_rfc');
            $table->string('receptor_nombre');
            $table->string('total');
            $table->string('folio');
            $table->mediumText('xml_cfdi');
            $table->mediumText('json_cfdi');
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
        Schema::dropIfExists('movimientos_proveedores');
    }
}
