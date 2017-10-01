<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Vendas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->increments('id');
            
            $table->decimal('valor', 10, 4)->default(null);
            $table->integer('id_alias')->default(null);
            $table->decimal('custo', 10, 0)->default(null);
            $table->date('data_venda')->default(null);
            $table->date('data_entrega')->default(null);
            $table->integer('id_lancamento_financeiro')->default(null);
            $table->integer('id_usuario')->default(null);
            $table->integer('id_cadastro_situacao_venda')->default(null);
            $table->boolean('ativo')->default(null);
            $table->boolean('orcamento')->default(null);
            $table->decimal('desconto', 10, 0);
            $table->integer('cliente_id')->unsigned()->default(null);
            $table->string('asaas_boleto', 100)->default(null);
            $table->string('asaas_status', 40)->default(null);
            $table->string('asaas_transaction_id', 50)->default(null);
        
            $table->index('cliente_id', 'vendas_fk_cliente_id');
        
            $table->foreign('cliente_id')
                ->references('id')->on('s');
        
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
        Schema::dropIfExists('vendas');
    }
}
