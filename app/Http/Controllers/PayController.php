<?php

namespace App\Http\Controllers;

use DateTime;

use DateTimeZone;
use App\Models\plan;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Requests\ActivePending;
use Illuminate\Support\Facades\Date;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\UserPlanResource;

class PayController extends Controller
{
    public function pending()
    {

      $payment=Payment::where('status', 'pending')->with(['plan','user'])->orderBy('id', 'desc')->get();
      return PaymentResource::collection($payment);
    
    return UserPlanResource::collection(User::where('Status_Plan', 'pending')->with(['plan', 'imgPay' => function ($query) {
        $query->orderBy('id', 'desc')->first();
    }])->get());

    }
    
    
    public function returnFree(Request $request){
     $user=auth('api')->user();    
     $user->plan_id=1;
     $user->save();

    }

     


    public function ActivePending($transactionId, $planId)
    {

          

        // $transactionId = $request->transaction_id;
        $startPlan = gmdate('Y-m-d');
        $endPlan = Carbon::now()->addDays(30)->format('Y-m-d');

        $payment = Payment::where('transaction_id', $transactionId)->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction ID not found',
            ]);
        }

        $payment->status = 'success';
        $payment->save();

        $user = User::find($payment->user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ]);
        }

        $user->update([
            'plan_id' => $planId,
            'start_plan' => $startPlan,
            'end_plan' => $endPlan,
            'Status_Plan' =>'paid',
        ]);
        
        $this->afterPay($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Request is successful',
        ]);
    }
      function afterPay($id)
    {
        $user_comming = User::find($id);
        $user_comming_affiliate = $user_comming->comming_afflite;
        if ($user_comming->plan->title != 'free') {
            $user_plan_price = $user_comming->plan->price;
            $user_master3 = User::where('affiliate_code', $user_comming_affiliate)->first();
            if (!empty($user_master3)) {
                $user_comming_affiliate3 = $user_master3->comming_afflite;
                $perc_paln = +$user_master3->plan->percentage1;
                $this->affililateProccess($user_master3, $user_plan_price, $perc_paln);
                $user_master2 = User::where('affiliate_code', $user_comming_affiliate3)->first();
                if (!empty($user_master2)) {
                    $user_comming_affiliate2 = $user_master2->comming_afflite;
                    $check_his_plan = +$user_master2->plan->percentage2;
                    if ($check_his_plan != null) {
                        $this->affililateProccess($user_master2, $user_plan_price, $check_his_plan);
                    } else {
                        $this->affililateProccess($user_master2, $user_plan_price, $check_his_plan = 0);
                    }
                    $user_master1 = User::where('affiliate_code', $user_comming_affiliate2)->first();
                    if (!empty($user_master1)) {
                        $check_his_plan = +$user_master2->plan->percentage3;
                        if ($check_his_plan != null) {
                            $this->affililateProccess($user_master1, $user_plan_price, $check_his_plan);
                        } else {
                            $this->affililateProccess($user_master2, $user_plan_price, $check_his_plan = 0);
                        }
                    } else {
                        return "Not Assign to Father yet";
                    }
                } else {
                    return "Not Assign to Father yet";
                }
            } else {
                return "Not Assign to Father yet";
            }
        } else {
            return "Not Assign to Paln yet";
        }
    }

    function affililateProccess($user, $price, $perc)
    {

        $user_old_money = $user->money;
        $user_new_money = ($perc / 100) * $price;
        $user_money = $user_old_money + $user_new_money;
        $user->money = $user_money;
        $user->save();
    }




}
