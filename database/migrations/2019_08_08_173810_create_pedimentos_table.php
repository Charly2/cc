<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePedimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedimentos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pedimento',50);
            $table->integer('aduanaDespacho');
            $table->string('impExpNombre',500);
            $table->integer('tipoOperacion');
            $table->integer('empresa_id')->unsigned();
            $table->integer('expediente_id')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->string('archivoM');
            $table->string('archivoPDF')->nullable();
            $table->mediumText('json');
            $table->timestamps();
    
            $table->index('pedimento','idx_pedimento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pedimentos');
    }
}
