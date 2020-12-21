<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CupomDesconto extends Model
{
    //campos obrigatorios
    protected $fillable = [
        'nome',
        'localizador',
        'desconto',
        'modo_desconto',
        'limite',
        'modo_limite',
        'dthr_validade',
        'ativo'
    ];
}
