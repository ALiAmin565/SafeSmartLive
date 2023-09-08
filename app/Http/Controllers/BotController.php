<?php

namespace App\Http\Controllers;

use App\Models\BotStatus;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function getBotStatus()
    {
        $botStatus = BotStatus::all();
        return response()->json([
            'botStatus' => $botStatus->first()->is_active,
        ]);     
    }

    public function updateBotStatus(Request $request)
    {
        $user = auth('api')->user();
        if ($user->state != 'super_admin') {
            return response()->json([
                'message' => 'You are not authorized to perform this action'
            ], 401);
        }
        $botStatus = BotStatus::find(1);
        if($botStatus->is_active)
            $botStatus->is_active = 0;
        else
            $botStatus->is_active = 1;
        $botStatus->save();
        return response()->json([
            'botStatus' => $botStatus->first()->is_active,
        ]);
    }
}
