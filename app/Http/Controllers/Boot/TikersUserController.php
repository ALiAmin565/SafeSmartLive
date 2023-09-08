<?php

namespace App\Http\Controllers\Boot;

use App\Http\Controllers\Controller;
use App\Models\Tiker;
use Illuminate\Http\Request;

class TikersUserController extends Controller
{
    public function getAllTikers(Request $request)
    {
        return Tiker::get();
    }

    public function getAllUnsubscrib(Request $request)
    {
         $user = auth('api')->user();

         return json_decode($user->tickers);

    }

}
