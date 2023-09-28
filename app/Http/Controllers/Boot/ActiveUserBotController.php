<?php

namespace App\Http\Controllers\Boot;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
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
            $user->is_bot = 1;
            $user->num_orders = $request['numOrders'];
            $user->orders_usdt = $request['ordersUsdt'];
            $user->tickers = $request['tickers'];



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

        $data = [
            'shutdown' => 1,
            "userid" => $user->id,

        ];

        $response = Http::post('http://51.161.128.30:5015/shutdown', $data);
        $responseBody = $response->body();

        return response()->json([
            'success' => true,
            'message' => $user,
        ]);
    }







}
