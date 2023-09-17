<?php

namespace App\Http\Controllers\Front;

use auth;
use App\Models\plan;
use App\Models\User;
use App\Models\Payment;
use App\Models\BotStatus;
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

class FrontController extends Controller
{
    public function getPlan()
    {
        return PlanResource::collection(plan::with('plan_desc')->get());
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
            "wallet" => "TLmUhwJQuvGmBfYeURLb39Pwc9LD6REsuA"
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
            return response()->json([
                'Success' => false,
                'Massage' => "Invalid token",
            ]);
        }

        $Payment = Payment::where('user_id', $user->id)->latest()->first();

        if (!$request->textId) {
            return response()->json([
                'Success' => false,
                'Massage' => "Text ID or image not provided",
            ]);
        }

        // Handle image upload


        
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

        $existingDeposit = DepositsBinance::where('textId', $textId)->where('status', '1')->first();

        if ($existingDeposit) {
            return response()->json([
                'success' => false,
                'massage' => "The Text ID found or wrong",
            ]);
        }

        $existingDeposit = DepositsBinance::where('textId', $textId)->first();

        if (!$existingDeposit) {
            return response()->json([
                'success' => false,
                'massage' => "The deposit has not been made to Binance, please check this",
            ]);
        }

        // Found deposit, continue processing

        $getplanPrice = Plan::where('id', $Payment['plan_id'])->first();

        if ($existingDeposit->amount < $getplanPrice['price']) {
            return response()->json([
                'success' => false,
                'massage' => "You don't have enough money ",
            ]);
        } elseif ($existingDeposit->amount == $getplanPrice['price']) {
            $new = new PayController();
            $new->ActivePending($Payment['transaction_id'], $Payment['plan_id']);

            $notfication = new NotficationController();
            $body = "تم الاشتراك بنجاح";
            $notfication->notfication($user->fcm_token, $body);
            $bodyManger = "تم اشترك شخص جديد";
            $notfication->notficationManger($bodyManger);
        } elseif ($existingDeposit->amount > $getplanPrice['price']) {

            $coolect = $existingDeposit->amount - $getplanPrice['price'];

            $addMony = $user->money += $coolect;
            $notfication = new NotficationController();
            $body = "تم الاشتراك بنجاح كذلك تمت اضافه الباقي اللي محفظتك 
            رصيدك اصبح $addMony ";
            $notfication->notfication($user->fcm_token, $body);
            $bodyManger = "تم اشترك شخص جديد";
            $notfication->notficationManger($bodyManger);




            return response()->json([
                'success' => true,
                'message' => "Amount is greater than the plan price",

            ]);
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
}
