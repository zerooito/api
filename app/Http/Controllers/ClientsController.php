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

    public function getTotalCountClients(Request $request, JWTAuth $JWTAuth)
    {
        $user = $JWTAuth->parseToken()->authenticate();
        
        $countTotalClients = Clients::getTotalCountRegisters($user->id);

        return Response(
            json_encode($countTotalClients)
        );
    }

    public function registerClient(Request $request, JWTAuth $JWTAuth)
    {
        $user = $JWTAuth->parseToken()->authenticate();

        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:clientes',
            'password' => 'required'
        ]);

        $data = [
            'nome1' => $request->input('firstname'),
            'nome2' => $request->input('lastname'),
            'email' => $request->input('email'),
            'senha' => app('hash')->make($request->input('password')),
            'id_usuario' => $user->id
        ];

        $response = Clients::create($data);

        $request['id'] = $response->id;
        $request['password'] = $response->senha;

        return response()->json($request, 201);
    }

}

