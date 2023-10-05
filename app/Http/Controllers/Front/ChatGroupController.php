<?php

namespace App\Http\Controllers\Front;

use App\Models\plan;
use App\Models\Massage;
use App\Events\ChatPlan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Front\MassageResource;

class ChatGroupController extends Controller
{
    // public function Massage(Request $request)
    // {

    //     $header = $request->header('Authorization');
    //     $user = auth('api')->user();

    //     if (!$user) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => "Invalid token",
    //         ]);
    //     }

    //     $massages = Massage::with(['user', 'media'])
    //         ->where('plan_id', $user->plan_id)
    //         ->orderBy('created_at', 'desc')
    //         ->paginate(15);

    //     return response()->json([
    //         'data' => MassageResource::collection($massages->items()),
    //         'meta' => [
    //             'current_page' => $massages->currentPage(),
    //             'last_page' => $massages->lastPage(),
    //             'per_page' => $massages->perPage(),
    //             'total' => $massages->total(),
    //         ],
    //     ]);
    // }
    public function Massage(Request $request)
    {

        // $header = $request->header('Authorization');
         $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => "Invalid token",
            ]);
        }

        $page = $request->input('page', 1); // Get the requested page from the request parameters

        $massages = Massage::with(['user', 'media'])
            ->where('plan_id', $user->plan_id)
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'page', $page); // Specify the desired page and the number of items per page

        return response()->json([
            'data' => MassageResource::collection($massages->items()),
            'meta' => [
                'current_page' => $massages->currentPage(),
                'last_page' => $massages->lastPage(),
                'per_page' => $massages->perPage(),
                'total' => $massages->total(),
            ],
        ]);
    }

    public function StoreMassage(Request $request)
    {

         $header = $request->header('Authorization');

        $user = auth('api')->user();

        $plan = plan::find($user->plan_id);


        $massage = Massage::create([
            'user_id' => $user->id,
            'plan_id' => $user->plan_id,
            'massage' => $request['massage'],
        ]);

        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('media'), $filename);

            $massage->media()->create([
                'img' => $filename,
            ]);
        }


        if ($request->hasFile('video')) {
            $image = $request->file('video');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('media'), $filename);

            $massage->media()->create([
                'video' => $filename,
            ]);
        }

        if ($request->hasFile('audio')) {
            $image = $request->file('audio');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('media'), $filename);

            $massage->media()->create([
                'audio' => $filename,
            ]);
        }

        event(new ChatPlan($massage, $plan->nameChannel));

        $lastMessage = Massage::with(['user', 'media'])
            ->where('plan_id', $user->plan_id)
            ->latest()
            ->first();



        return response()->json([
            'success' => true,
            'massage' => MassageResource::make($lastMessage),
        ]);
    }

    // public function massageAdmin(Request $request)
    // {

    // }
}
