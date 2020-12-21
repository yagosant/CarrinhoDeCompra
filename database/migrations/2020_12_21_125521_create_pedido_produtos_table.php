<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidoProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_produtos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pedidos_id')->unsigned(); //unsigned somente inteiros e positivos
            $table->integer('produto_id')->unsigned(); //unsigned somente inteiros e positivos
            $table->enum('status',['RE', 'PA', 'CA']); //RESERVADO, PAGO, CANCELADO 
            $table->decimal('valor',6,2) ->default(0); //quando nenhum valor é passado será inserido o 0
            $table->decimal('desconto',6,2) ->default(0);
            $table->integer('cupom_desconto_id')->nullable()->unsigned(); //unsigned somente inteiros e positivos, o nullable permite que mesmo sendo chave estrangeira possa ser nulo, pois nem todo produto vai ter cupom de desconto
            $table->timestamps();
            
            //chaves estrangeiras
            $table->foreign('pedido_id')->references('id')->on('pedidos');
            $table->foreign('produto_id')->references('id')->on('produtos');
            $table->foreign('cupom_desconto_id')->references('id')->on('cupom_descontos');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedido_produtos');
    }
}
