<?php


namespace App\Http\Controllers\Deposits;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepositsController extends Controller
{
    public function getDeposits()
    {
        $api_key = 'f0gUx4ukrKXftiay0bihaBaNMYhV9wNUls4T7O4QbHgvr2xJYKeMaaNG8DL9RSP1';
        $api_secret = 'r9u1KtFzjb5MyFNZgvWqyCMne8xiVuGWfQLK1WapbRyUKnUkNECmbSMwGNcbzbQA';

        // إعداد البيانات المطلوبة للتوقيع
        $timestamp = time() * 1000;
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

        return  $deposits = json_decode($response->getBody()->getContents());

        foreach ($deposits as  $value) {

            return $value[''];
        }
    }

    public  function createBinanceSignature($data, $apiSecret)
    {
        return hash_hmac('sha256', $data, $apiSecret);
    }


}
