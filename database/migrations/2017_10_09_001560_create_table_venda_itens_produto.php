<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableVendaItensProduto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venda_itens_produto', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('produto_id')->nullable()->unsigned();
            $table->foreign('produto_id')->references('id')->on('produtos');

            $table->integer('venda_id')->nullable()->unsigned();
            $table->foreign('venda_id')->references('id')->on('vendas');

            $table->timestamps();

            $table->index('produto_id');
            $table->index('venda_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venda_itens_produto');
    }
}
