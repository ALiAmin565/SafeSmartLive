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




        // Add fake or static data here
        $fakeBot = new bots_usdt();
        $fakeBot->bot_id = 1; // Choose a unique ID
        $fakeBot->user_id = $user->id;
        $fakeBot->bot_status = $user->is_bot; // Example status
        $fakeBot->nameBot = 'Fake Bot';
        $fakeBot->currency = 'Fake Currency';
        $fakeBot->profit = '10.0%'; // Example profit
        $fakeBot->bot_name = "Currency_usdt";
        $botMap[999] = $fakeBot; // Add the fake bot to the map

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
            $binance = binance::where('user_id', $data->user_id)->where('bot_num', $data->bot_id)->where('side', 'sell')->sum('profit_per');
            $test = $bot->profit = number_format($binance, 2) . "" . "%"; // Approximate to two decimal places

            $data->profit = strval($test);

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

             $gethistory = Binance::where('user_id', $user->id)->where('bot_num', $bot_id)->orderBy('created_at', 'desc')->get();

        $gethistory->each(function ($data) {
            // Convert to double
            if($data->status == "FILLED")
            {
                $data->status='success';
            }
            $data->profit_per = number_format($data->profit_per, 2, '.', ''); // Format to 2 decimal places with no thousands separator
        });

        // Now $formattedHistory contains the formatted profit_per values



        if ($gethistory->isEmpty()) {
            return $this->error('You not  subscribed');
        } else {




            $totleSell = $gethistory->where('side', 'sell')->sum('profit_per');
            $totleBuy = $gethistory->where('side', 'buy')->sum('price');

            if ($totleSell != 0) {
                $profit = number_format($totleSell, 2) . "" . "%";
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
        return $this->success("operation accomplished successfully");
    }
}
