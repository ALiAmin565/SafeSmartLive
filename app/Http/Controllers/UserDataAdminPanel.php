<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\recommendation;
use App\Http\Resources\RecommendationResource;

class UserDataAdminPanel extends Controller
{
    public function UserCount()
    {
        $userCount = User::where('state','=','user')->count();
        return response()->json([
            'data' => $userCount
        ]);
    }
    
        public function AdminCount()
    {
        $userCount = User::where('state','=','admin')->count();
        return response()->json([
            'data' => $userCount
        ]);
    }
    
    
    public function AdvicesCount()
    {
        $recmoCount=recommendation::count();
        return response()->json([
            'data' => $recmoCount
        ]);
    }
    
        public function UserCountBanned()
    {
          $userCount = User::where('state', '=', 'user')
        ->where('banned','=',1)
        ->count();
        return response()->json([
            'data' => $userCount
        ]);
    }
    
        public function LastPaymentCount()
    {
     $lastPayments = Payment::with('user','plan')->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        // $userNames = User::with('plan')->whereIn('id', $lastPayments->pluck('user_id'))
        //     ->get();
        return response()->json([
            'data' => $lastPayments,
            // 'userNames' => $userNames->pluck('name', 'id')->toArray(),
            // 'plans' => $userNames->pluck('plan.name', 'plan.id')->toArray()
        ]);
    }
    
       public function LastAdviceCount()
    {
         $lastRecommendation =   RecommendationResource::collection(recommendation::orderBy('created_at', 'desc')
        ->limit(5)
        ->get());
        return response()->json([
            'data' => $lastRecommendation,

        ]);
    }
}
