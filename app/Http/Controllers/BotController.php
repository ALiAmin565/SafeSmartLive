<?php

namespace App\Http\Controllers;

use App\Models\Bots;
use App\Models\User;
use App\Models\BotStatus;
use Illuminate\Http\Request;

class BotController extends Controller
{

    public function getAllUserBot()
    {
        $users = User::where('is_bot', 1)->get();
        return response()->json([
            'users' => $users,
        ]);
    }
    // Update On User in colum is_bot
    public function updateBotUser(Request $request)
    {
        $user = auth('api')->user();
        if ($user->state != 'super_admin') {
            return response()->json([
                'message' => 'You are not authorized to perform this action'
            ], 401);
        }
        $user = User::find($request->id);
        $user->num_orders = $request->num_orders;
        $user->orders_usdt = $request->orders_usdt;
        $user->save();
        return response()->json([
            'message' => 'Bot User Updated Successfully'
        ]);
    }
    // Set Bot Status For user 
    public function setBotStatus(Request $request)
    {
        $user = auth('api')->user();
        if ($user->state != 'super_admin') {
            return response()->json([
                'message' => 'You are not authorized to perform this action'
            ], 401);
        }
        $user = User::find($request->id);
        $user->is_bot = 0;
        $user->save();
        return response()->json([
            'message' => 'Bot Status Updated Successfully'
        ]);
    }
    // Add Bot Status For user 
    public function AddBotStatuForUser(Request $request)
    {
        $user = auth('api')->user();
        if ($user->state != 'super_admin') {
            return response()->json([
                'message' => 'You are not authorized to perform this action'
            ], 401);
        }
        $user = User::find($request->id);
        $user->binanceApiKey= $request->binanceApiKey;
        $user->binanceSecretKey= $request->binanceSecretKey;
        $user->num_orders = $request->num_orders;
        $user->orders_usdt = $request->orders_usdt;
        $user->is_bot = 1;
        $user->save();
        return response()->json([
            'message' => 'Bot Created Successfully'
        ]);
    }
    // Get Bot Status
    public function getBotStatus()
    {
        $botStatus = BotStatus::all();
        return response()->json([
            'botStatus' => $botStatus->first()->is_active,
        ]);
    }
    // Update Bot Status
    public function updateBotStatus(Request $request)
    {
        $user = auth('api')->user();
        if ($user->state != 'super_admin') {
            return response()->json([
                'message' => 'You are not authorized to perform this action'
            ], 401);
        }
        $botStatus = BotStatus::find(1);
        if ($botStatus->is_active)
            $botStatus->is_active = 0;
        else
            $botStatus->is_active = 1;
        $botStatus->save();
        return response()->json([
            'botStatus' => $botStatus->first()->is_active,
        ]);
    }

    // getAllHavingBots
    public function getAllHavingBots()
    {
        return response()->json([
            'bots' => Bots::all(),
        ]);
    }
}
