<?php

namespace App\Http\Controllers\Front;

use App\Models\plan;
use Illuminate\Http\Request;
use App\Models\recommendation;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Http\Resources\RecommendationResource;

class adminPlan extends Controller
{
    public function adminPlan()
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
}
