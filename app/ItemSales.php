<?php

namespace App;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;

use App\Products;

class ItemSales extends Model
{

    /**
     * The name table rewrite
     *
     * @var array
     */
    protected $table = 'venda_itens_produto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'produto_id', 'venda_id', 'quantidade'
    ];

    public static function saveItems($items, $orderId, $userId)
    {
        foreach ($items as $item) {
            $product = Products::getItemBySKUAndUserId($item['sku'], $userId);

            $query = "
                INSERT INTO `venda_itens_produto` (produto_id, venda_id, quantidade) VALUES (?, ?, ?)
            ";

            app('db')->insert($query, [$product['id'], $orderId, $item['quantity']]);

            Products::updateStock($item['sku'], $userId, 'decrease', $item['quantity']);
        }
    }

}
