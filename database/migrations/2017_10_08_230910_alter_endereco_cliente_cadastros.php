<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEnderecoClienteCadastros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('endereco_cliente_cadastros', function (Blueprint $table) {
            $table->string('tipo')->default('pagador');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('endereco_cliente_cadastros', function (Blueprint $table) {
            //
        });
    }
}
