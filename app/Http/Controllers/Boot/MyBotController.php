<?php

namespace App\Http\Controllers\Boot;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\bots_usdt;


class MyBotController extends Controller
{
    public function AllMyBot(Request $request)
    {
        $user = auth('api')->user();
         $bots = bots_usdt::where('user_id', $user->id)->get();
        
         $bots->each(function($data){
          $bot=$data->bot;
          $data->currency=explode('_', $bot->bot_name)[0] . "-USDT";
          $data->nameBot= $bot->bot_name;
        //   for profit 

        
         });
 
       

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

    public function UpdatedMyBot()
    {
    }
    public function historyMyBot(Request $request)
    {

    }
}
