<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogoAduanasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogoaduanas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('aduana');
            $table->string('seccion');
            $table->string('denominacion',5000);
            $table->integer('compuesto');
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
        Schema::drop('catalogoaduanas');
    }
}
