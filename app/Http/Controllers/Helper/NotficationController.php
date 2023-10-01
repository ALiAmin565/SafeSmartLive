<?php

namespace App\Http\Controllers\Helper;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    public function notficationManger($body)
    {
        $user = User::where('id', 12)->first();

        $serverKey = 'AAAAdOBidSQ:APA91bGf83SZcbSaGfybST4Z7y1RHqHV0h1yKgMlB-p09IErYNDo2HXkYiq5aW-iVjgDMQaSinWQNbnJF7vs5m-JPMoILRjoX8kdezLNj54i8gcevawlskPuckqlI9NIxyMzAQKkADWk'; // Replace with your Firebase server key

        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://fcm.googleapis.com/fcm/',
            'headers' => [
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ],
        ]);

        $message = [
            'to' => $user->fcm_token,
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


    public function notficatopnPlan($id)
    {
        $users = User::where('plan_id', $id)->get();

        $serverKey = 'AAAAdOBidSQ:APA91bGf83SZcbSaGfybST4Z7y1RHqHV0h1yKgMlB-p09IErYNDo2HXkYiq5aW-iVjgDMQaSinWQNbnJF7vs5m-JPMoILRjoX8kdezLNj54i8gcevawlskPuckqlI9NIxyMzAQKkADWk'; // Replace with your Firebase server key

        $client = new \GuzzleHttp\Client([
            'base_uri' => 'https://fcm.googleapis.com/fcm/',
            'headers' => [
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ],
        ]);



        foreach ($users as $user) {
            $message = [
                'to' => $user->fcm,
                'notification' => [
                    'title' => 'Upvale Notification',
                    'body' => $user->body,
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

    public function allPlanForBot($botNam, $ticker, $target)
    {

         $serverKey = 'AAAAdOBidSQ:APA91bGf83SZcbSaGfybST4Z7y1RHqHV0h1yKgMlB-p09IErYNDo2HXkYiq5aW-iVjgDMQaSinWQNbnJF7vs5m-JPMoILRjoX8kdezLNj54i8gcevawlskPuckqlI9NIxyMzAQKkADWk'; // Replace with your Firebase server key

        $client = new Client([
            'base_uri' => 'https://fcm.googleapis.com/fcm/',
            'headers' => [
                'Authorization' => 'key=' . $serverKey,
                'Content-Type' => 'application/json',
            ],
        ]);

        $message = [
            'condition' => "'all' in topics",
            'notification' => [
                'title' => "Upvale Bots",
                'body' => "مبررروك لكل المشتركين ف بوت  ال $botNam
                تم تحقيق الهدف $target من عمله $ticker
                "
            ],
        ];

        $response = $client->post('send', [
            'json' => $message,
        ]);

        if ($response->getStatusCode() === 200) {
            return response()->json(['message' => 'Notification sent to all users.']);
        } else {
            return response()->json(['error' => 'Failed to send notification.'], $response->getStatusCode());
        }
    }
}
