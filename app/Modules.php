<?php

namespace App;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model;

use App\User;

class Modules extends Model
{

    /**
     * The name table rewrite
     *
     * @var array
     */
    protected $table = 'modulos';

    public static function getModulesByUser(User $user)
    {
        $query = "
            SELECT a.id, b.modulo, b.nome_modulo, b.icone FROM modulo_relaciona_usuarios a
            LEFT JOIN modulos b ON b.id = a.id_modulo
            WHERE a.id_usuario = ? AND a.ativo = 1
        ";

        $modulos = app('db')->select($query, [$user->id]);

        return $modulos;
    }

}
