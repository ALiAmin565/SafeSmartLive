<?php

namespace App\Http\Controllers\Binance;

use App\Models\binance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class transactionController extends Controller
{
 
    public function getAllOrder(Request $request)
    {
         $user = auth()->user();

if ($user) {
    $binance = binance::where('user_id', $user->id)->get();
    
    return response()->json($binance);
} else {
    return response()->json(['message' => 'User not found'], 404);
}

      
    } 
}