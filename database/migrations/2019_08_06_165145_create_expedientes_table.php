<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateExpedientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expedientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('expediente');
            $table->string('nombre');
            $table->string('descripcion');
            $table->string('agente_aduanal');
            $table->integer('aduana_id')->nullable()->index();
            $table->integer('empresa_id')->unsigned();
            $table->string('status');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->timestamps();

            $table->index('expediente');
            $table->unique(['expediente','empresa_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('expedientes');
    }
}
