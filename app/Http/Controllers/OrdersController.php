<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

use App\Orders;
use App\Clients;
use App\Products;
use App\Andresses;
use App\ItemSales;
use App\Shipments;

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

        $clientId = $ClientsModel->registerInfoByOrder($request->input('client'), $user->id);

        if (!$clientId) {
            return response()->json(['error' => 'An error ocurred when register client'], 403);
        }

        $order = [
            'valor' => $request->input('value'),
            'custo' => $request->input('cust'),
            'data_venda' => date('Y-m-d'),
            'id_usuario' => $user->id,
            'ativo' => 1,
            'cliente_id' => $clientId,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $orderId = $OrderModel->registerOrder($order);

        if (!$orderId) {
            return response()->json(['error' => 'An error ocurred when create order'], 403);
        }

        $order = $OrderModel->getOrderToAPI($orderId);

        $order['payer_info'] = Andresses::getAndressPayerByClientAndUserId($clientId, $user->id, 'pagador');
        $order['receiver_info'] = Andresses::getAndressInfoByClientAndUserId($clientId, $user->id, 'entrega');
        $order['products'] = ItemSales::saveItems($request->input('products'), $orderId, $user->id);

        return response()->json($order, 201);
    }

    public function getById(Request $request, JWTAuth $JWTAuth, Clients $clients, 
                            Products $products, Andresses $andresses, $id)
    {
        return response()->json([], 200);
    }

    public function patch(Request $request, JWTAuth $JWTAuth, $id)
    {
        $user = $JWTAuth->parseToken()->authenticate();

        $data = $request->input('shipments');

        $data['created_at'] = date('Y-m-d');
        $data['updated_at'] = date('Y-m-d');
        $data['order_id'] = $id;
        $data['user_id'] = $user->id;

        Shipments::updateShipmentIfExist($data);

        unset($data['user_id']);

        return response()->json($data, 200);
    }

}