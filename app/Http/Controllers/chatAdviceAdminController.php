<?php

namespace App\Http\Controllers;

use App\Models\plan;
use App\Models\Massage;
use App\Models\posts;
use Illuminate\Http\Request;
use App\Models\recommendation;
use App\Models\plan_recommendation;
use App\Http\Requests\nameChannelRequest;
use App\Http\Resources\Front\MassageResource;
use App\Http\Resources\PlanResource;

use App\Events\ChatPlan;



class chatAdviceAdminController extends Controller
{
    public function chat(nameChannelRequest $request)
    {

          $plan = plan::where('nameChannel',$request['nameChannel'])->first();


        // $page = $request->input('page', 1); // Get the requested page from the request parameters

        $massages = Massage::with(['user', 'media'])
            ->where('plan_id', $plan->id)
            ->orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => MassageResource::collection($massages),

        ]);
    }
    public function Advice(nameChannelRequest $request)
     {

     $plan = Plan::where('nameChannel', $request['nameChannel'])->first();

    if ($plan) {
        $planRecommendations = plan_recommendation::where('planes_id', $plan->id)->pluck('recomondations_id');

        if ($planRecommendations->isNotEmpty()) {
            $recommendations = Recommendation::whereIn('id', $planRecommendations)->with(['target','Recommindation_Plan'])->get();
            $post = posts::where('status','is_advice','planes_id')
            ->where('plan_id', $plan->id)
            ->orderBy('created_at')
            ->get();
            // return $recommendations;
            // $combinedResult = collect([$recommendations, $post])
            // ->flatten()
            // ->sortBy(function ($item) {
            //     return strtotime($item->created_at);
            // })
            // ->values();

                   $combinedResult = collect([$recommendations, $post])
            ->flatten()
                        ->each(function ($item) {
        $item->created_at = date('Y-m-d H:i:s', strtotime($item->created_at . '+3 hours'));
          $item->updated_at = date('Y-m-d H:i:s', strtotime($item->updated_at . '+3 hours'));
    })
    ->sortBy(function ($item) {
        return strtotime($item->created_at);
    })->values();



            return response()->json([
            'data' => $combinedResult,

            ]);
        } else {
            return "No plan recommendations found for the given plan.";
        }
    } else {
        return "No plan found for the given nameChannel.";
    }




}
    public function adminForPlan()
        {
             $user = auth('api')->user();

            if ($user && $user->state == 'admin') {
                $user->load('role');
                $planIds = $user->role->pluck('pivot')->pluck('plan_id');
                return PlanResource::collection(Plan::whereIn('id', $planIds)->get());
            }elseif($user && $user->state == 'super_admin') {
                return PlanResource::collection(Plan::get());
            }else{
               return response()->json(['success' => 'false'], 404);
            }

        }


         public function StoreMassageAdmin(Request $request)
    {

             $user = auth('api')->user();
             $planChannelName = plan::where('nameChannel',$request->nameChannel)->first();
             $massage = Massage::create([
                'user_id' => $user->id,
                'plan_id' => $planChannelName->id,
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

                    event(new ChatPlan($massage, $planChannelName->nameChannel));
                    // $lastMessage = Massage::with(['user', 'media'])
                    // ->where('plan_id', $user->plan_id)
                    // ->latest()
                    // ->first();



                return response()->json([
                    'success' => true,
                    // 'massage' => MassageResource::make($lastMessage),
                ]);






    }
}
