<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    //campos obrigatorios
    protected $fillable = [
        'user_id',
        'status'
    ];

    public function pedido_produtos()
    {
        return $this->hasMany('App\PedidoProduto')
            ->select( \DB::raw('produto_id, sum(desconto) as descontos, sum(valor) as valores, count(1) as qtd') )
            ->groupBy('produto_id')
            ->orderBy('produto_id', 'desc');
    }

    public function pedido_produtos_itens()
    {
        //faz a validação da foreign key com a primary key
        return $this->hasMany('App\PedidoProduto');
    }

    public static function consultaId($where)
    {
        //retorna um array no objeto, porem só o is será exibido
        $pedido = self::where($where)->first(['id']);
        return !empty($pedido->id) ? $pedido->id : null;
    }
}
