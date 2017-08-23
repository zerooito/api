<?php

namespace App;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{

    /**
     * The name table rewrite
     *
     * @var array
     */
    protected $table = 'vendas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'valor', 'data_venda',
    ];

    public static function getCountValorOrders($userId)
    {
        $valor = app('db')->select("SELECT SUM(valor) as total FROM vendas WHERE id_usuario = ?", [$userId]);

        return 'R$ ' . number_format($valor[0]->total, 2, ',', '.');
    }

}
