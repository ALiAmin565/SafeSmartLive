<?php

namespace App\Http\Controllers\Boot;

use App\Models\plan;
use App\Models\binance;
use App\Models\bots_usdt;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\StoreMyBotRequest;


class MyBotController extends Controller
{
    use ResponseJson;
    public function AllMyBot(Request $request)
    {

        $user = auth('api')->user();
        $bots = bots_usdt::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        $botMap = [];

        foreach ($bots as $bot) {
            if (!isset($botMap[$bot->bot_id]) || $bot->bot_status == 1) {
                $botMap[$bot->bot_id] = $bot;
            }
        }

      $uniqueBots = collect(array_values($botMap));

        $uniqueBots->each(function ($data) {
            $bot = $data->bot;
            $data->nameBot = $bot->bot_name;
            $data->currency = explode('_', $bot->bot_name)[0] . "-USDT";
            //   for profit
            $data->profit = "12.3%";
            $data->makeHidden('bot');
        });

        return $uniqueBots;
    }

    public function storeMyBot(StoreMyBotRequest $request)
    {

             $user = auth('api')->user();


        //  info plan bots
        $planid = $user->plan_id;
        $plan = plan::where('id', $planid)->first();
        $numberBpt = $plan->number_bot;

        // get all my bots
        $myBot = $request['bot_id'];
        $myusdt = $request['usdt'];
        $blance = $request['blance'];
        $count = bots_usdt::where([
            ['user_id', '=', $user->id],
            ['bot_status', '=', '1'],
        ])->count();

        if ($count >= $numberBpt) {
            return $this->error("You subscribe to everything available to you");
        } else {
            $checkSubscription = bots_usdt::where([
                ['user_id', '=', $user->id],
                ['bot_id', '=', $myBot],
                ['bot_status', '=', '1'],
            ])->first();


            if ($checkSubscription) {
                return $this->error("You are already subscribed");
            }
            // get totle binanace
            $totleNumberOrder = $user->num_orders * $user->orders_usdt;
            $totleUsdMyBot = bots_usdt::where('user_id', $user->id)->where('bot_status', '1')->sum('orders_usdt');
            $finletotle = $totleUsdMyBot + $totleNumberOrder + $myusdt;



            if ($finletotle >= $blance) {
                return $this->error("You don't have enough money ");
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
