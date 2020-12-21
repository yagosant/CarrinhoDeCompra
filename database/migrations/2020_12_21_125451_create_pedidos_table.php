<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id')->unsigned(); //unsigned somente inteiros e positivos
            $table->enum('status',['RE', 'PA', 'CA']); //RESERVADO, PAGO, CANCELADO
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users'); //chave estrangeira com a id da tabela users
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
