<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

use Illuminate\Http\Request;

use App\Orders;

class OrdersController extends Controller
{

    public function getTotalOrders(Request $request)
    {
        $ordersTotalValue = Orders::getCountValorOrders($request->attributes->get('usuario_id'));

        return Response(['total' => $ordersTotalValue], 200);
    }

}

