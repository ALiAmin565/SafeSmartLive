<?php

namespace App\Http\Controllers\Boot;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ActiveBotRequest;

class ActiveUserBotController extends Controller
{
    public function ActiveBot(ActiveBotRequest $request)
    {

        $user = auth('api')->user();


        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => "Invalid token",
            ]);
        } else {
           $user->is_bot=1;
            $user->num_orders = $request['numOrders'];
            $user->orders_usdt = $request['ordersUsdt'];
            $user->tickers = $request['tickers'];
            // $user->admins = $request['admins'];

            

          $user->save();
           

         }

          return response()->json([
                'success' => true,
                'message' => $user,
            ]);
    }

    public function stopBot(Request $request)
    {
        $user = auth('api')->user();

        $user->is_bot=1;

        return $user;

    }


}
