<?php

namespace App\Http\Controllers\Front;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HistoryWalteController extends Controller
{
    public function all(Request $request)
    {
         
        $user = auth('api')->user();
        
       return $user = User::with('imgPay')->find($user->id);
        // Retrieve the authenticated user

        if ($user) {
            $user->load('imgPay'); // Load the BuySellBinance relationship
        
            return $user;
        } else {
          
          return 'pp';
            // Handle the case where the user is not authenticated or not found.
        }
    }  
         
}
