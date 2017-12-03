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
        $product = Products::where('sku', $sku)->where('id_usuario', $userId)->first();

        if (!empty($product)) {
            if ($action == 'decrease') {
                $query = "
                    UPDATE produtos SET estoque = estoque - ? WHERE sku = ? AND id_usuario = ?
                ";
            }

            if ($action == 'increase') {
                $query = "
                    UPDATE produtos SET estoque = estoque + ? WHERE sku = ? AND id_usuario = ?
                ";
            }    
        }

        $variation = Variations::getBySkuAndUserId($sku, $userId);

        if (!empty($variation->toArray())) {
            if ($action == 'decrease') {
                $query = "
                    UPDATE variations SET stock = stock - ? WHERE sku = ? AND user_id = ?
                ";
            }

            if ($action == 'increase') {
                $query = "
                    UPDATE variations SET stock = stock + ? WHERE sku = ? AND user_id = ?
                ";
            }  
        }

        return app('db')->update($query, [$quantity, $sku, $userId]);
    }

    public static function getItemBySKUAndUserId($sku, $userId)
    {
        $product = Products::where('sku', $sku)->where('id_usuario', $userId)->first();

        if (!empty($product)) {
            return $product->toArray();
        }

        $variation = Variations::getBySkuAndUserId($sku, $userId);

        if (!empty($variation)) {
            return $variation->toArray()[0];
        }

        return [];
    }

    public static function updateById($data, $id)
    {
        return Products::where('id', $id)->update($data);
    }

    public function loadProductsByItemSale($items)
    {
        $response = [];

        foreach ($items as $item) {
            $product = Products::where('id', $item['produto_id'])
                                  ->get([
                                    'sku', 'preco as unit_value'
                                  ])->toArray();

            $response[] = [
                'sku' => $product[0]['sku'],
                'quantity' => $item['quantidade'],
                'unit_value' => number_format($product[0]['unit_value'], 2),
                'total' => number_format($product[0]['unit_value'] * $item['quantidade'], 2)
            ];
        }

        return $response;
    }

    public static function getAllRegisterProducts($userId)
    {
        $query = "
            SELECT count(*) as count FROM produtos
            WHERE id_usuario = ?
        ";

        $orders = app('db')->select($query, [$userId]);

        return $orders[0]->count;
    }

    public static function getProductsUserId($userId, $limit=null, $offset=null)
    {
        $query = "
            SELECT 
                a.id, a.sku, a.nome as name, a.preco as price,
                a.estoque as stock
            FROM produtos a
            WHERE a.id_usuario = ?
            ORDER BY a.id DESC
            LIMIT ?, ?
        ";
    
        $filter = [
            $userId, !empty($offset) ? $offset : 0, !empty($limit) ? $limit : 15
        ];

        $products = app('db')->select($query, $filter);

        return array_map(function($products) {
            return (array) $products;
        }, $products);
    }

    public function getProductExistBySkuAndUserId($sku, $userId)
    {
        $query = "
            SELECT 
                a.sku
            FROM produtos a
            WHERE a.id_usuario = ? AND a.sku = ?
            LIMIT 1
        ";
    
        $filter = [
            $userId, $sku
        ];

        $product = app('db')->select($query, $filter);

        return array_map(function($product) {
            return (array) $product;
        }, $product);
    }

    public function deleteProductBySkuAndUserId($sku, $userId)
    {
        $productId = Products::where('sku', $sku)->where('id_usuario', $userId)->get(['id'])->toArray();
        
        if (!empty($productId)) {
            Variations::deleteAllVariationsThisProductIdAndUserId($productId[0]['id'], $userId);

            HistoryStock::clearHistoryStockByProductIdAndUserId($productId[0]['id'], $userId);
            
            $product = app('db')->table('produtos')
                                ->where('id_usuario', $userId)
                                ->where('sku', $sku)
                                ->delete();

            return $product;
        }

        return false;
    }

}
