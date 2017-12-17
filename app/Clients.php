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
        
        if (isset($client['payer_info'])) {
            Andresses::registerAddress('pagador', $userId, $client['payer_info'], $clientId);
        }

        if (isset($client['receiver_info'])) {
            Andresses::registerAddress('entrega', $userId, $client['receiver_info'], $clientId);
        }

        return $clientId;
    }

    public function getInfoBasicClientById($clientId)
    {
        return Clients::where('id', $clientId)
                      ->get([
                        'nome1 as firstname', 'nome2 as lastname', 'email'
                      ])->toArray();
    }

}
