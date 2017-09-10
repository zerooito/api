<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

use App\Orders;

use App\Helpers\Format;

class OrdersController extends Controller
{

    public function getTotalOrders(Request $request, JWTAuth $JWTAuth)
    {
    	$user = $JWTAuth->parseToken()->authenticate();
    	
        $ordersTotalValue = Orders::getCountValorOrders($user->id);

        return Response(['total' => $ordersTotalValue], 200);
    }

    public function loadOrdersByPeriod(Request $request, JWTAuth $JWTAuth)
    {
    	$user = $JWTAuth->parseToken()->authenticate();

    	$ordersPeriod = Orders::getLoadOrderByPeriod($user->id);

    	return Response(Format::toChartDashboard($ordersPeriod), 200);
    }
    
}