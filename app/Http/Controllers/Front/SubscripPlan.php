<?php

namespace App\Http\Controllers\Front;

use auth;
use App\Models\plan;
use App\Models\User;
use App\Models\feesBot;
use App\Models\Payment;
use App\Models\BotStatus;
use App\Models\TargetsRecmo;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use App\Models\recommendation;
use App\Models\DepositsBinance;
use App\Http\Requests\imageRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\planIdRequest;
use App\Http\Resources\PlanResource;
use App\Http\Requests\OrderPayRequest;
use App\Http\Controllers\PayController;
use App\Http\Resources\PaymentResource;
use App\Http\Controllers\Deposits\DepositsController;
use App\Http\Controllers\Helper\NotficationController;

class SubscripPlan extends Controller
{
    use ResponseJson;
    public function getPlan()
    {
        return PlanResource::collection(plan::with('plan_desc')->where('id', '!=', 7)->get());
    }

    // for user slect plan
    public function Orderpay(planIdRequest $request)
    {
        $timestamp = time(); // Get the current timestamp
        $uniqueId = uniqid(); // Generate a unique identifier
        $randomNumber = mt_rand(1000, 9999); // Generate a random number
        $transactionId = $timestamp . $uniqueId . $randomNumber;


        $header = $request->header('Authorization');
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'Success' => false,
                'Massage' => "Invalid token",
            ]);
        }



        //  for cheak if have any status pending make is declined
        $paymentSelect = Payment::where('user_id', $user->id)
            ->where('status', 'pending')
            ->update([
                'status' => 'declined',
            ]);




        $Payment = Payment::create([
            'user_id' => $user->id,
            'plan_id' => $request['plan_id'],
            'status' => 'pending',
            'transaction_id' => $transactionId,

        ]);
        $user = user::find($user->id);
        $user->Status_Plan = "pending";
        $user->save();
        return response()->json([
            "success" => true,
            "wallet" => "TLNaJdkATC5NnmHfnLfskXMG85NtihQT29"
        ]);
    }



    public function HistroyPay(Request $request)
    {
        $header = $request->header('Authorization');
        $user = auth('api')->user();
        if (!$user) {
            return response()->json([
                'Success' => false,
                'Massage' => "Invalid token",
            ]);
        }
        $Payment = Payment::where('user_id', $user->id)->with('plan')->get();
        return PaymentResource::collection($Payment);
    }



    public function UploadImagePayment(imageRequest $request)
    {

        $user = auth('api')->user();
        if (!$user) {
            return $this->error('Invalid token');
        }
        $Payment = Payment::where('user_id', $user->id)->latest()->first();

        if (!$request->textId) {
            return $this->error('Text ID  not provided');
        }

        $Payment->update([
            'image_payment' => $request->textId,
        ]);


        return $this->complate($request->textId, $Payment);


        return response()->json([
            'Success' => true,
            'Massage' => "Uploaded Image",
        ]);
    }

    public function complate($textId, $Payment)
    {
        $user = auth('api')->user();

        $existingDeposits = DepositsBinance::where('textId', $textId)->where('status', '1')->first();

        if ($existingDeposits) {
            return $this->error('The Text ID found or wrong');
        }

        $existingDeposit = DepositsBinance::where('textId', $textId)->first();

        if (!$existingDeposit) {
            return $this->error('The deposit has not been made to Binance, please check this');
        } else {

            $binanceDeopsite = new DepositsController();
            $binanceDeopsite->getDeposits();


            $getplanPrice = Plan::where('id', $Payment['plan_id'])->first();

            if ($existingDeposit->amount < $getplanPrice['price']) {
                return $this->error("You don't have enough number_points ");
            } elseif ($existingDeposit->amount == $getplanPrice['price']) {

                $new = new PayController();
                $new->ActivePending($Payment['transaction_id'], $Payment['plan_id']);

                // for updata stata textid =1
                $existingDeposit->update([
                    'status' => 1,
                ]);



                $notfication = new NotficationController();
                $body = "تم الاشتراك بنجاح";
                $notfication->notfication($user->fcm_token, $body);
                $bodyManger = "تم اشترك شخص جديد";
                $notfication->notficationManger($bodyManger);

                return $this->success('You have successfully subscribed');
            } elseif ($existingDeposit->amount > $getplanPrice['price']) {



                $new = new PayController();
                $new->ActivePending($Payment['transaction_id'], $Payment['plan_id']);

                $coolect = $existingDeposit->amount - $getplanPrice['price'];

                $addMony = $user->number_points += $coolect;
                // updata user mony
                $user->update([
                    'number_points' =>  $addMony,
                ]);
                // update text id


                $existingDeposit->status = '1';
                $existingDeposit->save();



                $notfication = new NotficationController();
                $body = "تم الاشتراك بنجاح كذلك تمت اضافه الباقي اللي محفظتك
            رصيدك اصبح $addMony ";
                $notfication->notfication($user->fcm_token, $body);
                $bodyManger = "تم اشترك شخص جديد";
                $notfication->notficationManger($bodyManger);

                return $this->success('You have successfully subscribed and the rest has been transferred to your wallet');
            }
        }
    }



    // getRecmoData

    public function getRecmoData($recomId)
    {
        return $user = auth('api')->user();
        $recom = recommendation::where('id', $recomId)->first();
        $targets = TargetsRecmo::where('recomondations_id', $recomId)->pluck('target')->toArray();
        $entry = $recom->entry_price;
        $parts = explode(' - ', $entry);
        // Convert the string parts to float values
        $output = array_map('floatval', $parts);
        // $facilityImages = FacilityImages::where('facility_id', $facilityId)->get()->pluck('image')->toArray();
        return response()->json([
            'ticker' => $recom->currency,
            'targets' => $targets,
            'entry' => $output,
            'stopLose' => $recom->stop_price,
        ]);
    }

    // for subscribe by fess

    public function subByFess()
    {
        $user = auth('api')->user();
        if (!$user) {
            return $this->error('Invalid token');
        }
        $Payment = Payment::where('user_id', $user->id)->where('status', 'pending')->latest()->first();
        if (!$Payment) {
            return $this->error('Subscrib First');
        }
        $planPayment = $Payment->plan_id;

        $plan = plan::where('id', $planPayment)->first();
        $pricePlan = $plan->price;

        if ($user->number_points >= $pricePlan) {
            $new = new PayController();
            $new->ActivePending($Payment['transaction_id'], $Payment['plan_id']);

            $user->number_points -= $pricePlan;

            // Save the updated user object to the database
            $user->save();

            $notfication = new NotficationController();
            $body = "تم الاشتراك بنجاح ";
            $notfication->notfication($user->fcm_token, $body);
            $bodyManger = "تم اشترك شخص جديد";
            $notfication->notficationManger($bodyManger);




            $this->storeSubPlan($pricePlan);


            return $this->success('You have successfully subscribed');
        } else {
            return $this->error("You don't have enough number_points ");
        }
    }

    public function storeSubPlan($pricePlan)
    {
         $user = auth('api')->user();
        $feesBot=feesBot::create([
        'user_id'=>$user->id,
        'fees'=>$pricePlan,
        'status'=>"success",
        ]);


    }
}
