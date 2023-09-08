<?php

namespace App\Http\Controllers\Front;

use auth;
use App\Models\plan;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\recommendation;
use App\Http\Requests\imageRequest;
use App\Http\Requests\planIdRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Http\Requests\OrderPayRequest;
use App\Http\Resources\PaymentResource;

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
        $user=auth('api')->user();
        if(!$user){
            return response()->json([
                'Success'=>false,
                'Massage'=>"Invalid token",
            ]);
        }



//  for cheak if have any status pending make is declined
        $paymentSelect=Payment::where('user_id', $user->id)
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
        $user=user::find($user->id);
        $user->Status_Plan="pending";
        $user->save();
        return response()->json([
            "success" => true,
             "wallet"=>"TLmUhwJQuvGmBfYeURLb39Pwc9LD6REsuA"
        ]);
    }

    public function HistroyPay(Request $request)
    {
        $header = $request->header('Authorization');
        $user=auth('api')->user();
        if(!$user){
            return response()->json([
                'Success'=>false,
                'Massage'=>"Invalid token",
            ]);
        }
        $Payment = Payment::where('user_id', $user->id)->with('plan')->get();
        return PaymentResource::collection($Payment);

    }

    // public function SelectPlan(Request $request)
    // {

    //     $user = User::find($request->user_id)->first();
    //     $user->update([
    //         'Status_Plan' =>'pending',
    //         'plan_id' => $request['plan_id'],
    //     ]);
    //     $user->save();

    //     return response()->json([
    //         'Massage' => "The process has been completed and we are awaiting approval",
    //     ]);
    // }

    // public function Recommindation(Request $request)
    // {

    //     $user = User::find($request->user_id)->first();
    //     $recommendation = recommendation::where('planes_id', $user->plan_id)->get();
    //     return $recommendation;

    //     return $recommendation;
    // }

    public function UploadImagePayment(imageRequest $request)
    {




        $header = $request->header('Authorization');
        $user=auth('api')->user();
        if(!$user){
            return response()->json([
                'Success'=>false,
                'Massage'=>"Invalid token",
            ]);
        }


         $Payment=Payment::where('user_id',$user->id)->latest()->first();


        if ($request->hasFile('img')) {
            $img = time() . '.' . $request->img->extension();
            $path = $request->img->move(public_path('ImagePayment'), $img);
        }else{
            return response()->json([
                'Success'=>false,
                'Massage'=>"Not Uploaded Image",
            ]);
        }
        $Payment->update([
            'image_payment'=>$img,
        ]);


        return response()->json([
            'Success'=>true,
            'Massage'=>"Uploaded Image",
        ]);




    }




    // getRecmoData

    public function getRecmoData($recomId)
    {
        $recom = recommendation::where('id', $recomId)->first();
        $targets=TargetsRecmo::where('recomondations_id',$recomId)->pluck('target')->toArray();
        $entry = $recom->entry_price;
        $parts = explode(' - ', $entry);
        // Convert the string parts to float values
        $output = array_map('floatval', $parts);
        // $facilityImages = FacilityImages::where('facility_id', $facilityId)->get()->pluck('image')->toArray();
        return response()->json([
            'ticker' => $recom->currency,
            'targets'=>$targets,
            'entry'=>$output,
            'stopLose'=>$recom->stop_price,
        ]);
    }
    
    // Get Bot Controller 
    
    public function getBotData()
    {
        // return 1510;
    $user=auth('api')->user();
    if($user)
    {
    $botController=$user->botController;
    return  $botController;
    }
    
    return response()->json([
            'massage' => 'Not authorized'
    ]);
    }
    
    
    // setBotData
    
    public function setBotData()
    {
        $user=auth('api')->user();
        if($user)
        {
        $botController=$user->botController;
        if($botController == 'off' || $botController == null )
        $user->botController='on';
        else
        $user->botController='off';
        
        $user->save();
        $lastStatue =$user->botController;
        return $lastStatue;
        }
         return response()->json([
            'massage' => 'Not authorized'
         ]);
    }



}
