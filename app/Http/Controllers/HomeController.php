<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Produto;

class HomeController extends Controller
{
    
    public function index()
    {
        //o model de produto verifica se elss estão ativos e elews são listados na view
       $registros = Produto::where([
           'ativo' => 'S'
           ])->get();
       
        return view('home.index', compact('registros'));
    }

    //busca os produtos de maneira detalhada, retornando  a primeira ocorrencia
    public function produto($id = null)
    {
        if(!empty($id)){
                $registro = Produto::where([
                    'id' => $id,
                    'ativo' => 'S' 
                ])->first();

            if(!empty($registro)){
                return view('home.produto', compact('registro'));
            }
        }
        return redirect()->route('index');
    }

    }
