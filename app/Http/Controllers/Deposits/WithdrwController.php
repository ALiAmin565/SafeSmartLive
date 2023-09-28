<?php

namespace App\Http\Controllers\Deposits;

use Log;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\ClientException;

class WithdrwController extends Controller
{
    public function withdraw($address, $mony)
    {



        $api_key = env('API_KEY_WITHDRWA');
        $api_secret = env('KEY_SECRT_WITHDRWA');


        $base_url = 'https://api.binance.com';

        // Data for the withdrawal request
        $data = [
            'coin' => 'USDT', // The asset symbol you want to withdraw
            'network' => 'TRX', //trc20
            'address' => $address, // Replace with the recipient's external wallet address
            'amount' => $mony,
            'timestamp' => $this->timestampBinance() // Timestamp for the request
        ];

        // Create a signature for the request
        $query_string = http_build_query($data);
        $signature = hash_hmac('sha256', $query_string, $api_secret);

        // Add the signature to the data
        $data['signature'] = $signature;

        try {
            // Make the API request
            $client = new Client();
            $response = $client->post("$base_url/sapi/v1/capital/withdraw/apply", [
                'headers' => [
                    'X-MBX-APIKEY' => $api_key,
                ],
                'form_params' => $data,
            ]);

            // Handle the response as needed
            $response_data = json_decode($response->getBody(), true);
            // You can check $response_data to ensure the withdrawal request was successful or handle errors.

            // Log the successful withdrawal request


            // Return a response to the user
            return response()->json($response_data);
        } catch (ClientException $e) {
            // Handle API errors
            $response = $e->getResponse();
            return $response_data = json_decode($response->getBody(), true);

            // Log the error response


            // Return an error response to the user
            return response()->json(['error' => 'Withdrawal request failed'], 500);
        }
    }

    public function getUSDTBalance()
    {


        try {
            $apiBaseUrl = 'https://api.binance.com';
            $endpoint = '/api/v3/account';

            // Binance API requires authentication
            $apiKey = 'f0gUx4ukrKXftiay0bihaBaNMYhV9wNUls4T7O4QbHgvr2xJYKeMaaNG8DL9RSP1';
            $apiSecret = 'r9u1KtFzjb5MyFNZgvWqyCMne8xiVuGWfQLK1WapbRyUKnUkNECmbSMwGNcbzbQA';

            // Create a timestamp for the request
            $timestamp = $this->timestampBinance();

            // Create a query string for the request
            $queryString = "timestamp={$timestamp}";

            // Create a signature for the request
            $signature = hash_hmac('sha256', $queryString, $apiSecret);

            // Make a GET request to Binance API with authentication
            $response = Http::withHeaders([
                'X-MBX-APIKEY' => $apiKey,
            ])->get($apiBaseUrl . $endpoint, [
                'timestamp' => $timestamp,
                'signature' => $signature,
            ]);

            // Check if the request was successful
            if ($response->successful()) {
                // Parse the JSON response
                $data = $response->json();

                // Find the USDT balance
                foreach ($data['balances'] as $balance) {
                    if ($balance['asset'] === 'USDT') {
                        return response()->json($balance['free']);
                    }
                }

                // If USDT balance is not found, return an error response
                return response()->json(['error' => 'USDT balance not found'], 404);
            } else {
                // Handle the error (e.g., log, return an error response, etc.)
                return response()->json(['error' => 'Failed to fetch data from Binance API'], $response->status());
            }
        } catch (\Exception $e) {
            // Handle exceptions, log the error, and return an error response
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    protected function timestampBinance()
    {
        $client = new Client();
        $response = $client->get('https://api.binance.com/api/v3/time');
        $serverTime = json_decode($response->getBody(), true);
        return $serverTime['serverTime'];
    }
}
