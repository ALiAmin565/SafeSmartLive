<?php

namespace App\Http\Controllers;

use App\Models\plan;
use App\Models\plan_desc;

use App\Http\Resources\PlanResource;
use App\Http\Requests\StoreplanRequest;
use App\Http\Requests\UpdateplanRequest;
use Illuminate\Http\Request;

class PlanController extends Controller
{

    public function index()
    {
        return PlanResource::collection(plan::with(['telegram','plan_desc'])->get());
    }


    public function store(Request $request)
    {

        // return $request['descss'];
        $plan = plan::create($request->all());
        $planDescIds = $request->input('description', []);
        // Assuming 'descss' is an array of plan_desc IDs



        if (!empty($planDescIds)) {

            foreach ($planDescIds as $planDescId) {
                $tt = plan_desc::create([
                    'plan_id' => $plan['id'],
                    'desc' => $planDescId,

                ]);
            }
        }



        if ($request->has('telegram_id')) {
            $plan->telegram()->attach($request['telegram_id']);
        }

        return response()->json(['message' => 'Plan created successfully'], 201);
    }



    public function show($id)
    {
        $request = plan::with('telegram')->find($id);

        if (!$request) {
            return response()->json(['message' => 'Request not found'], 404);
        }
        return PlanResource::make($request);
    }


    public function update(Request $request, $id)
    {
        // return 150;
        //  $request->has('telegram_id');

         $requestss = plan::with('telegram')->find($id);
       

        if (!$requestss) {
            return response()->json(['message' => 'Request not found'], 404);
        }
        if ($request->has('telegram_id')) {

            $requestss->telegram()->sync($request['telegram_id']);
        } elseif ($request->has('telegram_id') == null) {
            return 150;
        }
        
     
        
     
 
 
$planDescIds = $request->input('description', []);

if (!empty($planDescIds)) {
    // Update or Add the related 'plan_desc' records for the 'plan'
    foreach ($requestss->plan_desc as $index => $planDesc) {
        $newDesc = $planDescIds[$index] ?? null; // Get the corresponding value from the $planDescIds array
        if ($newDesc !== null) {
            // Assuming 'column_name' is the actual column you want to update in 'plan_desc' table
            $planDesc->update(['desc' => $newDesc]);
        } else {
            // If the value is null, delete the related 'plan_desc' record
            $planDesc->delete();
        }
    }

    // Add any remaining new 'desc' values that were not matched with existing plan_desc records
    $newPlanDescIds = array_slice($planDescIds, count($requestss->plan_desc));
    foreach ($newPlanDescIds as $newDesc) {
        // Assuming 'column_name' is the actual column you want to set in 'plan_desc' table
        $requestss->plan_desc()->create(['desc' => $newDesc]);
    }
} else {
    // If the input is empty, delete all related 'plan_desc' records
    $requestss->plan_desc()->delete();
}
    
    
    
        $requestss->update($request->all());

        return response()->json([
            'Massage' => "Updated Success",

        ]);
    }


    public function destroy($id)
    {


        if ($id == 1) {

            return response()->json(['message' => 'Request deny'], 404);
        } else {

            $request = plan::find($id);
            $request->telegram()->detach();

            if (!$request) {
                return response()->json(['message' => 'Request not found'], 404);
            }
            $request->delete();

            return response()->json(['message' => 'Rquest is delete']);
        }
    }
}
