<?php

namespace App;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;

class Variations extends Model
{

    /**
     * The name table rewrite
     *
     * @var array
     */
    protected $table = 'variations';

    protected $fillable = [
        'name', 'produto_id', 'user_id', 'sku',
        'price', 'stock', 'created_at', 'updated_at'
    ];

    public function createVariations($variations, $productId, $userId)
    {
        foreach ($variations as $variation) {
            $variation['produto_id'] = $productId;
            $variation['user_id'] = $userId;
            $variation['created_at'] = date('Y-m-d H:i:s');
            $variation['updated_at'] = date('Y-m-d H:i:s');

            try {
                $find = Variations::where('produto_id', $productId)
                                  ->where('sku', $variation['sku'])
                                  ->where('user_id', $userId)
                                  ->get(['id'])
                                  ->toArray();

                if (!empty($find)) {
                    Variations::where('produto_id', $productId)
                              ->where('sku', $variation['sku'])
                              ->where('user_id', $userId)
                              ->update($variation);

                    continue;
                }

                Variations::create($variation);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }

        return true;
    }

    public static function getBySkuAndUserId($sku, $userId)
    {
        return Variations::where('sku', $sku)->where('user_id', $userId)->get();
    }

    public static function updateById($data, $id)
    {
        return Variations::where('id', $id)->update($data);
    }

    public function getVariationsByProductId($productId)
    {
        return Variations::where('produto_id', $productId)->get();
    }

    public static function deleteAllVariationsThisProductIdAndUserId($productId, $userId)
    {
        return Variations::where('produto_id', $productId)->where('user_id', $userId)->delete();
    }

}
