<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Entregas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('usuarios');

            $table->integer('order_id')->nullable()->unsigned();
            $table->foreign('order_id')->references('id')->on('vendas');

            $table->string('track_url');
            $table->string('track_code');
            $table->string('nfe_key');
            $table->string('nfe_number');
            $table->string('nfe_serie');
            $table->string('company');
            $table->string('service');

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
        Schema::dropIfExists('shipments');
    }
}
