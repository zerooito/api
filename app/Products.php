<?php

namespace App;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{

    /**
     * The name table rewrite
     *
     * @var array
     */
    protected $table = 'produtos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'preco',  'estoque', 'sku', 
        'custo', 'preco_venda', 'quantidade',
        'id_usuario'
    ];

    public static function getCountRegistersProducts($userId)
    {
        $query = "SELECT COUNT(*) as count FROM produtos WHERE id_usuario = ?";

        $count = app('db')->select($query, [$userId]);

        return $count[0];
    }

    public static function getCustTotalProducts($userId)
    {
        $query = "SELECT SUM(custo) AS cust FROM produtos WHERE id_usuario = ?";

        $cust = app('db')->select($query, [$userId]);
        
        return $cust[0];
    }

    public static function updateStock($sku, $userId, $action = 'decrease', $quantity, $log = '')
    {
        if ($action == 'decrease') {
            $query = "
                UPDATE produtos SET estoque = estoque - ? WHERE sku = ? AND id_usuario = ?
            ";
        }

        if ($action == 'increase') {
            $query = "
                UPDATE produtos SET estoque = estoque - ? WHERE sku = ? AND id_usuario = ?
            ";
        }

        return app('db')->update($query, [$quantity, $sku, $userId]);
    }

    public static function getItemBySKUAndUserId($sku, $userId)
    {
        return Products::where('sku', $sku)->where('id_usuario', $userId)->first()->toArray();
    }

}
