<?php

namespace App;

use Illuminate\Database\Capsule\Manager as DB;

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

    public static function getCountValorOrders()
    {
        return app('db')->select("SELECT COUNT(valor) FROM vendas WHERE usuario_id = ?", [$token]);
    }

}
