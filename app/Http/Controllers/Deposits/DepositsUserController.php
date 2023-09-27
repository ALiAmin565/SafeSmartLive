<?php

namespace App\Http\Controllers\Deposits;

use Illuminate\Http\Request;
use App\Models\DepositsBinance;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Deposits\DepositsController;
use App\Http\Controllers\Helper\NotficationController;

class DepositsUserController extends Controller
{
    public function cheakTextID(Request $request)
    {

        //  return 150;
         $user = auth('api')->user();

        $textid = $request['textid'];
        $existingDeposit = DepositsBinance::where('textId', $textid)->where('status', '1')->first();

        if ($existingDeposit) {
            return response()->json([
                'suucess' => false,
                "massage" => "The Text ID found or wrong",
            ]);
        } else {
<<<<<<< HEAD
            //  return 150;
            $binanceDeopsite = new DepositsController();
            $binanceDeopsite->getDeposits($user->id);
=======

            $binanceDeopsite = new DepositsController();
             $binanceDeopsite->getDeposits($user->id);
>>>>>>> 2d28734f27e193998c2fa21f9011f13eec0eac30


            $existingDeposit = DepositsBinance::where('textId', $textid)->first();
            if (!$existingDeposit) {
                return response()->json([
                    'suucess' => false,
                    "massage" => "The deposit has not been made to Binance, please check this",
                ]);
            } else {  //found it

                $existingDeposit->status = "1";
                $existingDeposit->user_id = $user->id;
                $existingDeposit->save();

                // Update the user's balance
                $user->money += $existingDeposit->amount;
                $user->save();


                // for Notfication
                $notfication = new NotficationController();
                $body = "تم الايداع في محفظتك مبلغ $existingDeposit->amount وأصبح اجمالي الرصيد $$user->money";
                $notfication->notfication($user->fcm_token, $body);
                $bodyManger = "تم إيداع مبلغ $$existingDeposit->amount في محفظتك من قبل $user->name   ";
                $notfication->notficationManger($bodyManger);
            }
        }

<<<<<<< HEAD
        return response()->json([
            'suucess' => true,
            "amount" => $existingDeposit->amount,
            "massage" =>
            "operation accomplished successfully"
        ]);
=======
        return response()->json(['suucess' => true, 
        "amount"=>$existingDeposit->amount,
        "massage" => 
        "operation accomplished successfully"]);
>>>>>>> 2d28734f27e193998c2fa21f9011f13eec0eac30
    }


    public function historyDeposit(Request $request)
    {
        $user = auth('api')->user();

        return $existingDeposit = DepositsBinance::where('user_id', $user->id)->get();
    }
}
