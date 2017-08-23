<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

use App\Orders;

class OrdersController extends Controller
{

    public function getTotalOrders(Request $request, JWTAuth $JWTAuth)
    {
    	$user = $JWTAuth->parseToken()->authenticate();
    	
        $ordersTotalValue = Orders::getCountValorOrders($user->id);

        return Response(['total' => $ordersTotalValue], 200);
    }

}

