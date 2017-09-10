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

    public static function getLoadOrderByPeriod($userId)
    {
        $query = "SELECT SUM(valor) as total, data_venda
                  FROM vendas 
                  WHERE id_usuario = ? 
                  GROUP BY data_venda 
                  ORDER BY data_venda DESC
                  LIMIT 30";

        $orders = app('db')->select($query, [$userId]);

        return $orders;
    }

}
