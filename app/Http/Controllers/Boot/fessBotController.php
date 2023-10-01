<?php

namespace App\Http\Controllers\Boot;

use App\Models\User;
use App\Models\binance;
use App\Models\feesBot;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\NotficationController;
use App\Models\Bots;

class fessBotController extends Controller
{
    public function fees(Request $request)
    {
        $notfication = new NotficationController();
        $MyBotController = new MyBotController();


        $binace = binance::where('status_fees', 1)->get();

        $body = "عميلنا العزيز رصيدك
                يرجي الشحن ف اسرع وقت حتي لا يتم ايقاف الابوات الخاصه باسيتادتكم والاستمرار ف تخقيق الاراباح";
                $notfication->Ahmed($body);

        if ($binace->count() < 1) {
            return 'NOT HAVE ANY FESS';
        }
        foreach ($binace as $fees) {
            $user = User::where('id', $fees->user_id)->first();
            $botMony = $fees->fees;
            $userMony = $user->number_points -= $botMony;
            $user->save();
            $fees->status_fees = 0;
            $fees->save();


            $modelFess = feesBot::create([
                'user_id' => $fees->user_id,
                'fees' => $fees->fees,
                'number_bot' => $fees->bot_num,
                'ticker' => $fees->symbol,
                'ticker' => $fees->symbol,
                'profusdt' => $fees->profit_per,
                // 'status'=>"success",
            ]);



            if ($userMony < 0) {
                // send shutdown
                $request['shutdown'] = 0;
                $request['userid'] = $user->id;
                $MyBotController->shutdownBot($request);
                // send notfication
                $body = "عميلنا العزيز رصيدك $userMony
                وللاسف تم ايقاف كل الابوات الخاصه بك يرجي الشحن وتفعيل لابوات مره اخره للاستمرار ف تحقيق الارباح";
                $notfication->notfication($user->fcm_tpken, $body);
            } elseif ($userMony > (0.5)) {
                $body = "عميلنا العزيز رصيدك $userMony
                يرجي الشحن ف اسرع وقت حتي لا يتم ايقاف الابوات الخاصه باسيتادتكم والاستمرار ف تخقيق الاراباح";
                $notfication->notfication($user->fcm_tpken, $body);
            } else {
                $body = " مبرووووك تمت تحقيق الهدف بنجاج وقد حققت مكسب $fees->profit_per %";
                $notfication->notfication($user->fcm_tpken, $body);
            }
            $bot = Bots::where('id', $fees->bot_num)->first();
            // return  $notfication->allPlanForBot($bot->bot_name, $fees->symbol, $fees->status);
        }


        return 'ok';
    }
}
