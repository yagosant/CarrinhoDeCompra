<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth; //caminho da pasta Auth
use Illuminate\Http\Request;
use App\Pedido;
use App\Produto;
use App\PedidoProduto;
use App\CupomDesconto;


class CarrinhoController extends Controller
{
    //definindo o pré requisto para acessar o controler, o user precisa estar logado
    function __construct()
    {
        //obrigado estar logado, o middleware faz a verificação se o user está logado
        $this->middleware( 'auth');
    }

    //metodo principal
    public function index()
    {
        //caminho completo definido ou poderia usar o use e App\Pedido
        $pedidos = Pedido::where([
            'status'=> 'RE',
            'user_id' => Auth::id()
        ])->get();

        return view('carrinho.index', compact('pedidos'));
    }

    public function adicionar()
    {
        //verifica se a chave fo criada
        $this->middleware('VerifyCsrfToken');

        $req = Request();
        $idproduto = $req->input('id');
        
        //find recebe a primary key do produto
        $produto = Produto::find($idproduto);
        if(empty($produto->id)){
            $req->session()->flash('mensagem-falha','Produto não encontrado em nossa loja!');
            return redirect()->route('carrinho.index');
        }

        $idusuario = Auth::id();
        
        $idpedido = Pedido::consultaId([
            'user_id' => $idusuario,
            'status'=>'RE' //reservado
        ]);

        if(empty($idpedido)){
            $pedido_novo = Pedido::create([
                'user_id' => $idusuario,
                'status'=>'RE' //reservado
            ]);

            $idpedido = $pedido_novo->id;
        }

        PedidoProduto::create([
            'pedido_id' => $idpedido,
            'produto_id'=> $idproduto,
            'valor' => $produto->valor,
            'status'=>'RE'
        ]);

        $req->session()->flash('mensagem-sucesso','Produto adicionado com sucesso!');
        return redirect()->route('carrinho.index');
    }
}
