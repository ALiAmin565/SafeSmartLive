<?php

namespace App\Http\Controllers\Front;

use App\Models\posts;
use App\Models\User;
use Carbon\Carbon;

use App\Models\plan_recommendation;
use App\Models\video;
use App\Models\transfer_many;
use App\Models\Archive;
use App\Models\Massage;
use Illuminate\Http\Request;
use App\Models\recommendation;
use App\Http\Controllers\Controller;
use App\Http\Resources\VadioResource;
use App\Http\Resources\ArchiveResource;
use App\Http\Resources\RecommendationResource;
use App\Http\Resources\Withdraw_moneyResource;
use App\Http\Requests\Storetransfer_manyRequest;
use GuzzleHttp\Client;
use App\Models\ImageSubmissionBinance;
use Illuminate\Support\Facades\Auth;
use App\Models\TargetsRecmo;


 class TabsController extends Controller
{
    public function Videos()
    {
      return VadioResource::collection(video::get());
    }
    public function Archive()
    {
        $archives = Archive::with([
            'recommendation.target',
            'recommendation.plan2' => function ($query) {
                $query->select('id', 'name');
            }
        ])->get()->sortBy('created_at');

         $post = posts::where('status','is_post')->orderBy('created_at')->get();

        $combinedResult = collect([$archives, $post])->flatten()->sortBy('created_at')->values();

        return response()->json([
            'data' => $combinedResult
        ]);
    }
    
        public function getPosts()
    {
          $user=auth('api')->user();
         $plan_ids=$user->Role->pluck('id')->toArray();
        if(!$user){
            return response()->json([
                'Success'=>false,
                'Massage'=>"Invalid token",
            ]);
        }
        if($user->state == 'super_admin' || $user->state == 'admin'){
            $post = posts::whereIn('plan_id',$plan_ids)->orderBy('created_at')->get();
            return response()->json([
                'data' => $post
            ]);
        }
        else{
            $post = posts::where('plan_id',$user->plan_id)->orderBy('created_at')->get();
            return response()->json([
                'data' => $post
            ]);
        }
    }



    public function Advice(Request $request)
    {
                        $header = $request->header('Authorization');

                        $user=auth('api')->user();
                        
                        if(!$user){
                            return response()->json([
                                'Success'=>false,
                                'Massage'=>"Invalid token",
                            ]);
                        }
                 $ali=plan_recommendation::where('planes_id',$user->plan_id)->get();
                 $recomIds = $ali->pluck('recomondations_id')->toArray();

        
  
                    $recom = recommendation::with(['target','tragetsRecmo'])
                    ->whereIn('id', $recomIds)
                    // ->where('archive', '0')
                    ->orderBy('created_at')
                    ->get();

                    $post = posts::where('status','is_advice','planes_id')
                    ->where('plan_id', $user->plan_id)
                    ->orderBy('created_at')
                    ->get();

                    // $combinedResult = collect([$recom, $post])
                    // ->flatten()
                    // ->sortBy(function ($item) {
                    //     return strtotime($item->created_at);
                    // })
                    // ->values();
                    
                    $combinedResult = collect([$recom, $post])
                    ->flatten()
                    ->each(function ($item) {
                        $item->created_at = date('Y-m-d H:i:s', strtotime($item->created_at . '+3 hours'));
                          $item->updated_at = date('Y-m-d H:i:s', strtotime($item->updated_at . '+3 hours'));
                    })
                    ->sortBy(function ($item) {
                        return strtotime($item->created_at);
                    })
                    ->values();


                    return response()->json([
                    'data' => $combinedResult,
                    
                    ]);
// }


    }

    // for transfarMony
    public function TransfarManyClient(Storetransfer_manyRequest $request)
    {
        $timestamp = time(); // Get the current timestamp
        $uniqueId = uniqid(); // Generate a unique identifier
        $randomNumber = mt_rand(1000, 9999); // Generate a random number

        $transactionId = $timestamp . $uniqueId . $randomNumber;



            $user=auth('api')->user();
            $transfer_many=transfer_many::create([
                'money'=>$request['money'],
                'Visa_number'=>$request['Visa_number'],
                'status'=>'pending',
                'transaction_id'=>$transactionId,
                'user_id'=>$user->id,

            ]);

                if ($request['money'] > $user->money) {
                    return response()->json(['success' => false, 'message' => 'You dont have all that money']);
                } else {
                    $check = +$user->money - +$request['money'];
                    $user->money = $check;
                    $user->save();
                }


                return response()->json(['success' => true, 'message' => 'Money deducted']);

    }

