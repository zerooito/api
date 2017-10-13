<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

use App\Products;

class ProductsController extends Controller
{

    public function getCountRegistersProducts(Request $request, JWTAuth $JWTAuth)
    {
    	$user = $JWTAuth->parseToken()->authenticate();
    	
        $countRegisterProducts = Products::getCountRegistersProducts($user->id);
        
        return Response(json_encode($countRegisterProducts), 200);
    }

    public function getCustTotalProducts(Request $request, JWTAuth $JWTAuth)
    {
    	$user = $JWTAuth->parseToken()->authenticate();
    	
        $custTotalProducts = Products::getCustTotalProducts($user->id);
        
        return Response(
            json_encode(
                [
                    'cust' => 'R$ ' . number_format($custTotalProducts->cust, 2, ',', '.')
                ]
            ),
            200
        );	
    }

    public function create(Request $request, JWTAuth $JWTAuth, Products $products)
    {        
        $user = $JWTAuth->parseToken()->authenticate();

        $this->validate($request, [
            'name' => 'required',
            'sku' => 'required',
            'price' => 'required',
            'stock' => 'required'
        ]);

        $data = [
            'nome' => $request->input('name'),
            'preco' => $request->input('price'),
            'estoque' => $request->input('stock'),
            'sku' => $request->input('sku'),
            'id_usuario' => $user->id
        ];

        return response()->json($products->create($data), 201);
    }

}

