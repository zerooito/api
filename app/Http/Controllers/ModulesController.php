<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

use App\Modules;

class ModulesController extends Controller
{

    public function getModulesActives(Request $request, JWTAuth $JWTAuth)
    {
    	$user = $JWTAuth->parseToken()->authenticate();
    	
        $modules = Modules::getModulesByUser($user);

        return response()->json($modules, 200);
    }
    
}