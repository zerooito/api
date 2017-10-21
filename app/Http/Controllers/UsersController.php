<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

use Illuminate\Http\Request;
use App\User;

class UsersController extends Controller
{

	public function create(Request $request, User $user)
	{
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'phone_area' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'confirm_password' => 'required'
        ]);

        $requestArray = $request->all();

        if ($requestArray['password'] != $requestArray['confirm_password']) {
        	return response()->json(['errors' => ['Password is different of confirm']], 400);
        }

        $existUser = $user->where('email', $requestArray['email'])->get()->toArray();
        
        if (!empty($existUser)) {        	
        	return response()->json(['errors' => ['Email already exist']], 400);
        }

        $data = [
        	'nome' => $requestArray['name'],
        	'email' => $requestArray['email'],
        	'telefone' => $requestArray['phone_area'] . ' ' . $requestArray['phone'],
        	'password' => app('hash')->make($requestArray['password']),
        	'created_at' => date('Y-m-d H:i:s'),
        	'updated_at' => date('Y-m-d H:i:s')
        ];

        $user->insert($data);

        $response =  $user->where('email', $requestArray['email'])
        				  ->first()
        				  ->toArray();

        return response()->json($response, 201);
	}

}

