<?php

namespace App\Http\Controllers\Boot;

use App\Models\Bots;
use App\Models\User;
use App\Models\binance;
use App\Models\feesBot;
use App\Events\recommend;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Helper\NotficationController;

class fessBotController extends Controller
{
    public $notfication;
    public $MyBotController;
    public function __construct()
    {
        $this->notfication = new NotficationController();
        $this->MyBotController = new MyBotController();
    }
    public function fees(Request $request)
    {


        $binace = binance::where('status_fees', 1)->get();

        if ($binace->isEmpty()) {
            return 'NOT HAVE ANY FEES';
        }
        $data = [
            'recomondations_id' => 1,
            "admin" => 1,
            "ticker" => 1,
            "targets" => 1,
            "entry" => 1,
            "stoplose" => 1,
            "bot_num" => 1,
        ];


        foreach ($binace as $fees) {
            $user = User::find($fees->user_id);
            $botMony = $fees->fees;
            $userMony = $user->number_points - $botMony;
            $user->number_points = $userMony;
            $user->save();

            $fees->status_fees = 0;
            $fees->save();

            $modelFess = feesBot::create([
                'user_id' => $fees->user_id,
                'fees' => $fees->fees,
                'number_bot' => $fees->bot_num,
                'ticker' => $fees->symbol,
                'profusdt' => $fees->profit_per,
                'status' => 'success'
            ]);

            if ($userMony < 0) {
                $data = [
                    'shutdown' => 0,
                    "userid" => $user->id,
                ];

                $response = Http::post('http://51.161.128.30:5015/shutdown', $data);
                $responseBody = $response->body();

                // for profit
                $this->NotfictionProfit($fees->profit_per, $user->fcm_token);

                // for fees
                $userMony = $user->number_points;

                $this->Notfictionfess($user->fcm_token, $userMony);
            } elseif ($userMony == 0 || $userMony <= 5) {
                // for profit
                $this->Notfictionfive($user->fcm_token, $fees->profit_per,);

                $this->NotfictionProfit($fees->profit_per, $user->fcm_token);
            } else {
                $this->NotfictionProfit($fees->profit_per, $user->fcm_token);
            }
        }

        return 'ok';
    }
    //   for profit
    public function NotfictionProfit($profit, $userfcm)
    {
        if ($profit > 0) {

            $body = "مبروك تمت تحقيق الهدف بنجاح وقد حققت مكسب $profit %";
            $this->notfication->notfication($userfcm, $body);
        } else {
            $body = "مبروك تمت تحقيق الهدف بنجاح ";
            $this->notfication->notfication($userfcm, $body);
        }
    }
    // for cheack have than 0
    public function Notfictionfess($user, $userMony)
    {
        $body = "عميلنا العزيز رصيدك $userMony وللأسف تم إيقاف كل الأبوات الخاصة بك يرجى الشحن وتفعيل الأبوات مرة أخرى للاستمرار في تحقيق الأرباح";

        $this->notfication->notfication($user, $body);
    }
    // for cheack have = 0 ,5

    public function Notfictionfive($user, $userMony)
    {

        $body = "عميلنا العزيز رصيدك $userMony يرجى الشحن في أسرع وقت حتى لا يتم إيقاف الأبوات الخاصة بأسيتادتكم والاستمرار في تحقيق الأرباح";
        $this->notfication->notfication($user, $body);
    }
}
