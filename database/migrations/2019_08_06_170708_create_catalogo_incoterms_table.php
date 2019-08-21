<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogoIncotermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogoIncoterms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('identificador', 50);
            $table->string('clave', 50);
            $table->string('descripcion', 3000);
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
        Schema::drop('catalogoIncoterms');
    }
}
