<?php

namespace App\Http\Controllers;

use App\Models\posts;
use App\Models\User;
use App\Models\plan;
use App\Events\recommend;
use GuzzleHttp\Client;

use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Requests\StorepostRequest;
use App\Http\Requests\UpdatepostRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
          $user = auth('api')->user()->load('role');
        if ($user->state == 'admin') {
            $planIds = $user->role->pluck('pivot')->pluck('plan_id');

        return PostResource::collection(posts::whereIn('plan_id', $planIds)->get());
        }else{
             return PostResource::collection(posts::get());
        }
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
    public function store(StorepostRequest $request)
    {
         $img=null;
        if ($request->hasFile('img')) {
            $img = time() . '.' . $request->img->extension();
            $path = $request->img->move(public_path('videosthumbnails'), $img);
        }

        // return $img;

        posts::create([
            'title' => $request['title'],
            'text' => $request['text'],
            'active' => $request['active'],
            'status'=>$request['status'],
            'img' => $img,
            "plan_id"=>$request['plan_id'],
            "link"=>$request['link'],
        ]);

             $plan = plan::where('id',$request['plan_id'])->first();

            event(new recommend($request->except('img'),$plan->nameChannel));
             $this->sendNotification($plan->name,$plan->nameChannel);


        return response()->json([
            'Massage' => "Request is Sucess",
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return  PostResource::make(posts::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatepostRequest $request, $id)
    {

        // return $request->all();
        $post = posts::find($id);

        if (!$post) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        if ($request->hasFile('img')) {
            $image_path = public_path('posts/' . $post->img);  // Value is not URL but directory file path
            if (file_exists($image_path)) {
                @unlink($image_path);
            }
            $img = time() . '.' . $request->img->extension();
            $request->img->move(public_path('posts'), $img);
        } else {
            $img = $post->img;
        }

        $post->update([
            'title' => $request['title'],
            'text' => $request['text'],
            'status' => $request['status'],
            'img' => $img,
        ]);

        return response()->json([
            'Massage' => "Request is Updated",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = posts::find($id);

        if (!$post) {
            return response()->json(['message' => 'Request not found'], 404);
        }
        $image_path = public_path('posts/' . $post->img);  // Value is not URL but directory file path
        if (file_exists($image_path)) {
            @unlink($image_path);
        }
        $post->delete();
        return response()->json(['message' => 'Request is delete']);
    }
     public function sendNotification($planName,$planNameChannel)
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
            'condition' => "'$planNameChannel' in topics",
            'notification' => [
                'title' => $planName,
                // 'body' => 'يوجد الان بوست جديد',
            ],
        ];



          $response = $client->post('send', [
            'json' => $message,
        ]);

        return $response;

        if ($response->getStatusCode() === 200) {
            return response()->json(['message' => 'Notification sent to all users.']);
        } else {
            return response()->json(['error' => 'Failed to send notification.'], $response->getStatusCode());
        }
    }
}
