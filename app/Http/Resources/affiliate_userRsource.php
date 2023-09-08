<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class affiliate_userRsource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'number_of_user' => $this->number_of_user,
            'plan' => PlanNameResource::make($this->whenLoaded('plan'))
        ];
    }
}
