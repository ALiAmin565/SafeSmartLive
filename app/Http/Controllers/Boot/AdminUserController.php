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

        // Fetch the user names based on the boss IDs
        $bossNames = User::whereIn('id', $bossIdsArray['boss'])->pluck('name')->toArray();

        return $bossNames;
    }

    public function getAllAdmin()
    {
         $user = auth('api')->user();

if (!$user) {
    return [
        'success' => false,
        'message' => 'Invalid token',
    ];
}

$userPlanId = $user->plan_id;
$bossIds = $user->admins;
$bossIdsArray = json_decode($bossIds, true);

try {
    $adminUsers = User::whereIn('id', $bossIdsArray['boss'])
        ->select('id', 'name', 'email')
        ->get();

    $Admins = Admin::with(['users:id,name,email'])
        ->where('plan_id', '<=', $userPlanId)
        ->get();

    // Use the unique method to filter out duplicates based on user_id
    $uniqueAdmins = $Admins->unique('user_id')->values();

    return [
        'allAdmin' => $uniqueAdmins,
        'myadmin' => $adminUsers,
    ];
} catch (\Exception $e) {
    return [
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage(),
    ];
}
}


    public function setAdmin(Request $request)
    {
         $user=auth('api')->user();

          $user->admins=$request['admins'];
          $user->save();

            return [
        'success' => true,
        'message' =>$user,
    ]; 


           
    }
}
