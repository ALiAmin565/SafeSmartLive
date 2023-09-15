<?php

namespace App\Http\Controllers\TransactionUser;

use App\Models\User;
use Guzzle\Http\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\transactionUser;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\NotficationController;
use PhpParser\Node\Stmt\Return_;

class TransactionUserController extends Controller
{

    protected $notificationController;

    public function __construct(NotficationController $notificationController)
    {
        $this->notificationController = $notificationController;
    }


    public function oneToOne(Request $request)
    {



        // Get the authenticated user
        $user = auth('api')->user();
        $affiliateCode = $request->input('affiliateCode'); // Corrected variable name
        $amount = (int)$request->input('amount'); // Make sure it's an integer
        $receiver = User::where('affiliate_code', $affiliateCode)->first();

        if (!$receiver) {
            return response()->json([
                'success' => false,
                'message' => "Affiliate Code Not Found"
            ]);
        }

        // Check if the authenticated user has enough money
        if ($amount > $user->money) {
            return response()->json([
                'success' => false,
                'message' => "Not enough money"
            ]);
        }

        // Perform the transaction
        $user->money -= $amount;
        $receiver->money += $amount;
        $user->save();
        $receiver->save();
        $this->Store($user->id, $receiver->id,$receiver->name, $amount);



        // Call the notfication method
        $massageSend = "تم تحويل المبلغ بنجاح الي $receiver->name";
        $result = $this->notificationController->notfication($user->fcm_token, $massageSend);

        $massageRecived = "تم تحويل مبلغ وقدره $$amount من $user->name ";
        $results = $this->notificationController->notfication($receiver->fcm_token, $massageRecived);


        return response()->json([
            'success' => true,
            'message' => "Transaction successful"
        ]);
    } 

    public function mySelf(Request $request)
    {
        // Get the authenticated user
        $user = auth('api')->user();
        $amount = (int)$request->input('amount'); // Make sure it's an integer
        if ($amount > $user->money) {
            return response()->json([
                'success' => false,
                'message' => "Not enough money"
            ]);
        }

        $user->money -= $amount;
        $user->number_points += $amount;
        $user->save();




        $this->Store($user->id, $user->id,$user->name="Me",$amount);
        $massageSend = "تم تحويل المبلغ الي محفظتك بنجاح";
        $result = $this->notificationController->notfication($user->fcm_token, $massageSend);
        return response()->json([
            'success' => true,
            'message' => "Transaction successful"
        ]);
    }


    public function historyTransaction(Request $request)
    {
        $user = auth('api')->user();
        if ($user) {
            // Load the "BuySellBinance" and "DepositsBinance" relationships
            return $test = $user->load([
                'BuySellBinance', 
                'DepositsBinance',
                'allsendandrecive:id,user_id,name,amount'
            ]); 
    }
}


    public function Store($userId, $reciveId,$name, $amount)
    {
        $randomString = Str::random(20);
        $randomNumber = mt_rand(1000, 9999);
        $uniqueCode = $randomString . $randomNumber;

        $transactionUser = transactionUser::create([
            'user_id' => $userId,
            'recive_id' => $reciveId,
            'name'=>$name,
            'amount' => $amount,
        
            'transaction_id' => $uniqueCode,

        ]);
    }
}