    public function historyTransFarMany(Request $request)
    {
        $user=auth('api')->user();
        if(!$user){
            return response()->json([
                'Success'=>false,
                'Massage'=>"Invalid token",
            ]);
        }
     return Withdraw_moneyResource::collection(transfer_many::where('user_id',$user->id)->get());

    }
    
    
     public function getCurrentDateTime(Request $request)
    {
        // // $currentDateTime = now(); // This retrieves the current date and time in Laravel's Carbon instance.
        //  $currentDateTime = Carbon::now('Africa/Cairo');
        // return response()->json([
        //     // 'datetime' => $currentDateTime->toDateTimeString(),
        //     'datetime' => $currentDateTime
        // ]);
        $timezone = 'Africa/Cairo';
        $url = "http://worldtimeapi.org/api/timezone/{$timezone}";

        try {
            $client = new Client();
            $response = $client->get($url);
            $data = json_decode($response->getBody(), true);

            $currentDateTime = $data['datetime'];

            return response()->json([
                'datetime' => $currentDateTime,
                'timezone' => $timezone,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Unable to fetch accurate time.',
            ], 500);
        }
    }
    
    public function userExpire()
    {
        
//         $date=Carbon::now()->format('Y-m-d');
         
         
         
//          $users=User::where('end_plan','<=',$date)->get();
         
         
          

// foreach ($users as $user) {
//     $user->update([
//         'start_plan'=>null,
//         'end_plan' => null,
//         'plan_id'=>1,
//         'Status_Plan'=>null,
//     ]);
// }

// return $users;
        
        
//         $usersWithEndPlan = User::where('email','zekeriyaaziz268@gmail.com')->first();


//   $usersWithEndPlan->update([
//               "start_plan"=> "2023-07-17",
//             "end_plan"=> "2023-08-16",
//             "plan_id"=> 5,
//      'Status_Plan'=>'paid',

            
//              ]);
// return $usersWithEndPlan;

//          $date=Carbon::now()->format('Y-m-d');
         
         
         
//          $users=User::where('end_plan','<=',$date)->get();
         
         
          

// foreach ($users as $user) {
//     $user->update([
//         'start_plan'=>null,
//         'end_plan' => null,
//         'plan_id'=>1,
//         'Status_Plan'=>null,
//     ]);
// }

// return $users;


// return response()->json(['message' => 'End plans updated successfully']);
        //  $usersWithEndPlan->update([
        //       "start_plan"=> "2023-08-04",
        //     "end_plan"=> "2023-09-04",
        //     "plan_id"=> 5
        //      ]);

// $users =User::where('created_at', '2023-07-13 00:00:00')->pluck('email','created_at')->toArray();
            // return response()->json($usersWithEndPlan);
            
            
            
        //     "id": 377,
        // "name": "ZAKARIA AZIZ",
        // "email": "zekeriyaaziz268@gmail.com",
        // "email_verified_at": "2023-07-16T15:24:50.000000Z",
        // "verified": "0",
        // "phone": "+90535884925",
        // "state": "user",
        // "plan_id": "5",
        // "Status_Plan": "paid",
        // "payment_method": "",
        // "banned": "0",
        // "start_plan": "2023-07-16",
        // "end_plan": "2023-08-15",
        // "comming_afflite": "C92A7SLO",
        // "percentage": null,
        // "discount": "0",
        // "affiliate_code": "HSHRARG7",
        // "affiliate_link": "https://upvela.page.link/59nqvNEuWw43fVKf7",
        // "fcm_token": null,
        // "image_profile": null,
        // "image_payment": null,
        // "number_points": "0",
        // "money": "0",
        // "number_of_user": "0",
        // "binanceApiKey": null,
        // "binanceSecretKey": null,
        // "deleted_at": null,
        // "created_at": "2023-07-16T15:21:16.000000Z",
        // "updated_at": "2023-07-16T16:30:30.000000Z"
 
    
    }
    
