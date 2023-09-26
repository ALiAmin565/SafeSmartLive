<?php

namespace App\Http\Controllers\Boot;

use App\Models\binance;
use App\Models\bots_usdt;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;


class MyBotController extends Controller
{
    use ResponseJson;
    public function AllMyBot(Request $request)
    {
        $user = auth('api')->user();
        $bots = bots_usdt::where('user_id', $user->id)->get();

        $bots->each(function ($data) {
            $bot = $data->bot;
            $data->currency = explode('_', $bot->bot_name)[0] . "-USDT";
            $data->nameBot = $bot->bot_name;
            //   for profit
            $data->profit = "12.3%";
            $data->makeHidden('bot');
        });

        unset($bots->bot);

        return $bots;
    }

    public function storeMyBot(Request $request)
    {

        $user = auth('api')->user();
        $myBot = $request['bot_id'];
        $myusdt = $request['usdt'];
        $checkSubscription = bots_usdt::where([
            ['user_id', '=', $user->id],
            ['bot_id', '=', $myBot],
            ['bot_status', '=', '1'],
        ])->first();

        if ($checkSubscription) {
            return $this->error("You are already subscribed");
        } else {
            $bot = bots_usdt::create([
                'user_id' => $user->id,
                'bot_id' => $myBot,
                'orders_usdt' => $myusdt,
                'bot_status' => 1
            ]);
            return $this->success("You have successfully subscribed to the bot");
        }
    }

    public function UpdatedMyBot(Request $request)
    {
        $user = auth('api')->user();
        $shutdown = $request['shutdown'];
        $data = [
            'shutdown' => $shutdown,
            "userid" => $user->id,

        ];
        $response = Http::post('http://51.161.128.30:5015/shutdown', $data);
        return $responseBody = $response->body();
    }
    public function historyMyBot(Request $request)
    {


        $user = auth('api')->user();
        $bot_id = $request['bot_id'];

        $gethistory = Binance::where('user_id', $user->id)->where('bot_num', $bot_id)->get();
        if ($gethistory->isEmpty()) {
            return $this->error('You not  subscribed');
        } else {
            $totleSell = $gethistory->where('side', 'sell')->sum('buy_price_sell');
            $totleBuy = $gethistory->where('side', 'buy')->sum('price');

            if ($totleBuy != 0) {
                $profit = ($totleSell / $totleBuy) * 100;
            } else {
                $profit = 0; // To avoid division by zero if there are no 'buy' records.
            }

            // Create an array or an object to return both $gethistory and $profit
            $result = [
                'profit' => $profit,
                'gethistory' => $gethistory,

            ];

            return $result;
        }
    }

    public function shutdownBot(Request $request)
    {
        $user = auth('api')->user();
        $shutdown = $request['shutdown'];
        $data = [
            'shutdown' => $shutdown,
            "userid" => $user->id,

        ];

        $response = Http::post('http://51.161.128.30:5015/shutdown', $data);
          $responseBody = $response->body();

          return $this->success('operation accomplished successfully');
    }
}
