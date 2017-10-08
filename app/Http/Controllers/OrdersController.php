<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

use App\Orders;
use App\Clients;
use App\Products;

use App\Helpers\Format;

class OrdersController extends Controller
{

    public function getTotalOrders(Request $request, JWTAuth $JWTAuth)
    {
    	$user = $JWTAuth->parseToken()->authenticate();
    	
        $ordersTotalValue = Orders::getCountValorOrders($user->id);

        return response()->json(['total' => $ordersTotalValue], 200);
    }

    public function loadOrdersByPeriod(Request $request, JWTAuth $JWTAuth)
    {
    	$user = $JWTAuth->parseToken()->authenticate();

    	$ordersPeriod = Orders::getLoadOrderByPeriod($user->id);

        return response()->json(Format::toChartDashboard($ordersPeriod), 200);
    }

    public function get(Request $request, JWTAuth $JWTAuth)
    {
        $user = $JWTAuth->parseToken()->authenticate();
        
        $per_page = $request->input('per_page') ? $request->input('per_page') : 15;

        if ($per_page > 100) {
            return response()->json(['error' => 'Query parameter "per_page" not can major 100'], 500);
        }

        $response['total'] = Orders::getAllRegisterOrders($user->id);
        $response['per_page'] = $per_page;
        $response['current_page'] = $request->input('page') ? $request->input('page') : 1;
        $response['last_page'] = round($response['total'] / $per_page);
        $response['next_page_url'] = env('APP_URL') . 'v1/orders?page=' . ($request->input('page') > 1 ? $request->input('page') + 1: 2);
        $response['prev_page_url'] = env('APP_URL') . 'v1/orders?page=' . ($request->input('page') > 1 ? $request->input('page') - 1: 1);
        $response['from'] = $request->input('per_page') ? ($request->input('per_page') + 1) : 16;
        $response['to'] = $request->input('per_page') ? ($request->input('per_page') * 2) : 30;
        $response['data'] = Orders::getOrdersUserId(
            $user->id, $request->input('per_page'), $request->input('page')
        );

        return response()->json($response, 200);
    }

    public function create(Request $request, JWTAuth $JWTAuth)
    {
        $user = $JWTAuth->parseToken()->authenticate();
        
        $ClientsModel = new Clients;
        $ProductsModel = new Products;
        $OrderModel = new Orders;

        if (!$ClientsModel->registerInfoByOrder($request->input('client'), $user->id))
            return response()->json(['error' => 'An error ocurred when register client'], 403);

        return response()->json([], 201);
    }
    
}