<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Users extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');

            $table->string('nome');
            $table->string('email');
            $table->string('senha');
            $table->tinyInteger('ativo');
            $table->string('token');
            $table->integer('estoque_minimo')->nullable();
            $table->tinyInteger('sale_without_stock')->default(0);
            $table->string('loja')->nullable();
            $table->string('cep_origem')->nullable();
            $table->text('descricao')->nullable();
            $table->string('token_pagseguro')->nullable();
            $table->string('email_pagseguro')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('password')->nullable();

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
        Schema::dropIfExists('usuarios');
    }
}
