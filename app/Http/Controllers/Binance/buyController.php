<?php

namespace App\Http\Controllers\Binance;

use Carbon\Carbon;
use GuzzleHttp\Client;

use App\Models\binance;
use App\Models\binanceUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\ClientException;
use App\Http\Controllers\transactionController;


class buyController extends Controller
{
    protected $client;
    protected $user;

    public function __construct(Client $client)
    {

        $this->client = new Client([
            'base_uri' => 'https://api.binance.com',
        ]);
        $this->user = auth()->user();
    }

    public function buy(Request $request)
    {


        try {

            $symbol = $request->input('symbol'); // Name of Cruency
            $side = $request->input('side');    // Buy Or sell
            $quantity = $request->input('quantity'); //quantity of curency
            $price = $request->input('price'); // CURENT PRICE IS REALTIME from Binance
            $stopPrice = $request->input('stop_price');
            $recomindation_id = $request->input('recomindation_id');


            $timestamp = $this->timestampBinance();
            $signature = $this->hashHmac($symbol, $side, $quantity, $price, $stopPrice, $timestamp);

            $responseData = $this->sendOrderRequest($symbol, $side, $quantity, $price, $stopPrice, $timestamp, $signature);


            $request['orderID'] = $responseData['orderId'];
            $request['status'] = $responseData['status'];
            $test = $this->insertTransaction($request);


            return response()->json([
                'success' => true,
                'responseData' => $responseData,
                // 'time' => $diffInSeconds,
            ]);
        } catch (ClientException $e) {


            return $this->handleBinanceError($e, $request);
        } catch (\Exception $e) {
            return $this->handleInternalError($e, $request);
        }
    }

    protected function timestampBinance()
    {
        $response = $this->client->get('/api/v3/time');
        $serverTime = json_decode($response->getBody(), true);
        return $serverTime['serverTime'];
    }

    protected function hashHmac($symbol, $side, $quantity, $price, $stopPrice, $timestamp)
    {
        $query = http_build_query([
            'symbol' => $symbol,
            'side' => $side,
            'type' => 'LIMIT',
            'timeInForce' => 'GTC',
            'quantity' => $quantity,
            'price' => $price,
            'timestamp' => $timestamp,

        ]);
        return hash_hmac('sha256', $query, $this->user->binanceSecretKey);
    }

    protected function sendOrderRequest($symbol, $side, $quantity, $price, $stopPrice, $timestamp, $signature)
    {
        $response = $this->client->post('/api/v3/order', [
            'headers' => [
                'X-MBX-APIKEY' => $this->user->binanceApiKey,
            ],
            'form_params' => [
                'symbol' => $symbol,
                'side' => $side,
                'type' => 'LIMIT',
                'timeInForce' => 'GTC',
                'quantity' => $quantity,
                'price' => $price,
                'timestamp' => $timestamp,

            ],
            'query' => [
                'signature' => $signature,
            ],
        ]);


        return json_decode($response->getBody(), true);
    }

    protected function handleBinanceError(ClientException $e, Request $request)
    {
        $responseBody = json_decode($e->getResponse()->getBody(), true);
        $errorCode = $responseBody['code'] ?? null;
        $errorMessage = $responseBody['msg'] ?? 'Unknown error';
        $request['status'] = 'Error';
        $request['massageError'] = $errorMessage;
        $test = $this->insertTransaction($request);

        return response()->json([
            'success' => false,

            'error' => 'Binance API error',
            'message' => $errorMessage,
            'code' => $errorCode,
        ], $e->getCode());
    }

    protected function handleInternalError(\Exception $e, Request $request)
    {
        $request['status'] = 'Error';
        $request['massageError'] = 'Internal error';
        $test = $this->insertTransaction($request);
        return response()->json([
            'error' => 'Internal error',
            'message' => $e->getMessage(),
        ], 500); // Internal Server Error
    }

    public function insertTransaction(Request $request)
    {


        $insert = binanceUser::create([
            'user_id' => $this->user->id,
            'symbol' => $request['symbol'],
            'side' => $request['side'],
            'quantity' => $request['quantity'],
            'price' => $request['price'],
            'status' => $request['status'],
            'orderID' => $request['orderID'],
            'massageError' => $request['massageError'],
            'recomondations_id' => $request['recomondations_id']

        ]);
    }

    public function getAllOrder(Request $request)
    {
        $user = auth()->user();
        $binances = binanceUser::where('user_id', $user->id)
            ->where('status', 'NEW')
            ->get();

        foreach ($binances as $binance) {
            $status = $this->getStatusOrder(
                $user->binanceApiKey,
                $user->binanceSecretKey,
                $binance->symbol,
                $binance->orderID
            );

            // Update the status of the binance record in the database
            $binance->update(['status' => $status]);
        }

        // Retrieve the updated list of orders for the user
        $updatedBinances = binanceUser::where('user_id', $user->id)->get();

        return $updatedBinances;
    }

    public function getStatusOrder($apiKey, $apiSecret, $symbol, $orderId)
    {
        $timestamp = $this->timestampBinance();
        $signature = hash_hmac('sha256', "symbol=$symbol&orderId=$orderId&timestamp=$timestamp", $apiSecret);

        $response = Http::withHeaders([
            'X-MBX-APIKEY' => $apiKey,
        ])->get('https://api.binance.com/api/v3/order', [
            'symbol' => $symbol,
            'orderId' => $orderId,
            'timestamp' => $timestamp,
            'signature' => $signature,
        ]);

        $data = $response->json();

        // Check if the API response contains a 'status' field
        if (isset($data['status'])) {
            // Return the order status
            return $data['status'];
        } else {
            // Handle the case where the API response does not contain a 'status' field
            return 'Unknown';
        }
    }

    public function canselOrder()
    {

        $apiKey = 'L7EdS7jSzmUE5DmBtgCnDeTLpFsfymkGmjQ8iK4UWMywvLH7R8HuxEUq7Mqb8TO8';
        $apiSecret = 'HFTGudmK7N2dbdmtdBGbYs6JA1sPsFKcDyQVkYDbmwJvUX4ehj4onpxrnqSEcGgO';

        $symbol = 'GLMUSDT'; // Replace with your desired trading pair
        $orderId = 16719579; // Replace with the order ID you want to cancel



        $timestamp = $this->timestampBinance();
        $signature = hash_hmac('sha256', "symbol=GLMUSDT&orderId=$orderId&timestamp=$timestamp", $apiSecret);
        $response = Http::withHeaders([
            'X-MBX-APIKEY' => $apiKey,
        ])->delete('https://api.binance.com/api/v3/order', [
            'symbol' => 'GLMUSDT',
            'orderId' => $orderId,
            'timestamp' => $timestamp,
            'signature' => $signature,
        ]);

        return $response;

        $data = $response->json();

        // Check for the 'code' field in the API response to handle specific error codes.
        if (isset($data['code'])) {
            return response()->json(['error' => 'Binance API Error: ' . $data['msg']]);
        }

        // Check if the API response contains a 'msg' field
        if (isset($data['msg']) && $data['msg'] === 'canceled') {
            return response()->json(['status' => 'Order canceled']);
        } else {
            // Handle the case where the API response does not indicate successful cancellation
            return response()->json(['error' => 'Unable to cancel order']);
        }
    }
}
