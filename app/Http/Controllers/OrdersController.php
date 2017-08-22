<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

use Illuminate\Http\Request;

class OrdersController extends Controller
{

    public function getTotalOrders(Request $request)
    {
        return Response(['total' => 34293], 200);
    }

}

