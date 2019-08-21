<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coves', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_agente'); // el id del agente aduanañ
            $table->integer('id_usuario');// id usuario que cargo el cove
            $table->integer('id_expediente')->nullable();//id del expediente
            $table->integer('id_empresa')->nullable();//id del expediente
            $table->string('usr_num_cove');// numero del cove proporcionado por el Usuario
            $table->string('id_fiscal',50);
            $table->mediumText('xml');
            $table->mediumText('json_cove');
            //$table->mediumText('archivo_m');
            $table->mediumText('pdfs');
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
        Schema::drop('coves');
    }
}
