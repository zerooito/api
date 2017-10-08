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

    public static function getOrdersUserId($userId, $limit=null, $offset=null)
    {
        $query = "
            SELECT 
                a.id as order_id, a.valor as value, a.custo as cust, 
                a.data_venda as date_order, a.orcamento as is_budget,
                CONCAT(b.nome1, ' ', b.nome2) as name, b.documento1 as document,
                b.data_de_nascimento as birtday, b.id as client_id
            FROM vendas a
            LEFT JOIN clientes b ON a.cliente_id = b.id
            WHERE a.id_usuario = ?
            ORDER BY a.id DESC
            LIMIT ?, ?
        ";

        $filter = [
            $userId, !empty($offset) ? $offset : 0, !empty($limit) ? $limit : 15
        ];
        
        $orders = app('db')->select($query, $filter);

        return array_map(function($orders) {
            return (array) $orders;
        }, $orders);
    }

    public static function getAllRegisterOrders($userId)
    {
        $query = "
            SELECT count(*) as count FROM vendas
            WHERE id_usuario = ?
        ";

        $orders = app('db')->select($query, [$userId]);

        return $orders[0]->count;
    }

}
