<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{

    //campos obrigatorios para o cadastro
    protected $fillable = [
        'user_id',
        'status'
    ];
    //faz o lançamento do pedido de acordo com o produto
    public function pedido_produto()
    {
        //hasMany quando é 1 para n é informado e o retorno é feito de maneira personalizado
        return $this->hasMany('App\PedidoProduto')
        ->select( \DB::raw('produto_id, sum(desconto) as descontos, sum(valor) as valores,
    count(1) as qtd'))
        ->groupBy('produto_id')
        ->orderBy('produto_id', 'desc');
    
    }

    public function pedido_produtos_itens()
    {
        return $this->hasMany('App\PedidoProduto');
    }

    //metodo para consultar o ID
    public static function consultaId($where)
    {
        //retorna apenas o id do objeto
        $pedido = self::where($where)->first(['id']);
        return !empty($pedido->id) ? $pedido->id : null;
    }
}
