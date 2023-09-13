<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tiker;

class TickerController extends Controller
{
    public function getAllTickers(Request $request)
    {
        return Tiker::get();
    }

    // Update on ticker
    public function updateTicker(Request $request)
    {
        $user = auth('api')->user();
        if ($user->state != 'super_admin') {
            return response()->json([
                'message' => 'You are not authorized to perform this action'
            ], 401);
        }
        $ticker = Tiker::where('ticker', $request->ticker_old)->first();
        $ticker->ticker = $request->ticker_new;
        $ticker->price = $request->price ? $request->price : $ticker->price;
        $ticker->save();
        return response()->json([
            'message' => 'Ticker Updated Successfully'
        ]);
    }
    // Delete ticker
    public function deleteTicker(Request $request)
    {
        $user = auth('api')->user();
        if ($user->state != 'super_admin') {
            return response()->json([
                'message' => 'You are not authorized to perform this action'
            ], 401);
        }
        $ticker = Tiker::where('ticker', $request->ticker)->first();
        $ticker->delete();
        return response()->json([
            'message' => 'Ticker Deleted Successfully'
        ]);
    }
    // Add ticker
    public function addTicker(Request $request)
    {
        $user = auth('api')->user();
        if ($user->state != 'super_admin') {
            return response()->json([
                'message' => 'You are not authorized to perform this action'
            ], 401);
        }
        $ticker = Tiker::where('ticker', $request->ticker)->first();
        if ($ticker) {
            return response()->json([
                'message' => 'Ticker Already Exists'
            ], 401);
        }
        $ticker = new Tiker();
        $ticker->ticker = $request->ticker;
        $ticker->price = $request->price ? $request->price : 0;
        $ticker->time= $request->time ? $request->time : 0;
        $ticker->save();
        return response()->json([
            'message' => 'Ticker Added Successfully'
        ]);
    }

}
