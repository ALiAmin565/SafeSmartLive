<?php

namespace App\Http\Controllers\TransactionUser;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\transactionUser;
use PhpParser\Node\Stmt\Return_;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Binance\buyController;
use App\Http\Controllers\Helper\NotficationController;

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
        $user->save();


        $receiver->number_points += $amount;

        $receiver->save();


        $this->Store($user->id, $receiver->id, $receiver->name, $amount);


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




        $this->Store($user->id, $user->id, $user->name = "Me", $amount);
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

        $sentTransactions = transactionUser::where('user_id', $user->id)->get();

        $receivedTransactions = transactionUser::where('recive_id', $user->id)->get();


        $sentTransactions->each(function ($transaction) {
            $transaction->transaction_type = 'sent';

            if ($transaction->user_id == $transaction->recive_id) {
                $transaction->send_name = "Me";
            } else {
                $transaction->send_name = User::select('name')->find($transaction->recive_id)->name;
            }
        });

        $receivedTransactions->each(function ($transaction) {
            $transaction->transaction_type = 'received';

            if ($transaction->user_id == $transaction->recive_id) {
                $transaction->receiver_name = 'Me';
            } else {
                $transaction->receiver_name = User::select('name')->find($transaction->user_id)->name;
            }
        });
        $mergedTransactions = $sentTransactions->concat($receivedTransactions);
        $mergedTransactions = $mergedTransactions->sortByDesc('created_at')->values();

        // for fess Bot it
        $is_Deposits = $user->DepositsBinance->each(function ($define) {
            $define->type = "is_Deposits";
        });
        $is_fess = $user->fessBot->each(function ($define) {
            $define->type = "is_fess";
            if ($define->number_bot == null) {
                $define->side = "plan";
            } else {
                $define->side = "bot";
            }
        });

        $fess = $is_Deposits->concat($is_fess);
        $sendfess = $fess->sortByDesc('created_at')->values();



        $client = new Client([
            'base_uri' => 'https://api.binance.com',
        ]);

        $buyController = new buyController($client);

        // Call the getstatsOrde method
        $buyController->getAllOrder($request);




          $user->load(['BuySellBinance', 'DepositsBinance', 'historypayment', 'fessBot']);


    return    $responseData = [
            'transactions' => $mergedTransactions,
            'historypayment' => $user->historypayment,
            'BuySellBinance' => $user->BuySellBinance,
            'DepositsBinance' => $sendfess,
        ];

    }




    public function Store($userId, $reciveId, $name, $amount)
    {

        $randomString = Str::random(20);
        $randomNumber = mt_rand(1000, 9999);
        $uniqueCode = $randomString . $randomNumber;

        $transactionUser = transactionUser::create([
            'user_id' => $userId,
            'recive_id' => $reciveId,
            'amount' => $amount,
            'transaction_id' => $uniqueCode,

        ]);
    }
}
