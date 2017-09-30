<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Clients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');

            $table->string('nome1');
            $table->string('nome2');
            $table->date('data_de_nascimento')->nullable();
            $table->string('documento1')->nullable();
            $table->string('documento2')->nullable();
            $table->string('email')->nullable();
            $table->string('senha')->nullable();
            $table->tinyInteger('ativo')->nullable();

            $table->string('id_usuario')->unsigned();
            $table->foreign('id_usuario')->references('id')->on('usuarios');

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
        Schema::dropIfExists('clientes');
    }
}
