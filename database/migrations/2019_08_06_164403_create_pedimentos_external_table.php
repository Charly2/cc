<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedimentosExternalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedimentos_external', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre_emisor');
            $table->string('rfc_emisor');
            $table->string('nombre_receptor');
            $table->string('rfc_receptor');
            $table->string('uuid');
            $table->string('pedimento');
            $table->integer('digitos');
            $table->string('pedimento_ct');

            $table->index('rfc_emisor');
            $table->index('rfc_receptor');
            $table->index('pedimento_ct');
            $table->index('pedimento');
            $table->index('uuid');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pedimentos_external');
    }
}
