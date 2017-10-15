<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

use App\Products;
use App\Variations;

class VariationsController extends Controller
{

	public function patchBySku(Request $request, JWTAuth $JWTAuth, 
							   Variations $variations, $sku)
	{
        $user = $JWTAuth->parseToken()->authenticate();

		$variation = Variations::getBySkuAndUserId($sku, $user->id);

		if (empty($variation->toArray())) {
			return response()->json(['error' => 'Variation not found'], 400);
		}
	
		if ($sku != $request->input('sku')) {
			return response()->json(['error' => 'SKU URI not match with data'], 400);	
		}
		$variation = $variation->toArray()[0];

		if (Variations::updateById($request->all(), $variation['id'])) {
			return response()->json(Variations::getBySkuAndUserId($sku, $user->id)->toArray()[0], 200); 
		}
		
		return response()->json(['error' => 'Error ocorred to process patch variation'], 400);
	}

}