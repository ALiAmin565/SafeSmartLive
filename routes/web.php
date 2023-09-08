<?php

use App\Events\recommend;
use App\Models\recommendation;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecommendationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {


    return view('welcome');
});

    // Route::apiResource('Recommendation', RecommendationController::class);

 

//  Route::get('send',function(){



    //     $SERVER_API_KEY = 'API_SERVER_KEY';

    //     $channel = 'channel_name'; // Replace with the desired channel name

    //     $data = [
    //         'to' => '/topics/' . $channel,
    //         'notification' => [
    //             'title' => 'Welcome',
    //             'body' => 'Description',
    //             'sound' => 'default', // Required for sound on iOS
    //         ],
    //     ];

    //     $dataString = json_encode($data);

    //     $headers = [
    //         'Authorization: key=' . $SERVER_API_KEY,
    //         'Content-Type: application/json',
    //     ];

    //     $ch = curl_init();

    //     curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

    //     $response = curl_exec($ch);

    //     curl_close($ch);


    //     dd($response);

    // });

