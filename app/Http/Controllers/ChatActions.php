<?php

namespace App\Http\Controllers;

use App\Models\plan;
use App\Models\User;
use App\Models\Massage;
use App\Events\closeChat;
use App\Traits\ResponseJson;




use Illuminate\Http\Request;
use App\Models\Massage_media;
use Illuminate\Support\Facades\DB;

class ChatActions extends Controller
{
    use ResponseJson;
    public function deletePlan(Request $request)
    {
        $nameChannel = $request['nameChannel'];
        $plan = plan::where('nameChannel', $nameChannel)->first();

        $massages = Massage::with('MassageMedia')->where('plan_id', $plan->id)->get();
        // Iterate through each massage and delete its associated media
        foreach ($massages as $massage) {
            $media = $massage->MassageMedia;
            $media->each->delete(); // Delete each associated media record
        }
        // Delete the massages
        Massage::where('plan_id', $plan->id)->delete();

        $eventMassage = "0";
        event(new closeChat($plan->nameChannel, $eventMassage));

        return $this->success('ResponseJson');
    }

    public function deleteAll()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        // Delete all records from the MassageMedia table
        Massage_media::truncate();
        // Delete all records from the Massage table
        Massage::truncate();
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        return 'All records deleted successfully.';
    }

    public function deleteMessageUser($id)
    {
        // Retrieve the authenticated user
        $user = auth('api')->user();
        // Find the message by ID
        $message = Massage::find($id);
        // Check if the message exists and if the user is the owner
        if ($message && $message->user_id === $user->id) {
            // Delete the associated message media, if any
            if ($message->messageMedia) {
                $messageMediaId = $message->messageMedia->id;
                $message->messageMedia()->whereIn('id', $messageMediaId)->delete();
            }
            // Delete the message
            $message->delete();
            return response()->json(['message' => 'Message and associated media deleted successfully.']);
        }
        // If the message doesn't exist or the user is not the owner, return an error
        return response()->json(['error' => 'Message not found or unauthorized.'], 404);
    }

    public function deleteMessageSuper($id)
    {
        // Find the message by ID
        $message = Massage::find($id);
        // Check if the message exists and if the user is the owner

        // Delete the associated message media, if any
        if ($message->messageMedia) {
            $messageMediaId = $message->messageMedia->id;
            $message->messageMedia()->whereIn('id', $messageMediaId)->delete();
        }
        // Delete the message
        $message->delete();
        return response()->json(['message' => 'Message and associated media deleted successfully.']);
    }


    public function banPlan(Request $request)
    {
        $nameChannel = $request['nameChannel'];
        $plan = plan::where('nameChannel', $nameChannel)->first();
        $users = User::where('plan_id', $plan->id)->get();

        foreach ($users as $user) {
            if ($user->banned == 0) {
                $user->banned = 2;
            } elseif ($user->banned == 1) {
                // Leave the record as it is
            }
            $user->save();
        }
        $eventMassage = '1';
        event(new closeChat($plan->nameChannel, $eventMassage));

        return response()->json(['success' => true]);
    }

    public function unbanPlan(Request $request)
    {
        $nameChannel = $request['nameChannel'];
        $plan = Plan::where('nameChannel', $nameChannel)->first();
        $users = User::where('plan_id', $plan->id)->get();

        foreach ($users as $user) {
            if ($user->banned == 1) {
                // Leave the record as it is
            } elseif ($user->banned == 2) {
                $user->banned = 0;
            } else {
                continue; // Skip updating if banned is already 0
            }

            $user->save();
        }
        $eventMassage = "1";
        event(new closeChat($plan->nameChannel, $eventMassage));

        return response()->json(['success' => true]);
    }
}