     public function addValueToBinance(Request $request)
    {
        
            $user=auth('api')->user();
            if (!$user) {
    return response()->json([
        'success'=>false,
        'message' => 'Unauthorized'
        ], 401); // Return a response indicating unauthorized access
}

         
        
        
        // $userId=auth('api')->user()->id;
        // Validate the input
        $request->validate([
            'binanceApiKey' => 'required',
            'binanceSecretKey' => 'required',
        ]);
        
         $user->update([
             'binanceApiKey' => $request->input('binanceApiKey'),
                'binanceSecretKey' => $request->input('binanceSecretKey'),
             ]);
             
        return response()->json([
            'success'=>true,
            'message' => 'Value added to records successfully']);

        // // Update the records
        // User::where('id',$userId ) // Replace with the actual IDs of the records you want to update
        //     ->update([
        //         'binanceApiKey' => $request->input('binanceApiKey'),
        //         'binanceSecretKey' => $request->input('binanceSecretKey'),
        //     ]);

        // // Return a success response
        // return response()->json(['message' => 'Value added to records successfully']);
    }
    
        public function binanceTransaction(Request $request)
    {
        
        $user = auth('api')->user();

        // Validate the incoming request (add more validation as needed)
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Upload and save the image
        if ($request->hasFile('image')) {
            $img = time() . '.' . $request->image->extension();
            $imagePath = $request->image->move(public_path('ImagePaymentBinance'), $img);
        }else{
            return response()->json([
                'Success'=>false,
                'Massage'=>"Not Uploaded Image",
            ]);
        }

        // Create a new image submission
        ImageSubmissionBinance::create([
            'user_id' => $user->id,
            'image' => $imagePath,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Image submitted successfully'], 201);
    }

    public function binanceTransactionUsers(Request $request)
    {
        $user=auth('api')->user();
        // Make validaiton on user authirty
        if($user->state == 'super_admin' || $user->state == 'admin'){
            $users = User::whereHas('transaction_binance')->get();
            return response()->json(['users' => $users], 200);
        }
        return response()->json([
            'Success'=>false,
            'Massage'=>"Invalid Authirty",
        ]);
    }

    public function acceptImageBinance(Request $request, $ImageSubmissionBinanceId)
    {
        // Find the image submission
        $imageSubmission = ImageSubmissionBinance::findOrFail($ImageSubmissionBinanceId);
        $user=auth('api')->user();
        // Make validaiton on user authirty
        if($user->state == 'super_admin' || $user->state == 'admin'){
            $imageSubmission->status = 'active';
            $imageSubmission->price = $request->input('price');
            $imageSubmission->save();

            return response()->json(['message' => 'Image accepted and price set successfully'], 200);
        }
        return response()->json([
            'Success'=>false,
            'Massage'=>"Invalid Authirty",
        ]);
    }

    public function cancelImageBinance(Request $request, $ImageSubmissionBinanceId)
    {
        // Find the image submission
        $imageSubmission = ImageSubmissionBinance::findOrFail($ImageSubmissionBinanceId);
        $user=auth('api')->user();
        // Make validaiton on user authirty
        if($user->state == 'super_admin' || $user->state == 'admin'){
            $imageSubmission->status = 'cancel';
            $imageSubmission->save();

            return response()->json(['message' => 'Image cancel successfully'], 200);
        }
        return response()->json([
            'Success'=>false,
            'Massage'=>"Invalid Authirty",
        ]);
    }
    
    
    
    public function crybto(Request $request)
    {
        $client = new Client();

            
            $response = $client->post('https://crypto.smartidea.tech/api/get', [
        'form_params' => [
            'email' => $request->email,
             
         ]
    ]);
    
     $statusCode = $response->getStatusCode();
   return $content = $response->getBody()->getContents();

        return 111;
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
}
    
    
    
    
    
    
    
    
    
    
    
    
    

 
 
