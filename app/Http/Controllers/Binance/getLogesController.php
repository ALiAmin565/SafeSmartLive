<?php

namespace App\Http\Controllers\Binance;

use App\Http\Controllers\Controller;
use App\Models\binance;
use Illuminate\Http\Request;

class getLogesController extends Controller
{
    public function index()
    {
        $binance = binance::with('user')->get();
        return response()->json($binance);
    }

    public function deleteLoges($id)
    {
        $loges = Binance::find($id);

        if (!$loges) {
            return response()->json(['message' => 'Request not found'], 404);
        }

        $loges->delete();

        return response()->json(['success' => true, 'message' => 'deleted']);
    }
}
