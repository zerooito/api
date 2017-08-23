<?php

namespace App;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;

class Clients extends Model
{

    /**
     * The name table rewrite
     *
     * @var array
     */
    protected $table = 'clientes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome1', 'nome2'
    ];

    public static function getLastClientesRegisters($userId, $limit)
    {
        $clients = app('db')->select("SELECT id, CONCAT(nome1, ' ', nome2) as nome FROM clientes WHERE id_usuario = ? ORDER BY id DESC LIMIT ?", [$userId, $limit]);

        return $clients;
    }

}
