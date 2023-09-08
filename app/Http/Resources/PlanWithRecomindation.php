<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanWithRecomindation extends JsonResource
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

            'id' => $this->plan->id,
            'name' => $this->plan->name, // Access the 'name' attribute of the associated plan
            'nameChannel' => $this->plan->nameChannel, // Access other attributes as needed
            'discount' => $this->plan->discount,
            'price' => $this->plan->price,

        ];
    }
}
