<?php

namespace App\Http\Controllers\Boot;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminUserController extends Controller
{
    public function getMyAdmin(Request $request)
    {
         $user = auth('api')->user();
        return json_decode($user->admins);
    }

    







}
