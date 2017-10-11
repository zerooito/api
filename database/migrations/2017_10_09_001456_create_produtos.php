<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdutos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('id_alias')->default(null);
            $table->decimal('preco', 10, 4)->default(null);
            $table->decimal('preco_promocional', 10, 0);
            $table->integer('estoque')->default(null);
            $table->string('nome', 100)->default(null);
            $table->decimal('peso_bruto', 10, 4)->default(null);
            $table->decimal('peso_liquido', 10, 4)->default(null);
            $table->string('id_usuario', 100)->default(null);
            $table->boolean('ativo')->default(null);
            $table->string('descricao', 255)->default(null);
            $table->string('imagem', 255)->default(null);
            $table->decimal('custo', 10, 4)->default(null);
            $table->integer('categoria_id')->unsigned()->default(null);
            $table->string('sku', 40)->default(null);
            $table->integer('quantidade_minima')->default(null);
            $table->boolean('destaque');
            
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
        Schema::dropIfExists('produtos');
    }
}
