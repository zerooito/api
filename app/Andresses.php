<?php

namespace App;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;

class Andresses extends Model
{

    /**
     * The name table rewrite
     *
     * @var array
     */
    protected $table = 'endereco_cliente_cadastros';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_cliente', 'cep', 'endereco', 'numero',
        'bairro', 'cidade', 'uf', 'id_usuario'
    ];

    public static function getAndressesByClientAndUserId()
    {
        return [];
    }

    public static function getAndressPayerByClientAndUserId($clientId, $userId, $type)
    {
        return Andresses::where('id_cliente', $clientId)->where('id_usuario', $userId)->where('tipo', $type)->get()->toArray();
    }

    public static function getAndressInfoByClientAndUserId($clientId, $userId, $type)
    {
        return Andresses::where('id_cliente', $clientId)->where('id_usuario', $userId)->where('tipo', $type)->get()->toArray();
    }

    public static function registerAddress($type = 'pagador', $userId, $data, $clientId)
    {
        $query = "
            INSERT INTO `endereco_cliente_cadastros` 
            (cep, endereco, numero, bairro, cidade, uf, id_usuario, id_cliente, ativo, tipo, created_at, updated_at) 
            values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        return app('db')->insert($query, [
            $data['zipcode'],
            $data['street'],
            $data['number'],
            $data['neighborhood'],
            $data['city'],
            $data['state'],
            $userId, 
            $clientId,
            1,
            $type,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
    }

}
