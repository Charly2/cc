<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatosConexionEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datos_conexion_empresas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_empresa');
            $table->string('host',100);
            $table->string('user',30);
            $table->string('password',30);
            $table->string('path',50);
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
        Schema::drop('datos_conexion_empresas');
    }
}
