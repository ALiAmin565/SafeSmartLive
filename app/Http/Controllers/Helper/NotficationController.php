<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotficationController extends Controller
{

    public function notfication($fcm, $body)
    {


        $serverKey = 'AAAAdOBidSQ:APA91bGf83SZcbSaGfybST4Z7y1RHqHV0h1yKgMlB-p09IErYNDo2HXkYiq5aW-iVjgDMQaSinWQNbnJF7vs5m-JPMoILRjoX8kdezLNj54i8gcevawlskPuckqlI9NIxyMzAQKkADWk'; // Replace with your Firebase server key

        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://fcm.googleapis.com/fcm/',
            'headers' => [
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ],
        ]);

        $message = [
            'to' => $fcm,
            'notification' => [
                'title' => 'Upvale Notification',
                'body' => $body,
            ],
        ];

        try {
            $response = $client->post('send', ['json' => $message]);
            $statusCode = $response->getStatusCode();
            $responseBody = $response->getBody()->getContents();
        } catch (\Exception $e) {
        }
    }
}
