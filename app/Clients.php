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
        'nome1', 'nome2', 'email', 'senha',
        'id_usuario'
    ];

    public static function getLastClientesRegisters($userId, $limit)
    {
        $query = "SELECT id, CONCAT(nome1, ' ', nome2) as nome 
                  FROM clientes WHERE id_usuario = ? 
                  ORDER BY id DESC LIMIT ?";

        $clients = app('db')->select(
            $query, 
            [
                $userId,
                $limit
            ]
        );

        return $clients;
    }

    public static function getTotalCountRegisters($userId)
    {
        $query = "SELECT count(*) as count FROM clientes WHERE id_usuario = ?";

        $count = app('db')->select(
            $query, 
            [
                $userId
            ]
        );

        return $count[0];
    }

    public function registerInfoByOrder($client, $userId)
    {
        $query = "INSERT INTO clientes (nome1, nome2, email, id_usuario) values (?, ?, ?, ?)";
        
        $response = app('db')->insert($query, [
            $client['firstname'], $client['lastname'], $client['email'], $userId
        ]);

        $clientId = app('db')->getPdo()->lastInsertId();
        
        Andresses::registerAddress('info', $userId, $client['payer_info'], $clientId);

        Andresses::registerAddress('receiver', $userId, $client['receiver_info'], $clientId);

        return $response;
    }

}
