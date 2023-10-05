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
        $this->user = auth('api')->user();
    }

    public function buy(Request $request)
    {




        try {
            // Retrieve input data
            $symbol = $request->input('symbol'); // Name of Currency
            $side = $request->input('side');    // Buy Or sell
            $quantity = $request->input('quantity'); // Quantity of currency
            $price = $request->input('price'); // Current price from Binance
            $stopPrice = $request->input('stop_price');
            $recomindation_id = $request->input('recomindation_id');

            // Fetch exchange info from Binance
            $exchangeInfo = Http::get('https://api.binance.com/api/v3/exchangeInfo')->json();

            // Find the filters for the specified symbol
            $symbolInfo = collect($exchangeInfo['symbols'])->first(function ($item) use ($symbol) {
                return $item['symbol'] === $symbol;
            });

            if (!$symbolInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Symbol not found.',
                ]);
            }

            // Extract the quantity filter (LOT_SIZE) and price filter (PRICE_FILTER)
            $quantityFilter = collect($symbolInfo['filters'])->first(function ($filter) {
                return $filter['filterType'] === 'LOT_SIZE';
            });

            $priceFilter = collect($symbolInfo['filters'])->first(function ($filter) {
                return $filter['filterType'] === 'PRICE_FILTER';
            });

            // Get the minimum and maximum allowed prices and quantities
            $minPrice = $priceFilter['minPrice'];
            $maxPrice = $priceFilter['maxPrice'];
            $minQuantity = $quantityFilter['minQty'];
            $maxQuantity = $quantityFilter['maxQty'];

            // Validate the price and quantity against the filters
            if ($price < $minPrice || $price > $maxPrice) {
                return response()->json(['success' => false, 'message' => 'Price is out of range']);
            }

            // Adjust the quantity to meet the LOT_SIZE constraints
            $stepSize = $quantityFilter['stepSize'];
            $quantity = max($minQuantity, ceil($quantity / $stepSize) * $stepSize);

            // Validate the adjusted quantity against the minimum and maximum allowed values
            if ($quantity < $minQuantity || $quantity > $maxQuantity) {
                return response()->json(['success' => false, 'message' => 'Quantity is out of range']);
            }

            // Continue with your order placement logic here
            $timestamp = $this->timestampBinance();
            $signature = $this->hashHmac($symbol, $side, $quantity, $price, $stopPrice, $timestamp);

            $responseData = $this->sendOrderRequest($symbol, $side, $quantity, $price, $stopPrice, $timestamp, $signature);

            $request['orderID'] = $responseData['orderId'];
            $request['status'] = $responseData['status'];
            $test = $this->insertTransaction($request);

            return response()->json([
                'success' => true,
                'responseData' => $responseData,
            ]);
        } catch (ClientException $e) {
            return $this->handleBinanceError($e, $request);
        } catch (\Exception $e) {
            return $this->handleInternalError($e, $request);
        }
    }


    public function placeOrder(Request $request)
    {
        $symbol = $request->input('symbol'); // Symbol (e.g., "BTCUSDT")
        $side = $request->input('side'); // "BUY" or "SELL"
        $quantity = $request->input('quantity'); // Quantity of the asset
        $price = $request->input('price'); // Price at which to buy or sell

        // Retrieve trading pair information
        $exchangeInfo = Http::get('https://api.binance.com/api/v3/exchangeInfo')->json();

        // Find the filters for the specified symbol
        $symbolInfo = collect($exchangeInfo['symbols'])->first(function ($item) use ($symbol) {
            return $item['symbol'] === $symbol;
        });

        // Verify price and quantity against the filters
        $priceFilter = collect($symbolInfo['filters'])->first(function ($filter) {
            return $filter['filterType'] === 'PRICE_FILTER';
        });

        $quantityFilter = collect($symbolInfo['filters'])->first(function ($filter) {
            return $filter['filterType'] === 'LOT_SIZE';
        });

        // Check if the price and quantity are within acceptable limits
        $minPrice = $priceFilter['minPrice'];
        $maxPrice = $priceFilter['maxPrice'];
        $minQuantity = $quantityFilter['minQty'];
        $maxQuantity = $quantityFilter['maxQty'];

        if ($price < $minPrice || $price > $maxPrice) {
            return response()->json(['success' => false, 'message' => 'Price is out of range']);
        }

        if ($quantity < $minQuantity || $quantity > $maxQuantity) {
            return response()->json(['success' => false, 'message' => 'Quantity is out of range']);
        }

        // Continue with placing the order
        // ...

        return response()->json(['success' => true, 'message' => 'Order placed successfully']);
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
