<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoProduto extends Model
{
    protected $fillable = [
        'pedido_id',
        'produto_id',
        'status',
        'valor'
    ];
    
    //cria o relacionamento com o produto
    public function produto()
    {
        //belongs to - faz o relacionamento da foregn key com a primary key
        return $this->belongsTo('App\Produto', 'produto_id','id');
    }
}
