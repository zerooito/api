<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAndresses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('andresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cep');
            $table->string('endereco');
            $table->string('numero');
            $table->string('bairro');
            $table->string('cidade');
            $table->string('uf');
            $table->tinyInteger('ativo');

            $table->integer('id_cliente')->nullable()->unsigned();
            $table->foreign('id_cliente')->references('id')->on('clientes');

            $table->integer('id_usuario')->nullable()->unsigned();
            $table->foreign('id_usuario')->references('id')->on('usuarios');

            $table->timestamps();

            $table->index('uf');
            $table->index('cidade');
            $table->index('cep');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('andresses');
    }
}
