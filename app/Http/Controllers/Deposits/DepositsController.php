<?php


namespace App\Http\Controllers\Deposits;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\DepositsBinance;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\NotficationController;

class DepositsController extends Controller
{

    public function getDeposits()
    {


        $api_key = 'f0gUx4ukrKXftiay0bihaBaNMYhV9wNUls4T7O4QbHgvr2xJYKeMaaNG8DL9RSP1';
        $api_secret = 'r9u1KtFzjb5MyFNZgvWqyCMne8xiVuGWfQLK1WapbRyUKnUkNECmbSMwGNcbzbQA';

        // إعداد البيانات المطلوبة للتوقيع
         $timestamp = $this->timestampBinance();
        $params = [
            'timestamp' => $timestamp,
        ];
        $query = http_build_query($params);

        // إنشاء معرف التوقيع
        $signature = hash_hmac('sha256', $query, $api_secret);

        $client = new Client();
        $response = $client->get('https://api.binance.com/sapi/v1/capital/deposit/hisrec', [
            'headers' => [
                'X-MBX-APIKEY' => $api_key,
            ],
            'query' => $query . "&signature={$signature}", // إضافة معرف التوقيع إلى الاستعلام
        ]);

           $deposits = json_decode($response->getBody()->getContents());

        foreach ($deposits as $deposit) {
            $textid = trim(str_replace('Internal transfer', '', $deposit->txId));
            $mount = $deposit->amount;
            $network = $deposit->network;
            $this->insertDeposit($mount, $textid, $network, $user_id = 1); // Pass parameters here
        }
    }




    public  function createBinanceSignature($data, $apiSecret)
    {
        return hash_hmac('sha256', $data, $apiSecret);
    }





    protected function timestampBinance()
    {
        $client = new Client();
        $response = $client->get('https://api.binance.com/api/v3/time');
        $serverTime = json_decode($response->getBody(), true);
        return $serverTime['serverTime'];
    }





    public function insertDeposit($mount, $textid, $network, $user_id)
    {

        $existingDeposit = DepositsBinance::where('textId', $textid)->first();

        // If the record with the same textId exists, do not insert a new one
        if ($existingDeposit) {
            $body = "هناك خطا يرجي التاكد او هذه العنوان موجود سابقا ";
            $notfy = new NotficationController();
            $user = 'gg';
            $notfy->notfication($user, $body);

            return 'Duplicate';
        }


        $test = DepositsBinance::create([
            'amount' => $mount,
            'textId' => $textid,
            'network' => $network,
            'user_id' => $user_id,




        ]);

        return 'ok';
    }

    public function walteaddress(Request $request)
    {
        return response()->json([
            "success" => true,
            "wallet" => "TLmUhwJQuvGmBfYeURLb39Pwc9LD6REsuA"
        ]);
    }
}
