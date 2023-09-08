<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\NotificationPlans;
use App\Models\plan;


class NotificationPlansController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
     {
        $allNotification=NotificationPlans::all();
        return response()->json([
            'data' => $allNotification,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user=auth('api')->user();
        foreach ($request->totalPlan as $plan) {
            NotificationPlans::create([
                'text' => $request->text,
                'user_id' => $user->id,
                'plan_id' => $plan,
            ]);
             $this->sendNotification($plan,$request->text);
        }
        return response()->json([
            'message' => 'Notification created successfully',
        ]);
    }

    public function sendNotification($plan,$text)
    {
             $plan = Plan::with('telegram')->where('id', $plan)->first();

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
                'title' => $plan->name,
                'body' => $text,
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
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NotificationPlans  $notificationPlans
     * @return \Illuminate\Http\Response
     */
    public function show(NotificationPlans $notificationPlans)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NotificationPlans  $notificationPlans
     * @return \Illuminate\Http\Response
     */
    public function edit(NotificationPlans $notificationPlans)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NotificationPlans  $notificationPlans
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NotificationPlans $notificationPlans)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NotificationPlans  $notificationPlans
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $notificationPlan=NotificationPlans::find($id);
        $notificationPlan->delete();
        return response()->json([
            'message' => 'Notification deleted successfully',
        ]);
    }
}
