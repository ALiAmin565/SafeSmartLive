<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use App\Models\User;
use App\Models\expert;
use App\Models\binance;
use App\Models\recommendation;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResourceAdmin extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $recommendations = $this->recommendation->where('created_at', '>=', '2023-10-06')
            ->where('created_at', '<=', now());
        $recommendationIds = $this->recommendation->where('created_at', '>=', '2023-10-06')
            ->where('created_at', '<=', now())->pluck('id')->toArray();
        // Get All Recommendation from date 6-10-2023
        $activeRecommendation = expert::whereIn('recomondations_id', $recommendationIds)->where('status', -1)->get();
        $getFees =  binance::whereIn('recomondations_id', $recommendationIds)->pluck('fees')->toArray();
        $getSumFees = array_sum($getFees);
        $reachFirstTarget = expert::whereIn('recomondations_id', $recommendationIds)->where('status', -1)->where('last_tp', 1)->get();
        $reachSecondTarget = expert::whereIn('recomondations_id', $recommendationIds)->where('status', -1)->where('last_tp', 2)->get();
        $loseTarget = expert::whereIn('recomondations_id', $recommendationIds)->where('status', -1)->where('last_tp', 0)->get();
        $users = User::pluck('admins')->toArray(); // Assuming you already have this array
        $filterIds = [];
        foreach ($users as $key => $user) {
            if ($user !== null) {
                // Convert the JSON string to an array
                $userData = json_decode($user, true);

                // Check if the "boss" array exists in the user's data
                if (isset($userData['boss'])) {
                    //   Check the values in array equal $this->id or not 
                    if (in_array($this->id, $userData['boss'])) {
                        //    add to array
                        $filterIds[] = $this->id;
                    }
                }
            }
        }
        $count = count($filterIds);
        // Assuming $data contains the array you provided in your question
        return [
            'id' => $this->id,
            'name' => $this->name,
            'recommendation' => count($recommendations),
            'fees' => $getSumFees,
            'SubscribedPersonsCount' => $count,
            'activeRecommendation' => count($activeRecommendation),
            'reachFirstTarget' => count($reachFirstTarget),
            'reachSecondTarget' => count($reachSecondTarget),
            'loseTarget' => count($loseTarget),
        ];
    }
}
