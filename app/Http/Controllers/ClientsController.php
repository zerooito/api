<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

use App\Clients;

class ClientsController extends Controller
{

    public function getLastClientsRegisters(Request $request, JWTAuth $JWTAuth)
    {
        $user = $JWTAuth->parseToken()->authenticate();

    	$limit = empty($request->input('limit')) ? 10 : $request->input('limit');

        $lastClientesRegisters = Clients::getLastClientesRegisters(
        	$user->id,
        	$limit
        );

        return Response(
        	json_encode($lastClientesRegisters),
        	empty($lastClientesRegisters) ? 204 : 200
        );
    }

}

