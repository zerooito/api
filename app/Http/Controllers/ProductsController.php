<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

use App\Products;
use App\Variations;

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

    public function create(Request $request, JWTAuth $JWTAuth, 
                           Products $products, Variations $variations)
    {        
        $user = $JWTAuth->parseToken()->authenticate();

        $this->validate($request, [
            'name' => 'required',
            'sku' => 'required',
            'price' => 'required',
            'stock' => 'required'
        ]);

        $register = $products->getProductExistBySkuAndUserId($request->input('sku'), $user->id);

        if (!empty($register)) {
            return response()->json(['error' => 'SKU is send already exist'], 400);
        }

        $data = [
            'nome' => $request->input('name'),
            'preco' => $request->input('price'),
            'estoque' => $request->input('stock'),
            'sku' => $request->input('sku'),
            'id_usuario' => $user->id
        ];

        $product = $products->create($data);
        $product->toArray();

        if (!empty($request->input('variations'))) {
            $variations->createVariations($request->input('variations'), $product['id'], $user->id);
            
            $product['variations'] = $request->input('variations');
        }

        return response()->json($product, 201);
    }

    public function patchBySku(Request $request, JWTAuth $JWTAuth, 
                           Products $products, Variations $variations, $sku)
    {        
        $user = $JWTAuth->parseToken()->authenticate();

        $this->validate($request, [
            'sku' => 'required'
        ]);

        $product = Products::getItemBySKUAndUserId($sku, $user->id);

        $data = [
            'nome' => $request->input('name'),
            'preco' => $request->input('price'),
            'estoque' => $request->input('stock'),
            'sku' => $request->input('sku'),
            'id_usuario' => $user->id
        ];

        $product = Products::updateById($data, $product['id']);
        
        if ($product) {
            $product = Products::getItemBySKUAndUserId($sku, $user->id);
            
            return response()->json($product, 200);
        }
        
        return response()->json(['error' => 'Error ocorred when update product'], 400);
    }

    public function getProductBySku(JWTAuth $JWTAuth, Products $products, Variations $variations, $sku)
    {
        $user = $JWTAuth->parseToken()->authenticate();

        $product = Products::getItemBySKUAndUserId($sku, $user->id);

        $variations = $variations->getVariationsByProductId($product['id']);

        $variationProduct = [];
        if (!empty($variations)) {
            $variations = $variations->toArray();

            foreach ($variations as $variation) {
                $variationProduct[] = [
                    'name' => $variation['name'],
                    'price' => $variation['price'],
                    'sku' => $variation['sku'],
                    'stock' => $variation['stock']
                ];
            }
        }

        $product = [
            'id' => $product['id'],
            'sku' => $product['sku'],
            'name' => $product['nome'],
            'stock' => $product['estoque'],
            'price' => (float) number_format($product['preco'], 2),
            'variations' => $variationProduct
        ];

        return response()->json($product, 200);
    }

    public function get(Request $request, JWTAuth $JWTAuth)
    {
        $user = $JWTAuth->parseToken()->authenticate();
        
        $per_page = $request->input('per_page') ? $request->input('per_page') : 15;

        if ($per_page > 100) {
            return response()->json(['error' => 'Query parameter "per_page" not can major 100'], 500);
        }

        $response['total'] = Products::getAllRegisterProducts($user->id);
        $response['per_page'] = $per_page;
        $response['current_page'] = $request->input('page') ? $request->input('page') : 1;
        $response['last_page'] = round($response['total'] / $per_page);
        $response['next_page_url'] = env('APP_URL') . 'v1/products?page=' . ($request->input('page') > 1 ? $request->input('page') + 1: 2);
        $response['prev_page_url'] = env('APP_URL') . 'v1/products?page=' . ($request->input('page') > 1 ? $request->input('page') - 1: 1);
        $response['from'] = $request->input('per_page') ? ($request->input('per_page') + 1) : 16;
        $response['to'] = $request->input('per_page') ? ($request->input('per_page') * 2) : 30;
        $response['data'] = Products::getProductsUserId(
            $user->id, $request->input('per_page'), $request->input('page') == 1 ? 0 : $request->input('page')
        );

        return response()->json($response, 200);
    }

}

