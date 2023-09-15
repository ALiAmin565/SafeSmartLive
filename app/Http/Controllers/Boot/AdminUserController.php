<?php

namespace App\Http\Controllers\Boot;

use App\Models\plan;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;

class AdminUserController extends Controller
{
    public function getMyAdmin(Request $request)
    {
        $user = auth('api')->user();
        $bossIds = $user->admins; // Assuming $user->boss is the JSON-encoded array {"boss": [520, 600, 700]}

        // Decode the JSON data to an array
        $bossIdsArray = json_decode($bossIds, true);

    }
 


  
