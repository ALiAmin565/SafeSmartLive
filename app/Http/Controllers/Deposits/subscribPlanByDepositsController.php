<?php

namespace App\Http\Controllers\Deposits;

use App\Models\plan;
use App\Models\Payment;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PayController;
use App\Http\Controllers\Helper\NotficationController;

class subscribPlanByDepositsController extends Controller
{
    use ResponseJson;

    public function subscrib(Request $request)
    {
         $user = auth('api')->user();
        $Payment = Payment::where('user_id', $user->id)->latest()->first();

        $planID = $Payment['plan_id'];
        $transactionID = $Payment['transaction_id'];

        $plan = plan::where('id', $planID)->first();

        if ($user->money >= $plan->price) {
            $active = new PayController();

            $activated = $active->ActivePending($transactionID, $planID);
            $responseData = json_decode($activated->getContent(), true);

            if ($responseData['success'] == 1) {
                $user->money -= $plan->price;
                $user->save();

                $notification = new NotficationController();
                $body = "تم الاشتراك بنجاح";
                $notification->notfication($user->fcm_token, $body);

                $message = "Request is successful";
                return $this->success($message);
            } else {
                $message = "Error during activation";
                return $this->error($message);
            }
        } else {
            $message = "NOT have enough money";
            return $this->error($message);
        }
    } 

    
}
