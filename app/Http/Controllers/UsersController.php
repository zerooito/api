<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;

use Illuminate\Http\Request;

class UsersController extends Controller
{

    public function teste(Request $request)
    {
        return Response(['total' => 0], 200);
    }

}

