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

        //verifica se o id foi encontrado
        $req = Request();
        $idproduto = $req->input('id');
        
        //find recebe a primary key do produto, se n for encontrado ele retorna uma msg de erro
        $produto = Produto::find($idproduto);
        if(empty($produto->id)){
            $req->session()->flash('mensagem-falha','Produto não encontrado em nossa loja!');
            return redirect()->route('carrinho.index');
        }

        //pega a id do user logado
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

    public function remover()
    {
        $this->middleware('VerifyCsrfToken');

        //pegando as informações
        $req = Request();

        $idpedido = $req->input('pedido_id');
        $idproduto = $req->input('produto_id');
        $remove_apenas_item = (boolean)$req->input('item');//define se deleta 1 ou mais itens do carrinho
        $idusuario = Auth::id();

        //garantindo que os produtos pertecem ao pedido do user
        $idpedido = Pedido::consultaId([
            'id' => $idpedido,
            'user_id'=>$idusuario,
            'status' => 'RE' //reservado
        ]);

        if(empty($idpedido)){
            $req->session()->flash('mensagem-falha', 'Pedido não encontrado!');
            return redirect()->route('carrinho.index');
        }

        $where_produto= [
            'pedido_id' => $idpedido,
            'produto_id' =>$idproduto
        ];
    
        //retorna em forma de objeto
        $produto = PedidoProduto::where($where_produto)->orderBy('id','desc')->first();
        if(empty($produto->id)){
            $req -> session()->flash('mensagem-falha', 'Produto não encontrado no carrinho!');
            return redirect()->route('carrinho.index');
        }

        if($remove_apenas_item){
            $where_produto['id'] = $produto->id;
        }
        PedidoProduto::where($where_produto)->delete();

        $check_pedido = PedidoProduto::where([
            'pedido_id' =>$produto->pedido_id
        ])->exists();

        if(!$check_pedido){
            Pedido::where([
                'id' => $produto->pedido_id
            ])->delete();
        }

        $req->session()->flash('mensagem-sucesso', 'Produto removido do carrinho com sucesso!');

        return redirect()->route('carrinho.index');
    }
}
