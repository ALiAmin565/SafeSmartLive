<?php

namespace App\Http\Resources;

use App\Http\Resources\ImgPayResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // if (is_string($this->resource)) {
        //     // If the resource is a string, return it directly
        //     return $this->resource;
        // }

// dd(ImgPayResource::make($this->whenLoaded('imgPay')));
            return [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'Status_Plan'=>$this->Status_Plan,
                'selected_plan'=>PlanNameResource::make($this->whenLoaded('plan')),
                'imgPay'=>ImgPayResource::make($this->whenLoaded('imgPay')),

            ];


    }
}
